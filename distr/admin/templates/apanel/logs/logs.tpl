
<div id="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_logs"></div>
  <div class="HeaderTitle"><h2>{#LOGS_SUB_TITLE#}</h2></div>
  <div class="HeaderText">{#LOGS_TIP#}</div>
</div>
<div class="upPage"></div>
<br>

<form method="">
  <table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
    <tr>
      <td width="70" class="tableheader">{#LOGS_ID#}</td>
      <td width="100" class="tableheader">{#LOGS_IP#}</td>
      <td width="130" class="tableheader">{#LOGS_DATE#}</td>
      <td class="tableheader">{#LOGS_ACTION#}</td>
    </tr>

    <tr>
      <td colspan="4" class="second">
        <textarea wrap="off" style="width:100%" name="textfield" rows="30">
        {foreach from=$logs item=log}
          {$log->Id}&nbsp;|&nbsp;{$log->IpCode}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;{$log->Zeit|date_format:$config_vars.LOGS_DATE_FORMAT} {#LOGS_IN#} {$log->Zeit|date_format:$config_vars.LOGS_DATE_FORMAT2}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| {$log->Meldung}
         {/foreach}
        </textarea>
      </td>
    </tr>

    <tr>
      <td colspan="4" class="second">
        <input onclick="if(confirm('{#LOGS_DELETE_CONFIRM#}')) location.href='index.php?do=logs&action=delete&cp={$sess}';" type="button" class="button" value="{#LOGS_BUTTON_DELETE#}" />
        <input onclick="location.href='index.php?do=logs&action=export&cp={$sess}'" class="button" type="button" value="{#LOGS_BUTTON_EXPORT#}" />
      </td>
    </tr>
  </table>
</form>
