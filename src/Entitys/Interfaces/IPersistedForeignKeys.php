<?php

namespace Websyspro\Core\Entitys\Interfaces;

class IPersistedForeignKeys
{
  public function __construct(
    public string $table,
    public string $name
  ){}
}