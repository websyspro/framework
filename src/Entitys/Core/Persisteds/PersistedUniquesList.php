<?php

namespace Websyspro\Core\Entitys\Core\Persisteds;

use Websyspro\Core\Entitys\Interfaces\IPersistedUnique;
use Websyspro\Core\Collection;

class PersistedUniquesList
{
  public function __construct(
    private Collection $uniques
  ){} 
  
  public function list(
  ): Collection {
    return $this->uniques;
  }

  public function listNames(
  ): Collection {
    return $this->list()->mapper(
      fn(IPersistedUnique $persistedUnique) => (
        $persistedUnique->name
      )
    );
  }

  public function isUnique(
    string $name
  ): bool {
    return (
      $this->list()->where(
        fn(IPersistedUnique $persistedUnique) => (
          $persistedUnique->name === $name
        )
      )->exist()
    );
  }
}