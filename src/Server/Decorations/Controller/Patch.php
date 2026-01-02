<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Websyspro\Core\Server\Enums\ControllerType;
use Websyspro\Core\Server\Enums\HttpMethod;
use Attribute;


/**
 * Marks a controller method as handling HTTP PATCH requests.
 *
 * This attribute is applied to methods to indicate that they should
 * respond to PATCH requests on the specified URI.
 *
 * Example usage:
 *   #[Patch("/users/:id")]
 *   public function updateUserPartial(Request $request) { ... }
 *
 * Extends AbstractEndpoint, inheriting properties such as `uri`.
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Patch extends AbstractEndpoint
{
  /**
   * The HTTP method this endpoint responds to.
   *
   * Set to HttpMethod::PATCH to indicate PATCH requests.
   *
   * @var HttpMethod
   */  
  public HttpMethod $httpMethod = HttpMethod::PATCH;

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