<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#VIDEO_EDIT#}</h2></div>
    <div class="HeaderText">{#VIDEO_EDIT_TIP#}</div>
</div>

<br />

<div class="infobox">
» <a href="index.php?do=modules&action=modedit&mod=videoplayer&moduleaction=new&cp={$sess}">{#VIDEO_ADD#}</a>
</div>

<br />

{if $Id}
  <table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
    <tr class="tableheader">
      <td width="1%">{#VIDEO_ID#}</td>
	  <td width="1%">{#VIDEO_IMAGE#}</td>
      <td>{#VIDEO_NAME#}</td>
	  <td width="100">{#VIDEO_TAG#}</td>
      <td width="1%" colspan="3">{#VIDEO_ACTIONS#}</td>
    </tr>
    {foreach from=$Id item=item}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
      <td class="itcen">{$item->Id}</td>
	  <td><img src="../thumb.php?file={$item->ImagePreview}&x_width=100" alt="" border="0"></td>
      <td>
        <a {popup sticky=false text=$config_vars.VIDEO_EDIT_HINT} href="index.php?do=modules&action=modedit&mod=videoplayer&moduleaction=edit&cp={$sess}&Id={$item->Id}">{$item->VideoTitle|escape:html|stripslashes}</a>
<br /><br /> <strong>Продолжительность:</strong> {$item->Duration|escape:html|stripslashes} секунд.
<br /> <strong>Размер (пиксель):</strong> Ширина: {$item->Width|escape:html|stripslashes}&nbsp;&nbsp;&nbsp;х&nbsp;&nbsp;&nbsp;Высота: {$item->Height|escape:html|stripslashes}
      </td>
      <td width="100"><input name="textfield" type="text" value="[cp_play:{$item->Id}]" readonly /></td>
      <td align="center">
<!-- 
<a {popup sticky=false text=$config_vars.VIDEO_VIEW_HINT} href="javascript:void(0);" onclick="window.open('index.php?do=modules&action=modedit&mod=videoplayer&moduleaction=view&Id={$item->Id}&pop=1','fo','width=820,height=600,scrollbars=yes,left=0,top=0')">
 -->
 
<a {popup sticky=false text=$config_vars.VIDEO_VIEW_HINT} href="javascript:void(0);" onclick="cp_pop('index.php?do=modules&action=modedit&mod=videoplayer&moduleaction=view&cp={$sess}&Id={$item->Id}&pop=1','800','600','1','videoview');">
        <img src="{$tpl_dir}/images/icon_look.gif" alt="" border="0" /></a>
      </td>
      <td align="center">
        <a {popup sticky=false text=$config_vars.VIDEO_EDIT_HINT} href="index.php?do=modules&action=modedit&mod=videoplayer&moduleaction=edit&cp={$sess}&Id={$item->Id}">
        <img src="{$tpl_dir}/images/icon_edit.gif" alt="" border="0" /></a>
      </td>
      <td align="center">
        <a {popup sticky=false text=$config_vars.VIDEO_DELETE_HINT} onclick="return confirm('{$config_vars.VIDEO_DEL_HINT}');" href="index.php?do=modules&action=modedit&mod=videoplayer&moduleaction=del&cp={$sess}&Id={$item->Id}">
        <img src="{$tpl_dir}/images/icon_del.gif" alt="" border="0" />
      </td>
    </tr>
    {/foreach}
</table>
{else}
<h4 style="color: #800000">{#VIDEO_NO_ITEMS#}</h4>
{/if}