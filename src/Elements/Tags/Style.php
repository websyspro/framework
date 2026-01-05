<?php

namespace Websyspro\Elements\Collectons;

use Websyspro\Commons\DataList;

class Style
extends AbstractElement
{
  public string $tagElement = "style";
  public DataList $stringList;

  public function __construct(
    string|array|null $strings = []
  ){
    $this->stringList = DataList::create([$strings]);
  }
  
  public function get(
  ): string {
    return DataList::create([
      "<{$this->tagElement}{$this->getAttributes()}>",
        "{$this->stringList->joinNotSpace()}",
      "</{$this->tagElement}>"
    ])->joinNotSpace();
  }  
}