<?php

namespace Websyspro\Core\Entitys\Decorations;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class EntityList
{
  public function __construct(
    public array $entitys = []
  ){}
}