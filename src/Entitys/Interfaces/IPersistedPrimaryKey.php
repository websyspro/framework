<?php

namespace Websyspro\Core\Entitys\Interfaces;

class IPersistedPrimaryKey
{
  public function __construct(
    public string $table,
    public string $name
  ){}
}