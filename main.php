<?php

use Websyspro\Core\Server\HttpServer;

$httpServer = new HttpServer();
$httpServer->get("/", function () {});

http_response_code( 200 );
header( "Content-Type: application/json" );

exit(
  json_encode([
      "status" => "success",
      "message" => $httpServer
    ], JSON_PRETTY_PRINT
  )
);