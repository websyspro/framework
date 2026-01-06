<?php

namespace Websyspro\Core\Entitys\Decorations\Constraints;

use Attribute;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;

/**
 * Atributo para definir chaves primárias.
 * 
 * Marca uma propriedade como chave primária da tabela.
 * Garante unicidade e identificação única de cada registro.
 * 
 * @package Websyspro\Core\Entitys\Decorations\Constraints
 * @author Framework Websyspro
 * @version 1.0
 * 
 * @example
 * #[PrimaryKey()]
 * #[AutoIncrement()]
 * public int $id;
 */
#[Attribute( Attribute::TARGET_PROPERTY )]
class PrimaryKey
extends IAbstractColumn
{
  /** @var AttributeType Tipo do atributo (chave primária) */
  public AttributeType $attributeType = AttributeType::primaryKey;
}