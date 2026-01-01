<?php

namespace Websyspro\Core\Server;

class File
{
  public function __construct(
    public string $file,
    public string $type,
    public float $size,
    private string $value,
  ){}
}