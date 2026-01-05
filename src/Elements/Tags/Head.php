<?php

namespace Websyspro\Core\Elements\Tags;

use Websyspro\Core\Collection;
use Websyspro\Core\Elements\Enums\ConstHtmls;
use Websyspro\Core\Elements\Dom;

class Head extends AbstractElement
{
  public static Collection $styles;
  public static Collection $scripts;
  
  public string $tagElement = "head";

  public static function registerStyle(
    string $styleName,
    string $styleText
  ): void {
    if(isset(Head::$styles) === false){
      Head::$styles = new Collection();
    }

    Head::$styles->add(
      Dom::style( $styleText )->data([
        "data-component" => $styleName
      ])
    );
  }

  public static function registerScript(
    string $scriptName,
    string $scriptText
  ): void {
    if(isset(Head::$scripts) === false){
      Head::$scripts = new Collection();
    }

    Head::$scripts->add(
      Dom::script( $scriptText )->data([
        "data-component" => $scriptName
      ])
    );
  }

  public function getChilds(
  ): string {
    if(isset($this->childList) === false){
      return ConstHtmls::emptyHtml->value;
    }

    if(isset(Head::$styles)){
      $this->childList->merge(Head::$styles->all());
    }

    if(isset(Head::$scripts)){
      $this->childList->merge(Head::$scripts->all());
    }    

    return $this->childList
      ->mapper(
        function(mixed $child){
          if(is_string($child) === true){
            return $child;
          }

          return $child->get();
        }
      )->joinNotSpace();
  } 
}