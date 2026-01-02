<?php

namespace Websyspro\Core\Server\Decorations\Controller;

use Websyspro\Core\Server\Enums\ControllerType;
use Websyspro\Core\Server\Exceptions\Error;
use Websyspro\Core\Util;

abstract class AbstractParameter
{
  public ControllerType $controllerType = ControllerType::Parameter;

  public function getValue(
    array|object|null $value,
    string $instanceType,
    string|null $key = null
  ): mixed {
    $valueType = Util::getType(
      $value
    );

    if( Util::isNull( $value )){
      Error::InternalServerError( 
        "Attributo [{$this->controllerType->name}] is null"
      );      
    }

    if( $valueType !== $instanceType ){
      Error::InternalServerError( 
        "Attributes [{$this->controllerType->name}] with incompatible types, received {} expected {$instanceType}"
      );       
    }
    
    if( Util::isArray( $value )){
      if( Util::sizeArray( $value ) === 0 && Util::isNull( $key )){
        Error::InternalServerError( 
          "Attributo [{$this->controllerType->name}]({$key})) is not exists"
        );
      }

      /*
       * Verificar instanceType is Primitivo
       * */
      if( Util::isPrimitiveType( $instanceType )){
        return Util::isNull( $key ) ? $value : $value[ $key ];
      } else {
        return Util::isNull( $key ) 
          ? Util::hydrateObject( $value, $instanceType )
          : Util::hydrateObject( $value[ $key ], $instanceType );
      }
    }

    if( Util::isObject( $value )){
      if( Util::isObjectEmpty( $value ) && Util::isNull( $key )){
        Error::internalServerError( 
          "Attributo [{$this->controllerType->name}]({$key})) is not exists"
        );
      }

      /*
       * Verificar instanceType is Primitivo
       * */
      if( Util::isPrimitiveType( $instanceType )){
        return Util::isNull( $key ) ? $value : $value[ $key ];
      } else {
        return Util::isNull( $key ) 
            ? Util::hydrateObject( $value, $instanceType )
            : Util::hydrateObject( $value[$key], $instanceType );
      }
    }

    return null;
  }
}