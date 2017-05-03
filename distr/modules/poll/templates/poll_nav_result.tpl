<div class="mod_pollbox">		
  <p class="mod_poll_title">{#POLL_PUBLIC_TITLE#}</p>
  <strong>{$poll->title}</strong>
  <p>
    {foreach from=$items item=item}
  	  <div style="padding:2px">{$item->title}</div>
  	  <div style="width: 98% height:5px; padding:0px;">
	    <div style="display:block; background-color:{$item->color}; height:5px; width:{if $item->sum!=""}{$item->sum}%{else}0.5%{/if};"></div>
      </div>
  	 {#POLL_VOTES#} {$item->hits} ({$item->sum}%)<br /><br />
	{/foreach}
	<p><small>{if $poll->message == '1'}{#POLL_EXPIRED#}{/if}{if $poll->message == '2'}{#POLL_NO_PERMISSION#}{/if}{if $poll->message == '3'}{#POLL_ALREADY_POLL#}{/if}</small></p>
	<br />
	<form method="post">
  	  <input onclick="location.href='{$formaction_result}';" type="button" class="button" value="{#POLL_PUB_RESULTS#} ({$hits})" />
 	  <input onclick="location.href='{$formaction_archive}';" type="button"  class="button" value="{#POLL_VIEW_ARCHIVES#}" />
	</form>
</div>

