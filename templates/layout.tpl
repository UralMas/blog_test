<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>{block name=title}Блог{/block}</title>
    <link rel="stylesheet" href="/assets/style.css" />
</head>
<body>
    <header>
        <div class="container"><a href="/">Мой блог</a></div>
    </header>
    <main class="container">
        <h1>{block name=h1}Мой тестовый блог{/block}</h1>
        {block name=content}{/block}
    </main>
    <footer>
        <div class="container">2026 Тестовый блог</div>
    </footer>
</body>
</html>