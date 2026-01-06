<?php

namespace Websyspro\Core\Entitys\Core;

use Websyspro\Commons\DataList;
use Websyspro\Core\Entitys\Core\Shareds\ForeignKeyItem;
use Websyspro\Core\Entitys\Core\Shareds\ForeignKeyReferenceItem;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IProperties;

class StructureTableForeignKeys
extends StructureTableAbstract
{
  public function list(
  ): DataList {
    return $this->properties(
      AttributeType::foreigns
    );
  }

  public function listNames(
    string $table
  ): DataList {
    return (
      $this->list()->mapper(
        fn(IProperties $property) => (
          new ForeignKeyItem(
            $table, $property->name, new ForeignKeyReferenceItem(
              $property->items->first()->referenceClass
            )
          )
        )
      )
    );
  }

  public function isForeignKey(
    string $table,
    string $name
  ): bool {
    return $this->listNames($table)->where(
      fn(ForeignKeyItem $foreignKeyItem) => (
        $foreignKeyItem->name === $name
      )
    )->exist();
  } 
}