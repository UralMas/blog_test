{extends file="layout.tpl"}

{block name=content}
    {foreach $categories as $category}
        <section class="category-block">
            <h2>{$category.name}</h2>
            <p>{$category.description}</p>

            {foreach $category.posts as $post}
                <div class="post-card">
                    <div class="post-card__grid-row {if $post.image}post-card__grid-row--with-image{/if}">
                        {if $post.image}
                            <div class="post-card__image">
                                <img src="{$post.image}" alt="{$post.title}" />
                            </div>
                        {/if}
                        <div class="post-card__data">
                            <h3><a href="/?page=post&id={$post.id}">{$post.title}</a></h3>
                            <p>{$post.description}</p>
                            <div class="post-card__meta">
                                Дата публикации: {$post.published_at} | Просмотров: {$post.views}
                            </div>
                        </div>
                    </div>
                </div>
            {/foreach}

            <div class="category-block__read-all">
                <a href="/?page=category&id={$category.id}">Все статьи →</a>
            </div>
        </section>
    {foreachelse}
        <p>Категорий со статьями пока нет.</p>
    {/foreach}
{/block}