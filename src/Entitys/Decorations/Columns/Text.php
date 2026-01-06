<?php

namespace Websyspro\Core\Entitys\Decorations\Columns;

use Attribute;
use Websyspro\Core\Entitys\Enums\ColumnType;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;

/**
 * Atributo para definir colunas de texto (VARCHAR).
 * 
 * Define uma coluna de texto com tamanho variável no banco de dados.
 * Por padrão, o tamanho máximo é 255 caracteres.
 * 
 * @package Websyspro\Core\Entitys\Decorations\Columns
 * @author Framework Websyspro
 * @version 1.0
 * 
 * @example
 * #[Text(100)]
 * public string $nome;
 */
#[Attribute( Attribute::TARGET_PROPERTY )]
class Text
extends IAbstractColumn
{
  /** @var AttributeType Tipo do atributo (coluna) */
  public AttributeType $attributeType = AttributeType::column;
  
  /** @var ColumnType Tipo da coluna (texto) */
  public ColumnType $columnType = ColumnType::text;

  /**
   * Construtor do atributo Text.
   * 
   * @param int $size Tamanho máximo da coluna de texto (padrão: 255)
   */
  public function __construct(
    public readonly int $size = 255
  ){}

  /**
   * Gera a definição SQL para a coluna.
   * 
   * Retorna a sintaxe SQL apropriada para criar uma coluna VARCHAR
   * com o tamanho especificado.
   * 
   * @return string Definição SQL da coluna (ex: "varchar(255)")
   */
  public function sql(
  ): string {
    return sprintf("varchar(%s)", ...[
      $this->size
    ]);
  } 
}