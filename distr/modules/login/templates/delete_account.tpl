<div id="module_header"><h2>{#LOGIN_DELETE_ACCOUNT#}</h2></div>

<div id="module_content">
  {if $admin==1}
    <p><em>{#LOGIN_ADMIN_ACCOUNT#}</em></p>
  {else}
    {if $delok == 1}
      <p><em>{#LOGIN_DELETE_OK#}</em></p>
    {else}
      <p><em>{#LOGIN_DELETE_INFO#}</em></p>

     <form method="post" action="/login/del/yes/">
     <input name="delconfirm" type="checkbox" value="1" />
     {#LOGIN_DELETE_CONFIRM#}<br /><br />
     <input class="button" type="submit" value="{#LOGIN_DELETE_BUTTON#}" />
     </form>
    {/if}
  {/if}
</div>