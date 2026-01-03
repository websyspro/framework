<?php

namespace Websyspro\Core\Server;

/**
 * Representa uma rota direta registrada manualmente,
 * sem dependência de módulos, controllers ou attributes.
 *
 * Normalmente utilizada para:
 * - Rotas simples
 * - Callbacks diretos (closures)
 * - Handlers customizados
 */
class RouteDirect
{
  /**
   * Construtor da rota direta.
   *
   * @param string $method  Método HTTP da rota (GET, POST, etc).
   * @param string $uri     URI da rota.
   * @param mixed  $handler Handler responsável por tratar a requisição (callable, controller, etc).
   */  
  public function __construct(
    public string $method,
    public string $uri,
    public mixed $handler
  ){}

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
   * Retorna o método HTTP da rota.
   *
   * @return string
   */  
  public function method(
  ): string {
    return $this->method;
  }  
}