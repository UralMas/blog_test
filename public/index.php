<?php

declare(strict_types=1);

use App\Controllers\CategoryController;
use App\Controllers\IndexController;
use App\Controllers\PostController;
use App\exceptions\NotFoundException;
use App\Helpers\SmartyHelper;

require __DIR__ . '/../vendor/autoload.php';

$smarty = SmartyHelper::init();

try {
    try {
        switch ($_GET['page'] ?? 'index') {
            case 'index':
                new IndexController($smarty)();
                break;
            case 'category':
                new CategoryController($smarty)();
                break;
            case 'post':
                new PostController($smarty)();
                break;
            default:
                throw new NotFoundException('Ошибка 404 - страница не найдена');
        }
    } catch (NotFoundException $e) {
        http_response_code(404);
        $smarty->assign('message', $e->getMessage());
        $smarty->display('error.tpl');
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo "Ошибка: {$e->getMessage()}";
}
