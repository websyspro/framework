<?php

namespace Websyspro\Core\Entitys\Decorations\Requireds;

use Attribute;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;

#[Attribute( Attribute::TARGET_PROPERTY )]
class NotNull
extends IAbstractColumn
{
  public AttributeType $attributeType = AttributeType::requireds;
}