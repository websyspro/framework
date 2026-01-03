<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Attribute;
use Websyspro\Core\Server\Enums\ControllerType;
use Websyspro\Core\Server\Request;


/**
 * Marks a controller method parameter to be populated from the query string.
 *
 * This attribute is applied to method parameters to indicate that the
 * value should be extracted from the request's query parameters (GET parameters)
 * and optionally mapped to a specific key.
 *
 * Example usage:
 *   public function getUser(
 *       #[Query("id")] int $userId
 *   ) { ... }
 *
 * Extends AbstractParameter, which provides helper methods like getValue()
 * for retrieving, casting, and validating parameter values.
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
class Query extends AbstractParameter
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
   * Constructor for the Query attribute.
   *
   * @param string|null $key Optional key in the query string to map to the parameter. If null, the parameter name itself is used.
   */  
  public function __construct(
    public readonly string|null $key = null
  ){}
  
  /**
   * Executes the attribute logic to extract the value from the query string.
   *
   * Delegates to AbstractParameter::getValue() to handle:
   *   - Fetching the value from $request->query()
   *   - Casting it to the correct type
   *   - Handling default values or missing keys
   *
   * @param Request $request The current request object.
   * @param string  $instanceType The expected type of the parameter.
   *
   * @return mixed The extracted and properly typed query parameter value.
   */  
  public function execute(
    Request $request,
    string $instanceType,
    mixed $defaultValue = null
  ): mixed {
    return $this->getValue(
      $request->query(), 
      $instanceType,
      $defaultValue,
      $this->key
    );
  }
}