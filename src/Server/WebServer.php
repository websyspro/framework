<?php

namespace Websyspro\Core\Server;

use Websyspro\Core\Elements\Document;
use Websyspro\Core\Elements\Dom;

/**
 * Core HTTP server class for routing and request handling.
 *
 * This class provides a minimalistic framework for defining HTTP routes,
 * handling requests, executing route handlers, and returning structured
 * JSON responses with proper HTTP status codes.
 *
 * It supports both CLI execution (for debugging/logging) and standard
 * API requests.
 */
class WebServer extends AbstractServer
{
  public function bootstrap(
    string $view
  ): void {
    Document::render( [
      Dom::docType(),
      Dom::html()->add( [
        Dom::head()->add( [
          Dom::title( "Websyspro" )
        ]),
        Dom::body()->add( [
          "Hello Word ....."
        ])
      ])
    ]);
  }  
}