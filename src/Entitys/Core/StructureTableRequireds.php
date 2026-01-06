<?php

namespace Websyspro\Core\Entitys\Core;

use Websyspro\Core\Entitys\Interfaces\IProperties;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Collection;

class StructureTableRequireds
extends StructureTableAbstract
{
  public function list(
  ): Collection {
    return $this->properties(
      AttributeType::requireds
    );
  }

  public function listKeysNames(
  ): Collection {
    return new Collection(
      array_flip(
        $this->list()->mapper(
          fn(IProperties $properties) => (
            $properties->name
          )
        )->all()
      )
    )->mapper(fn() => null);
  }  

  public function isRequired(
    string $name
  ): bool {
    return $this->list()->where(
      fn(IProperties $properties) => (
        $properties->name === $name
      )
    )->exist();
  } 
  
  public function sql(
    string $name
  ): string {
    return $this->isRequired($name)
      ? "Not Null" : "Null";
  }
}