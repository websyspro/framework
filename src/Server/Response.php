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

use Websyspro\Core\Server\Enums\ContentType;
use Websyspro\Core\Util;

class Response
{
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
  public function send(
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
    int $code = 200
  ): void {
    $this->send(
      code: $code, 
      content: json_encode( $value ),
      contentType: ContentType::JSON->value, 
    );
  }
}