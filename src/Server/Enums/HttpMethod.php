<?php

/**
 * Defines all supported HTTP request methods.
 *
 * This enum centralizes HTTP verbs to avoid
 * magic strings and improve type safety
 * across the routing and request layers.
 */

namespace Websyspro\Core\Server\Enums;

enum HttpMethod: string
{
  case GET = 'GET';
  case POST = 'POST';
  case PUT = 'PUT';
  case PATCH = 'PATCH';
  case DELETE = 'DELETE';
  case OPTIONS = 'OPTIONS';
  case HEAD = 'HEAD';
}
