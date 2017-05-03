{assign var="cols" value=2}
{assign var="maxsubs" value=5}

{foreach from=$shopitems item=item name=dl}
{assign var=op value=$smarty.request.navop}
{assign var="newtr" value=$newtr+1}
{math equation='( x / z )' x=100 z=3}

<div style="float:left;">
<div style="padding:3px"> {if $item->Bild!=''}
  <a title="{$item->visible_title|escape:'html'}" href="{$item->dyn_link}">
  <img src="modules/shop/uploads/{$item->Bild}" alt=""  class="absmiddle" border="0" />
  </a>
  <br />
            {else}
			<img src="{$shop_images}folder.gif" alt=""  class="absmiddle" />
			{/if}
            <h3> <a title="{$item->visible_title|escape:'html'}" href="{$item->dyn_link}">{$item->visible_title|truncate:'30'}</a> </h3>
            <br />
            {foreach from=$item->sub item=sub name=subs}
            
            {if $smarty.request.categ==""}
            
            {assign var="num" value=$num+1}
            {if ($num <= $maxsubs)}
			<img src="{$shop_images}folder_small.gif" alt="" hspace="4" vspace="0" class="absmiddle" />
			<a class="categtitle_n" title="{$sub->visible_title|escape:'html'}" href="index.php?module=shop&categ={$sub->Id}&amp;parent={$sub->Elter}&amp;navop={get_parent_shopcateg id=$sub->Id}"> {$sub->visible_title|escape:'html'} </a> {if !$smarty.foreach.subs.last}<br />
            {/if}
            {else}
            
            {if $showalllink!=1}
			<img src="{$shop_images}folder_small.gif" alt="" hspace="4" vspace="0" class="absmiddle" /> <a class="categtitle_n" href="index.php?module=shop&categ={$item->Id}&amp;parent={$item->Elter}&amp;navop={get_parent_shopcateg id=$item->Id}">{#ShowAll#}</a> {assign var=showalllink value=1}
            {/if}
            
            {/if}
            {else} <img src="{$shop_images}folder_small.gif" alt="" hspace="4" vspace="0" class="absmiddle" /> <a class="categtitle_n" title="{$sub->visible_title|escape:'html'}" href="index.php?module=shop&categ={$sub->Id}&amp;parent={$sub->Elter}&amp;navop={get_parent_shopcateg id=$sub->Id}"> {$sub->visible_title|escape:'html'} </a> {*...
            {$sub->data}
            *}
            {if !$smarty.foreach.subs.last} <br />
            {/if}
            {/if}
            {/foreach}
            {assign var=showalllink value=0}
            {assign var="num" value=0} </div>
</div>
        {if $newtr % $cols == 0}
        <div class="clear" style="width:100px">&nbsp;</div>
        {/if} 
		
{/foreach}
<div class="clear" style="width:100px">&nbsp;</div>