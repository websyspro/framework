<?php

namespace Websyspro\Elements\Tags;

use Websyspro\Core\Collection;

class Scripts extends AbstractElement
{
  public string $tagElement = "script";
  public Collection $stringList;

  public function __construct(
    string|array|null $strings = []
  ){
    $this->stringList = new Collection([$strings]);
  }
  
  public function get(
  ): string {
    return new Collection( [
      "<{$this->tagElement}>",
        "{$this->stringList->joinNotSpace()}",
      "</{$this->tagElement}>"
    ])->joinNotSpace();
  }  
}