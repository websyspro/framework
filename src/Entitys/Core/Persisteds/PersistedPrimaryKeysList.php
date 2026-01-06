<?php

namespace Websyspro\Core\Entitys\Core\Persisteds;

use Websyspro\Core\Entitys\Interfaces\IPersistedPrimaryKey;
use Websyspro\Core\Collection;

class PersistedPrimaryKeysList
{
  public function __construct(
    private Collection $primaryKeys
  ){}

  public function list(
  ): Collection {
    return $this->primaryKeys->mapper(
      fn(IPersistedPrimaryKey $persistedPrimaryKey) => (
        $persistedPrimaryKey->name
      )
    );
  }

  public function isRequired(
    string $name
  ): bool {
    return (
      $this->list()->where(
        fn(IPersistedPrimaryKey $persistedPrimaryKey) => (
          $persistedPrimaryKey->name === $name
        )
      )->exist()
    );
  }
}