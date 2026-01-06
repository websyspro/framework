<?php

namespace Websyspro\Core\Entitys\Decorations\Columns;

use Websyspro\Core\Entitys\Enums\ColumnType;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;
use Websyspro\Core\Collection;
use Websyspro\Core\Util;
use Attribute;
use UnitEnum;

#[Attribute( Attribute::TARGET_PROPERTY )]
class Enum
extends IAbstractColumn
{
  public AttributeType $attributeType = AttributeType::column;
  public ColumnType $columnType = ColumnType::longtext;

  public function __construct(
    public string $enum
  ){}

  public function sql(
  ): string {
    $enums = new Collection(
      $this->enum::cases()
    )->mapper(fn(UnitEnum $case) => "'{$case->value}'");

    return Util::sprintFormat(
      "enum(%s)", [ $enums->joinWithComma()]
    );
  } 
}