<div id="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#COMMENT_MODULE_NAME#}</h2></div>
    <div class="HeaderText">&nbsp;</div>
</div>
<br>
<div class="infobox">
  <a href="index.php?do=modules&action=modedit&mod=comment&moduleaction=1&cp={$sess}">{#COMMENT_MODULE_COMENTS#}</a> | <strong>{#COMMENT_MODULE_SETTINGS#}</strong>
</div>
<br>
<form action="index.php?do=modules&action=modedit&mod=comment&moduleaction=settings&cp={$sess}&sub=save" method="post">
<table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
  <tr>
    <td width="240" class="first">{#COMMENT_ENABLE_COMMENT#}</td>
    <td class="second"><input name="Aktiv" type="checkbox" value="1" {if $row->Aktiv=='1'}checked{/if} /></td>
  </tr>
  <tr>
    <td width="240" class="first">{#COMMENT_CHECK_ADMIN#}</td>
    <td class="second">
      <input name="Zensur" type="checkbox" value="1" {if $row->Zensur=='1'}checked{/if} />
    </td>
  </tr>
  <tr>
    <td width="240" class="first">{#COMMENT_FOR_GROUPS#}</td>
    <td class="second">
{foreach from=$groups item=g}
  <input name="Gruppen[]" type="checkbox" id="Gruppen[]" value="{$g->Benutzergruppe}" {if (in_array($g->Benutzergruppe,$row->Gruppen)) }checked{/if} /> {$g->Name|escape:html}<br />
{/foreach}
{*
<select  name="Gruppen[]"  multiple="multiple" size="5" style="width:200px">
{foreach from=$groups item=g}
{assign var='sel' value=''}
{if $g->Benutzergruppe}
  {if (in_array($g->Benutzergruppe,$row->Gruppen)) }
    {assign var='sel' value='selected'}
  {/if}
{/if}
<option value="{$g->Benutzergruppe}" {$sel}>{$g->Name|escape:html}</option>
{/foreach}
</select>
*}
  </td>
  </tr>
  <tr>
    <td width="240" class="first">{#COMMENT_MAX_CHARS#}</td>
    <td class="second">
      <input name="MaxZeichen" type="text" id="MaxZeichen" value="{$row->MaxZeichen}" size="5" maxlength="5" />
    </td>
  </tr>
</table>
<br />
<input type="submit" value="{#COMMENT_BUTTON_SAVE#}" class="button" />
</form>
