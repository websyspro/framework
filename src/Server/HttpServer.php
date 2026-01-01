<?php

namespace Websyspro\Core\Server;

use Websyspro\Core\Util;

class HttpServer
{
  public static array $routers = [];

  public function __construct(
    public AcceptHeader $acceptHeader = new AcceptHeader(),
    public Response $response = new Response(),
    public Request $request = new Request()
  ){}

  public function get(
    string $method,
    callable|null $handler = null
  ): HttpServer {
    match(Util::countArgs( $handler )){
      1 => $handler($this->response),
      2 => $handler($this->request),
        default => $handler()
    };

    return $this;
  }
  public function listen(
  ): void {}
}