<?php

namespace Websyspro\Core\Server;

class HeaderAccept
{
  public string|null $method = null;
  public string|null $requestUri = null;
  public string|null $requestQuery = null;
  public string|null $contentType = null;
  public string|null $contentBoundary = null;
  public array|null $contentBody = null;
  public string|null $remotePort = null;
  public string|null $remoteAddr = null;
  public function __construct() 
  {
    $this->acceptArgs();
    $this->acceptContentType();
    $this->acceptContentBody();
    $this->acceptRequestUri();
  }

  private function acceptArgs(): void
  {
    [ "REQUEST_METHOD" => $this->method,
      "CONTENT_TYPE" => $this->contentType,
      "REQUEST_URI" => $this->requestUri,
      "REMOTE_PORT" => $this->remotePort,
      "REMOTE_ADDR" => $this->remoteAddr
    ] = $_SERVER;
  }

  private function acceptContentType(): void
  {
    if( $this->contentType !== null ){
      $this->contentBoundary = preg_replace( "#^.*-#", "", $this->contentType);
      $this->contentType = preg_replace( "#;.*$#", "", $this->contentType);
    }
  }

  public function acceptContentBody(): void
  {
    $this->contentBody = preg_split( 
      "#\r\n#", file_get_contents(
      "php://input"
    ));
  }

  private function acceptRequestUri(): void
  {
    if( $this->requestUri !== null ){
      $this->requestQuery = preg_replace( "#^.*\?#", "", $this->requestUri );
      $this->requestUri = preg_replace( "#(^/)|(/$)|(\?.*$)#", "", $this->requestUri );
    }
  }
}