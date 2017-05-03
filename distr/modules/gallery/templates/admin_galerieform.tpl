<a name="newg"></a>
<h4>{#NewGallery#}</h4>
<form action="{$formaction}" method="post" name='fr'>
<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
<td width="180" class="first">{#Gname#}</td>
<td class="second">
  <input name="GName" type="text" id="GName" value="{$row.GName|stripslashes|escape:html}" style="width: 300px;" />
</td>
</tr>
<tr>
  <td width="180" class="first">{#Gdesc#}</td>
  <td class="second">
    <textarea name="Beschreibung" cols="50" rows="4" id="Beschreibung" style="width: 300px;">{$row.Beschreibung|stripslashes|escape:html}</textarea>
  </td>
</tr>
<tr>
  <td width="180" class="first">{#GALLERY_Pfad#}</td>
  <td class="second"><input name="GPfad" type="text" id="GPfad" size="40" value="" style="width: 300px;" /></td>
</tr>
<tr>
  <td class="second" colspan="2"><input type="submit" class="button" value="{#ButtonAdd#}" /></td>
</tr>
</table>
<br />
</form>