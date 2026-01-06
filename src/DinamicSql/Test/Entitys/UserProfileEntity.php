<?php

namespace Websyspro\Core\DynamicSql\Test\Entitys;

use Websyspro\Entity\Core\Bases\BaseEntity;
use Websyspro\Entity\Decorations\Columns\Number;
use Websyspro\Entity\Decorations\Constraints\ForeignKey;
use Websyspro\Entity\Decorations\Constraints\OneToOne;
use Websyspro\Entity\Decorations\Constraints\Unique;
use Websyspro\Entity\Decorations\Requireds\NotNull;
use Websyspro\Entity\Decorations\Statistics\Index;

class UserProfileEntity
extends BaseEntity
{
  #[Number()]
  #[Unique()]
  #[NotNull()]
  #[Index()]
  #[ForeignKey(UserEntity::class)]
  public int $UserId;

  #[Number()]
  #[Unique()]
  #[NotNull()]
  #[ForeignKey(ProfileEntity::class)]
  public int $ProfileId;
}