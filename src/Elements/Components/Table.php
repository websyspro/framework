<?php

namespace Websyspro\Core\Elements\Components;

use Websyspro\Core\Elements\Shareds\Component;
use Websyspro\Core\Elements\Dom;
use Websyspro\Core\Elements\Tags\Table as TagsTable;
use Websyspro\Core\Elements\Tags\Tr;
use Websyspro\Core\Util;

class Table 
extends Component
{
  public static function addColumn(
    string $text,
    string $name,
    int $size = 0
  ): Column {
    return new Column(
      $text, $name, $size
    );
  }

  private static function colsToHead(
    array $cols = []
  ): array {
    return [
      Dom::tr()->add(
        Util::mapper( 
          $cols,
          fn( Column $col ) => (
            Dom::th()->css([ "width" => $col->size === 0 ? "auto" : $col->size . "px" ])->add( 
              [ $col->text ]
            )
          )
        )
      )
    ];
  }  

  private static function colsToBody(
    array $row = [],
    array $cols = []
  ): array {
    return Util::mapper( 
      $cols,
      fn( Column $col ) => (
        Dom::td()->add( 
          [ $row[ $col->name ]]
        )
      )
    );
  }

  private static function createRows(
    array $data = [],
    array $cols = []
  ): array {
    return Util::mapper(
      $data, 
      fn(array $row) => (
        Dom::tr()->add(
          Table::colsToBody(
            $row, $cols
          ) 
        )
      )
    );
  }

  public static function render(
    string $title,
    array $data = [],
    array $cols = []
  ): object {
    return new static(
      [
        Dom::h1()->css( [ "color" => "#666" ])->add([
          "Listagem de Posts"
        ]),
        Dom::table()->add( 
          [
            Dom::thead()->add(
              Table::colsToHead( $cols )
            ),
            Dom::tbody()->add(
              Table::createRows( $data, $cols )
            ),
            Dom::tfoot()->add( [
              Dom::tr()->add( [
                Dom::td()->data([
                  "colspan" => sizeof( $cols ) 
                ])->add([ "test" ])
              ])
            ])
          ]
        )
      ]
    );
  }
}