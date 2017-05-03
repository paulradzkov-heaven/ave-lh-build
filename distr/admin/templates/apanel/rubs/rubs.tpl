{strip}
<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_rubs"></div>
    <div class="HeaderTitle"><h2>{#RUBRIK_SUB_TITLE#}</h2></div>
    <div class="HeaderText">{#RUBRIK_TIP#}</div>
</div>


{if cp_perm('rub_neu')}
<script type="text/javascript" language="JavaScript">
 function check_name() {ldelim}
   if (document.getElementById('RubName').value == '') {ldelim}
     alert("{#RUBRIK_ENTER_NAME#}");
     document.getElementById('RubName').focus();
     return false;
   {rdelim}
   return true;
 {rdelim}
</script>

<h4>{#RUBRIK_NEW#}</h4>
<form method="post" action="index.php?do=rubs&amp;action=new&amp;cp={$sess}" onSubmit="return check_name();">
<table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
 <tr>
   <td class="second">{#RUBRIK_NAME2#} <input type="text" id="RubName" name="RubName" value="" style="width: 250px;">&nbsp;<input type="submit" class="button" value="{#RUBRIK_BUTTON_NEW#}" />
 </tr>
</table>
</form>
{/if}


<h4>{#RUBRIK_ALL#}</h4>
{if cp_perm('rub_edit')}
<form method="post" action="index.php?do=rubs&amp;cp={$sess}&amp;sub=quicksave{if $smarty.request.page!=''}&amp;page={$smarty.request.page}{/if}">
{/if}

<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
  <tr class="tableheader">
    <td width="10">{#RUBRIK_ID#}</td>
    <td>{#RUBRIK_NAME#}</td>
    <td>{#RUBRIK_URL_PREFIX#}</td>
    <td>{#RUBRIK_TEMPLATE_OUT#}</td>
    <td width="120" align="center">{#RUBRIK_COUNT_DOCS#}</td>
    <td align="center">{#RUBRIK_AUTHOR#}</td>
    <td colspan="4"><div align="center">{#RUBRIK_ACTION#}</div></td>
  </tr>

  {foreach from=$items item=tpl}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
    <td width="10" class="itcen">{$tpl->Id}</td>
    <td>
    {if cp_perm('rub_edit')}
      <input style="width:98%" type="text" name="RubrikName[{$tpl->Id}]" value="{$tpl->RubrikName}">
    {else}
      <strong>{$tpl->RubrikName}</strong>
    {/if} </td>
    <td>
    {if cp_perm('rub_edit')}
      <input style="width:98%" type="text" name="UrlPrefix[{$tpl->Id}]" value="{$tpl->UrlPrefix}">
    {else}
      <strong>{$tpl->UrlPrefix}</strong>
    {/if} </td>
    <td style="width:200px">
    {if cp_perm('rub_edit')}
      <select style="width:200px" name="Vorlage[{$tpl->Id}]">
      {foreach from=$tpl->AlleVorlagen item=av}
        <option value="{$av->Id}" {if $av->Id==$tpl->Vorlage}selected {/if}/>{$av->TplName}</option>
      {/foreach}
      </select>
    {else}
      {$tpl->VorlageName}
    {/if}
    </td>

    <td align="center"><strong>{$tpl->doc_count}</strong></td>

    <td>{$tpl->RBenutzer}</td>

    <td nowrap="nowrap" width="1%" align="center">
    {if cp_perm('rub_edit')}
      <a {popup sticky=false text=$config_vars.RUBRIK_EDIT} href="index.php?do=rubs&amp;action=edit&amp;Id={$tpl->Id}&amp;cp={$sess}">
      <img src="{$tpl_dir}/images/icon_edit.gif" alt="" border="0" /></a>
    {else}
      <img {popup sticky=false text=$config_vars.RUBRIK_NO_CHANGE1} src="{$tpl_dir}/images/icon_edit_no.gif" alt="" border="0" />
    {/if}
    </td>

    <td nowrap="nowrap" width="1%" align="center">
    {if cp_perm('rub_edit')}
      <a {popup sticky=false text=$config_vars.RUBRIK_EDIT_TEMPLATE} href="index.php?do=rubs&action=template&Id={$tpl->Id}&cp={$sess}">
      <img src="{$tpl_dir}/images/icon_template.gif" alt="" border="0" /></a>
    {else}
      <img {popup sticky=false text=$config_vars.RUBRIK_NO_CHANGE2} src="{$tpl_dir}/images/icon_template_no.gif" alt="" border="0" />
    {/if}
    </td>

    <td nowrap="nowrap" width="1%" align="center">
    {if cp_perm('rub_multi')}
      <a {popup sticky=false text=$config_vars.RUBRIK_MULTIPLY} href="javascript:void(0);" onclick="window.open('?do=rubs&amp;action=multi&amp;pop=1&amp;Id={$tpl->Id}&amp;cp={$sess}','pop','top=0,left=0,width=400,height=250')">
      <img src="{$tpl_dir}/images/icon_copy.gif" alt="" border="0" /></a>
    {else}
      <img {popup sticky=false text=$config_vars.RUBRIK_NO_MULTIPLY} src="{$tpl_dir}/images/icon_copy_no.gif" alt="" border="0" />
    {/if}
    </td>

    <td nowrap="nowrap" width="1%" align="center">
    {if $tpl->Id != 1}
    {if $tpl->can_deleted==1}
    {if cp_perm('rub_loesch')}
      <a {popup sticky=false text=$config_vars.RUBRIK_DELETE} onclick="return (confirm('{#RUBRIK_DELETE_CONFIRM#}'))" href="index.php?do=rubs&amp;action=delete&amp;Id={$tpl->Id}&amp;cp={$sess}"><img src="{$tpl_dir}/images/icon_delete.gif" alt="" border="0" /></a>
    {else}
      <img {popup sticky=false text=$config_vars.RUBRIK_NO_PERMISSION} src="{$tpl_dir}/images/icon_delete_no.gif" alt="" border="0" />
    {/if}
    {else}
      <img {popup sticky=false text=$config_vars.RUBRIK_USE_DOCUMENTS} src="{$tpl_dir}/images/icon_delete.gif" alt="" border="0" />
    {/if}
    {else}
      <img src="{$tpl_dir}/images/icon_delete_no.gif" alt="" border="0" />
    {/if}
    </td>
  </tr>
 {/foreach}
</table><br />

{if cp_perm('rub_edit')}
  <input class="button" type="submit" value="{#RUBRIK_BUTTON_SAVE#}" /><br />
{/if}


{if $page_nav}
  <div class="infobox">{$page_nav} </div>
{/if}
{if cp_perm('rub_edit')}
</form>
{/if}

<br />

<div class="iconHelpSegmentBox">
  <div class="segmentBoxHeader">
    <div class="segmentBoxTitle">&nbsp;</div>
  </div>
  <div class="segmentBoxContent">
    <img class="absmiddle" src="{$tpl_dir}/images/arrow.gif" alt="" border="0" /> <strong>{#RUBRIK_LEGEND#}</strong><br />
    <br />
    <img class="absmiddle" src="{$tpl_dir}/images/icon_edit.gif" alt="" border="0" /> - {$config_vars.RUBRIK_EDIT}<br />
    <img class="absmiddle" src="{$tpl_dir}/images/icon_template.gif" alt="" border="0" /> - {$config_vars.RUBRIK_EDIT_TEMPLATE}<br />
    <img class="absmiddle" src="{$tpl_dir}/images/icon_copy.gif" alt="" border="0" /> - {$config_vars.RUBRIK_MULTIPLY}<br />
    <img class="absmiddle" src="{$tpl_dir}/images/icon_del.gif" alt="" border="0" /> - {$config_vars.RUBRIK_DELETE_LEGEND}
  </div>
</div>
{/strip}