<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#ProductCategChild#}</h2></div>
    <div class="HeaderText">&nbsp;</div>
</div>
<br />
<form action="index.php?do=modules&action=modedit&mod=shop&moduleaction=new_categ&cp={$sess}&pop=1&Id={$smarty.request.Id}&sub=save" method="post" enctype="multipart/form-data">
<table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
  <tr>
  <td width="200" class="first">{#SShipperName#}</td>
  <td class="second">
    <input style="width:200px" type="text" name="KatName">
  </td>
  </tr>
  <tr>
    <td class="first">{#ProductCategsParent#}</td>
    <td class="second">
<select style="width:250px" name="Elter">
<option value="0">{#ProductCategsNoParent#}</option>
{foreach from=$ProductCategs item=pc}
<option value="{$pc->Id}" {if $pc->Elter == 0}style="font-weight:bold"{/if} {if $pc->Id==$smarty.request.Id}selected="selected"{/if}>{$pc->visible_title}</option>
{/foreach}
</select>
	</td>
  </tr>
  <tr>
    <td class="first">{#ProductCategEDescr#}</td>
    <td class="second">
      <textarea style="width:90%; height:120px" name="KatBeschreibung"></textarea>
    </td>
  </tr>
  <tr>
    <td width="200" class="first">{#Position#}</td>
    <td class="second">
      <input name="Rang" type="text" value="1" size="10" maxlength="4" />
    </td>
  </tr>
  
  <tr>
    <td class="first">{#ProductNewImage#}</td>
    <td class="second">
      <input name="Bild" type="file" id="Bild" />
    </td>
  </tr>
</table>
<br />
<input class="button" type="submit" value="{#ButtonSave#}" />
</form>