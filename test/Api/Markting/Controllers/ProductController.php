<?php

namespace Websyspro\Test\Api\Markting\Controllers;

use Websyspro\Core\Server\Decorations\Controller\AllowAnonymous;
use Websyspro\Core\Server\Decorations\Controller\Authenticate;
use Websyspro\Core\Server\Decorations\Controller\Controller;
use Websyspro\Core\Server\Decorations\Controller\Body;
use Websyspro\Core\Server\Decorations\Controller\Get;
use Websyspro\Core\Server\Decorations\Controller\Post;

#[Authenticate]
#[Controller(prefix: "product")]
class ProductController
{
  public function __construct(){}

  #[Get(uri: "/")]
  #[AllowAnonymous]
  public function list(
  ): mixed {
    return [ "Hello word!!! -----" ];
  }

  #[Post(uri: "/create")]
  #[AllowAnonymous]
  public function create(
    #[Body] array $items = []
  ): array {
    return $items;
  }
}