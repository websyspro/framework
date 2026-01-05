<?php

namespace Websyspro\Test\Api\Account;

use Websyspro\Core\Server\Decorations\Controller\Module;
use Websyspro\Test\Api\Account\Controllers\UserController;

#[Module(
  [
    UserController::class
  ]
)]
class AccountModule {}