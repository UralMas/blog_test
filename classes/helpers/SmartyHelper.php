<?php

declare(strict_types=1);

namespace App\Helpers;

use Smarty\Smarty;

class SmartyHelper
{
    /**
     * Инициализирует объект Smarty с базовыми настройками
     */
    public static function init(): Smarty
    {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/../../templates');
        $smarty->setCompileDir(__DIR__ . '/../../templates_c');
        $smarty->setCaching(Smarty::CACHING_OFF);

        return $smarty;
    }
}
