
{if $smarty.request.print!=1}
<script language="javascript" type="text/javascript">

function showSearch() {ldelim}

var block = document.getElementById('search');

if (block.style.display == "none") {ldelim}

		block.style.display = "";
		
{rdelim} else {ldelim}

 block.style.display = "none";
 
{rdelim}

{rdelim}
</script>

<table width="100%" border="0" cellpadding="2" cellspacing="1" class="forum_tableborder">
  <tr>
    <td align="center" class="forum_info_meta"><a href="index.php?module=forums">{#PageNameForums#}</a></td>
	  <td align="center" class="forum_info_meta"><a href="index.php?module=forums&amp;show=userlist">{#Users#}</a></td>
    <td align="center" class="forum_info_meta"><a href="javascript:void(0);" onclick="showSearch();">{#ForumsSearch#}</a>
    
    <div id="search" class="forum_search" style="display:none">
<div>
<form action='index.php' method='get'>
<input type='text' name='pattern' size='30' value='' />
<input class='button' type='submit' value='Найти' />
<input type='hidden' name='module' value='forums' />
<input type='hidden' name='show' value='search' />
<input name='search_post' type='hidden' value='1' />
<a href='index.php?module=forums&amp;show=search_mask'><img class='absmiddle' src='templates/default/modules/forums/forum/arrow.gif' alt='' border='' /></a>
<a href='index.php?module=forums&amp;show=search_mask'>Расширеный поиск</a>
</form>
</div>
</div>
    
    </td>
    
	  <td align="center" class="forum_info_meta"><a href="index.php?module=forums&amp;show=last24">{#ShowLast24#}</a></td>
    {if $smarty.session.cp_ugroup != 2}
	  <td align="center" class="forum_info_meta"><a href="index.php?module=forums&amp;show=userpostings&amp;user_id={$smarty.session.cp_benutzerid}">{#ShowMyPostings#}</a></td>
    <td align="center" class="forum_info_meta"><a href="index.php?module=forums&amp;show=showforums&amp;action=markread&amp;what=forum&amp;ReadAll=1">{#MarkForumsRead#}</a></td>
	  {/if}
  </tr>
</table>
<br />
{/if}