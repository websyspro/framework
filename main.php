<?php

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
$httpServer->listen();


// $httpServer->get( "/product/:productId{int}/detail/:message/test", function( Response $response, Request $request ) {
//   $response->json( $request->param() );
// });

// $httpServer->get( "/product/test", function( Response $response, Request $request ) {
//   $response->json( $request->body() );
// });