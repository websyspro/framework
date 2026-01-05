<?php

namespace Websyspro\Test\Api\Account\Controllers;

use Websyspro\Core\Server\Decorations\Controller\AllowAnonymous;
use Websyspro\Core\Server\Decorations\Controller\Authenticate;
use Websyspro\Core\Server\Decorations\Controller\Controller;
use Websyspro\Core\Server\Decorations\Controller\Body;
use Websyspro\Core\Server\Decorations\Controller\Get;
use Websyspro\Core\Server\Decorations\Controller\Param;
use Websyspro\Core\Server\Decorations\Controller\Post;
use Websyspro\Core\Server\Decorations\Controller\Query;

class UserDto {
  public string $body;
  public string $content;
  public int $test;
  public array $access;
}

#[Authenticate]
#[Controller(prefix: "user")]
class UserController
{
  public function __construct(){}

  #[Get(uri: "/:testId")]
  #[AllowAnonymous]
  public function list(
    #[Body] UserDto $body,
    #[Query] array $query,
    #[Param] array $param 
  ): mixed {
    return [ "body" => $body, "query" => $query, "param" => $param ];
  }

  #[Post(uri: "/create")]
  #[AllowAnonymous]
  public function create(
    #[Body] array $items = []
  ): array {
    return $items;
  }
}