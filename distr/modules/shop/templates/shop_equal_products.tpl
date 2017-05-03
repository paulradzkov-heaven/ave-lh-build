{strip}
<br /><br />
<strong>{#EqualProductsInterest#}</strong>
<br />
<br />
{foreach from=$equalProducts item=i}
<div class="mod_shop_header"><h3><a href="{$i->Detaillink}">{$i->ArtName|truncate:55|stripslashes|escape:html}</a></h3></div>
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
<td valign="top">
{if $i->BildFehler==1}
<img src="{$shop_images}no_productimage.gif" alt="" />
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
<div class="mod_shop_price_big">{numformat val=$i->Preis} {$Currency}</div> 
{if $i->PreisW2 && $ZeigeWaehrung2=='1'} {numformat val=$i->PreisW2} {$Currency2}{/if}
<br />
</td>
</tr>
</table>
<br />
{/foreach}
{/strip}