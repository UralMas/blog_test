{extends file="layout.tpl"}

{block name=content}
    {foreach $categories as $category}
        <section class="category-block">
            <h2>{$category.name}</h2>
            <p>{$category.description}</p>

            {foreach $category.posts as $post}
                <div class="post-card">
                    {if $post.image}
                        <img src="{$post.image}" alt="{$post.title}">
                    {/if}
                    <h3><a href="/?page=post&id={$post.id}">{$post.title}</a></h3>
                    <p>{$post.description}</p>
                    <div class="meta">
                        Просмотров: {$post.views} |
                        Дата: {$post.published_at}
                    </div>
                </div>
            {/foreach}

            <div class="read-all">
                <a class="btn" href="/?page=category&id={$category.id}">Все статьи →</a>
            </div>
        </section>
    {foreachelse}
        <p>Категорий со статьями пока нет.</p>
    {/foreach}
{/block}