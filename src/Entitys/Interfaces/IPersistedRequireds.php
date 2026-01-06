<?php

namespace Websyspro\Core\Entitys\Interfaces;

class IPersistedRequireds
{
  public function __construct(
    public string $table,
    public string $name
  ){}
}