<?php

namespace Websyspro\Core\Entitys\Core\Persisteds;

use Websyspro\Core\Entitys\Interfaces\IPersistedStatistics;
use Websyspro\Core\Collection;

class PersistedStatisticsList
{
  public function __construct(
    private Collection $indexes
  ){} 
  
  public function list(
  ): Collection {
    return $this->indexes;
  }

  public function listNames(
  ): Collection {
    return $this->List()->mapper(
      fn(IPersistedStatistics $persistedStatistics) => (
        $persistedStatistics->name
      )
    );
  }

  public function isIndex(
    string $name
  ): bool {
    return (
      $this->list()->where(
        fn(IPersistedStatistics $persistedStatistics) => (
          $persistedStatistics->name === $name
        )
      )->exist()
    );
  }
}