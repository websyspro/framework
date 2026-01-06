<?php

namespace Websyspro\Core\Entitys\Interfaces;

class IUniqueNameItems
{
  public function __construct(
    public string $name,
    public string $columns
  ){}
}