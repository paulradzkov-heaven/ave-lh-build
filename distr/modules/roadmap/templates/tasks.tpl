<a href="/roadmap/"><h2>{#ROADMAP_SITE_TITLE_IN#}</h2></a>
<br />

<p><div class="mod_roadmap_titlebar">
<table width = "100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td><strong>{$row->project_name}</strong></td>
<td align="right">{#ROADMAP_SELECT_STATUS#}: 
  <form method="POST">
    <select name="closed" onchange="location.href='/roadmap/project-{$smarty.request.pid}/tasks/{if $smarty.request.closed == "1"}open{else}closed{/if}/'">
      <option value="1" {if $smarty.request.closed == "1"}selected{/if}>{#ROADMAP_CLOSED#}</option>
      <option value="0" {if $smarty.request.closed == "0"}selected{/if}>{#ROADMAP_OPEN#}</option>
      </select>
  </form>
</td>
</tr>
</table>
</div></p>

<p>
<table width = "100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
      <td class="mod_roadmap_topheader" align="center">�</td>
      <td class="mod_roadmap_topheader" align="center">{#ROADMAP_DESCRIPTION#}</td>
      <td class="mod_roadmap_topheader" align="center">{#ROADMAP_DATE_CREATE#}</td>
      <td class="mod_roadmap_topheader" align="center">{#ROADMAP_OWNER#}</td>
      <td class="mod_roadmap_topheader" align="center">{#ROADMAP_TASK_STATUS#}</td>
    </tr>

    {foreach from=$items item=item}
    <tr>
    <td class="dl_{cycle name="1" values="a,b"}_{$item->prio}" align="center">{$item->id}</td>
    <td class="dl_{cycle name="2" values="a,b"}_{$item->prio}">{$item->task_desc}</td>
    <td class="dl_{cycle name="3" values="a,b"}_{$item->prio}" align="center">{$item->date_create|date_format:"%d-%m-%Y �."}</td>
    <td class="dl_{cycle name="4" values="a,b"}_{$item->prio}" align="center">{$item->lastname} {$item->firstname}</td>
    <td class="dl_{cycle name="5" values="a,b"}_{$item->prio}" align="center">{$item->priority}</td>
  </tr>
  {/foreach}

</table>
</p>

<br /><br />