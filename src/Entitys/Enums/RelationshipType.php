<?php

namespace Websyspro\Core\Entitys\Enums;

enum RelationshipType: int
{
  case oneToOne = 1;
  case oneToMany = 2;
}