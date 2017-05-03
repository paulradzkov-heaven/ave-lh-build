<h3>Авторизация</h3>
<div id="loginform"  class="blockborder">
   <form method="post" action="/index.php">
      <input name="module" value="login" type="hidden" />
      <input name="action" value="login" type="hidden" />
      <label for="cp_login">{#LOGIN_YOUR_LOGIN#}</label>
      <input  tabindex="1" class="loginfield" name="cp_login" id="cp_login" type="text" style="width:99%" />
      <label for="cp_password">{#LOGIN_YOUR_PASSWORD#}</label>
      <input tabindex="2" type="password" class="loginfield" name="cp_password" id="cp_password" style="width:99%" />
      <label for="SaveLogin" onmouseover="$.cursorMessage('{$config_vars.LOGIN_SAVE_INFO}');" onmouseout="$.hideCursorMessage();"><input tabindex="3" name="SaveLogin" type="checkbox" id="SaveLogin" value="1" /> {#LOGIN_SAVE_COOKIE#}</label>
		<div align="center">
      <input tabindex="4" class="button" type="submit" value="{#LOGIN_BUTTON_ENTER#}" style="width:100px" /></div>
    </form>
    <div class="dotted"></div>
  <a class="pg_qw" onmouseover="$.cursorMessage('{$config_vars.LOGIN_REMINDER_INFO}');" onmouseout="$.hideCursorMessage();" href="/login/reminder/">{#LOGIN_PASSWORD_REMIND#}</a>
  {if $active == 1}
  <a class="pg_plus" onmouseover="$.cursorMessage('{$config_vars.LOGIN_REGISTER_INFO}');" onmouseout="$.hideCursorMessage();" href="/login/register/">{#LOGIN_NEW_REGISTER#}</a>
  {/if}
</div>
