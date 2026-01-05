<?php

namespace Websyspro\Elements;

use Websyspro\Core\Collection;
use Websyspro\Elements\Tags\AbstractElement;

class Document
{
  public static function render(
    array $childs = []
  ): void {
    print new Collection( $childs )
      ->mapper(fn( AbstractElement $abstractElement ) => $abstractElement->get())
      ->joinNotSpace();
  }
}