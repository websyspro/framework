<?php

namespace Websyspro\Core\Server;

class File
{
  public float $size;

  public function __construct(
    public string $file,
    public string $type,
    private string $content,
  ){
    $this->size = (float)bcdiv( 
      \strlen( $content ), 
      1024, 4
    );
  }

  /**
   * @private Size
   * 
   * @param none
   * @return none
   * **/ 
  public function size(
  ): float {
    return $this->size;
  }

  /**
   * @private Content
   * 
   * @param none
   * @return none
   * **/   
  public function content(
  ): string {
    return $this->content;
  }
}