<?php

namespace Websyspro\Core\DynamicSql\Shareds;

use Websyspro\Core\Collection;
use Websyspro\Core\Entitys\Core\StructureTable;

class ItemParameter
{
  public Collection $properts;
  public StructureTable $structureTable;

  public function __construct(
    public string $entity,
    public string $name
  ){
    $this->parseParameters();
  }

  public function parseParameters(
  ): void {
    $this->structureTable = (
      new StructureTable($this->entity)
    );
  }
}