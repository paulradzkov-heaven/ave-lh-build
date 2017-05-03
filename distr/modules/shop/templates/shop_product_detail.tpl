<script language="javascript" type="text/javascript">
function tab_switch(id)
{ldelim}
	if(id==1)
	{ldelim}
	document.getElementById('text').style.display = '';
	document.getElementById('tab_images').style.display = 'none';
	document.getElementById('Frei_Text_1').style.display = 'none';
	document.getElementById('Frei_Text_2').style.display = 'none';
	document.getElementById('Frei_Text_3').style.display = 'none';
	document.getElementById('Frei_Text_4').style.display = 'none';
	{rdelim}
	if(id=='tab_images')
	{ldelim}
	document.getElementById('text').style.display = 'none';
	document.getElementById('tab_images').style.display = '';
	document.getElementById('Frei_Text_1').style.display = 'none';
	document.getElementById('Frei_Text_2').style.display = 'none';
	document.getElementById('Frei_Text_3').style.display = 'none';
	document.getElementById('Frei_Text_4').style.display = 'none';
	{rdelim}
	if(id==2)
	{ldelim}
	document.getElementById('text').style.display = 'none';
	document.getElementById('tab_images').style.display = 'none';
	document.getElementById('Frei_Text_1').style.display = '';
	document.getElementById('Frei_Text_2').style.display = 'none';
	document.getElementById('Frei_Text_3').style.display = 'none';
	document.getElementById('Frei_Text_4').style.display = 'none';
	{rdelim}
	if(id==3)
	{ldelim}
	document.getElementById('text').style.display = 'none';
	document.getElementById('tab_images').style.display = 'none';
	document.getElementById('Frei_Text_1').style.display = 'none';
	document.getElementById('Frei_Text_2').style.display = '';
	document.getElementById('Frei_Text_3').style.display = 'none';
	document.getElementById('Frei_Text_4').style.display = 'none';
	{rdelim}
	if(id==4)
	{ldelim}
	document.getElementById('text').style.display = 'none';
	document.getElementById('tab_images').style.display = 'none';
	document.getElementById('Frei_Text_1').style.display = 'none';
	document.getElementById('Frei_Text_2').style.display = 'none';
	document.getElementById('Frei_Text_3').style.display = '';
	document.getElementById('Frei_Text_4').style.display = 'none';
	{rdelim}
	if(id==5)
	{ldelim}
	document.getElementById('text').style.display = 'none';
	document.getElementById('tab_images').style.display = 'none';
	document.getElementById('Frei_Text_1').style.display = 'none';
	document.getElementById('Frei_Text_2').style.display = 'none';
	document.getElementById('Frei_Text_3').style.display = 'none';
	document.getElementById('Frei_Text_4').style.display = '';
	{rdelim}
{rdelim}
</script>

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

<!-- SHOP - ITEMS -->
{strip}

