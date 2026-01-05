<?php

namespace Websyspro\Core\Elements\Tags;

use Websyspro\Core\Collection;

class Body extends AbstractElement
{
  public string $tagElement = "body";

  public function __construct(
    string|array|null $classes = [],
    string|array|null $childs = []
  ){
    $this->classList = new Collection()->merge($classes);
    $this->cssList   = new Collection()->merge([
      "box-sizing" => "border-box",
      "font-family" => "roboto",
      "padding" => "0px",
      "margin" => "0px"
    ]);
    $this->childList = new Collection()->merge([
      "<script src=\"//ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js\"></script>",
      "<script>",
        "WebFont.load({",
          "google: {",
            "families: ['Roboto:100,200,300,400,500,600,700']",
          "}",
        "});",
      "</script>"
    ])->merge($childs);
  }  
}