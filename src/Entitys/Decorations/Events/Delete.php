<?php

namespace Websyspro\Core\Entitys\Decorations\Events;

use Attribute;
use Websyspro\Core\Entitys\Enums\AttributeType;
use Websyspro\Core\Entitys\Interfaces\IAbstractColumn;

#[Attribute( Attribute::TARGET_PROPERTY )]
class Delete
extends IAbstractColumn
{
  public AttributeType $attributeType = AttributeType::delete;
  
  public function __construct(
    public readonly mixed $value
  ){}

  public function Get(
  ): mixed {
    if(class_exists($this->value) === false){
      return $this->value;
    } else {
      return call_user_func_array(
        [$this->value, "Get"], []
      );
    }

    return null;
  }
}