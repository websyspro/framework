<?php

namespace Websyspro\Core\Server;

use Exception;
use Websyspro\Core\Server\Decorations\Controller\AbstractEndpoint;
use Websyspro\Core\Server\Enums\HttpMethod;
use Websyspro\Core\Server\Enums\HttpStatus;
use Websyspro\Core\Server\Exceptions\Error;
use Websyspro\Core\Server\Logger\Enums\LogType;
use Websyspro\Core\Server\Logger\Log;
use Websyspro\Core\Util;

/**
 * Core HTTP server class for routing and request handling.
 *
 * This class provides a minimalistic framework for defining HTTP routes,
 * handling requests, executing route handlers, and returning structured
 * JSON responses with proper HTTP status codes.
 *
 * It supports both CLI execution (for debugging/logging) and standard
 * API requests.
 */
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
   * @param Response $response Responsible for response output
   * @param Request $request Represents the incoming request
   *
   * @return void
   */
  public function __construct(
    private Response $response = new Response(),
    private Request $request = new Request(
      new AcceptHeader()
    )
  ){}

  /**
   * Registers or initializes modules for the application.
   *
   * This method takes an array of module class names and creates
   * instances of `HttpModule` for each one, passing the current
   * context (`$this`) to the module constructor.
   *
   * Example usage:
   *   $app->module([
   *       MyFirstModule::class,
   *       MySecondModule::class
   *   ]);
   *
   * Notes:
   *   - Only executes if the $modules array is not empty.
   *   - Uses `Util::mapper()` to iterate over the modules array.
   *
   * @param array $modules An array of module class names to register.
   */  
  public function module(
    array $modules = []
  ): void {
    if( Util::exist( $modules )){
      Util::mapper( $modules, fn(string $module) => (
        new HttpModule( $this, $module )
      ));
    }
  }

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
      HttpMethod::GET->value, $uri, $handler
    );
  }

  /**
   * Registers a POST route.
   *
   * This method is a convenience wrapper around addRouter(),
   * specifically for HTTP POST requests.
   *
   * It associates a URI pattern with a handler function
   * that will be executed when an incoming POST request
   * matches the route definition.
   *
   * @param string $uri URI pattern for the POST route
   * @param callable|null $handler  Callback executed when the route is matched
   *
   * @return void
   */
  public function post(
    string $uri,
    callable|null $handler = null
  ): void {
    $this->addRouter(
      HttpMethod::POST->value, $uri, $handler
    );
  }

  /**
   * Registers a PUT route.
   *
   * This method is a convenience wrapper around addRouter(),
   * specifically for HTTP PUT requests.
   *
   * It associates a URI pattern with a handler function
   * that will be executed when an incoming PUT request
   * matches the route definition.
   *
   * @param string $uri URI pattern for the PUT route
   * @param callable|null $handler  Callback executed when the route is matched
   *
   * @return void
   */
  public function put(
    string $uri,
    callable|null $handler = null
  ): void {
    $this->addRouter(
      HttpMethod::PUT->value, $uri, $handler
    );
  }

  /**
   * Registers a PATCH route.
   *
   * This method is a convenience wrapper around addRouter(),
   * specifically for HTTP PATCH requests.
   *
   * It associates a URI pattern with a handler function
   * that will be executed when an incoming PATCH request
   * matches the route definition.
   *
   * @param string $uri URI pattern for the PATCH route
   * @param callable|null $handler  Callback executed when the route is matched
   *
   * @return void
   */
  public function patch(
    string $uri,
    callable|null $handler = null
  ): void {
    $this->addRouter(
      HttpMethod::PATCH->value, $uri, $handler
    );
  }

  /**
   * Registers a DELETE route.
   *
   * This method is a convenience wrapper around addRouter(),
   * specifically for HTTP DELETE requests.
   *
   * It associates a URI pattern with a handler function
   * that will be executed when an incoming DELETE request
   * matches the route definition.
   *
   * @param string $uri URI pattern for the DELETE route
   * @param callable|null $handler  Callback executed when the route is matched
   *
   * @return void
   */
  public function delete(
    string $uri,
    callable|null $handler = null
  ): void {
    $this->addRouter(
      HttpMethod::DELETE->value, $uri, $handler
    );
  }

  /**
   * Registers a OPTIONS route.
   *
   * This method is a convenience wrapper around addRouter(),
   * specifically for HTTP OPTIONS requests.
   *
   * It associates a URI pattern with a handler function
   * that will be executed when an incoming OPTIONS request
   * matches the route definition.
   *
   * @param string $uri URI pattern for the OPTIONS route
   * @param callable|null $handler  Callback executed when the route is matched
   *
   * @return void
   */
  public function options(
    string $uri,
    callable|null $handler = null
  ): void {
    $this->addRouter(
      HttpMethod::OPTIONS->value, $uri, $handler
    );
  }

  /**
   * Registers a HEAD route.
   *
   * This method is a convenience wrapper around addRouter(),
   * specifically for HTTP HEAD requests.
   *
   * It associates a URI pattern with a handler function
   * that will be executed when an incoming HEAD request
   * matches the route definition.
   *
   * @param string $uri URI pattern for the HEAD route
   * @param callable|null $handler  Callback executed when the route is matched
   *
   * @return void
   */
  public function head(
    string $uri,
    callable|null $handler = null
  ): void {
    $this->addRouter(
      HttpMethod::HEAD->value, $uri, $handler
    );
  }
  
  /**
   * Delegates the URI normalization to the Request object,
   * applying the API base path when necessary.
   *
   * This method acts as a proxy, forwarding the URI to the
   * request layer, which decides whether the "api/" prefix
   * should be applied based on the current request context.
   *
   * @param string $uri The original route URI.
   * @return string The normalized URI, with or without the API base path.
   */  
  private function acceptAPIBase(
    string $uri
  ): string {
    return $this->request->acceptAPIBase( $uri );
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
    $this->routers[] = new RouteDirect(
      $method, $this->acceptAPIBase( $uri ), $handler
    );
  }

  /**
   * Adiciona uma nova rota ao conjunto de routers, associada a um módulo específico.
   *
   * @param AbstractEndpoint $endpoint O endpoint responsável por tratar a rota.
   * @param HttpMethod $httpMethod O método HTTP da rota (GET, POST, etc.).
   * @param string $name Nome identificador da rota.
   * @param string $uri URI da rota, que será processada por `acceptAPIBase`.
   *
   * Funcionalidade:
   * - Cria uma instância de `RouterByModule` com os parâmetros fornecidos.
   * - O método HTTP é convertido para seu valor (`$httpMethod->value`).
   * - A URI passa pelo método `$this->acceptAPIBase()` antes de ser registrada.
   * - Adiciona a nova rota ao array `$this->routers`.
   *
   * Observações:
   * - Esse método não retorna valor (`void`); apenas registra a rota internamente.
   */  
  public function addRouterByModule(
    string $controller,
    string $method,
    string $name,
    string $uri
  ): void {
    $this->routers[] = new Router(
      $controller, $method, $name, $this->acceptAPIBase( $uri )
    );
  }  

  /**
   * Determines if the current PHP execution is running in the CLI (Command Line Interface).
   *
   * This method checks the PHP SAPI (Server API) and returns true if
   * the script is being executed from the command line (e.g., `php index.php`),
   * or false if it is running via a web server (e.g., Apache, Nginx, PHP-FPM).
   *
   * @return bool True if running in CLI, false otherwise.
   */  
  private function isClient(
  ): bool {
    return strtolower( PHP_SAPI ) === "cli";
  }

  /**
   * Itera sobre todas as rotas registradas e faz o log de suas informações
   * somente se o contexto atual for de um cliente.
   *
   * Passos:
   * 1. Verifica se o contexto atual é de um cliente via `$this->isClient()`.
   * 2. Se for, percorre o array `$this->routers` usando `Util::mapper`.
   * 3. Para cada objeto `Router`, registra um log de depuração (`Log::debug`)
   *    com o método HTTP e a URI da rota.
   *
   * Observações:
   * - `Router $router` representa uma rota registrada, que deve ter métodos
   *   `method()` e `uri()` para retornar o tipo da requisição e o caminho.
   * - `LogType::controller` indica que o log está categorizado como relacionado
   *   a controllers.
   * - Esse método não retorna nada (`void`), apenas realiza logging.
   */  
  private function routersByClientInfors(
  ): void {
    if($this->isClient()){
      Util::mapper(
        $this->routers,
        function( RouteDirect|Router $router ) {
          Log::debug(
            LogType::controller, 
            "Route {$router->method()} {$router->uri()}"
          );
        }
      );
    }
  }

  /**
   * Filters the registered routers by HTTP method.
   *
   * This method narrows down the list of available routes by
   * keeping only those whose HTTP method matches the current
   * request method.
   */  
  private function routersByMethods(
  ): void {
    $this->routers = Util::where( 
      $this->routers, 
      fn( RouteDirect|Router $router ) => (
        $this->request->compareMethod(
          $router->method()
        )
      )
    );
  }

  /**
   * Filters the registered routers by URI.
   *
   * This method narrows down the list of available routes by
   * keeping only those whose URI matches the current
   * request URI.
   */  
  private function routersByUris(
  ): void {
    $this->routers = Util::where( 
      array: $this->routers, 
      fn: fn( RouteDirect|Router $router ): bool => (
        $this->request->compareUri( requestUri: $router->uri())
      )
    );
  }
  
  /**
   * Ensures that at least one route was matched.
   *
   * If no routers are available after applying all filters,
   * this method triggers a "Not Found" error response.
   */  
  private function routersEmpty(
  ): void {
    if( Util::exist( array: $this->routers ) === false ){
      Error::NotFound( message: "Route {$this->request->requestUri()} not found" );
    }
  }

  /**
   * Handles errors that occur during route execution.
   *
   * This method converts the given exception into a JSON response,
   * using the exception message as the response body and the
   * exception code as the HTTP status code.
   *
   * @param Exception $error The exception thrown during route handling.
   */  
  private function routersIsError(
    Exception $error
  ): void {
    [ $message, $code ] = [
      $error->getMessage(),
      $error->getCode()
    ];
    
    $this->response->json(
      HttpStatus::resolvePublicMessage(
        $code, $message
      ), $code
    );
  }

  private function routersExec(
  ): void {
    [ $router ] = $this->routers;
    if( $router instanceof RouteDirect ){
      if( Util::isFN( $router->handler )){
        Util::callUserFN( $router->handler, [
          $this->response, $this->request->defineParam($router->uri()),
        ]);
      }
    } else if( $router instanceof Router ) {
      $router->execute( $this->request );
    }
  }

  /**
   * Handles routing when the PHP script is executed from the CLI (Command Line Interface).
   *
   * This method delegates the routing logic specifically for client-side
   * execution, typically when running commands like `php index.php`.
   */  
  private function listenByClient(
  ): void {
    $this->routersByClientInfors();
  }

  /**
   * Handles routing when the PHP script is executed as a web API request.
   *
   * This method performs the full routing workflow for API requests:
   * 1. Filter routers by HTTP method.
   * 2. Filter routers by URI.
   * 3. Check if any matching routers exist.
   * 4. Execute the matched router handler.
   *
   * Any exception thrown during routing is caught and passed to the
   * error handler for consistent JSON error responses.
   */  
  private function listenByApi(
  ): void {
    try {
      $this->routersByMethods();
      $this->routersByUris();
      $this->routersEmpty();
      $this->routersExec();
    } 
    catch ( Exception $error ){
      $this->routersIsError( 
        $error
      );
    }    
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
    $this->isClient()
      ? $this->listenByClient()
      : $this->listenByApi();
  }
}