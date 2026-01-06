<?php

namespace Websyspro\Core\Entitys\Interfaces;

class IOneToOne
{
  public function __construct(
    public string $key,
    public string $reference,
    public string $referenceKey
  ){}
}