<?php

namespace Websyspro\Core\DynamicSql\Core;

use Websyspro\Core\DynamicSql\Interfaces\ICompare;
use Websyspro\Core\DynamicSql\Shareds\Equal;
use Websyspro\Core\DynamicSql\Shareds\ItemParameter;
use Websyspro\Core\DynamicSql\Shareds\Token;
use Websyspro\Core\Collection;

class WhereByFn
extends AbstractByFn
{
  private array $equals = [
    "!==", "!=",
    "===", "==", "=",
    ">=", "<=", ">", "<", "<>"
  ];

  private array $conditions = [
    "&&", "and",
    "||", "or"
  ];  

  private int $parentheses = 0;
  private bool $isEquals = false;

  public function defines(
  ): void {
    $this->defineConditionsBlocks();
    $this->defineConditionsSplits();
    $this->defineConditionsNormalizeds();
    $this->defineConditionsNegations();
    $this->defineConditionsToEquals();
  }

  private function defineConditionsBlocks(
  ): void {
    $this->tokens->mapper(
      function(Token $token){
        if( in_array($token->getString(), $this->equals )){
          $this->isEquals = true;
        }

        if( in_array($token->getString(), $this->conditions )){
          $this->isEquals = false;
        }

        if( $this->isEquals === true ){
          if( $this->parentheses === 0 ){
            if( $token->getString() === ")" ){
                $this->isEquals = false;
            } 
          }
        }
        
        if( $this->isEquals === true ){
          if( $token->getString() === "(" ) $this->parentheses++;
          if( $token->getString() === ")" ) $this->parentheses--;
        }

        if( $this->isEquals === false ) {
          if( $token->getString() === "(" ){
            $token->string = "__(";
          }

          if( $token->getString() === ")" ){
            $token->string = ")__";
          }
        }
      }
    );
  }

  private function defineConditionsSplits(
  ): void {
    $this->tokens = new Collection(
      preg_split( "/(\s{1,}&&\s{1,})|(\s{1,}and\s{1,})|(\s{1,}\|\|\s{1,})|(\s{1,}or\s{1,})|(__\()|(\)__)/i", (
        $this->tokens->Mapper(fn(Token $token) => $token->getString())->joinNotSpace()
      ), -1, ( PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY ))
    )->where(fn(string $token) => empty( trim( $token )) === false);
  }

  private function defineConditionsNormalizeds(    
  ): void {
    $this->tokens->mapper(fn(string $token) => preg_replace( "/(\r?\n)/", "", $token ));
    $this->tokens->mapper(fn(string $token) => preg_replace( "/\s{2,}/", " ", $token ));
    $this->tokens->mapper(fn(string $token) => preg_replace( "/(^\s*)|(\s*$)/", "", $token ));
    $this->tokens->mapper(fn(string $token) => preg_replace( "/^__\($/", "(", $token ));
    $this->tokens->mapper(fn(string $token) => preg_replace( "/^\)__$/", ")", $token ));
    $this->tokens->mapper(fn(string $token) => preg_replace( "/(^&&$)|(^and$)/i", "And", $token ));
    $this->tokens->mapper(fn(string $token) => preg_replace( "/(^\|\|$)|(^or$)/i", "Or", $token ));
    $this->tokens->mapper(fn(string $token) => preg_replace( "/!==/i", "!=", $token ));
    $this->tokens->mapper(fn(string $token) => preg_replace( "/(===|==)/i", "==", $token ));
  }

  private function defineConditionsNegations(
  ): void {
    $this->tokens->mapper(
      function(string $token){
        if(preg_match("/^!\\$\.*/", $token)){
          return sprintf( "%s == false", (
            preg_replace("/^!/", "", $token)
          ));
        }

        return $token;
      }
    );
  }

  private function defineConditionsToEquals(
  ): void {
    $this->tokens->mapper(
      fn(string $token) => (
        new Equal(
          $token, 
          $this->getParameters(),
          $this->getStatics(),
          true
        )
      )
    );
  }

  public function getCompare(
  ): ICompare {
    $forms = $this->getParameters()->mapper(
      fn( ItemParameter $itemParameter ) => $itemParameter->structureTable->table
    );

    $leftJoins = $this->tokens
      ->where(fn(Equal $token) => $token->isLeftJoin || $token->isPrimary)
      ->mapper(fn(Equal $token) => $token->leftJoin);

    $conditionsPrimary = new Collection([
      $this->tokens->mapper(
        fn(Equal $token) => $token->getCompare(
          $forms->first()
        )
      )->joinWithSpace()
    ]);

    $conditionsSecundary = new Collection([
      $this->tokens->mapper(
        fn(Equal $token) => $token->getCompare(
          $forms->first(), true
        )
      )->joinWithSpace()
    ]);    

    return new ICompare(
      $forms,
      $leftJoins,
      $conditionsPrimary,
      $conditionsSecundary
    );
  }
}