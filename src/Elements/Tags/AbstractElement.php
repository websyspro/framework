<?php

namespace Websyspro\Core\Elements\Tags;

use Websyspro\Core\Elements\Enums\ConstHtmls;
use Websyspro\Core\Collection;

class AbstractElement
{
  protected string $tagElement = "div";
  public bool $isEndTag = true;
  public array $isElementEndBar = [
    "img"
  ];

  public Collection $childList;
  public Collection $classList;
  public Collection $dataList;
  public Collection $cssList;
  public Collection $eventList;

  public function __construct(
    string|array|null $classes = [],
    string|array|null $childs = []
  ){
    $this->classList = new Collection( $classes );
    $this->childList = new Collection( $childs );
  }

  public function tag(
    string $tagElement
  ): mixed {
    $this->tagElement = $tagElement;
    return $this;
  }

  public function add(
    array $childs = []
  ): mixed {
    if(isset($this->childList) === false){
      $this->childList = new Collection();
    }

    $this->childList->merge($childs);
    return $this;     
  }

  public function css(
    array $css = []
  ): mixed {
    if(isset($this->cssList) === false){
      $this->cssList = new Collection();
    }

    $this->cssList->merge($css);
    return $this; 
  }

  public function data(
    array $data = []
  ): mixed {
    if(isset($this->dataList) === false){
      $this->dataList = new Collection();
    }

    $this->dataList->merge($data);
    return $this; 
  }

  public function events(
    array $event = []
  ): mixed {
    if(isset($this->eventList) === false){
      $this->eventList = new Collection();
    }

    $this->dataList->merge($event);
    return $this;
  }

  public function getClasses(
  ): string {
    if(isset($this->classList) === false){
      return ConstHtmls::emptyHtml->value;
    }

    if($this->classList->exist() === true){
      return sprintf("class=\"%s\"", (
        $this->classList->joinWithSpace()
      ));    
    }

    return ConstHtmls::emptyHtml->value;
  }

  public function getDatas(
  ): string {
    if(isset($this->dataList) === false){
      return ConstHtmls::emptyHtml->value;
    }
    
    return $this->dataList->mapper(
      fn(string $value, string $key) => (
        "{$key}=\"{$value}\""
      )
    )->joinWithComma(); 
  }  

  private function getCsss(
  ): string {
    if(isset($this->cssList) === false){
      return ConstHtmls::emptyHtml->value;
    }

    $csss = $this->cssList->mapper(
      fn(string $value, string $key) => (
        "{$key}:{$value}"
      )
    )->join(";");

    return "style=\"{$csss}\""; 
  }

  private function getEvents(
  ): string {
    if(isset($this->eventList) === false){
      return ConstHtmls::emptyHtml->value;
    }

    return $this->eventList->mapper(
      fn(string $value, string $key) => (
        "on{$key}=\"{$value}\""
      )
    )->joinWithComma();    
  }  
  
  public function getChilds(
  ): string {
    if(isset($this->childList) === false){
      return ConstHtmls::emptyHtml->value;
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

  public function getAttributes(
  ): string {
    $attributes = (
      new Collection([
        $this->getClasses(),
        $this->getEvents(),
        $this->getDatas(),
        $this->getCsss()
      ])->where(
        fn(string $attribute) => (
          empty(trim($attribute)) === false
        )
      )
    );

    if($attributes->exist() === false){
      return ConstHtmls::emptyHtml->value;
    }

    return new Collection([
      ConstHtmls::emptyHtml->value, $attributes->joinWithSpace()
    ])->joinWithSpace();
  }

  public function get(
  ): string {
    if($this->isEndTag === false){
      if(in_array($this->tagElement, $this->isElementEndBar) === true){
        return new Collection([
          "<{$this->tagElement}{$this->getAttributes()}/>"
        ])->joinNotSpace();
      } else {
        return new Collection([
          "<{$this->tagElement}{$this->getAttributes()}>"
        ])->joinNotSpace();        
      }

    }

    return new Collection([
      "<{$this->tagElement}{$this->getAttributes()}>",
        "{$this->getChilds()}",
      "</{$this->tagElement}>"
    ])->joinNotSpace();
  }
}