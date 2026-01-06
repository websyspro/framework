<?php

namespace Websyspro\Core\Database\Enums;

/**
 * Database driver enumeration
 * 
 * Defines supported database drivers for PDO connections.
 * Each case represents a specific database type with its corresponding
 * PDO driver string value.
 * 
 * @package Websyspro\Core\Database\Enums
 * @author Websyspro Team
 */
enum ConnectDriver: string {
  /** MySQL database driver */
  case mysql = "mysql";
  
  /** PostgreSQL database driver */
  case postgres = "pgsql";
  
  /** SQL Server database driver */
  case sqlServer = "sqlsrv";
  
  /** DBLib database driver (FreeTDS) */
  case dblib = "dblib";
}