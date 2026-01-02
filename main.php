<?php

use Websyspro\Core\Server\HttpServer;
use Websyspro\Core\Server\Request;
use Websyspro\Core\Server\Response;

$httpServer = new HttpServer();
$httpServer->get( "/product/:productId{int}/detail/:message/test", function( Response $response, Request $request ) {
  $response->json( $request->param() );
});

$httpServer->listen();