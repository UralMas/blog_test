<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Category;
use Smarty\Exception;

/**
 * Контроллер обработки главной страницы
 */
class IndexController extends BaseController
{
    /**
     * @throws Exception
     */
    public function __invoke(): void
    {
        $this->smarty->assign('categories', Category::getNotEmptyWithPosts(3));
        $this->smarty->display('index.tpl');
    }
}