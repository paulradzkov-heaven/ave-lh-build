<h2>{#SEARCH_RESULTS#}</h2>
<br />
{if $no_results==1}
  {#SEARCH_NO_RESULTS#}
{else}

{if $q_navi}
  <div class="mod_search_pages"> {#SEARCH_PAGES#} {$q_navi} </div>
{/if} <br />
{foreach from=$searchresults item=res}
<div class="mod_search_title"> <a href="{$res->Url}">{$res->Titel}</a> </div>
<div class="mod_search_text"> {$res->Text} </div>
<div class="mod_search_footernavi"> <a href="{$res->Url}">{#SEARCH_VIEW#}</a> | <a target="_blank" href="{$res->Url}">{#SEARCH_VIEW_BLANK#}</a> {$Geklickt}</div>
<br />
{/foreach} <br />
{if $q_navi}
<div class="mod_search_pages"> {#SEARCH_PAGES#} {$q_navi} </div>
{/if}

{/if}
{include file="$inc_path/form_big.tpl"}