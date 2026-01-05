<?php

namespace Websyspro\Core\Elements\Tags;

use Websyspro\Core\Elements\Enums\FlexDirection;
use Websyspro\Core\Collection;

class FlexContainer extends AbstractElement
{
  public string $tagElement = "div";

  public function __construct(
    FlexDirection $flexDirection = FlexDirection::column,
    int $flexGap = 0
  ){
    $this->cssList = new Collection()->merge( [
      "flex-direction" => "{$flexDirection->value}",
      "gap" => "{$flexGap}px",
      "display" => "flex",
      "height" => "100%",
      "width" => "100%"
    ]);
  }   
}