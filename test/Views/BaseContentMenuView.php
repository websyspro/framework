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
        Dom::div( [ "header" ])->add(
          [ "header" ]
        ),
        Dom::div( [ "main" ])->add(
          [
            Dom::div([ "menu-item" ])->add([ "Menu 1" ]),
            Dom::div([ "menu-item" ])->add([ "Menu 2" ]),
            Dom::div([ "menu-item" ])->add([ "Menu 3" ]),
          ]
        ),
      ]
    );
  }
}