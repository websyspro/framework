<?php

namespace Websyspro\Core\Elements\Shareds;

use Websyspro\Core\Elements\Tags\Head;
use Websyspro\Core\Elements\Dom;
use Websyspro\Core\Collection;
use ReflectionClass;

class Component
{
  protected string $tagElement = "div";

  public function __construct(
    private array $childs = []
  ){
    $this->getAssets();
  }

  private function getBasePath(
  ): string {
    return dirname((
      new ReflectionClass($this)
    )->getFileName());
  } 
  
  private function getFilesByExt(
    string $ext
  ): string {
    $contents = new Collection();
    $globFind = sprintf(
      "{$this->getBasePath()}%s*.%s", DIRECTORY_SEPARATOR, $ext
    );

    foreach (glob($globFind) as $file) {
      $code = trim(file_get_contents($file));
      if ($code !== '') {
        $contents->merge([ $code ]);
      }
    }

    return $contents->joinNotSpace();
  }  

  private function getAssets(
  ): array {
    $className = new Collection(
      explode("\\", get_class($this))
    )->last();

    Head::registerStyle($className, $this->getFilesByExt("css"));
    Head::registerScript($className, $this->getFilesByExt("js"));
    return [];
  }

  private function getClassName(
  ): string {
    return new Collection(
      explode(
        "\\", 
        get_class($this)
      )
    )->last();
  } 

  private function getChilds(
  ): array {
    return array_merge(
      $this->getAssets(), $this->childs
    );
  }

  public function get(
  ): string {
    return Dom::div( [ $this->getClassName() ] )
      ->tag($this->tagElement)
      ->add($this->getChilds())->get();
  }
}