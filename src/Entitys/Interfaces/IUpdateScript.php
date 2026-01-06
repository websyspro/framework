<?php

namespace Websyspro\Core\Entitys\Interfaces;

use Websyspro\Core\Entitys\Enums\ScriptType;

class IUpdateScript
{
  public function __construct(
    public string $sql,
    public string $message,
    public ScriptType $scriptType
  ){}
}