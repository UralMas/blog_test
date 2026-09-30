<?php

declare(strict_types=1);

namespace App\Models;

use App\Database;

final class Category
{
    /**
     * Получает список непустых категорий и их последних постов
     * @return array<int, array>
     */
    public static function getNotEmptyWithPosts(int $amountPosts): array
    {
        $pdo = Database::getConnection();

        // Все категории, в которых есть хотя бы одна статья
        $sql = "SELECT c.`id`, c.`name`, c.`description`
                FROM `categories` c
                WHERE EXISTS (SELECT 1 FROM `post_category` pc WHERE pc.`category_id` = c.id)
                ORDER BY c.`name`";
        $categories = $pdo->query($sql)->fetchAll();

        // Для каждой — последние статьи
        $stmt = $pdo->prepare(
            "SELECT a.`id`, a.`title`, a.`image`, a.`description`, a.`views`, a.`published_at`
             FROM `posts` a
             JOIN `post_category` ac ON ac.`post_id` = a.`id`
             WHERE ac.`category_id` = :cid
             ORDER BY a.`published_at` DESC
             LIMIT $amountPosts"
        );

        foreach ($categories as &$category) {
            $stmt->execute(['cid' => $category['id']]);
            $category['posts'] = $stmt->fetchAll();
        }

        return $categories;
    }
}
