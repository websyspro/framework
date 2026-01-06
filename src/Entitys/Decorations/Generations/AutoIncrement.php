<?php

namespace Websyspro\Core\Entitys\Decorations\Generations;

use Attribute;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;

#[Attribute( Attribute::TARGET_PROPERTY )]
class AutoIncrement
extends IAbstractColumn
{
  public AttributeType $attributeType = AttributeType::generations;
}