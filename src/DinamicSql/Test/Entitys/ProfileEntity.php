<?php

namespace Websyspro\Core\DynamicSql\Test\Entitys;

use Websyspro\Entity\Core\Bases\BaseEntity;
use Websyspro\Entity\Decorations\Columns\Text;
use Websyspro\Entity\Decorations\Constraints\Unique;
use Websyspro\Entity\Decorations\Requireds\NotNull;

class ProfileEntity
extends BaseEntity
{
  #[Text(32)]
  #[NotNull()]
  #[Unique()]
  public string $Name;
}