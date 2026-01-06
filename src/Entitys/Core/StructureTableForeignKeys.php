<?php

namespace Websyspro\Core\Entitys\Core;

use Websyspro\Core\Entitys\Core\Shareds\ForeignKeyReferenceItem;
use Websyspro\Core\Entitys\Core\Shareds\ForeignKeyItem;
use Websyspro\Core\Entitys\Interfaces\IProperties;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Collection;

class StructureTableForeignKeys
extends StructureTableAbstract
{
  public function list(
  ): Collection {
    return $this->properties(
      AttributeType::foreigns
    );
  }

  public function listNames(
    string $table
  ): Collection {
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