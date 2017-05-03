<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#ModName#}</h2></div>
    <div class="HeaderText">&nbsp;</div>
</div>
<br />
{include file="$source/shop_topnav.tpl"}
<h4>{#EditHelpPages#}</h4>
<form action="index.php?do=modules&action=modedit&mod=shop&moduleaction=helppages&cp={$sess}&sub=save" method="post">

<table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
  <tr>
    <td width="300" valign="top" class="first"><strong>{#ShopFooter#}</strong></td>
    <td class="second">{$row->ShopFuss}</td>
  </tr>
  <tr>
    <td width="300" valign="top" class="first"><strong>{#SShopWelcomeText#}</strong></td>
    <td class="second">{$row->ShopWillkommen}</td>
  </tr>
  <tr>
    <td width="300" valign="top" class="first"><strong>{#DataShippingInf#}</strong></td>
    <td class="second">{$row->VersandInfo}</td>
  </tr>
  <tr>
    <td width="300" valign="top" class="first"><strong>{#DataInf#}</strong></td>
    <td class="second">{$row->DatenschutzInf}</td>
  </tr>
  <tr>
    <td width="300" valign="top" class="first"><strong>{#Imprint#}</strong></td>
    <td class="second">{$row->Impressum}</td>
  </tr>
  <tr>
    <td width="300" valign="top" class="first"><strong>{#SAgb#}	</strong></td>
    <td class="second">{$row->Agb}</td>
  </tr>
</table>
<br />
<input type="submit" value="{#ButtonSave#}" class="button" />
</form>