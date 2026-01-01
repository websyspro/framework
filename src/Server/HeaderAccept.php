<?php

namespace Websyspro\Core\Server;

class HeaderAccept
{
  public string|null $method = null;
  public array|string|null $requestUri = null;
  public array|string|null $requestQuery = null;
  public array|string|null $requestParam = null;
  public string|null $contentType = null;
  public string|null $contentBoundary = null;
  public array|object|string|null $contentBody = null;
  public string|null $remotePort = null;
  public string|null $remoteAddr = null;

  public function __construct() 
  {
    $this->acceptOptions();
    $this->acceptContentParse();
    $this->acceptContentBodyParse();
    $this->acceptRequestUriParse();
  }

  /**
   * @public CompareMethod
   * 
   * @param string|null $method
   * @return bool
   * **/ 
  public function query(
  ): array|object|string|null {
    if( $this->isNotQuerys() ){
      return null;
    }

    return $this->requestQuery;
  }  

  /**
   * @public CompareMethod
   * 
   * @param string|null $method
   * @return bool
   * **/ 
  public function body(
  ): array|object|string|null {
    if( $this->isNotFields() ){
      return null;
    }

    return $this->contentBody[ "fields" ];
  }

  public function files(
  ): array|object|string|null {
    if( $this->isNotFiles() ){
      return null;
    }

    return $this->contentBody[ "files" ];
  }  

  /**
   * @public CompareMethod
   * 
   * @param string|null $method
   * @return bool
   * **/
  public function compareMethod(
    string|null $method = null
  ): bool {
    return $this->method === $method;
  }

  /**
   * @public CompareUri
   * 
   * @param string|null $requestUri
   * @return bool
   * **/
  public function compareUri(
    string|array|null $requestUri = null
  ): bool {
    // Check is $requestUri null
    if($requestUri === null){
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
    if($this->isEqualRequestUriPaths( $requestUri )){
      return false;
    }

    // Mapper $requestUri path start ":"
    $requestUri = array_map(
      fn(string $path) => (
        str_starts_with( $path, ":") ? "*" : $path
      ), $requestUri
    );

    // Check is paths equals
    $compareUri = array_map( 
      fn( string $path, int $i ) => (
        str_starts_with( $path, "*") === false 
          ? $path === $this->requestUri[ $i ] : true
      ), $requestUri, array_keys( $this->requestUri )
    );

    // Filter is values true
    $compareUri = array_filter( 
      $compareUri, fn(bool $bool) => $bool === true
    );

    // Is SUM $compareUri for equal or >1 
    return array_sum( $compareUri ) === 0 ? false : true;
  }

  /**
   * @private IsEqualRequestUriPaths
   * 
   * @param array|null $requestUri
   * @return bool
   * **/  
  private function isEqualRequestUriPaths(
    array|null $requestUri = null
  ) : bool {
    return \sizeof( $this->requestUri ) 
       !== \sizeof( $requestUri );
  }

  /**
   * @private IsNotFields
   * 
   * @param none
   * @return bool
   * **/   
  private function isNotFields(
  ): bool {
    return isset( $this->contentBody[ "fields" ]) === false;
  }

  /**
   * @private IsNotFields
   * 
   * @param none
   * @return bool
   * **/   
  private function isNotQuerys(
  ): bool {
    return $this->requestQuery === null;
  }  

  /**
   * @private isNotFiles
   * 
   * @param none
   * @return bool
   * **/ 
  private function isNotFiles(
  ): bool {
    return isset( $this->contentBody[ "files" ]) === false;
  }  

  /**
   * @private AcceptOptions
   * 
   * @param none
   * @return none
   * **/ 
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
   * @private AcceptOptionsDefault
   * 
   * @param none
   * @return array
   * **/   
  private function AcceptOptionsDefault(
  ): array {
    return array_merge(
      [ 
        "CONTENT_TYPE" => null
      ], $_SERVER
    );
  }

  /**
   * @private AcceptContentParse
   * 
   * @param none
   * @return array
   * **/
  private function acceptContentParse(
  ): void {
    if( $this->contentType !== null ){
      $this->contentBoundary = preg_replace( "#^.*-#", "", $this->contentType );
      $this->contentType = preg_replace( "#;.*$#", "", $this->contentType );
    }
  }

  /**
   * @private ContentTypeApplicationJson
   * 
   * @param none
   * @return array
   * **/  
  private function contentTypeApplicationJson(
  ) : array {
    return [
      "fields" => json_decode(
        file_get_contents( "php://input" )
      )
    ];
  }  

  /**
   * @private ContentTypeFormDataParse
   * 
   * @param array $car
   * @param array $data
   * @return none
   * **/   
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
        $contentValue
      ] = $data;

      $carr[ "fields" ][
        preg_replace(
          "#.*\sname=\"([^\"]+)\".*#", 
          '$1', 
          $formData
        )
      ] = $contentValue;
    } else {
      [ $formData, 
        $contentType
      ] = $data;

      $contentValue = implode(
        "\r\n", 
        \array_slice( 
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
        (float)bcdiv( \strlen( $contentValue ), 1024, 4 ),
        $contentValue
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
    array $formDataArr = [],
    array $phpInputArr = []
  ): array {
    // Split file "php://input" with path #(\-{28}[0-9]{24})#
    $phpInputArr = preg_split(
      "#(\-{28}[0-9]{24})#",
      file_get_contents(
        "php://input"
      )
    );

    $formData = array_map(
      fn(string $data) => (
        preg_split( "#\r\n#", $data )
      ), $phpInputArr
    );

    $formData = \array_slice( 
      $formData,
      1, 
      bcsub( 
        \sizeof( $formData ), 
        2, 0
      )
    );

    $formData = array_map(
      fn(array $formData) => array_slice(
        $formData, 1
      ), $formData
    );

    $formData = array_reduce(
      $formData,
      fn(
        array $carr,
        array $data
      ) => $this->ContentTypeFormDataParse( $carr, $data ), 
      []
    );

    return $formData;
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

    return [
      "fields" => $formData
    ];
  }

  /**
   * @private AcceptContentBodyParse
   * 
   * @param none
   * @return none
   * **/  
  private function acceptContentBodyParse(
  ): void {
    if( $this->contentType === "application/json" ){
      $this->contentBody = $this->contentTypeApplicationJson();
    } else if( $this->contentType === "multipart/form-data" ){
      $this->contentBody = $this->contentTypeFormData();
    } else if( $this->contentType === "application/x-www-form-urlencoded" ){
      $this->contentBody = $this->contentTypeFormUrlEncoded();
    }

    /*
    $this->contentBody = preg_split( 
      "#\r\n#", file_get_contents(
      "php://input"
    )); */
  }

  /**
   * @private AcceptRequestUriParse
   * 
   * @param none
   * @return none
   * **/
  private function acceptRequestUriParse(
  ): void {
    if( $this->requestUri !== null ){
      // Define requestQuery
      if(preg_match( "#\?#", $this->requestUri ) === 1){
        parse_str( preg_replace(
          "#^.*\?#", 
          "", 
          $this->requestUri
        ), $this->requestQuery );
      }

      // Define requestUri paths
      $this->requestUri = preg_split( 
        "#/#", preg_replace( 
          "#(^/)|(/$)|(\?.*$)#",
          "", 
          $this->requestUri 
        ), -1, PREG_SPLIT_NO_EMPTY
      );
    }
  }
}