<?php

namespace Websyspro\Core\Entitys\Core;

use ReflectionProperty;
use Websyspro\Commons\DataList;
use Websyspro\Commons\Reflect;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;
use Websyspro\Core\Entitys\Interfaces\IProperties;

class StructureTableAbstract
{
  public function __construct(
    public string $entity
  ){}

  private function propertiesBase(
  ): DataList {
    $properts = new DataList(
      Reflect::propertsFromClass(
        $this->entity
      )
    );

    return (
      $properts->mapper(
        fn(ReflectionProperty $reflectionProperty) => (
          new IProperties($reflectionProperty->name, (
            new DataList($reflectionProperty->getAttributes())
          ))
        )
      )
    );
  }

  public function properties(
    AttributeType $attributeType
  ): DataList {
    return (
      $this->propertiesBase()->forEach(
        fn(IProperties $properties) => (
          $properties->items->where(
            fn(IAbstractColumn $abstractColumn) => (
              $abstractColumn->attributeType === $attributeType
            )
          )
        )
      )->where(fn(IProperties $properties) => (
        $properties->items->count() !== 0
      ))
    );
  }
}