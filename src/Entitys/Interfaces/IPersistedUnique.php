<?php

namespace Websyspro\Core\Entitys\Interfaces;

class IPersistedUnique
{
  public function __construct(
    public string $table,
    public string $name
  ){}
}