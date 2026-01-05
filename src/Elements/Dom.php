<?php

namespace Websyspro\Elements;

use Websyspro\Elements\Tags\AbstractElement;
use Websyspro\Elements\Tags\Body;
use Websyspro\Elements\Tags\Container;
use Websyspro\Elements\Tags\Div;
use Websyspro\Elements\Tags\DocType;
use Websyspro\Elements\Tags\FlexContainer;
use Websyspro\Elements\Tags\FlexItem;
use Websyspro\Elements\Tags\H1;
use Websyspro\Elements\Tags\H2;
use Websyspro\Elements\Tags\Head;
use Websyspro\Elements\Tags\Html;
use Websyspro\Elements\Tags\Meta;
use Websyspro\Elements\Tags\Paragraph;
use Websyspro\Elements\Tags\Scripts;
use Websyspro\Elements\Tags\Style;
use Websyspro\Elements\Tags\Title;
use Websyspro\Elements\Enums\FlexDirection;

class Dom
{
  public static function docType(
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new DocType( $classes, $childs );
  }

  public static function html(
    string|array|null $data = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new Html( $data, $childs);
  }

  public static function head(
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new Head( $classes, $childs );
  }

  public static function title(
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new Title( $classes, $childs );
  }
  
  public static function meta(
    string|array|null $data = []
  ): AbstractElement {
    return new Meta($data);
  }

  public static function style(
    string|array|null $strings = []
  ): AbstractElement {
    return new Style($strings);
  } 
  
  public static function script(
    string|array|null $strings = []
  ): AbstractElement {
    return new Scripts( $strings );
  }  
 
  public static function body(
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new Body( $classes, $childs );
  }

  public static function div(
    mixed ...$classes
  ): AbstractElement {
    return new Div( $classes, [] );
  }
  
  public static function container(
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new Container($classes, $childs);
  } 
  
  public static function flexContainer(
    FlexDirection $flexDirection = FlexDirection::column,
    int $flexGap = 0
  ): AbstractElement {
    return new FlexContainer( $flexDirection, $flexGap );
  }

  public static function flexItem(
    int $size = 0,
    bool $resized = true
  ): AbstractElement {
    return new FlexItem( $size, $resized );
  }
  
  public static function h1(
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new H1( $classes, $childs );
  }

  public static function h2(
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new H2( $classes, $childs );
  }
  
  public static function paragraph(
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new Paragraph( $classes, $childs );
  }
}