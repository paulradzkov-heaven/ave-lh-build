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
<!-- SHOP - TOP NAVI -->

{include file="$mod_dir/shop/templates/shop_topnav.tpl"}<br />

{$Inf}

<!-- FOOTER -->
<p>&nbsp;</p><p>&nbsp;</p>
{$FooterText}
<br />
  
  </td>
{if $smarty.request.print!=1}
    <td valign="top" class="mod_shop_menue">
	<!-- BASKET -->
	{$Basket}
	<!-- SHOP - SEARCH -->
	{$Search}
	<!-- TOPSELLER -->
	{$Topseller}
</td>
{/if}

  </tr>
</table>