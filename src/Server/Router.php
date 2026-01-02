<?php

namespace Websyspro\Core\Server;

class Router
{
  public function __construct(
    public string $method,
    public string $uri,
    public mixed $handler
  ){}

  public function uri(
  ): string {
    return $this->uri;
  }

  public function method(
  ): string {
    return $this->method;
  }  
}