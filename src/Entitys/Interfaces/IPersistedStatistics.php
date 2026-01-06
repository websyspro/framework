<?php

namespace Websyspro\Core\Entitys\Interfaces;

class IPersistedStatistics
{
  public function __construct(
    public string $table,
    public string $name
  ){}
}