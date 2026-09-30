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
                WHERE EXISTS (SELECT 1 FROM `post_category` pc WHERE pc.`category_id` = c.`id`)
                ORDER BY c.`name`";
        $categories = $pdo->query($sql)->fetchAll();

        // Для каждой — последние статьи
        $stmt = Post::getPreparedQueryForData('a.`published_at` DESC', $amountPosts);

        foreach ($categories as &$category) {
            $stmt->execute(['cid' => $category['id']]);
            $category['posts'] = $stmt->fetchAll();
        }
        unset($category);

        return $categories;
    }

    /**
     * Получает данные категории
     */
    public static function find(int $id): ?array
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM `categories` WHERE `id` = :id');
        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }
}
