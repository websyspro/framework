<?php

namespace Websyspro\Core\Entitys\Decorations\Constraints;

use Attribute;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;

#[Attribute( Attribute::TARGET_PROPERTY )]
class OneToOne
extends IAbstractColumn
{
  public AttributeType $attributeType = AttributeType::oneToOne;

  public function __construct(
    public readonly string $referenceClass
  ){}
}