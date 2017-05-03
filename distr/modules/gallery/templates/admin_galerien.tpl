<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#ModName#}</h2></div>
    <div class="HeaderText">&nbsp;</div>
</div>
<div class="infobox"><a href="#newg">&raquo;&nbsp;{#LinknewGal#}</a></div>
<br />
<form action="" method="post">
<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
<tr class="tableheader">
{if $alert == "Folder_exists"}
<script type="text/javascript" language="JavaScript">
alert("{#Folder_exists#}");
</script>
{/if}
<td width="180">{#Gname#}</td>
<td width="180">{#CpTag#}</td>
<td width="180">{#Gauthor#}</td>
<td width="180">{#GPfad#}</td>
<td width="180">{#Gcreated#}</td>
<td width="5%">{#IncImages#}</td>
<td width="1%" colspan="4" align="center">{#Actions#}</td>
</tr>
<form action='' method='post'>
{foreach from=$gals item=g key=key}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
  <td>
{if $g.Icount > 0}
  <a href="javascript:void(0);" onclick="cp_pop('index.php?do=modules&action=modedit&mod=gallery&moduleaction=showimages&id={$g.Id}&cp={$sess}&compile=1&pop=1','890','800','1','img');">{$g.GName}</a>
{else}
<strong>{$g.GName}</strong>
{/if} </td>
  <td>
    <input name="textfield" type="text" value="[cp_gallery:{$g.Id}]" size="17" />
  </td>
  <td>{$g.Author}</td>
  <td>{$g.GPfad}</td>
  <td class="time">{$g.Erstellt|date_format:$config_vars.DateFormat}</td>
  <td width="5%">
    <div align="center">
	{if $g.Icount > 0}
	<a href="javascript:void(0);" onclick="cp_pop('index.php?do=modules&action=modedit&mod=gallery&moduleaction=showimages&id={$g.Id}&cp={$sess}&pop=1','890','900','1','img');">{$g.Icount}</a>
	{else}-{/if}</div>
</td>
<td> 
<a {popup sticky=false text=$config_vars.AddnewImages} href="index.php?do=modules&amp;action=modedit&amp;mod=gallery&amp;moduleaction=add&amp;id={$g.Id}&amp;cp={$sess}"><img src="{$tpl_dir}/images/icon_add.gif" alt="" border="0" /></a>
</td>
<td> 
<a {popup sticky=false text=$config_vars.EditGallery} href="javascript:void(0);" onclick="cp_pop('index.php?do=modules&action=modedit&mod=gallery&moduleaction=galleryinfo&id={$g.Id}&cp={$sess}&pop=1','660','660','1','img');"><img src="{$tpl_dir}/images/icon_edit.gif" alt="" border="0" /></a>
</td>
<td> 
<a {popup sticky=false text=$config_vars.DeleteGallery} onclick="return confirm('{#DeleteGalleryC#}');" href="index.php?do=modules&action=modedit&mod=gallery&moduleaction=delgallery&id={$g.Id}&cp={$sess}"><img src="{$tpl_dir}/images/icon_del.gif" alt="" border="0" /></a>
</td>
<td> 
<INPUT {popup sticky=false text=$config_vars.CheckboxCreate} type="checkbox" name="create[{$key}]" value="{$g.Id}" unchecked {if $g.GPfad}disabled {/if}/>
</td>
</tr>
{/foreach}
<tr><td class="second" colspan="10"><input type='submit' class='button' style='float:right;' value='{#Create_folder#}'></td></tr>
</table>

</form>
</form>
{if $page_nav}
  <div class="infobox">{$page_nav} </div>
{/if}

{$formnew}