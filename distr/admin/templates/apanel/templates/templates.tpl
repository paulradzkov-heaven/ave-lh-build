{strip}
<div id="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_tpl"></div>
    <div class="HeaderTitle"><h2>{#TEMPLATES_SUB_TITLE#}</h2></div>
    <div class="HeaderText">{#TEMPLATES_TIP1#}</div>
</div>

{if cp_perm('vorlagen_neu')}
<script type="text/javascript" language="JavaScript">

 function check_name() {ldelim}
   if (document.getElementById('TempName').value == '') {ldelim}
     alert("{#TEMPLATES_TIP3#}");
     document.getElementById('TempName').focus();
     return false;
   {rdelim}
   return true;
 {rdelim}

</script>

<h4>{#TEMPLATES_TITLE_NEW#}</h4>
<form method="post" action="index.php?do=templates&amp;action=new&amp;cp={$sess}" onSubmit="return check_name();">
<table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
 <tr>
   <td class="second">{#TEMPLATES_NAME3#} <input type="text" id="TempName" name="TempName" value="" style="width: 250px;">&nbsp;<input type="submit" class="button" value="{#TEMPLATES_BUTTON_ADD#}" />
 </tr>
</table>
</form>
{/if}

<h4>{#TEMPLATES_ALL#}</h4>

<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
  <tr class="tableheader">
    <td width="10">{#TEMPLATES_ID#}</td>
    <td>{#TEMPLATES_NAME#}</td>
    <td>{#TEMPLATES_AUTHOR#}</td>
    <td>{#TEMPLATES_DATE#}</td>
    <td colspan="3" class="tableheader"><div align="center">{#TEMPLATES_ACTION#}</div></td>
  </tr>
  
  {foreach from=$items item=tpl}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
    <td width="10" class="itcen">{$tpl->Id}</td>
    <td><strong>{if cp_perm('vorlagen_edit')}<a {popup sticky=false text=$config_vars.TEMPLATES_EDIT} href="index.php?do=templates&amp;action=edit&amp;Id={$tpl->Id}&amp;cp={$sess}">{$tpl->TplName}</a>{else}{$tpl->TplName}{/if}</strong></td>
    <td>{$tpl->TBenutzer}</td>
    <td class="time">{$tpl->TDatum|date_format:$config_vars.TEMPLATES_DATE_FORMAT} {#TEMPLATES_IN#} {$tpl->TDatum|date_format:$config_vars.TEMPLATES_DATE_FORMAT2}</td>
    <td nowrap="nowrap" width="1%" align="center">
      {if cp_perm('vorlagen_edit')}
        <a {popup sticky=false text=$config_vars.TEMPLATES_EDIT} href="index.php?do=templates&amp;action=edit&amp;Id={$tpl->Id}&amp;cp={$sess}">
        <img src="{$tpl_dir}/images/icon_edit.gif" alt="" border="0" /></a>
      {else}
        <img {popup sticky=false text=$config_vars.TEMPLATES_NO_CHANGE} src="{$tpl_dir}/images/icon_edit_no.gif" alt="" border="0" />
      {/if}
    </td>
    <td nowrap="nowrap" width="1%" align="center">
      {if cp_perm('vorlagen_multi')}
        <a {popup sticky=false text=$config_vars.TEMPLATES_COPY} href="javascript:void(0);" onclick="window.open('?do=templates&amp;action=multi&amp;pop=1&amp;Id={$tpl->Id}&amp;cp={$sess}','pop','top=0,left=0,width=400,height=250')">
        <img src="{$tpl_dir}/images/icon_copy.gif" alt="" border="0" />    </a>
      {else}
        <img {popup sticky=false text=$config_vars.TEMPLATES_NO_COPY} src="{$tpl_dir}/images/icon_copy_no.gif" alt="" border="0" />
      {/if}
    </td>
    <td nowrap="nowrap" width="1%" align="center">
      {if $tpl->Id == 1}
        <img src="{$tpl_dir}/images/icon_delete_no.gif" alt="" border="0" />
      {else}
    
      {if $tpl->can_deleted==1}
      {if cp_perm('vorlagen_loesch')}
        <a  {popup sticky=false text=$config_vars.TEMPLATES_DELETE} onclick="return (confirm('{#TEMPLATES_DELETE_CONF#}'))" href="index.php?do=templates&amp;action=delete&amp;Id={$tpl->Id}&amp;cp={$sess}">
        <img  src="{$tpl_dir}/images/icon_delete.gif" alt="" border="0" /></a>
      {else}
        <img {popup sticky=false text=$config_vars.TEMPLATES_NO_DELETE3} src="{$tpl_dir}/images/icon_delete_no.gif" alt="" border="0" />
      {/if}
      {else}
        <img {popup sticky=false text=$config_vars.TEMPLATES_NO_DELETE2} src="{$tpl_dir}/images/icon_delete_no.gif" alt="" border="0" />
      {/if}
      {/if}
    </td>
  </tr>
 {/foreach}
</table>
{if $page_nav}
  <div class="infobox">{$page_nav} </div>
{/if}
<br /><br />
      <div class="iconHelpSegmentBox">
        <div class="segmentBoxHeader"><div class="segmentBoxTitle">&nbsp;</div></div>
        <div class="segmentBoxContent">
<img class="absmiddle" src="{$tpl_dir}/images/arrow.gif" alt="" border="0" /> <strong>{#TEMPLATES_LEGEND#}</strong>
<br /><br>
  <img class="absmiddle" src="{$tpl_dir}/images/icon_edit.gif" alt="" border="0" /> - {$config_vars.TEMPLATES_EDIT}<br />
  <img class="absmiddle" src="{$tpl_dir}/images/icon_delete.gif" alt="" border="0" /> - {$config_vars.TEMPLATES_DELETE}<br />
  <img class="absmiddle" src="{$tpl_dir}/images/icon_copy.gif" alt="" border="0" /> - {$config_vars.TEMPLATES_COPY}</div>
        </div>
      </div>
{/strip}


