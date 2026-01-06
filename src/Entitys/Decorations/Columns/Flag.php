<?php

namespace Websyspro\Core\Entitys\Decorations\Columns;

use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Enums\ColumnType;
use Attribute;

/**
 * Atributo para definir colunas booleanas (SMALLINT).
 * 
 * Define uma coluna para valores booleanos (true/false) representados
 * como SMALLINT no banco de dados (0 = false, 1 = true).
 * 
 * @package Websyspro\Core\Entitys\Decorations\Columns
 * @author Framework Websyspro
 * @version 1.0
 * 
 * @example
 * #[Flag()]
 * public bool $ativo;
 */
#[Attribute( Attribute::TARGET_PROPERTY )]
class Flag 
extends IAbstractColumn
{
  /** @var AttributeType Tipo do atributo (coluna) */
  public AttributeType $attributeType = AttributeType::column;
  
  /** @var ColumnType Tipo da coluna (booleano) */
  public ColumnType $columnType = ColumnType::flag;

  /**
   * Gera a definição SQL para a coluna booleana.
   * 
   * @return string Definição SQL da coluna ("smallint")
   */
  public function sql(
  ): string {
    return "smallint";
  }
}