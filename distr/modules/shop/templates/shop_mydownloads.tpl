<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
   {if $smarty.request.print!=1}
    <td valign="top" class="mod_shop_left">
	<!-- SHOP - NAVI -->
	<div id="leftnavi" style="margin:0px; padding:0px;">
	{$ShopNavi}
	</div>
	
	<!-- FLOAT END -->
	<div style="clear:both"></div>
	
	<!-- USER - PANEL -->
	{$UserPanel}
	
	<!-- USER - ORDERS -->
	{$MyOrders}

	<!-- INFOBOX -->
	{$InfoBox}
	<!-- BASKET -->
	<br />
	{$Basket}
	<!-- SHOP - SEARCH -->
	{$Search}
	<!-- TOPSELLER -->
	{$Topseller}
	</td>

   {/if}
    <td valign="top" id="contents_middle_shop2">

  <div class="mod_shop_topnav"><a class="mod_shop_navi" href="index.php?module=shop">{#PageName#}</a> {#PageSep#} {#DownloadsOverviewShowLink#}</div>
  <br />
  <script language="javascript">
function print_container(id,act)
{ldelim}
	var html = document.getElementById(id).innerHTML;
	html = html.replace(/&lt;/gi, '<' );
	html = html.replace(/&gt;/gi, '>' );
	var act = (act == 'preform') ? '<pre>' : '';
	var pFenster = window.open( '', null, 'height=600,width=780,toolbar=yes,location=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes' ) ;
	var HTML = '<html><head></head><body style="font-family:arial,verdana;font-size:12px" onload="window.print()">'+ act + html + '</body></html>' ;
	pFenster.document.write(HTML);
	pFenster.document.close();
{rdelim}

function request(id)
{ldelim}
	var text = '{#OrderRequestText#}';
	text = text.replace(/%%ORDER%%/gi, id);
	text = text.replace(/%%USER%%/gi, '{$smarty.session.cp_uname}');
	document.getElementById('request').style.display='';
	document.getElementById('request_subject').value = '{#OrdersRequestSubject#} ' + id;
	document.getElementById('request_text').value = text;
{rdelim}
  </script>
{#DownloadsOverviewInf#} <br />
<br />
{foreach from=$downloads item=dl name=d}

<div class="mod_shop_orders_header" style="padding:4px"> <span style="" id="so_{$dl->Id}"><a href="javascript:void(0);" onclick="document.getElementById('{$dl->Id}').style.display=''; document.getElementById('sc_{$dl->Id}').style.display=''; document.getElementById('so_{$dl->Id}').style.display='none'">[+]</a>&nbsp;</span> <span style="display:none" id="sc_{$dl->Id}"><a href="javascript:void(0);" onclick="document.getElementById('{$dl->Id}').style.display='none'; document.getElementById('so_{$dl->Id}').style.display=''; document.getElementById('sc_{$dl->Id}').style.display='none'">[-]</a>&nbsp;</span> <strong>{$dl->ArtName|stripslashes}</strong> </div>
<div id="{$dl->Id}" style="display:none">
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td width="200" valign="top">{#DownloadsFilename#}</td>
      <td valign="top">{#DownloadsFileDownloadTill#}</td>
      <td valign="top">{#DownloadsFileSize#}</td>
      <td valign="top">&nbsp;</td>
    </tr>
    {if $dl->DataFiles}
    <tr>
      <td colspan="4" class="mod_shop_download_typheader">{#DownloadsTypeFull#}</td>
    </tr>
    {/if}
    {foreach from=$dl->DataFiles item=df}
    <tr>
      <td width="200" valign="top">
	  {if $df->Abgelaufen==1}
	  {$df->Titel|stripslashes} 
	  {else}
	  <strong>
	  <a {if $df->Beschreibung}{popup sticky=false width=500 text=$df->Beschreibung|replace:"'":"""}{/if}  href="index.php?module=shop&action=mydownloads&sub=getfile&Id={$df->Id}&FileId={$dl->ArtikelId}&getId={$df->Id}">{$df->Titel|stripslashes}</a>	  </strong>
	  {/if}	  </td>
      <td>
	  {$dl->DownloadBis|date_format:$config_vars.DateFormatEsdTill}	  </td>
      <td>{$df->size} kb</td>
      <td align="right"> <a href="index.php?module=shop&action=mydownloads&sub=getfile&Id={$df->Id}&FileId={$dl->ArtikelId}&getId={$df->Id}"><img src="{$shop_images}download.gif" alt="" border="0" /></a> </td>
    </tr>
    {/foreach}
    
    {if $dl->DataFilesUpdates}
    <tr>
      <td colspan="4" class="mod_shop_download_typheader">{#DownloadsTypeUpdates#}</td>
    </tr>
    {/if}
    {foreach from=$dl->DataFilesUpdates item=df}
    <tr>
      <td width="200" valign="top"> <strong> <a {if $df->Beschreibung}{popup sticky=false width=500 text=$df->Beschreibung|replace:"'":"&quot;"}{/if}  href="index.php?module=shop&action=mydownloads&sub=getfile&Id={$df->Id}&FileId={$dl->ArtikelId}&getId={$df->Id}">{$df->Titel}</a></strong></td>
      <td>{$dl->DownloadBis|date_format:$config_vars.DateFormatEsdTill}</td>
      <td>{$df->size} kb</td>
      <td align="right"> <a href="index.php?module=shop&action=mydownloads&sub=getfile&Id={$df->Id}&FileId={$dl->ArtikelId}&getId={$df->Id}"><img src="{$shop_images}download.gif" alt="" border="0" /></a> </td>
    </tr>
    {/foreach}
    
    {if $dl->DataFilesBugfixes}
    <tr>
      <td colspan="4" class="mod_shop_download_typheader">{#DownloadsTypeBugfix#}</td>
    </tr>
    {/if}
    {foreach from=$dl->DataFilesBugfixes item=df}
    <tr>
      <td width="200" valign="top"> <strong> <a {if $df->Beschreibung}{popup sticky=false width=500 text=$df->Beschreibung|replace:"'":"&quot;"}{/if}  href="index.php?module=shop&action=mydownloads&sub=getfile&Id={$df->Id}&FileId={$dl->ArtikelId}&getId={$df->Id}">{$df->Titel}</a></strong></td>
      <td>{$dl->DownloadBis|date_format:$config_vars.DateFormatEsdTill}</td>
      <td>{$df->size} kb</td>
      <td align="right"> <a href="index.php?module=shop&action=mydownloads&sub=getfile&Id={$df->Id}&FileId={$dl->ArtikelId}&getId={$df->Id}"><img src="{$shop_images}download.gif" alt="" border="0" /></a> </td>
    </tr>
    {/foreach}
    
    {if $dl->DataFilesOther}
    <tr>
      <td colspan="4" class="mod_shop_download_typheader">{#DownloadsTypeOther#}</td>
    </tr>
    {/if}
    {foreach from=$dl->DataFilesOther item=df}
    <tr>
      <td width="200" valign="top"> <strong> <a {if $df->Beschreibung}{popup sticky=false width=500 text=$df->Beschreibung|replace:"'":"&quot;"}{/if}  href="index.php?module=shop&action=mydownloads&sub=getfile&Id={$df->Id}&FileId={$dl->ArtikelId}&getId={$df->Id}">{$df->Titel}</a></strong></td>
      <td>Unbegrenzt</td>
      <td>{$df->size} kb</td>
      <td align="right"> <a href="index.php?module=shop&action=mydownloads&sub=getfile&Id={$df->Id}&FileId={$dl->ArtikelId}&getId={$df->Id}"><img src="{$shop_images}download.gif" alt="" border="0" /></a> </td>
    </tr>
    {/foreach}
  </table>
</div>
<br />
{/foreach} </div></td>
{if $smarty.request.print!=1}    {/if}  </tr>
</table>