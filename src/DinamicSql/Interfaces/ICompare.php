<?php

namespace Websyspro\Core\DynamicSql\Interfaces;

use Websyspro\Core\Collection;

class ICompare
{
  public function __construct(
    public Collection $froms,
    public Collection $leftJoins,
    public Collection $conditionsPrimary,
    public Collection $conditionsSecundary
  ){}
}