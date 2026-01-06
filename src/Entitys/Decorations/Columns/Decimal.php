<?php

namespace Websyspro\Core\Entitys\Decorations\Columns;

use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Enums\ColumnType;
use Attribute;

#[Attribute( Attribute::TARGET_PROPERTY )]
class Decimal
extends IAbstractColumn
{
  public AttributeType $attributeType = AttributeType::column;
  public ColumnType $columnType = ColumnType::decimal;

  public function __construct(
    public readonly int $numberOfDigits = 10,
    public readonly int $numberDigitsAfterTheComma = 2
  ){}

  public function sql(
  ): string {
    return sprintf("decimal(%s,%s)", ...[
      $this->numberOfDigits,
      $this->numberDigitsAfterTheComma
    ]);
  }  
}