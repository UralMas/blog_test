<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Database;

$pdo = Database::getConnection();

// Очистка таблиц
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE post_category');
$pdo->exec('TRUNCATE TABLE posts');
$pdo->exec('TRUNCATE TABLE categories');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

// Категории
$categories = [
    ['Технологии', 'Новости и статьи о современных технологиях'],
    ['Путешествия', 'Рассказы о поездках и интересных местах'],
    ['Кулинария', 'Рецепты и кулинарные советы'],
    ['Спорт', 'Новости спорта и здоровый образ жизни'],
];

$catStmt = $pdo->prepare('INSERT INTO `categories` (`name`, `description`) VALUES (:n, :d)');
$catIds  = [];

foreach ($categories as $c) {
    $catStmt->execute(['n' => $c[0], 'd' => $c[1]]);
    $catIds[] = (int) $pdo->lastInsertId();
}

// Статьи
$titles = [
    'Введение в PHP 8.1',
    'Обзор Smarty шаблонизатора',
    'MySQL: оптимизация запросов',
    'Путешествие в Токио',
    'Как собрать рюкзак в поход',
    '10 причин посетить Исландию',
    'Паста карбонара: классический рецепт',
    'Топ-5 полезных завтраков',
    'Домашний хлеб без замеса',
    'Утренняя зарядка для начинающих',
    'Бег на 10 км: план тренировок',
    'Йога для спины',
    'Docker для разработчика',
    'REST vs GraphQL',
    'Что нового в PHP 8.5',
];

$postStmt = $pdo->prepare(
    'INSERT INTO `posts` (`image`, `title`, `description`, `content`, `views`, `published_at`)
     VALUES (:img, :t, :d, :c, :v, :p)'
);
$linkStmt = $pdo->prepare('INSERT INTO `post_category` (`category_id`, `post_id`) VALUES (:c, :p)');

foreach ($titles as $i => $title) {
    $postStmt->execute([
        'img' => 'https://f.sravni.ru/cms/KnowledgeBaseArticle/2kursy/1669888087133.png',
        't'   => $title,
        'd'   => "Краткое описание статьи «{$title}».",
        'c'   => "<p>Полный текст статьи «{$title}».</p><p>Здесь может быть много интересного контента.</p>",
        'v'   => random_int(0, 500),
        'p'   => date('Y-m-d H:i:s', strtotime("-$i days")),
    ]);
    $postId = (int) $pdo->lastInsertId();

    // Привязка к 1-2 случайным категориям
    foreach ((array) array_rand($catIds, random_int(1, 2)) as $idx) {
        $linkStmt->execute(['p' => $postId, 'c' => $catIds[$idx]]);
    }
}

echo 'Сидинг завершён: ' . count($categories) . ' категорий, ' . count($titles) . ' статей.' . PHP_EOL;
