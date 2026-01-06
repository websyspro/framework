<?php

namespace Websyspro\Core\Entitys\Core;

use Websyspro\Core\Entitys\Core\Designs\MySql\MySqlUpdateColumns;
use Websyspro\Core\Entitys\Core\Designs\MySql\MySqlUpdateForeignKeys;
use Websyspro\Core\Entitys\Core\Designs\MySql\MySqlUpdateGenerations;
use Websyspro\Core\Entitys\Core\Designs\MySql\MySqlUpdatePrimaryKeys;
use Websyspro\Core\Entitys\Core\Designs\MySql\MySqlUpdateStatistics;
use Websyspro\Core\Entitys\Core\Designs\MySql\MySqlUpdateUniques;
use Websyspro\Core\Entitys\Core\Persisteds\PersistedColumnsList;
use Websyspro\Core\Entitys\Core\Persisteds\PersistedForeignKeysList;
use Websyspro\Core\Entitys\Core\Persisteds\PersistedGenerationsList;
use Websyspro\Core\Entitys\Core\Persisteds\PersistedOneToOnesList;
use Websyspro\Core\Entitys\Core\Persisteds\PersistedPrimaryKeysList;
use Websyspro\Core\Entitys\Core\Persisteds\PersistedRequiredsList;
use Websyspro\Core\Entitys\Core\Persisteds\PersistedStatisticsList;
use Websyspro\Core\Entitys\Core\Persisteds\PersistedUniquesList;
use Websyspro\Core\Entitys\Core\Persisteds\MySqlScript;
use Websyspro\Core\Entitys\Interfaces\IPersistedForeignKeys;
use Websyspro\Core\Entitys\Interfaces\IPersistedGeneration;
use Websyspro\Core\Entitys\Interfaces\IPersistedOneToOnes;
use Websyspro\Core\Entitys\Interfaces\IPersistedPrimaryKey;
use Websyspro\Core\Entitys\Interfaces\IPersistedRequireds;
use Websyspro\Core\Entitys\Interfaces\IPersistedStatistics;
use Websyspro\Core\Entitys\Interfaces\IPersistedUnique;
use Websyspro\Core\Entitys\Interfaces\IPersistedColumn;
use Websyspro\Core\Entitys\Interfaces\IUpdateScript;
use Websyspro\Core\Entitys\Enums\ScriptType;
use Websyspro\Core\Server\Logger\Log;
use Websyspro\Core\Database\Connect;
use Websyspro\Core\Collection;
use Websyspro\Core\Server\Logger\Enums\LogType;

class StructureDatabase
{
  public Connect $connect;
  public Collection $updateScripts;
  public Collection $structureTable;
  public Collection $persistedColumn;
  public Collection $persistedPrimaryKeys;
  public Collection $persistedGenerations;
  public Collection $persistedRequireds;
  public Collection $persistedUniques;
  public Collection $persistedStatistics;
  public Collection $persistedForeignKeys;
  public Collection $persistedOneToOnes;

  public function __construct(
    public Collection $entitys,
    public string $module
  ){}

  public function getDatabase(
  ): void {
    $connect = Connect::set();

    if(is_null($connect) === false){
      $this->connect = $connect;
    }
  }

  private function getDecorationEntitys(
  ): void {
    if($this->entitys->count() !== 0){
      $this->structureTable = $this->entitys;
      $this->structureTable->mapper(
        fn(string $entity) => new StructureTable($entity)
      );
    }
  }

  private function get(
    string $query
  ): Collection {
    return $this->connect->query($query);
  }

  private function setPersistedsColumns(
  ): Collection {
    return (
      $this->get(
        MySqlScript::columns(
          $this->connect->database()
        )
      )->mapper(fn(object $obj) => (
        new IPersistedColumn(
          ...(array)$obj
        )
      ))
    );
  }

  private function setPersistedsRequireds(
  ): Collection {
    return $this->get(
      MySqlScript::requireds(
        $this->connect->database()
      )
    )->mapper(fn(object $obj) => (
      new IPersistedRequireds(
        ...(array)$obj
      )
    ));
  }  

  private function setPersistedsPrimaryKeys(
  ): Collection {
    return $this->get(
      MySqlScript::primaryKeys(
        $this->connect->database()
      )
    )->mapper(fn(object $obj) => (
      new IPersistedPrimaryKey(
        ...(array)$obj
      )
    ));
  }
  
