<?php

namespace Websyspro\Core\DynamicSql\Test\Entitys;

use Websyspro\Commons\DataList;
use Websyspro\Entity\Core\Bases\BaseEntity;
use Websyspro\Entity\Decorations\Columns\Text;
use Websyspro\Entity\Decorations\Constraints\OneToMany;
use Websyspro\Entity\Decorations\Constraints\Unique;
use Websyspro\Entity\Decorations\Requireds\NotNull;

class UserEntity
extends BaseEntity
{
  #[Text(255)]
  public string $Name;

  #[Text(320)]
  #[NotNull()]
  #[Unique()]
  public string $Email;

  #[OneToMany(CredentialEntity::class)]
  public DataList $Credentials;
}