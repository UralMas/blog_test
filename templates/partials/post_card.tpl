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
            <div class="post-meta">
                Дата публикации: {$post.published_at} | Просмотров: {$post.views}
            </div>
        </div>
    </div>
</div>