<?php

namespace Websyspro\Elements\Collectons;

use Websyspro\Commons\DataList;

class Meta
extends AbstractElement
{
  public string $tagElement = "meta";
  public bool $isEndTag = false;

  public function __construct(
    string|array|null $data = []
  ){
    $this->dataList = DataList::create([])->merge($data);
  }  
}