<?php

namespace Websyspro\Test\Views\Users;

use Websyspro\Core\Elements\Shareds\Component;
use Websyspro\Core\Elements\Dom;
use Websyspro\Core\Util;

class UserView extends Component
{
  public static function render(
    array $query = []
  ): object {
    $cssHeader = [
      "border-bottom" => "1px solid #bbb",
      "text-align" => "left",
      "padding" => "14px"
    ];

    $cssColumn = [
      "border-bottom" => "1px solid #ddd",
      "text-align" => "left",
      "padding" => "12px 14px"
    ];

    $ufs = [
      ["Acre", "Rio Branco", "900000"],
      ["Alagoas", "Maceió", "3300000"],
      ["Amapá", "Macapá", "880000"],
      ["Amazonas", "Manaus", "4200000"],
      ["Bahia", "Salvador", "14900000"],
      ["Ceará", "Fortaleza", "9200000"],
      ["Distrito Federal", "Brasília", "3100000"],
      ["Espírito Santo", "Vitória", "4100000"],
      ["Goiás", "Goiânia", "7200000"],
      ["Maranhão", "São Luís", "7100000"],
      ["Mato Grosso", "Cuiabá", "3600000"],
      ["Mato Grosso do Sul", "Campo Grande", "2800000"],
      ["Minas Gerais", "Belo Horizonte", "21400000"],
      ["Pará", "Belém", "8700000"],
      ["Paraíba", "João Pessoa", "4000000"],
      ["Paraná", "Curitiba", "11600000"],
      ["Pernambuco", "Recife", "9600000"],
      ["Piauí", "Teresina", "3300000"],
      ["Rio de Janeiro", "Rio de Janeiro", "17300000"],
      ["Rio Grande do Norte", "Natal", "3500000"],
      ["Rio Grande do Sul", "Porto Alegre", "11300000"],
      ["Rondônia", "Porto Velho", "1800000"],
      ["Roraima", "Boa Vista", "650000"],
      ["Santa Catarina", "Florianópolis", "7700000"],
      ["São Paulo", "São Paulo", "46000000"],
      ["Sergipe", "Aracaju", "2300000"],
      ["Tocantins", "Palmas", "1600000"],
    ];
    
    return new static( 
      [
        Dom::table()->css([
          "background-color" => "rgb(255,255,255)",
          "table-layout" => "fixed",
          "width" => "100%"
        ])->add( [
          Dom::thead()->add( [
            Dom::trow()->add( [
              Dom::th()->css( $cssHeader )->add( [ "TRow 1" ]),
              Dom::th()->css( $cssHeader )->add( [ "TRow 2" ]),
              Dom::th()->css( array_merge($cssHeader, [ "width" => "120px" ]) )->add( [ "TRow 3" ])
            ])
          ]),
          Dom::tbody()->add(
            Util::mapper($ufs, fn( array $uf ): mixed => (
              Dom::trow()->add( [
                Dom::td()->css( $cssColumn )->add([ $uf[0] ]),
                Dom::td()->css( $cssColumn )->add([ $uf[1] ]),
                Dom::td()->css( array_merge( $cssColumn, [ "text-align" => "right" ]) )->add([
                  number_format($uf[2], 2, ',', '.')
                ])
             ])
            ))
          )
        ])
      ]
    );
  }
}