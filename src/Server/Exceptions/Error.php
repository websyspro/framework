<?php

namespace Websyspro\Core\Server\Exceptions;

use Exception;
use Websyspro\Core\Server\Enums\HttpStatus;

class Error
{
  public static function BadRequest(
    string $message
  ): Exception {
    return throw new Exception(
      message: $message, code: HttpStatus::BAD_REQUEST->value
    );
  }

  public static function NotFound(
    string $message
  ): Exception {
    return throw new Exception(
      message: $message, code: HttpStatus::NOT_FOUND->value
    );
  }

  public static function ServiceUnavailableError(
    string $message
  ): Exception {
    return throw new Exception(
      message: $message, code: HttpStatus::SERVICE_UNAVAILABLE->value
    );
  }  

  public static function InternalServerError(
    string $message
  ): Exception {
    return throw new Exception(
      message: $message, code: HttpStatus::INTERNAL_SERVER_ERROR->value
    );
  }

  public static function Unauthorized(
    string $message
  ): Exception {
    return throw new Exception(
      message: $message, code: HttpStatus::UNAUTHORIZED->value
    );
  }
}