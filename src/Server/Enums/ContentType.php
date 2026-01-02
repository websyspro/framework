<?php

/**
 * Enum ContentType
 *
 * Defines the most common HTTP Content-Types used
 * in requests and responses.
 *
 * Centralizes Content-Type values to avoid
 * hardcoded strings across the framework.
 */

namespace Websyspro\Core\Server\Enums;

enum ContentType: string
{
  case JSON = "application/json";
  case FORM_URLENCODED = "application/x-www-form-urlencoded";
  case MULTIPART_FORM_DATA = "multipart/form-data";
  case TEXT_PLAIN = "text/plain";
  case TEXT_HTML = "text/html";
  case XML = "application/xml";
  case OCTET_STREAM = "application/octet-stream";
  case JAVASCRIPT = "application/javascript";
  case PDF = "application/pdf";
  case CSV = "text/csv";
}