<?php

namespace Websyspro\Elements\Collectons;

use Websyspro\Commons\DataList;

class Container
extends AbstractElement
{
  public string $tagElement = "div";

  public function __construct(
    string|array|null $classes = [],
    string|array|null $childs = []
  ){
    $this->classList = DataList::create([])->merge($classes);
    $this->childList = DataList::create([])->merge($childs);
    $this->cssList   = DataList::create([])->merge([
      "height" => "100vh",
      "width" => "100vw"
    ]);
  }   
}