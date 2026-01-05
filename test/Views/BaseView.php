<?php

namespace Websyspro\Test\Views;

use Websyspro\Core\Elements\Dom;
use Websyspro\Core\Elements\Enums\FlexDirection;
use Websyspro\Core\Elements\Shareds\Component;

class BaseView extends Component
{
  public static function render(
    string|object|null $routeView = null
  ): object {
    return new static( 
      [
        Dom::flexContainer(
          FlexDirection::row
        )->add( 
          [
            Dom::flexItem()->add(
              [ 
                Dom::div()->add( [ "Flex Item 1" ])
              ]
            ),
            Dom::flexItem()->add(
              [ 
                Dom::div()->add( [
                  $routeView ?? Dom::div()->add( [ "Flex Item 2" ])
                ])
              ]
            )
          ]
        )
      ]
    );
  }
}