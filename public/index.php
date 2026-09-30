<?php

declare(strict_types=1);

use App\helpers\SmartyHelper;

require __DIR__ . '/../vendor/autoload.php';

$smarty = SmartyHelper::init();

try {
    $smarty->display('layout.tpl');
} catch (Throwable $e) {
    http_response_code(500);
    echo "Ошибка: {$e->getMessage()}";
}
