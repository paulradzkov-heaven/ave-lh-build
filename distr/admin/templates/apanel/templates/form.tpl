{strip}
	<script language="Javascript" type="text/javascript" src="editarea/edit_area_full.js"></script>
	<script language="Javascript" type="text/javascript" src="editarea/templates.js"></script>

<div id="pageHeaderTitle" style="padding-top: 7px;">
  <div class="h_tpl"></div>
  {if $smarty.request.action=='new'}
    <div class="HeaderTitle"><h2>{#TEMPLATES_TITLE_NEW#}</h2></div>
    <div class="HeaderText">{#TEMPLATES_WARNING2#}</div>
  {else}
    <div class="HeaderTitle"><h2>{#TEMPLATES_TITLE_EDIT#}<span style="color: #000;">&nbsp;&gt;&nbsp;{$row->TplName|escape:html}{$smarty.request.TempName|escape:html}</span></h2></div>
    <div class="HeaderText">{#TEMPLATES_WARNING1#}</div>
  {/if}
</div>
<div class="upPage"></div>
<br />

<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
  {if $smarty.request.action=='new'}
  <tr>
    <td class="tableheader">{#TEMPLATES_LOAD_INFO#}</td>
  </tr>

  <tr>
    <td class="first">
      <form action="index.php?do=templates&action=new" method="post">
        <select name="theme_pref">
          <option></option>
          {$sel_theme}
        </select>
		<input type="hidden" name="TempName" value="{$smarty.request.TempName|escape:html}">
        <input type="submit" class="button" value="{#TEMPLATES_BUTTON_LOAD#}">
      </form>
    </td>
  </tr>
  {/if}

  <form name="f_tpl" id="f_tpl" method="post" action="{$formaction}">
  <tr>
    <td class="tableheader">{#TEMPLATES_NAME#}</td>
  </tr>

  <tr class="{cycle name='ta' values='first,second'}">
    <td>
      {foreach from=$errors item=e}
        {assign var=message value=$e}
        <ul>
          <li>{$message}</li>
        </ul>
      {/foreach}
      <input name="TplName" type="text" value="{$row->TplName|escape:html}{$smarty.request.TempName|escape:html}" size="50" maxlength="50" >
    </td>
  </tr>

  <tr>
    <td class="tableheader">{#TEMPLATES_HTML#}</td>
  </tr>

  <tr>
    <td class="second">
      {if $php_forbidden==1}
        <div class="infobox_error">{#TEMPLATES_USE_PHP#} </div>
      {/if}

      <textarea {$read_only} class="{if $php_forbidden==1}tpl_code_readonly{else}{/if}" wrap="off" style="width:100%; height:500px" name="Template" id="Template">{$row->Template|default:$prefab|escape:html}</textarea>
      <div class="infobox">
        {assign var=js_textfeld value='Template'}
        {assign var=js_form value='f_tpl'}|&nbsp;
		  <a href="javascript:void(0);" onclick="editAreaLoader.insertTags('{$js_textfeld}', '<p>', '</p>');">P</a>&nbsp;|&nbsp;
		  <a href="javascript:void(0);" onclick="editAreaLoader.insertTags('{$js_textfeld}', '<strong>', '</strong>');">B</a>&nbsp;|&nbsp;
		  <a href="javascript:void(0);" onclick="editAreaLoader.insertTags('{$js_textfeld}', '<em>', '</em>');">I</a>&nbsp;|&nbsp;
		  <a href="javascript:void(0);" onclick="editAreaLoader.insertTags('{$js_textfeld}', '<h1>', '</h1>');">H1</a>&nbsp;|&nbsp;
		  <a href="javascript:void(0);" onclick="editAreaLoader.insertTags('{$js_textfeld}', '<h2>', '</h2>');">H2</a>&nbsp;|&nbsp;
		  <a href="javascript:void(0);" onclick="editAreaLoader.insertTags('{$js_textfeld}', '<h3>', '</h3>');">H3</a>&nbsp;|&nbsp;
		  <a href="javascript:void(0);" onclick="editAreaLoader.insertTags('{$js_textfeld}', '<pre>', '</pre>');">PRE</a>&nbsp;|&nbsp;
		  <a href="javascript:void(0);" onclick="editAreaLoader.insertTags('{$js_textfeld}', '<br />', '');">BR</a>&nbsp;|&nbsp;
		  <a href="javascript:void(0);" onclick="editAreaLoader.insertTags('{$js_textfeld}', '<div>', '</div>');">DIV</a>&nbsp;|
      </div>
    </td>
  </tr>

  <tr>
    <td style="padding:0px" class="second">
      <table width="100%" border="0" cellspacing="1" cellpadding="4">
        {foreach from=$tags item=tag}
        <tr>
          <td width="350" class="first">
		    <a {popup sticky=false text=$config_vars.TEMPLATES_TAG_INSERT} href="javascript:void(0);" onclick="editAreaLoader.insertTags('{$js_textfeld}', '{$tag->cp_tag}','');">{$tag->cp_tag}</a>
          </td>
          <td class="first">{$tag->cp_desc}</td>
        </tr>
        {/foreach}
      </table>
    </td>
  </tr>

  <tr class="{cycle name='ta' values='first,second'}">
    <td class="second">
      <input type="hidden" name="Id" value="{$smarty.request.Id}">
      {if $smarty.request.action=='new'}
        <input class="button" type="submit" value="{#TEMPLATES_BUTTON_ADD#}" />
      {else}
        <input class="button" type="submit" value="{#TEMPLATES_BUTTON_SAVE#}" />
      {/if}
    </td>
  </tr>
  </form>
</table>
{/strip}