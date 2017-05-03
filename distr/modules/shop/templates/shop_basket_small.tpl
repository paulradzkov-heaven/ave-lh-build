{strip}
<div class="mod_shop_basket">
		<strong>{#ShopBasket#}</strong>
		<br />
		<br />
		{if !$BasketItems}
		{#BasketEmpty#}
		{else}
		<table width="100%" border="0" cellpadding="1" cellspacing="0">
			<tr>
			{foreach from=$BasketItems item=bi}
				<td valign="top" nowrap="nowrap">{$bi->Anzahl} x</td>
				<td valign="top">
				<a {popup sticky=false text=$bi->ArtName|escape:html} href="{$bi->ProdLink}">{$bi->ArtName|truncate:20|escape:html}</a>
				
				<!-- PRODUCT VARIATIONS -->
				{*
				{if $bi->Vars}
				<br />
				{foreach from=$bi->Vars item=vars}
				<small class="mod_shop_basket_price">
				<strong>{$vars->VarName|stripslashes}</strong><br />
				{$vars->Name|stripslashes}
				<br />
				{$vars->Operant}{$vars->Wert} {$Currency}
				</small>
				<br />
				{/foreach}
				{/if}
				*}
				<!-- PRODUCT VARIATIONS END -->
				
			  </td>
			  
				<td valign="top" align="right">
				<form method="post" action="{$bi->DelLink}"><input {popup sticky=false text=$config_vars.OL_DeleteItem} class="absmiddle" type="image" src="{$shop_images}delete.gif" /></form>
				</td>
			</tr>
			{/foreach}
  </table>
		{/if}
		<br />
		<small class="mod_shop_basket_price">{#TempOverall#} {numformat val=$smarty.session.BasketOverall} {$Currency}</small>
		<br />
		{if $BasketItems}
		<br />
		<img class="absmiddle" src="{$shop_images}arrow.gif" alt="" /> <a href="{$ShowBasketLink}">{#GotoBasket#}</a><br />
		<img class="absmiddle" src="{$shop_images}arrow.gif" alt="" /> <a href="{$ShowPaymentLink}">{#GotoCheckout#}</a>
		<br />
		{/if}
		{if $WishListActive==1}
		<br />
		<img class="absmiddle" src="{$shop_images}arrow.gif" alt="" /> <a href="index.php?module=shop&amp;action=wishlist">{#Wishlist#}</a>
		&nbsp; &laquo; <a {popup sticky=false text=$config_vars.WishlistInf} href="#">{#WhatsThat#}</a>
		{/if}
</div>
{/strip}