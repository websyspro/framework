<?php

namespace Websyspro\Test\Routers\Account\Controllers;

use Websyspro\Core\Server\Decorations\Controller\AllowAnonymous;
use Websyspro\Core\Server\Decorations\Controller\Authenticate;
use Websyspro\Core\Server\Decorations\Controller\Controller;
use Websyspro\Core\Server\Decorations\Controller\Query;
use Websyspro\Core\Server\Decorations\Controller\Route;
use Websyspro\Test\Views\Users\UserView;

#[Authenticate]
#[Controller(prefix: "user")]
class UserController
{
  public function __construct(){}

  #[Route(uri: "/")]
  #[AllowAnonymous]
  public function list(
    #[Query] array $query = []
  ): mixed {
    return UserView::render( $query );
  }
}