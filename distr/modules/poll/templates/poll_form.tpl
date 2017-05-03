<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>{#POLL_ADD_COMMENT#}</title>
<link href="/templates/{$cp_theme}/css/style.css" rel="stylesheet" type="text/css" media="screen" />
<script src="/templates/{$cp_theme}/js/common.js" type="text/javascript"></script>
</head>

<body id="body_popup">
<script type="text/javascript" language="JavaScript">
 function check() {ldelim}
   if (document.getElementById('comment_title').value == '') {ldelim}
     alert("{#POLL_ENTER_TITLE#}");
     document.getElementById('comment_title').focus();
     return false;
   {rdelim}
   
   if (document.getElementById('comment_text').value == '') {ldelim}
     alert("{#POLL_ENTER_TEXT#}");
     document.getElementById('comment_text').focus();
     return false;
   {rdelim}
   
   if (document.getElementById('comment_code').value == '') {ldelim}
     alert("{#POLL_ENTER_CODE#}");
     document.getElementById('comment_code').focus();
     return false;
   {rdelim}
   return true;
 {rdelim}
</script>

  <div id="module_header"> <h2>{#POLL_ADD_COMMENT#}</h2> </div>
  <div id="module_content">
  {if $cancomment != 1}
    <p id="module_intro">{#POLL_COMMENT_ERROR#}</p>
    <p>&nbsp;</p>
    <p><input onclick="window.close();" type="button" class="button" value="{#POLL_CLOSE_W#}" /></p>
  {else}
    <form method="post" action="index.php?module=poll&action=comment&pid={$smarty.request.pid}" onSubmit="return check();">
    {foreach from=$errors item=item}
  	  <ul>
		<li>{$item}</li>
  	  </ul>
    {/foreach}
    <fieldset>
      <legend><label for="l_Titel">{#POLL_COMMENT_T#}</label></legend>
      <input name="comment_title" type="text" id="comment_title" style="width:250px" value="{$title}" />
    </fieldset>
    <br />

    <fieldset>
      <legend><label for="l_Text">{#POLL_COMMENT_M#}</label></legend>
      <textarea onkeyup="javascript:textCounter(this.form.comment_text,this.form.charleft,{$max_chars});" onkeydown="javascript:textCounter(this.form.comment_text,this.form.charleft,{$max_chars});" style="width:98%; height:165px" name="comment_text" id="comment_text">{$text}</textarea>
   	  <input type="text" size="6" name="charleft" value="{$max_chars}" /> {#POLL_CHARSET_LEFT#}
    </fieldset>

    {if $anti_spam == 1}
    <fieldset>
      <legend><label for="l_Text">{#POLL_SECURE_CODE#}</label></legend>
   	  <img src="/nospam_{$im}.jpeg" alt="" width="121" height="41" border="0" /><br /><br />
      <small><span id="S_secure_{$im}"></span></small>
      <input name="comment_code" type="text" id="comment_code" style="width:100px" maxlength="7" />
    </fieldset>
    {/if}

    <p>
      <input type="submit" class="button" value="{#POLL_BUTTON_ADD_C#}" />
      <input type="reset" class="button" value="{#POLL_BUTTON_RESET#}"/>
    </p>
   </form>
  {/if}
</div>
</body>
</html>

