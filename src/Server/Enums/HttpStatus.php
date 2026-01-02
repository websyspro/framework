<?php

namespace Websyspro\Core\Server\Enums;

use Websyspro\Core\Util;

/**
 * HTTP Status Codes
 *
 * Provides a complete and strongly-typed representation
 * of HTTP response status codes.
 */
enum HttpStatus: int
{
  // 1xx — Informational
  case CONTINUE = 100;
  case SWITCHING_PROTOCOLS = 101;
  case PROCESSING = 102;
  case EARLY_HINTS = 103;

  // 2xx — Success
  case OK = 200;
  case CREATED = 201;
  case ACCEPTED = 202;
  case NON_AUTHORITATIVE_INFORMATION = 203;
  case NO_CONTENT = 204;
  case RESET_CONTENT = 205;
  case PARTIAL_CONTENT = 206;
  case MULTI_STATUS = 207;
  case ALREADY_REPORTED = 208;
  case IM_USED = 226;

  // 3xx — Redirection
  case MULTIPLE_CHOICES = 300;
  case MOVED_PERMANENTLY = 301;
  case FOUND = 302;
  case SEE_OTHER = 303;
  case NOT_MODIFIED = 304;
  case USE_PROXY = 305;
  case TEMPORARY_REDIRECT = 307;
  case PERMANENT_REDIRECT = 308;

  // 4xx — Client Error
  case BAD_REQUEST = 400;
  case UNAUTHORIZED = 401;
  case PAYMENT_REQUIRED = 402;
  case FORBIDDEN = 403;
  case NOT_FOUND = 404;
  case METHOD_NOT_ALLOWED = 405;
  case NOT_ACCEPTABLE = 406;
  case PROXY_AUTHENTICATION_REQUIRED = 407;
  case REQUEST_TIMEOUT = 408;
  case CONFLICT = 409;
  case GONE = 410;
  case LENGTH_REQUIRED = 411;
  case PRECONDITION_FAILED = 412;
  case PAYLOAD_TOO_LARGE = 413;
  case URI_TOO_LONG = 414;
  case UNSUPPORTED_MEDIA_TYPE = 415;
  case RANGE_NOT_SATISFIABLE = 416;
  case EXPECTATION_FAILED = 417;
  case IM_A_TEAPOT = 418;
  case MISDIRECTED_REQUEST = 421;
  case UNPROCESSABLE_ENTITY = 422;
  case LOCKED = 423;
  case FAILED_DEPENDENCY = 424;
  case TOO_EARLY = 425;
  case UPGRADE_REQUIRED = 426;
  case PRECONDITION_REQUIRED = 428;
  case TOO_MANY_REQUESTS = 429;
  case REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
  case UNAVAILABLE_FOR_LEGAL_REASONS = 451;

  // 5xx — Server Error
  case INTERNAL_SERVER_ERROR = 500;
  case NOT_IMPLEMENTED = 501;
  case BAD_GATEWAY = 502;
  case SERVICE_UNAVAILABLE = 503;
  case GATEWAY_TIMEOUT = 504;
  case HTTP_VERSION_NOT_SUPPORTED = 505;
  case VARIANT_ALSO_NEGOTIATES = 506;
  case INSUFFICIENT_STORAGE = 507;
  case LOOP_DETECTED = 508;
  case NOT_EXTENDED = 510;
  case NETWORK_AUTHENTICATION_REQUIRED = 511;

  /**
   * Determines whether the given HTTP status code represents
   * a successful response.
   *
   * Only explicitly allowed success status codes (2xx) are
   * considered valid successes.
   *
   * @param int $code The HTTP status code.
   * @return bool Returns true if the status code indicates success.
   */  
  public static function isSuccess(
    int $code
  ): bool {
    return Util::inArray(
      HttpStatus::from( $code ), 
      [
        HttpStatus::OK,
        HttpStatus::CREATED,
        HttpStatus::ACCEPTED,
        HttpStatus::NON_AUTHORITATIVE_INFORMATION,
        HttpStatus::NO_CONTENT,
        HttpStatus::RESET_CONTENT,
        HttpStatus::PARTIAL_CONTENT,
        HttpStatus::MULTI_STATUS,
        HttpStatus::ALREADY_REPORTED,
        HttpStatus::IM_USED
      ]
    );
  }

  /**
   * Determines whether the given HTTP status code represents
   * an internal server error.
   *
   * This method checks if the status code belongs to the
   * 5xx (Server Error) category.
   *
   * @param int $code The HTTP status code.
   * @return bool Returns true if the status code indicates an internal server error.
   */  
  public static function isInternalError(
    int $code
  ): bool {
    return Util::inArray(
      HttpStatus::from( $code ), 
      [
        HttpStatus::INTERNAL_SERVER_ERROR,
        HttpStatus::NOT_IMPLEMENTED,
        HttpStatus::BAD_GATEWAY,
        HttpStatus::SERVICE_UNAVAILABLE,
        HttpStatus::GATEWAY_TIMEOUT,
        HttpStatus::HTTP_VERSION_NOT_SUPPORTED,
        HttpStatus::VARIANT_ALSO_NEGOTIATES,
        HttpStatus::INSUFFICIENT_STORAGE,
        HttpStatus::LOOP_DETECTED,
        HttpStatus::NOT_EXTENDED,
        HttpStatus::NETWORK_AUTHENTICATION_REQUIRED
      ]
    );
  }
  
  /**
   * Resolves the public-facing error message based on the HTTP status code.
   *
   * For internal server errors (5xx), a generic and safe message is returned
   * to avoid exposing sensitive internal details.
   *
   * For non-internal errors (4xx), the original message is returned as-is,
   * since these errors are safe and relevant to the client.
   *
   * @param int $code The HTTP status code.
   * @param string $message The original error message.
   * @return string The resolved public error message.
   */
  public static function resolvePublicMessage(
    int $code,
    string $message
  ): string {
    if( HttpStatus::isInternalError( $code )) {
      return match (HttpStatus::from($code)) {
        HttpStatus::INTERNAL_SERVER_ERROR => 'Internal server error',
        HttpStatus::NOT_IMPLEMENTED => 'Not implemented',
        HttpStatus::BAD_GATEWAY => 'Bad gateway',
        HttpStatus::SERVICE_UNAVAILABLE => 'Service unavailable',
        HttpStatus::GATEWAY_TIMEOUT => 'Gateway timeout',
          default => 'Internal server error',
      };
    }

    return $message;
  }  
}