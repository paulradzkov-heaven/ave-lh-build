<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#Overview#}</h2></div>
    <div class="HeaderText">{#OverviewT#}</div>
</div>
<br />
<form name="kform" method="post" action="index.php?do=modules&action=modedit&mod=gallery&moduleaction=showimages&id={$smarty.request.id}&cp={$sess}&pop=1&sub=save&page={$smarty.request.page}">

  <table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
    <tr class="tableheader">
      <td width="1%" align="center"><input name="allbox" type="checkbox" id="d" onclick="selall();" value="" /></td>
      <td width="120">{#FilePrev#}</td>
      <td>{#FileTitle#} / {#FileDesc#}</td>
      <td>{#MoreInfos#}</td>
    </tr>
	{foreach from=$images item=f}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
      <td width="1%" valign="top">
	      <input type="hidden" value="{$f.Id}" name="gimg[{$f.Id}]"  />
    	  <input type="hidden" value="{$f.Pfad}" name="datei[{$f.Id}]"  />
        <input {popup sticky=false text=$config_vars.MarDel|default:''}  name="del[{$f.Id}]" type="checkbox" id="del[{$f.Id}]" value="1">
      </td>
      <td valign="top">
	  <a href="../modules/gallery/uploads/{$f.GPfad}{$f.Pfad}" target="_blank">
	  <img src="../modules/gallery/thumb.php?file={$f.Pfad}&type={$f.Type}&x_width={$f.ThumbBreite}&folder={$f.GPfad}&compile=1" alt="" border="0" />
	  </a>
	  </td>
      <td valign="top">
        <input name="BildTitel[{$f.Id}]" type="text" style="width:350px"  id="BildTitel[{$f.Id}]" value="{$f.BildTitel}">
        <br>
        <textarea name="BildBeschr[{$f.Id}]" cols="40" rows="4" style="width:350px" id="BildBeschr[{$f.Id}]">{$f.BildBeschr}</textarea>
      </td>
      <td valign="top">{#Uploader#}: {$f.Author} <br>
      {#Filesize#}: {$f.Size} kb <br>
      {#UploadOn#}: {$f.Erstellt|date_format:$config_vars.DateFormat}</td>
    </tr>
	{/foreach}
<tr><td class="second" colspan="4"><input class="button" type="submit" value="{#ButtonSave#}" /></td></tr>
  </table>
<br />
</form>
<p>
{$page_nav}
</p>
<p>
<div align="center"><input class="button" onClick="window.close();" type="button" value="{#WinClose#}" /></div>
</p>