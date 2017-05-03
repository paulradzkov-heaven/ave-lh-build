{if $shopitems}
{assign var="cols" value=3}
{assign var="maxsubs" value=5}

{foreach from=$shopitems item=item name=dl}
{assign var="newtr" value=$newtr+1}
<div style="float:left;">
  <div style="padding:3px"> {if $item->Bild!=''} <a title="{$item->visible_title|escape:'html'}" href="{$item->dyn_link}"> <img src="modules/shop/uploads/{$item->Bild}" alt=""  class="absmiddle" border="0" /> </a> <br />
    {else} <img src="{$shop_images}folder.gif" alt=""  class="absmiddle" /> {/if}
    <h3> <a {if $item->KatBeschreibung}{popup sticky=false text=$item->KatBeschreibung|escape:html}{/if} title="{$item->visible_title|escape:'html'}" href="{$item->dyn_link}">{$item->visible_title|truncate:'30'}</a> </h3>
    <br />
  </div>
</div>
{if $newtr % $cols == 0}
<div class="clear" style="width:100px">&nbsp;</div>
{/if} 
		
{/foreach}
<div class="clear" style="width:100px">&nbsp;</div>
{/if}