<?php 

namespace Websyspro\Core\Elements\Shareds;

use Websyspro\Core\Elements\Dom;

class StructureStyle
{
  public function __construct(
    private string $styleName,
    private string $styleText
  ){}

  public function get(
  ): object {
    return Dom::style( $this->styleText )->data([
      "component-name" => $this->styleName
    ]);
  }
}