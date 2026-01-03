<?php

namespace Websyspro\Core\Server;

use Websyspro\Core\Server\Decorations\Controller\AbstractEndpoint;
use Websyspro\Core\Server\Decorations\Controller\Module;
use Websyspro\Core\Util;
use ReflectionAttribute;
use ReflectionClass;

/**
 * Responsável por registrar automaticamente as rotas
 * de um módulo HTTP a partir de seus controllers.
 *
 * O processo funciona da seguinte forma:
 * 1. Lê a annotation/attribute #[Module] do módulo informado
 * 2. Extrai a lista de controllers
 * 3. Analisa cada método dos controllers via Reflection
 * 4. Converte attributes de endpoints em rotas do HttpServer
 */
class HttpModule
{
  /**
   * @param HttpServer $httpServer Instância do servidor HTTP
   * @param string $module Classe do módulo anotada com #[Module]
   */ 
  public function __construct(
    public HttpServer $httpServer,
    public string $module
  ){
    $this->ready();
  }

  /**
   * Obtém a lista de controllers definidos no attribute #[Module].
   *
   * @return array Lista de classes de controllers
   */  
  private function controllers(
  ): array {
    $reflectionClass = new ReflectionClass(
      objectOrClass: $this->module
    );

    [ $moduleClass ] = $reflectionClass->getAttributes( name: Module::class );

    if( $moduleClass instanceof ReflectionAttribute ){
      $newInstance = $moduleClass->newInstance();

      if( $newInstance instanceof Module ){
        return $newInstance->controllers;
      }
    } 

    return [];
  }
  
  /**
   * Extrai os métodos públicos de um controller (exceto o construtor)
   * e associa cada método aos seus attributes (endpoints).
   *
   * @param ReflectionClass $reflectionClass
   * @return array Estrutura contendo controller, método e endpoint
   */  
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
  
  /**
   * Registra uma rota no HttpServer a partir de um endpoint.
   *
   * Responsabilidades:
   * - Normalizar o nome do controller para URI
   * - Converter CamelCase em paths (UserController → user)
   * - Concatenar a URI do endpoint
   * - Registrar a rota via HttpServer
   *
   * @param array $endpoint Estrutura [controller, methodName, endpoint]
   */  
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
        format: "%s/{$endpoint->uri()}", args: [
          implode( "/", $controllerPaths )
        ]
      );

      $this->httpServer->addRouterByModule(
        controller: $controller, method: $method, name: $name, uri: $uri
      );
    }
  }

  /**
   * Processa todos os métodos de um controller
   * e registra suas rotas.
   *
   * @param string $objectOrClass Classe do controller
   */  
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

  /**
   * Ponto de entrada do módulo.
   *
   * Percorre todos os controllers definidos no módulo
   * e registra suas rotas automaticamente.
   */  
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