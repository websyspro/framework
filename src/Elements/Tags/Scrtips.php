<?php

namespace Websyspro\Elements\Tags;

use Websyspro\Core\Collection;

class Script extends AbstractElement
{
  public string $tagElement = "script";
  public Collection $stringList;

  public function __construct(
    string|array|null $strings = []
  ){
    $this->stringList = new Collection(
      [ $strings ]
    );
  }
  
  public function get(
  ): string {
    return new Collection( [
      "<{$this->tagElement}{$this->getAttributes()}>",
        "{$this->stringList->joinNotSpace()}",
      "</{$this->tagElement}>"
    ])->joinNotSpace();
  }  
}