<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Attribute;
use Websyspro\Core\Server\Enums\ControllerType;
use Websyspro\Core\Server\Request;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Param extends AbstractParameter
{
  public ControllerType $controllerType = ControllerType::Parameter;

  public function __construct(
    public readonly string|null $key = null
  ){}
  
  public function execute(
    Request $request,
    string $instanceType
  ): mixed {
    return $this->getValue(
      $request->param(), 
      $instanceType, 
      $this->key
    );
  }
}