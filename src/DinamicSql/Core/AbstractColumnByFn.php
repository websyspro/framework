<?php

namespace Websyspro\Core\DynamicSql\Core;

use Websyspro\Core\DynamicSql\Shareds\Column;
use Websyspro\Core\DynamicSql\Shareds\ItemParameter;
use Websyspro\Core\DynamicSql\Shareds\Token;
use Websyspro\Core\Entitys\Interfaces\IColumnType;
use Websyspro\Core\Collection;

class AbstractColumnByFn
extends AbstractByFn
{
  public function defines(
  ): void {
    $this->defineColumnsToString();
    $this->defineColumnsNormalizeds();
    $this->defineColumnsSplits();
    $this->defineColumnsEntitys();
    $this->defineColumnsCreate();
    $this->defineColumns();
  }
  
  private function defineColumnsToString(
  ): void {
    $this->tokens->mapper(fn(Token $token) => $token->getString())->where(
      fn(string $token) => empty(trim($token)) === false
    );
  }

  private function defineColumnsNormalizeds(
  ): void {
    $this->tokens->mapper(fn(string $token) => preg_replace( "/(\r?\n)/", "", $token ));
    $this->tokens->mapper(fn(string $token) => preg_replace( "/\s{2,}/", " ", $token ));
    $this->tokens->mapper(fn(string $token) => preg_replace( "/(^\s*)|(\s*$)/", "", $token ));
    $this->tokens->mapper(fn(string $token) => preg_replace( "/(\")|(\')/", "", $token ));
  }

  private function defineColumnsSplits(
  ): void {
    $this->tokens = new Collection(
      explode(",", $this->tokens->joinNotSpace())
    );
  }

  private function defineColumnsEntitys(
  ): void {
    $this->getParameters()->mapper(
      fn(ItemParameter $itemParameter) => (
        $itemParameter->structureTable->columns()->listType()->mapper(
          fn(IColumnType $columnType) => (
            $this->tokens->mapper(
              fn(string $token) => preg_replace(
                "/\\$$itemParameter->name->$columnType->name/", 
                "{$itemParameter->structureTable->table}.{$columnType->name}", $token 
              ) 
            )
          )
        )
      )
    );
  }

  private function defineColumnsCreate(
  ): void {
    $this->tokens->mapper(
      fn(string $token) => new Column($token) 
    );
  }  

  public function defineColumns(
  ): void {}
}