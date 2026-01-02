<?php

namespace Websyspro\Core\Server;

use Websyspro\Core\Server\Decorations\Controller\Module;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use Websyspro\Core\Server\Decorations\Controller\AbstractEndpoint;
use Websyspro\Core\Server\Enums\ControllerType;
use Websyspro\Core\Util;

class HttpModule
{
  public function __construct(
    public HttpServer $httpServer,
    public string $module
  ){
    $this->ready();
  }

  private function controllers(
  ): array {
    $reflectionClass = new ReflectionClass(
      objectOrClass: $this->module
    );

    [ $moduleClass ] = $reflectionClass->getAttributes(
      name: Module::class
    );

    if( $moduleClass instanceof ReflectionAttribute ){
      $newInstance = $moduleClass->newInstance();

      if( $newInstance instanceof Module ){
        return $newInstance->controllers;
      }
    } 

    return [];
  }
  
  private function methodFromController(
    ReflectionClass $reflectionClass
  ) : array {
    return Util::mapper(
      array: Util::where(
        array: get_class_methods( object_or_class: $reflectionClass->getName()),
        fn: fn(string $method): string => $method !== "__construct"
      ), 
      fn: fn( string $method ): array|object => (
        Util::mapper(
          array: $reflectionClass->getMethod( $method )->getAttributes(), 
          fn: fn(ReflectionAttribute $reflectionAttribute) => (
            [ $reflectionClass->getName(), $method, $reflectionAttribute->newInstance() ]
          )
        )
      )
    );
  }
  
  private function addRouteFromController(
    ReflectionClass $controller,
    array $router
  ): void {
    [ $controller, $name, $endpoint ] = $router;
    if( $endpoint instanceof AbstractEndpoint ){
      [ $controller ] = Util::slice(
        preg_split( 
          "#\\\\#", 
          $controller, 
          -1, 
          PREG_SPLIT_DELIM_CAPTURE ),
          -1
      );
      
      $controller = Util::mapper(
        Util::slice(
          preg_split( 
            "#(?=[A-Z])#", 
            $controller, 
            -1, 
            PREG_SPLIT_NO_EMPTY
          ),
          0,
          -1
        ), fn(string $paths): string => strtolower($paths)
      );

      $httpMethod = $endpoint->httpMethod();
      $uri = Util::sprintFormat( 
        "%s/{$endpoint->uri()}", implode(
          "/", $controller
        )
      );

      $this->httpServer->addRouterByModule(
        $endpoint, $httpMethod, $name, $uri
      );
    }
  }

  private function routesFromController(
    string $objectOrClass
  ): void {
    $reflectionClass = new ReflectionClass(
      objectOrClass: $objectOrClass
    );

    Util::mapper(
      array: $this->methodFromController( 
        reflectionClass: $reflectionClass
      ), fn: fn(array $endpoints): array|object => (
        Util::mapper(
          array: $endpoints, 
          fn: fn(mixed $endpoint) => (
            $this->addRouteFromController(
              controller: $reflectionClass, 
              router: $endpoint
            )
          )
        )
      )
    );
  } 

  private function ready(
  ): void {
    Util::mapper(
      array: $this->controllers(), 
      fn: fn(string $controller) => (
        $this->routesFromController(
          objectOrClass: $controller
        )
      )
    );
  } 
}