<?php

declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDOStatement;

final class Post
{
    public const string SORT_VIEWS = 'views';
    public const string SORT_DATE  = 'date';
    private const int PER_PAGE     = 5;

    /**
     * Получает данные постов в категории + данные пагинации
     */
    public static function getPaginatedData(int $categoryId, string $sort, int $page): array
    {
        $pdo = Database::getConnection();

        $orderBy = match ($sort) {
            self::SORT_VIEWS => 'a.`views` DESC, a.`published_at` DESC',
            default          => 'a.`published_at` DESC',
        };

        $countStmt = $pdo->prepare('SELECT COUNT(*) FROM `post_category` WHERE category_id = :cid');
        $countStmt->execute(['cid' => $categoryId]);
        $total = (int) $countStmt->fetchColumn();

        $perPage = self::PER_PAGE;
        $pages   = max(1, (int) ceil($total / $perPage));
        $page    = max(1, min($page, $pages));
        $offset  = ($page - 1) * $perPage;

        $stmt = self::getPreparedQueryForData($orderBy, $perPage, $offset);
        $stmt->execute(['cid' => $categoryId]);

        return [
            'items' => $stmt->fetchAll(),
            'page'  => $page,
            'pages' => $pages,
            'total' => $total,
        ];
    }

    /**
     * Подготавливает запрос на получение данных по постам категории
     */
    public static function getPreparedQueryForData(string $orderBy, int $limit, int $offset = 0): PDOStatement
    {
        $pdo = Database::getConnection();

        $sql = "SELECT a.`id`, a.`title`, a.`image`, a.`description`, a.`views`, a.`published_at`
                FROM `posts` a
                JOIN `post_category` pc ON pc.`post_id` = a.`id`
                WHERE pc.`category_id` = :cid
                ORDER BY $orderBy
                LIMIT $limit OFFSET $offset";

        return $pdo->prepare($sql);
    }

    /**
     * Получение данных поста с данными связанных категорий
     */
    public static function find(int $id): ?array
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM `posts` WHERE `id` = :id');
        $stmt->execute(['id' => $id]);

        $post = $stmt->fetch();
        if (!$post) {
            return null;
        }

        $catStmt = $pdo->prepare(
            'SELECT c.`id`, c.`name`
             FROM `categories` c
             JOIN `post_category` pc ON pc.`category_id` = c.`id`
             WHERE pc.`post_id` = :id'
        );
        $catStmt->execute(['id' => $id]);
        $post['categories'] = $catStmt->fetchAll();

        return $post;
    }

    /**
     * Инкрементное увеличение количества просмотра поста
     */
    public static function incrementViews(int $id): void
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE `posts` SET `views` = `views` + 1 WHERE `id` = :id');
        $stmt->execute(['id' => $id]);
    }

    /**
     * Получение похожих постов: по общим категориям, исключая текущий
     */
    public static function getSimilar(int $postId, int $limit): array
    {
        $pdo = Database::getConnection();
        $sql = "SELECT DISTINCT a.`id`, a.`title`, a.`image`, a.`description`, a.`views`, a.`published_at`,
                        COUNT(*) AS common_cats
                 FROM `posts` a
                 JOIN `post_category` pc ON pc.`post_id` = a.`id`
                 WHERE pc.`category_id` IN (
                     SELECT `category_id` FROM `post_category` WHERE `post_id` = :pid
                 )
                 AND a.`id` != :post_id
                 GROUP BY a.`id`
                 ORDER BY common_cats DESC, a.`published_at` DESC
                 LIMIT $limit";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['pid' => $postId, 'post_id' => $postId]);

        return $stmt->fetchAll();
    }
}
