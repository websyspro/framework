<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Query extends AbstractParameter
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
      $request->query, 
      $instanceType, 
      $this->key
    );
  }
}