  private function setPersistedsGenerations(
  ): Collection {
    return $this->get(
      MySqlScript::generations(
        $this->connect->database()
      )
    )->mapper(fn(object $obj) => (
      new IPersistedGeneration(
        ...(array)$obj
      )
    ));
  }
  
  private function setPersistedsUniques(
  ): Collection {
    return $this->get(
      MySqlScript::uniques(
        $this->connect->database()
      )
    )->mapper(fn(object $obj) => (
      new IPersistedUnique(
        ...(array)$obj
      )
    ));
  }
  
  private function setPersistedsStatistics(
  ): Collection {
    return $this->get(
      MySqlScript::statistics(
        $this->connect->database()
      )
    )->mapper(fn(object $obj) => (
      new IPersistedStatistics(
        ...(array)$obj
      )
    ));
  }

  private function setPersistedsForeignKeys(
  ): Collection {
    return $this->get(
      MySqlScript::foreignKeys(
        $this->connect->database()
      )
    )->mapper(fn(object $obj) => (
      new IPersistedForeignKeys(
        ...(array)$obj
      )
    ));
  }
  
  private function getPersistedsEntitys(
  ): void {
    $this->persistedColumn = $this->setPersistedsColumns();
    $this->persistedRequireds = $this->setPersistedsRequireds();
    $this->persistedPrimaryKeys = $this->setPersistedsPrimaryKeys();
    $this->persistedGenerations = $this->setPersistedsGenerations();
    $this->persistedUniques = $this->setPersistedsUniques();
    $this->persistedStatistics = $this->setPersistedsStatistics();
    $this->persistedForeignKeys = $this->setPersistedsForeignKeys();
  }

  private function getPersistedColumns(
    StructureTable $structureTable
  ): PersistedColumnsList {
    if(isset($this->persistedColumn) === false){
      return new PersistedColumnsList(
        new Collection()
      );
    }

    return new PersistedColumnsList(
      $this->persistedColumn->where(
        fn(IPersistedColumn $persistedColumn) => (
          $persistedColumn->table === $structureTable->table
        )
      )
    );
  }

  private function getPersistedRequireds(
    StructureTable $structureTable
  ): PersistedRequiredsList {
    return new PersistedRequiredsList( 
      $this->persistedRequireds->where(
        fn(IPersistedRequireds $persistedRequireds) => (
          $persistedRequireds->table === $structureTable->table
        )
      )
    );
  }
  
  private function getPersistedPrimaryKeys(
    StructureTable $structureTable
  ): PersistedPrimaryKeysList {
    if(isset($this->persistedPrimaryKeys) === false){
      return new PersistedPrimaryKeysList(
        new Collection()
      );
    }

    return new PersistedPrimaryKeysList(
      $this->persistedPrimaryKeys->where(
        fn(IPersistedPrimaryKey $persistedPrimaryKey) => (
          $persistedPrimaryKey->table === $structureTable->table
        )
      )
    );
  }

  private function getPersistedGenerations(
    StructureTable $structureTable
  ): PersistedGenerationsList {
    if(isset($this->persistedGenerations) === false){
      return new PersistedGenerationsList(
        new Collection()
      );
    }

    return new PersistedGenerationsList(
      $this->persistedGenerations->where(
        fn(IPersistedGeneration $persistedGeneration) => (
          $persistedGeneration->table === $structureTable->table
        )
      )
    );
  }

  private function getPersistedUniques(
    StructureTable $structureTable
  ): PersistedUniquesList {
    if(isset($this->persistedUniques) === false){
      return new PersistedUniquesList(
        new Collection()
      );
    }

    return new PersistedUniquesList(
      $this->persistedUniques->where(
        fn(IPersistedUnique $persistedUnique) => (
          $persistedUnique->table === $structureTable->table
        )
      )
    );
  }

  private function getPersistedStatistics(
    StructureTable $structureTable
  ): PersistedStatisticsList {
    if(isset($this->persistedStatistics) === false){
      return new PersistedStatisticsList(
        new Collection()
      );
    }

    return new PersistedStatisticsList(
      $this->persistedStatistics->where(
        fn(IPersistedStatistics $persistedStatistic) => (
          $persistedStatistic->table === $structureTable->table
        )
      )
    );
  }

  private function getPersistedForeignKeys(
    StructureTable $structureTable
  ): PersistedForeignKeysList {
    if(isset($this->persistedForeignKeys) === false){
      return new PersistedForeignKeysList(
        new Collection()
      );
    }

    return new PersistedForeignKeysList(
      $this->persistedForeignKeys->where(
        fn(IPersistedForeignKeys $persistedForeignKey) => (
          $persistedForeignKey->table === $structureTable->table
        )
      )
    );
  }
  
