<?php

namespace Websyspro\Test\Account;

use Websyspro\Core\Server\Decorations\Controller\Module;
use Websyspro\Test\Account\Controllers\UserController;

#[Module(
  [
    UserController::class
  ]
)]
class AccountModule {}