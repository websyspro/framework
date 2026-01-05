<?php

namespace Websyspro\Elements\Collectons;

use Websyspro\Commons\DataList;

class Scripts
extends AbstractElement
{
  public string $tagElement = "script";
  public DataList $stringList;

  public function __construct(
    string|array|null $strings = []
  ){
    $this->stringList = DataList::create([$strings]);
  }
  
  public function get(
  ): string {
    return DataList::create([
      "<{$this->tagElement}>",
        "{$this->stringList->joinNotSpace()}",
      "</{$this->tagElement}>"
    ])->joinNotSpace();
  }  
}