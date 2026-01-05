<?php

use Websyspro\Core\Server\WebServer;
use Websyspro\Test\Routers\Account\AccountModule;
use Websyspro\Test\Views\BaseView;

/** 
 * Creates a new instance of the WebServer.
 * This object is responsible for initializing and running
 * the web application lifecycle.
 */
$webServer = new WebServer();

/**
 * Registers application modules.
 * The modules array can be used to configure routes,
 * services, middlewares, or other application components.
 */
$webServer->module( 
  [
    AccountModule::class
  ]
);

/**
 * Bootstraps the web application using the Base view class.
 * This defines the main entry point for rendering views
 * and handling incoming HTTP requests.
 */
$webServer->bootstrap(
  BaseView::class
);

/**
 * Starts the HTTP server and begins listening for incoming requests.
 */
$webServer->load();