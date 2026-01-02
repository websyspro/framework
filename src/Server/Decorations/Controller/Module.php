<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Module
{
  public function __construct(
    public readonly array $controllers
  ){}
}