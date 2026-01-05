<?php

namespace Websyspro\Core;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Collection
{
  public function __construct(
    public array $items = []
  ){}

  public function add(
    mixed $item
  ): Collection {
    $this->items[] = $item;
    return $this;
  }

  public function merge(
    Collection|array $array 
  ): Collection {
    $this->items = $array instanceof Collection
      ? array_merge( $this->items, $array->all()) 
      : array_merge( $this->items, $array );

    return $this;
  }

  public function mapper(
    callable|object $fn
  ): Collection {
    if(is_callable( $fn ) === false){
      // TODO:: mapper hidratate
      return new Collection();
    }

    return new Collection(
      Util::mapper(
        $this->items, $fn
      )
    );
  }

  public function where(
    callable $fn
  ): Collection {
    return new Collection(
      Util::where( 
        $this->items, $fn
      )
    );
  }

  public function find(
    callable $fn
  ): mixed {
    return Util::find(
      $this->items, $fn
    );
  }

  public function reduce(
    mixed $curremt,
    callable $fn
  ): mixed {
    return Util::reduce(
      $this->items, $fn, $curremt
    );
  }

  public function slice(
    int $start,
    int|null $lenght = null
  ): Collection {;
    return new Collection(array_slice($this->items, $start, $lenght));
  }
  
  public function chunk(
    int $length
  ): Collection {
    $this->items = Util::chunk($this->items, $length);
    return $this;
  }  

  public function join(
    string $join = ""
  ): string {
    return implode($join, $this->items);
  }

  public function joinWithComma(
  ): string {
    return $this->Join(", ");
  }

  public function joinWithSpace(
  ): string {
    return $this->Join(" ");
  }

  public function joinNotSpace(
  ): string {
    return $this->Join("");
  }

  public function joinWithBreak(
  ): string {
    return $this->Join( "\r\n" );
  }  

  public function count(
  ): int {
    return sizeof($this->items);
  }

  public function exist(
  ): bool {
    return sizeof($this->items) !== 0;
  }
  
  public function sum(
    callable $callable
  ): float {
    return array_sum(
      Util::mapper( 
        $this->items, $callable
      )
    );
  }

  public function eq(
    int $eq
  ): Collection {
    return new Collection(
      array_slice($this->items, $eq, 1)
    );
  }

  public function first(    
  ): mixed {
    return reset($this->items);
  }

  public function last(    
  ): mixed {
    return end($this->items);
  } 
  
  public function orderByAsc(
  ): Collection {
    ksort($this->items);
    return new Collection($this->items);
  }

  public function all(
  ): array {
    return $this->items;
  }
}