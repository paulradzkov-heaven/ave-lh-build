<h2>{#ROADMAP_SITE_TITLE#}</h2>
<br />
{foreach from=$items item=item}
<p>
<div class="mod_roadmap_titlebar">
  <table width = "100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td><b>{$item->project_name}</b></td>
      <td align="right">{if !$item->date}{else}{#ROADMAP_LAST_CHANGE#}: {$item->date|date_format:"%d-%m-%Y, â %H:%M"}{/if}</td>
    </tr>
</table>
</div>
</p>

<table width="100%">
<tr>
  <td>
    <table class="progress" width="100%">
      <tr>
        <td class="closed" style="width: {$item->closed}%;"> </td>
        <td class="open" style="width: {$item->open}%;"> </td>
      </tr>
    </table>
  </td>

  <td><i>{$item->closed}%</i></td>
</tr>
</table>

<br />
<span style="color: #666"><i>{#ROADMAP_INACTIVE_TASK_2#}: <a href="/roadmap/project-{$item->id}/tasks/closed/" alt="link"><b>{$item->num_closed}</b></a> {#ROADMAP_ACTIVE_TASK_2#}: <a href="/roadmap/project-{$item->id}/tasks/open/" alt="link"><b>{$item->num_open}</b></a></i></span>

<br /><br />
<small>{#ROADMAP_SHORT_DESC#}:<br />{$item->project_desc}</small>
<br /><br />
{/foreach}

