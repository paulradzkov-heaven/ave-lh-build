<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#DOC_SUB_TITLE#}</h2></div>
    <div class="HeaderText">{#NEWS_DOC_TIPS#}</div>
</div>
<br />


<div class="infobox">
<a href="index.php?do=modules&action=modedit&mod=news&moduleaction=1&cp={$sess}">{#NEWS_RUB#}</a>&nbsp;|&nbsp;
<a href="index.php?do=modules&action=modedit&mod=news&moduleaction=editrub&id={$parent}&cp={$sess}">{#NEWS_SUBRUB#}</a>&nbsp;|&nbsp;
<strong>{#NEWS_DOC#}</strong>
</div>
<br />
<form>
<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
  <tr class="tableheader">
    <td width="10"><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Id'}IdDesc{else}Id{/if}">{#DOC_ID#}</a></td>
    <td width="10%"><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Url'}UrlDesc{else}Url{/if}">{#DOC_URL_RUB#}</a></td>    
    <td><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Titel'}TitelDesc{else}Titel{/if}">{#DOC_TITLE#}</a></td>
    <td width="11">&nbsp;</td>
{*	<td width="10%"><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Rubrik'}RubrikDesc{else}Rubrik{/if}">{#DOC_IN_RUBRIK#}</a></td> *}
    <td><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Erstellt'}ErstelltDesc{else}Erstellt{/if}">{#DOC_CREATED#}</a></td>
    <td><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='KlicksDesc'}Klicks{else}KlicksDesc{/if}">{#DOC_CLICKS#}</a></td>
    <td><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='DruckDesc'}Druck{else}DruckDesc{/if}">{#DOC_PRINTED#}</a></td>
    <td><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Autor'}AutorDesc{else}Autor{/if}">{#DOC_AUTHOR#}</a></td>
    <td colspan="4"><div align="center">{#DOC_ACTIONS#}</div></td>
  </tr>

  {foreach from=$docs item=item}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
    <td width="10" class="itcen">{$item->Id}</td>
    <td width="10">{$item->Url}</td>
    <td>
	  <strong>
	  {if $item->cantEdit==1}
	  <a {popup sticky=false text=$config_vars.DOC_EDIT_TITLE} href="index.php?do=modules&action=modedit&mod=news&moduleaction=editnews&RubrikId={$item->RubrikId}&Id={$item->Id}&cp={$sess}">{$item->Titel}</a>
	 {else}
	 {$item->Titel}
	 {/if}
	  </strong>
  </td>

	 <td width="11">
	{if $item->DokStart > $smarty.now}
	&nbsp;
	{elseif $item->DokEnde < $smarty.now && $item->DokEnde != '0'}
	&nbsp;
	 {else}
	 <a {popup sticky=false text=$config_vars.DOC_SHOW_TITLE} href="../index.php?id={$item->Id}&amp;cp={$sess}" target="_blank"><img src="{$tpl_dir}/images/icon_search.gif" alt="" border="0" /></a>
	{/if}
	</td>

{*	<td nowrap="nowrap">{$item->RubName|escape:html}</td> *}

	<td width="120">{$item->DokStart|date_format:$config_vars.DOC_DATE_FORMAT}<br /></td>

	<td width="1%"align="center">{$item->Geklickt}</td>

	<td width="1%"align="center">{$item->Drucke}</td>

	<td>{$item->RBenutzer}</td>

	<td nowrap="nowrap" width="1%" align="center">
	{if cp_perm("docs_comments")}
	{if $item->Kommentare=='0'}
		<a {popup sticky=false text=$config_vars.DOC_CREATE_NOTICE_TITLE} href="javascript:void(0);" onclick="cp_pop('index.php?do=docs&action=comment&RubrikId={$item->RubrikId}&Id={$item->Id}&pop=1&cp={$sess}','','','1','docs');"><img src="{$tpl_dir}/images/icon_comment.gif" alt="" border="0" /></a>
	{else}
		<a {popup sticky=false text=$config_vars.DOC_REPLY_NOTICE_TITLE} href="javascript:void(0);" onclick="cp_pop('index.php?do=docs&action=comment_reply&RubrikId={$item->RubrikId}&Id={$item->Id}&pop=1&cp={$sess}','','','1','docs');"><img src="{$tpl_dir}/images/icon_iscomment.gif" alt="" border="0" /></a>
	{/if}
	{else}
		&nbsp;
	{/if}
	</td>

	<td nowrap="nowrap" width="1%" align="center">
	{if $item->cantEdit==1}
		<a {popup sticky=false text=$config_vars.DOC_EDIT_TITLE} href="index.php?do=modules&action=modedit&mod=news&moduleaction=editnews&RubrikId={$item->RubrikId}&Id={$item->Id}&cp={$sess}"><img src="{$tpl_dir}/images/icon_edit.gif" alt="{#DOC_EDIT_TITLE#}" border="0" /></a>
	{else}
	&nbsp;
	{/if}
	</td>

	 <td nowrap="nowrap" width="1%" align="center">
{if $item->Geloescht==1}
&nbsp;
{else}

{if $item->DokStatus==1}
{if $item->canOpenClose==1 && $item->Id != 1 && $item->Id != 2}
	   <a {popup sticky=false text=$config_vars.DOC_DISABLE_TITLE} href="index.php?do=docs&action=close&RubrikId={$item->RubrikId}&Id={$item->Id}&cp={$sess}"><img src="{$tpl_dir}/images/icon_unlock.gif" alt="" border="0" /></a>
{else}
	   <img src="{$tpl_dir}/images/icon_unlock.gif" alt="" border="0" />
{/if}
{else}

{if $item->canOpenClose==1}
	   <a {popup sticky=false text=$config_vars.DOC_ENABLE_TITLE} href="index.php?do=docs&action=open&RubrikId={$item->RubrikId}&Id={$item->Id}&cp={$sess}"><img src="{$tpl_dir}/images/icon_lock.gif" alt="" border="0" /></a>
{else}
	   <img src="{$tpl_dir}/images/icon_lock.gif" alt="" border="0" />
{/if}
{/if}
{/if}
</td>

<td nowrap="nowrap" width="1%" align="right">
{if $item->Geloescht==1}
<a {popup sticky=false text=$config_vars.DOC_RESTORE_DELETE} href="index.php?do=docs&action=redelete&RubrikId={$item->RubrikId}&Id={$item->Id}&cp={$sess}"><img src="{$tpl_dir}/images/icon_active.gif" alt="" border="0" /></a>
{else}

{if $item->canDelete==1}
<a {popup sticky=false text=$config_vars.DOC_TEMPORARY_DELETE} onclick="return confirm('{#DOC_TEMPORARY_CONFIRM#}')"  href="index.php?do=docs&action=delete&RubrikId={$item->RubrikId}&Id={$item->Id}&cp={$sess}"><img src="{$tpl_dir}/images/icon_inactive.gif" alt="" border="0" /></a>
{else}
&nbsp;
{/if}
{/if}

{if $item->canEndDel==1 && $item->Id != 1 && $item->Id != 2}
<a {popup sticky=false text=$config_vars.DOC_FINAL_DELETE} onclick="return confirm('{#DOC_FINAL_CONFIRM#}')" href="index.php?do=docs&action=enddelete&RubrikId={$item->RubrikId}&Id={$item->Id}&cp={$sess}"><img src="{$tpl_dir}/images/icon_del.gif" alt="" hspace="2" border="0" /></a>
{/if}
</td>
    </tr>
 {/foreach}
</table>

<br />

{if $page_nav}
<div class="infobox">{$page_nav} </div>
{/if}
<br /><br />
</form>

<form action="index.php?do=modules&action=modedit&mod=news&moduleaction=addnews&id={$smarty.request.id}&cp={$sess}" method="post">
<input type="submit" class="button" value="{#NEWS_DOC_ADD#}" />
<br /><br />
</form>

<br /><br />
      <div class="iconHelpSegmentBox">
        <div class="segmentBoxHeader"><div class="segmentBoxTitle">&nbsp;</div></div>
        <div class="segmentBoxContent">
<img class="absmiddle" src="{$tpl_dir}/images/arrow.gif" alt="" border="0" /> <strong>{#DOC_LEGEND#}</strong>
<br /><br>
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
      </div>