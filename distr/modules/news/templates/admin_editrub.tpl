<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#NEWS_MODULE_NAME#}</h2></div>
    <div class="HeaderText">{#NEWS_SUBRUB_TIPS#}</div>
</div>
<br />
<div class="infobox"><a href="index.php?do=modules&action=modedit&mod=news&moduleaction=1&cp={$sess}">{#NEWS_RUB#}</a>&nbsp;|&nbsp;<strong>{#NEWS_SUBRUB#}</strong></div>
<br />


<form action="" method="post">
<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
<tr class="tableheader">
<td width="10" align="center">{#NEWS_RUB_ID#}</td>
<td>{#NEWS_SUBRUB_TITLE#}</td>
<td width="65">{#NEWS_DOC_COUNTS#}</td>
<td colspan="3" width="2%" align="center">{#NEWS_ACTIONS#}</td>
</tr>
{foreach from=$rubs item=r}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
  <td align="center" class="itcen">{$r.id}</td>
  <td>
<strong>
	<a {popup sticky=false text=$config_vars.NEWS_SUBRUB_EDIT} href="index.php?do=modules&action=modedit&mod=news&moduleaction=editsubrub&id={$r.id}&cp={$sess}">{$r.name}</a>
</strong>
  </td>
  <td>
<strong>{$r.news}</strong>
  </td>
  <td>
<a {popup sticky=false text=$config_vars.NEWS_DOC_ADD} href="index.php?do=modules&action=modedit&mod=news&moduleaction=addnews&id={$r.id}&cp={$sess}"><img src="{$tpl_dir}/images/icon_add.gif" alt="" border="0" /></a>
  </td>
  <td>
<a {popup sticky=false text=$config_vars.NEWS_SUBRUB_EDIT} href="index.php?do=modules&action=modedit&mod=news&moduleaction=editsubrub&id={$r.id}&cp={$sess}"><img src="{$tpl_dir}/images/icon_edit.gif" alt="" border="0" /></a>
  </td>
  <td>
<a {popup sticky=false text=$config_vars.NEWS_SUBRUB_DEL} onclick="return confirm('{#NEWS_SUBRUB_DEL_CONFIRM#}');" href="index.php?do=modules&action=modedit&mod=news&moduleaction=delsubrub&id={$r.id}&cp={$sess}{if $smarty.request.page!=''}&page={$smarty.request.page}{/if}"><img src="{$tpl_dir}/images/icon_del.gif" alt="" border="0" /></a>
</td>
</tr>
{/foreach}
</table>
</form>
<br />
<p>{$page_nav}</p>

<h4>{#NEWS_SUBRUB_ADD#}</h4>
<form action="{$formaction}" method="post">
<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
<tr>
<td width="180" class="first">{#NEWS_SUBRUB_TITLE#}</td>
<td class="second">
  <input name="name" type="text" id="name" value="{$row.name|stripslashes|escape:html}" size="50" />&nbsp;<input type="submit" class="button" value="{#NEWS_BUTTON_ADD#}" />
</td>
</tr>
</table>
<input name="id" type="hidden" value="{$id}">

</form>

<br /><br />
      <div class="iconHelpSegmentBox">
        <div class="segmentBoxHeader"><div class="segmentBoxTitle">&nbsp;</div></div>
        <div class="segmentBoxContent">
<img class="absmiddle" src="{$tpl_dir}/images/arrow.gif" alt="" border="0" /> <strong>{#NEWS_LEGEND#}</strong>
<br /><br>
<img class="absmiddle" src="{$tpl_dir}/images/icon_add.gif" alt="" border="0" /> {$config_vars.NEWS_DOC_ADD}<br />
<img class="absmiddle" src="{$tpl_dir}/images/icon_edit.gif" alt="" border="0" /> {$config_vars.NEWS_SUBRUB_EDIT}<br />
<img class="absmiddle" src="{$tpl_dir}/images/icon_del.gif" alt="" border="0" /> {$config_vars.NEWS_SUBRUB_DEL}
          </div>
        </div>
      </div>