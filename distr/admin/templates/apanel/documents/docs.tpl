{strip}
<div class="pageHeaderTitle" style="padding-top: 7px;">
  <div class="h_docs"></div>
  <div class="HeaderTitle"><h2>{#DOC_SUB_TITLE#}</h2></div>
  <div class="HeaderText">{#DOC_TIPS#}</div>
</div><br />
<br />

{if cp_perm('docs')}
  <script type="text/javascript" language="JavaScript">
   function check_name() {ldelim}
     if (document.getElementById('DocName').value == '') {ldelim}
       alert("{#DOC_ENTER_NAME#}");
       document.getElementById('DocName').focus();
       return false;
     {rdelim}
     return true;
   {rdelim}
  </script>

  <table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
      <td width="50%"><h4>{#MAIN_ADD_IN_RUB#}</h4></td>
      <td width="50%"><h4>{#MAIN_SORT_DOCUMENTS#}</h4></td>
    </tr>
    <tr>
      <td>
        <form action="index.php" method="get" onSubmit="return check_name();">
          <input type="hidden" name="do" value="docs" />
          <input type="hidden" name="action" value="new" />
          <input type="hidden" name="cp" value="{$sess}" />
          <table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
            <tr>
              <td class="second">{#MAIN_ADD_IN#}&nbsp;
                <select style="width:45%" name="Id" id="DocName">
                  <option value=""></option>
                  {foreach from=$rub_items item=rfn}
                    {if $rfn->Show==1}
                      <option value="{$rfn->Id}" {if $smarty.request.Id==$rfn->Id && $smarty.request.action=='new'}selected{/if}>{$rfn->RubrikName|escape:html}</option>
                    {/if}
                  {/foreach}
                </select>&nbsp;
                <input style="width:85px" type="submit" class="button" value="{#MAIN_BUTTON_ADD#}" />
              </td>
            </tr>
          </table>
        </form>
      </td>
      <td>
        <form action="index.php" method="get">
          <input type="hidden" name="cp" value="{$sess}" />
          <input type="hidden" name="do" value="docs" />
          <table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
            <tr>
              <td class="second">{#MAIN_ADD_IN#}&nbsp;
                <select style="width:45%;" name="RubrikId" id="RubrikSort">
                  <option value="all">{#MAIN_ALL_RUBRUKS#}</option>
                  {foreach from=$rub_items item=rfn}
                    {if $rfn->Show==1}
                      <option value="{$rfn->Id}"{if $smarty.request.RubrikId==$rfn->Id}selected{/if}>{$rfn->RubrikName|escape:html}</option>
                    {/if}
                  {/foreach}
                </select>&nbsp;
                <input style="width:85px" type="submit" class="button" value="{#MAIN_BUTTON_SORT#}" />
              </td>
            </tr>
          </table>
        </form>
      </td>
    </tr>
  </table>
{/if}

<h4>{#MAIN_SEARCH_DOCUMENTS#}</h4>
<form method="get" action="index.php">
  <input type="hidden" name="do" value="docs" />
  <input type="hidden" name="cp" value="{$sess}" />
  <input type="hidden" name="TimeSelect" value="1" />
  <table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
    <tr class="first">
      <td rowspan="2" class="second"><strong>{#MAIN_TIME_PERIOD#}</strong></td>
      {if $sel_start != ''}
        {assign var=ZeitStart value=$sel_start}
        {assign var=ZeitEnde value=$sel_ende}
      {else}
        {assign var=ZeitStart value=$ddstartyear}
        {assign var=ZeitEnde value=$ddendyear}
      {/if}
      <td>
        {html_select_date time=$ZeitStart prefix="DokStart" end_year="+10" start_year="-10" display_days=true month_format="%B" reverse_years=false day_size=1 field_order=DMY month_extra="style='width:80px'" all_extra=""}
      </td>
      <td class="second"><strong>{#MAIN_TITLE_SEARCH#}</strong></td>
      <td><input style="width:150px" type="text" name="QueryTitel" value="{$smarty.request.QueryTitel|escape:html|stripslashes}" /> <input style="cursor:help" {popup width=400 sticky=false text=$lang_vars.MAIN_SEARCH_HELP} type="button" class="button" value="?" /></td>
      <td class="second"><strong>{#MAIN_SELECT_RUBRIK#}</strong></td>
      <td>
        <select name="RubrikId" style="width:185px">
          <option value="all">{#MAIN_ALL_RUBRUKS#}</option>
          {foreach from=$rub_items item=rfn}
            <option value="{$rfn->Id}" {if $smarty.request.RubrikId==$rfn->Id}selected{/if}>{$rfn->RubrikName|escape:html}</option>
          {/foreach}
        </select>
      </td>
    </tr>

    <tr class="first">
      <td>
       {html_select_date time=$ZeitEnde prefix="DokEnde" end_year="+30" start_year="-10" display_days=true month_format="%B" reverse_years=false day_size=1 field_order=DMY month_extra="style='width:80px'" all_extra=""}
      </td>
      <td class="second"><strong>{#MAIN_ID_SEARCH#}</strong></td>
      <td><input style="width:160px" type="text" name="doc_id" value="{$smarty.request.doc_id|escape:html|stripslashes}" /></td>
      <td class="second"><strong>{#MAIN_DOCUMENT_STATUS#}</strong></td>
      <td>
        <select style="width:185px" name="DokStatus">
          <option value="All">{#MAIN_ALL_DOCUMENTS#}</option>
          <option value="Opened" {if $smarty.request.DokStatus=='Opened'}selected{/if}>{#MAIN_DOCUMENT_ACTIVE#}</option>
          <option value="Closed" {if $smarty.request.DokStatus=='Closed'}selected{/if}>{#MAIN_DOCUMENT_INACTIVE#}</option>
          <option value="Deleted" {if $smarty.request.DokStatus=='Deleted'}selected{/if}>{#MAIN_TEMP_DELETE_DOCS#}</option>
          <option value="Outtime"{if $smarty.request.DokStatus=='Outtime'}selected{/if}>{#MAIN_DOCUMENT_OUTTIME#}</option>
        </select>
      </td>
    </tr>

    <tr class="first">
      <td colspan="6" class="second"><strong>{#MAIN_RESULTS_ON_PAGE#}</strong>&nbsp;
        <select style="width:95px" name="Datalimit">
          {section loop=150 name=dl step=15}
            <option value="{$smarty.section.dl.index+10}" {if $smarty.request.Datalimit==$smarty.section.dl.index+10}selected{/if}>{$smarty.section.dl.index+10}</option>
          {/section}
        </select>&nbsp;
        <input style="width:85px" type="submit" class="button" value="{#MAIN_BUTTON_SEARCH#}" />
      </td>
    </tr>
  </table>
</form>

<h4>{#MAIN_DOCUMENTS_ALL#}</h4>
<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
  <tr class="tableheader">
    <td width="10"><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Id'}IdDesc{else}Id{/if}">{#DOC_ID#}</a></td>
    <td width="10">&nbsp;</td>
    <td><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Titel'}TitelDesc{else}Titel{/if}">{#DOC_TITLE#}</a>&nbsp;|&nbsp;<a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Url'}UrlDesc{else}Url{/if}">{#DOC_URL_RUB#}</a></td>
    <td width="10%"><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Rubrik'}RubrikDesc{else}Rubrik{/if}">{#DOC_IN_RUBRIK#}</a></td>
    <td width="11">&nbsp;</td>
    <td><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Erstellt'}ErstelltDesc{else}Erstellt{/if}">{#DOC_CREATED#}</a></td>
    <td><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='KlicksDesc'}Klicks{else}KlicksDesc{/if}">{#DOC_CLICKS#}</a></td>
    <td><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='DruckDesc'}Druck{else}DruckDesc{/if}">{#DOC_PRINTED#}</a></td>
    <td><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Autor'}AutorDesc{else}Autor{/if}">{#DOC_AUTHOR#}</a></td>
    <td colspan="5" align="center">{#DOC_ACTIONS#}</td>
  </tr>

{foreach from=$docs item=item}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
    <td width="10" class="itcen">{$item->Id}</td>

    <td width="10">
      {if $item->DokStart > $smarty.now || ($item->DokEnde < $smarty.now && $item->DokEnde != '0')}
        <img src="{$tpl_dir}/images/icon_blank.gif" alt="" border="0" />
      {else}
        <a {popup sticky=false text=$config_vars.DOC_SHOW_TITLE} href="../index.php?id={$item->Id}&amp;cp={$sess}" target="_blank"><img src="{$tpl_dir}/images/icon_look.gif" alt="" border="0" /></a>
      {/if}
    </td>

    <td>
      <strong>
        {if $item->cantEdit==1}
          <a {popup sticky=false text=$config_vars.DOC_EDIT_TITLE} href="index.php?do=docs&action=edit&RubrikId={$item->RubrikId}&Id={$item->Id}&cp={$sess}">{$item->Titel}</a>
		  <br />
		  <div class="url">{$item->Url}</div>
        {else}
          {$item->Titel}
        {/if}
      </strong>
    </td>

    <td nowrap="nowrap">{$item->RubName|escape:html}</td>

    <td width="1%">
      {if $item->cantEdit==1}
        <a {popup sticky=false text=$config_vars.DOC_EDIT_RUB} href="javascript:void(0);" onclick="cp_pop('index.php?do=docs&action=change&Id={$item->Id}&RubrikId={$item->RubrikId}&pop=1&cp={$sess}','550','550','1','docs');"><img src="{$tpl_dir}/images/icon_rubrik_change.gif" alt="" border="0" /></a>
      {else}
        <img src="{$tpl_dir}/images/icon_rubrik_change_no.gif" alt="" border="0" />
      {/if}
    </td>

    <td class="time" nowrap="nowrap" width="1%" align="center">{$item->DokStart|date_format:$config_vars.DOC_DATE_FORMAT} {#DOC_IN#} {$item->DokStart|date_format:$config_vars.DOC_DATE_FORMAT2}<br /></td>

    <td width="1%" align="center">{$item->Geklickt}</td>

    <td width="1%" align="center">{$item->Drucke}</td>

    <td nowrap="nowrap" width="1%" align="center">{$item->RBenutzer}</td>

    <td nowrap="nowrap" width="1%" align="center">
      {if cp_perm("docs_comments")}
        {if $item->Kommentare=='0'}
          <a {popup sticky=false text=$config_vars.DOC_CREATE_NOTICE_TITLE} href="javascript:void(0);" onclick="cp_pop('index.php?do=docs&action=comment&RubrikId={$item->RubrikId}&Id={$item->Id}&pop=1&cp={$sess}','800','700','1','docs');"><img src="{$tpl_dir}/images/icon_comment.gif" alt="" border="0" /></a>
        {else}
          <a {popup sticky=false text=$config_vars.DOC_REPLY_NOTICE_TITLE} href="javascript:void(0);" onclick="cp_pop('index.php?do=docs&action=comment_reply&RubrikId={$item->RubrikId}&Id={$item->Id}&pop=1&cp={$sess}','800','700','1','docs');"><img src="{$tpl_dir}/images/icon_iscomment.gif" alt="" border="0" /></a>
        {/if}
      {else}
        <img src="{$tpl_dir}/images/icon_comment_no.gif" alt="" border="0" />
      {/if}
    </td>

    <td nowrap="nowrap" width="1%" align="center">
      {if $item->cantEdit==1}
        <a {popup sticky=false text=$config_vars.DOC_EDIT_TITLE} href="index.php?do=docs&action=edit&RubrikId={$item->RubrikId}&Id={$item->Id}&cp={$sess}"><img src="{$tpl_dir}/images/icon_edit.gif" alt="{#DOC_EDIT_TITLE#}" border="0" /></a>
      {else}
        <img src="{$tpl_dir}/images/icon_edit_no.gif" alt="" border="0" />
      {/if}
    </td>

    <td nowrap="nowrap" width="1%" align="center">
      {if $item->Geloescht==1}
        <img src="{$tpl_dir}/images/icon_blank.gif" alt="" border="0" />
      {else}
        {if $item->DokStatus==1}
          {if $item->canOpenClose==1 && $item->Id != 1 && $item->Id != 2}
            <a {popup sticky=false text=$config_vars.DOC_DISABLE_TITLE} href="index.php?do=docs&action=close&RubrikId={$item->RubrikId}&Id={$item->Id}&cp={$sess}"><img src="{$tpl_dir}/images/icon_unlock.gif" alt="" border="0" /></a>
          {else}
            {if $item->cantEdit==1 && $item->Id != 1 && $item->Id != 2}
              <img src="{$tpl_dir}/images/icon_unlock_no.gif" alt="" border="0" />
            {else}
              <img src="{$tpl_dir}/images/icon_unlock_no.gif" alt="" border="0" />
            {/if}
          {/if}
        {else}
          {if $item->canOpenClose==1}
            <a {popup sticky=false text=$config_vars.DOC_ENABLE_TITLE} href="index.php?do=docs&action=open&RubrikId={$item->RubrikId}&Id={$item->Id}&cp={$sess}"><img src="{$tpl_dir}/images/icon_lock.gif" alt="" border="0" /></a>
          {else}
            {if $item->cantEdit==1 && $item->Id != 1 && $item->Id != 2}
              <img src="{$tpl_dir}/images/icon_lock.gif" alt="" border="0" />
            {else}
              <img src="{$tpl_dir}/images/icon_lock_no.gif" alt="" border="0" />
            {/if}
          {/if}
        {/if}
      {/if}
    </td>

    <td nowrap="nowrap" width="1%" align="center">
      {if $item->Geloescht==1}
        <a {popup sticky=false text=$config_vars.DOC_RESTORE_DELETE} href="index.php?do=docs&action=redelete&RubrikId={$item->RubrikId}&Id={$item->Id}&cp={$sess}"><img src="{$tpl_dir}/images/icon_active.gif" alt="" border="0" /></a>
      {else}
        {if $item->canDelete==1}
          <a {popup sticky=false text=$config_vars.DOC_TEMPORARY_DELETE} onclick="return confirm('{#DOC_TEMPORARY_CONFIRM#}')"  href="index.php?do=docs&action=delete&RubrikId={$item->RubrikId}&Id={$item->Id}&cp={$sess}"><img src="{$tpl_dir}/images/icon_inactive.gif" alt="" border="0" /></a>
        {else}
          <img src="{$tpl_dir}/images/icon_active_no.gif" alt="" border="0" />
        {/if}
      {/if}
    </td>

    <td nowrap="nowrap" width="1%" align="center">
      {if $item->canEndDel==1 && $item->Id != 1 && $item->Id != 2}
        <a {popup sticky=false text=$config_vars.DOC_FINAL_DELETE} onclick="return confirm('{#DOC_FINAL_CONFIRM#}')" href="index.php?do=docs&action=enddelete&RubrikId={$item->RubrikId}&Id={$item->Id}&cp={$sess}"><img src="{$tpl_dir}/images/icon_delete.gif" alt="" border="0" /></a>
      {else}
        <img src="{$tpl_dir}/images/icon_delete_no.gif" alt="" border="0" />
      {/if}
    </td>

  </tr>
{/foreach}
</table>
<br />

{if $page_nav}
<div class="infobox">{$page_nav} </div>
{/if}
<br />

<div class="iconHelpSegmentBox">
  <div class="segmentBoxHeader">
    <div class="segmentBoxTitle">&nbsp;</div>
  </div>
  <div class="segmentBoxContent">
    <img class="absmiddle" src="{$tpl_dir}/images/arrow.gif" alt="" border="0" /> <strong>{#DOC_LEGEND#}</strong><br />
    <br />
    <img class="absmiddle" src="{$tpl_dir}/images/icon_edit.gif" alt="" border="0" /> - {$config_vars.DOC_LEGEND_EDIT}<br />
    <img class="absmiddle" src="{$tpl_dir}/images/icon_look.gif" alt="" border="0" /> - {$config_vars.DOC_LEGEND_SHOW}<br />
    <img class="absmiddle" src="{$tpl_dir}/images/icon_rubrik_change.gif" alt="" border="0" /> - {$config_vars.DOC_EDIT_RUB}<br />
    <img class="absmiddle" src="{$tpl_dir}/images/icon_comment.gif" alt="" border="0" /> - {$config_vars.DOC_CREATE_NOTICE_TITLE}<br />
    <img class="absmiddle" src="{$tpl_dir}/images/icon_iscomment.gif" alt="" border="0" /> - {$config_vars.DOC_REPLY_NOTICE_TITLE}<br />
    <img class="absmiddle" src="{$tpl_dir}/images/icon_lock.gif" alt="" border="0" /> - {$config_vars.DOC_LEGEND_ENABLE}<br />
    <img class="absmiddle" src="{$tpl_dir}/images/icon_unlock.gif" alt="" border="0" /> - {$config_vars.DOC_LEGEND_DISABLE}<br />
    <img class="absmiddle" src="{$tpl_dir}/images/icon_inactive.gif" alt="" border="0" /> - {$config_vars.DOC_LEGEND_TEMP_DELETE}<br />
    <img class="absmiddle" src="{$tpl_dir}/images/icon_active.gif" alt="" border="0" /> - {$config_vars.DOC_LEGEND_RESTORE}<br />
    <img class="absmiddle" src="{$tpl_dir}/images/icon_delete.gif" alt="" border="0" /> - {$config_vars.DOC_LEGEND_FINAL_DELETE}
  </div>
</div>
{/strip}