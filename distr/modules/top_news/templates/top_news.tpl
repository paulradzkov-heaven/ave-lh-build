<div class="mod_searchbox">
<strong>{#TOP_NEWS#}</strong><br /><br />
{section name=item loop=$items}
 <strong>{$smarty.section.item.index+1}.</strong> 
   <a href="{$items[item]->Url}">{$items[item]->dt|truncate:36:"...":true}...</a><br />
   <div style="text-align:right; font-size:11px;">({#TOP_COMMENTS#} {$items[item]->top_comments})</div><br />
{/section} 
</div>

