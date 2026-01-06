<?php

namespace Websyspro\Core\DynamicSql\Enums;

enum EqualType
{
  case Equal;
  case StartGroup;
  case EndGroup;
  case Logical;
}