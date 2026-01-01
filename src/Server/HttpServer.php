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

  /**
   * Initializes the core HTTP context objects.
   *
   * This constructor prepares and injects the main components
   * required to handle an HTTP request lifecycle:
   *
   * - AcceptHeader: Parses and normalizes request headers,
   *   content type, body, files, and client metadata.
   *
   * - Response: Provides methods to build and send HTTP responses
   *   (JSON, plain text, status codes, headers, etc.).
   *
   * - Request: Encapsulates request-specific data such as
   *   parameters, query strings, and body content.
   *
   * Default instances are created automatically, allowing
   * the server to operate without external dependency injection.
   *
   * @param AcceptHeader $acceptHeader Handles request header parsing
   * @param Response $response Responsible for response output
   * @param Request $request Represents the incoming request
   *
   * @return void
   */
  public function __construct(
    public AcceptHeader $acceptHeader = new AcceptHeader(),
    public Response $response = new Response(),
    public Request $request = new Request()
  ){}

  /**
   * Registers a GET route.
   *
   * This method is a convenience wrapper around addRouter(),
   * specifically for HTTP GET requests.
   *
   * It associates a URI pattern with a handler function
   * that will be executed when an incoming GET request
   * matches the route definition.
   *
   * @param string $uri URI pattern for the GET route
   * @param callable|null $handler  Callback executed when the route is matched
   *
   * @return void
   */  
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

  /**
   * Registers a new route definition in the router collection.
   *
   * This method creates a Router instance containing:
   *  - The HTTP method (GET, POST, PUT, DELETE, etc.)
   *  - The route URI pattern
   *  - The handler function responsible for processing the request
   *
   * The created Router object is appended to the internal
   * router list and will later be evaluated by the dispatcher.
   *
   * @param string $method HTTP method associated with the route
   * @param string $uri URI pattern of the route
   * @param callable|null $handler  Callback executed when the route is matched
   *
   * @return void
   */  
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

  /**
   * Iterates through all registered routes and executes
   * their handlers when applicable.
   *
   * This method acts as the main dispatcher of the router layer.
   * Each router entry is expected to contain a callable handler
   * responsible for processing the request and generating a response.
   *
   * The handler is invoked only if it is a valid callable.
   * When executed, the handler receives:
   *  - The current Response instance
   *  - The current Request instance
   *
   * @return void
   */  
  public function listen(
  ): void {
    Util::mapper(
      array: $this->routers, 
      fn: function(
        Router $router
      ): void {
        if( Util::isFN( fn: $router->handler )){
          Util::callUserFN( fn: $router->handler, args: [ 
            $this->response, $this->request 
          ]);
        }
      }
    );
  }
}