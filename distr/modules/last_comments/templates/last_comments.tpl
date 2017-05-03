<div class="mod_searchbox">
<strong>{#LAST_COMMENTS#}</strong><br /><br />
{foreach from=$items item=items}
  {#AUTHOR#} <strong>{$items->Author}</strong><br />
  "...<em>{$items->Text|truncate:"150"}</em>"<br /><br />
  <small>{$items->Erstellt|date_format:"%d-%m-%Y ã. %H:%M"}&nbsp; | &nbsp;<a href="{$items->Url}">{#READMORE#}</a></small><br /><br />
  <hr>
{/foreach} 
</div>