<?php

use Websyspro\Core\Server\HttpServer;
use Websyspro\Core\Server\Request;
use Websyspro\Core\Server\Response;

$httpServer = new HttpServer();
$httpServer->get( "/", function (Response $response, Request $request) {
  $response->json( [
    "status" => "success",
    "message" => "Hello World"
  ]);
});
$httpServer->listen();


// http_response_code( 200 );
// header( "Content-Type: application/json" );

// exit(
//   json_encode([
//       "status" => "success",
//       "message" => $httpServer
//     ], JSON_PRETTY_PRINT
//   )
// );