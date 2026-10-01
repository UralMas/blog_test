{extends file="layout.tpl"}

{block name=title}{$category.name}{/block}
{block name=h1}{$category.name}{/block}

{block name=content}
    <p>{$category.description}</p>

    <div class="sort">
        Сортировать:
        <a href="/?page=category&id={$category.id}&sort=date"
           {if $sort == 'date'}style="font-weight:bold"{/if}>по дате публикации</a>
        <a href="/?page=category&id={$category.id}&sort=views"
           {if $sort == 'views'}style="font-weight:bold"{/if}>по просмотрам</a>
    </div>

    {foreach $posts as $post}
        {include file="partials/post_card.tpl"}
    {foreachelse}
        <p>В этой категории пока нет статей.</p>
    {/foreach}

    {if $pagination.pages > 1}
        <div class="pagination">
            {for $i=1 to $pagination.pages}
                {if $i == $pagination.page}
                    <span class="active">{$i}</span>
                {else}
                    <a href="/?page=category&id={$category.id}&sort={$sort}&p={$i}">{$i}</a>
                {/if}
            {/for}
        </div>
    {/if}
{/block}