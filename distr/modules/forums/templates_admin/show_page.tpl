<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#ModName#}</h2></div>
    <div class="HeaderText">&nbsp;</div>
</div><br />
{include file="$source/forum_topnav.tpl"}
<br />
<form name="f" action="index.php?do=modules&action=modedit&mod=forums&moduleaction=show_page&cp={$sess}&sub=save" method="post">
	<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
		<tr>
			<td colspan="2" class="tableheader">{#ShowTopics#}</td>
		</tr>

		<tr class="second">
		<td colspan="2">
		<div class="infobox" style="height:200px; overflow:auto">
<table border="0" cellpadding="5" cellspacing="1" class="tableborder" width="100%">
		{foreach from=$Topics item=t}
<tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
	<td width="1%"><input type="hidden" name="title[{$t->id}]" value="{$t->title}"><input name="show[{$t->id}]" type="checkbox" value="1" {if $t->show_page=="1"}checked{else}{/if} /></td>
	<td><a href="javascript:;" onclick="window.open('../index.php?module=forums&show=showtopic&toid={$t->id}&fid={$t->forum_id}','pv','left=0,top=0,width=950,height=650,scrollbars=yes')">{$t->title|stripslashes}</a></td>
	<td><strong>{#ShowDate#}</strong> {$t->datum}</td>
	<td><strong>{#ShowAnswer#}</strong> {$t->replies}</td>
</tr>
		{/foreach}
		</table>
		</div>		</td>
		</tr>

		<tr>
		  <td colspan="2" class="second">
		  <input class="button" type="submit" value="{#ShowMarked#}" />		  
		  </td></tr>
  </table>
</form>
