<?php

namespace Websyspro\Core\Entitys\Interfaces;

class IOneToOneRelationship
{
  public function __construct(
    public string $table,
    public string $referece
  ){}
}