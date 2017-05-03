{strip}
{if $TopsellerActive==1}
<div class="mod_shop_myordersbox"> <strong>{#Topseller#}</strong> <br />
  <br />
  <table width="100%">
  {foreach from=$TopSeller item=ts}
  <tr>
  {assign var="num" value=$num+1}
<td>
 <a href="{$ts->Detaillink}">{$ts->Img}</a> {* {if $num<10}0{/if}{$num}.  *}
</td>
  <td align="center">
  <a href="{$ts->Detaillink}" class="topseller">{$ts->ArtName|truncate:55}</a>
  </td>
  </tr>
  <tr>
  <td colspan="2">
  <div class="mod_shop_toptopseller_bottom">
 {numformat val=$ts->Preis} {$Currency}
 {if $ts->PreisW2 && $ZeigeWaehrung2=='1'} = <span class="mod_shop_ust">{numformat val=$ts->PreisW2} {$Currency2}</span>{/if}
 </div>
 <br />
  </td>
  </tr>
  {/foreach}
  </table>
  </div>
{/if}
{/strip}