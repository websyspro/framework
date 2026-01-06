<?php

namespace Websyspro\Core\Entitys\Core;

use Websyspro\Commons\DataList;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IProperties;

class StructureTableEventUpdates
extends StructureTableAbstract
{
  public function list(
  ): DataList {
    return $this->properties(
      AttributeType::update
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