<?php

namespace Websyspro\Test\Views;

use Websyspro\Core\Elements\Shareds\Component;

class BaseContentView extends Component
{
  public static function render(
    string|object|null $routeView = null
  ): object {
    return new static( 
      [
        BaseContentMenuView::render(),
        BaseContentMainView::render( $routeView )
      ]
    );
  }
}