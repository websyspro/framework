<?php

namespace Websyspro\Core\Entitys\Core;

use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;
use Websyspro\Core\Entitys\Interfaces\IProperties;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Collection;
use ReflectionAttribute;
use ReflectionProperty;
use ReflectionClass;

class StructureTableAbstract
{
  public function __construct(
    public string $entity
  ){}

  private function propertiesBase(
  ): Collection {
    $properts = new Collection(
      new ReflectionClass( 
        $this->entity 
      )->getAttributes()
    );

    $properts = $properts->mapper(
      fn(ReflectionAttribute $reflectionAttribute) => (
        $reflectionAttribute->newInstance()
      )
    );    

    return $properts->mapper(
      fn(ReflectionProperty $reflectionProperty) => (
        new IProperties(
          $reflectionProperty->name, 
          new Collection( $reflectionProperty->getAttributes() )
        )
      )
    );
  }

  public function properties(
    AttributeType $attributeType
  ): Collection {
    return $this->propertiesBase()
      ->mapper(
        fn(IProperties $properties) => (
          $properties->items->where(
            fn(IAbstractColumn $abstractColumn) => (
              $abstractColumn->attributeType === $attributeType
            )
          )
        )
      )->where(fn(IProperties $properties) => (
        $properties->items->count() !== 0
      ));
  }
}