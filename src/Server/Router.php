<?php

namespace Websyspro\Core\Server;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use Websyspro\Core\Server\Decorations\Controller\AllowAnonymous;
use Websyspro\Core\Server\Decorations\Controller\Authenticate;
use Websyspro\Core\Server\Decorations\Controller\Controller;
use Websyspro\Core\Server\Decorations\Controller\Delete;
use Websyspro\Core\Server\Decorations\Controller\Get;
use Websyspro\Core\Server\Decorations\Controller\Patch;
use Websyspro\Core\Server\Decorations\Controller\Post;
use Websyspro\Core\Server\Decorations\Controller\Put;
use Websyspro\Core\Util;

/**
 * Representa uma rota registrada a partir de um módulo.
 *
 * Esta classe encapsula todas as informações necessárias
 * para identificar e resolver uma rota, incluindo:
 * - Controller responsável
 * - Método HTTP
 * - Nome da rota
 * - URI associada
 */
class Router
{
  /**
   * List of attributes that should be ignored when resolving middlewares.
   *
   * These attributes represent HTTP method mappings and controller
   * metadata, not actual middleware logic, and therefore must be
   * excluded from middleware resolution.
   */  
  private array $excerptMiddleware = [
    Put::class,
    Get::class,
    Post::class,
    Patch::class,
    Delete::class,
    Controller::class
  ]; 

  /**
   * Construtor da rota por módulo.
   *
   * @param string $controller Nome ou referência do controller responsável.
   * @param string $method Método HTTP da rota (GET, POST, PUT, DELETE, etc).
   * @param string $name Nome identificador da rota.
   * @param string $uri URI da rota.
   */  
  public function __construct(
    private string $controller,
    private string $method,
    private string $name,
    private string $uri
  ){}

  /**
   * Retorna o controller associado à rota.
   *
   * @return string
   */  
  public function controller(
  ): string {
    return $this->controller;
  }  

  /**
   * Retorna o método HTTP da rota.
   *
   * @return string
   */
  public function method(
  ): string {
    return $this->method;
  }

  /**
   * Retorna o nome identificador da rota.
   *
   * @return string
   */  
  public function name(
  ): string {
    return $this->name;
  }
  
  /**
   * Retorna a URI da rota.
   *
   * @return string
   */  
  public function uri(
  ): string {
    return $this->uri;
  }

  /**
   * Retrieves middleware attributes from a reflection object
   * (either a controller class or a controller method),
   * excluding attributes listed in `$this->excerptMiddleware`.
   *
   * @param ReflectionClass|ReflectionMethod $reflection
   *        The reflection instance used to read attributes.
   *
   * @return array An array of ReflectionAttribute instances representing
   *               the filtered middlewares.
   */  
  private function middlewares(
    ReflectionClass|ReflectionMethod $reflection
  ): array {
    return Util::where(
      array: $reflection->getAttributes(), 
      fn: fn(ReflectionAttribute $reflectionAttribute): bool => Util::inArray(
        value: $reflectionAttribute->getName(), array: $this->excerptMiddleware
      ) === false
    );
  }

  /**
   * Retrieves middleware attributes declared at the method level
   * for the current controller action.
   *
   * This method delegates to `middlewares()` and uses reflection
   * to inspect the current controller method by name.
   *
   * @return array An array of ReflectionAttribute instances representing
   *               the method-level middlewares.
   */  
  private function middlewaresFromMethods(
  ): array {
    return $this->middlewares(
      reflection: new ReflectionMethod(
        objectOrMethod: $this->controller,
        method: $this->name
      )
    );
  }

  /**
   * Retrieves and filters middleware attributes declared at the controller level.
   *
   * Logic overview:
   * - Collect all middleware attributes defined on the controller.
   * - For each middleware:
   *   - If it is NOT the Authenticate middleware, it is always kept.
   *   - If it IS the Authenticate middleware:
   *     - Check whether any controller method declares the AllowAnonymous middleware.
   *     - If AllowAnonymous exists on any method, Authenticate is excluded.
   *     - Otherwise, Authenticate is kept.
   *
   * This allows method-level AllowAnonymous attributes to override
   * controller-level Authenticate middleware.
   *
   * @return array An array of ReflectionAttribute instances representing 
   * the effective middlewares for the controller.
   */  
  private function middlewaresFromControllers(
  ): array {
    return Util::where(
      array: $this->middlewares(
        reflection: new ReflectionClass(
          objectOrClass: $this->controller
        )
      ),
      fn: fn(ReflectionAttribute $reflectionAttribute): bool => (
        $reflectionAttribute->getName() !== Authenticate::class ? true : (
          Util::exist(
            array: Util::where(
              array: $this->middlewaresFromMethods(), 
              fn: fn(ReflectionAttribute $reflectionAttribute): bool => (
                $reflectionAttribute->getName() === AllowAnonymous::class
              )
            )
          ) ? false : true
        )
      )
    );
  }

  /**
   * Executes all resolved middlewares for the current request.
   *
   * This method:
   * - Merges controller-level and method-level middlewares.
   * - Instantiates each middleware attribute.
   * - Executes the middleware by calling its `execute()` method,
   *   passing the current Request instance.
   *
   * The execution order follows the merged array order and assumes
   * that each middleware implements an `execute(Request $request)` method.
   *
   * @param Request $request The current HTTP request being handled.
   *
   * @return void
   */
  private function doMiddlewares(
    Request $request
  ): void {
    Util::mapper(
      array: Util::merge(
        array: $this->middlewaresFromControllers(),
        arrays: $this->middlewaresFromMethods()
      ), fn: fn(ReflectionAttribute $reflectionAttribute): mixed => (
        $reflectionAttribute->newInstance()->execute( $request )
      )
    );
  }

  private function parametersFromMethod(
  ): array {
    return [
      new ReflectionMethod(
        objectOrMethod: $this->controller,
        method: $this->name
      )
    ];
  } 

  public function execute(
    Request $request
  ): mixed {
    $this->doMiddlewares( request: $request );
    print_r( $this->parametersFromMethod() );

    return [];
  }
}