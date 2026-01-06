<?php

namespace Websyspro\Core\Entitys\Core;

use Websyspro\Core\Entitys\Interfaces\IProperties;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Collection;

class StructureTableGenerations
extends StructureTableAbstract
{
  public function list(
  ): Collection {
    return $this->properties(
      AttributeType::generations
    );
  }

  public function listNames(
  ): Collection {
    return $this->list()->mapper(
      fn(IProperties $property) => (
        $property->name
      )
    );
  }
}