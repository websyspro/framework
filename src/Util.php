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
    array $arrays
  ): array {
    return array_merge(
      $array, $arrays
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
  
  public static function sprintFormat(
    string $format,
    array $args
  ): string {
    return \sprintf( $format, ...$args);
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
      if( Util::isArray($value)){
        return isset($value[ $key ]);
      } else if( Util::isObject($value)){
        return isset($value->{ $key });
      }
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
    return Util::inArray( $primitiveType, [
      "int", "integer", "float", "double", "string",
      "bool", "boolean", "array", "null"
    ]);
  }
  
  /**
   * Hydrates an object instance from an array or stdClass data source.
   *
   * This method dynamically maps values from a given data structure
   * (array or object) into a strongly typed DTO or object instance.
   * The hydration process is based on reflection and property type hints.
   *
   * The process follows these rules:
   *
   * - Only properties that exist in the target class are considered.
   * - Values are matched by property name.
   * - Primitive (built-in) types are assigned directly.
   * - Non-primitive types (custom classes / DTOs) are hydrated recursively.
   * - Properties without a resolvable type or missing data are skipped.
   * - The constructor is not executed during instantiation.
   *
   * This method is designed to be generic and reusable, serving as
   * a core utility for request mapping, DTO hydration, entity mapping,
   * and other object transformation scenarios.
   *
   * @param object|array $data
   *        The source data used to hydrate the object. Can be an array
   *        or a stdClass-like object.
   *
   * @param string $dtoClass
   *        Fully qualified class name of the object to be hydrated.
   *
   * @return object
   *         A hydrated instance of the given class.
   */
  public static function hydrateObject(
    object|array $originClass,
    string $destineClass
  ): mixed {
    /**
     * Creates a ReflectionClass instance for the target DTO class.
     *
     * Reflection is used to inspect properties, types, and metadata
     * without requiring prior knowledge of the class structure.
     */
    $reflectionClass = new ReflectionClass(
      objectOrClass: $destineClass
    );

    /**
     * Instantiates the DTO without executing its constructor.
     *
     * This allows full control over property hydration and avoids
     * side effects or required constructor parameters.
     */
    $instance = $reflectionClass->newInstanceWithoutConstructor();

    /**
     * Iterates over all declared properties of the DTO.
     *
     * Only properties defined in the target class are considered,
     * ensuring strict and predictable hydration behavior.
     */
    foreach ($reflectionClass->getProperties() as $property) {

      /**
       * Retrieves the property name used to map incoming data.
       */
      $propertyName = $property->getName();

      /**
       * Skips hydration when the incoming data does not contain
       * a matching property key.
       *
       * Supports both array-based and object-based payloads.
       */
      if( Util::existVar($originClass, $propertyName) === false ){
        continue;
      }

      /**
       * Extracts the value from the data source, handling both
       * array and object access transparently.
       */
      $value = Util::isArray( value: $originClass )
        ? $originClass[$propertyName]
        : $originClass->{$propertyName};

      /**
       * Retrieves the declared type of the property.
       *
       * Only named types are supported; union or complex types
       * are ignored to preserve deterministic behavior.
       */
      $type = $property->getType();

      if ($type instanceof ReflectionNamedType === false) {
        continue;
      }

      /**
       * Resolves the type name of the property.
       *
       * This value is used to determine whether the property
       * represents a primitive type or a nested object.
       */
      $typeName = $type->getName();

      /**
       * Handles primitive (built-in) PHP types.
       *
       * Primitive values are assigned directly without
       * additional transformation or validation.
       */
      if ($type->isBuiltin()) {
        $property->setValue($instance, $value);
        continue;
      }

      /**
       * Handles object or DTO types recursively.
       *
       * When the property type is a class, the value is assumed
       * to be an array or object compatible with that class
       * and is hydrated recursively.
       */
      if (class_exists($typeName)) {
        $property->setValue(
          $instance,
          Util::hydrateObject(
            originClass: $value,
            destineClass: $typeName
          )
        );
      }
    }

    /**
     * Returns the fully hydrated object instance.
     */
    return $instance;
  }
} 