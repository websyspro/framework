<?php

namespace Websyspro\Core\Elements\Tags;

use Websyspro\Core\Collection;

class Html extends AbstractElement
{
  public string $tagElement = "html";

  public function __construct(
    string|array|null $data = [],
    string|array|null $childs = []
  ){
    $this->dataList = new Collection()->merge($data);
    $this->childList = new Collection()->merge($childs);
  }  
}