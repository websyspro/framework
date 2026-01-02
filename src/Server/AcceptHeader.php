<?php

/**
 * Class AcceptHeader
 *
 * Responsible for parsing the entire HTTP request context, including:
 * - HTTP method (GET, POST, etc.)
 * - Request URI and query string
 * - Content-Type and body parsing
 * - Uploaded files (multipart/form-data)
 * - Remote client information (IP and port)
 *
 * This class works as a lightweight HTTP Request parser,
 * similar to Laravel Request, ASP.NET Request or Express.js Request.
 *
 * @package Websyspro\Core\Server
 */

namespace Websyspro\Core\Server;

use Exception;
use Websyspro\Core\Server\Exceptions\Error;
use Websyspro\Core\Util;

class AcceptHeader
{
  /** @var string|null HTTP request method */
  public string|null $method = null;

  /** @var bool|null Indicates if request starts with /api */
  public bool|null $requestIsApi = false;

  /** @var array|string|null Parsed request URI segments */
  public array|string|null $requestUri = null;

  /** @var array|string|null Parsed query string */
  public array|string|null $requestQuery = null;

  /** @var array|string|null Request parameters */
  public array|string|null $requestParam = null;

  /** @var string|null Content-Type header */
  public string|null $contentType = null;

  /** @var string|null Boundary used in multipart/form-data */
  public string|null $contentBoundary = null;

  /**
   * Parsed request body:
   * [
   *   'fields' => array|object,
   *   'files'  => array
   * ]
   *
   * @var array|object|string|null
   */
  public array|object|string|null $contentBody = null;

  /** @var string|int|null Remote client port */
  public string|int|null $remotePort = null;

  /** @var string|null Remote client IP address */
  public string|null $remoteAddr = null;

  /**
   * AcceptHeader constructor.
   *
   * Initializes and parses all request information.
   */ 
  public function __construct() 
  {
    $this->acceptOptions();
    $this->acceptContentParse();
    $this->acceptContentBodyParse();
    $this->acceptRequestUriParse();
    $this->acceptRemoteParse();
  }

  public function requestUri(
  ): string {
    return implode( "/", $this->requestUri );
  }

  /**
   * Returns parsed query string parameters.
   *
   * @return array|object|string|null
   */
  public function query(
  ): array|object|string|null {
    if($this->isNotQuerys()){
      return [];
    }

    return $this->requestQuery[ "fields" ];
  }  

  /**
   * Returns parsed body fields.
   *
   * @return array|object|string|null
   */
  public function body(
  ): array|object|string|null {
    if($this->isNotFields()){
      return [];
    }

    return $this->contentBody["fields"];
  }

  /**
   * Returns parsed param fields.
   *
   * @return array|object|string|null
   */
  public function param(
  ): array|object|string|null {
    if($this->isNotParams()){
      return [];
    }

    return $this->requestParam["fields"];
  }
  
  /**
   * Defines and extracts route parameters from the request URI.
   *
   * This method parses a route definition that contains dynamic
   * parameters (e.g. :id{int}) and maps them to the actual values
   * extracted from the current request URI.
   *
   * The extracted parameters are stored internally in the
   * $this->requestParam array under the "fields" key.
   *
   * @param string $requestUri The route URI definition containing parameter placeholders.
   */  
  public function defineParam(
    string $requestUri,
  ): void {
    // Split $requestUri por "/"
    $requestUri = preg_split(
      "#/#", 
      $requestUri, 
      -1, 
      PREG_SPLIT_NO_EMPTY
    );

    // Mapper $requestUri path start ":"
    $requestUri = Util::mapper(
      $requestUri, 
      function( 
        string $path, int $index
      ): void {
        $hasParam = preg_match(
          "#^:.*#", $path
        ) === 1;

        if( $hasParam === true ){
          if($this->isNotParams()){
            $this->requestParam["fields"] = [];
          }
          
          $this->requestParam[ "fields" ][
            preg_replace( [ "#^:#", "#\{.*#" ], "", $path )
          ] = $this->defineParamFromType( 
            $this->requestUri[ $index ], 
            preg_replace( [ "#^.*{#", "#\}$#" ], "", $path )
          );
        }
      }
    );
  }

  /**
   * Returns uploaded files.
   *
   * @return array|object|string|null
   */  
  public function files(
  ): array|object|string|null {
    if( $this->isNotFiles() ){
      return [];
    }

    return $this->contentBody[ "files" ];
  }  

  /**
   * Compares the current HTTP method.
   *
   * @param string|null $method
   * @return bool
   */
  public function compareMethod(
    string|null $method = null
  ): bool {
    return $this->method === $method;
  }

