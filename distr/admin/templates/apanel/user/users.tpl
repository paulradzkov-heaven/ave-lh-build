{strip}
<div id="pageHeaderTitle" style="padding-top: 7px;">
  <div class="h_user"></div>
  <div class="HeaderTitle"><h2>{#USER_SUB_TITLE#}</h2></div>
  <div class="HeaderText">{#USER_TIP1#}</div>
</div>
<div class="upPage"></div>

{if cp_perm('user_new')}

<script type="text/javascript" language="JavaScript">
 function check_name() {ldelim}
   if (document.getElementById('UserName').value == '') {ldelim}
     alert("{#USER_NO_FIRSTNAME#}");
     document.getElementById('UserName').focus();
     return false;
   {rdelim}
   return true;
 {rdelim}
</script>

<h4>{#USER_NEW_ADD#}</h4>
<form method="post" action="index.php?do=user&amp;action=new&amp;cp={$sess}" onSubmit="return check_name();">
<table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
 <tr>
   <td class="second">{#USER_FIRSTNAME_ADD#} <input type="text" id="UserName" name="UserName" value="" style="width: 250px;">&nbsp;<input type="submit" class="button" value="{#USER_BUTTON_ADD#}" /></td>
 </tr>
</table>
</form>
{/if}

{if cp_perm('user')}
<h4>{#MAIN_SEARCH_USERS#}</h4>
<form action="index.php?do=user&amp;cp={$sess}" method="post">
<table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
 <tr>
   <td class="second"><strong>{#MAIN_USER_PARAMS#}</strong></td>
   <td class="first" width="5%">
   <input style="width:180px" name="query" type="text" id="l_query" value="{$smarty.request.query|escape:html}" />
   </td>
   <td class="second"><strong>{#MAIN_USER_STATUS#}</strong></td>
   <td class="first" width="5%">
        <select style="width:180px" name="Status">
          <option value="all" {if $smarty.request.Status=='all'}selected="selected"{/if}>{#MAIN_USER_STATUS_ALL#}</option>
          <option value="1" {if $smarty.request.Status=='1'}selected="selected"{/if}>{#MAIN_USER_STATUS_ACTIVE#}</option>
          <option value="0" {if $smarty.request.Status=='0'}selected="selected"{/if}>{#MAIN_USER_STATUS_INACTIVE#}</option>
        </select>
   </td>
   <td class="second"><strong>{#MAIN_USER_GROUP#}</strong></td>
   <td class="first" width="5%">
        <select style="width:180px" name="Benutzergruppe" id="l_ug">
          <option value="0">{#MAIN_ALL_USER_GROUP#}</option>
          {foreach from=$ugroups item=g}
            <option value="{$g->Benutzergruppe}">{$g->Name|escape:html}</option>
          {/foreach}
        </select>
   </td>
   <td class="second" width="1%"><input style="width:85px" type="submit" class="button" value="{#MAIN_BUTTON_SEARCH#}" /> </td>
 </tr>
</table>
</form>
{/if}

<h4>{#USER_ALL#}</h4>
{if cp_perm('user_edit')}<form method="post" action="/admin/index.php?do=user&amp;cp={$sess}&amp;action=quicksave">{/if}
  <table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
    <tr class="tableheader">
      <td width="10">{#USER_ID#}</td>
      <td align="center"><img src="{$tpl_dir}/images/icon_delete.gif" alt="" border="0" /></td>
      <td>{#USER_NAME#}</td>
      <td>{#USER_GROUP#}</td>
      <td>{#USER_LAST_VISIT#}</td>
      <td>{#USER_REGISTER_DATE#}</td>
      <td colspan="5"><div align="center">{#USER_ACTION#}</div></td>
  </tr>

  {foreach from=$users item=user}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
    <td width="10" class="itcen">{$user->Id}</td>
    <td width="1%"><input {popup sticky=false text=$config_vars.USER_MARK_DELETE|default:''}  name="del[{$user->Id}]" type="checkbox" id="del[{$user->Id}]" value="1" {if !cp_perm('user_loesch') || $user->Benutzergruppe==1 || $user->Id==$smarty.session.cp_benutzerid}disabled{/if} /></td>
    <td>
      {if cp_perm('user_edit')}
        <a {popup sticky=false text=$config_vars.USER_EDIT} href="index.php?do=user&amp;action=edit&amp;Id={$user->Id}&amp;cp={$sess}">
      {/if}

      <strong>{if $user->UserName!=''}{$user->UserName}{else}{$user->Vorname} {$user->Nachname}{/if}</strong>

      {if cp_perm('user_edit')}</a>{/if}<br /><small>{$user->Email} (IP:{$user->IpReg})</small>
    </td>

    <td>
      {if $user->Status!=1}
        {#USER_STATUS_WAIT#}
      {else}
        <select name="Benutzergruppe[{$user->Id}]">
        {foreach from=$ugroups item=g}
          {if $g->Benutzergruppe!=2}
            <option value="{$g->Benutzergruppe}" {if $user->Id==1 && $g->Benutzergruppe!=1} disabled{else}{if $g->Benutzergruppe==$user->Benutzergruppe}selected{/if}{/if}>{$g->Name|escape:html}</option>
          {/if}
        {/foreach}
        </select>
      {/if}
    </td>

    <td class="time">
      {if $user->Status==1}
        {$user->ZuletztGesehen|date_format:$config_vars.USER_DATE_FORMAT} {#USER_IN#} {$user->ZuletztGesehen|date_format:$config_vars.USER_DATE_FORMAT2}
      {else}
        -
      {/if}
    </td>

    <td class="time">{$user->Registriert|date_format:$config_vars.USER_DATE_FORMAT} {#USER_IN#} {$user->Registriert|date_format:$config_vars.USER_DATE_FORMAT2}</td>

    <td nowrap="nowrap" width="1%" align="center">
      {if cp_perm('user_edit')}
        <a {popup sticky=false text=$config_vars.USER_EDIT} href="index.php?do=user&amp;action=edit&amp;Id={$user->Id}&amp;cp={$sess}">
        <img src="{$tpl_dir}/images/icon_edit.gif" alt="" border="0" /></a>
      {else}
        <img {popup sticky=false text=$config_vars.USER_NO_CHANGE} src="{$tpl_dir}/images/icon_edit_no.gif" alt="" border="0" />
      {/if}
    </td>

    <td nowrap="nowrap" width="1%" align="right">
      {if $user->Id != 1}
      {if cp_perm('user_loesch') && $user->Id!=$smarty.session.cp_benutzerid}
        <a {popup sticky=false text=$config_vars.USER_DELETE} onclick="return (confirm('{#USER_DELETE_CONFIRM#}'))" href="index.php?do=user&amp;action=delete&amp;Id={$user->Id}&amp;cp={$sess}">
        <img src="{$tpl_dir}/images/icon_delete.gif" alt="" border="0" /></a>
      {else}
        <img src="{$tpl_dir}/images/icon_delete_no.gif" alt="" border="0" />
      {/if}
      {else}
        <img src="{$tpl_dir}/images/icon_delete_no.gif" alt="" border="0" />
      {/if}
    </td>

      {if $user->IsShop==1}
	<td width="1%">
      {if $user->Orders >= 1}
        <a {popup sticky=false text=$config_vars.USER_ORDERS} href="javascript:void(0)" onclick="window.open('index.php?do=modules&action=modedit&mod=shop&moduleaction=showorders&cp={$sess}&search=1&Query={$user->Id}&start_Day=1&start_Month=1&start_Year=2005&pop=1','best','left=0,top=0,width=960,height=700,scrollbars=1,resizable=1');"><img hspace="2" src="{$tpl_dir}/images/icon_shop.gif" alt="" border="0" /></a>
      {else}
        <img hspace="2" src="{$tpl_dir}/images/icon_shop_no.gif" alt="" />
      {/if}
	</td>
	<td width="1%">
        <a {popup sticky=false text=$config_vars.USER_DOWNLOADS} href="javascript:void(0);" onclick="window.open('index.php?do=modules&action=modedit&mod=shop&moduleaction=shop_downloads&cp={$sess}&Id={$i.Id}&pop=1&User={$user->Id}&N={$user->Nachname}','sd','top=0,left=0,height=600,width=970,scrollbars=1');">
        <img hspace="2" src="{$tpl_dir}/images/icon_esd_download.gif" alt="" border="0" /></a>
    </td>
      {/if}

  </tr>
 {/foreach}
</table>
<br />

{if cp_perm('user_edit')}<input type="submit" class="button" value="{#USER_BUTTON_SAVE#}" />{/if}
</form>
<br />

{if $page_nav}
  <div class="infobox">{$page_nav} </div>
  <br />
{/if}
<br />

<div class="iconHelpSegmentBox">
  <div class="segmentBoxHeader">
    <div class="segmentBoxTitle">&nbsp;</div>
  </div>
  <div class="segmentBoxContent">
    <img class="absmiddle" src="{$tpl_dir}/images/arrow.gif" alt="" border="0" /> <strong>{#USER_LEGEND#}</strong><br />
    <br />
    <img class="absmiddle" src="{$tpl_dir}/images/icon_edit.gif" alt="" border="0" /> - {$config_vars.USER_EDIT}<br />
    <img class="absmiddle" src="{$tpl_dir}/images/icon_delete.gif" alt="" border="0" /> - {$config_vars.USER_DELETE}
  </div>
</div>
{/strip}