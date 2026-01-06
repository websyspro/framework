<?php

namespace Websyspro\Core\Entitys\Core;

use Websyspro\Core\Entitys\Interfaces\IProperties;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Collection;

class StructureTableEventDeletes
extends StructureTableAbstract
{
  public function list(
  ): Collection {
    return $this->properties(
      AttributeType::delete
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