<?php

namespace Websyspro\Test\Views\Users;

use Websyspro\Core\Elements\Shareds\Component;

class UserView extends Component
{
  public static function render(
    string|object|null $routeView = null
  ): object {
    return new static( 
      [
        "Componente UserView"
      ]
    );
  }
}