<?php

namespace Websyspro\Core\Server\Logger;

use Websyspro\Core\Server\Logger\Enums\LogType;

class Message
{
  public static function infors(
    LogType $logType,
    string $logText
  ): bool {
    return Log::debug(
      $logType,
      $logText
    );
  }

  public static function error(
    LogType $logType,
    string $logText     
  ): bool {
    return Log::fail($logType, $logText);
  }
}