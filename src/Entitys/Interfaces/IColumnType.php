<?php

namespace Websyspro\Core\Entitys\Interfaces;

class IColumnType
{
  public function __construct(
    public string $name,
    public string $type
  ){}
}