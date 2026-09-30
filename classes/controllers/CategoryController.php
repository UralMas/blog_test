<?php

declare(strict_types=1);

namespace App\Controllers;

use App\exceptions\NotFoundException;
use App\Models\Post;
use App\Models\Category;
use Smarty\Exception;

/**
 * Контроллер обработки страницы категории
 */
class CategoryController extends BaseController
{
    /**
     * @throws NotFoundException
     * @throws Exception
     */
    public function __invoke(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $sort = $_GET['sort'] ?? Post::SORT_DATE;
        $page = (int)($_GET['p'] ?? 1);
        $sort = in_array($sort, [Post::SORT_DATE, Post::SORT_VIEWS], true)
            ? $sort
            : Post::SORT_DATE;

        $category = Category::find($id);
        if (!$category) {
            throw new NotFoundException('Категория не найдена');
        }

        $result = Post::getPaginatedData($id, $sort, $page);

        $this->smarty->assign('category', $category);
        $this->smarty->assign('posts', $result['items']);
        $this->smarty->assign('pagination', $result);
        $this->smarty->assign('sort', $sort);
        $this->smarty->display('category.tpl');
    }
}