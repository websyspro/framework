<?php

namespace Websyspro\Test\Views\Users;

use Websyspro\Core\Elements\Components\Table;
use Websyspro\Core\Elements\Dom;
use Websyspro\Core\Elements\Shareds\Component;

class UserView extends Component
{
  public static function render(
    array $query = []
  ): object {
    return new static(
      [
        Table::render(
          "Listagem de Posts",
          [
            [ "id" => "01", "url" => "Tanto didáticos quanto paradidáticos, permeiam as salas de aula desde o início da Educação Básica", "description" => "Por ser tão onipresente no cotidiano pedagógico, o livro impresso, suporte textual por excelência do ambiente escolar, pode acabar tendo sua importância menosprezada ou, dizendo de outra forma", "date" => "15/10/2025" ],
            [ "id" => "02", "url" => "Sed do eiusmod tempor", "description" => "Sed do eiusmod tempor incididunt ut labore et dolore magna", "date" => "15/10/2025" ],
            [ "id" => "03", "url" => "Ut enim ad minim", "description" => "Ut enim ad minim veniam, quis nostrud exercitation", "date" => "15/10/2025" ],
            [ "id" => "04", "url" => "Duis aute irure dolor", "description" => "Duis aute irure dolor in reprehenderit in voluptate", "date" => "15/10/2025" ],
            [ "id" => "05", "url" => "Excepteur sint occaecat", "description" => "Excepteur sint occaecat cupidatat non proident", "date" => "15/10/2025" ],
            [ "id" => "06", "url" => "Sunt in culpa qui", "description" => "Sunt in culpa qui officia deserunt mollit anim", "date" => "15/10/2025" ],
            [ "id" => "07", "url" => "At vero eos et", "description" => "At vero eos et accusamus et iusto odio dignissimos", "date" => "15/10/2025" ],
            [ "id" => "08", "url" => "Et harum quidem rerum", "description" => "Et harum quidem rerum facilis est et expedita", "date" => "15/10/2025" ],
            [ "id" => "09", "url" => "Nam libero tempore", "description" => "Nam libero tempore, cum soluta nobis est eligendi", "date" => "15/10/2025" ],
            [ "id" => "10", "url" => "Temporibus autem quibusdam", "description" => "Temporibus autem quibusdam et aut officiis debitis", "date" => "15/10/2025" ],
            [ "id" => "11", "url" => "Sunt in culpa qui", "description" => "Sunt in culpa qui officia deserunt mollit anim", "date" => "15/10/2025" ],
            [ "id" => "12", "url" => "At vero eos et", "description" => "At vero eos et accusamus et iusto odio dignissimos", "date" => "15/10/2025" ],
            [ "id" => "13", "url" => "Et harum quidem rerum", "description" => "Et harum quidem rerum facilis est et expedita", "date" => "15/10/2025" ],
            [ "id" => "14", "url" => "Nam libero tempore", "description" => "Nam libero tempore, cum soluta nobis est eligendi", "date" => "15/10/2025" ],
            [ "id" => "15", "url" => "Temporibus autem quibusdam", "description" => "Temporibus autem quibusdam et aut officiis debitis", "date" => "15/10/2025" ]
          ],
          [
            Table::addColumn( "#", "id", 80 ),
            Table::addColumn( "Descrição da URL", "url",240 ),
            Table::addColumn( "Description do Site", "description", 0 ),
            Table::addColumn( "Data", "date", 140 )
          ]
        )
      ]
    );
  }
}