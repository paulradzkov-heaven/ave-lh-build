{if !$ShopArticles}{#ErrorNoProductsHere#}{else}
{strip}
{* Geben Sie hier an, in wie vielen Spalten die Artikel nebeneinander angezeigt werden sollen. Empfohlen: 1 *}
{assign var="cols" value=1}

{* Seiten - Navigation *}
{if $page_nav}
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td>{#NavPage#} {$smarty.request.page|default:'1'} {#NavPageFrom#} {$PageNumbers} {#NavPages#}</td>
<td align="right">{$page_nav}</td>
</tr>
</table>
<br />
{/if}


{foreach from=$ShopArticles item=i}

{assign var="newtr" value=$newtr+1}
<div class="mod_shop_header"><h2><a href="{$i->Detaillink}">{$i->ArtName|truncate:45|stripslashes|escape:html}</a></h2>

{if $i->Hersteller_Name != ''}
<br />
{#Manufacturer#} <a href="{$i->Hersteller_Link}">{$i->Hersteller_Name}</a>
{/if}

{if $i->Prozwertung > 0}
<br />{#CommentsVotesCut#} <img class="absmiddle" src="{$shop_images}{$i->Prozwertung}.gif" alt="" /><br />
{/if}
</div>

<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>

<td valign="top">
{if $i->BildFehler==1}
<img align="left" src="{$shop_images}no_productimage.gif" alt="" />
{else}
{* <a href="javascript:void(0);" onclick="shop_image_pop('<h3>{$i->ArtName|truncate:175|stripslashes|escape:html}</h3> <img style=\\'cursor:pointer\\' onclick=\\'window.close();\\' src=\\'modules/shop/uploads/{$i->Bild}\\'>','{$i->ArtName|truncate:45|stripslashes|escape:html}')"> *}
<a rel="lightbox" href="modules/shop/uploads/{$i->Bild}" title="{$i->ArtName|truncate:100|stripslashes|escape:html}">
<img align="left"  class="mod_shop_image" src="{if $i->ImgSrc=='FALSE'}modules/shop/thumb.php?file={$i->Bild}&amp;type={$i->Bild_Typ}&amp;x_width={$WidthThumb}{else}{$i->ImgSrc}{/if}" border="0" alt="{$i->ArtName|truncate:175|stripslashes|escape:html}" /></a>
{/if}
</td>

<td valign="top" class="mod_shop_text">
{$i->TextKurz|stripslashes|truncate:250}
<br /><br />
{#ArtNr#} {$i->ArtNr}
<br />
{*{#Release#} {$i->Erschienen|date_format:$config_vars.DateFormatRelease}*}
<br /><br />


{if $i->Lager < 1 && $KaufLagerNull == '1'}
<div class="mod_shop_preorder_warn">{#PreOrderMsg#}</div>
{elseif $i->Lager < 1 && $KaufLagerNull == '0'}
<div class="mod_shop_preorder_warn">{#PreOrderMsgF#}</div>
{/if}
<br />

<!-- UNITS -->
{if $i->Einheit_Preis}
<span class="mod_shop_ust">{$i->Einheit|replace:'.00':''} {$i->Einheit_Art} {#UnitIncluded#} {$i->Einheit_Art_S}: {numformat val=$i->Einheit_Preis} {$Currency}</span>
<br />
{/if}

{if $i->ZeigeNetto==1 && $i->Preis_USt>0}
<span class="mod_shop_ust">{#IncludeMwSt#} {numformat val=$i->Preis_Netto_Out} {$Currency} <!-- {#Netto#} -->+ {#InVatOnce#} {numformat val=$i->Preis_USt} {$Currency}
<br />
{if $i->PreisDiff>0}
<strong>{#PriceYouSave#}</strong> {numformat val=$i->PreisDiff} {$Currency} ({math equation="p / (x / 100)" x=$i->PreisListe y=$i->Preis p=$i->PreisDiff format="%.0f"} %)
{/if}
</span>
<br />
{/if}

<br />
{if $CanOrderHere==1}
<img class="absmiddle" src="{$shop_images}arrow.gif" alt="" /> <a href="{$i->Detaillink}">{#ShowDetails#}</a>
<br />
{/if}

</td>

<td valign="top" class="mod_shop_insertrow">
 
<!-- PRICE - DIFFERENCE -->
<div style="text-align:center">
{if $i->PreisDiff > 0}
<span class="mod_shop_ust">
{#PriceListS#} <strong style="text-decoration:line-through">{$i->PreisListe} {$Currency}</strong> 
</span>
{/if}

<!-- PRICE -->
<div class="mod_shop_price_big_shopstar">{numformat val=$i->Preis} {$Currency}</div>

<!-- PRICE ALT. CURRENCY -->
{if $i->PreisW2 && $ZeigeWaehrung2=='1'}<span class="mod_shop_ust">{numformat val=$i->PreisW2} {$Currency2}</span>{/if}
</div>
 <br />
 
 
{if $CanOrderHere==1}
{if $i->Lager < 1 && $KaufLagerNull == '0'}
{else}
<form method="post" action="{$i->AddToLink}">
{/if}
  <table border="0" cellspacing="0" cellpadding="1" align="center">
    <tr>
      <td>
        <div align="center">
		<input class="mod_shop_inputfields" name="amount" type="text" size="4" maxlength="2" value="1" {if $i->Lager < 1 && $KaufLagerNull != '1'}disabled="disabled"{/if} />
		<input name="product_id" type="hidden" value="{$i->Id}" />
		</div>
      <br />
	  <input {if $i->InBasket==1}onclick="alert('{#AllreadyInBasket#}'); return false;"{/if} {popup sticky=false text=$config_vars.AddToBasket} class="button" style="width:90px;margin-bottom:2px"  type="submit" value="{#ButtonToBasket#}" />
	 {if $WishListActive==1}
	   <br />
	   <input onclick="{if $smarty.session.cp_benutzerid<1}alert('{#ToWishlistError#}');return false;{else}document.getElementById('to_wishlist_{$i->Id}').value='1';{/if}" {popup sticky=false text=$config_vars.ToWishlist} class="button" style="width:90px"  type="submit" value="{#ButtonToWishlist#}" />
	   <input type="hidden" name="wishlist_{$i->Id}" id="to_wishlist_{$i->Id}"  value="" />
	{/if}
	
	  </td>
    </tr>
  </table>
  {if $i->Lager < 1 && $KaufLagerNull == '0'}
{else}
</form>
{/if}
{else}
&gt;&gt; <a href="{$i->Detaillink}">{#ShowDetails#}</a>
{/if}

  
<!-- PREIS - PROGRESSIVE RATE -->
{if $i->StPrices}
<br />
<div class="mod_shop_stprices">
<strong>{#StPricesS#}</strong>
<table width="100%" cellpadding="1" cellspacing="0" border="0">
  {foreach from=$i->StPrices name=staffel item=sp}
  <tr>
<td>{$sp->StkVon}-{if !$smarty.foreach.staffel.last}{$sp->StkBis}{else}?{/if}</td>
<td nowrap="nowrap" align="right">{numformat val=$sp->Preis} {$Currency}</td>
</tr>
{/foreach}
</table>
</div>
<br />
{/if}
<!-- PREIS - PROGRESSIVE RATE END -->

</td>

</tr>
</table>
<br /><br />

{/foreach}



{* Seiten - Navigation *}
{if $page_nav}
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td>{#NavPage#} {$smarty.request.page|default:'1'} {#NavPageFrom#} {$PageNumbers} {#NavPages#}</td>
<td align="right">{$page_nav}</td>
</tr>
</table>
<br />
{/if}
{/strip}
{/if}