<?php

/**
 * Represents the current HTTP request.
 *
 * This class acts as a facade over the AcceptHeader component,
 * delegating the responsibility of parsing and resolving
 * request data such as body, query parameters, route parameters,
 * and API base path handling.
 */

namespace Websyspro\Core\Server;

class Request
{

  /**
   * Creates a new Request instance.
   *
   * @param AcceptHeader $acceptHeader Handles content negotiation and request data extraction.
   */  
  public function __construct(
    public AcceptHeader $acceptHeader = new AcceptHeader()
  ){}
  
  /**
   * Returns the parsed request body according to the accepted content type.
   *
   * @return array|object|string|null The request body, or null if not present.
   */  
  public function body(
  ): array|object|string|null {
    return $this->acceptHeader->body();
  }

  /**
   * Returns the parsed query string parameters.
   *
   * @return array|object|string|null The query parameters.
   */  
  public function query(
  ): array|object|string|null {
    return $this->acceptHeader->query();
  }

  /**
   * Returns the resolved route parameters.
   *
   * @return array|object|string|null The route parameters.
   */  
  public function param(
  ): array|object|string|null {
    return $this->acceptHeader->param();
  }
  
  /**
   * Resolves the URI by applying the API base path when required.
   *
   * This method delegates the decision to the AcceptHeader,
   * which determines whether the request is part of the API
   * context and adjusts the URI accordingly.
   *
   * @param string $uri The original route URI.
   * @return string The normalized URI with or without the API base path.
   */  
  public function acceptAPIBase(
    string $uri
  ): string {
    return $this->acceptHeader->acceptAPIBase( $uri );
  }
  
  /**
   * Compares the current HTTP method.
   *
   * @param string|null $method
   * @return bool
   */
  public function compareMethod(
    string|null $method = null
  ): bool {
    return $this->acceptHeader->compareMethod( $method );
  } 
  
  /**
   * Compares the current URI against a route pattern.
   * Supports dynamic parameters using ":" notation.
   *
   * Example:
   *   /users/:id
   *
   * @param string|array|null $requestUri
   * @return bool
   */
  public function compareUri(
    string|array|null $requestUri = null
  ): bool {
    return $this->acceptHeader->compareUri( $requestUri );
  }  
}