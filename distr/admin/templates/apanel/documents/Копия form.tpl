{strip}
<div id="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
{if $smarty.request.action=='edit'}
  <div class="HeaderTitle"><h2>{#DOC_EDIT_DOCUMENT#}<span style="color: #000;"> &gt; {$row_doc->Titel}</span></h2></div>
{else}
  <div class="HeaderTitle"><h2>{#DOC_ADD_DOCUMENT#}</h2></div>
{/if}
<div class="HeaderText"><strong>Рубрика</strong> &gt; {$RubName}</div>
</div>
<div class="upPage"></div>

{/strip}
<script language="javascript">
<!--
function insertHTML(ed, code) {ldelim}
  document.getElementById('feld['+ed+']___Frame').contentWindow.FCK.InsertHtml(code);
{rdelim}

function clickUrlCheck () {ldelim}
  $.post('/admin/functions/func.checkUrl.php',{ldelim}url: $("input[@name=Url]").val(){rdelim},function(txt){ldelim}$('span.checkUrlInfo').html(txt);{rdelim});
{rdelim}

function insert_now_date(now_date) {ldelim}
  document.getElementById(now_date).value = "{$now_date}";
  document.getElementById(now_date).focus();
{rdelim}

  function openLinkWin(target,doc) {ldelim}
    if (typeof width=='undefined' || width=='') var width = screen.width * 0.6;
    if (typeof height=='undefined' || height=='') var height = screen.height * 0.6;
    if (typeof doc=='undefined') var doc = 'Titel';
    if (typeof scrollbar=='undefined') var scrollbar=1;
    window.open('index.php?doc='+doc+'&target='+target+'&do=docs&action=showsimple_edit&cp={$sess}&pop=1','pop','left=0,top=0,width='+width+',height='+height+',scrollbars='+scrollbar+',resizable=1');
  {rdelim}

  function openFileWin(target,id) {ldelim}
    if (typeof width=='undefined' || width=='') var width = screen.width * 0.6;
    if (typeof height=='undefined' || height=='') var height = screen.height * 0.6;
    if (typeof doc=='undefined') var doc = 'Titel';
    if (typeof scrollbar=='undefined') var scrollbar=1;
    window.open('browser.php?id='+id+'&typ=bild&mode=fck&target=navi&cp={$sess}','pop','left=0,top=0,width='+width+',height='+height+',scrollbars='+scrollbar+',resizable=1');
  {rdelim}
//-->
</script>

{strip}
{*
{if $smarty.request.action=='edit'}
<script language="javascript">
<!--
function highlight_feld() {ldelim}
  var feld = "{$smarty.request.feld}";
  document.getElementById('feld_' + feld).style.border = "2px solid red";
  document.getElementById('feld_' + feld).style.font = "120% verdana,arial";
  document.getElementById('feld_' + feld).style.background = "#ffffff";
{rdelim}
//-->
</script>
<body onLoad="highlight_feld();">
{/if}
*}
<form method="post" name="formDocOption" action="/admin/{$formaction}" enctype="multipart/form-data">
  <table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
    <tr>
      <td colspan="3" class="tableheader">{#DOC_OPTIONS#}</td>
    </tr>

    <tr>
      <td width="225" class="first">{#DOC_NAME#}</td>
      <td class="second"><input name="Titel" type="text" id="Titel" size="40" style="width: 275px" value="{$row_doc->Titel}" /></td>
      <td rowspan="10" valign="top" class="first">
	  <h4>{#DOC_QUERIES#}</h4>
        <div style="width:99%; overflow:auto; height:200px">
        {foreach from=$conditions item=cond}
          <a onClick="cp_pop('index.php?do=queries&action=edit&RubrikId={$cond->RubrikId}&Id={$cond->Id}&pop=1&cp={$sess}','750','600','1','cond')" {popup sticky=false text=$cond->Beschreibung|stripslashes|default:'Для данного запроса описания нет.'|escape:html} href="javascript:void(0);">{$cond->Titel|escape:html}</a>&nbsp;-&nbsp;<input type="text" readonly="" style="width: 80px;" class="query" value="[cprequest:{$cond->Id}]"><br />
        {/foreach}
        </div>
      </td>
    </tr>
    <tr>
      <td width="225" class="first">{#DOC_URL#}</td>
      <td class="second" nowrap><input name="Url" type="text" id="Url" size="40" style="width:275px" value="{if $row_doc->Url}{$row_doc->Url}{else}{$autoUrl}{/if}" />&nbsp;
        <input {popup width=350 sticky=false text=$config_vars.DOC_URL_INFO|default:''} class="button" style="cursor:help" type="button" value="?" /><br />
        &raquo;&nbsp;<a href="javascript:;" onclick="clickInsertTitleUrl(); return false;">{#DOC_TRANSLIT#}</a>&nbsp;&nbsp;&nbsp;
        <span class="checkUrlInfo">&raquo;&nbsp;<a href="javascript:;" onclick="clickUrlCheck(); return false;">{#DOC_CHECK_URL#}</a></span>
      </td>
    </tr>

    <tr>
      <td width="225" class="first">{#DOC_META_KEYWORDS#}</td>
      <td class="second"><input name="MetaKeywords" type="text" id="MetaKeywords" size="40" value="{$row_doc->MetaKeywords}" style="width: 275px" />&nbsp;
        <input {popup width=350 sticky=false text=$config_vars.DOC_META_KEYWORDS_INFO|default:''} class="button" style="cursor:help" type="button" value="?" />
      </td>
    </tr>

    <tr>
      <td width="225" class="first">{#DOC_META_DESCRIPTION#}</td>
      <td class="second"><input name="MetaDescription" type="text" id="MetaDescription" size="40" style="width: 275px" value="{$row_doc->MetaDescription}" maxlength="170" />&nbsp;
        <input {popup width=350 sticky=false text=$config_vars.DOC_META_DESCRIPTION_INFO|default:''} class="button" style="cursor:help" type="button" value="?" />
      </td>
    </tr>

    <tr>
      <td width="225" class="first">{#DOC_CAN_SEARCH#}</td>
      <td class="second"><input name="Suche" type="checkbox" id="Suche" value="1" {if $row_doc->Suche==1 || $smarty.request.action=='new'}checked{/if} /></td>
    </tr>

    <tr>
      <td width="225" class="first">{#DOC_INDEX_TYPE#}</td>
      <td class="second">
        <select style="width:300px" name="IndexFollow" id="IndexFollow">
          <option value="index,follow" {if $row_doc->IndexFollow=='index,follow'}selected{/if}>{#DOC_INDEX_FOLLOW#}</option>
          <option value="index,nofollow" {if $row_doc->IndexFollow=='index,nofollow'}selected{/if}>{#DOC_INDEX_NOFOLLOW#}</option>
          <option value="noindex,nofollow" {if $row_doc->IndexFollow=='noindex,nofollow'}selected{/if}>{#DOC_NOINDEX_NOFOLLOW#}</option>
        </select>
      </td>
    </tr>

    <tr>
      <td class="first">{#DOC_START_PUBLICATION#}</td>
      <td class="second">
      {if ($smarty.request.Id==1 || $smarty.request.Id==2) && ($smarty.request.action != 'new')}
        {assign var=extra value="disabled"}
      {else}
        {assign var=extra value=""}
      {/if}

      {html_select_date time=$row_doc->DokStart prefix="DokStart" start_year="-10" end_year="+10" display_days=true month_format="%B" reverse_years=false day_size=1 field_order=DMY all_extra=$extra}
      &nbsp;-&nbsp;
      {html_select_time prefix=Start time=$row_doc->DokStart display_seconds=false use_24_hours=true all_extra=$extra}
      </td>
    </tr>

    <tr>
      <td class="first">{#DOC_END_PUBLICATION#}</td>
      <td class="second">
      {if $row_doc->DokEnde<1}
        {assign var=DENDE value=$ddendyear}
      {else}
        {assign var=DENDE value=$row_doc->DokEnde}
      {/if}

      {html_select_date time=$DENDE prefix="DokEnde" start_year="-10" end_year="+30" display_days=true month_format="%B" reverse_years=false day_size=1 field_order=DMY year_empty="" month_empty="" day_empty="" all_extra=$extra}
      &nbsp;-&nbsp;
      {html_select_time prefix=Ende time=$DENDE display_seconds=false use_24_hours=true all_extra=$extra}
      </td>
    </tr>

    <tr>
      <td class="first">{#DOC_STATUS#}</td>
      <td class="second">
      {if $smarty.request.action == 'new'}
      {if  $row_doc->dontChangeStatus==1}
        {assign var=sel_1 value=''}
        {assign var=sel_2 value='selected="selected"'}
      {else}
        {assign var=sel_1 value='selected="selected"'}
        {assign var=sel_2 value=''}
      {/if}

      {else}

      {if  $row_doc->dontChangeStatus==1}
      {if $row_doc->DokStatus==1}
        {assign var=sel_1 value='selected="selected"'}
        {assign var=sel_2 value=''}
      {else}
        {assign var=sel_1 value=''}
        {assign var=sel_2 value='selected="selected"'}
      {/if}

      {else}

      {if $row_doc->DokStatus==1}
        {assign var=sel_1 value='selected="selected"'}
        {assign var=sel_2 value=''}
      {else}
        {assign var=sel_1 value=''}
        {assign var=sel_2 value='selected="selected"'}
      {/if}
      {/if}
      {/if}
        <select style="width:300px" name="DokStatus" id="DokStatus" {if $row_doc->dontChangeStatus==1}disabled="disabled"{/if}>
          <option value="1" {$sel_1}>{#DOC_STATUS_ACTIVE#}</option>
          <option value="0" {$sel_2}>{#DOC_STATUS_INACTIVE#}</option>
        </select>
      </td>
    </tr>

    <tr>
      <td width="225" class="first">{#DOC_USE_NAVIGATION#} </td>
      <td class="second">
        {include file='navigation/tree.tpl'}
        <input {popup width=300 sticky=false text=$config_vars.DOC_NAVIGATION_INFO|default:''} class="button" style="cursor:help" type="button" value="?" />
      </td>
    </tr>

    <tr>
      <td colspan="3" class="tableheader">{#DOC_MAIN_CONTENT#}</td>
    </tr>

    {foreach from=$items item=df}
    <tr>
      <td width="225" class="first"><strong>{$df->Titel}</strong></td>
      <td colspan="2" class="second">{$df->Feld}</td>
    </tr>

    {if $df->extended_insert==1}
    <tr>
      <td width="225" class="first">&nbsp;</td>
      <td colspan="2" class="second">--</td>
    </tr>
    {/if}
    {/foreach}

  </table>

<br />
{*$hidden*}
{if $smarty.request.action=='edit'}
  <input type="submit" class="button" value="{#DOC_BUTTON_EDIT_DOCUMENT#}" />
{else}
  <input type="submit" class="button" value="{#DOC_BUTTON_ADD_DOCUMENT#}" />
{/if}
<input name="closeafter" type="hidden" id="closeafter" value="{$smarty.request.closeafter}">
</form>
{/strip}