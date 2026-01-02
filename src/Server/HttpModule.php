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
    array $endpoint
  ): void {
    [ $controller, $name, $endpoint ] = $endpoint;
    if( $endpoint instanceof AbstractEndpoint ){
      [ $controllerPaths ] = Util::slice(
        preg_split( 
          "#\\\\#", 
          $controller, 
          -1, 
          PREG_SPLIT_DELIM_CAPTURE ),
          -1
      );
      
      $controllerPaths = Util::mapper(
        Util::slice(
          preg_split( 
            "#(?=[A-Z])#", 
            $controllerPaths, 
            -1, 
            PREG_SPLIT_NO_EMPTY
          ),
          0,
          -1
        ), fn(string $paths): string => strtolower($paths)
      );

      $method = $endpoint->httpMethod()->value;
      $uri = Util::sprintFormat( 
        "%s/{$endpoint->uri()}", implode(
          "/", $controllerPaths
        )
      );

      $this->httpServer->addRouterByModule(
        $controller, $method, $name, $uri
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
              endpoint: $endpoint
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