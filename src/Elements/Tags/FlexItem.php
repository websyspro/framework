<?php

namespace Websyspro\Elements\Collectons;

use Websyspro\Commons\DataList;

class FlexItem
extends AbstractElement
{
  public string $tagElement = "div";

  public function __construct(
    private int $size = 0,
    private bool $resized = true
  ){
    $this->cssList = DataList::create([])
      ->merge($this->defaultCSS())
      ->merge($this->defaultCSSResized())
      ->merge($this->defaultCSSFlex());
  }
  
  private function defaultCSS(
  ): array {
    return [
      "justify-content" => "center",
      "align-items" => "center",
      "overflow-y" => "auto",
      "display" => "flex"
    ];
  }

  private function defaultCSSResized(
  ): array {
    if($this->resized === false){
      return [
        "over-flow-y" => "auto",
        "min-height" => "0px"
      ];
    }

    return [];
  }

  private function defaultCSSFlex(
  ): array {
    if($this->size !== 0){
      return [
        "flex" => "0 0 {$this->size}px"
      ];
    }

    return [ "flex" => "1" ];
  }
}