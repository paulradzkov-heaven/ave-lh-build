<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#Upload#}</h2></div>
    <div class="HeaderText">{#UploadInfo#} <strong>{foreach from=$allowed item=a}{$a} {/foreach}</strong></div>
</div>
<br>
{if $not_writeable==1}
<br />
<div class="infobox">{#ErrorFolder#}</div>
{else}
<form method="post" action="{$formaction}" enctype="multipart/form-data">

  <table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
  {section name=gu loop=5}
    <tr>
      <td width="1%" class="second">#{$smarty.section.gu.index+1}</td>
      <td width="200" class="second"><strong>{#IName#}</strong></td>
      <td class="second">
        <input name="BildTitel[]" type="text" id="BildTitel[]" style="width: 250px;" />
      </td>
    </tr>
    <tr>
      <td width="1%" class="first">&nbsp;</td>
      <td width="200" class="first"><strong>{#ISelect#}</strong></td>
      <td class="first">
        <input name="file[]" type="file" id="file[]" size="43" style="width:250px;" />
      </td>
    </tr>
	{/section}
	<tr>
	<td width="1%" class="second">&nbsp;</td>
	<td width="200" class="second"><strong>{#Shrink#}</strong></td>
	<td class="second">
	  <select name="shrink" id="shrink">
	    <option></option>
	    <option value="75">{#To75#}</option>
		  <option value="50">{#To50#}</option>
		  <option value="25">{#To25#}</option>
	  </select>
	  </td>
	</tr>
	   <tr>
      <td width="1%" class="first">&nbsp;</td>
      <td width="200" class="first"><strong>Загрузить из папки /temp/</strong></td>
      <td class="first"><input name="regfile" type="checkbox" id="regfile" value="1" unchecked /></td>
    </tr>
    <tr>
      <td class="second" colspan="3"><input type="submit" class="button" value="{#ButtonSave#}" /></td>
    </tr>
  </table>
  <br />

</form>
{/if}