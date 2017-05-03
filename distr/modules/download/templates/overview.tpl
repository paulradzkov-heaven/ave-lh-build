<div id="mod_download">
<div class="mod_download_topnav">
  <h1>{$NavTop|default:$config_vars.PageName}</h1>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
	{if $smarty.request.categ!=''}
	{$TheDownloads}
	{else}
	{$NewestDownloads}
	<br />
	{$TopDownloads}
	{/if}
    </td>
    <td valign="top">&nbsp;&nbsp;</td>
    <td width="45%" valign="top">
	{$SearchPanel}
	<br />
	{$Categs}
	</td>
  </tr>
</table>
<p> </p>
<p> </p>
</div>