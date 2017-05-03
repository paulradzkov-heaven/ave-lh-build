<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#ModName#}</h2></div>
    <div class="HeaderText">&nbsp;</div>
</div>
<br />
{include file="$source/shop_topnav.tpl"}
<h4>{#ProductManufacturers#}</h4>
<form action="index.php?do=modules&action=modedit&mod=shop&moduleaction=manufacturer&cp={$sess}&sub=save" method="post">
<table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
  <tr>
    <td width="1%" class="tableheader" align="center"><img src="{$tpl_dir}/images/icon_del.gif" alt="" /></td>
    <td width="160" class="tableheader">{#ProductUnitE#}</td>
    </tr>
  {foreach from=$Manufacturer item=ss}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
    <td width="1%" align="center">
      <input {popup sticky=false text=$config_vars.Delete} name="Del[{$ss->Id}]" type="checkbox" value="1" />
    </td>
    <td><input style="width:160px" type="text" name="Name[{$ss->Id}]" value="{$ss->Name|stripslashes}"></td>
    </tr>
  {/foreach}
 </table>
 <br />
 <input type="submit" class="button" value="{#ButtonSave#}">
</form>
<h4>{#ProductManufacturerNew#}</h4>
<form method="post" action="index.php?do=modules&action=modedit&mod=shop&moduleaction=manufacturer_new&cp={$sess}">
<table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
  <tr>
 <td class="first">
 <input style="width:160px" type="text" name="Name" value="{#SShipperName#}">
 <input type="submit" class="button" value="{#ButtonSave#}" />
 </td>
 </tr>
</table>
</form>