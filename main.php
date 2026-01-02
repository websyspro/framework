<?php

use Websyspro\Core\Server\HttpModule;
use Websyspro\Core\Server\HttpServer;
use Websyspro\Core\Server\Request;
use Websyspro\Core\Server\Response;
use Websyspro\Test\Account\AccountModule;

$httpServer = new HttpServer();
$httpServer->module(
  modules: [
    AccountModule::class
  ]
);


// $httpServer->get( "/product/:productId{int}/detail/:message/test", function( Response $response, Request $request ) use ($httpServer) {
//   $response->json( new HttpModule($httpServer, AccountModule::class) );
// });

$httpServer->listen();

//$httpModule = new HttpModule($httpServer, AccountModule::class);

// $httpServer->get( "/product/test", function( Response $response, Request $request ) {
//   $response->json( $request->body() );
// });