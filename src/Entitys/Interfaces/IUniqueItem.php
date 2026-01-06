<?php

namespace Websyspro\Core\Entitys\Interfaces;

class IUniqueItem
{
  public function __construct(
    public string $name,
    public int $uniqueGroup
  ){}
}