<?php

namespace Websyspro\Core\Server;

use Websyspro\Core\Util;

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
    Util::mapper(
      $this->routers, 
      function(
        Router $router
      ) {
        if(is_callable($router->handler)){
          \call_user_func(
            $router->handler, ...[
              $this->response, $this->request
            ]
          );
        }
      }
    );

  
    // match(Util::countArgs( $handler )){
    //   1 => $handler($this->response),
    //   2 => $handler($this->request),
    //     default => $handler()
    // };    
  }
}