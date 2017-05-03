<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#ProductCategEEdit#}</h2></div>
    <div class="HeaderText">&nbsp;</div>
</div>
<br />
<form action="index.php?do=modules&action=modedit&mod=shop&moduleaction=edit_categ&cp={$sess}&pop=1&Id={$smarty.request.Id}&sub=save" method="post" enctype="multipart/form-data">
<table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
  <tr>
  <td width="200" class="first">{#SShipperName#}</td>
  <td class="second">
    <input style="width:200px" type="text" name="KatName" value="{$row->KatName|escape:html|stripslashes}">
  </td>
  </tr>
  <tr>
    <td class="first">{#ProductCategEDescr#}</td>
    <td class="second">
      <textarea style="width:90%; height:120px" name="KatBeschreibung">{$row->KatBeschreibung|stripslashes|escape:html}</textarea>
    </td>
  </tr>
  <tr>
    <td width="200" class="first">{#Position#}</td>
    <td class="second">
      <input name="Rang" type="text" value="{$row->Rang}" size="10" maxlength="4" />
    </td>
  </tr>
  <tr>
    <td width="200" class="first">{#ProductCategEImage#}</td>
    <td class="second">
	{if $row->Bild != ''}<img src="../modules/shop/uploads/{$row->Bild}">
	<input name="Old" type="hidden" id="Old" value="{$row->Bild}" />
	<input name="ImgDel" type="checkbox" id="ImgDel" value="1" />
	{#ProductCategEImageDel#}
	{else}-{/if}	</td>
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