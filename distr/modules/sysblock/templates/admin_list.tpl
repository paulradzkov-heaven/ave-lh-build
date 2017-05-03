<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#SYSBLOCK_EDIT#}</h2></div>
    <div class="HeaderText">{#SYSBLOCK_EDIT_TIP#}</div>
</div>

<br />

<div class="infobox">
» <a href="index.php?do=modules&action=modedit&mod=sysblock&moduleaction=edit&cp={$sess}">{#SYSBLOCK_ADD#}</a>
</div>

<br />

{if $SysBlock}
  <table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
    <tr class="tableheader">
      <td width="1%">{#SYSBLOCK_ID#}</td>
      <td>{#SYSBLOCK_NAME#}</td>
	  <td width="100">{#SYSBLOCK_TAG#}</td>
      <td width="1%" colspan="2">{#SYSBLOCK_ACTIONS#}</td>
    </tr>
    {foreach from=$SysBlock item=item}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
      <td class="itcen">{$item->id}</td>
      <td>
        <a {popup sticky=false text=$config_vars.SYSBLOCK_EDIT_HINT} href="index.php?do=modules&action=modedit&mod=sysblock&moduleaction=edit&cp={$sess}&id={$item->id}">{$item->sysblock_name|escape:html|stripslashes}</a>
      </td>
      <td width="100"><input name="textfield" type="text" value="[cp_sysblock:{$item->id}]" readonly /></td>
      <td align="center">
        <a {popup sticky=false text=$config_vars.SYSBLOCK_EDIT_HINT} href="index.php?do=modules&action=modedit&mod=sysblock&moduleaction=edit&cp={$sess}&id={$item->id}">
        <img src="{$tpl_dir}/images/icon_edit.gif" alt="" border="0" /></a>
      </td>
      <td align="center">
        <a {popup sticky=false text=$config_vars.SYSBLOCK_DELETE_HINT} onclick="return confirm('{$config_vars.SYSBLOCK_DEL_HINT}');" href="index.php?do=modules&action=modedit&mod=sysblock&moduleaction=del&cp={$sess}&id={$item->id}">
        <img src="{$tpl_dir}/images/icon_del.gif" alt="" border="0" />
      </td>
    </tr>
    {/foreach}
</table>
{else}
<h4 style="color: #800000">{#SYSBLOCK_NO_ITEMS#}</h4>
{/if}
