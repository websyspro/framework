<?php

use Websyspro\Core\Server\HttpServer;
use Websyspro\Core\Server\Request;
use Websyspro\Core\Server\Response;

$httpServer = new HttpServer();
$httpServer->get( "/send", function( Response $response, Request $request ) {
  $response->json( [
    "success" => true,
    "message" => $request->body()
  ]);
});

$httpServer->listen();