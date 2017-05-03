{strip}
<div><a href="{$shopStart}" class="{if ($smarty.request.action=='wishlist' || $smarty.request.action=='showbasket' || $smarty.request.action=='checkout') || ($smarty.request.action=='shopstart' && $smarty.request.categ=='')}first_active{else}first_inactive{/if}">{#ShopStart#}</a></div>

{foreach from=$shopnavi item=item name=sn}
{if $item->Elter == 0}
{assign var=op value=$item->Id}
{assign var=firstsecond value=$item->Id}

<a class="{if $smarty.request.categ==$item->Id && $smarty.request.parent==$item->Elter || $smarty.request.navop==$item->Id}first_active{else}first_inactive{/if}" href="{$item->dyn_link}">{$item->visible_title}</a>

{else}
<div style="display:{if $smarty.request.categ!='' && ($smarty.request.categ==$op || $smarty.request.navop==$op) }block{else}none{/if}">


<div class="{if $smarty.request.categ==$item->Id || $smarty.request.parent==$item->Id}shop_sub_div_active{else}shop_sub_div{/if}">
{$item->expander}
<a class="{if $smarty.request.categ==$item->Id || $smarty.request.parent==$item->Id}shopnavi_second_active{else}shopnavi_second_inactive{/if}"  href="{$item->dyn_link}">
{$item->KatName|escape:'html'}
</a>
</div>

</div>
{assign var=subdiv value=''}
 {/if}
{/foreach}
{/strip}