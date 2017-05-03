<div id="module_header">
 <h2>{#LOGIN_TEXT_REGISTER#}</h2>
</div>

<div id="module_content">

  {if $final == "ok"}
    <p><em>{#LOGIN_MESSAGE_OK#}</em></p>
  {else}
    <p><em>{#LOGIN_MESSAGE_TEXT#}</em></p>
  <form method="post" action="/login/register/final/">
    <div class="formleft"><label for="l_emc">{#LOGIN_CODE_FROM_MAIL#}</label></div>
    <div class="formright"><input name="emc" type="text" id="l_emc" style="width:200px" value="{$smarty.request.emc|strip_tags|stripslashes}" /></div>
    
    <div class="clear"></div><br />
    <input class="button" type="submit" value="{#LOGIN_BUTTON_FINAL#}" />
  </form>
  {/if}

</div>