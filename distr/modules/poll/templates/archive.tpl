<h2>{#POLL_ARCHIVE_TITLE#}</h2><br /><br />

<table width="100%" border="0" cellpadding="3" cellspacing="1">
  <tr>
    <td class="mod_poll_table"><strong>{#POLL_PUB_TITLE#}</strong></td>
    <td class="mod_poll_table" align="center"><strong>{#POLL_PUB_STARTED#}</strong></td>
    <td class="mod_poll_table" align="center"><strong>{#POLL_ARCHIVE_HITS#}</strong></td>
  </tr>
  {foreach from=$items item=item}
  <tr class="{cycle name='1' values="mod_poll_first,mod_poll_second"}">
    <td ><a href="{$item->plink}">{$item->title}</a></td>
    <td align="center">с {$item->start|date_format:$config_vars.POLL_DATE_FORMAT4} по {$item->ende|date_format:$config_vars.POLL_DATE_FORMAT4}</td>
    <td align="center">{$item->sum_hits}</td>
  </tr>
  {/foreach}
</table>
<br /><br />