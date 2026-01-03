<?php

namespace Websyspro\Core\Server\Decorations\Controller;

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
    } else {
      return [
        "type" => $parameterType, 
        "text" => Util::hydrateObject(
          originClass: $parameterValue, 
          destineClass: $parameterType
        )
      ];
    };
  }
}