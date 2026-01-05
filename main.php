<?php

use Websyspro\Core\Server\HttpServer;
use Websyspro\Test\Api\Account\AccountModule;
use Websyspro\Test\Api\Markting\Controllers\ProductController;

/**
 * Creates a new instance of the HTTP server.
 * This server is responsible for handling incoming HTTP requests
 * and dispatching them to the appropriate modules and controllers.
 */
$httpServer = new HttpServer();

/**
 * Registers API modules and controllers.
 * These classes define routes, handlers, and business logic
 * that will be exposed through the HTTP server.
 */
$httpServer->module(
  [
    AccountModule::class,
    ProductController::class
  ]
);

/**
 * Starts the HTTP server and begins listening for incoming requests.
 */
$httpServer->listen();