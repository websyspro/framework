<?php

namespace Websyspro\Core\Elements\Tags;

use Websyspro\Core\Collection;

class Meta
extends AbstractElement
{
  public string $tagElement = "meta";
  public bool $isEndTag = false;

  public function __construct(
    string|array|null $data = []
  ){
    $this->dataList = new Collection()->merge( $data );
  }  
}