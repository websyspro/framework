<?php

namespace Websyspro\Test\Api\Markting;

use Websyspro\Core\Server\Decorations\Controller\Module;
use Websyspro\Test\Api\Markting\Controllers\ProductController;

#[Module(
  [
    ProductController::class
  ]
)]
class MarkingModule {}