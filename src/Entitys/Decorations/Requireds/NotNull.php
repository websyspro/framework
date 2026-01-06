<?php

namespace Websyspro\Core\Entitys\Decorations\Requireds;

use Attribute;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;

/**
 * Atributo para definir campos obrigatórios (NOT NULL).
 * 
 * Marca uma propriedade como obrigatória, impedindo valores nulos
 * no banco de dados. Essencial para campos críticos da entidade.
 * 
 * @package Websyspro\Core\Entitys\Decorations\Requireds
 * @author Framework Websyspro
 * @version 1.0
 * 
 * @example
 * #[NotNull()]
 * #[Text(100)]
 * public string $nome;
 */
#[Attribute( Attribute::TARGET_PROPERTY )]
class NotNull
extends IAbstractColumn
{
  /** @var AttributeType Tipo do atributo (campo obrigatório) */
  public AttributeType $attributeType = AttributeType::requireds;
}