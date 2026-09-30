<?php

declare(strict_types=1);

use App\helpers\SmartyHelper;
use App\Models\Category;

require __DIR__ . '/../vendor/autoload.php';

$smarty = SmartyHelper::init();

try {
    switch ($_GET['page'] ?? 'index') {
        case 'index':
            $smarty->assign('categories', Category::getNotEmptyWithPosts(3));
            $smarty->display('index.tpl');
            break;
        default:
            http_response_code(404);
            echo "Ошибка 404 - страница не найдена";
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo "Ошибка: {$e->getMessage()}";
}
