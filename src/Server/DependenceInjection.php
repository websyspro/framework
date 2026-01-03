<?php

namespace Websyspro\Core\Server;

use Websyspro\Core\Util;
use ReflectionParameter;
use ReflectionClass;

class DependenceInjection
{
  public static function getInstance(
    string|object $class
  ): object {
    $hasConstruct = method_exists(
      $class, "__construct"
    );

    if($hasConstruct === true){
      return DependenceInjection::gets($class);
    } else return new $class;
  }

  /**
   * Retrieves the name of a reflected method parameter.
   *
   * This method returns the parameter identifier as declared
   * in the method signature.
   *
   * @param ReflectionParameter $reflectionParameter
   *        The reflected parameter being inspected.
   *
   * @return string The parameter name.
   */  
  private function nameFromParameter(
    ReflectionParameter $reflectionParameter
  ): string {
    return $reflectionParameter->getName();
  }  

  public static function gets(
    string $objectClass
  ): object {
    $reflectionClass = (
      new ReflectionClass(
        objectOrClass: $objectClass
      )
    );

    if( $reflectionClass ){
      if($reflectionClass->getConstructor()){
        $getParameters = (
          $reflectionClass
            ->getConstructor()
            ->getParameters()
        );
      }

      if( $getParameters ){
        $getParametersList = Util::mapper(
          array: $getParameters, fn: (
            function( ReflectionParameter $reflectionParameter ): mixed {
              if( $reflectionParameter->isDefaultValueAvailable() === false ){
                return DependenceInjection::gets(
                   objectClass: DependenceInjection::nameFromParameter(
                    reflectionParameter: $reflectionParameter
                   )
                );
              }

              return $reflectionParameter->getDefaultValue();
            }
          )
        );

        return call_user_func_array( [
          new ReflectionClass(
            $objectClass
          ), "newInstance"
        ], $getParametersList );
      }

      return new $objectClass();
    }

    return new $objectClass();
  }
}