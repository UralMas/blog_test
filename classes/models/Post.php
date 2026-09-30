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
     * Подготавливаниет запрос на получение данных по постам категории
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
}