  /**
   * Compares the current URI against a route pattern.
   * Supports dynamic parameters using ":" notation.
   *
   * Example:
   *   /users/:id
   *
   * @param string|array|null $requestUri
   * @return bool
   */
  public function compareUri(
    string|array|null $requestUri = null
  ): bool {
    // Check is $requestUri null
    if( $requestUri === null ){
      return false;
    }

    // Split $requestUri por "/"
    $requestUri = preg_split(
      "#/#", 
      $requestUri, 
      -1, 
      PREG_SPLIT_NO_EMPTY
    );

    // Check is sizeof($requestUri)
    if( $this->isEqualRequestUriPaths( $requestUri )){
      return false;
    }

    // Mapper $requestUri path start ":"
    $requestUri = Util::mapper(
      $requestUri, fn(string $path) => (
        str_starts_with( $path, ":") ? "*" : $path
      )
    );

    // Check is paths equals
    $compareUri = Util::mapper( 
      $requestUri, fn( string $path, int $i ) => (
        str_starts_with( $path, "*") === false 
          ? $path === $this->requestUri[ $i ] : true
      )
    );

    // Filter is values true
    $compareUri = Util::where( 
      $compareUri, fn(bool $bool) => $bool === false
    );

    // Is SUM $compareUri for equal or >1 
    return Util::exist( $compareUri ) === false;
  }

  /**
   * Prepends the API base path to the given URI when the request
   * is coming from the API context.
   *
   * If the request is identified as an API request, the method
   * prefixes the URI with "api/".
   * Otherwise, it returns the URI unchanged.
   *
   * @param string $uri The original route URI.
   * @return string The adjusted URI with or without the API base path.
   */  
  public function acceptAPIBase(
    string $uri
  ): string {
    $uri = preg_replace( "#(^/)|(/$)#", "", $uri );
    return $this->requestIsApi ? "api/{$uri}" : $uri;
  }  

  /**
   * Checks if URI path sizes are different.
   *
   * @param array|null $requestUri
   * @return bool
   */  
  private function isEqualRequestUriPaths(
    array|null $requestUri = null
  ) : bool {
    return Util::size( $this->requestUri ) 
       !== Util::size( $requestUri );
  }

  /**
   * Converts a raw route parameter value based on its declared type.
   *
   * This method ensures that parameters extracted from the URI
   * are correctly typed and valid according to their definition.
   * 
   * Supported types:
   *   - int / integer
   *   - float
   *   - bool / boolean
   *   - string
   *   - uuid
   *
   * If the value does not match the expected type, a BadRequest
   * exception (HTTP 400) is thrown, indicating that the client
   * sent an invalid parameter.
   *
   * @param mixed  $value The raw value extracted from the URI.
   * @param string $type  The declared type of the route parameter.
   *
   * @return mixed The value converted to the appropriate type.
   *
   * @throws Exception If the value does not match the expected type.
   */  
  private function defineParamFromType(
    mixed $value,
    string $type
  ): mixed {
    switch( strtolower( $type )){
      case "int":
      case "integer":
        if( is_numeric( $value ) === false ){
          Error::BadRequest( "Expected int, got: {$value}" );
        }

        return (int)$value;
      case "float":
        if( is_numeric( $value ) === false ) {
          Error::BadRequest("Expected float, got: {$value}" );
        }

        return (float) $value;
      case "bool":
      case "boolean":
        return filter_var(
          $value, 
          FILTER_VALIDATE_BOOLEAN, 
          FILTER_NULL_ON_FAILURE
        ) ?? false;

      case "string":
        return (string) $value;

      case "uuid":
        if( preg_match('#^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$#i', $value) === 0 ) {
          Error::BadRequest( "Invalid UUID format: {$value}" );
        }

        return $value;
      default:
        return $value;
    }
  }

  /**
   * Checks if body fields are missing.
   *
   * @return bool
   */  
  private function isNotFields(
  ): bool {
    return Util::existVar(
      $this->contentBody, 
      "fields"
    ) === false;
  }

  /**
   * Checks if query string is missing.
   *
   * @return bool
   */   
  private function isNotParams(
  ): bool {
    return Util::isNull(
      $this->requestParam
    );
  }
  
  /**
   * Checks if query string is missing.
   *
   * @return bool
   */   
  private function isNotQuerys(
  ): bool {
    return Util::isNull(
      $this->requestQuery
    );
  }  

  /**
   * Checks if request has no uploaded files.
   *
   * @return bool
   */
  private function isNotFiles(
  ): bool {
    return Util::existVar(
      $this->contentBody, 
      "files"
    ) === false;
  }  

  /**
   * Loads request options from $_SERVER.
   */
  private function acceptOptions(
  ): void {
    [ "REQUEST_METHOD" => $this->method,
      "CONTENT_TYPE" => $this->contentType,
      "REQUEST_URI" => $this->requestUri,
      "REMOTE_PORT" => $this->remotePort,
      "REMOTE_ADDR" => $this->remoteAddr
    ] = $this->AcceptOptionsDefault();
  }

  /**
   * Defines default request options.
   *
   * @return array
   */
  private function AcceptOptionsDefault(
  ): array {
    return Util::merge(
      [ "CONTENT_TYPE" => null ], $_SERVER
    );
  }

