<div class="mod_searchbox">
<strong>{#LAST_DOC#}</strong><br /><br />
{section name=item loop=$items}
 <strong>{$smarty.section.item.index+1}.</strong> 
   <a href="{$items[item]->Url}">{$items[item]->Titel|truncate:40:"...":true}...</a><br />
{/section} 
</div>

