<h2>{$poll->title}</h2>
<br /><br />
<table width="100%" border="0" cellpadding="3" cellspacing="1">
  <tr>
    <td width="50%" class="mod_poll_table"><strong>{#POLL_QUESTION_LIST#}</strong></td>
    <td width="50%" colspan="3" class="mod_poll_table" align="center"><strong>{#POLL_RESULT_INFO#}</strong></td>
  </tr>
  {foreach from=$items item=item}
  <tr>
      <td  class="{cycle name="1" values="mod_poll_first,mod_poll_second"}">{$item->title}</td>
      <td  class="{cycle name="2" values="mod_poll_first,mod_poll_second"}"width="5%"><div align="center"> {$item->hits} </div></td>
      <td  class="{cycle name="3" values="mod_poll_first,mod_poll_second"}">
        <div style="width: 100% height:12px; padding:0px;">
          <div style="width: {if $item->sum!=""}{$item->sum}%{else}1%{/if}; height: 12px; background-color: {$item->color};"><img height="1" width="1" src="{$img_folder}/pixel.gif" alt="" /></div>
        </div>
      </td>
      <td  class="{cycle name="pollerg4" values="row_first,row_second"}" width="5%" nowrap="nowrap"><div align="center"> {$item->sum} %</div></td>
    </tr>
{/foreach}
</table>

<br /><br />
<table width="100%" border="0" cellpadding="5" cellspacing="1">

  <tr>
    <td colspan="2" class="mod_poll_table"><strong>{#POLL_INFOS#}</strong></td>
  </tr>

  <tr>
    <td width="170">{#POLL_ALL_HITS#}</td>
    <td>{$hits}</td>
  </tr>

  <tr>
    <td width="170">{#POLL_PUB_STATUS#}</td>
    <td>{if $end == '1'}{#POLL_INACTIVE_INFO#}{else}{#POLL_ACTIVE_INFO#}{/if}</td>
  </tr>

  <tr>
    <td width="170">{#POLL_STARTED#}</td>
    <td>{$start|date_format:$config_vars.POLL_DATE_FORMAT3}</td>
  </tr>

  <tr>
    <td width="170">{#POLL_ENDED#}</td>
    <td>{$end|date_format:$config_vars.POLL_DATE_FORMAT3}</td>
  </tr>

  <tr>
    <td width="170">{#POLL_GROUPS_PERM#}</td>
    <td>{foreach from=$groups item=group name=ugroups} {$group->Name}{if $smarty.foreach.ugroups.last}{else},{/if} {/foreach}</td>
  </tr>
</table>

<br />	

{if $vote != '1'}
	<div style="padding:5px"> <strong>{$poll->title}</strong></div>
	<form method="post" action="{$formaction}">
	{foreach from=$items item=item}
		<div style="margin-left:5px"><input type="radio" name="p_item" value="{$item->id}" /><small>{$item->title}</small></div>
	{/foreach}
	<div style="padding:5px"><input type="submit" class="button" value="{#POLL_BUTTON_VOTE#}" /></div><br />
	</form>
{/if}

{if $can_comment == '1'}
<table width="100%" border="0" cellpadding="5" cellspacing="1">
  <tr>
    <td colspan="2" class="mod_poll_table"><strong>{#POLL_PUB_COMMENTS#}</strong></td>
  </tr>

  <tr>
    <td><a href="javascript:void(0);" onclick="popup('{$formaction_comment}','comment','600','500','1');">{#POLL_PUB_ADD_COMMENT#}</a> | {#POLL_ALL_COMMENTS#} {$count_comments} <br /><br />
    {foreach from=$comments item=item}
     <div class="mod_poll_comments">
       <div style="font-size:inherit">{#POLL_ADDED#} <a href="#">{$item->lastname} {$item->firstname}</a> {$item->ctime|date_format:$config_vars.POLL_DATE_FORMAT3}</div><br />
  	   <div style="padding:5px;"><strong>{$item->title}</strong><br />{$item->comment}</div>
     </div>
<br /><br />
{/foreach}
</td>
</tr>
</table>
{/if}