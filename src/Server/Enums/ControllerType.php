<?php

namespace Websyspro\Core\Server\Enums;

enum ControllerType {
  case Controller;
  case Middleware;
  case Endpoint;
  case Parameter;
}