<?php

namespace Websyspro\Test\Account\Controllers;

use Websyspro\Core\Server\Decorations\Controller\AllowAnonymous;
use Websyspro\Core\Server\Decorations\Controller\Authenticate;
use Websyspro\Core\Server\Decorations\Controller\Controller;
use Websyspro\Core\Server\Decorations\Controller\Get;
use Websyspro\Core\Server\Decorations\Controller\Post;

#[Authenticate]
#[Controller(prefix: "user")]
class UserController
{
  public function __construct(){}

  #[Get(uri: "/")]
  #[AllowAnonymous]
  public function list(
  ): array {
    return [];
  }

  #[Post("/create")]
  public function create(
  ): array {
    return [];
  }
}