<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Websyspro\Core\Server\Enums\ControllerType;
use Attribute;


/**
 * Marks a class as a Controller with a URI prefix.
 *
 * This attribute is applied to a controller class to indicate that
 * it is a web API controller and to define a common URI prefix
 * for all endpoints within the class.
 *
 * Example usage:
 *   #[Controller("/users")]
 *   class UserController { ... }
 *
 * All endpoints inside UserController will be prefixed with "/users".
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Controller
{
  /**
   * Specifies the type of this controller.
   *
   * ControllerType::Controller indicates that this is a main controller class,
   * rather than middleware or a parameter.
   *
   * @var ControllerType
   */  
  public ControllerType $controllerType = ControllerType::Controller;

  /**
   * Constructor for the Controller attribute.
   *
   * @param string $prefix The URI prefix for all endpoints in this controller.
   */  
  public function __construct(
    public string $prefix
  ){}
}