  private function getPersistedOneToOnes(
    StructureTable $structureTable
  ): PersistedOneToOnesList {
    if(isset($this->persistedOneToOnes) === false){
      return new PersistedOneToOnesList(
        new Collection()
      );
    }

    return new PersistedOneToOnesList(
      $this->persistedOneToOnes->where(
        fn(IPersistedOneToOnes $persistedOneToOnes) => (
          $persistedOneToOnes->table === $structureTable->table
        )
      )
    );
  }  

  private function addUpdateScripts(
    Collection $updateScripts
  ): void {
    if(isset($this->updateScripts) === false){
      $this->updateScripts = (
        new Collection()
      );
    }
    
    $updateScripts->mapper(
      fn(IUpdateScript $updateScripts) => (
        $this->updateScripts->add(
          $updateScripts
        )
      )
    );
  }

  private function getUpdateStructureColumns(
    StructureTable $structureTable
  ): void {
    $this->addUpdateScripts(
      (new MySqlUpdateColumns(
        $this->getPersistedColumns($structureTable),
        $this->getPersistedRequireds($structureTable), $structureTable, $this->connect
      ))->startUpdates()->updateScripts()
    );
  }

  private function getUpdateStructurePrimaryKeys(
    StructureTable $structureTable
  ): void {
    $this->addUpdateScripts(
      (new MySqlUpdatePrimaryKeys(
        $this->getPersistedPrimaryKeys($structureTable), $structureTable
      ))->startUpdates()->updateScripts()
    );
  }

  private function getUpdateStructureGenerations(
    StructureTable $structureTable
  ): void {
    $this->addUpdateScripts(
      (new MySqlUpdateGenerations(
        $this->getPersistedGenerations($structureTable), $structureTable
      ))->startUpdates()->updateScripts()
    );
  }

  private function getUpdateStructureUniques(
    StructureTable $structureTable
  ): void {
    $this->addUpdateScripts(
      (new MySqlUpdateUniques(
        $this->getPersistedUniques($structureTable), $structureTable
      ))->startUpdates()->updateScripts()
    );
  }

  private function getUpdateStructureStatistics(
    StructureTable $structureTable
  ): void {
    $this->addUpdateScripts(
      (new MySqlUpdateStatistics(
        $this->getPersistedStatistics($structureTable), $structureTable
      ))->startUpdates()->updateScripts()
    );
  }

  private function getUpdateStructureForeignKeys(
    StructureTable $structureTable
  ): void {
    $this->addUpdateScripts(
      (new MySqlUpdateForeignKeys(
        $this->getPersistedForeignKeys($structureTable), $structureTable
      ))->startUpdates()->updateScripts()
    );
  }
  
  private function getUpdateEntitys(
  ): void {
    $this->structureTable->mapper(
      function(StructureTable $structureTable){
        $this->getUpdateStructureColumns($structureTable);
        $this->getUpdateStructurePrimaryKeys($structureTable);
        $this->getUpdateStructureGenerations($structureTable);
        $this->getUpdateStructureUniques($structureTable);
        $this->getUpdateStructureStatistics($structureTable);
        $this->getUpdateStructureForeignKeys($structureTable);
      }
    );
  }

  private function executeDatabase(
    IUpdateScript $updateScript
  ): void {
    if($this->connect->exec($updateScript->sql) === true){
      Log::debug( LogType::database, $updateScript->message);
    }
  }

  private function SetUpdateDatabase(
  ): void {
    $updateScriptsNotDependence = $this->updateScripts->where(
      fn(IUpdateScript $updateScript) => (
        $updateScript->scriptType === ScriptType::notDependence
      )
    );

    $updateScriptsDependence = $this->updateScripts->where(
      fn(IUpdateScript $updateScript) => (
        $updateScript->scriptType === ScriptType::dependence
      )
    );    

    $updateScriptsNotDependence->mapper(fn(IUpdateScript $updateScript) => $this->executeDatabase($updateScript));
    $updateScriptsDependence->mapper(fn(IUpdateScript $updateScript) => $this->executeDatabase($updateScript));
  }
  
  public function update(
  ): void {
    $module = new $this->module;
    if($module->isUpdate === true){
      $this->getDatabase();

      if(isset($this->connect)){
        $this->getDecorationEntitys();
        $this->getPersistedsEntitys();
  
        if(isset($this->structureTable)){
          $this->getUpdateEntitys();
          $this->setUpdateDatabase();
        }
      }
    }
  }
}