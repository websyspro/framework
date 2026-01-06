<?php

namespace Websyspro\Core\Entitys\Decorations\Columns;

use Attribute;
use Websyspro\Core\Entitys\Enums\ColumnType;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;

/**
 * Atributo para definir colunas numéricas (BIGINT).
 * 
 * Define uma coluna de números inteiros de grande capacidade no banco de dados.
 * Adequado para IDs, contadores e valores numéricos em geral.
 * 
 * @package Websyspro\Core\Entitys\Decorations\Columns
 * @author Framework Websyspro
 * @version 1.0
 * 
 * @example
 * #[Number()]
 * public int $quantidade;
 */
#[Attribute( Attribute::TARGET_PROPERTY )]
class Number
extends IAbstractColumn
{
  /** @var AttributeType Tipo do atributo (coluna) */
  public AttributeType $attributeType = AttributeType::column;
  
  /** @var ColumnType Tipo da coluna (numérico) */
  public ColumnType $columnType = ColumnType::number;

  /**
   * Gera a definição SQL para a coluna numérica.
   * 
   * @return string Definição SQL da coluna ("bigint")
   */
  public function sql(
  ): string {
    return "bigint";
  } 
}