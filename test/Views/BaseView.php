<?php

namespace Websyspro\Test\Views;

use Websyspro\Core\Elements\Shareds\Component;

class BaseView extends Component
{
  public static function render(
    string|object|null $routeView = null
  ): object {
    return new static( 
      [
        $routeView
      ]
    );
  }
}