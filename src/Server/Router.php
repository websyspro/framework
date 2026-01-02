<?php

namespace Websyspro\Core\Server;

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
}