<?php

use Websyspro\Core\Server\AcceptHeader;

http_response_code( 200 );
header( "Content-Type: application/json" );

exit(
  json_encode([
      "status" => "success",
      "message" => new AcceptHeader()
    ], JSON_PRETTY_PRINT
  )
);