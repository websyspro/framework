<?php

namespace Websyspro\Core\Entitys\Core\Designs\MySql;

use Websyspro\Core\Entitys\Core\Persisteds\PersistedOneToOnesList;
use Websyspro\Core\Entitys\Interfaces\IPersistedOneToOnes;
use Websyspro\Core\Entitys\Core\Shareds\OneToOneItem;
use Websyspro\Core\Entitys\Interfaces\IUpdateScript;
use Websyspro\Core\Entitys\Core\StructureTable;
use Websyspro\Core\Entitys\Enums\ScriptType;
use Websyspro\Commons\DataList;

class MySqlUpdateOneToOne
{
  public DataList $updateScripts;

  public function __construct(
    public PersistedOneToOnesList $persistedOneToOnesList,
    public StructureTable $structureTable
  ){}

  private function setInicial(
  ): void {
    $this->updateScripts = DataList::Create();
  }

  private function setAdd(
  ): void {
    if($this->persistedOneToOnesList->listNames()->exist() === false){
      if($this->structureTable->oneToOnes()->listNames($this->structureTable->table)->exist() === true){
        $this->structureTable->oneToOnes()->listNames($this->structureTable->table)
          ->mapper(fn(OneToOneItem $oneToOneItem) => (
            $this->updateScripts->add(
              new IUpdateScript(
                "Alter Table {$this->structureTable->table} Add Constraint {$oneToOneItem->name} Foreign Key ({$oneToOneItem->key}) References {$oneToOneItem->oneToOneReferenceItem->table}({$oneToOneItem->oneToOneReferenceItem->key})",
                "Foreign key constraint {$oneToOneItem->name} added with successfully to {$this->structureTable->table}", ScriptType::dependence
              )
            )
          ));
      }
    }
  }  

  private function setModify(
  ): void {
    if($this->persistedOneToOnesList->listNames()->exist() === true){
      if($this->structureTable->oneToOnes()->listNames($this->structureTable->table)->exist() === true){
        $this->structureTable->oneToOnes()->listNames($this->structureTable->table)
          ->where(fn(OneToOneItem $oneToOneItem) => (
            $this->persistedOneToOnesList->isForeignKey(
              $oneToOneItem->name
            ) === false
          ))
          ->mapper(fn(OneToOneItem $oneToOneItem) => (
            $this->updateScripts->add(
              new IUpdateScript(
                "Alter Table {$this->structureTable->table} Add Constraint {$oneToOneItem->name} Foreign Key ({$oneToOneItem->key}) References {$oneToOneItem->oneToOneReferenceItem->table}({$oneToOneItem->oneToOneReferenceItem->key})",
                "Foreign key constraint {$oneToOneItem->name} added with successfully to {$this->structureTable->table}", ScriptType::dependence
              )
            )            
          ));
      }
    }
  }

  private function setDrops(
  ): void {
    if($this->persistedOneToOnesList->listNames()->exist() === true){
      $this->persistedOneToOnesList->listNames()
        ->where(fn(IPersistedOneToOnes $persistedOneToOnes) => (
          $this->structureTable->oneToOnes()
            ->isOneToOne($this->structureTable->table, $persistedOneToOnes->name) === false
        ))
        ->mapper(
          function(IPersistedOneToOnes $persistedOneToOnes){
            $this->updateScripts->add(
              new IUpdateScript(
                "Alter Table {$this->structureTable->table} Drop Foreign Key {$persistedOneToOnes->name}",
                "Foreign key constraint {$persistedOneToOnes->name} drop with successfully to {$this->structureTable->table}", ScriptType::dependence
              )
            );
          }
        );
    }
  }

  public function startUpdates(
  ): MySqlUpdateOneToOne {
    $this->setInicial();
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