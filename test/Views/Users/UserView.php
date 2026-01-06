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
      ["Acre", "Rio Branco", "900_000"],
      ["Alagoas", "Maceió", "3_300_000"],
      ["Amapá", "Macapá", "880_000"],
      ["Amazonas", "Manaus", "4_200_000"],
      ["Bahia", "Salvador", "14_900_000"],
      ["Ceará", "Fortaleza", "9_200_000"],
      ["Distrito Federal", "Brasília", "3_100_000"],
      ["Espírito Santo", "Vitória", "4_100_000"],
      ["Goiás", "Goiânia", "7_200_000"],
      ["Maranhão", "São Luís", "7_100_000"],
      ["Mato Grosso", "Cuiabá", "3_600_000"],
      ["Mato Grosso do Sul", "Campo Grande", "2_800_000"],
      ["Minas Gerais", "Belo Horizonte", "21_400_000"],
      ["Pará", "Belém", "8_700_000"],
      ["Paraíba", "João Pessoa", "4_000_000"],
      ["Paraná", "Curitiba", "11_600_000"],
      ["Pernambuco", "Recife", "9_600_000"],
      ["Piauí", "Teresina", "3_300_000"],
      ["Rio de Janeiro", "Rio de Janeiro", "17_300_000"],
      ["Rio Grande do Norte", "Natal", "3_500_000"],
      ["Rio Grande do Sul", "Porto Alegre", "11_300_000"],
      ["Rondônia", "Porto Velho", "1_800_000"],
      ["Roraima", "Boa Vista", "650_000"],
      ["Santa Catarina", "Florianópolis", "7_700_000"],
      ["São Paulo", "São Paulo", "46_000_000"],
      ["Sergipe", "Aracaju", "2_300_000"],
      ["Tocantins", "Palmas", "1_600_000"],
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
                Dom::td()->css( array_merge( $cssColumn, [ "text-align" => "right" ]) )->add([ $uf[2] ])
             ])
            ))
          )
        ])
      ]
    );
  }
}