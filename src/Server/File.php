<?php

/**
 * Class File
 *
 * Represents an uploaded or raw file received in an HTTP request.
 * Stores file metadata and content in memory.
 *
 * This class is commonly used when parsing multipart/form-data
 * or raw request bodies.
 *
 * @package Websyspro\Core\Server
 */

namespace Websyspro\Core\Server;

class File
{
  /**
   * File size in kilobytes (KB).
   *
   * @var float
   */  
  public float $size;

  /**
   * File constructor.
   *
   * @param string $file    Original file name
   * @param string $type    MIME type
   * @param string $content Raw file content
   */
  public function __construct(
    public string $file,
    public string $type,
    private string $content,
  ){
    // Calculate file size in KB with 4 decimal precision
    $this->size = (float)bcdiv( 
      \strlen( $content ), 
      1024, 4
    );
  }

  /**
   * Returns the file size in kilobytes (KB).
   *
   * @return float
   */
  public function size(
  ): float {
    return $this->size;
  }

  /**
   * Returns the raw file content.
   *
   * @return string
   */   
  public function content(
  ): string {
    return $this->content;
  }
}