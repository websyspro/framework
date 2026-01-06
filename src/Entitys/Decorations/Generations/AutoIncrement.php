<?php

namespace Websyspro\Core\Entitys\Decorations\Generations;

use Attribute;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;

/**
 * Atributo para definir incremento automático (AUTO_INCREMENT).
 * 
 * Marca uma propriedade para ter seu valor gerado automaticamente
 * pelo banco de dados. Geralmente usado em chaves primárias.
 * 
 * @package Websyspro\Core\Entitys\Decorations\Generations
 * @author Framework Websyspro
 * @version 1.0
 * 
 * @example
 * #[PrimaryKey()]
 * #[AutoIncrement()]
 * #[Number()]
 * public int $id;
 */
#[Attribute( Attribute::TARGET_PROPERTY )]
class AutoIncrement
extends IAbstractColumn
{
  /** @var AttributeType Tipo do atributo (geração automática) */
  public AttributeType $attributeType = AttributeType::generations;
}