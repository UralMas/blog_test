<?php

declare(strict_types=1);

namespace App\Controllers;

use Smarty\Smarty;

/**
 * Хранит базовую логику котроллеров
 */
class BaseController
{
    public function __construct(
        protected Smarty $smarty
    ) {
    }
}
