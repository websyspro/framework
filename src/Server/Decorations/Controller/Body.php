<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Websyspro\Core\Server\Enums\ControllerType;
use Websyspro\Core\Server\Request;
use Websyspro\Core\Util;
use Attribute;

/**
 * Marks a controller method parameter to be populated from the request body.
 *
 * This attribute is applied to method parameters to indicate that the
 * value should be extracted from the request body (e.g., JSON payload)
 * and optionally mapped to a specific key.
 *
 * Example usage:
 *   public function createUser(
 *       #[Body("username")] string $username,
 *       #[Body("email")] string $email
 *   ) { ... }
 *
 * Extends AbstractParameter, which provides helper methods like getValue()
 * for retrieving and casting the parameter value.
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
class Body extends AbstractParameter
{
  /**
   * Specifies that this attribute is a parameter-level middleware.
   *
   * ControllerType::Parameter indicates that it operates on method parameters.
   *
   * @var ControllerType
   */ 
  public ControllerType $controllerType = ControllerType::Parameter;

  /**
   * Constructor for the Body attribute.
   *
   * @param string|null $key Optional key in the request body to map to the parameter. If null, the parameter name itself is used.
   */  
  public function __construct(
    public readonly string|null $key = null
  ){}
  
  /**
   * Executes the attribute logic to extract the value from the request body.
   *
   * Delegates to AbstractParameter::getValue() to handle:
   *   - Fetching the value from the body
   *   - Casting it to the correct type
   *   - Handling default values or missing keys
   *
   * @param Request $request       The current request object.
   * @param string  $instanceType  The expected type of the parameter.
   *
   * @return mixed The extracted and properly typed value.
   */  
  public function execute(
    Request $request,
    string $paramterName,
    array $paramterTypes = [],
    mixed $paramterDefault = null
  ): array {
    /**
     * Retrieves the raw request body to be used as the source
     * for parameter value resolution.
     */    
    $paramterValue = $request->body();

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
     * Filters resolved parameter values, keeping only valid results.
     *
     * A parameter value is considered valid when:
     * - The resolved type is not null.
     * - The resolved value is not null.
     *
     * Invalid or unresolved entries are removed from the result set.
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