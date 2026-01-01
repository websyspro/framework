<?php

namespace Websyspro\Core\Server;

class Router
{
  public function __construct(
    public string $method,
    public mixed $fn
  ){}
}