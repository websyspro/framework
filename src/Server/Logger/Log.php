<?php

namespace Websyspro\Core\Server\Logger;

use Websyspro\Core\Server\Logger\Enums\LogType;

class Log
{
  public static float $startTimer;

  private static function setStartTimer(
  ): void {
    Log::$startTimer = microtime(true);
  }

  public static function getNowTimer(
  ): int { 
    $starDiff = round(( 
      microtime(true) - Log::$startTimer
    ) * 1000);

    Log::setStartTimer(); 
    return $starDiff;
  }

  private static function getIp(
  ): string {
    return $_SERVER['REMOTE_ADDR'] ?? "::1";
 
  }

  private static function getAddr(
  ): string {
    return $_SERVER['REMOTE_PORT'] ?? "00000";
  }  

  private static function getNow(
  ): string {
    return date( "[D M  j H:i:s Y]" );
  }

  private static function isStartTimer(
  ): void {
    if(isset(Log::$startTimer) === false){
      Log::setStartTimer();
    }
  }


  public static function debug(
    LogType $type,
    string $message 
  ): bool {
    Log::isStartTimer();
    fwrite( fopen('php://stdout', 'w'), (
      sprintf("\x1b[37m%s\x1b[32m [%s]:%s \x1b[33m[{$type->value}] \x1b[32m{$message}\x1b[37m \x1b[37m+%sms\n", 
        ... [
          Log::getNow(),
          Log::getIp(),
          Log::getAddr(),
          Log::getNowTimer()
        ]
      )
    ));

    Log::getNowTimer();

    return true;
  }

  public static function fail(
    LogType $type,
    string $message     
  ): bool {
    Log::isStartTimer();
    fwrite( fopen('php://stdout', 'w'), (
      sprintf( "\x1b[37m%s\x1b[32m [%s]:%s \x1b[33m[{$type->value}] \x1b[31m{$message} \x1b[37m+%sms\n",
        ... [
          Log::getNow(),
          Log::getIp(),
          Log::getAddr(),
          Log::getNowTimer()
        ]
      )
    ));

    Log::getNowTimer();

    return false;
  }
}