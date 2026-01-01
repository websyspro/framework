<?php

use Websyspro\Core\Server\HeaderAccept;

http_response_code(200);
header("Content-Type: application/json");

exit(
  json_encode([
      "status" => "success",
      "message" => new HeaderAccept()
    ], JSON_PRETTY_PRINT
  )
);