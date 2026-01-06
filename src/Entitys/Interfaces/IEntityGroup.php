<?php

namespace Websyspro\Core\Entitys\Interfaces;

use Websyspro\Core\Entitys\Core\Shareds\ForeignKeyItem;
use Websyspro\Core\Entitys\Core\StructureTable;
use Websyspro\Core\Collection;

class IEntityGroup
{
  public Collection $rowList;
  public Collection $primaryKeys;
  public Collection $foreignKeys;
  public Collection $oneToOne;
  public Collection $oneToMany;

  public function __construct(
    public StructureTable $structure,
    public Collection $queryRows,
    public String $alias,
  ){
    $this->defineOneToOne();
    $this->definePrimaryKey();
    $this->defineFilter();
    $this->defineClear();
  }

  private function defineOneToOne(
  ): void {
    $this->oneToOne = (
      $this->structure
        ->foreignKeys()
        ->listNames($this->structure->table)
        ->mapper(
          fn(ForeignKeyItem $fk) => (
            new IOneToOne(
              $fk->key,
              $fk->foreignKeyReferenceItem->table,
              $fk->foreignKeyReferenceItem->key
            )
          )
        )
    );
  }

  public function defineOneToMany(
    Collection $entityGroupList
  ): void {
    $oneToMany = [];

    foreach($entityGroupList->all() as $entityGroup){
      if($entityGroup instanceof IEntityGroup){
        $foreingsKeysList = $entityGroup->structure
          ->foreignKeys()->listNames(
            $entityGroup->structure->table
          );

        foreach($foreingsKeysList->all() as $foreingsKeys){
          if($foreingsKeys instanceof ForeignKeyItem){
            if($foreingsKeys->foreignKeyReferenceItem->table === $this->structure->table){
              $oneToMany[] = new IOneToMany(
                $foreingsKeys->foreignKeyReferenceItem->key,
                $foreingsKeys->table,
                $foreingsKeys->key
              );
            }
          }
        }
      }
    }

    $this->oneToMany = new Collection(
      $oneToMany
    );
  } 

  private function definePrimaryKey(
  ): void {
    $this->primaryKeys = new Collection(
      array_flip($this->structure->primaryKeys()->list()->all())
    );
  }

  private function defineFilter(
  ): void {
    if($this->queryRows->exist() === true){
      $this->rowList = new Collection();

      foreach($this->queryRows->all() as $row){
        $rowNew = [];

        foreach($row as $column => $value){
          if(preg_match("#^{$this->alias}_\.*#", $column) === 1){
            $rowNew[preg_replace("/^{$this->alias}_/", "", $column)] = $value;
          }
        }

        $this->primaryKeys->mapper(
          fn(mixed $val, string $key) => $rowNew[$key] 
        );

        if($this->rowList->count() === 0){
          $this->rowList->add($rowNew);
        } else {
          $hasQueryRowsFilter = (
            $this->rowList->where(
              fn(array $row) => (
                $this->primaryKeys->where(
                  fn(mixed $val, string $key) => (
                    isset($row[$key]) === true && $row[$key] === $val
                  )
                )->exist()
              )
            )
          );

          if($hasQueryRowsFilter->exist() === false){
            $this->rowList->add($rowNew);
          }
        }
      }
    }
  }

  public function defineClear(
  ): void {
    unset($this->primaryKeys);
    unset($this->queryRows);
    unset($this->alias);
  }
}