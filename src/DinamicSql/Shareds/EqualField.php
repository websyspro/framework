<?php

namespace Websyspro\Core\DynamicSql\Shareds;

use Websyspro\Core\Entitys\Enums\ColumnType;
use Websyspro\Core\Entitys\Core\StructureTable;
use Websyspro\Core\Entitys\Interfaces\IProperties;

class EqualField
{
  public string $table;
  public ColumnType $columnType;

  public function __construct(
    public StructureTable $structureTable,
    public string $name
  ){
    $this->parseTable();
    $this->parseType();
    $this->parseClear();
  }

  public function parseTable(
  ): void {
    $this->table = (
      $this->structureTable->table
    );
  }

  public function parseType(
  ): void {
    $this->columnType = $this->structureTable->columns()->list()->where(
      fn(IProperties $properties) => $properties->name === $this->name
    )->first()->items->first()->columnType;
  }

  public function parseClear(
  ): void {}
}