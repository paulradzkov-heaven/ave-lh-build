<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#CONTACT_MODULE_NAME#}
  {if $smarty.request.moduleaction == 'showmessages_new'}
    <span style="color: #000;"> ({#CONTACT_TITLE_NOANSWERED#})</span>
  {else}
    <span style="color: #000;"> ({#CONTACT_TITLE_ANSWERED#})</span>
  {/if}
	</h2></div>
    <div class="HeaderText">&nbsp;</div>
</div>
<br>
<div class="infobox">
  <a href="index.php?do=modules&action=modedit&mod=contact&moduleaction=1&cp={$sess}">{#CONTACT_FORM_LIST#}</a>
</div>
<br>
<form method="post" action="index.php?do=modules&action=modedit&mod=contact&moduleaction=quicksave&cp={$sess}">
  <table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
    <tr class="tableheader">
      <td align="center" width="1%"><img src="{$tpl_dir}/images/icon_del.gif" alt="" /></td>
      <td>{#CONTACT_MESSAGE_SUBJECT#} </td>
      <td>{#CONTACT_FROM_NAME#}</td>
      <td>{#CONTACT_SEND_TIME#}</td>
      <td>{#CONTACT_ANSWER_TIME#}</td>
      <td>{#CONTACT_ACTIONS#}</td>
    </tr>
    
    {foreach from=$items item=item} 
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
      <td><input {popup sticky=false text=$config_vars.CONTACT_MARK_DELETE|default:''} name="del[{$item->Id}]" type="checkbox" id="del[{$item->Id}]" value="1" /></td>
      <td><a href="javascript:void(0);" onclick="cp_pop('index.php?do=modules&action=modedit&mod=contact&moduleaction=showmessages_new&sub=view&cp={$sess}&id={$item->Id}&pop=1','','','1','modcontactedit');"><strong>{$item->Betreff|stripslashes|escape:html|truncate:30}</strong></a></td>
      <td><a href="mailto:{$item->Email}">{$item->Email}</a></td>
      <td>{$item->Datum|date_format:$config_vars.CONTACT_DATE_FORMAT}</td>
      <td>
        {if $smarty.request.moduleaction=='showmessages_new'}
          -
        {else}
          {$item->Aw_Zeit|date_format:$config_vars.CONTACT_DATE_FORMAT}
        {/if}
      </td>
      <td width="1%" align="center">
      {if $smarty.request.moduleaction=='showmessages_new'}
        <a {popup sticky=false text=$config_vars.CONTACT_SHOW_ANSWER|default:''} href="javascript:void(0);" onclick="cp_pop('index.php?do=modules&action=modedit&mod=contact&moduleaction=showmessages_new&sub=view&cp={$sess}&id={$item->Id}&pop=1','','','1','modcontactedit');"><img src="{$tpl_dir}/images/icon_edit.gif" alt="" border="0" /></a>
      {else}
        <a {popup sticky=false text=$config_vars.CONTACT_SHOW_ANSWER|default:''} href="javascript:void(0);" onclick="cp_pop('index.php?do=modules&action=modedit&mod=contact&moduleaction=showmessages_new&sub=view&cp={$sess}&id={$item->Id}&pop=1&reply=no','','','1','modcontactedit');"><img src="{$tpl_dir}/images/icon_edit.gif" alt="" border="0" /></a>
      {/if}
      </td>
    </tr>
    {/foreach}
  </table>

<br  />
<input type="submit" class="button" value="{#CONTACT_BUTTON_DELETE#}" />
</form>

<br  />
{if $page_nav}
  <div class="infobox">{$page_nav}</div>
{/if}