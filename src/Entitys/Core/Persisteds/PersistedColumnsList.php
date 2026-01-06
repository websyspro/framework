<?php

namespace Websyspro\Core\Entitys\Core\Persisteds;

use Websyspro\Core\Collection;
use Websyspro\Core\Entitys\Interfaces\IPersistedColumn;

class PersistedColumnsList
{
  public function __construct(
    private Collection $columns
  ){}

  public function columns(
  ): Collection {
    return $this->columns->mapper(
      function(IPersistedColumn $iPersistedColumn){
        if(preg_match("#^(decimal|varchar)#", $iPersistedColumn->type) === 0){
          $iPersistedColumn->type = preg_replace(
            "#\(\d*\)$#", "", $iPersistedColumn->type
          );
        }
        
        return $iPersistedColumn;
      }
    );
  }

  public function exist(
  ): bool {
    return $this->columns()->exist();
  }  

  public function columnExist(
    string $name
  ): bool {
    return (
      $this->columns()->where(
        fn(IPersistedColumn $column) => (
          $column->name === $name
        )
      )->exist()
    );
  }
  
  public function type(
    string $name
  ): string {
    return (
      $this->columns()->where(
        fn(IPersistedColumn $column) => (
          $column->name === $name
        )
      )->first()->type
    );
  }
}