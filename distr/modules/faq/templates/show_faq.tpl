<div class="mod_searchbox">
<center><b>{$faq_name}</b>
<br>
{$desc}
</center>
</div>
<div class="infobox">{#faq_EDIT_TIP#}</div>
{if $quest}
  <table width="100%" border="0" cellpadding="4" cellspacing="1" class="tableborder">
    {foreach from=$quest item=item}
    <td>
      <td>
      {$item->quest|stripslashes}
      </td>
      <td>
      {$item->answer|stripslashes}
      </td>      
    </tr>
    {/foreach}
  </table>
<br />
{/if}

