<?php

namespace Websyspro\Core\Entitys\Core\Designs\MySql;

use Websyspro\Core\Collection;
use Websyspro\Core\Database\Connect;
use Websyspro\Core\Entitys\Core\Persisteds\PersistedColumnsList;
use Websyspro\Core\Entitys\Core\Persisteds\PersistedRequiredsList;
use Websyspro\Core\Entitys\Core\StructureTable;
use Websyspro\Core\Entitys\Enums\ScriptType;
use Websyspro\Core\Entitys\Interfaces\IColumnType;
use Websyspro\Core\Entitys\Interfaces\IPersistedColumn;
use Websyspro\Core\Entitys\Interfaces\IUpdateScript;

class MySqlUpdateColumns
{
  public Collection $updateScripts;

  public function __construct(
    public PersistedColumnsList $persistedColumnsList,
    public PersistedRequiredsList $persistedRequiredsList,
    public StructureTable $structureTable,
    public Connect $connect
  ){}

  public function setStarteds(
  ): void {
    $this->updateScripts = (
      new Collection()
    );
  }

  private function setCreateds(
  ): void {
    if($this->persistedColumnsList->exist() === false){
      $columnsTypes = $this->structureTable->columns()->listType()->mapper(
        fn(IColumnType $columnType) => "{$columnType->name} {$columnType->type} {$this->structureTable->requireds()->sql($columnType->name)}"
      );

      $this->updateScripts->add(
        new IUpdateScript(
          "Create Table {$this->structureTable->table} ({$columnsTypes->joinWithComma()}) engine=innodb",
          "Table {$this->structureTable->table} created with successfully", ScriptType::notDependence
        )
      );
    }
  }

  private function setAdd(
  ): void {
    if($this->persistedColumnsList->exist() === true){
      $columnsAdd = $this->structureTable->columns()->listType()->where(
        fn(IColumnType $columnType) => $this->persistedColumnsList->columnExist($columnType->name) === false
      );

      if($columnsAdd->exist() === true){
        $columnsAdd->forEach(fn(IColumnType $columnType) => (
          $this->updateScripts->add(
            new IUpdateScript(
              "Alter Table {$this->structureTable->table} Add Column {$columnType->name} {$columnType->type} {$this->structureTable->requireds()->sql($columnType->name)} {$this->structureTable->columns()->before($columnType->name)}",
              "Column {$columnType->name} added with successfully to {$this->structureTable->table}", ScriptType::notDependence
            )
          )
        ));
      }
    }
  }  

  private function setModify(
  ): void {
    if($this->persistedColumnsList->exist() === true){
      $columnsModify = $this->structureTable->columns()->listType()->where(
        fn(IColumnType $columnType) => (
          $this->persistedColumnsList->columnExist($columnType->name) === true && (
            $this->structureTable->columns()->type($columnType->name) !== $this->persistedColumnsList->type($columnType->name) || 
            $this->structureTable->requireds()->isRequired($columnType->name) !== $this->persistedRequiredsList->isRequired($columnType->name)
          )
        )
      );

      if($columnsModify->exist()){
        $columnsModify->forEach(fn(IColumnType $columnType) => (
          $this->updateScripts->add(
            new IUpdateScript(
              "Alter Table {$this->structureTable->table} Modify Column {$columnType->name} {$columnType->type} {$this->structureTable->requireds()->sql($columnType->name)}",
              "Column {$columnType->name} modify with successfully to {$this->structureTable->table}", ScriptType::notDependence
            )
          )
        ));
      }
    }
  }
  
  private function setDrops(
  ): void {
    if($this->persistedColumnsList->exist() === true){
      $persistedColumns = $this->persistedColumnsList->columns()->where(
        fn(IPersistedColumn $persistedColumn) => (
          $this->structureTable->columns()->columnExist($persistedColumn->name) 
        ) === false
      );

      if($persistedColumns->exist() === true){
        $persistedColumns->forEach(fn(IPersistedColumn $persistedColumn) => (
          $this->connect->query(
            "Select Count(*) as IsNotNull 
               From {$this->structureTable->table} 
              Where {$persistedColumn->name} Is Not Null"
          )->forEach(
            function(object $row) use($persistedColumn) {
              if((int)$row->IsNotNull === 0){
                $this->updateScripts->add(
                  new IUpdateScript(
                    "Alter Table {$this->structureTable->table} Drop {$persistedColumn->name}",
                    "Column {$persistedColumn->name} drop with successfully to {$this->structureTable->table}", ScriptType::notDependence
                  )
                );
              }
            }
          )
        ));
      }
    }
  }
  
  public function StartUpdates(
  ): MySqlUpdateColumns {
    $this->setStarteds();
    $this->setCreateds();
    $this->setAdd();
    $this->setModify();
    $this->setDrops();
    return $this;
  }

  public function updateScripts(
  ): DataList {
    return $this->updateScripts;
  }
}