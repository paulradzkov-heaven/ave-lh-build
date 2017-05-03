<div id="loginform">
<strong>{#TOP_USERS#}</strong><br /><br />
{section name=item loop=$items}
  <strong>{$smarty.section.item.index+1} {#TOP_PLACE#}</strong> {$items[item]->Author}<br /><div style="text-align:right; font-size:11px;">({#TOP_COMMENTS#} {$items[item]->top_comments})</div><br /><br />
{/section} 
</div>