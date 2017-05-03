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
<div id="shopcontent">
<div class="mod_shop_topnav"><a class="mod_shop_navi" href="{$ShopStartLink}">{#PageName#}</a> {#PageSep#} <a class="mod_shop_navi" href="{$ShowBasketLink}">{#ShopBasket#}</a> {#PageSep#} {#ShopPaySite#}</div>

{include file="$mod_dir/shop/templates/shop_bar.tpl"}

<br />
<form name="process" method="post" action="index.php">
<input type="hidden" name="module" value="shop" />
<input type="hidden" name="action" value="checkout" />
<input type="hidden" name="send"  value="1" />
<input type="hidden" name="create_account" value="{$smarty.request.create_account}" />
{if $errors}
<div class="mod_shop_warn">
<strong>{#ErrorsA#}</strong><br />
<ul>
{foreach from=$errors item=e}
<li style="line-height:1.1em">{$e}</li>
{/foreach}
</ul>
 </div>
{/if}
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td valign="top">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
  <tr>
    <td class="mod_shop_payment_headers">{#ShopShippingAdress#}</td>
    </tr>
  <tr>
    <td valign="top" style="padding:0px">
      
	  <table width="100%" border="0" cellspacing="0" cellpadding="3" class="mod_shop_payment_table">
        <tr>
          <td>{#SSCompany#}</td>
          <td>

            <input name="billing_company" type="text" class="mod_shop_inputfields" style="width:130px" value="{if $smarty.session.billing_company}{$smarty.session.billing_company|stripslashes}{else}{$row->Firma}{/if}" maxlength="75" />
          </td>
        </tr>
        
        <tr>
          <td>{#SSSect#}</td>
          <td>
            <input name="billing_company_reciever" type="text" class="mod_shop_inputfields" style="width:130px" value="{if $smarty.session.billing_company_reciever}{$smarty.session.billing_company_reciever|stripslashes}{/if}" maxlength="35" />
          </td>
        </tr>
        <tr>
          <td>{#SSFirst#}</td>
          <td>
            <input name="billing_firstname" type="text" class="mod_shop_inputfields" style="width:130px" value="{if $smarty.session.billing_firstname != ''}{$smarty.session.billing_firstname|stripslashes}{else}{$row->Vorname}{/if}" maxlength="35" />
          </td>
        </tr>
        
        <tr>
          <td>{#SSLAst#}</td>
          <td>
            <input name="billing_lastname" type="text" class="mod_shop_inputfields" style="width:130px" value="{if $smarty.session.billing_lastname != ''}{$smarty.session.billing_lastname|stripslashes}{else}{$row->Nachname}{/if}" maxlength="35" />
          </td>
        </tr>
        <tr>
          <td>{#SSStreet#}/{#SSHnr#}</td>
          <td>
            <input name="billing_street" type="text" class="mod_shop_inputfields" style="width:130px" value="{if $smarty.session.billing_street != ''}{$smarty.session.billing_street|stripslashes}{else}{$row->Strasse}{/if}" maxlength="35" />
            <input name="billing_streetnumber" type="text" class="mod_shop_inputfields" style="width:40px" value="{if $smarty.session.billing_streetnumber != ''}{$smarty.session.billing_streetnumber|stripslashes}{else}{$row->HausNr}{/if}" maxlength="10" />
          </td>
        </tr>
        <tr>
          <td>{#SSZip#}/{#SSTown#}</td>
          <td>
            <input name="billing_zip" type="text" class="mod_shop_inputfields" style="width:40px" value="{if $smarty.session.billing_zip != ''}{$smarty.session.billing_zip|stripslashes}{else}{$row->Postleitzahl}{/if}" maxlength="15" />
            <input name="billing_town" type="text" class="mod_shop_inputfields" style="width:130px" value="{if $smarty.session.billing_town != ''}{$smarty.session.billing_town|stripslashes}{else}{$row->Ort}{/if}" maxlength="25" />
          </td>
        </tr>
        <tr>
          <td>{#SSEmail#}</td>
          <td><input name="OrderEmail" type="text" class="mod_shop_inputfields" style="width:130px" value="{if $smarty.session.OrderEmail != ''}{$smarty.session.OrderEmail|stripslashes}{else}{$row->Email}{/if}" maxlength="35" /></td>
        </tr>
        <tr>
          <td>{#SSPhone#}</td>
          <td><input name="OrderPhone" type="text" class="mod_shop_inputfields" style="width:130px" value="{if $smarty.session.OrderPhone != ''}{$smarty.session.OrderPhone|stripslashes}{else}{$row->Telefon}{/if}" maxlength="35" /></td>
        </tr>
        <tr>
          <td>{#SSCountry#}</td>
          <td>
		
<select name="Land" id="l_land" style="width:180px" class="mod_shop_inputfields" onchange="document.process.submit();">
<option value="">Выберите страну </option>
{if $smarty.request.Land!=''}
{assign var=sL value=$smarty.request.Land}
{else}
{assign var=ssL value=$row->Land|default:$defland}
{/if}

{foreach from=$available_countries item=land}
{if in_array($land->LandCode|upper,$shippingCountries)}
<option value="{$land->LandCode|upper}" {if $sL==$land->LandCode|upper}selected{/if}>{$land->LandName}</option>
{/if}
{/foreach}
</select>
          </td>
        </tr>
      </table>
    </td>
    </tr>
</table>
</td>
<td>&nbsp;&nbsp;</td>
<td valign="top">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
  <tr>
    <td class="mod_shop_payment_headers">{#ShopBillingAdress#}</td>
  </tr>
  <tr>
    <td valign="top" style="padding:0px">
      <table width="100%" border="0" cellspacing="0" cellpadding="3" class="mod_shop_payment_table">
        <tr>
          <td>{#SSCompany#}</td>
          <td>
            <input class="mod_shop_inputfields" name="shipping_company" type="text" style="width:130px" value="{if $smarty.session.shipping_company != ''}{$smarty.session.shipping_company|stripslashes}{else}{$row->company}{/if}" />
          </td>
        </tr>
        
        <tr>
          <td>{#SSSect#}</td>
          <td>
            <input class="mod_shop_inputfields" name="shipping_company_reciever" type="text" style="width:130px" value="{if $smarty.session.shipping_company_reciever != ''}{$smarty.session.shipping_company_reciever|stripslashes}{/if}" />
          </td>
        </tr>
        <tr>
          <td>{#SSFirst#}</td>
          <td>
            <input class="mod_shop_inputfields" name="shipping_firstname" type="text" style="width:130px" value="{if $smarty.session.shipping_firstname != ''}{$smarty.session.shipping_firstname|stripslashes}{else}{$row->name}{/if}" />
          </td>
        </tr>
        
        <tr>
          <td>{#SSLAst#}</td>
          <td>
            <input class="mod_shop_inputfields" name="shipping_lastname" type="text" style="width:130px" value="{if $smarty.session.shipping_lastname != ''}{$smarty.session.shipping_lastname|stripslashes}{else}{$row->lastname}{/if}" />
          </td>
        </tr>
        <tr>
          <td>{#SSStreet#}/{#SSHnr#}</td>
          <td>
            <input class="mod_shop_inputfields" name="shipping_street" type="text" style="width:130px" value="{if $smarty.session.shipping_street != ''}{$smarty.session.shipping_street|stripslashes}{else}{$row->street}{/if}" />
            <input class="mod_shop_inputfields" name="shipping_streetnumber" type="text" style="width:40px" value="{if $smarty.session.shipping_streetnumber != ''}{$smarty.session.shipping_streetnumber|stripslashes}{/if}" />
          </td>
        </tr>
        <tr>
          <td>{#SSZip#}/{#SSTown#}</td>
          <td>
            <input class="mod_shop_inputfields" name="shipping_zip" type="text" style="width:40px" value="{if $smarty.session.shipping_zip != ''}{$smarty.session.shipping_zip|stripslashes}{else}{$row->zip}{/if}" />
            <input class="mod_shop_inputfields" name="shipping_town" type="text" style="width:130px" value="{if $smarty.session.shipping_town != ''}{$smarty.session.shipping_town|stripslashes}{else}{$row->user_from}{/if}" />
          </td>
        </tr>
        <tr>
          <td>{#SBCountry#}</td>
          <td>
<select name="RLand" id="ll_land" class="mod_shop_inputfields" style="width:180px">
<option value="">Выберите страну </option>
{if $smarty.request.action=='register' && $smarty.request.sub == 'register'}
{assign var=sL value=$smarty.request.RLand}
{else}
{assign var=ssL value=$row->Land|default:$defland}
{/if}

{foreach from=$available_countries item=land}
{if in_array($land->LandCode|upper,$shippingCountries)}
<option value="{$land->LandCode|upper}" {if $sL==$land->LandCode|upper}selected{/if}>{$land->LandName}</option>
{/if}
{/foreach}
</select>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

</td>
</tr>
</table>


<br />
<div class="mod_shop_payment_headers">{#PleaseSelShippingMethod#}</div>


{if $showShipper}
{assign var=showPaymethods value=1}
<table width="100%" border="0" cellpadding="3" cellspacing="0">

  <tr>
  <td width="20" class="mod_shop_payment_shipppayrows">&nbsp;</td>
    <td width="250" class="mod_shop_payment_shipppayrows">{#Shippingmethod#}</td>
    <td width="150" class="mod_shop_payment_shipppayrows">{#Shippingcost#}</td>
    <td class="mod_shop_payment_shipppayrows">{#Description#}</td>
  </tr>
 {foreach from=$showShipper item=ss}
  <tr>
<td class="mod_shop_basket_row_price">
<input style="cursor:pointer" onclick="document.process.submit();" type="radio" name="ShipperId[]" value="{$ss->Id}" {if $smarty.session.ShipperId==$ss->Id}checked{/if}></td>
<td class="mod_shop_basket_row"><strong>{$ss->Name}</strong></td>
<td class="mod_shop_basket_row">{numformat val=$ss->cost} {$Currency} {if $ss->is_pauschal==1}(Pauschal){/if}</td>
<td class="mod_shop_basket_row">
<a {popup width=360 sticky=false text=$ss->Beschreibung|stripslashes} href="javascript:void(0);">{#OpenDescWindow#}</a>
</td>
</tr>
{/foreach}
</table>


{elseif $si_count < 1 && $smarty.post.Land != ''}
<div class="mod_shop_warn">
{#WarningNoMethodFor#}
</div>
&gt;&gt; <a href="index.php?module=shop&action=showbasket">{#LinkToBasket#}</a>
{else}
{#InfoShippingMethod#}
{/if}

<br />

<div class="mod_shop_payment_headers">{#InfoSelPayMethod#}</div>
{if !$PaymentMethods}
{assign var=NoVa value=1}
{#WarningFirstSMethod#} <br />
{/if}
{if $showPaymethods==1}
{if $PaymentMethods}
{assign var=ReadyToSell value=1}
<table width="100%" border="0" cellpadding="3" cellspacing="0">

  <tr>
  <td width="20" class="mod_shop_payment_shipppayrows">&nbsp;</td>
    <td width="250" class="mod_shop_payment_shipppayrows">{#PayMethod#}</td>
    <td width="150" class="mod_shop_payment_shipppayrows"> {#Cost#} </td>
    <td class="mod_shop_payment_shipppayrows">{#Description#}</td>
  </tr>
 {foreach from=$PaymentMethods item=ss}
  <tr>
<td class="mod_shop_basket_row_price">
{if $smarty.session.PaymentId==$ss->Id}
{assign var=FormSend value=1}
{/if}
<input style="cursor:pointer" onclick="document.process.submit();" type="radio" name="PaymentId[]" value="{$ss->Id}" {if $smarty.session.PaymentId==$ss->Id}checked{/if}></td>
<td class="mod_shop_basket_row"><strong>{$ss->Name|stripslashes}</strong></td>
<td class="mod_shop_basket_row">{numformat val=$ss->Kosten} {if $ss->KostenOperant=='%'}%{else}{$Currency}{/if}</td>
<td class="mod_shop_basket_row">

<a href="javascript:void(0);"onclick="popup('index.php?module=shop&action=PaymentInfo&pop=1&cp_theme={$cp_theme}&payid={$ss->Id}','comment','500','400','1')">{#OpenDescWindow#}</a>
 </td>
  </tr>
{/foreach}
</table>
{else}
{if $smarty.session.PaymentId!=''}
<div class="mod_shop_warn">
	 {#WarningNoPayMethodFor#}
</div>
{/if}
{/if}
{elseif $smarty.session.PaymentId=='' || $si_count < 1 || $smarty.request.ShipperId==''}
{if $NoVa != 1}
{#WarningFirstSMethod#}
{/if}
{/if}

<br />
<input class="absmiddle" type="image" src="{$shop_images}refresh.gif" />
{if $showPaymethods == '1' && $ReadyToSell == '1' && $smarty.session.PaymentId != '' && $FormSend==1}
<input type="hidden" name="zusammenfassung" id="zus" value="" />
<input onclick="document.getElementById('zus').value='1'" class="absmiddle" type="image" src="{$shop_images}sendorder.gif" />
{/if}

</form>
<br />
<br />
</div>


{*
	<b>{#WeightCa#} {$smarty.session.GewichtSumm} Kg</b>
	<br />
	<b>{#FinalSumm#} {numformat val=$Endsumme} {$Currency}</b>
	<br />
*}


<!-- FOOTER -->
<p>&nbsp;</p><p>&nbsp;</p>
{$FooterText}
<br />
  </td>
</tr>
</table>