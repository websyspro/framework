<?php

namespace Websyspro\Elements\Collectons;

use Websyspro\Commons\DataList;

class Html
extends AbstractElement
{
  public string $tagElement = "html";

  public function __construct(
    string|array|null $data = [],
    string|array|null $childs = []
  ){
    $this->dataList = DataList::create([])->merge($data);
    $this->childList = DataList::create([])->merge($childs);
  }  
}