{if !$row}
<p>
{#ErrorNoProduct#}</p>
{else}

{include file="$mod_dir/shop/templates/shop_topnav.tpl"}
<div class="mod_shop_header"><h2>{$row->ArtName|stripslashes|escape:html}</h2>
{if $row->Hersteller_Name != ''}
<br />
{#Manufacturer#} <a href="{$row->Hersteller_Link}">{$row->Hersteller_Name}</a>
<br />
{/if}
<!-- DELIVERY TIME -->
{if $row->Versandfertig}
<div class="mod_shop_timetillshipping">{$row->Versandfertig}</div>
{/if}</div>


<div class="mod_shop_tabs">
<div class="mod_shop_itabs" onclick="tab_switch('1')"><span class="mod_shop_tabs_text">{#Description#}</span></div>


{if $row->MultiImages}
<div class="mod_shop_itabs" onclick="tab_switch('tab_images')"><span class="mod_shop_tabs_text">{#MoreImages#}</span></div>
{/if}

{if $row->Frei_Titel_1!=''}
<div onclick="tab_switch('2')" class="mod_shop_itabs"><span class="mod_shop_tabs_text">{$row->Frei_Titel_1}</span></div>
{/if}

{if $row->Frei_Titel_2!=''}
<div class="mod_shop_itabs" onclick="tab_switch('3')"><span class="mod_shop_tabs_text">{$row->Frei_Titel_2}</span></div>
{/if}

{if $row->Frei_Titel_3!=''}
<div class="mod_shop_itabs" onclick="tab_switch('4')"><span class="mod_shop_tabs_text">{$row->Frei_Titel_3}</span></div>
{/if}

{if $row->Frei_Titel_4!=''}
<div class="mod_shop_itabs" onclick="tab_switch('5')"><span class="mod_shop_tabs_text">{$row->Frei_Titel_4}</span></div>
{/if}

<div style="clear:both"></div>
</div>
{if $row->Lager < 1 && $KaufLagerNull == '0'}
{else}
<form method="post" action="{$row->AddToLink}">
{/if}

<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>

{if $row->BildFehler!=1}
<td valign="top">
{* <a href="javascript:void(0);" onclick="shop_image_pop('<h3>{$row->ArtName|truncate:175|stripslashes|escape:html}</h3>  <img style=\\'cursor:pointer\\' onclick=\\'window.close();\\' src=\\'modules/shop/uploads/{$row->Bild}\\'>','{$row->ArtName|truncate:45|stripslashes|escape:html}')"> *}
<a class="reflect" rel="lightbox[MultiImages]" href="modules/shop/uploads/{$row->Bild}" title="{$row->ArtName|truncate:100|stripslashes|escape:html}">
<img align="left"  class="mod_shop_image" src="{if $row->ImgSrc=='FALSE'}modules/shop/thumb.php?file={$row->Bild}&amp;type={$row->Bild_Typ}&amp;x_width={$WidthThumb}{else}{$row->ImgSrc}{/if}" border="0" alt="{$row->ArtName|truncate:175|stripslashes|escape:html}" />
</a>
</td>
{/if}

<td valign="top" class="mod_shop_text">
<div id="text" style="">{$row->TextLang|stripslashes}</div>


<div id="tab_images" {if $smarty.request.print!=1}style="display:none"{/if}>
<strong>{#MoreImages#}</strong>
<div style="clear:both"></div>
<br />
{if $row->MultiImages}

	{foreach from=$row->MultiImages item=mi}
	<div style="float:left;margin:2px">
	{* <a href="javascript:void(0);" onclick="window.open('modules/shop/uploads/{$mi->Bild}','x','width=400,height=400,scrollbars=1,resizable=1')"> *}
  <a rel="lightbox[MultiImages]" href="modules/shop/uploads/{$mi->Bild}" title="{$row->ArtName|truncate:100|stripslashes|escape:html}">
	<img class="mod_shop_image" src="modules/shop/thumb.php?file={$mi->Bild}&amp;type={$mi->endung}&amp;x_width={$WidthThumb}" alt="{$row->ArtName|truncate:175|stripslashes|escape:html}" border="0" /></a>	</div>
	{/foreach}
	<div style="clear:both"></div>

{/if}
</div>

<div id="Frei_Text_1" {if $smarty.request.print!=1}style="display:none"{/if}>
<strong>{$row->Frei_Titel_1}</strong><br /><br />
{$row->Frei_Text_1|stripslashes}</div>

<div id="Frei_Text_2" {if $smarty.request.print!=1}style="display:none"{/if}>
<strong>{$row->Frei_Titel_2}</strong><br /><br />
{$row->Frei_Text_2|stripslashes}</div>

<div id="Frei_Text_3" {if $smarty.request.print!=1}style="display:none"{/if}>
<strong>{$row->Frei_Titel_3}</strong><br /><br />
{$row->Frei_Text_3|stripslashes}</div>

<div id="Frei_Text_4" {if $smarty.request.print!=1}style="display:none"{/if}>
<strong>{$row->Frei_Titel_4}</strong><br /><br />
{$row->Frei_Text_4|stripslashes}</div>

<br />
{#ArtNr#} {$row->ArtNr}
<br />
{#Release#} {$row->Erschienen|date_format:$config_vars.DateFormatRelease}
<br />
<br />

<!-- PRICE -->
{if $row->PreisDiff > 0}
<span class="mod_shop_ust">
{* {#PriceListS#}&nbsp;*}
<strong style="text-decoration:line-through">{numformat val=$row->PreisListe} {$Currency}</strong> 
<br />
<strong>{#PriceYouSave#}</strong> {numformat val=$row->PreisDiff} {$Currency} ({math equation="p / (x / 100)" x=$row->PreisListe y=$row->Preis p=$row->PreisDiff format="%.0f"} %)
</span>
{/if}
<div class="mod_shop_price_big">{#OurPrice#} {numformat val=$row->Preis} {$Currency}</div> 
{if $row->PreisW2 && $ZeigeWaehrung2=='1'}({numformat val=$row->PreisW2} {$Currency2}){/if}
<br />
{if $row->Einheit_Preis}
<span class="mod_shop_ust">{$row->Einheit|replace:'.00':''} {$row->Einheit_Art} {#UnitIncluded#} {$row->Einheit_Art_S}: {numformat val=$row->Einheit_Preis} {$Currency}</span>
<br />
{/if}
{if $row->ZeigeNetto==1  && $row->Preis_USt>0 && $row->NettoAnzeigen==1}
<span class="mod_shop_ust">{#IncludeMwSt#} {numformat val=$row->Preis_USt} {$Currency} {#InVatOnce#} / {numformat val=$row->Preis_Netto_Out} {$Currency} netto</span>
<br />
{/if}




<!-- PRICE - PROGRESSIVE RATE -->
{if $StPrices}
<br />
<div class="mod_shop_stprices_vars">
<strong>{#StPrices#}</strong>
<br />
<table width="100%" cellpadding="1" cellspacing="0" border="0">

  <tr>
    <td>{#AnzPr#}</td>
    <td>{#StPrice#}</td>
  </tr>
  {foreach from=$StPrices name=staffel item=sp}
  <tr>
<td>{$sp->StkVon} - {if !$smarty.foreach.staffel.last}{$sp->StkBis}{else}?{/if}</td>
<td>{numformat val=$sp->Preis} {$Currency}</td>
</tr>
{/foreach}
</table>
</div>

{/if}
<!-- PRICE - PROGRESSIVE RATE END -->


<!-- VARIATIONS -->
{if $Variants}
<br />
<div class="mod_shop_stprices_vars">
<strong>{#ProductVars#}</strong>
<br />
<br />
<table cellpadding="1" cellspacing="0" border="0">
{foreach from=$Variants item=vars}
<tr>
<td>{$vars->VarName}&nbsp;&nbsp;</td>
<td>
<select class="mod_shop_inputfields" style="width:180px" name="product_vars[]">
<option value="0"></option>
{foreach from=$vars->VarItems item=vi}
<option value="{$vi->Id}">{$vi->Name} ({$vi->Operant}{numformat val=$vi->Wert} {$Currency})</option>
{/foreach}
</select>
<input {popup sticky=false text=$vars->Beschreibung|default:'-'} type="button" class="button" value="?" style="margin-left:2px" />
</td>
</tr>
{/foreach}
</table>
</div>
<br />
{/if}
<!-- VARIATIONS END -->

{if $row->Lager < 1 && $KaufLagerNull == '1'}
<div class="mod_shop_preorder_warn">{#PreOrderMsg#}</div>
{elseif $row->Lager < 1 && $KaufLagerNull == '0'}
<div class="mod_shop_preorder_warn">{#PreOrderMsgF#}</div>
{/if}</td>
</tr>
<tr>
  {if $row->BildFehler!=1}<td valign="top">&nbsp;</td>{/if}
  <td valign="top" class="mod_shop_addtotd">
  <table border="0" cellspacing="0" cellpadding="1">
    <tr>
      <td>
	 
        {#AnzPr#}: &nbsp;
		<input class="mod_shop_inputfields" name="amount" type="text" size="4" maxlength="2" value="1" {if $row->Lager < 1 && $KaufLagerNull != '1'}disabled="disabled"{/if} />
		<input name="product_id" type="hidden" value="{$row->Id}" />
      </td>
      <td>
        <input value="{#ButtonToBasket#}"  class="button" {if $InBasket==1}onclick="alert('{#AllreadyInBasket#}'); return false;"{/if} name="insertinto" type="submit" />
     	{if $WishListActive==1}
		<input value="{#ButtonToWishlist#}" class="button" style="margin-left:2px" onclick="{if $smarty.session.cp_benutzerid<1}alert('{#ToWishlistError#}');return false;{else}document.getElementById('to_wishlist_{$row->Id}').value='1';{/if}" name="insertwishlist" type="submit" />
	   <input type="hidden" name="wishlist_{$row->Id}" id="to_wishlist_{$row->Id}"  value="" />
	{/if}
	  </td>
    </tr>
  </table>
  
{if $row->MultiImages}
{*
  <br />
	{foreach from=$row->MultiImages item=mi}
	
	<div style="float:left;margin-right:1px">
	<a href="javascript:void(0);" onclick="window.open('modules/shop/uploads/{$mi->Bild}','x','width=400,height=400,scrollbars=1,resizable=1')"><img class="mod_shop_image " src="modules/shop/thumb.php?file={$mi->Bild}&type={$mi->Endung}" alt="" border="0" /></a>	</div>
	{/foreach}
	<div style="clear:both"></div>
*}
{/if}</td>
  </tr>
</table>
{if $row->Lager < 1 && $KaufLagerNull == '0'}
{else}
</form>
{/if}



<br />

<!-- SIMILAR PRODUCTS -->
{if $equalProducts}
{include file="$mod_dir/shop/templates/shop_equal_products.tpl"}
{/if}
<!-- SIMILAR PRODUCTS END --> 

<br />
{if $AllowComments}
<h3>{#ArticleComments#}</h3>
<br />
<br />
<strong>{#CommentsVotesCut#}</strong> {if $rez->Proz<1}{#CommentsNull#}{else}<img class="absmiddle" src="{$shop_images}{$rez->Proz}.gif" alt="" />{/if}<br />
<strong>{#CommentsCount#}</strong> {$rez->Anz}<br />
{if $CanComment==1}
<img class="absmiddle" src="{$shop_images}arrow.gif" alt="" /> <a href="#rezNew">{#CommentWrite#}</a>
{/if}
<hr noshade="noshade" size="1" />
<br />
{foreach from=$Comments item=c}
<strong>{$c->Titel|stripslashes}</strong> {$c->Datum|date_format:$config_vars.DateFormatRelease}<br />
{#CommentAuthor#} <em>{$c->Autor}</em><br />
{$c->Kommentar|stripslashes}<br >
<hr noshade="noshade" size="1" />
{/foreach}
<br />
{/if}

{if $CanComment==1}
<a name="rezNew"></a><strong>{#CommentWrite#}</strong>
<br />

<form onsubmit="return confirm_comment();" method="post" action="index.php?sendcomment=1&amp;module=shop&amp;action=product_detail&amp;product_id={$smarty.request.product_id}&amp;categ={$smarty.request.categ}&amp;navop={$smarty.request.navop}">

<fieldset>
<legend><label for="l_ATitel">{#CommentTitle#}</label></legend>
<input style="width:97%" id="l_ATitel" type="text" name="ATitel">
</fieldset>


<fieldset>
<legend><label for="l_AKommentar">{#CommentC#}</label></legend>
<textarea style="width:97%;height:100px"  id="l_AKommentar" name="AKommentar"></textarea>
</fieldset>

<fieldset>
<legend><label for="l_AWertung">{#CommentV#}</label></legend>
{#CommentVoteInf#}
<br />
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><img class="absmiddle" src="{$shop_images}1.gif" alt="" /></td>
    <td align="center"><img class="absmiddle" src="{$shop_images}2.gif" alt="" /></td>
    <td align="center"><img class="absmiddle" src="{$shop_images}3.gif" alt="" /></td>
    <td align="center"><img class="absmiddle" src="{$shop_images}4.gif" alt="" /></td>
    <td align="center"><img class="absmiddle" src="{$shop_images}5.gif" alt="" /></td>
    </tr>
  <tr>
    <td align="center">
      <input name="AWertung" type="radio" value="1" />
    </td>
    <td align="center">
      <input name="AWertung" type="radio" value="2" />
    </td>
    <td align="center">
      <input name="AWertung" type="radio" value="3" checked="checked" />
    </td>
    <td align="center">
      <input name="AWertung" type="radio" value="4" />
    </td>
    <td align="center">
      <input name="AWertung" type="radio" value="5" />
    </td>
    </tr>
</table>
</fieldset>

<br />
<input type="submit" class="button" value="{#ArticleSendComment#}">
</form>
{/if}
{/if}

{/strip}

<script language="javascript">
function confirm_comment()
{ldelim}
	if(document.getElementById('l_ATitel').value == '')
	{ldelim}
		alert('{#ConfirmEnoTitle#}');
		document.getElementById('l_ATitel').focus();
		return false;
	{rdelim}
	
	if(document.getElementById('l_AKommentar').value == '')
	{ldelim}
		alert('{#ConfirmEnoText#}');
		document.getElementById('l_AKommentar').focus();
		return false;
	{rdelim}
	
	if(confirm('{#ConfirmComment#}')) return true;
	return false;
{rdelim}
</script>

<!-- FOOTER -->
<p>&nbsp;</p>
{$FooterText}
<br />
</td>
    <td valign="top" class="mod_shop_menue">
      <!-- BASKET -->
{$Basket}
<!-- SHOP - SEARCH -->
{$Search}
<!-- TOPSELLER -->
{$Topseller} </td>
  </tr>
</table>