<?php

namespace Websyspro\Test\Routers\Account;

use Websyspro\Core\Server\Decorations\Controller\Module;
use Websyspro\Test\Routers\Account\Controllers\UserController;

#[Module(
  [
    UserController::class
  ]
)]
class AccountModule {}