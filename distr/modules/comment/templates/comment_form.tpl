<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
  <title>{#COMMENT_NEW_TITLE#}</title>
  <link href="/templates/{$cp_theme}/css/style.css" rel="stylesheet" type="text/css" media="screen" />
  <script src="/templates/{$cp_theme}/js/common.js" type="text/javascript"></script>
</head>

<body id="body_popup">
  <div id="module_header">
    <h2>{#COMMENT_NEW_TITLE#}</h2>
  </div>

  <div id="module_content">
  {if $closed==1}
    {#COMMENT_NEW_CLOSED#}
    <p>&nbsp;</p>
    <p><input onclick="window.close();" type="button" class="button" value="{#COMMENT_CLOSE_BUTTON#}" /></p>
  {else}
    {if $cancomment != 1}
    <p id="module_intro">{#COMMENT_NEW_FALSE#}</p>
    <p>&nbsp;</p>
    <p><input onclick="window.close();" type="button" class="button" value="{#COMMENT_CLOSE_BUTTON#}" /></p>
  {else}

  <form method="post" action="index.php">
    {if $smarty.session.cp_uname != ''}
      <input name="Author" type="hidden" value="{$smarty.session.cp_uname}" />
    {else}
      <fieldset>
      <legend><label for="l_Author">{#COMMENT_YOUR_NAME#}</label></legend>
      <input name="Author" type="text" id="l_Author" style="width:250px" value="{$smarty.session.cp_uname}" />
      </fieldset>
    
      <br />
    {/if}
  
    {if $smarty.session.cp_email != ''}
      <input name="AEmail" type="hidden" value="{$smarty.session.cp_email}" />
    {else}
  
      <fieldset>
      <legend><label for="l_AEmail">{#COMMENT_YOUR_EMAIL#}</label></legend>
      <input name="AEmail" type="text" id="l_AEmail" style="width:250px" value="{$smarty.session.cp_email}" />
      </fieldset>
      
      <br />
    {/if}
  
  
      <fieldset>
      <legend><label for="l_AWebseite">{#COMMENT_YOUR_SITE#}</label></legend>
      <input name="AWebseite" type="text" id="l_AWebseite" style="width:250px" />
      </fieldset>
      
      <br />

      <fieldset>
      <legend><label for="l_AOrt">{#COMMENT_YOUR_FROM#}</label></legend>
      <input name="AOrt" type="text" id="l_AOrt" style="width:250px" />
      </fieldset>
      
      <br />

      <fieldset>
      <legend><label for="l_Text">{#COMMENT_YOUR_TEXT#}</label></legend>
      <textarea onkeyup="javascript:textCounter(this.form.Text,this.form.charleft,{$MaxZeichen});" onkeydown="javascript:textCounter(this.form.Text,this.form.charleft,{$MaxZeichen});" style="width:98%; height:165px" name="Text" id="l_Text"></textarea>
      <input type="text" size="6" name="charleft" value="{$MaxZeichen}" /> {#COMMENT_CHARSET_LEFT#}
      </fieldset>

  
      <input name="cp_theme" type="hidden" id="cp_theme" value="{$smarty.request.cp_theme}" />
      <input name="module" type="hidden" value="comment" />
      <input name="action" type="hidden" value="comment" />
      <input name="pop" type="hidden" id="pop" value="1" />
      <input name="sub" type="hidden" id="sub" value="send" />
      <input name="page" type="hidden" value="{$smarty.request.page}" />
      <input name="DokId" type="hidden" value="{$smarty.request.docid}" />
      <input name="Elter" type="hidden" value="{$smarty.request.parent|default:0}" />
    
      <p>
        <input type="submit" class="button" value="{#COMMENT_BUTTON_ADD#}" />
        <input type="reset" class="button" />
      </p>
  </form>
 {/if}
{/if}
</div>
</body>
</html>
