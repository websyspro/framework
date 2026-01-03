<?php

namespace Websyspro\Core\Server;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionType;
use ReflectionUnionType;
use Websyspro\Core\Server\Decorations\Controller\AllowAnonymous;
use Websyspro\Core\Server\Decorations\Controller\Authenticate;
use Websyspro\Core\Server\Decorations\Controller\Controller;
use Websyspro\Core\Server\Decorations\Controller\Delete;
use Websyspro\Core\Server\Decorations\Controller\Get;
use Websyspro\Core\Server\Decorations\Controller\Patch;
use Websyspro\Core\Server\Decorations\Controller\Post;
use Websyspro\Core\Server\Decorations\Controller\Put;
use Websyspro\Core\Server\Exceptions\Error;
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

  /**
   * Resolves the declared type(s) of a reflected parameter.
   *
   * This method normalizes different ReflectionType implementations:
   * - If the type is a ReflectionNamedType, its name is returned as a string.
   * - If the type is a ReflectionUnionType, an array of type names is returned.
   * - For unsupported or unknown ReflectionType implementations,
   *   an empty array is returned.
   *
   * @param ReflectionParameter $reflectionParameter
   *        The reflection type extracted from a parameter.
   *
   * @return string|array<int, string>
   *         A single type name for named types, or an array of type names
   *         for union types.
   */  
  private function typeFromParameter(
    ReflectionParameter $reflectionParameter
  ): array {
    if( $reflectionParameter->getType() instanceof ReflectionNamedType) {
      return [ $reflectionParameter->getType()->getName() ];
    } else if( $reflectionParameter->getType() instanceof ReflectionUnionType ){
      return Util::mapper(
        array: $reflectionParameter->getType()->getTypes(),
        fn: fn(ReflectionNamedType $reflectionNamedType): string => $reflectionNamedType->getName()
      );
    }

    return [];
  }

  /**
   * Retrieves the name of a reflected method parameter.
   *
   * This method returns the parameter identifier as declared
   * in the method signature.
   *
   * @param ReflectionParameter $reflectionParameter
   *        The reflected parameter being inspected.
   *
   * @return string The parameter name.
   */  
  private function nameFromParameter(
    ReflectionParameter $reflectionParameter
  ): string {
    return $reflectionParameter->getName();
  }

  /**
   * Instantiates and returns the object represented by a reflection attribute.
   *
   * This method creates a new instance of the attribute class using
   * the arguments defined in the attribute declaration.
   *
   * @param ReflectionAttribute $reflectionAttribute
   *        The reflection attribute to be instantiated.
   *
   * @return mixed The instantiated attribute object.
   */  
  private function instanceFromAttribute(
    ReflectionAttribute $reflectionAttribute
  ): mixed {
    return $reflectionAttribute->newInstance();
  }

  /**
   * Resolves the default value of a reflected method parameter.
   *
   * If the parameter is optional, its declared default value is returned.
   * Otherwise, null is returned to indicate that no default value exists.
   *
   * @param ReflectionParameter $reflectionParameter
   *        The reflected parameter being inspected.
   *
   * @return mixed The default value if defined, or null when the parameter
   *               does not declare a default value.
   */  
  private function defaultFromParameter(
    ReflectionParameter $reflectionParameter
  ): mixed {
    if( $reflectionParameter->isOptional() ){
      return $reflectionParameter->getDefaultValue();
    }

    return null;
  }

  /**
   * Resolves and normalizes all parameters declared on the current controller method.
   *
   * For each reflected method parameter, this method:
   * - Reads the parameter attributes.
   * - Ensures that exactly one attribute is declared.
   * - Instantiates the attribute.
   * - Resolves the parameter default value.
   * - Resolves the parameter declared type(s).
   *
   * The resulting structure is later used by the parameter resolver
   * to inject values into the controller method invocation.
   *
   * @return array<int, array{
   *     paramterInstance: object,
   *     paramterDefault: mixed,
   *     paramterTypes: string|array<int, string>
   * }>
   */  
  private function parametersFromMethod(
  ): array {
    $reflectionMethod = new ReflectionMethod(
      objectOrMethod: $this->controller,
      method: $this->name
    );

    /**
     * @var array<int, array{paramterInstance: object, paramterDefault: mixed, paramterType: array<int, string>}> 
     */
    $reflectionMethodParameters = Util::mapper(
      array: $reflectionMethod->getParameters(), 
      fn: function(ReflectionParameter $reflectionParameter): array {
        $attributsFromParameter = $reflectionParameter->getAttributes();

        /*
         * Ensure that the parameter declares at least one attribute.
         * If no attribute is found, this indicates an invalid configuration
         * and an internal server error is raised.
         * */
        if( Util::exist(  $attributsFromParameter ) === false ){
          Error::InternalServerError(
            "The parameter {$reflectionParameter->getName()} does not have an attribute."
          );
        }

        /* Ensure that the parameter has at most one attribute.
         * If more than one attribute is found, this indicates an invalid
         * or ambiguous configuration and an internal server error is raised.
         * */
        if( Util::sizeArray( $attributsFromParameter) > 1 ){
          Error::InternalServerError(
            "The parameter {$reflectionParameter->getName()} should have only one attribute"
          );
        }

        /**
         * Maps each parameter attribute to a structured array containing:
         * - The instantiated attribute object.
         * - The declared type of the reflected parameter.
         *
         * This structure is used later to resolve or inject the parameter
         * value based on attribute metadata and type information.
         *
         * @return array<int, array{instance: object, type: ?\ReflectionType}>
         */    
        [ $paramter ] = Util::mapper(
          $attributsFromParameter, fn( ReflectionAttribute $reflectionAttribute ) => ([
            "paramterInstance" => $this->instanceFromAttribute( $reflectionAttribute ),
            "paramterDefault" => $this->defaultFromParameter( $reflectionParameter ),
            "paramterTypes" => $this->typeFromParameter( $reflectionParameter ),
            "paramterName" => $this->nameFromParameter( $reflectionParameter )
          ])
        );
        
        return $paramter;
      }
    );

    return $reflectionMethodParameters;
  }

  private function doParamters(
    Request $request    
  ): array {
    Util::mapper(
      array: $this->parametersFromMethod(),
      fn: function( array $prameter ) use( $request ) : array {
        [ "paramterInstance" => $paramterInstance,
          "paramterDefault" => $paramterDefault,
          "paramterTypes" => $paramterTypes,
          "paramterName" => $paramterName
        ] = $prameter;

        $compiledParameters = $paramterInstance->execute( 
          $request, $paramterName, $paramterTypes, $paramterDefault
        );

        print_r($compiledParameters);

        return [];
      }
    );

    return [];
  }

  public function execute(
    Request $request
  ): mixed {
    $this->doMiddlewares( request: $request );
    $this->doParamters( request: $request );

    return [];
  }
}