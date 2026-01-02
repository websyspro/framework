<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Attribute;
use Websyspro\Core\Server\Enums\ControllerType;
use Websyspro\Core\Server\Request;


/**
 * Marks a controller class as requiring authentication.
 *
 * This attribute can be applied to a controller class to indicate
 * that all endpoints within the class should be protected and
 * require the user to be authenticated.
 *
 * Usage:
 *   #[Authenticate]
 *   class UserController { ... }
 *
 * @see ControllerType::Middleware for classification
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Authenticate
{
  /**
   * The type of controller this attribute represents.
   *
   * Defaults to Middleware, indicating that this attribute acts
   * as a pre-processing step (like a filter) rather than a direct
   * endpoint.
   *
   * @var ControllerType
   */  
  public ControllerType $controllerType = ControllerType::Middleware;

  /**
   * Constructor for the Authenticate attribute.
   *
   * Currently empty, but can be extended to accept parameters
   * such as roles, permissions, or authentication strategies.
   */  
  public function __construct(
  ){}

  /**
   * Executes the authentication logic for the request.
   *
   * Currently empty, but in a full implementation this method
   * would validate the request (e.g., check tokens, sessions,
   * or headers) and potentially throw an error if the user is
   * not authenticated.
   *
   * @param Request $request The current request object.
   */  
  public function execute(
    Request $request
  ): void {}  
}