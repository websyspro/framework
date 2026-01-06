<?php

namespace Websyspro\Core\Entitys\Core\Persisteds;

use Websyspro\Core\Entitys\Interfaces\IPersistedRequireds;
use Websyspro\Core\Collection;

class PersistedRequiredsList
{
  public function __construct(
    private Collection $requireds
  ){}

  public function list(
  ): Collection {
    return $this->requireds;
  }

  public function isRequired(
    string $name
  ): bool {
    return (
      $this->list()->where(
        fn(IPersistedRequireds $persistedRequireds) => (
          $persistedRequireds->name === $name
        )
      )->exist()
    );
  }
}