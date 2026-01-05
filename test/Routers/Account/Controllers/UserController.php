<?php

namespace Websyspro\Test\Routers\Account\Controllers;

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
class UserController
{
  public function __construct(){}

  #[Get(uri: "/:testId")]
  #[AllowAnonymous]
  public function list(
  ): mixed {
    return [ "" ];
  }
}