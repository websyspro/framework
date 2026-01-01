<?php

namespace Websyspro\Core\Server;

class HttpServer
{
  /**
   * Summary of routers
   * @var array<int, Router>
   */
  public array $routers = [];

  public function __construct(
    public AcceptHeader $acceptHeader = new AcceptHeader(),
    public Response $response = new Response(),
    public Request $request = new Request()
  ){}

  public function get(
    string $uri,
    callable|null $handler = null
  ): void {
    $this->addRouter(
      handler: $handler,
      method: "GET",
      uri: $uri,
    );
  }

  private function addRouter(
    string $method,
    string $uri,
    callable|null $handler = null
  ): void {
    $this->routers[] = new Router(
      handler: $handler,
      method: $method,
      uri: $uri,
    );
  }

  public function listen(
  ): void {
    // match(Util::countArgs( $handler )){
    //   1 => $handler($this->response),
    //   2 => $handler($this->request),
    //     default => $handler()
    // };    
  }
}