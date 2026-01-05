<?php

namespace Websyspro\Elements;

use Websyspro\Commons\DataList;
use Websyspro\Elements\Collectons\AbstractElement;

class Document
{
  public static function render(
    array $childs = []
  ): void {
    print DataList::create($childs)
      ->mapper(fn( AbstractElement $abstractElement ) => $abstractElement->get())
      ->joinNotSpace();
  }
}