  /**
   * Extracts boundary and normalizes Content-Type.
   */
  private function acceptContentParse(
  ): void {
    if( $this->contentType !== null ){
      $this->contentBoundary = preg_replace( "#^.*-#", "", $this->contentType );
      $this->contentType = preg_replace( "#;.*$#", "", $this->contentType );
    }
  }

  /**
   * Parses application/json body.
   *
   * @return array
   */ 
  private function contentTypeApplicationJson(
  ) : array {
    return [ "fields" => json_decode(
      file_get_contents( "php://input" )
    )];
  }  

  /**
   * Determines how to parse the request body
   * based on the Content-Type.
   */   
  private function ContentTypeFormDataParse(
    array $carr,
    array $data    
  ) : array {
    [ $formData ] = $data;

    $hasFile = preg_match( 
      "#filename#",
      $formData
    );

    if((bool)$hasFile === false){
      [ $formData, $_, 
        $content
      ] = $data;

      $carr[ "fields" ][
        preg_replace(
          "#.*\sname=\"([^\"]+)\".*#", 
          '$1', 
          $formData
        )
      ] = $content;
    } else {
      [ $formData, 
        $contentType
      ] = $data;

      $content = implode(
        "\r\n", 
        Util::slice( 
          $data, 
          3,  
          -1
        )
      );

      $carr[ "files" ][
        preg_replace(
          "#.*\sname=\"([^\"]+)\".*#", 
          '$1', 
          $formData
        )
      ] = new File(
        preg_replace( "#.*\sfilename=\"([^\"]+)\".*#", "$1", $formData ),
        preg_replace( "#^.*\:\s#", "$1", $contentType ),
        $content
      );
    }

    return $carr;
  }

  /**
   * @private ContentTypeFormData
   * 
   * @param none
   * @return none
   * **/  
  private function contentTypeFormData(
    array $formData = [],
    array $phpInputArr = []
  ): array {
    // Split file "php://input" with path #(\-{28}[0-9]{24})#
    $phpInputArr = preg_split(
      "#(\-{28}[0-9]{24})#",
      file_get_contents(
        "php://input"
      )
    );

    
    // mapper e break in form-data
    $formData = Util::mapper(
      $phpInputArr,
      fn(string $data) => (
        preg_split( "#\r\n#", $data )
      ), 
    );
    
    // array slice remove first element e last element
    $formData = Util::slice( 
      $formData,
      1, 
      bcsub( 
        \sizeof( $formData ), 
        2, 0
      )
    );

    // array mapper remove first element
    $formData = Util::mapper(
      $formData,
      fn(array $formData) => Util::slice(
        $formData, 1
      ), 
    );

    // array reduce 
    return Util::reduce(
      $formData,
      fn(
        array $carr,
        array $data
      ) => $this->ContentTypeFormDataParse( 
        $carr, $data 
      ), 
      []
    );
  }

  /**
   * @private ContentTypeFormUrlEncoded
   * 
   * @param array $formData
   * @return none
   * **/  
  private function contentTypeFormUrlEncoded(
    array $formData = [],
  ): array {
    parse_str( 
      file_get_contents(
        "php://input"
      ), $formData
    );

    return [ "fields" => $formData ];
  }

  /**
   * @private AcceptContentOuters
   * 
   * @param none
   * @return array
   * **/   
  private function acceptContentOuters(
  ): array {
    if(Util::isNull($this->contentType)){
      return [];
    }

    return [
      "files" => [
        "file" => new File(
          "file",
          $this->contentType,
          file_get_contents(
            "php://input"
          )
        )
      ]
    ];
  }

  /**
   * Determines how to parse the request body
   * based on the Content-Type.
   */
  private function acceptContentBodyParse(
  ): void {
    $this->contentBody = match($this->contentType){
      "application/json" => $this->contentTypeApplicationJson(),
      "multipart/form-data" => $this->contentTypeFormData(),
      "application/x-www-form-urlencoded" => $this->contentTypeFormUrlEncoded(),
        default => $this->acceptContentOuters()
    };
  }

  /**
   * Parses URI, query string and API request type.
   */
  private function acceptRequestUriParse(
  ): void {
    if( $this->requestUri !== null ){
      // define requestQuery
      if(preg_match( "#\?#", $this->requestUri ) === 1){
        parse_str( preg_replace(
          "#^.*\?#", 
          "", 
          $this->requestUri
        ), $this->requestQuery[ "fields" ] );
      }

      // define requestFromApi start with (/)api
      $this->requestIsApi = preg_match(
        "#^(/)api.*#",
        $this->requestUri
      ) === 1;

      // define requestUri paths
      $this->requestUri = preg_split( 
        "#/#", preg_replace( 
          "#(^/)|(/$)|(\?.*$)#",
          "", 
          $this->requestUri 
        ), -1, PREG_SPLIT_NO_EMPTY
      );
    }
  }

  /**
   * Normalizes remote port value.
   */ 
  private function acceptRemoteParse(
  ): void {
    if(empty($this->remotePort) === false){
      $this->remotePort = (int)$this->remotePort;
    }
  }
}