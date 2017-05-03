<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#ModName#}</h2></div>
    <div class="HeaderText">&nbsp;</div>
</div>
<br />
{include file="$source/shop_topnav.tpl"}
<h4>{#ShopSettings#}</h4>
<form action="index.php?do=modules&action=modedit&mod=shop&moduleaction=settings&cp={$sess}&sub=save" method="post">

<table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
  <tr>
    <td width="300" class="first">{#SisActive#}</td>
    <td class="second"><input name="Aktiv" type="checkbox" value="1" {if $row->Aktiv=='1'}checked{/if} /></td>
  </tr>

  <tr>
    <td width="300" class="first">Shop Keywords:</td>
    <td class="second"><input name="ShopKeywords" type="text" id="ShopKeywords" size="40" value="{$row->ShopKeywords}" style="width: 500px" /></td>
  </tr>

  <tr>
    <td width="300" class="first">Shop Description:</td>
    <td class="second"><input name="ShopDescription" type="text" id="ShopDescription" size="40" style="width: 500px" value="{$row->ShopDescription}" maxlength="170" /></td>
  </tr>

  <tr>
    <td width="300" class="first">{#TemplateArticles#}</td>
    <td class="second">
	<select style="width:200px" name="TemplateArtikel">
	<option value="shop_items.tpl" {if $row->TemplateArtikel=='shop_items.tpl'}selected="selected"{/if}>{#TemplateStandart#}</option>
	<option value="shop_items_simple.tpl" {if $row->TemplateArtikel=='shop_items_simple.tpl'}selected="selected"{/if}>{#TemplateNItems#}</option>
	</select>
	</td>
  </tr>


  <tr>
    <td width="300" class="first">{#Scountry#}</td>
    <td class="second">
      <input name="ShopLand" type="text" id="ShopLand" value="{$row->ShopLand|upper}" size="2" maxlength="2" />
    </td>
  </tr>

  <tr>
    <td width="300" class="first">{#Scurrency#} </td>
    <td class="second">
      <input name="Waehrung" type="text" id="Waehrung" value="{$row->Waehrung}" size="10" maxlength="10" />
    </td>
  </tr>

  <tr>
    <td width="300" class="first">{#ScurencySymbol#} </td>
    <td class="second">
      <input name="WaehrungSymbol" type="text" value="{$row->WaehrungSymbol}" size="10" maxlength="5" />
    </td>
  </tr>

  <tr>
    <td width="300" class="first">{#AltCurrencyUse#}</td>
    <td class="second"><input type="radio" value="1" name="ZeigeWaehrung2" {if $row->ZeigeWaehrung2==1}checked{/if}> {#Yes#}
	<input type="radio" value="0" name="ZeigeWaehrung2" {if $row->ZeigeWaehrung2==0}checked{/if}> {#No#}	</td>
  </tr>
  <tr>
    <td width="300" class="first">{#AltCurrency#}</td>
    <td class="second">
      <input name="Waehrung2" type="text"  value="{$row->Waehrung2}" size="10" maxlength="10" />
    </td>
  </tr>

  <tr>
    <td width="300" class="first">{#AltCurrencySymbol#}</td>
    <td class="second">
      <input name="WaehrungSymbol2" type="text" value="{$row->WaehrungSymbol2}" size="10" maxlength="5" />
    </td>
  </tr>
  <tr>
    <td width="300" class="first">{#AltCMulti#}</td>
    <td class="second">
      <input name="Waehrung2Multi" type="text" value="{$row->Waehrung2Multi}" size="10" maxlength="10" />
    </td>
  </tr>
  <tr>
    <td width="300" class="first">{#SitemsEPage#}</td>
    <td class="second">
	<input name="ArtikelMax" type="text" id="ArtikelMax" value="{$row->ArtikelMax}" size="10" maxlength="5" />
	</td>
  </tr>

  <tr>
    <td width="300" class="first">{#SStopIfNull#}</td>
    <td class="second">
	<input type="radio" value="1" name="KaufLagerNull" {if $row->KaufLagerNull==1}checked{/if}> {#Yes#}
	<input type="radio" value="0" name="KaufLagerNull" {if $row->KaufLagerNull==0}checked{/if}> {#No#} </td>
  </tr>

  <tr>
    <td width="300" class="first">
	{#SScountries#}<br />
	<small>{#SSelectCountries#}</small>	</td>
    <td class="second">

<select  name="VersandLaender[]"  multiple="multiple" size="6" style="width:200px">
{foreach from=$laender item=g}
{assign var='sel' value=''}
{if $g->LandCode}
  {if (in_array($g->LandCode,$row->VersandLaender)) }
    {assign var='sel' value='selected'}
  {/if}
{/if}
<option value="{$g->LandCode}" {$sel}>{$g->LandName|escape:html}</option>
{/foreach}
</select>
</td>
  </tr>
  <tr>
    <td width="300" class="first">{#SNoShippingCost#}</td>
    <td class="second">
      <input type="radio" value="1" name="VersFrei" {if $row->VersFrei==1}checked{/if}>{#Yes#}
      <input type="radio" value="0" name="VersFrei" {if $row->VersFrei==0}checked{/if}>{#No#} </td>
  </tr>
  <tr>
    <td width="300" class="first">{#SNoShippingCostFrom#}</td>
    <td class="second"><input name="VersFreiBetrag" type="text" value="{$row->VersFreiBetrag}" size="10" maxlength="10" /></td>
  </tr>

  <tr>
    <td class="first">{#WidthThumbs#}</td>
    <td class="second">
      <input name="Vorschaubilder" type="text" value="{$row->Vorschaubilder}" size="10" maxlength="3" />
    </td>
  </tr>
  <tr>
    <td class="first">{#WidthTsThumbs#}</td>
    <td class="second">
      <input name="Topsellerbilder" type="text" value="{$row->Topsellerbilder}" size="10" maxlength="3" />
    </td>
  </tr>
  <tr>
    <td class="first">{#OrderGuests#}</td>
    <td class="second">
	<input type="radio" value="1" name="GastBestellung" {if $row->GastBestellung==1}checked{/if}>{#Yes#}
	<input type="radio" value="0" name="GastBestellung" {if $row->GastBestellung==0}checked{/if}>{#No#}	</td>
  </tr>
  <tr>
    <td width="300" class="first">{#SUseCoupons#}</td>
    <td class="second">
	<input type="radio" value="1" name="GutscheinCodes" {if $row->GutscheinCodes==1}checked{/if}>{#Yes#}
	<input type="radio" value="0" name="GutscheinCodes" {if $row->GutscheinCodes==0}checked{/if}>{#No#} </td>
  </tr>
  <tr>
    <td width="300" class="first">{#ShopShowUnits#}</td>
    <td class="second">
	<input type="radio" value="1" name="ZeigeEinheit" {if $row->ZeigeEinheit==1}checked{/if}>{#Yes#}
	<input type="radio" value="0" name="ZeigeEinheit" {if $row->ZeigeEinheit==0}checked{/if}>{#No#}	</td>
  </tr>
   <tr>
    <td width="300" class="first">{#ShopZeigeNetto#}</td>
    <td class="second">
	<input type="radio" value="1" name="ZeigeNetto" {if $row->ZeigeNetto==1}checked{/if}>{#Yes#}
	<input type="radio" value="0" name="ZeigeNetto" {if $row->ZeigeNetto==0}checked{/if}>{#No#}	</td>
  </tr>

   <tr>
     <td width="300" class="first">{#SSShowCategsStart#}</td>
     <td class="second">
	 <input type="radio" value="1" name="KategorienStart" {if $row->KategorienStart==1}checked{/if}>{#Yes#}
	<input type="radio" value="0" name="KategorienStart" {if $row->KategorienStart==0}checked{/if}>{#No#}	 </td>
   </tr>
   <tr>
     <td width="300" class="first">{#SSShowCategsSons#}</td>
     <td class="second">
	 <input type="radio" value="1" name="KategorienSons" {if $row->KategorienSons==1}checked{/if}>{#Yes#}
	<input type="radio" value="0" name="KategorienSons" {if $row->KategorienSons==0}checked{/if}>{#No#}	 </td>
   </tr>
   <tr>
     <td width="300" class="first">{#SSShowRandomOffer#}</td>
     <td class="second">
	 <input type="radio" value="1" name="ZufallsAngebot" {if $row->ZufallsAngebot==1}checked{/if}>{#Yes#}
	<input type="radio" value="0" name="ZufallsAngebot" {if $row->ZufallsAngebot==0}checked{/if}>{#No#}	 </td>
   </tr>
    <tr>
     <td width="300" class="first">{#SSShowRandomOfferCat#}</td>
     <td class="second">
	 <input type="radio" value="1" name="ZufallsAngebotKat" {if $row->ZufallsAngebotKat==1}checked{/if}>{#Yes#}
	<input type="radio" value="0" name="ZufallsAngebotKat" {if $row->ZufallsAngebotKat==0}checked{/if}>{#No#}	 </td>
   </tr>
    <tr>
      <td width="300" class="first">{#CanOrderHere#}</td>
      <td class="second">
	  <input type="radio" value="1" name="BestUebersicht" {if $row->BestUebersicht==1}checked{/if}>{#Yes#}
	<input type="radio" value="0" name="BestUebersicht" {if $row->BestUebersicht==0}checked{/if}>{#No#}	</td>
    </tr>
    <tr>
      <td width="300" class="first">{#Wishlist#}</td>
      <td class="second">
	  <input type="radio" value="1" name="Merkliste" {if $row->Merkliste==1}checked{/if}>{#Yes#}
	<input type="radio" value="0" name="Merkliste" {if $row->Merkliste==0}checked{/if}>{#No#}	</td>
    </tr>
	 <tr>
      <td width="300" class="first">{#ShowTopSeller#}</td>
      <td class="second">
	  <input type="radio" value="1" name="Topseller" {if $row->Topseller==1}checked{/if}>{#Yes#}
	<input type="radio" value="0" name="Topseller" {if $row->Topseller==0}checked{/if}>{#No#}	</td>
    </tr>

	<tr>
      <td width="300" class="first">{#AllowComments#}</td>
      <td class="second">
	  <input type="radio" value="1" name="Kommentare" {if $row->Kommentare==1}checked{/if}>{#Yes#}
	<input type="radio" value="0" name="Kommentare" {if $row->Kommentare==0}checked{/if}>{#No#}	</td>
    </tr>
	{*
	<tr>
      <td width="300" class="first">{#AllowCommentsGuest#}</td>
      <td class="second">
	  <input type="radio" value="1" name="KommentareGast" {if $row->KommentareGast==1}checked{/if}>{#Yes#}
	<input type="radio" value="0" name="KommentareGast" {if $row->KommentareGast==0}checked{/if}>{#No#}	</td>
    </tr>
	*}
</table>
<br />
<input accesskey="s" type="submit" value="{#ButtonSave#}" class="button" />
</form>