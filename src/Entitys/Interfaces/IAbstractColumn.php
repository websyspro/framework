<?php

namespace Websyspro\Core\Entitys\Interfaces;

use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Enums\ColumnType;

class IAbstractColumn
{
  public AttributeType $attributeType = AttributeType::column;
  public ColumnType $columnType = ColumnType::date;

  public function sql(
  ): string {
    return "";
  }
}