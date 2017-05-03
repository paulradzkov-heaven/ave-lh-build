<div id="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_docs"></div>
    <div class="HeaderTitle"><h2>{#DOC_SUB_TITLE#}</h2></div>
    <div class="HeaderText">{#DOC_INSERT_LINK_TIP#}</div>
</div>
<div class="upPage"></div>
<script language="javascript" type="text/javascript">
  function insertLink(target,value,doc,doctarget) {ldelim}
    window.opener.document.getElementById(target).value = value;
    window.opener.document.getElementById(doctarget).value = doc;
    window.close();
{rdelim}
</script>
<br>
<form enctype="multipart/form-data">
<table width="100%" border="0" cellpadding="6" cellspacing="1" class="tableborder">
  <tr>
    <td width="10" class="tableheader"><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Id'}IdDesc{else}Id{/if}">{#DOC_ID#}</a></td>
    <td class="tableheader"><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Url'}UrlDesc{else}Url{/if}">{#DOC_URL_TITLE#}</a></td>
    <td class="tableheader"><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Titel'}TitelDesc{else}Titel{/if}">{#DOC_TITLE#}</a></td>
    <td width="11" class="tableheader">&nbsp;</td>
    <td width="10%" class="tableheader"><a class="header" href="{redir}&amp;sort={if $smarty.request.sort=='Rubrik'}RubrikDesc{else}Rubrik{/if}">{#DOC_IN_RUBRIK#}</a></td>
    <td class="tableheader">&nbsp;</td>
  </tr>
  
  {foreach from=$docs item=item}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
    <td width="10" class="itcen">{$item->Id}</td><td width="10">{$item->Url}</td>
    <td>
      <strong>{$item->Titel}</strong>
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
  
    <td nowrap="nowrap">
      {$item->RubName|escape:html}
    </td>
    
    <td nowrap="nowrap" width="1%" align="right">
      {if $smarty.request.idonly==1}
        <input onclick="insertLink('{$smarty.request.target}','{$item->Url}','{$item->Id}','{$smarty.request.doc}');" class="button" type="button" value="{#DOC_BUTTON_INSERT_LINK#}" />
      {else}
        <input onclick="insertLink('{$smarty.request.target}','{$item->Url}','{$item->Titel}','{$smarty.request.doc}');" class="button" type="button" value="{#DOC_BUTTON_INSERT_LINK#}" />
        <input onclick="insertLink('{$smarty.request.target}','javascript:popup(\'{$item->Url}\',\'{$item->Titel}\',\'800\',\'700\')','{$item->Titel}','{$smarty.request.doc}');" class="button" type="button" value="{#DOC_BUTTON_LINK_POPUP#}" />
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


