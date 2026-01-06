<?php

namespace Websyspro\Core\Entitys\Core;

use Websyspro\Commons\DataList;
use Websyspro\Core\Entitys\Core\Shareds\OneToManyItem;
use Websyspro\Core\Entitys\Core\Shareds\OneToManyReferenceItem;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IProperties;

class StructureTableOneToManys
extends StructureTableAbstract
{
  public function list(
  ): DataList {
    return $this->properties(
      AttributeType::oneToMany
    );
  }

  public function listNames(
    string $table
  ): DataList {
    return (
      $this->list()->mapper(
        fn(IProperties $property) => (
          new OneToManyItem(
            $table, $property->name, new OneToManyReferenceItem(
              $property->items->first()->referenceClass
            )
          )
        )
      )
    );
  }

  public function isOneToMany(
    string $table,
    string $name
  ): bool {
    return $this->listNames($table)->where(
      fn(OneToManyItem $oneToOneItem) => (
        $oneToOneItem->name === $name
      )
    )->exist();
  } 
}