<?php

namespace Websyspro\Core\Entitys\Core;

use Websyspro\Core\Entitys\Core\Shareds\OneToOneReferenceItem;
use Websyspro\Core\Entitys\Core\Shareds\OneToOneItem;
use Websyspro\Core\Entitys\Interfaces\IProperties;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Collection;

class StructureTableOneToOnes
extends StructureTableAbstract
{
  public function list(
  ): Collection {
    return $this->properties(
      AttributeType::oneToOne
    );
  }

  public function listNames(
    string $table
  ): Collection {
    return (
      $this->list()->mapper(
        fn(IProperties $property) => (
          new OneToOneItem(
            $table, $property->name, new OneToOneReferenceItem(
              $property->items->first()->referenceClass
            )
          )
        )
      )
    );
  }

  public function isOneToOne(
    string $table,
    string $name
  ): bool {
    return $this->listNames($table)->where(
      fn(OneToOneItem $oneToOneItem) => (
        $oneToOneItem->name === $name
      )
    )->exist();
  } 
}