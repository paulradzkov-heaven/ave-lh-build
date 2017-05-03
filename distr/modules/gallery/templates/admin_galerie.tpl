<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#ModSettingGal#}</h2></div>
    <div class="HeaderText">&nbsp;</div>
</div>
<form method="post" name='form' action="index.php?do=modules&action=modedit&mod=gallery&moduleaction=galleryinfo&id={$smarty.request.id}&cp={$sess}&pop=1&sub=save">
<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
<tr>
  <td width="180" class="first">{#Gname#}</td>
  <td class="second"><input name="GName" type="text" id="GName" value="{$row.GName|escape:html|stripslashes}" size="40"></td>
</tr>
<tr>
  <td width="180" class="first">{#Gdesc#}</td>
  <td class="second"><textarea name="Beschreibung" cols="40" rows="5" id="Beschreibung">{$row.Beschreibung|escape:html|stripslashes}</textarea></td>
</tr>
<tr>
  <td width="220" class="first">{#GALLERY_Pfad#}</td>
  <td class="second" {popup sticky=false text=$config_vars.GALLERY_Pfad_desc}><input name="GPfad" type="text" id="GPfad" onChange="document.form.GPfad_flag.value='1';" size="40" value="{$row.GPfad|replace:'/':''}" /></td>
  <input type='hidden' name='GPfad_flag' value='0'>
</tr>
<tr>
  <td width="180" class="first">{#MaxWidth#}</td>
  <td class="second" {popup sticky=false text=$config_vars.MaxWidthWarn}><input name="ThumbBreite" type="text" id="ThumbBreite" value="{$row.ThumbBreite|escape:html|stripslashes}" size="5" maxlength="3"></td>
  <input name="ThumbBreite_Alt" type="hidden" value="{$row.ThumbBreite}">
</tr>
<tr>
  <td width="180" class="first">{#MaxImagesERow#}</td>
  <td class="second"><input name="MaxZeile" type="text" id="MaxZeile" value="{$row.MaxZeile|escape:html|stripslashes}" size="5" maxlength="2"></td>
</tr>
<tr>
  <td class="first">{#MaxImagesPage#}</td>
  <td class="second"><input name="MaxBilder" type="text" id="MaxBilder" value="{$row.MaxBilder}" size="5" maxlength="4" /></td>
</tr>
<tr>
  <td width="180" class="first">{#ShowHeader#}</td>
  <td class="second"><input name="ZeigeTitel" type="checkbox" id="ZeigeTitel" value="1" {if $row.ZeigeTitel==1}checked{/if}></td>
</tr>
<tr>
  <td width="180" class="first">{#Showdescr#}</td>
  <td class="second"><input name="ZeigeBeschreibung" type="checkbox" id="ZeigeBeschreibung" value="1" {if $row.ZeigeBeschreibung==1}checked{/if}></td>
</tr>
<tr>
  <td width="180" class="first">{#ShowSize#}</td>
  <td class="second"><input name="ZeigeGroesse" type="checkbox" id="ZeigeGroesse" value="1" {if $row.ZeigeGroesse==1}checked{/if}></td>
</tr>
<tr>
  <td width="180" class="first">{#Watermark#}</td>
  <td class="second"><input name="Watermark" type="text" id="Watermark" value="{$row.Watermark}" size="40"></td>
</tr>
<tr>
  <td width="180" class="first">{#TypeOut#}</td>
  <td class="second">
		<select name="TypeOut">
		  <option value="1" {if $row.TypeOut==1}selected{/if} />{#TypeOut1#}</option>
		  <option value="2" {if $row.TypeOut==2}selected{/if} />{#TypeOut2#}</option>
		  <option value="3" {if $row.TypeOut==3}selected{/if} />{#TypeOut3#}</option>
		  <option value="4" {if $row.TypeOut==4}selected{/if} />{#TypeOut4#}</option>
		</select>  	
  </td>
</tr>
<tr>
  <td width="180" class="first">{#Sort#}</td>
  <td class="second">
		<select name="Sort">
		  <option value="asc" {if $row.Sort=='asc'}selected{/if} />{#asc#}</option>
		  <option value="desc" {if $row.Sort=='desc'}selected{/if} />{#desc#}</option>
		</select>  	
  </td>
</tr>
<tr>
  <td class="second" colspan="2">
   	<input type="submit" class="button" value="{#ButtonSave#}" />
  </td>
</tr>
</table>
<br />
</form>