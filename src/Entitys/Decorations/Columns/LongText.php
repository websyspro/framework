<?php

namespace Websyspro\Core\Entitys\Decorations\Columns;

use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Enums\ColumnType;
use Attribute;

#[Attribute( Attribute::TARGET_PROPERTY )]
class LongText
extends IAbstractColumn
{
  public AttributeType $attributeType = AttributeType::column;
  public ColumnType $columnType = ColumnType::longtext;

  public function __construct(
    public readonly int $size = 255
  ){}

  public function sql(
  ): string {
    return sprintf("longtext");
  } 
}