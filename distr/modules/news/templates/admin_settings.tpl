<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#NEWS_MODULE_NAME#}</h2></div>
    <div class="HeaderText">{#NEWS_RUB_TIPS#}</div>
</div><br>

<h4>{#NEWS_RUB#}</h4>

<form action="" method="post">
<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
<tr class="tableheader">
<td width="10">{#NEWS_RUB_ID#}</td>
<td>{#NEWS_RUB_TITLE#}</td>
<td width="150">{#NEWS_SUBRUB_COUNTS#}</td>
<td width="65">{#NEWS_DOC_COUNTS#}</td>
<td colspan="2" width="2%">{#NEWS_ACTIONS#}</td>
</tr>
{foreach from=$rubs item=r}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
  <td class="itcen" align="center">{$r.id}</td>
  <td>
<strong>
{if $r.id > 1}
	<a {popup sticky=false text=$config_vars.NEWS_RUB_EDIT} href="index.php?do=modules&action=modedit&mod=news&moduleaction=editrub&id={$r.id}&cp={$sess}">{$r.name}</a>
{else}
	{$r.name}
{/if}
</strong>
  </td>
  <td>
<strong>{$r.subs}</strong>
  </td>
  <td>
<strong>{$r.news}</strong>
  </td>
  <td>
{if $r.id > 1}
<a {popup sticky=false text=$config_vars.NEWS_RUB_EDIT} href="index.php?do=modules&action=modedit&mod=news&moduleaction=editrub&id={$r.id}&cp={$sess}"><img src="{$tpl_dir}/images/icon_edit.gif" alt="" border="0" /></a>
{/if}
  </td>
  <td>
{if $r.id > 1}
<a {popup sticky=false text=$config_vars.NEWS_RUB_DEL} onclick="return confirm('{#NEWS_RUB_DEL_CONFIRM#}');" href="index.php?do=modules&action=modedit&mod=news&moduleaction=delrub&id={$r.id}&cp={$sess}{if $smarty.request.page!=''}&page={$smarty.request.page}{/if}"><img src="{$tpl_dir}/images/icon_del.gif" alt="" border="0" /></a>
{/if}
</td>
</tr>
{/foreach}
</table>
</form>
<p>{$page_nav}</p>

<h4>{#NEWS_RUB_ADD#}</h4>

<form action="{$formaction}" method="post">
<table width="100%" border="0" cellpadding="4" cellspacing="1" class="tableborder">
<tr>
<td width="180" class="first">{#NEWS_RUB_TITLE#}</td>
<td class="second">
  <input name="name" type="text" id="name" value="{$row.name|stripslashes|escape:html}" size="50" />&nbsp;<input type="submit" class="button" value="{#NEWS_BUTTON_ADD#}" />
</td>
</tr>
</table>
</form>
<h4>{#NEWS_DOC_SEARCH#}</h4>
<form action="index.php" method="get">
<table width="100%" border="0" cellpadding="4" cellspacing="1" class="tableborder">
<tr>
<td width="180" class="first">{#NEWS_RUB_ID#}</td>
<td class="second">
  <input name="do" type="hidden" value="modules">
  <input name="action" type="hidden" value="modedit">
  <input name="mod" type="hidden" value="news">
  <input name="moduleaction" type="hidden" value="editnews">
  <input name="RubrikId" type="hidden" value="2">
  <input name="pop" type="hidden" value="0">
  <input name="cp" type="hidden" value="{$sess}">
  <input name="Id" type="text" value="{$row.Id|stripslashes|escape:html}" />&nbsp;<input type="submit" class="button" value="{#NEWS_BUTTON_EDIT#}" />
</td>
</tr>
</table>
</form>
<br /><br />
      <div class="iconHelpSegmentBox">
        <div class="segmentBoxHeader"><div class="segmentBoxTitle">&nbsp;</div></div>
        <div class="segmentBoxContent">
<img class="absmiddle" src="{$tpl_dir}/images/arrow.gif" alt="" border="0" /> <strong>{#NEWS_LEGEND#}</strong>
<br /><br>
<img class="absmiddle" src="{$tpl_dir}/images/icon_edit.gif" alt="" border="0" /> {$config_vars.NEWS_RUB_EDIT}<br />
<img class="absmiddle" src="{$tpl_dir}/images/icon_del.gif" alt="" border="0" /> {$config_vars.NEWS_RUB_DEL}
          </div>
        </div>
      </div>