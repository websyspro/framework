<?php

namespace Websyspro\Core\Entitys\Core\Commons;

class Now
{
  public static function get(
  ): string {
    return date( "d/m/Y H:i:s" );
  }
}