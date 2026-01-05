<?php

use Websyspro\Core\Server\HttpServer;
use Websyspro\Test\Api\Account\AccountModule;
use Websyspro\Test\Api\Markting\Controllers\ProductController;

$httpServer = new HttpServer();
$httpServer->module(
  [
    AccountModule::class,
    ProductController::class
  ]
);

$httpServer->listen();