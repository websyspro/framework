<?php

namespace Websyspro\Test\Views\Users;

use Websyspro\Core\Elements\Dom;
use Websyspro\Core\Elements\Shareds\Component;
use Websyspro\Core\Util;

class UserView extends Component
{
  public static function render(
    array $query = []
  ): object {
    return new static( 
      [
        Dom::div()->add(
          [ 
            Dom::h1()->add([
              "Lorem ipsum dolor sit amet"
            ]),
            ...Util::mapper([1,2,3,4,5,6,7,8,9,10], fn() => Dom::paragraph()->add(
              [ "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum." ]
            ))
          ]
        )
      ]
    );
  }
}