<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Attribute;
use Websyspro\Core\Server\Enums\ControllerType;
use Websyspro\Core\Server\Enums\HttpMethod;


/**
 * Marks a controller method as handling HTTP PUT requests.
 *
 * This attribute is applied to methods to indicate that they should
 * respond to PUT requests on the specified URI.
 *
 * Example usage:
 *   #[Put("/users/:id")]
 *   public function updateUser(Request $request) { ... }
 *
 * Extends AbstractEndpoint, inheriting properties such as `uri`.
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Put extends AbstractEndpoint
{
  /**
   * The HTTP method this endpoint responds to.
   *
   * Set to HttpMethod::PUT to indicate PUT requests.
   *
   * @var HttpMethod
   */  
  public HttpMethod $httpMethod = HttpMethod::PUT;

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