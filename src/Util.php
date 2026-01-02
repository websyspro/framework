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
   * Determines whether a given value exists in the provided array.
   *
   * This method is a thin wrapper around PHP's native `in_array` function,
   * providing a consistent interface for array comparisons within the
   * application utilities.
   *
   * @param mixed $value The value to search for in the array.
   * @param array $array The array to be searched.
   * @return bool Returns true if the value is found in the array, false otherwise.
   */
  public static function inArray(
    mixed $value,
    array $array
  ): bool {
    return \in_array( $value, $array);
  }
  
  /**
   * Joins array elements using CRLF line breaks.
   *
   * This method concatenates all values of the array into a single string,
   * separating each element with "\r\n" (Carriage Return + Line Feed).
   *
   * Commonly used when rebuilding raw HTTP content, headers,
   * multipart/form-data blocks or text-based payloads.
   *
   * @param array $array List of strings to be joined
   * @return string Combined string with CRLF separators
   */  
  public static function joinWithBr(
    array $array
  ): mixed {
    return implode( "\r\n", $array );
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
   * Returns the number of elements in an array or object.
   *
   * This method is a thin wrapper around PHP's sizeof() / count(),
   * allowing consistent usage across the framework when dealing
   * with arrays or iterable objects.
   *
   * Common use cases:
   * - Counting request parameters
   * - Validating collection sizes
   * - Checking payload structure
   *
   * @param string $value Array or object to be counted
   * @return int Number of elements
   */
  public static function sizeText(
    string $value
  ): int {
    return \strlen($value);
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
    return $value === null;
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
  
  /**
   * Checks whether the given value is a valid callable function.
   *
   * This method is a small wrapper around PHP's native `is_callable`
   * function and is mainly used to improve readability and semantic
   * clarity when working with functional-style utilities.
   *
   * @param callable $fn The value to be checked
   * @return bool Returns true if the value is callable, false otherwise
   */  
  public static function isFN(
    callable $fn
  ): bool {
    return is_callable( value: $fn );
  }

  /**
   * Executes a user-defined callable with the given arguments.
   *
   * This method is a small abstraction over PHP's call_user_func(),
   * allowing dynamic execution of callbacks while supporting
   * argument spreading.
   *
   * It is commonly used to invoke:
   *  - Route handlers
   *  - Middleware functions
   *  - User-defined callbacks
   *
   * @param callable $fn The callable function or method to be executed
   * @param array $args List of arguments passed to the callable
   *
   * @return mixed The return value of the executed callable
   */  
  public static function callUserFN(
    callable $fn, array $args = []
  ): mixed {
    return \call_user_func(
      $fn, ...$args
    );
  }
} 