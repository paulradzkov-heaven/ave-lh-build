{if cp_perm("docs_comments")}

<div id="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_docs"></div>
    <div class="HeaderTitle"><h2>{#DOC_NOTICE#}</h2></div>
    <div class="HeaderText">{#DOC_NOTICE_NEW_LINK#}</div>
</div>
<div class="upPage"></div>
<br>

<table width="100%" border="0" cellpadding="8" cellspacing="1">
  {foreach from=$answers item=a}
  <tr>
    <td class="tableheader">{#DOC_NOTICE_AUTHOR#}{$a.Author} ({$a.Zeit|date_format:$config_vars.DOC_DATE_FORMAT} {#DOC_IN#} {$a.Zeit|date_format:$config_vars.DOC_DATE_FORMAT2})</td>
  </tr>
  
  <tr>
    <td style="line-height:1.3em" class="first"> 
      {if $a.Titel}<strong>{$a.Titel}</strong><br />{/if}
       <br />{$a.Kommentar} <br />
      {if cp_perm("docs_comments_del")}
      <div align="right">&raquo;&nbsp;<strong><a href="index.php?do=docs&action=del_comment&Id={$smarty.request.Id}&CId={$a.Id}&KommentarStart={$a.KommentarStart}&pop=1&cp={$sess}">{#DOC_NOTICE_DELETE_LINK#}</a></strong></div>
      {/if}
    </td>
  </tr>
  {/foreach}
</table>

{if cp_perm("comments_openlose")}
<form method="post" action="index.php?do=docs&action=openclose_discussion&Id={$smarty.request.Id}&pop=1&cp={$sess}">
  <div class="infobox">
    <input name="Aktiv" type="checkbox" id="Aktiv" value="1" {if $row_a.Aktiv==1}checked{/if} />
    {#DOC_ALLOW_NOTICE#}
    <input type="submit" class="button" value="{#DOC_BUTTON_NOTICE#}" />
  </div>
</form>
{/if}

{if $page_nav}
<div class="infobox">{$page_nav}</div>
{/if}
<p>&nbsp;</p>
{/if}

{if $reply==1}
{if $row_a.Aktiv==1 || $new ==1}
{include file='documents/replyform.tpl'}
{/if}
{/if}