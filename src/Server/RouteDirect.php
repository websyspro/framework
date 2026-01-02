<?php

namespace Websyspro\Core\Server;

class RouteDirect
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