<?php

namespace Websyspro\Test\Views;

use Websyspro\Core\Elements\Dom;
use Websyspro\Core\Elements\Shareds\Component;

class BaseContentMenuView extends Component
{
  public static function render(
  ): object {
    return new static( 
      [
        Dom::div( 
          ["header" ]
        )->add(
          [ "BaseHeaderView" ]
        )
      ]
    );
  }
}