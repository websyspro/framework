<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Websyspro\Enums\ControllerType;
use Websyspro\Enums\MethodType;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Post extends AbstractEndpoint
{
  public MethodType $methodType = MethodType::POST;
  public ControllerType $controllerType = ControllerType::Endpoint;
}