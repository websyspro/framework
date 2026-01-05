<?php

namespace Websyspro\Test\Api\Markting\Controllers;

use Websyspro\Core\Server\Decorations\Controller\AllowAnonymous;
use Websyspro\Core\Server\Decorations\Controller\Authenticate;
use Websyspro\Core\Server\Decorations\Controller\Controller;
use Websyspro\Core\Server\Decorations\Controller\Body;
use Websyspro\Core\Server\Decorations\Controller\Get;
use Websyspro\Core\Server\Decorations\Controller\Param;
use Websyspro\Core\Server\Decorations\Controller\Post;
use Websyspro\Core\Server\Decorations\Controller\Query;

#[Authenticate]
#[Controller(prefix: "user")]
class ProductController
{
  public function __construct(){}

  #[Get(uri: "/:testId")]
  #[AllowAnonymous]
  public function list(
    #[Body] object $body,
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