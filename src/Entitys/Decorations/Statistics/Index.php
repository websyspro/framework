<?php

namespace Websyspro\Core\Entitys\Decorations\Statistics;

use Attribute;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;

#[Attribute( Attribute::TARGET_PROPERTY )]
class Index extends IAbstractColumn
{
  public AttributeType $attributeType = AttributeType::indexes;

  public function __construct(
    public readonly int $indexGroup = 1
  ){}
}