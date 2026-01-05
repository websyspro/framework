<?php

namespace Websyspro\Test\Views\Users;

use Websyspro\Core\Elements\Dom;
use Websyspro\Core\Elements\Shareds\Component;

class UserView extends Component
{
  public static function render(
    array $query = []
  ): object {
    return new static( 
      [
        Dom::flexContainer()->add([
          Dom::flexItem()->add([
            "UserID: $query[userId]"
          ])
        ])
      ]
    );
  }
}