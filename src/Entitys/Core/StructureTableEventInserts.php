<?php

namespace Websyspro\Core\Entitys\Core;

use Websyspro\Core\Collection;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IProperties;

class StructureTableEventInserts
extends StructureTableAbstract
{
  public function list(
  ): Collection {
    return $this->properties(
      AttributeType::insert
    );
  }

  public function listNames(
  ): array {
    return $this->list()->mapper(
      fn(IProperties $properties) => (
        $properties->name
      )
    )->all();
  } 
}