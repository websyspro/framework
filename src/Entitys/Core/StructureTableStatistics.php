<?php

namespace Websyspro\Core\Entitys\Core;

use Websyspro\Commons\DataList;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IProperties;
use Websyspro\Core\Entitys\Interfaces\IStatisticsItem;
use Websyspro\Core\Entitys\Interfaces\IStatisticsNamesItem;

class StructureTableStatistics
extends StructureTableAbstract
{
  public function list(
  ): DataList {
    return $this->properties(
      AttributeType::indexes
    );
  }

  public function listNames(
  ): DataList  {
    return (
      $this
        ->list()
        ->mapper(
          fn(IProperties $property) => (
            new IStatisticsItem(
              $property->name, 
              $property->items
                ->first()->indexGroup
            )
          )
        )
        ->reduce([], function(mixed $curr, IStatisticsItem $statisticsItem){
          $curr[$statisticsItem->indexGroup][] = $statisticsItem->name; 
          return $curr;
        })
        ->mapper(fn(array $indexGroups) => DataList::create($indexGroups))
        ->mapper(fn(DataList $indexe) => new IStatisticsNamesItem("Index_{$indexe->join("_")}", $indexe->joinWithComma()))
    );
  }

  public function isIndex(
    string $name
  ): bool {
    return (
      $this->listNames()->where(
        fn(IStatisticsNamesItem $statisticsItem) => (
          $statisticsItem->name === $name
        )
      )->exist()
    );
  }
}