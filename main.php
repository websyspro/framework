<?php

use Websyspro\Core\Server\HttpServer;
use Websyspro\Test\Account\AccountModule;

$httpServer = new HttpServer();
$httpServer->module(
  modules: [
    AccountModule::class
  ]
);

$httpServer->listen();
