{extends file="layout.tpl"}

{block name=title}{$post.title}{/block}
{block name=h1}{$post.title}{/block}

{block name=content}
    <article class="post-block">
        {if $post.image}
            <img src="{$post.image}" alt="{$post.title}">
        {/if}

        <div class="post-categories">
            Категории:
            {foreach $post.categories as $c}
                <a href="/?page=category&id={$c.id}">{$c.name}</a>{if !$c@last}, {/if}
            {/foreach}
        </div>

        <div class="post-meta">
            Просмотров: {$post.views} | Дата: {$post.published_at}
        </div>

        <div>{$post.content nofilter}</div>
    </article>

    <h2>Похожие статьи</h2>

    {foreach $similar as $s}
        {include file="partials/post_card.tpl" post=$s}
    {foreachelse}
        <p>Похожих статей не найдено.</p>
    {/foreach}
{/block}