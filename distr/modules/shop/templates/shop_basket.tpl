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
	
	<!-- USER - ORDERS -->
	{$MyOrders}

	<!-- INFOBOX -->
	{$InfoBox}	</td>
   {/if}
    <td valign="top" id="contents_middle_shop2">
<div class="mod_shop_topnav"><a class="mod_shop_navi" href="{$ShopStartLink}">{#PageName#}</a> {#PageSep#} {#ShopBasket#}</div>

{if !$BasketItems}
<br />
<br />
<div align="center"><h2>{#BasketEmpty#}</h2></div>
{else}
<br />
<form method="post" action="index.php?module=shop&amp;action=showbasket&amp;refresh=1">
<div align=""><strong>{#TempOverall#} </strong><span class="mod_shop_basket_summ">{numformat val=$smarty.session.BasketOverall} {$Currency}</span>
    <div style="margin-top:4px"> {#BasketRefresh#}&nbsp;
      <input class="absmiddle" type="image" src="{$shop_images}refresh.gif" />
    </div>
  </div>
  <br />
  <table width="100%" border="0" cellpadding="0" cellspacing="1">
    <tr>
      <td valign="top" class="mod_shop_basket_header">&nbsp;</td>
      <th valign="top" class="mod_shop_basket_header">{#BasketAmount#}</th>
      <th valign="top" class="mod_shop_basket_header">{#BasketArticles#}</th>
      <th valign="top" align="right" class="mod_shop_basket_header">{#BasketSummO#}</th>
      <th valign="top" align="right" class="mod_shop_basket_header">{#BasketSumm#}</th>
      <th align="right" valign="top" class="mod_shop_basket_header">{#BasketDel#}</th>
    </tr>
    {foreach from=$BasketItems item=bi}
    <tr>
      <td valign="top" nowrap="nowrap" class="mod_shop_basket_row"> {if $bi->BildFehler==1}
        &nbsp;{* <img src="{$shop_images}no_productimage.gif" alt="" /> *}
        {else} <a class="reflect" rel="lightbox" href="modules/shop/uploads/{$bi->Bild}" title="{$bi->ArtName|truncate:100|stripslashes|escape:html}"><img src="modules/shop/thumb.php?file={$bi->Bild}&type={$bi->Bild_Typ}&x_width=50" alt="{$bi->ArtName|truncate:175|stripslashes|escape:html}" border="0" /></a> {/if} </td>
      <td align="center" valign="top" class="mod_shop_basket_row">
        <input class="mod_shop_inputfields" name="amount[{$bi->Id}]" type="text" size="3" maxlength="3" value="{$bi->Anzahl}"  />
      </td>
      <td valign="top" class="mod_shop_basket_row"> <a href="{$bi->ProdLink}" target="_blank"><strong>{$bi->ArtName|truncate:100|escape:html}</strong></a> - {$bi->Hersteller_Name}
        <br /><small>{#ArtNr#} {$bi->ArtNr}</small>
		<!-- DELIVERY TIME -->
        {if $bi->Versandfertig}
        <div class="mod_shop_timetillshipping">{$bi->Versandfertig}</div>
        {/if}
        <!-- PRODUCT VARIATIONS -->
        {if $bi->Vars} <br />
        <br />
        <strong>{#ProductVars#}</strong><br />
        {foreach from=$bi->Vars item=vars} <span class="mod_shop_basket_price"> <em>{$vars->VarName|stripslashes}</em><br />
        {$vars->Name|stripslashes} {$vars->Operant} {numformat val=$vars->WertE} {$Currency} </span> <br />
        {/foreach}
        {/if}
        <!-- PRODUCT VARIATIONS END -->
      </td>
      <td align="right" valign="top" class="mod_shop_basket_row_price" nowrap="nowrap">
	  {numformat val=$bi->EPreis} {$Currency}{if $bi->PreisW2 && $ZeigeWaehrung2=='1'}<br /><span class="mod_shop_ust">{numformat val=$bi->PreisW2} {$Currency2}</span>{/if}
	  </td>
      <td align="right" valign="top" class="mod_shop_basket_row_price" nowrap="nowrap">{numformat val=$bi->EPreisSumme} {$Currency}{if $bi->PreisW2Summ && $ZeigeWaehrung2=='1'}<br /><span class="mod_shop_ust">{numformat val=$bi->PreisW2Summ} {$Currency2}</span>{/if}</td>
      <td align="center" valign="top" class="mod_shop_basket_row_right">
        <input name="del_product[{$bi->Id}]" type="checkbox" value="1" />
       </td>
    </tr>
    {/foreach}
  </table>
 <br />
  <table width="100%" border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td>
        <div align="right"><strong>{#TempOverall#} </strong></div>
      </td>
      <td width="120">
        <div align="right"><span class="mod_shop_basket_summ"> {numformat val=$smarty.session.BasketOverall} {$Currency}</span>{if $smarty.session.BasketSummW2}<br /><span class="mod_shop_ust">{numformat val=$smarty.session.BasketSummW2} {$Currency2}</span>{/if}
		</div>
      </td>
    </tr>
    {foreach from=$VatZones item=vz}
    {if $smarty.session.VatInc!='' && in_array($vz->Wert,$smarty.session.VatInc)}
    <tr>
      <td>
        <div align="right">{#IncludesVatInf#} {$vz->Wert}%:</div>
      </td>
      <td width="120"> {assign var=VatSessionName value=$vz->Wert}
        <div align="right">{numformat val=$smarty.session.$VatSessionName} {$Currency}</div>
      </td>
    </tr>
    {/if}
    {/foreach}
  </table>
  <br />
  {if $BasketItems} <br />
  {/if}
  <table width="100%">
    <tr>
      <td>
        <input class="absmiddle" type="image" src="{$shop_images}refresh.gif" />
      </td>
      <td align="right"><a href="{$CheckoutLink}"><img src="{$shop_images}checkout.gif" alt="" border="0" /></a></td>
    </tr>
  </table>
</form>
{/if}


<!-- FOOTER -->
<p>&nbsp;</p><p>&nbsp;</p>
{$FooterText}
<br />
  </td>
</tr>
</table>