<?php

namespace Websyspro\Core\Entitys\Core;

use Websyspro\Commons\DataList;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IProperties;

class StructureTableGenerations
extends StructureTableAbstract
{
  public function list(
  ): DataList {
    return $this->properties(
      AttributeType::generations
    );
  }

  public function listNames(
  ): DataList  {
    return $this->list()->mapper(
      fn(IProperties $property) => (
        $property->name
      )
    );
  }
}