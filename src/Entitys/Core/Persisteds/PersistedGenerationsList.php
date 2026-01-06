<?php

namespace Websyspro\Core\Entitys\Core\Persisteds;

use Websyspro\Core\Collection;
use Websyspro\Core\Entitys\Interfaces\IPersistedGeneration;

class PersistedGenerationsList
{
  public function __construct(
    private Collection $generations
  ){}

  public function list(
  ): Collection {
    return $this->generations;
  }

  public function listNames(
  ): Collection {
    return $this->list()->mapper(
      fn(IPersistedGeneration $persistedGeneration) => (
        $persistedGeneration->name
      )
    );
  }

  public function isGeneration(
    string $name
  ): bool {
    return (
      $this->list()->where(
        fn(IPersistedGeneration $persistedGeneration) => (
          $persistedGeneration->name === $name
        )
      )->exist()
    );    
  }
}