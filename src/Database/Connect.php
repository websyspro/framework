<?php

namespace Websyspro\Core\Database;

use Websyspro\Core\Database\Enums\ConnectDriver;
use Websyspro\Core\Server\Exceptions\Error;
use Websyspro\Core\Collection;
use PDOException;
use PDOStatement;
use Exception;
use PDO;

/**
 * Database connection management class
 * 
 * This class provides an interface to connect and execute operations
 * on different types of databases (MySQL, PostgreSQL, SQL Server, etc.)
 * using PDO as abstraction layer.
 * 
 * @package Websyspro\Database
 * @author Websyspro Team
 */
class Connect
{
  /** @var PDO PDO database connection instance */
  private PDO $handle;
  
  /** @var PDOStatement Prepared statement for query execution */
  private PDOStatement $handleState;

  /**
   * Factory method to create a new Connect class instance
   * 
   * @return Connect New Connect class instance
   */
  public static function set(
  ): Connect {
    return new static();
  }

  /**
   * Creates database connection
   * 
   * Establishes PDO connection using environment configurations
   * and handles possible connection errors.
   * 
   * @return bool True if connection was established successfully, false otherwise
   */
  private function create(
  ): bool {
    try {
      // Establishes PDO connection
      $this->handle = $this->connection();
      return true;
    } catch ( PDOException $error ){
      // In case of error, returns false after logging the error
      return Error::InternalServerError(
        $error->getMessage()
      ) instanceof Exception === false;
    }
  }

  /**
   * Creates PDO instance based on configured database type
   * 
   * Reads environment variables to configure connection and uses
   * match pattern to determine correct DSN based on driver.
   * 
   * @return PDO PDO instance configured for the database
   * @throws PDOException If there's an error creating the connection
   */
  private function connection(
  ): PDO {
    // Extracts connection settings from environment variables
    [ $type, $host, $name, $port, $user, $pass ] = [
      getenv( "DATABASE_TYPE" ), getenv( "DATABASE_HOST" ),
      getenv( "DATABASE_NAME" ), getenv( "DATABASE_PORT" ),
      getenv( "DATABASE_USER" ), getenv( "DATABASE_PASS" )
    ];

    // Determines DSN based on database type
    return match($type){
      ConnectDriver::mysql->value => new PDO( "mysql:host={$host};dbname={$name};port={$port};charset=utf8mb4", $user, $pass ),
      ConnectDriver::postgres->value => new PDO( "pgsql:host={$host};dbname={$name};port={$port}", $user, $pass ),
      ConnectDriver::sqlServer->value => new PDO( "sqlsrv:Server={$host},{$port};Database={$name}", $user, $pass ),
      ConnectDriver::dblib->value => new PDO( "dblib:host={$host}:{$port};dbname={$name}", $user, $pass ),
        // MySQL as default if type is not recognized
        default => new PDO( "mysql:host={$host};dbname={$name};port={$port};charset=utf8mb4", $user, $pass )
    };
  }


  /**
   * Returns the configured database name
   * 
   * @return string Database name obtained from environment variable
   */
  public function database(
  ): string {
    return getenv( "DATABASE_NAME" );
  }
  
  /**
   * Executes a SELECT query and returns results
   * 
   * Executes an SQL query and returns results in a Collection.
   * Used for read operations (SELECT).
   * 
   * @param string $sql SQL query to be executed
   * @return Collection Collection containing query results
   */
  public function query(
    string $sql
  ): Collection {
    try {
      // Attempts to establish connection
      if($this->create()){
        // Executes the query
        $this->handleState = (
          $this->handle->query( $sql )
        ); 
        
        // If query was executed successfully, returns results
        if( isset( $this->handleState )){
          return new Collection(
            $this->handleState->fetchAll(
              PDO::FETCH_OBJ // Returns objects instead of arrays
            )
          );
        }
      }
    } catch( PDOException $error ) {
      // In case of error, logs and returns empty collection
      Error::InternalServerError( $error->getMessage());
      return new Collection();
    }

    // Returns empty collection if couldn't execute
    return new Collection();
  }

  /**
   * Executes SQL modification commands (INSERT, UPDATE, DELETE)
   * 
   * Executes commands that modify database data. For INSERT commands
   * that affect only one row, returns the last inserted record ID.
   * 
   * @param string $sql SQL command to be executed
   * @return bool|int True for general success, record ID for single-row INSERT, false for failure
   */
  public function exec(
    string $sql
  ): bool|int {
    try {
      // Attempts to establish connection
      if($this->create()){
        // Executes the SQL command
        $affectedRows = (
          $this->handle->exec(
            $sql
          )
        );

        // If affected exactly one row and it's an INSERT, returns inserted ID
        if($affectedRows === 1){
          if( preg_match( "#^insert#i", trim($sql)) === 1){
            return $this->handle->lastInsertId();
          }
        }
        
        // Returns true to indicate success
        return true;  
      }

      // Returns false if couldn't establish connection
      return false;
    } catch( PDOException $error ){
      // In case of error, logs and returns false
      return Error::InternalServerError(
        $error->getMessage()
      ) instanceof Exception === false;
    }
  }
}