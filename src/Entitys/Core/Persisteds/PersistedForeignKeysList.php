<?php

namespace Websyspro\Core\Entitys\Core\Persisteds;

use Websyspro\Core\Collection;
use Websyspro\Core\Entitys\Interfaces\IPersistedForeignKeys;

class PersistedForeignKeysList
{
  public function __construct(
    private Collection $foreignKeys
  ){}

  public function list(
  ): Collection {
    return $this->foreignKeys;
  }

  public function listNames(
  ): Collection {
    return $this->list();
  }

  public function isForeignKey(
    string $name
  ): bool {
    return (
      $this->list()->where(
        fn(IPersistedForeignKeys $persistedForeignKeys) => (
          $persistedForeignKeys->name === $name
        )
      )->exist()
    );    
  }
}