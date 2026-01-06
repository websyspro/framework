<?php

namespace Websyspro\Core\Entitys\Decorations\Columns;

use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Enums\ColumnType;
use Attribute;

#[Attribute( Attribute::TARGET_PROPERTY )]
class Flag 
extends IAbstractColumn
{
  public AttributeType $attributeType = AttributeType::column;
  public ColumnType $columnType = ColumnType::flag;

  public function sql(
  ): string {
    return "smallint";
  }
}