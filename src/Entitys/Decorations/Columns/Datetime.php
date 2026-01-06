<?php

namespace Websyspro\Core\Entitys\Decorations\Columns;

use Attribute;
use Websyspro\Core\Entitys\Enums\ColumnType;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;

/**
 * Atributo para definir colunas de data e hora (DATETIME).
 * 
 * Define uma coluna para armazenar data e hora no formato DATETIME.
 * Adequado para timestamps, datas de criação, atualização, etc.
 * 
 * @package Websyspro\Core\Entitys\Decorations\Columns
 * @author Framework Websyspro
 * @version 1.0
 * 
 * @example
 * #[Datetime()]
 * public string $criadoEm;
 */
#[Attribute( Attribute::TARGET_PROPERTY )]
class Datetime 
extends IAbstractColumn
{
  /** @var AttributeType Tipo do atributo (coluna) */
  public AttributeType $attributeType = AttributeType::column;
  
  /** @var ColumnType Tipo da coluna (data e hora) */
  public ColumnType $columnType = ColumnType::datetime;

  /**
   * Gera a definição SQL para a coluna de data e hora.
   * 
   * @return string Definição SQL da coluna ("datetime")
   */
  public function sql(
  ): string {
    return "datetime";
  }
}