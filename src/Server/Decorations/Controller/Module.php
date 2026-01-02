<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Attribute;


/**
 * Marks a module containing multiple controller classes.
 *
 * This attribute is applied to a class to group related controllers
 * together, allowing the framework to load or register them as a module.
 *
 * Example usage:
 *   #[Module([UserController::class, ProductController::class])]
 *   class ApiModule {}
 *
 * This helps organize controllers by feature or domain within the application.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Module
{
  /**
   * The list of controller classes included in this module.
   *
   * @var array List of fully qualified controller class names.
   */  
  public function __construct(
    public readonly array $controllers
  ){}
}