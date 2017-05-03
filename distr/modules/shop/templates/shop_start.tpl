<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
 {if $smarty.request.print!=1}
  <td valign="top" class="mod_shop_left">

	<!-- SHOP - NAVI -->
	<div id="leftnavi" style="margin:0px; padding:0px;">
	{$ShopNavi}
	</div>

	<!-- FLOAT END -->
	<div style="clear:both"></div>

	<!-- USER - PANEL -->
	{$UserPanel}

	<!-- USER - ORDERS -->
	{$MyOrders}

	<!-- INFOBOX -->
	{$InfoBox}

	</td>

   {/if}
    <td valign="top" id="contents_middle_shop">

{* <!-- GLOBAL JS -->
<script type="text/javascript" src="modules/shop/js/shop.js"></script> *}

<!-- SHOP - TOP NAVI -->
{$TopNav}<br />

{if $smarty.request.categ==''}
{$RandomOffer}
{if $ShopWillkommen}
{$ShopWillkommen}
<br />
<br />
{/if}
{/if}

{if $KategorienStart==1 && $smarty.request.categ==''}
{include file="$mod_dir/shop/templates/shop_tree.tpl"}
<br />
{/if}

{if $KategorienSons==1 && $smarty.request.categ!=''}
{include file="$mod_dir/shop/templates/shop_tree.tpl"}
<br />
{/if}

{if $smarty.request.categ!=''}
{$RandomOfferKateg}
{/if}

<strong>{#ProductOverview#}</strong>
<br /><br />
<!-- SHOP - ITEMS -->
{include file="$mod_dir/shop/templates/$TemplateArtikel"}

<!-- FOOTER -->
<p>&nbsp;</p><p>&nbsp;</p>
{$FooterText}
<br />
</td>
{if $smarty.request.print!=1}
    <td valign="top" class="mod_shop_menue">
	<!-- BASKET -->
	{$Basket}
	<!-- SHOP - SEARH -->
	{$Search}
	<!-- TOPSELLER -->
	{$Topseller}
</td>
{/if}
  </tr>
</table>