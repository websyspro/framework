<?php

namespace Websyspro\Core\Elements;

use Websyspro\Core\Elements\Tags\AbstractElement;
use Websyspro\Core\Elements\Tags\Body;
use Websyspro\Core\Elements\Tags\Container;
use Websyspro\Core\Elements\Tags\Div;
use Websyspro\Core\Elements\Tags\DocType;
use Websyspro\Core\Elements\Tags\FlexContainer;
use Websyspro\Core\Elements\Tags\FlexItem;
use Websyspro\Core\Elements\Tags\H1;
use Websyspro\Core\Elements\Tags\H2;
use Websyspro\Core\Elements\Tags\Head;
use Websyspro\Core\Elements\Tags\Html;
use Websyspro\Core\Elements\Tags\Meta;
use Websyspro\Core\Elements\Tags\Paragraph;
use Websyspro\Core\Elements\Tags\Scripts;
use Websyspro\Core\Elements\Tags\Style;
use Websyspro\Core\Elements\Tags\Table;
use Websyspro\Core\Elements\Tags\TBody;
use Websyspro\Core\Elements\Tags\Td;
use Websyspro\Core\Elements\Tags\TFoot;
use Websyspro\Core\Elements\Tags\Th;
use Websyspro\Core\Elements\Tags\THead;
use Websyspro\Core\Elements\Tags\Title;
use Websyspro\Core\Elements\Enums\FlexDirection;
use Websyspro\Core\Elements\Tags\Tr;
use Websyspro\Core\Util;

class Dom
{
  public static function docType(
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new DocType( Util::merge( $classes, [ "html" ]), $childs );
  }

  public static function html(
    string|array|null $data = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new Html( Util::merge( $data, [ "lang" => "pt" ]), $childs);
  }

  public static function head(
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new Head( $classes, $childs );
  }

  public static function title(
    string $title
  ): AbstractElement {
    return new Title()->add([ $title ]);
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
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new Div( $classes, $childs );
  }
  
  public static function container(
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new Container( $classes, $childs );
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

  public static function table(
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new Table( $classes, $childs );
  }

  public static function thead(
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new THead( $classes, $childs );
  }
  
  public static function tbody(
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new TBody( $classes, $childs );
  }
  
  public static function tfoot(
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new TFoot( $classes, $childs );
  }

  public static function tr(
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new Tr( $classes, $childs );
  }

  public static function th(
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new Th( $classes, $childs );
  }  
  
  public static function td(
    string|array|null $classes = [],
    string|array|null $childs = []
  ): AbstractElement {
    return new Td( $classes, $childs );
  }  
}