<?php

namespace Websyspro\Test\Routers\Account\Controllers;

use Websyspro\Core\Server\Decorations\Controller\AllowAnonymous;
use Websyspro\Core\Server\Decorations\Controller\Authenticate;
use Websyspro\Core\Server\Decorations\Controller\Controller;
use Websyspro\Core\Server\Decorations\Controller\Get;
use Websyspro\Test\Views\Users\UserView;

#[Authenticate]
#[Controller(prefix: "user")]
class UserController
{
  public function __construct(){}

  #[Get( uri: "/" )]
  #[AllowAnonymous]
  public function list(
  ): mixed {
    return UserView::render();
  }
}