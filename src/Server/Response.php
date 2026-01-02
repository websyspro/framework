<?php

/**
 * Class Response
 *
 * Responsible for sending HTTP responses to the client.
 * This class acts as a thin abstraction over PHP's
 * native HTTP response handling (SAPI-based).
 *
 * IMPORTANT:
 * - This implementation is intended to run behind a web server
 *   (Apache, Nginx, PHP built-in server).
 * - It does NOT build raw HTTP responses manually.
 */

namespace Websyspro\Core\Server;

use Exception;
use Websyspro\Core\Server\Enums\ContentType;
use Websyspro\Core\Server\Enums\HttpStatus;
use Websyspro\Core\Util;

class Response
{
  /**
   * Initializes a new Response instance.
   *
   * This constructor sets the default HTTP status code for the response.
   * By default, the status code is 200 (OK), but it can be overridden
   * when creating the instance.
   *
   * Example:
   *   $response = new Response();       // status 200 by default
   *   $response = new Response(404);    // status 404
   *
   * @param int $code The initial HTTP status code for the response.
   */  
  public function __construct(
    private int $code = 200
  ){}

  /**
   * Sends an HTTP response using PHP's native
   * header and response handling.
   *
   * This method:
   * - Sets the HTTP status code
   * - Sends the Content-Type header
   * - Sends the Content-Length header
   * - Outputs the response body
   * - Terminates script execution
   *
   * @param int $code HTTP status code
   * @param string $content Response body
   * @param string $contentType MIME type of the response
   * @return void
   */
  private function send(
    int $code,
    string $content,   
    string $contentType,
  ): void {
    // Calculates the response body length in bytes
    $contenLength = Util::sizeText( 
      value: $content
    );
    
    /* 
     * Sets the HTTP status code (handled by the SAPI)
     * Defines the response content type
     * Defines the response body length
     * Explicitly closes the connection after the response
     */
    http_response_code( response_code: $code );
    header( header: "Content-Type: {$contentType}" );
    header( header: "Content-Length: {$contenLength}" );
    header( header: "Connection: close" );

    /**
     * Outputs the response body and stops execution 
     */
    exit( $content );    
  }

  /**
   * Builds a JSON response payload based on the given value and HTTP status code.
   *
   * The "success" flag is determined by checking whether the provided
   * HTTP status code represents a successful response (2xx range).
   * Only explicitly allowed success status codes are considered valid.
   *
   * @param mixed $value The response content to be encoded as JSON.
   * @param int $code The HTTP status code (default: 200).
   * @return string The JSON-encoded response string.
   */  
  private function contentJson(
    mixed $value,
    int $code = 200    
  ): string {
    $success = HttpStatus::isSuccess( $code );
    return json_encode( [
      "success" => $success,
      "content" => $value,
    ]);
  }

  /**
   * Sets the HTTP status code for the response.
   *
   * This method assigns the given status code to the response
   * object and returns the current instance to allow method
   * chaining.
   *
   * Example:
   *   $response->status(200)->json($data);
   *
   * @param int $code The HTTP status code to set (e.g., 200, 404, 500).
   *
   * @return Response Returns the current Response instance for chaining.
   */
  public function status(
    int $code
  ): Response {
    $this->code = $code;
    return $this;
  }

  /**
   * Sends a JSON response.
   *
   * Automatically:
   * - Encodes the given value to JSON
   * - Sets the Content-Type to application/json
   * - Sends the response with the provided HTTP status code
   *
   * @param mixed $value Data to be JSON-encoded
   * @param int $code  HTTP status code (default: 200)
   * @return void
   */  
  public function json(
    mixed $value,
    int|null $code = null
  ): void {
    $this->send(
      code: $code ?? $this->code, 
      content: $this->contentJson( $value, $code ?? $this->code ),
      contentType: ContentType::JSON->value, 
    );
  }
}