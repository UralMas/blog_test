{extends file="layout.tpl"}

{block name=content}
    {foreach $categories as $category}
        <section class="category-block">
            <h2>{$category.name}</h2>
            <p>{$category.description}</p>

            {foreach $category.posts as $post}
                {include file="partials/post_card.tpl"}
            {/foreach}

            <div class="category-block__read-all">
                <a href="/?page=category&id={$category.id}">Все статьи →</a>
            </div>
        </section>
    {foreachelse}
        <p>Категорий со статьями пока нет.</p>
    {/foreach}
{/block}