<?php

namespace Websyspro\Core\Entitys\Core\Commons;

use Websyspro\Core\Util;

class GuidV4
{
  public static function get(
  ): string {
    return Util::guidV4();
  }
}