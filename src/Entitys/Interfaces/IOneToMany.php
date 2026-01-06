<?php

namespace Websyspro\Core\Entitys\Interfaces;

class IOneToMany
{
  public function __construct(
    public string $key,
    public string $reference,
    public string $referenceKey
  ){}
}