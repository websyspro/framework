<?php

namespace Websyspro\Core\Server;

use Websyspro\Core\Server\Decorations\Controller\AbstractEndpoint;
use Websyspro\Core\Server\Decorations\Controller\Module;
use Websyspro\Core\Util;
use ReflectionAttribute;
use ReflectionClass;

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
          array: $reflectionClass->getMethod( name: $method )->getAttributes(), 
          fn: fn(ReflectionAttribute $reflectionAttribute): array => (
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
        array: preg_split( 
          pattern: "#\\\\#", 
          subject: $controller, 
          limit: -1, 
          flags: PREG_SPLIT_DELIM_CAPTURE ),
          offset: -1
      );
      
      $controllerPaths = Util::mapper(
        array: Util::slice(
          array: preg_split( 
            pattern: "#(?=[A-Z])#", 
            subject: $controllerPaths, 
            limit: -1, 
            flags: PREG_SPLIT_NO_EMPTY
          ),
          offset: 0,
          length: -1
        ), fn: fn(string $paths): string => strtolower( string: $paths )
      );

      $method = $endpoint->httpMethod()->value;
      $uri = Util::sprintFormat( 
        format: "%s/{$endpoint->uri()}", args: implode(
          "/", $controllerPaths
        )
      );

      $this->httpServer->addRouterByModule(
        controller: $controller, method: $method, name: $name, uri: $uri
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