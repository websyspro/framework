<?php

namespace Websyspro\Core\Entitys\Interfaces;

class IStatisticsItem
{
  public function __construct(
    public string $name,
    public int $indexGroup
  ){}
}