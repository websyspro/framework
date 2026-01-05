<?php

namespace Websyspro\Elements\Collectons;

use Websyspro\Commons\DataList;
use Websyspro\Elements\Enums\FlexDirection;

class FlexContainer
extends AbstractElement
{
  public string $tagElement = "div";

  public function __construct(
    FlexDirection $flexDirection = FlexDirection::column,
    int $flexGap = 0
  ){
    $this->cssList   = DataList::create([])->merge([
      "flex-direction" => "{$flexDirection->value}",
      "gap" => "{$flexGap}px",
      "display" => "flex",
      "height" => "100%",
      "width" => "100%"
    ]);
  }   
}