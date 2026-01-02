<?php

namespace Websyspro\Core\Server;

use ReflectionAttribute;
use ReflectionClass;
use Websyspro\Core\Server\Decorations\Controller\Controller;
use Websyspro\Core\Server\Decorations\Controller\Module;

class HttpModule
{
  public $controller;

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

  private function ready(
  ): void {
    $controllerList = $this->controllers();

    print_r($controllerList);
  } 
}