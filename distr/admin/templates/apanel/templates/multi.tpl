<div id="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_tpl"></div>
    <div class="HeaderTitle"><h2>{#TEMPLATES_COPY_TITLE#}</h2></div>
    <div class="HeaderText">{#TEMPLATES_TIP2#}</div>
</div>
<div class="upPage"></div>

<br>
<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
  <tr>
    <td class="tableheader">{#TEMPLATES_NAME2#}</td>
  </tr>

  <tr class="{cycle name='ta' values='first,second'}">
    <td>
      {foreach from=$errors item=e}
      {assign var=message value=$e}
        <ul>
          <li>{$message}</li>
        </ul>
      {/foreach}
  
      <form name="m" method="post" action="?do=templates&amp;action=multi&amp;pop=1&amp;sub=save&amp;Id={$smarty.request.Id}">
        <input type="text" name="TplName" value="{$smarty.request.TplName|escape:html|default:"��������"}">
        <input class="button" type="submit" value="{#TEMPLATES_BUTTON_COPY#}" onmouseover="this.style.backgroundColor='#ff7711';" onmouseout="this.style.backgroundColor='#77aa00';" />
        <input name="oId" type="hidden" id="oId" value="{$smarty.request.Id}" />
      </form>
    </td>
  </tr>

</table>
