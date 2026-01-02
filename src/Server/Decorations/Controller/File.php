<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Websyspro\Core\Server\Enums\ControllerType;
use Websyspro\Core\Server\Request;
use Attribute;

/**
 * Marks a controller method parameter to be populated from uploaded files.
 *
 * This attribute is applied to method parameters to indicate that the
 * value should be extracted from the request's uploaded files.
 * Optionally, a specific key can be specified to select a particular file.
 *
 * Example usage:
 *   public function uploadFile(
 *       #[File("avatar")] UploadedFile $avatar
 *   ) { ... }
 *
 * Extends AbstractParameter, which provides helper methods like getValue()
 * to retrieve, cast, and validate parameter values.
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
class File extends AbstractParameter
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
   * Constructor for the File attribute.
   *
   * @param string|null $key Optional key in the uploaded files array to map to the parameter.
   *                         If null, the parameter name itself is used.
   */
  public function __construct(
    public readonly string|null $key = null
  ){}

  /**
   * Executes the attribute logic to extract the value from the request files.
   *
   * Delegates to AbstractParameter::getValue() to handle:
   *   - Fetching the file from $request->files()
   *   - Casting or validating the file object
   *   - Handling missing keys or defaults
   *
   * @param Request $request       The current request object.
   * @param string  $instanceType  The expected type of the parameter.
   *
   * @return mixed The extracted and properly typed uploaded file.
   */
  public function execute(
    Request $request,
    string $instanceType
  ): mixed {
    return $this->getValue(
      $request->files(), 
      $instanceType, 
      $this->key
    );
  }
}
