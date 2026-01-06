<?php

namespace Websyspro\Core\DynamicSql\Test\Entitys;

use Websyspro\Entity\Core\Bases\BaseEntity;
use Websyspro\Entity\Decorations\Columns\Text;
use Websyspro\Entity\Decorations\Constraints\Unique;

class OperatorEntity
extends BaseEntity
{
  #[Text(64)]
  #[Unique(1)]
  public string $Name;
}