<div class="mod_pollbox">		
  <p class="mod_poll_title">{#POLL_PUBLIC_TITLE#}</p>
  <strong>{$poll->title}</strong>
  <p>
    <form method="post" action="{$formaction}">
 	  {foreach from=$items item=item}
   	    <input type="radio" name="p_item" value="{$item->id}" /><small>{$item->title}</small><br />
      {/foreach}
	  <br />
   	  <input type="submit" class="button" value="{#POLL_BUTTON_VOTE#}" />
	  <input type="button" onclick="location.href='{$formaction_result}';" class="button" value="{#POLL_PUB_RESULTS#}" />
	</form>
  </p>
</div>

