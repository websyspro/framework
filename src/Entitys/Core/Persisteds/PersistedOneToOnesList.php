<?php

namespace Websyspro\Core\Entitys\Core\Persisteds;

use Websyspro\Core\Entitys\Interfaces\IPersistedOneToOnes;
use Websyspro\Core\Collection;

class PersistedOneToOnesList
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
        fn(IPersistedOneToOnes $iPersistedOneToOnes) => (
          $iPersistedOneToOnes->name === $name
        )
      )->exist()
    );    
  }
}