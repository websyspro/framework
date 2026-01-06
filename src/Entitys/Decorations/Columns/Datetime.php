<?php

namespace Websyspro\Core\Entitys\Decorations\Columns;

use Attribute;
use Websyspro\Core\Entitys\Enums\ColumnType;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;

#[Attribute( Attribute::TARGET_PROPERTY )]
class Datetime 
extends IAbstractColumn
{
  public AttributeType $attributeType = AttributeType::column;
  public ColumnType $columnType = ColumnType::datetime;

  public function sql(
  ): string {
    return "datetime";
  }
}