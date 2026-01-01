<?php

namespace Websyspro\Core\Server;

class HttpServer
{
  public function __construct(
    public AcceptHeader $acceptHeader = new AcceptHeader(),
    public Response $response = new Response(),
    public Request $request = new Request()
  ){}

  public function listen(
  ): void {}
}