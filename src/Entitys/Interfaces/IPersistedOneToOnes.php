<?php

namespace Websyspro\Core\Entitys\Interfaces;

class IPersistedOneToOnes
{
  public function __construct(
    public string $table,
    public string $name
  ){}
}