<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Websyspro\Core\Server\Enums\ControllerType;
use Websyspro\Core\Server\Enums\HttpMethod;

/**
 * Abstract base class for API endpoints or controllers.
 *
 * This class provides the basic structure for defining an endpoint,
 * including its URI, HTTP method, and controller type. Specific
 * endpoints should extend this abstract class and implement their
 * own logic.
 */

abstract class AbstractEndpoint
{
  /**
   * The HTTP method for this endpoint.
   *
   * Defaults to GET. This property determines which HTTP requests
   * this endpoint will handle.
   *
   * @var HttpMethod
   */  
  public HttpMethod $httpMethod = HttpMethod::GET;

  /**
   * The type of controller this endpoint represents.
   *
   * Defaults to ControllerType::Endpoint. Can be used to differentiate
   * between different types of controllers in the framework.
   *
   * @var ControllerType
   */  
  public ControllerType $controllerType = ControllerType::Endpoint;

  /**
   * Constructor for the endpoint.
   *
   * @param string $uri The URI path for this endpoint (e.g., "users/:id").
   */  
  public function __construct(
    public string $uri
  ){}

  /**
   * Returns the normalized URI of the endpoint.
   *
   * Removes leading and trailing slashes to ensure a consistent format.
   *
   * Example:
   *   "/users/:id/" => "users/:id"
   *
   * @return string The normalized URI string.
   */  
  public function uri(
  ): string {
    return preg_replace(
      "#(^/)|(/$)#", "", $this->uri
    );
  }

  /**
   * Returns the HTTP method assigned to this endpoint.
   *
   * @return HttpMethod The HTTP method of this endpoint.
   */  
  public function httpMethod(
  ): HttpMethod {
    return $this->httpMethod;
  }   
}