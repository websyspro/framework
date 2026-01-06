<?php

namespace Websyspro\Core\Elements\Components;

use Websyspro\Core\Elements\Shareds\Component;

class Column extends Component
{
  public function __construct(
    public string $text,
    public string $name,
    public int $size = 0 
  ){}
}