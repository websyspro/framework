<?php

namespace Websyspro\Core\Entitys\Core;

use Websyspro\Core\Entitys\Interfaces\IProperties;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Collection;

class StructureTablePrimaryKeys
extends StructureTableAbstract
{
  public function list(
  ): Collection {
    return $this->properties(
      AttributeType::primaryKey
    )->mapper(fn(IProperties $properties) => $properties->name);
  }
}