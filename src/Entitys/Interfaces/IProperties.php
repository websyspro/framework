<?php

namespace Websyspro\Core\Entitys\Interfaces;

use Websyspro\Core\Collection;
use ReflectionAttribute;

class IProperties
{
  public function __construct(
    public string $name,
    public Collection $items
  ){
    $this->items->mapper(
      fn(ReflectionAttribute $ra ) => $ra->newInstance()
    );
  }
}