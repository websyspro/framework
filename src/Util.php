<?php

/**
 * Class Util
 *
 * Utility helper class that provides functional-style operations
 * for arrays and objects, such as map, filter, reduce and helpers
 * commonly used across the framework.
 *
 * Designed to work with both arrays and objects in a predictable way.
 *
 * @package Websyspro\Core
 */

namespace Websyspro\Core;

use ReflectionFunction;

class Util
{
  /**
   * Returns a portion of an array.
   * Wrapper around PHP array_slice.
   *
   * @param array $array   Source array
   * @param int   $offset  Start offset
   * @param int|null $length Optional length
   * @return array
   */ 
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
   * Counts how many parameters a callable accepts.
   *
   * Used to determine whether a callback expects
   * value-only or value + key.
   *
   * @param callable $fn
   * @return int
   */  
  public static function countArgs(
    callable $fn
  ): int {
    $rf = new ReflectionFunction( $fn );
    return $rf->getNumberOfParameters();
  }  

  /**
   * Maps over an array or object and transforms its values.
   *
   * The callback may receive:
   *  - fn(value)
   *  - fn(value, key)
   *
   * @param array|object $array
   * @param callable $fn
   * @return array|object
   */ 
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
   * Filters an array or object using a callback.
   *
   * Preserves keys for associative arrays.
   *
   * @param array|object $array
   * @param callable $fn
   * @param array $arrayFromArry Internal accumulator
   * @return array
   */  
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
   * Merges multiple arrays into one.
   *
   * @param array $array
   * @param array ...$arrays
   * @return array
   */  
  public static function merge(
    array $array,
    array ...$arrays
  ): array {
    return array_merge(
      $array, ...$arrays
    );
  }
  
  /**
   * Reduces an array to a single value.
   *
   * @param array $array
   * @param callable $fn
   * @param array $initial Initial accumulator value
   * @return mixed
   */ 
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
   * Returns the size of an array or object.
   *
   * @param array|object $array
   * @return int
   */ 
  public static function size(
    array|object $array
  ): int {
    return \sizeof($array);
  }  

  /**
   * Checks whether an array or object has at least one element.
   *
   * @param array|object $array
   * @return bool
   */ 
  public static function exist(
    array|object $array
  ): bool {
    return Util::size($array) !== 0;
  }
  
  /**
   * Checks whether a value is null.
   *
   * @param mixed $value
   * @return bool
   */  
  public static function isNull(
    mixed $value
  ): bool {
    return is_null($value);
  }   
  
  /**
   * Checks if a variable or array key exists.
   *
   * If a key is provided, checks array key existence.
   * Otherwise, checks if the value itself exists.
   *
   * @param mixed $value
   * @param mixed $key
   * @return bool
   */  
  public static function existVar(
    mixed $value,
    mixed $key = null
  ): bool {
    if( Util::isNull($key) === false ){
      return isset($value[ $key ]);
    }

    return isset($value);
  }  
} 