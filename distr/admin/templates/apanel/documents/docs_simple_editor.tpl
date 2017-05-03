<div id="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_docs"></div>
    <div class="HeaderTitle"><h2>{#DOC_SUB_TITLE#}</h2></div>
    <div class="HeaderText">{#DOC_INSERT_LINK_TIP#}</div>
</div>
<div class="upPage"></div>
<script language="javascript" type="text/javascript">
  function insertLink(target,value) {ldelim}
    window.opener.document.getElementById(target).value = value;
    window.close();
{rdelim}
function selectPage(){ldelim}
	var page=document.getElementById('page');
	location.href = "{$formaction_script}&page="+page.options[page.selectedIndex].value;
{rdelim}
</script>
<form action="{$formaction}" method="post">
          <table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
            <tr>
              <td class="second">{#MAIN_ADD_IN#}&nbsp;
                <select style="width:45%;" name="RubrikId" id="RubrikId">
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
<form action="{$formaction}" method="post">
          <table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
            <tr>
              <td class="second">{#MAIN_TITLE_SEARCH#}&nbsp;
              	<input style="width:150px" type="text" name="QueryTitel" value="{$smarty.request.QueryTitel|escape:html|stripslashes}" />&nbsp;
                 <input style="width:85px" type="submit" class="button" value="{#MAIN_BUTTON_SEARCH#}" />
              </td>
            </tr>
          </table>
</form>
<br/>
<form enctype="multipart/form-data">
<table width="100%" border="0" cellpadding="6" cellspacing="1" class="tableborder">
  <tr>
    <td width="10" class="tableheader"><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Id'}IdDesc{else}Id{/if}">{#DOC_ID#}</a></td>
	<td width="11" class="tableheader">&nbsp;</td>
    <td class="tableheader"><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Url'}UrlDesc{else}Url{/if}">{#DOC_URL_TITLE#}</a></td>
    <td class="tableheader"><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Titel'}TitelDesc{else}Titel{/if}">{#DOC_TITLE#}</a></td>
    
    <td width="10%" class="tableheader"><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Rubrik'}RubrikDesc{else}Rubrik{/if}">{#DOC_IN_RUBRIK#}</a></td>
    <td class="tableheader">&nbsp;</td>
  </tr>
  
  {foreach from=$docs item=item}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
    <td width="10" class="itcen">{$item->Id}</td>
    <td width="11">
      {if $item->DokStart > $smarty.now}
        &nbsp;
      {elseif $item->DokEnde < $smarty.now && $item->DokEnde != '0'}
        &nbsp;
      {else}
        <a {popup sticky=false text=$config_vars.DOC_SHOW_TITLE} href="../index.php?id={$item->Id}&amp;cp={$sess}" target="_blank"><img src="{$tpl_dir}/images/icon_search.gif" alt="" border="0" /></a>
      {/if}
    </td>
	<td width="10%" nowrap="nowrap">{$item->Url}</td>
    <td>
      <strong><a href="javascript:void(0);" onclick="insertLink('{$smarty.request.target}','{$item->Url}','{$item->Id}','{$smarty.request.doc}');">{$item->Titel}</a></strong>
    </td>
  
    <td nowrap="nowrap">
      {$item->RubName|escape:html}
    </td>
    
    <td nowrap="nowrap" width="1%" align="right">

        <input onclick="insertLink('{$smarty.request.target}','{$item->Url}','{$item->Id}','{$smarty.request.doc}');" class="button" type="button" value="{#DOC_BUTTON_INSERT_LINK#}" />

    </td>
  </tr>
 {/foreach}
</table>

<br />

{if $page_nav}
  <div class="infobox">{$page_nav}&nbsp;{$select}</div>
{/if}
<br /><br />
</form>


