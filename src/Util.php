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

use ReflectionClass;
use ReflectionFunction;
use ReflectionNamedType;
use stdClass;

class Util
{
  /**
   * Checks if a given value is an array.
   *
   * This is a simple wrapper around PHP's built-in is_array() function.
   * It returns true if the value is an array, false otherwise.
   *
   * Example usage:
   *   isArray([1, 2, 3]); // returns true
   *   isArray("hello");   // returns false
   *
   * @param mixed $value The value to check.
   * @return bool True if the value is an array, false otherwise.
   */  
  public static function isArray(
    mixed $value
  ): bool {
    return \is_array( $value );
  }

  /**
   * Returns the number of elements in an array.
   *
   * This is a simple wrapper around PHP's built-in sizeof() function.
   *
   * Example usage:
   *   sizeArray([1, 2, 3]); // returns 3
   *
   * @param array $array The array whose size is to be determined.
   * @return int The number of elements in the array.
   */  
  public static function sizeArray(
    array $array
  ): int {
    return \sizeof($array);
  }

  /**
   * Checks if a given value is an object.
   *
   * This is a wrapper around PHP's built-in is_object() function.
   *
   * Example usage:
   *   isObject(new stdClass()); // returns true
   *   isObject(123); // returns false
   *
   * @param mixed $object The value to check.
   * @return bool True if the value is an object, false otherwise.
   */  
  public static function isObject(
    mixed $object,
  ): bool {
    return \is_object( $object );
  }

  /**
   * Checks if an object has no properties.
   *
   * Uses get_object_vars() to retrieve an associative array of
   * the object's properties, then checks if the array is empty.
   *
   * Example usage:
   *   isObjectEmpty(new stdClass()); // returns true
   *   $obj = (object)['a' => 1];
   *   isObjectEmpty($obj); // returns false
   *
   * @param mixed $object The object to check.
   * @return bool True if the object has no properties, false otherwise.
   */  
  public static function isObjectEmpty(
    mixed $object,
  ): bool {
    return Util::sizeArray( 
      get_object_vars( $object )
    ) === 0;
  }  

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

  /**
   * Returns the type of a given value as a string.
   *
   * This is a simple wrapper around PHP's built-in gettype() function.
   * It can be used to determine the type of any variable at runtime.
   *
   * Example usage:
   *   getType(123); // returns "integer"
   *   getType("hello"); // returns "string"
   *   getType([]); // returns "array"
   *
   * @param mixed $value The value whose type is to be determined.
   * @return string The type of the value (e.g., "integer", "string", "array", etc.).
   */  
  public static function getType(
    mixed $value
  ): string {
    return \gettype( $value );
  }

  /**
   * Checks if a given type name represents a primitive PHP type.
   *
   * Primitive types include: int, integer, float, double, string,
   * bool, boolean, array, object, and null.
   *
   * This is useful for type validation, casting, and deciding
   * whether a property should be handled directly or recursively hydrated.
   *
   * Example usage:
   *   isPrimitiveType("int"); // returns true
   *   isPrimitiveType("User"); // returns false
   *
   * @param string $primitiveType The type name to check.
   * @return bool True if it is a primitive type, false otherwise.
   */  
  public static function isPrimitiveType(
    string $primitiveType
  ): bool {
    return in_array( $primitiveType, [
      "int", "integer", "float", "double", "string",
      "bool", "boolean", "array", "object", "null"
    ], true );
  }
  
  /**
   * Hydrates an object of a given class from an associative array.
   *
   * This function creates an instance of the specified class without
   * calling its constructor, then maps each property from the provided
   * data array to the object. It handles:
   *   - Primitive types (casts automatically)
   *   - Nested objects (recursively hydrates arrays into objects)
   *   - Any other type (sets the value as-is)
   *
   * Example usage:
   *   $data = ['id' => 1, 'name' => 'John'];
   *   $user = Util::hydrateObject($data, User::class);
   *
   * Notes:
   *   - If the class does not exist, returns a new stdClass instance.
   *   - Only properties that exist in the data array are set.
   *   - Uses Reflection to access private/protected properties.
   *
   * @param mixed  $data The associative array of data to hydrate.
   * @param string $className The fully-qualified class name to instantiate.
   * @return object The hydrated object instance.
   */  
  public static function hydrateObject( 
    mixed $data,
    string $className
  ): object {
    if( class_exists( $className ) === false){
      return new stdClass();
    }

    $refClass = new ReflectionClass($className);
    $instance = $refClass->newInstanceWithoutConstructor();

    foreach($refClass->getProperties() as $prop){

      $type = $prop->getType();
      if (!$type instanceof ReflectionNamedType) {
        continue;
      }

      $typeName = $type->getName();
      $propName = $prop->getName();

      if(!array_key_exists($propName, $data)){
        continue;
      }

      $value = $data[$propName];

      if (Util::isPrimitiveType($typeName)) {
        settype($value, $typeName);
        $prop->setValue($instance, $value);
      } else if (class_exists($typeName) && is_array($value)) {
        $nestedObj = Util::hydrateObject($value, $typeName);
        $prop->setValue($instance, $nestedObj);
      } else {
        $prop->setValue($instance, $value);
      }
    }

    return $instance;
  }  
} 