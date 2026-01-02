<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Attribute;
use Websyspro\Core\Server\Enums\ControllerType;
use Websyspro\Core\Server\Enums\HttpMethod;


/**
 * Marks a controller method as handling HTTP GET requests.
 *
 * This attribute is applied to methods to indicate that they should
 * respond to GET requests on the specified URI.
 *
 * Example usage:
 *   #[Get("/users/:id")]
 *   public function getUser(Request $request) { ... }
 *
 * Extends AbstractEndpoint, inheriting properties such as `uri`.
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Get extends AbstractEndpoint
{
  /**
   * The HTTP method this endpoint responds to.
   *
   * Set to HttpMethod::GET to indicate GET requests.
   *
   * @var HttpMethod
   */  
  public HttpMethod $httpMethod = HttpMethod::GET;

  /**
   * The type of controller this attribute represents.
   *
   * Defaults to ControllerType::Endpoint to indicate that this
   * attribute marks an actual endpoint method.
   *
   * @var ControllerType
   */  
  public ControllerType $controllerType = ControllerType::Endpoint;
}