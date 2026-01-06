<?php

namespace Websyspro\Core\Entitys\Decorations\Constraints;

use Attribute;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;

#[Attribute( Attribute::TARGET_PROPERTY )]
class ForeignKey
extends IAbstractColumn
{
  public AttributeType $attributeType = AttributeType::foreigns;

  public function __construct(
    public readonly string $referenceClass
  ){}
}