<?php

namespace Websyspro\Core\DynamicSql\Core;

use Websyspro\Core\DynamicSql\Shareds\ItemParameter;
use Websyspro\Core\DynamicSql\Shareds\Token;
use Websyspro\Core\Collection;
use Websyspro\Core\Util;
use ReflectionParameter;
use ReflectionFunction;

class AbstractByFn
{
  private bool $startBody = false;
  private int $brackets = 0;
  private int $parentheses = 0;

  public Collection $tokens;

  public function __construct(
    private mixed $fn
  ){
    $this->defineTokens();
    $this->defineClears();
    $this->defines();
  }

  public function getParameters(
  ): Collection {
    return new Collection(
      new ReflectionFunction($this->fn)->getParameters()
    )->mapper(
      fn(ReflectionParameter $rp) => (
        new ItemParameter(
          $rp->getType()->getName(), 
          $rp->getName()
        )
      )
    );    
  }

  public function getStatics(
  ): Collection {
    return new Collection(
      Util::ParseBodyStaticsUnion(
        new ReflectionFunction(
          $this->fn
        )->getStaticVariables()
      )
    );
  }  
  
  private function defineTokens(
  ): void {
    $this->tokens = (
      $this->load($this->fn)
    );
  }

  private function defineClears(
  ): void {
    unset($this->parentheses);
    unset($this->startBody);
    unset($this->brackets);
  }

  private function getTokenAll(
    string $bodyStr
  ): Collection {
    return new Collection(
      token_get_all( "<?php {$bodyStr}" )
    )->mapper(fn(array|string $token) => new Token($token))->slice(1);
  }  

  private function defineStartAndEndBody(
    Collection $reflectFnBodyTokens
  ): Collection {
    $reflectFnBodyTokens->where(
      function(Token $token){
        if( $token->type === T_DOUBLE_ARROW ){
          $this->startBody = true;
        }

        return $this->startBody;
      }
    );

    $reflectFnBodyTokens->where(
      function(Token $token){
        if( $token->getString() === "(" ) $this->parentheses++;
        if( $token->getString() === "[" ) $this->brackets++; 

        if( $token->getString() === ")" ){
          if( $this->parentheses === 0 ){
            $this->startBody = false;
          } else $this->parentheses--;
        }

        if( $token->getString() === "]" ){
          if( $this->brackets === 0 ){
            $this->startBody = false;
          } else $this->brackets--;
        }

        if( $token->getString() === ";" ){
          if( $this->parentheses === 0 && $this->brackets === 0 ){
            $this->startBody = false;
          }
        }
        
        return $this->startBody;
      }
    );

    return $reflectFnBodyTokens->slice(1);
  }

  private function dropSpacesExtras(
    Collection $reflectFnBodyTokens
  ): Collection {
    return (
      $this->getTokenAll(
        preg_replace([
          "/(^\s*)|(\s*$)/",
          "/(^\()|(\)$)|(^\[)|(\]$)/",
          "/(^\s*)|(\s*$)/" 
        ], "", 
          $reflectFnBodyTokens->mapper(
            fn(Token $token) => $token->getString()
          )->joinNotSpace()
        )
      )
    );
  }

  private function load(
    callable $fn
  ): Collection {
    $reflectFn = new ReflectionFunction($fn);
    
    if( $reflectFn ){
      $reflectFNLines =  new Collection(
        file( $reflectFn->getFileName())
      );

      $reflectFnBody = (
        $reflectFNLines->slice(
          $reflectFn->getStartLine() - 1, (
            $reflectFn->getEndLine() - 
            $reflectFn->getStartLine() + 1
          )
        )
      );

      $reflectFnBodyTokens = $this->getTokenAll(
        $reflectFnBody->joinWithSpace()
      );

      $reflectFnBodyTokens = (
        $this->dropSpacesExtras(
          $this->defineStartAndEndBody(
            $reflectFnBodyTokens
          )
        )
      );
    }
    
    return $reflectFnBodyTokens;
  }

  public function defines(
  ): void {}

  public static function create(
    callable $fn
  ): static {
    return new static($fn);
  }
}