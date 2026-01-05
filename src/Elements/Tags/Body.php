<?php

namespace Websyspro\Elements\Collectons;

use Websyspro\Commons\DataList;

class Body
extends AbstractElement
{
  public string $tagElement = "body";

  public function __construct(
    string|array|null $classes = [],
    string|array|null $childs = []
  ){
    $this->classList = DataList::create([])->merge($classes);
    $this->cssList   = DataList::create([])->merge([
      "box-sizing" => "border-box",
      "font-family" => "roboto",
      "padding" => "0px",
      "margin" => "0px"
    ]);
    $this->childList = DataList::create([])->merge([
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