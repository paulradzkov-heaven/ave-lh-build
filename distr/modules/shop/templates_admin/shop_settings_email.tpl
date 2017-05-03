<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#ModName#}</h2></div>
    <div class="HeaderText">&nbsp;</div>
</div>
<br />
{include file="$source/shop_topnav.tpl"}
<h4>{#EmailSettings#}</h4>
<form action="index.php?do=modules&action=modedit&mod=shop&moduleaction=email_settings&cp={$sess}&sub=save" method="post">

<table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
  

  <tr>
    <td width="300" class="first">{#SFormatEmail#}</td>
    <td class="second">
	<input type="radio" value="html" name="EmailFormat" {if $row->EmailFormat=='html'}checked{/if}> {#SFormatHTML#}
	<input type="radio" value="text" name="EmailFormat" {if $row->EmailFormat=='text'}checked{/if}> {#SFormatText#}	</td>
  </tr>
  <tr>
    <td width="300" class="first">{#CompanyLogo#}<br /><small>{#CompanyLogoInf#}</small></td>
    <td class="second">
     {if $row->Logo!=''}<img src="{$row->Logo}" /><br />{/if}
	 <input  name="Logo" type="text" id="Logo" value="{$row->Logo}" size="50" />
	</td>
  </tr>
  <tr>
    <td width="300" class="first">{#SEmailCopy#}</td>
    <td class="second">
      <input name="EmpEmail" type="text" id="EmpEmail" value="{$row->EmpEmail}" size="50" />
    </td>
  </tr>
  <tr>
    <td width="300" class="first">{#SEmailSender#}</td>
    <td class="second">
      <input name="AbsEmail" type="text" id="AbsEmail" value="{$row->AbsEmail}" size="50" />
    </td>
  </tr>
  <tr>
    <td width="300" class="first">{#SSender#}</td>
    <td class="second">
      <input name="AbsName" type="text" id="AbsName" value="{$row->AbsName}" size="50" />
    </td>
  </tr>
  <tr>
    <td width="300" class="first">{#SSubjectOrders#}</td>
    <td class="second">
      <input name="BetreffBest" type="text" id="BetreffBest" value="{$row->BetreffBest|stripslashes}" size="50" />
    </td>
  </tr>
  <tr>
    <td width="300" class="first">
	{#STextfooter#}    </td>
    <td class="second"><textarea style="width:90%; height:100px" name="AdresseText">{$row->AdresseText|escape:html|stripslashes}</textarea></td>
  </tr>
  <tr>
    <td width="300" class="first">{#SHTMLfooter#}    </td>
    <td class="second"><textarea style="width:90%; height:100px" name="AdresseHTML">{$row->AdresseHTML|stripslashes}</textarea></td>
  </tr>
</table>
<br />
<input accesskey="s" type="submit" value="{#ButtonSave#}" class="button" />
</form>