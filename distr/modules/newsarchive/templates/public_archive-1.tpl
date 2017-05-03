<div class="mod_searchbox">
<strong>{#ARCHIVE_TITLE#}</strong><br /><br />
{foreach from=$months item=items}
    {if $items->nums == 0}
      {if $items->show_empty == 1}
      <span style="line-height: 25px;"><a href="/newsarchive-{$archiveid}/{$items->mid}/{$items->year}/">{$items->month}, {$items->year}</a> <small>({$items->nums})</small></span><br />
      {/if}
    {else}
      <span style="line-height: 25px;"><a href="/newsarchive-{$archiveid}/{$items->mid}/{$items->year}/">{$items->month}, {$items->year}</a> <small>({$items->nums})</small></span><br />
    {/if}
{/foreach}
</div>