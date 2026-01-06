<?php

namespace Websyspro\Core\Entitys\Core\Designs\MySql;

use Websyspro\Core\Entitys\Core\Persisteds\PersistedStatisticsList;
use Websyspro\Core\Entitys\Interfaces\IStatisticsNamesItem;
use Websyspro\Core\Entitys\Interfaces\IUpdateScript;
use Websyspro\Core\Entitys\Core\StructureTable;
use Websyspro\Core\Entitys\Enums\ScriptType;
use Websyspro\Core\Collection;

class MySqlUpdateStatistics
{
  public Collection $updateScripts;

  public function __construct(
    public PersistedStatisticsList $persistedStatisticsList,
    public StructureTable $structureTable
  ){}

  public function setInicial(
  ): void {
    $this->updateScripts = new Collection();
  }

  public function setAdd(
  ): void {
    if($this->persistedStatisticsList->listNames()->exist() === false){
      if($this->structureTable->statistics()->listNames()->exist() === true){
        $this->structureTable->statistics()->listNames()->mapper(
          fn(IStatisticsNamesItem $statisticsNamesItem) => (
            $this->updateScripts->add(
              new IUpdateScript(
                "Create index {$statisticsNamesItem->name} on {$this->structureTable->table} ({$statisticsNamesItem->columns})",
                "Index {$statisticsNamesItem->name} added with successfully to {$this->structureTable->table}", ScriptType::notDependence
              )
            )
          )
        );
      }
    }
  }

  public function setModify(
  ): void {
    if($this->persistedStatisticsList->listNames()->exist() === true){
      if($this->structureTable->statistics()->listNames()->exist() === true){
        $this->structureTable->statistics()->listNames()
          ->where(
            fn(IStatisticsNamesItem $statisticsNamesItem) => (
              $this->persistedStatisticsList->isIndex(
                $statisticsNamesItem->name
              ) === false
            )
          )
          ->mapper(fn(IStatisticsNamesItem $statisticsNamesItem) => (
            $this->updateScripts->add(
              new IUpdateScript(
                "Create Index {$statisticsNamesItem->name} On {$this->structureTable->table} ({$statisticsNamesItem->columns})",
                "Index {$statisticsNamesItem->name} added with successfully to {$this->structureTable->table}", ScriptType::notDependence
              )
            )            
          ));
      }
    }
  }

  public function setDrops(
  ): void {
    if($this->persistedStatisticsList->listNames()->exist() === true){
      $this->persistedStatisticsList->listNames()
        ->where(
          fn(string $indexName) => (
            $this->structureTable->statistics()->isIndex($indexName) === false
          )
        )
        ->mapper(
          fn(string $indexName) => (
            $this->updateScripts->add(
              new IUpdateScript(
                "Alter Table {$this->structureTable->table} Drop Index {$indexName}",
                "Index {$indexName} drop with successfully to {$this->structureTable->table}", ScriptType::notDependence
              )
            )
          )
        );
    }
  }

  public function startUpdates(
  ): MySqlUpdateStatistics {
    $this->setInicial();
    $this->setAdd();
    $this->setModify();
    $this->setDrops();  
    return $this;
  }

  public function updateScripts(
  ): Collection {
    return $this->updateScripts;
  }  
}