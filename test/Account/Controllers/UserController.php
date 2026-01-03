<?php

namespace Websyspro\Test\Account\Controllers;

use Websyspro\Core\Server\Decorations\Controller\AllowAnonymous;
use Websyspro\Core\Server\Decorations\Controller\Authenticate;
use Websyspro\Core\Server\Decorations\Controller\Controller;
use Websyspro\Core\Server\Decorations\Controller\Body;
use Websyspro\Core\Server\Decorations\Controller\Post;

class UserDto {
  public string $body;
  public string $content;
  public int $test;
}

#[Authenticate]
#[Controller(prefix: "user")]
class UserController
{
  public function __construct(){}

  #[Post(uri: "/")]
  #[AllowAnonymous]
  public function list(
    #[Body] UserDto $items
  ): mixed {
    return $items;
  }

  #[Post("/create")]
  #[AllowAnonymous]
  public function create(
    #[Body] array $items = []
  ): array {
    return $items;
  }
}