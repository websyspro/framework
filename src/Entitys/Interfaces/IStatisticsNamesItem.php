<?php

namespace Websyspro\Core\Entitys\Interfaces;

class IStatisticsNamesItem
{
  public function __construct(
    public string $name,
    public string $columns
  ){}
}