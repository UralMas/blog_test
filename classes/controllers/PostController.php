<?php

declare(strict_types=1);

namespace App\Controllers;

use App\exceptions\NotFoundException;
use App\Models\Post;
use Smarty\Exception;

/**
 * Контроллер обработки страницы поста
 */
class PostController extends BaseController
{
    /**
     * @throws NotFoundException
     * @throws Exception
     */
    public function __invoke(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $post = Post::find($id);

        if (!$post) {
            throw new NotFoundException('Пост не найден');
        }

        Post::incrementViews($id);
        $post['views']++;

        $this->smarty->assign('post', $post);
        $this->smarty->assign('similar', Post::getSimilar($id, 3));
        $this->smarty->display('post.tpl');
    }
}