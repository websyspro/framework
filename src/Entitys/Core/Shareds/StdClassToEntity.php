<?php

namespace Websyspro\Core\Entitys\Core\Shareds;

class StdClassToEntity
{
  public static function parse(
    array|object $stdClass,
    string $entity
  ): object {
    $newEntity = (
      new $entity
    );

    foreach($stdClass as $key => $val){
      $newEntity->{$key} = $val;
    }
    
    return $newEntity;
  }
}