<h2>{#ARCHIVE_FROM#} {$month_name}, {$year} {#ARCHIVE_YEAR#}</h2><br /> <br />
{if $results->show_days == 1}
  <div><small>{#ARCHIVE_DAYS#}</small></div>

  <table width="100%" cellpadding="2" cellspacing="2">
    <tr>
      {if $day == 0}
        <td><small>{#ARCHIVE_ALL_DAY#}</small></td>
      {else}
        <td><small><a href="/newsarchive-{$results->id}/{$month}/{$year}/">{#ARCHIVE_ALL_DAY#}</a></small></td>
      {/if}
      {foreach from=$days item=items}
        {if $day == $items}
          <td><small>{$items}</small></td>
        {else}
          <td><small><a href="/newsarchive-{$results->id}/{$items}/{$month}/{$year}/">{$items}</a></small></td>
        {/if}
      {/foreach}
    </tr>
  </table>
{/if}

{if !$documents}
  <strong>{#ARCHIVE_NOT_FOUND#}</strong>
{else}
  <table width="100%" cellpadding="0" cellspacing="2">
    <tr class="arc_header">
    {if $day == 0}
      <td><a href="/newsarchive-{$results->id}/{$month}/{$year}/{if $smarty.request.sort=='Title'}TitleDesc{else}Title{/if}/">{#ARCHIVE_DOC_NAME#}</a></td>
      <td><a href="/newsarchive-{$results->id}/{$month}/{$year}/{if $smarty.request.sort=='Date'}DateDesc{else}Date{/if}/">{#ARCHIVE_PUBLIC_DATE#}</a></td>
      <td><a href="/newsarchive-{$results->id}/{$month}/{$year}/{if $smarty.request.sort=='Rubric'}RubricDesc{else}Rubric{/if}/">{#ARCHIVE_IN_RUBRIC#}</a></td>
    {else}
      <td><a href="/newsarchive-{$results->id}/{$day}/{$month}/{$year}/{if $smarty.request.sort=='Title'}TitleDesc{else}Title{/if}/">{#ARCHIVE_DOC_NAME#}</a></td>
      <td><a href="/newsarchive-{$results->id}/{$day}/{$month}/{$year}/{if $smarty.request.sort=='Date'}DateDesc{else}Date{/if}/">{#ARCHIVE_PUBLIC_DATE#}</a></td>
      <td><a href="/newsarchive-{$results->id}/{$day}/{$month}/{$year}/{if $smarty.request.sort=='Rubric'}RubricDesc{else}Rubric{/if}/">{#ARCHIVE_IN_RUBRIC#}</a></td>
    {/if}
    </tr>
    {foreach from=$documents item=items}
      <tr>
        <td><a href="{$items->Url}">{$items->Titel}</a></td>
        <td>{$items->DokStart|date_format:"%d-%m-%Y ã."}</td>
        <td>{$items->RubrikName}</td>
      </tr>
    {/foreach}
  </table>
{/if}
