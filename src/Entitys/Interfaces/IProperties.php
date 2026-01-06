<?php

namespace Websyspro\Core\Entitys\Interfaces;

use ReflectionAttribute;
use Websyspro\Commons\DataList;

class IProperties
{
  public function __construct(
    public string $name,
    public DataList $items
  ){
    $this->items->mapper(
      fn(ReflectionAttribute $ra ) => $ra->newInstance()
    );
  }
}