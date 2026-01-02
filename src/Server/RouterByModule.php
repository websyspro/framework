<?php

namespace Websyspro\Core\Server;

use Websyspro\Core\Server\Decorations\Controller\AbstractEndpoint;
use Websyspro\Core\Server\Enums\HttpMethod;

class RouterByModule
{
  public function __construct(
    private AbstractEndpoint $endpoint,
    private string $method,
    private string $name,
    private string $uri
  ){}

  public function endpoint(
  ): AbstractEndpoint {
    return $this->endpoint;
  }  

  public function method(
  ): string {
    return $this->method;
  }

  public function name(
  ): string {
    return $this->name;
  }

  public function uri(
  ): string {
    return $this->uri;
  }  
}