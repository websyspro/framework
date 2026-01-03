<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Exception;
use Websyspro\Core\Server\Enums\ControllerType;
use Websyspro\Core\Util;


/**
 * Base abstraction for parameter-based value resolution.
 *
 * This abstract class defines the common behavior for resolving,
 * validating, and normalizing values injected into controller
 * method parameters.
 *
 * It is designed to support attribute-driven parameter binding,
 * where values are extracted from a request context and validated
 * against declared parameter types.
 *
 * Concrete implementations are expected to extend this class and
 * specialize how parameter values are obtained (e.g. from request
 * body, query string, headers, or route parameters).
 */
abstract class AbstractParameter
{
  /**
   * Defines the controller type associated with this component.
   *
   * This property indicates that the controller operates in
   * parameter-based mode, where values are resolved and injected
   * through method parameters.
   */  
  public ControllerType $controllerType = ControllerType::Parameter;

  public function __construct(
    public readonly string|null $key = null
  ){}  

  /**
   * Resolves and validates a parameter value against an expected type.
   *
   * This method attempts to determine whether the provided parameter value
   * is compatible with the expected parameter type. The resolution process:
   *
   * - Uses the default value when the provided value is null and the default
   *   type matches the expected type.
   * - Determines the runtime type of the provided value.
   * - Validates primitive types with strict type matching.
   * - Accepts values whose runtime type exactly matches the expected type.
   *
   * If no compatible value can be resolved, null is returned.
   *
   * @param mixed       $parameterValue   The value to be resolved.
   * @param string|null $parameterType    The expected parameter type.
   * @param mixed       $paramterDefault  The default value used when the
   *                                      provided value is null.
   *
   * @return array{type: string|null, value: mixed}|null
   *         An array containing the resolved type and value when successful,
   *         or null when the value cannot be resolved.
   */  
  public function getValue(
    mixed $parameterValue,
    string|null $parameterType = null,
    mixed $paramterDefault = null
  ): mixed {
    /**
     * Handles the case where the provided parameter value is null.
     *
     * If the parameter value is null:
     * - The default parameter value is evaluated.
     * - If the default value type matches the expected parameter type,
     *   the default value is used.
     * - Otherwise, null is returned to indicate an incompatible or
     *   missing value.
     *
     * The result is normalized into an array containing the
     * resolved type and value.
     */
    if ( Util::isNull( value: $parameterValue )) {
      if ( Util::getType( value: $paramterDefault ) === $parameterType ) {
        return [ 
          "type" => $parameterType, 
          "text" => $paramterDefault
        ];
      } else return [ 
        "type" => null,
        "text" => null
      ];
    }

    /**
     * Determines the runtime type of the provided parameter value.
     *
     * The resolved type is later used to validate compatibility
     * with the declared parameter type.
     */
    $parameterTypeFromValue = Util::getType(
      value: $parameterValue
    );

    
    /**
     * Validates and resolves the parameter value when its runtime type
     * is a primitive type.
     *
     * If the runtime type of the provided value is primitive and matches
     * the expected parameter type, the value is accepted and returned.
     * Otherwise, resolution continues or fails in subsequent checks.
     */
    if ( Util::isPrimitiveType( $parameterTypeFromValue )) {
      if( $parameterType === $parameterTypeFromValue ){
        return [ 
          "type" => $parameterType,
          "text" => $parameterValue
        ];
      }
    }

    /**
     * Accepts the parameter value when its runtime type exactly matches
     * the expected parameter type.
     *
     * When the types are identical, the value is considered valid and
     * returned without further transformation.
     */    
    if( $parameterType === $parameterTypeFromValue ){
      return [
        "type" => $parameterType,
        "text" => $parameterValue
      ];
    } else if( Util::isObject( $parameterValue )){
      try {
        return [
          "type" => $parameterType,
          "text" => Util::hydrateObject(
            originClass: $parameterValue, 
            destineClass: $parameterType
          )
        ];
      } catch ( Exception $error ){
        return [
          "type" => null,
          "text" => null
        ];
      }
    };

    return [
      "type" => null,
      "text" => null
    ];
  }

  /**
   * Executes the attribute logic to extract the value from the request body.
   *
   * Delegates to AbstractParameter::getValue() to handle:
   *   - Fetching the value from the body
   *   - Casting it to the correct type
   *   - Handling default values or missing keys
   *
   * @param string  $instanceType  The expected type of the parameter.
   *
   * @return mixed The extracted and properly typed value.
   */  
  public function hydrateTypes(
    mixed $paramterValue,
    string $paramterName,
    array $paramterTypes = [],
    mixed $paramterDefault = null
  ): array {
    /**
     * Retrieves the request body and attempts to resolve the parameter value
     * against all possible declared parameter types.
     *
     * If no explicit parameter types are declared, the runtime type of the
     * request body is used as a fallback.
     *
     * Each candidate type is passed to `getValue()` in order to determine
     * whether the parameter value can be successfully resolved.
     */    
    $paramterValue = Util::mapper(
      array: Util::merge(
        array: $paramterTypes, arrays: Util::sizeArray($paramterTypes) === 0 
          ? [Util::getType( value: $paramterValue )] : []
      ), fn: fn( string $paramterType ): array|null => (
        $this->getValue(
          parameterValue: $paramterValue,
          parameterType: $paramterType,
          paramterDefault: $paramterDefault,
        )
      )
    );

    /**
     * If a specific key is defined, attempt to extract the parameter value
     * from the provided source using that key.
     *
     * - When the source value is an array, the key is used as an array index.
     *
     * If the key does not exist in the source, the parameter default value
     * is returned as a fallback.
     */    
    if (Util::isNull(value: $this->key ) === false) {
      if (Util::isArray(value: $paramterValue )) {
        return $paramterValue[ $this->key ] ?? $paramterDefault;
      }
    }

    /**
     * Filters out invalid resolved parameter values.
     *
     * Only entries that contain both:
     * - a non-null resolved type
     * - a non-null resolved value ("text")
     *
     * are kept. This ensures that only successfully
     * validated and hydrated parameter values are
     * processed further.
     */    
    return Util::where(
      array: $paramterValue, 
      fn: fn(array $value): bool => (
        Util::isNull( value: $value[ "type" ]) === false &&
        Util::isNull( value: $value[ "text" ]) === false
      )
    );
  }  
}