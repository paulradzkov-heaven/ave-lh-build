<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
   {if $smarty.request.print!=1}
    <td valign="top" class="mod_shop_left">
	<!-- SHOP - NAVI -->
	<div id="leftnavi" style="margin:0px; padding:0px;">
	{$ShopNavi}	</div>
	
	<!-- FLOAT END -->
	<div style="clear:both"></div>
	
	<!-- USER - PANEL -->
	{$UserPanel}
	
	<!-- USER - BESTELLUNGEN -->
	{$MyOrders}

	<!-- INFOBOX -->
	{$InfoBox}	</td>
   {/if}
    <td valign="top" id="contents_middle_shop2">
<div class="mod_shop_topnav"> <a class="mod_shop_navi" href="{$ShopStartLink}">{#PageName#}</a> {#PageSep#} {#OrderOkNav#} </div>
{include file="$mod_dir/shop/templates/shop_bar.tpl"} 
{literal}
<script language="javascript">
function OrderPrint(id){
	var html = document.getElementById(id).innerHTML;
	//html = html.replace(/src="/gi, 'src="../' );
	html = html.replace(/&lt;/gi, '<' );
	html = html.replace(/&gt;/gi, '>' );
	html = html.replace(/&amp;/gi, '&' );
	var pFenster = window.open( '', null, 'height=600,width=780,toolbar=yes,location=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes' ) ;
	var HTML = '<body onload="print();">' + html;
	pFenster.document.write(HTML);
	pFenster.document.close();
}
</script>
{/literal}

<br />
<br />

<h2>{#OrderPrintM1#}</h2>
<p>{#OrderPrintM2#}<br>
  <br>
<div id="{$smarty.session.TransId}" style="display:none">{$innerhtml}</div>
&gt;&gt; <a href="javascript:OrderPrint('{$smarty.session.TransId}');">{#OrderPrint#}</a></p>

    <!-- FOOTER -->

<p>&nbsp;</p><p>&nbsp;</p>
{$FooterText}
<br />
  </td>
</tr>
</table>