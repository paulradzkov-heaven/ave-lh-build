<div class="pageHeaderTitle">
    <div class="h_docs"></div>
	<div class="HeaderTitle"><h2>{#DOC_AFTER_CREATE_TITLE#}</h2></div>
    <div class="HeaderText">{#DOC_AFTER_CREATE_INFO#}</div>
</div>

{if $smarty.request.action != 'new'}
{assign var=pop value='&amp;pop=1'}
{/if}

<ul style="list-style-type: none; line-height: 2em; padding-left: 70px;">
<li>&raquo;&nbsp;<a href="index.php?do=modules&action=modedit&mod=news&moduleaction=editnews&RubrikId={$RubrikId}&Id={$Id}&cp={$sess}">{#DOC_EDIT_THIS_DOCUMENT#}</a></li>

{if $innavi==1}
<li>&raquo;&nbsp;<a href="javascript:void(0);" onclick="document.getElementById('Nav').style.display=''">{#DOC_INCLUDE_NAVIGATION#}</a></li>
{/if}

<li>&raquo;&nbsp;<a href="index.php?do=modules&action=modedit&mod=news&moduleaction=addnews&id={$newsRub}&cp={$sess}">{#DOC_ADD_NEW_DOCUMENT#}</a></li>

<li>&raquo;&nbsp;<a href="../index.php?id={$Id}" target="_blank">{#DOC_DISPLAY_NEW_WINDOW#}</a></li>

<li>&raquo;&nbsp;<a href="index.php?do=modules&action=modedit&mod=news&moduleaction=editsubrub&id={$newsRub}&cp={$sess}">{#DOC_CLOSE_WINDOW#}</a></li>
</ul>
</div>

<div id="Nav" style="display:none">
<form method="post" action="{if $smarty.request.action=='edit'}index.php?do=docs&action=edit&RubrikId={$smarty.request.RubrikId}&Id={$smarty.request.Id}&cp={$sess}&pop=1&sub=savenavi{else}index.php?do=docs&action=new&RubrikId={$RubrikId}&Id={$Id}&cp={$sess}&sub=savenavi{/if}">
<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
	<tr class="tableheader">
		<td colspan="2"><a name="nav"></a>{#DOC_TO_NAVI_TITLE#}</td>
	</tr>
	<tr>
		<td width="200" class="first">{#DOC_USE_NAVIGATION#} </td>
        <td class="second">
          {include file='navigation/tree_docform.tpl'} {#DOC_IN_MENU#}
          <select name="NaviRubric" id="NaviRubric">
            {foreach from=$rubs item=r}
              <option value="{$r->Id}">{$r->RubrikName|escape:html|stripslashes}</option>
            {/foreach}
          </select>
        </td>
	</tr>
	<tr>
		<td class="first">{#DOC_NAVIGATION_POSITION#} </td>
		<td class="second"><input style="width:45px" name="Rang" type="text" id="Rang" value="1" maxlength="4"></td>
	</tr>
	<tr>
		<td class="first">{#DOC_NAVIGATION_TITLE#} </td>
		<td class="second"><input style="width:400px" name="Titel" type="text" id="Titel" value="{$smarty.request.Titel|stripslashes}"></td>
	</tr>
	<tr>
		<td class="first">{#DOC_TARGET#}</td>
		<td class="second">
			<select style="width:150px" name="Ziel" id="Ziel">
				<option value="_self">{#DOC_TARGET_SELF#}</option>
				<option value="_blank">{#DOC_TARGET_BLANK#}</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="first">
			<input name="action" type="hidden" id="action" value="updateNavi">
			<input name="Id" type="hidden" id="Id" value="{$Id}">
			<input name="Url" type="hidden" id="Url" value="{$smarty.request.Url}">
			<input name="RubrikId" type="hidden" id="RubrikId" value="{$RubrikId}">
		</td>
		<td class="second"><input type="submit" class="button" value="{#DOC_BUTTON_ADD_MENU#}"></td>
	</tr>
</table>
</form>

