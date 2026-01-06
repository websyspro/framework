<?php

namespace Websyspro\Core\Entitys\Core;

use Websyspro\Core\Entitys\Interfaces\IUniqueNameItems;
use Websyspro\Core\Entitys\Interfaces\IProperties;
use Websyspro\Core\Entitys\Interfaces\IUniqueItem;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Collection;

class StructureTableUniques
extends StructureTableAbstract
{
  public function list(
  ): Collection {
    return $this->properties(
      AttributeType::uniques
    );
  }

  public function listNames(
  ): Collection  {
    return (
      $this
        ->list()
        ->Mapper(
          fn(IProperties $property) => (
            new IUniqueItem(
              $property->name, 
              $property->items
                ->first()->uniqueGroup
            )
          )
        )
        ->reduce([], function(mixed $curr, IUniqueItem $uniqueItem){
          $curr[$uniqueItem->uniqueGroup][] = $uniqueItem->name; 
          return $curr;
        })
        ->mapper(fn(array $uniqueGroups) => new Collection($uniqueGroups))
        ->mapper(fn(Collection $uniques) => new IUniqueNameItems("Unique_{$uniques->join("_")}", $uniques->joinWithComma()))
    );
  }

  public function isUnique(
    string $name
  ): bool {
    return (
      $this->listNames()->where(
        fn(IUniqueNameItems $uniqueNameItems) => (
          $uniqueNameItems->name === $name
        )
      )->exist()
    );
  }
}