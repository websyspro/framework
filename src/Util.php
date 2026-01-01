<?php

namespace Websyspro\Core;

use ReflectionFunction;

class Util
{
  /**
   * @private Slice
   * 
   * @param array $array
   * @param int $offset
   * @param int|null $length
   * @return array
   * **/  
  public static function slice(
    array $array,
    int $offset,
    int|null $length = null
  ): array {
    return \array_slice( 
      $array,
      $offset,
      $length
    );
  }

  /**
   * @private CountArgs
   * 
   * @param callable $fn
   * @return int
   * **/   
  public static function countArgs(
    callable $fn
  ): int {
    $rf = new ReflectionFunction( $fn );
    return $rf->getNumberOfParameters();
  }  

  /**
   * @private Mapper
   * 
   * @param array|object $array
   * @param callable $fn
   * @param array $arrayFromArry
   * @return array|object
   * **/  
  public static function mapper(
    array|object $array,
    callable $fn
  ): array|object {
    if(is_array($array)){
      foreach($array as $key => $val){
        $array[$key] = Util::countArgs( $fn ) === 2 
          ? $fn($val, $key) : $fn($val);
      }
    } else
    if(is_object($array)){
      foreach($array as $key => $val){
        $array->{$key} = Util::countArgs( $fn ) === 2 
          ? $fn($val, $key) : $fn($val);
      }      
    }

    return $array;
  }

  /**
   * @private Where
   * 
   * @param array $array
   * @param callable $fn
   * @param array $arrayFromArry
   * @return array|object
   * **/  
  public static function where(
    array|object $array,
    callable $fn,
    array $arrayFromArry = []
  ): array {
    foreach($array as $key => $val){
      if(is_numeric($key)){
        Util::countArgs($fn) === 2
          ? ($fn($val, $key) ? $arrayFromArry[] = $val : [])
          : ($fn($val) ? $arrayFromArry[] = $val : []);
      } else {
        Util::countArgs($fn) === 2
          ? ($fn($val, $key) ? $arrayFromArry[$key] = $val : [])
          : ($fn($val) ? $arrayFromArry[$key] = $val : []);
      }
    }

    return $arrayFromArry;
  }

  /**
   * @private Size
   * 
   * @param array $array
   * @return array
   * **/  
  public static function merge(
    array $array,
    array ...$arrays
  ): array {
    return array_merge(
      $arrays, ...$arrays
    );
  }
  
  /**
   * @private Reduce
   * 
   * @param array $array
   * @param callable $fn
   * @param array $initial
   * @return mixed
   * **/  
  public static function reduce(
    array $array,
    callable $fn,
    array $initial = []
  ): mixed {
    return array_reduce(
      $array, 
      $fn, 
      $initial
    );
  }  

  /**
   * @private Size
   * 
   * @param array $array
   * @return int
   * **/  
  public static function size(
    array|object $array
  ): int {
    return \sizeof($array);
  }  

  /**
   * @private Exist
   * 
   * @param array $array
   * @return int
   * **/  
  public static function exist(
    array|object $array
  ): bool {
    return Util::size($array) !== 0;
  }
  
  /**
   * @private isNull
   * 
   * @param array $array
   * @return int
   * **/  
  public static function isNull(
    mixed $value
  ): bool {
    return is_null($value);
  }   
  
  /**
   * @private ExistVar
   * 
   * @param array $array
   * @return int
   * **/  
  public static function existVar(
    mixed $value,
    mixed $key = null
  ): bool {
    if(Util::isNull($key) === false){
      return isset($value[ $key ]);
    }

    return isset($value);
  }  
} 