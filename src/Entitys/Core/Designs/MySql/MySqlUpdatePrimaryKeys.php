<?php

namespace Websyspro\Core\Entitys\Core\Designs\MySql;

use Websyspro\Commons\DataList;
use Websyspro\Commons\Util;
use Websyspro\Core\Entitys\Core\Persisteds\PersistedPrimaryKeysList;
use Websyspro\Core\Entitys\Core\StructureTable;
use Websyspro\Core\Entitys\Enums\ScriptType;
use Websyspro\Core\Entitys\Interfaces\IUpdateScript;

class MySqlUpdatePrimaryKeys
{
  public DataList $updateScripts;

  public function __construct(
    public PersistedPrimaryKeysList $persistedPrimaryKeysList,
    public StructureTable $structureTable
  ){}

  public function setStarteds(
  ): void {
    $this->updateScripts = (
      DataList::create()
    );
  }

  public function setModify(
  ): void {
    if($this->structureTable->primaryKeys()->list()->exist() === true){
      $primaryKeysIsEquals = Util::arrayEquais(
        $this->structureTable->primaryKeys()->list()->all(),
        $this->persistedPrimaryKeysList->list()->all()
      );

      if($primaryKeysIsEquals === false){
        if($this->persistedPrimaryKeysList->list()->exist()){
          $this->updateScripts->add(
            new IUpdateScript(
              "Alter Table {$this->structureTable->table} Drop Primary Key",
              "Primary key ({$this->persistedPrimaryKeysList->list()->joinWithComma()}) create for {$this->structureTable->table} table successfully", ScriptType::notDependence
            )
          );          
        }

        $this->updateScripts->add(
          new IUpdateScript(
            "Alter Table {$this->structureTable->table} Add Primary Key ({$this->structureTable->primaryKeys()->list()->joinWithComma()})",
            "Primary key ({$this->structureTable->primaryKeys()->list()->joinWithComma()}) create for {$this->structureTable->table} table successfully", ScriptType::notDependence
          )
        );         
      }
    }
  }

  public function setDrops(
  ): void {
    if($this->persistedPrimaryKeysList->list()->exist() === true){
      if($this->structureTable->primaryKeys()->list()->exist() === false){
        $this->updateScripts->add(
          new IUpdateScript(
            "Alter Table {$this->structureTable->table} Drop Primary Key",
            "Primary key ({$this->persistedPrimaryKeysList->list()->joinWithComma()}) create for {$this->structureTable->table} table successfully", ScriptType::notDependence
          )
        );        
      }
    }
  }  

  public function startUpdates(
  ): MySqlUpdatePrimaryKeys {
    $this->setStarteds();
    $this->setModify();
    $this->setDrops(); 
    return $this;
  }

  public function updateScripts(
  ): DataList {
    return $this->updateScripts;
  }  
}