<br />
<div class="mod_searchbox">
  <form method="get" action="/index.php">
    <input type="hidden" name="module" value="search" />
    <input style="width:350px" class="query" name="query" type="text" value="{$smarty.request.query|stripslashes|escape:html}" />
    <input type="submit" class="button" value="{#SEARCH_BUTTON#}" />
  <input onmouseout="$.hideCursorMessage();" onmouseover="$.cursorMessage('{$config_vars.SEARCH_HELP}');" type="button" class="button" value="?" />
  </form>
</div>