<?php

namespace Websyspro\Core\DynamicSql\Test\Entitys;

use Websyspro\Entity\Core\Bases\BaseEntity;
use Websyspro\Entity\Decorations\Columns\Decimal;
use Websyspro\Entity\Decorations\Columns\Number;
use Websyspro\Entity\Decorations\Columns\Text;
use Websyspro\Entity\Decorations\Constraints\ForeignKey;
use Websyspro\Entity\Decorations\Statistics\Index;

class CashMovementEntity
extends BaseEntity
{
  #[Text(1)]
  public string $Type;

  #[Text(3)]
  public string $PaymentMethod;

  #[Number()]
  #[Index()]
  #[ForeignKey(DocumentEntity::class)]
  public int $DocumentId;

  #[Number()]
  #[Index()]
  #[ForeignKey(BoxEntity::class)]
  public int $BoxId;

  #[Decimal(10,2)]
  public int $Value;

  #[Text(255)]
  public int $Observations;
}