<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Websyspro\Core\Server\Enums\ControllerType;
use Websyspro\Core\Server\Request;
use Attribute;


/**
 * Marks a controller class for file validation middleware.
 *
 * This attribute is applied to a controller class to indicate that
 * file uploads handled by this controller should be validated before
 * processing.
 *
 * Usage:
 *   #[FileValidade]
 *   class UploadController { ... }
 *
 * Currently, the execute() method is empty and can be implemented
 * to add custom validation logic for uploaded files.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class FileValidade
{
  /**
   * The type of controller this attribute represents.
   *
   * Defaults to Middleware, indicating that this attribute acts
   * as a pre-processing step before executing endpoint methods.
   *
   * @var ControllerType
   */  
  public ControllerType $controllerType = ControllerType::Middleware;

  /**
   * Constructor for the FileValidade attribute.
   *
   * Currently empty, but can later accept configuration options
   * for validation rules, allowed types, or size limits.
   */  
  public function __construct(
  ){}

  /**
   * Executes the file validation logic for the request.
   *
   * @param Request $request The current request object containing uploaded files.
   *
   * Currently empty, but in a full implementation this method could:
   *   - Validate file types, sizes, or extensions
   *   - Throw errors if validation fails
   *   - Set flags in the request for downstream processing
   */  
  public function execute(
    Request $request
  ): void {}    
}