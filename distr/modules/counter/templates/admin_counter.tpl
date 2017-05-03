<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#COUNTER_MODULE_NAME#}</h2></div>
    <div class="HeaderText">{#COUNTER_MODULE_INFO#}</div>
</div>
<script type="text/javascript" language="JavaScript">

 function check_name() {ldelim}
   if (document.getElementById('Name').value == '') {ldelim}
     alert("{#COUNTER_ENTER_NAME#}");
     document.getElementById('Name').focus();
     return false;
   {rdelim}
   return true;
 {rdelim}

</script>
<br>
<form method="post" action="index.php?do=modules&action=modedit&mod=counter&moduleaction=quicksave&cp={$sess}">
  <table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
    <tr class="tableheader">
      <td align="center" width="1%"><img src="{$tpl_dir}/images/icon_del.gif" alt="" /></td>
      <td>{#COUNTER_NAME_TABLE#} </td>
      <td>{#COUNTER_SYSTEM_TAG#}</td>
      <td width="5%">{#COUNTER_SHOW_ALL#}</td>
      <td width="5%">{#COUNTER_SHOW_TODAY#}</td>
      <td width="5%">{#COUNTER_SHOW_YESTODAY#}</td>
      <td width="5%" nowrap="nowrap">{#COUNTER_SHOW_MONTH#}</td>
      <td width="5%" nowrap="nowrap">{#COUNTER_SHOW_YEAR#}</td>
      <td>{#COUNTER_ACTION_TABLE#}</td>
    </tr>
  
    {foreach from=$items item=item} 
  
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
      <td><input {popup sticky=false text=$config_vars.COUNTER_MARK_DELETE|default:''} name="del[{$item->Id}]" type="checkbox" id="del[{$item->Id}]" value="1" /></td>
      <td width="150"><input name="CName[{$item->Id}]" type="text" id="CName[{$item->Id}]" value="{$item->CName|escape:html|stripslashes}" style="width: 250px;"></td>
      <td width="150"><input readonly="" name="CpEngineTag{$item->Id}" type="text" id="CpEngineTag_{$item->Id}" value="[cp_counter:{$item->Id}]"></td>
      <td align="center">{$item->all}</td>
      <td align="center">{$item->today}</td>
      <td align="center">{$item->yest}</td>
      <td align="center">{$item->lastm}</td>
      <td align="center">{$item->lasty}</td>
      <td width="1%" align="center">
        <a {popup sticky=false text=$config_vars.COUNTER_SHOW_MORE|default:''} href="javascript:void(0);" onclick="cp_pop('index.php?do=modules&action=modedit&mod=counter&moduleaction=view_referer&id={$item->Id}&cp={$sess}&pop=1','960','700','1','modcontactedit');">
        <img src="{$tpl_dir}/images/icon_look.gif" alt="" border="0" /></a>
      </td>
   </tr>
   
   {/foreach}
</table>

<br />
<input type="submit" class="button"  value="{#COUNTER_BUTTON_SAVE#}" />
</form>

<h4>{#COUNTER_NEW_COUNTER#}</h4>

<form method="post" action="index.php?do=modules&action=modedit&mod=counter&moduleaction=new_counter&cp={$sess}" onSubmit="return check_name();">
  <table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
    <tr class="tableheader">
      <td width="180">{#COUNTER_NAME_COUNTER#}</td>
    </tr>
    <tr>
      <td width="180" class="first">
        <input name="CName" type="text" id="Name" size="40" maxlength="50" />&nbsp;<input type="submit" class="button"  value="{#COUNTER_BUTTON_CREATE#}" />
      </td>
    </tr>
  </table>
</form>