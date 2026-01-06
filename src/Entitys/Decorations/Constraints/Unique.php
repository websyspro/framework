<?php

namespace Websyspro\Core\Entitys\Decorations\Constraints;

use Attribute;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;

#[Attribute( Attribute::TARGET_PROPERTY )]
class Unique extends IAbstractColumn
{
  public AttributeType $attributeType = AttributeType::uniques;

  public function __construct(
    public readonly int $uniqueGroup = 1
  ){}
}