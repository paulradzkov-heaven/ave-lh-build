<h2>{#LOGIN_PLEASE_LOGON#}</h2><br />

  {if $login=='true'} {#LOGIN_TEXT_TRUE_INFO#} {redir} {/if}
  
  {if $login=='false'} {#LOGIN_TEXT_FALSE_INFO#}
    
    <p>&nbsp;</p>
    
    <form method="post" action="/index.php">
      <input name="module" value="login" type="hidden" />
      <input name="action" value="login" type="hidden" />
      <label for="f_email"><strong>{#LOGIN_YOUR_MAIL#}</strong></label>
      <br />
      <input class="loginfield" name="cp_login" type="text" id="f_email" style="width:200px" />
      <br />
      <label for="f_kennwort"><strong>{#LOGIN_PASSWORD#}</strong></label>
      <br />
      <input class="loginfield" name="cp_password" type="password" id="f_kennwort" style="width:200px" />
      <br />
      <input name="SaveLogin" type="checkbox" id="SaveLogin" value="1" />
      &nbsp;<a {popup width=380 sticky=false text=$config_vars.LOGIN_SAVE_INFO|default:''} href="javascript:void(0);">{#LOGIN_SAVE_COOKIE#}</a>
      <br /><br />
      <input class="button" type="submit" value="{#LOGIN_BUTTON_ENTER#}" />
    </form>
  
    <a {popup width=380 sticky=false text=$config_vars.LOGIN_REMINDER_INFO|default:''} href="/login/reminder/">{#LOGIN_PASSWORD_REMIND#}</a> 
  
    <a {popup width=380 sticky=false text=$config_vars.LOGIN_REGISTER_INFO|default:''} href="/login/register/">{#LOGIN_NEW_REGISTER#}</a>

    <p>&nbsp;</p>
    <p>&nbsp;</p>
  
  {/if}