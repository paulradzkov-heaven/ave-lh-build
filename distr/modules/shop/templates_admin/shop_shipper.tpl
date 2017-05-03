<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#ModName#}</h2></div>
    <div class="HeaderText">&nbsp;</div>
</div>
<br />
{include file="$source/shop_topnav.tpl"}
<h4>{#SShipper#}</h4>
<form action="index.php?do=modules&action=modedit&mod=shop&moduleaction=shipping&cp={$sess}&sub=save" method="post">
<table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
  <tr>
    <td width="160" class="tableheader">{#SShipperName#}</td>
    <td class="tableheader">{#SShipperActive#} </td>
	<td class="tableheader">{#SShipperDoCost#}</td>
    <td class="tableheader">&nbsp;</td>
    </tr>
  {foreach from=$shopShipper item=ss}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
    <td><input style="width:160px" type="text" name="Name[{$ss->Id}]" value="{$ss->Name|stripslashes}"></td>
    <td>
     <input type="radio" name="Aktiv[{$ss->Id}]" value="1" {if $ss->Aktiv=='1'}checked{/if}> {#Yes#} 
    <input type="radio" name="Aktiv[{$ss->Id}]" value="0" {if $ss->Aktiv=='0'}checked{/if}> {#No#}	</td>
	<td>
	<input type="radio" name="KeineKosten[{$ss->Id}]" value="0" {if $ss->KeineKosten=='0'}checked{/if}> {#Yes#} 
    <input type="radio" name="KeineKosten[{$ss->Id}]" value="1" {if $ss->KeineKosten=='1'}checked{/if}> {#No#}	</td>
    <td>
	<a href="javascript:void(0);" onclick="cp_pop('index.php?do=modules&action=modedit&mod=shop&moduleaction=editshipper&cp={$sess}&pop=1&Id={$ss->Id}','750','600','1','shopshipper');">{#DokEdit#}</a> | 
	{if $ss->KeineKosten=='1'}{#EditShipperCost#}{else}<a href="javascript:void(0);" onclick="cp_pop('index.php?do=modules&action=modedit&mod=shop&moduleaction=editshipper_cost&cp={$sess}&pop=1&Id={$ss->Id}','750','600','1','shopshipper');">{#EditShipperCost#}</a>{/if}
	</td>
    </tr>
  {/foreach}
 </table>
 <br />
 <input type="submit" class="button" value="{#ButtonSave#}">
</form>