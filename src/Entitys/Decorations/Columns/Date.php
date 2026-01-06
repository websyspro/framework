<?php

namespace Websyspro\Core\Entitys\Decorations\Columns;

use Websyspro\Core\Entitys\Enums\ColumnType;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;
use Attribute;

#[Attribute( Attribute::TARGET_PROPERTY )]
class Date
extends IAbstractColumn
{
  public AttributeType $attributeType = AttributeType::column;
  public ColumnType $columnType = ColumnType::date;

  public function sql(
  ): string {
    return "date";
  }
}