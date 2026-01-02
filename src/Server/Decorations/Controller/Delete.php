<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Attribute;
use Websyspro\Core\Server\Enums\ControllerType;
use Websyspro\Core\Server\Enums\HttpMethod;


/**
 * Marks a controller method as handling HTTP DELETE requests.
 *
 * This attribute is applied to methods to indicate that they should
 * respond to DELETE requests on the specified URI.
 *
 * Example usage:
 *   #[Delete("/users/:id")]
 *   public function deleteUser(Request $request) { ... }
 *
 * Extends AbstractEndpoint, inheriting properties such as `uri`.
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Delete extends AbstractEndpoint
{
  /**
   * The HTTP method this endpoint responds to.
   *
   * Set to HttpMethod::DELETE to indicate DELETE requests.
   *
   * @var HttpMethod
   */
  public HttpMethod $httpMethod = HttpMethod::DELETE;

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