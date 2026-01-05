<?php

namespace Websyspro\Elements\Tags;

use Websyspro\Core\Collection;

class Container extends AbstractElement
{
  public string $tagElement = "div";

  public function __construct(
    string|array|null $classes = [],
    string|array|null $childs = []
  ){
    $this->classList = new Collection()->merge($classes);
    $this->childList = new Collection()->merge($childs);
    $this->cssList   = new Collection()->merge([
      "height" => "100vh",
      "width" => "100vw"
    ]);
  }   
}