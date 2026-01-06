<?php

namespace Websyspro\Core\DynamicSql\Test\Entitys;

use Websyspro\Entity\Core\Bases\BaseEntity;
use Websyspro\Entity\Decorations\Columns\Number;
use Websyspro\Entity\Decorations\Columns\Text;
use Websyspro\Entity\Decorations\Constraints\ForeignKey;
use Websyspro\Entity\Decorations\Requireds\NotNull;
use Websyspro\Entity\Decorations\Statistics\Index;

class CredentialEntity
extends BaseEntity
{
  #[Number()]
  #[Index()]
  #[ForeignKey(UserEntity::class)]
  public int $UserId;

  #[Text(128)]
  #[NotNull()]
  public string $Hash;
}