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

<form name="process" method="post" action="index.php">

  <div id="shopcontent">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top">
{*           <!-- GLOBAL JS -->
          <script type="text/javascript" src="modules/shop/js/shop.js"></script> *}
          <div class="mod_shop_topnav"> <a class="mod_shop_navi" href="{$ShopStartLink}">{#PageName#}</a> {#PageSep#} <a class="mod_shop_navi" href="{$ShowBasketLink}">{#ShopBasket#}</a> {#PageSep#} <a href="index.php?module=shop&action=checkout">{#BillingName#}</a> {#PageSep#} {#CheckoutInfoStatus#} </div>
          {include file="$mod_dir/shop/templates/shop_bar.tpl"} <br />
          {#CheckoutInfoText#}
		  <br />
          {if $NoAGB==1} <br />
          <div class="mod_shop_warn">{#ErrorNoAGBText#}</div>
          {/if} <br />
          <div class="mod_shop_payment_headers">{#TextComplete#}</div>
          <table width="100%">
            <tr>
              <td valign="top">
                <h2>{#ShopShippingAdress#}</h2>
                <br />
                {if $smarty.session.billing_company!=''}<strong>{$smarty.session.billing_company}</strong><br />
                {/if}
                {if $smarty.session.billing_company_reciever!=''}<em>{$smarty.session.billing_company_reciever}</em><br />
                {/if}
                {$smarty.session.billing_firstname} {$smarty.session.billing_lastname}<br />
                <br />
                {$smarty.session.billing_street} {$smarty.session.billing_streetnumber}<br />
                {$smarty.session.billing_zip} {$smarty.session.billing_town}<br />
                {$smarty.request.Land}<br />
              </td>
              <td valign="top">
                <h2>{#BillAdress#}</h2>
                <br />
                {if $smarty.session.shipping_firstname=='' || $smarty.session.shipping_lastname==''}{#SameAsShipping#}{else}
                {if $smarty.session.shipping_company!=''}<strong>{$smarty.session.shipping_company}</strong><br />
                {/if}
                {if $smarty.session.shipping_company_reciever!=''}<em>{$smarty.session.shipping_company_reciever}</em><br />
                {/if}
                
                {$smarty.session.shipping_firstname} {$smarty.session.shipping_lastname}<br />
                <br />
                {$smarty.session.shipping_street} {$smarty.session.shipping_streetnumber}<br />
                {$smarty.session.shipping_zip} {$smarty.session.shipping_town}<br />
                {$smarty.request.RLand}<br />
              {/if} </td>
            </tr>
            <tr>
              <td valign="top"><br />
                <br />
              </td>
              <td valign="top">&nbsp;</td>
            </tr>
          </table>
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <th valign="top" class="mod_shop_basket_header">{#BasketArticles#}</th>
              <th valign="top" align="right" class="mod_shop_basket_header">{#BasketAmount#}</th>
              <th valign="top" align="right" class="mod_shop_basket_header">{#BasketSummO#}</th>
              <th valign="top" align="right" class="mod_shop_basket_header">{#BasketSumm#}</th>
            </tr>
            {foreach from=$BasketItems item=bi}
            <tr>
              <td valign="top" class="mod_shop_basket_row"> <a href="{$bi->ProdLink}"><strong>{$bi->ArtName|truncate:100|escape:html}</strong></a>
                <!-- DELIVERY TIME -->
                {if $bi->Versandfertig}
                <div class="mod_shop_timetillshipping">{$bi->Versandfertig}</div>
                {/if}
                <!-- PRODUCT VARIATIONS -->
                {if $bi->Vars} <br />
                <br />
                <strong>{#ProductVars#}</strong><br />
                {foreach from=$bi->Vars item=vars} <span class="mod_shop_basket_price"> <em>{$vars->VarName|stripslashes}</em><br />
                {$vars->Name|stripslashes} {$vars->Operant}{$vars->WertE} {$Currency} </span> <br />
                {/foreach}
                {/if}
                <!-- PRODUCT VARIATIONS END -->
              </td>
              <td align="center" valign="top" class="mod_shop_basket_row_price">{$bi->Anzahl}</td>
              <td align="right" valign="top" class="mod_shop_basket_row_price" nowrap="nowrap">{numformat val=$bi->EPreis} {$Currency}</td>
              <td align="right" valign="top" class="mod_shop_basket_row_price" nowrap="nowrap">{numformat val=$bi->EPreisSumme} {$Currency}</td>
            </tr>
            {/foreach}
          </table>
          <br />
		  
		  {if $couponcodes==1}
		  <div class="mod_shop_payment_headers">{#CouponCodeText#}</div>
		  <div style="padding:2px">
		  {if $smarty.session.CouponCode > 0}
		  {#CouponcodeOk#}
		  <br />    
		  <input type="hidden" id="coup_del" name="couponcode_del" value="" />
		   <input onclick="document.getElementById('coup_del').value='1'"  class="absmiddle" type="image" src="{$shop_images}coupon_delete.gif" alt="{#ButtonCouponDelete#}" />
		  {else}
		  
		  <input class="mod_shop_inputfields" type="text" name="couponcode" value="" />
		  <input class="absmiddle" type="image" src="{$shop_images}coupon_ok.gif" alt="{#ButtonCouponSend#}" />
		  {/if}
		  </div>
		  {/if}
		  
		  
          <br />
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="mod_shop_summlist" align="right">{#ShippingMethod#}</td>
              <td width="100" align="right" class="mod_shop_summlist">{$ShipperName|stripslashes}</td>
            </tr>
            <tr>
              <td class="mod_shop_summlist" align="right">{#BillingMethod#}</td>
              <td width="100" align="right" class="mod_shop_summlist">{$PaymentMethod|stripslashes}</td>
            </tr>
            <tr>
              <td class="mod_shop_summlist">&nbsp;</td>
              <td width="100" align="right" class="mod_shop_summlist">&nbsp;</td>
            </tr>
            <tr>
              <td class="mod_shop_summlist" align="right">{#OrdersSumm#}</td>
              <td width="100" align="right" class="mod_shop_summlist">{numformat val=$smarty.session.Zwisumm} {$Currency}</td>
            </tr>
			
			{if $smarty.session.CouponCode > 0}
			 <tr>
              <td class="mod_shop_summlist" width="200" align="right">{#Coupon#}</td>
              <td width="100" align="right" class="mod_shop_summlist">-{$smarty.session.CouponCode} %</td>
            </tr>
			{/if}
			
            {if $smarty.session.Rabatt>0}
            <tr>
              <td class="mod_shop_summlist" align="right">{#CustomerDiscount#}</td>
              <td width="100" align="right" class="mod_shop_summlist">-{$smarty.session.RabattWert}{* ({numformat val=$smarty.session.Rabatt}) {$Currency}*}</td>
            </tr>
            {/if}
			
		
			
            <tr>
              <td class="mod_shop_summlist" width="200" align="right">{#Packing#} </td>
              <td width="100" align="right" class="mod_shop_summlist"> {numformat val=$smarty.session.ShippingSumm} {$Currency}</td>
            </tr>
			
			{if $smarty.session.KostenZahlungOut>0}
            <tr>
              <td class="mod_shop_summlist" width="200" align="right">{#SummBillingMethod#}</td>
              <td width="100" align="right" class="mod_shop_summlist">{$smarty.session.KostenZahlungPM}{$smarty.session.KostenZahlungOut} {$smarty.session.KostenZahlungSymbol}</td>
            </tr>
			{/if}
			
			
			
            <tr>
              <td class="mod_shop_summoverall"  align="right">{#SummOverall#}</td>
              <td width="100" align="right" class="mod_shop_summoverall"> {numformat val=$PaymentOverall} {$Currency}{if $smarty.session.BasketSummW2}<br /><span class="mod_shop_ust">{numformat val=$PaymentOverall2} {$Currency2}</span>{/if}
			  </td>
            </tr>
           
		   	{if $smarty.session.CouponCode > 0}
			{foreach from=$VatZones item=vz}
            {if $smarty.session.VatInc!='' && in_array($vz->Wert,$smarty.session.VatInc)}
			 <tr>
              <td class="mod_shop_incvat" align="right"> {#IncVat#} {$vz->Wert}%</td>
              <td width="100" class="mod_shop_incvat" align="right">&nbsp;</td>
            </tr>
			{/if}
            {/foreach}
			{else}
		    {foreach from=$VatZones item=vz}
            {if $smarty.session.VatInc!='' && in_array($vz->Wert,$smarty.session.VatInc)}
            <tr>
              <td class="mod_shop_incvat" align="right"> {#IncVat#} {$vz->Wert}%: </td>
              <td width="100" class="mod_shop_incvat" align="right"> {assign var=VatSessionName value=$vz->Wert} {numformat val=$smarty.session.$VatSessionName} {$Currency}</td>
            </tr>
            {/if}
            {/foreach}
			{/if}
			
			
            <tr>
              <td width="200">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
          <br />
          {if $smarty.session.ShowNoVatInfo==1}
          <div class="mod_shop_warn">{#WarnVatInc#}</div>
          {/if} <br />
          <a name="agb"></a>
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td class="mod_shop_payment_headers">{#AGB#}</td>
              <td>&nbsp;&nbsp;</td>
              <td class="mod_shop_payment_headers">{#MessageTitle#}</td>
            </tr>
            <tr>
              <td width="60%">
			  
                <div class="mod_shop_inputfields" style="width:97%;height:120px; overflow:auto" name="Agb">{$ShopAgb}</div>
              </td>
              <td>&nbsp;&nbsp;</td>
              <td>
                <textarea class="mod_shop_inputfields" style="width:97%;height:120px" name="Msg">{$smarty.request.Msg|escape:html|stripslashes}</textarea>
              </td>
            </tr>
          </table>
		  <br />
          <div class="mod_shop_payment_table" style="padding:4px"><input id="abg_accept" type="checkbox" name="agb_accept" value="1" /><label for="abg_accept">{#AcceptAGB#}</label> </div>
		  
          <br />
          <input type="hidden" name="module" value="shop" />
          <input type="hidden" name="action" value="checkout" />
          <input type="hidden" name="sendorder"  value="1" />
          <input type="hidden" name="create_account" value="{$smarty.request.create_account}" />
          <input type="hidden" name="zusammenfassung" id="zus" value="1" />
          <input type="hidden" name="PaymentId" value="{$smarty.session.PaymentId}" />
          <input type="hidden" name="ShipperId" value="{$smarty.session.ShipperId}" />
          <input type="hidden" name="billing_zip" value="{$smarty.session.billing_zip}" />
          <input type="hidden" name="Land" value="{$smarty.request.Land}" />
          <input type="hidden" name="RLand" value="{$smarty.request.RLand}" />
          <input class="absmiddle" type="image" src="{$shop_images}sendorder.gif" />
        </td>
      </tr>
    </table>
  </div>
</form>

<!-- FOOTER -->
<p>&nbsp;</p><p>&nbsp;</p>
{$FooterText}
<br />
  </td>
</tr>
</table>