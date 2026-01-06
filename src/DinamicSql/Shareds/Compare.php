<?php

namespace Websyspro\Core\DynamicSql\Shareds;

use Websyspro\Core\Collection;

class Compare
{
  public Collection $equals;

  public function __construct(
    public string $value
  ){
    $this->define();
  }
  
  public function define(
  ): void {
    $this->equals = new Collection(
      preg_split("/,/", $this->value, -1, (
        PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
      ))
    );
    
    $this->equals->mapper(
      fn(string $equal) => new Collection(
        preg_split("/=/", trim($equal), 2, (
          PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
        ))
      )->mapper(fn(string $equalItem) => trim($equalItem))
    );    
  }
}