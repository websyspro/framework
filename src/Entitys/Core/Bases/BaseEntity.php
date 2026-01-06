<?php

namespace Websyspro\Core\Entitys\Core\Bases;

use Websyspro\Core\Util;
use Websyspro\Core\Entitys\Core\Commons\Now;
use Websyspro\Core\Entitys\Decorations\Columns\Datetime;
use Websyspro\Core\Entitys\Decorations\Columns\Flag;
use Websyspro\Core\Entitys\Decorations\Columns\Number;
use Websyspro\Core\Entitys\Decorations\Constraints\PrimaryKey;
use Websyspro\Core\Entitys\Decorations\Events\Delete;
use Websyspro\Core\Entitys\Decorations\Events\Insert;
use Websyspro\Core\Entitys\Decorations\Events\Update;
use Websyspro\Core\Entitys\Decorations\Generations\AutoIncrement;
use Websyspro\Core\Entitys\Decorations\Requireds\NotNull;

class BaseEntity
{
  #[NotNull()]
  #[Number()]
  #[PrimaryKey()]
  #[AutoIncrement()]    
  public int $id;

  #[Flag()]
  #[NotNull()]
  #[Insert(1)]
  public bool $actived;

  #[NotNull()]
  #[Number()]
  #[Insert(1)]
  public int $activedBy;

  #[NotNull()]
  #[Datetime()]
  #[Insert(Now::class)]
  public string $activedAt;

  #[NotNull()]
  #[Number()]
  #[Insert(1)] 
  public int $createdBy;

  #[NotNull()]
  #[Datetime()]
  #[Insert(Now::class)]
  public string $createdAt;

  #[Number()]
  #[Update(1)]
  public ?int $updatedBy;

  #[Datetime()]
  #[Update(Now::class)]
  public ?string $updatedAt;

  #[Flag()]
  #[Insert(0)]
  #[Delete(1)]
  public ?bool $deleted;

  #[Number()]
  #[Delete(1)]
  public ?int $deletedBy;

  #[Datetime()]
  #[Delete(Now::class)]
  public ?string $deletedAt;

  public function exist(
  ): bool {
    return empty(get_object_vars($this)) === false;
  }

  public function mapper(
    string $toEntity
  ): mixed {
    return Util::hydrateObject(
      $this, 
      $toEntity
    );
  }
}