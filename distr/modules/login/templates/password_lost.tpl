<script type="text/javascript" language="JavaScript">
 function check_email() {ldelim}
   if (document.getElementById('l_mailreminder').value == '') {ldelim}
     alert("{#LOGIN_ENTER_EMAIL#}");
     document.getElementById('l_mailreminder').focus();
     return false;
   {rdelim}
   return true;
 {rdelim}

</script>

<div id="module_header"><h2>{#LOGIN_REMIND#}</h2></div>

<div id="module_content">
  
  {if $smarty.request.sub=='send'}
    <p><em>{#LOGIN_REMINDER_INFO3#}</em></p>
  {else}
    <p><em>{#LOGIN_REMINDER_INFO2#}</em></p>

  <form method="post" action="/index.php" onSubmit="return check_email();">
    <div class="formleft"><label for="l_mailreminder">{#LOGIN_YOUR_MAIL#}</label></div>
    <div class="formright">
      <input name="f_mailreminder" type="text" id="l_mailreminder" style="width:200px" value="" />
    </div>
    <div class="clear"></div><br />
      <input class="button" type="submit" value="{#LOGIN_BUTTON_NEWPASS#}" />
      <input type="hidden" name="module" value="login" />
      <input type="hidden" name="action" value="passwordreminder" />
      <input type="hidden" name="sub" value="send" />
  </form>
  {/if}

</div>