<?php

namespace Websyspro\Test\Views\Users;

use Websyspro\Core\Elements\Shareds\Component;

class UserView extends Component
{
  public static function render(
    array $query = []
  ): object {
    return new static( 
      [
        "Componente UserView - $query[userId]"
      ]
    );
  }
}