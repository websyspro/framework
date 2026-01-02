<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Websyspro\Core\Server\Enums\ControllerType;
use Websyspro\Core\Server\Request;
use Attribute;


/**
 * Marks a controller method as "allow anonymous".
 *
 * This attribute can be applied to endpoint methods to indicate
 * that authentication is not required for this route.
 * 
 * Usage:
 *   #[AllowAnonymous]
 *   public function publicEndpoint(Request $request) { ... }
 *
 * @see ControllerType::Middleware for classification
 */
#[Attribute(Attribute::TARGET_METHOD)]
class AllowAnonymous
{
  /**
   * The type of controller this attribute represents.
   *
   * Defaults to Middleware to indicate it acts as a filter or decorator
   * rather than a standard endpoint.
   *
   * @var ControllerType
   */  
  public ControllerType $controllerType = ControllerType::Middleware;

  /**
   * Executes the attribute logic.
   *
   * Currently empty, but in the future could be used to modify
   * the request, perform checks, or set flags in the request pipeline.
   *
   * @param Request $request The current request object.
   */  
  public function execute(
    Request $request
  ): void {}
}