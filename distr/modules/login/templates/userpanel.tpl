<h3>Профиль</h3>
<div id="loginform" class="blockborder"> 
<div style="padding-top:0;">{#LOGIN_WELCOME_TEXT#}, {$smarty.session.cp_uname}!</div>
  <a class="pg_prof" href="/login/edit/">{#LOGIN_CHANGE_DETAILS#}</a>
  <a class="pg_pswd" href="/login/password/">{#LOGIN_CHANGE_LINK#}</a>
  <a class="pg_del" href="/login/del/">{#LOGIN_DELETE_LINK#}</a>
  <div class="dotted"></div>
  {if cp_perm("adminpanel")}<a class="pg_panel" target="_blank" href="/admin/admin.php">{#LOGIN_ADMIN_LINK#}</a>{/if}
    {if cp_perm("docs")}
    {if $smarty.session.cp_adminmode==1}
      <a class="pg_panel" href="/index.php?module=login&amp;action=wys&amp;sub=off">{#LOGIN_WYSIWYG_OFF#}</a>
    {else}
      <a class="pg_panel" href="/index.php?module=login&amp;action=wys&amp;sub=on">{#LOGIN_WYSIWYG_ON#}</a>
    {/if}
  {/if}
  <div class="dotted"></div>
  <a class="pg_exit" href="/login/exit/">{#LOGIN_LOGOUT_LINK#}</a>

  
  
  
 

</div>
