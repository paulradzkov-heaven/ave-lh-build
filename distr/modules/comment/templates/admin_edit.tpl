<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
  <title>{#COMMENT_EDIT_TITLE#}</title>
  <link href="/admin/templates/{$cp_theme}/css/style.css" rel="stylesheet" type="text/css" media="screen" />
  <script src="/admin/templates/{$cp_theme}/js/common.js" type="text/javascript"></script>
</head>

<body id="content">
<div id="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#COMMENT_EDIT_TITLE#}</h2></div>
    <div class="HeaderText">&nbsp;</div>
</div>

  <div id="module_content">
  {if $closed == 1 && $ugroup != 1}
    {#COMMENT_IS_CLOSED#}
    <p>&nbsp;</p>
    <p><input onclick="window.close();" type="button" class="button" value="{#COMMENT_CLOSE_BUTTON#}" /></p>
  {else}
    {if $editfalse==1}
    {#COMMENT_EDIT_FALSE#}
  {else}
  
<form method="post" action="index.php">
<table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">

{if $ugroup==1}
  <tr>
    <td width="240" class="first">{#COMMENT_YOUR_NAME#}</td>
    <td class="second"><input name="Author" type="text" id="l_Author" style="width:250px" value="{$row.Author|stripslashes|escape:html}" /></td>
  </tr>

  <tr>
    <td width="240" class="first">{#COMMENT_YOUR_EMAIL#}</td>
    <td class="second"><input name="AEmail" type="text" id="l_AEmail" style="width:250px" value="{$row.AEmail|stripslashes|escape:html}" /></td>
  </tr>
  {else}
    <input name="Author" type="hidden" value="{$row.Author|stripslashes|escape:html}" />
    <input name="AEmail" type="hidden" value="{$row.AEmail|stripslashes|escape:html}" />
  {/if}
  <tr>
    <td width="240" class="first">{#COMMENT_YOUR_SITE#}</td>
    <td class="second"><input name="AWebseite" type="text" id="l_AWebseite" style="width:250px" value="{$row.AWebseite|stripslashes|escape:html}" /></td>
  </tr>

  <tr>
    <td width="240" class="first">{#COMMENT_YOUR_FROM#}</td>
    <td class="second"><input name="AOrt" type="text" id="l_AOrt" style="width:250px" value="{$row.AOrt|stripslashes|escape:html}" /></td>
  </tr>

  <tr>
    <td width="240" class="first">{#COMMENT_YOUR_TEXT#}</td>
    <td class="second"><textarea onkeyup="javascript:textCounter(this.form.Text,this.form.charleft,{$MaxZeichen});" onkeydown="javascript:textCounter(this.form.Text,this.form.charleft,{$MaxZeichen});" style="width:98%; height:170px" name="Text" id="l_Text">{$row.Text}</textarea>
<input type="text" size="6" name="charleft" value="{$MaxZeichen}" />{#COMMENT_CHARS_LEFT#}</td>
  </tr>
    <input name="cp_theme" type="hidden" id="cp_theme" value="{$smarty.request.cp_theme}" />
    <input name="module" type="hidden" value="comment" />
    <input name="action" type="hidden" value="edit" />
    <input name="pop" type="hidden" value="1" />
    <input name="sub" type="hidden" value="send" />
    <input name="Id" type="hidden" value="{$smarty.request.Id}" />
</table>
<br>
<input type="submit" class="button" value="{#COMMENT_BUTTON_EDIT#}" />
    <input type="reset" class="button" />
  </form>
{/if}
{/if}
</div>
</body>
</html>
