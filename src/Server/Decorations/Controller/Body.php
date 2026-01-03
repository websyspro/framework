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
     * Delegates the resolution and hydration of the parameter value
     * to the hydrateTypes method.
     *
     * This call centralizes the logic responsible for:
     * - Resolving the correct value from the raw parameter input.
     * - Validating the value against the allowed parameter types.
     * - Applying the default value when necessary.
     * - Returning a properly hydrated and type-safe result.
     *
     * By isolating this behavior, the parameter resolution process
     * remains consistent and reusable across different contexts.
     */ 
    return $this->hydrateTypes(
      $paramterValue,
      $paramterName,
      $paramterTypes,
      $paramterDefault
    ); 
  }
}