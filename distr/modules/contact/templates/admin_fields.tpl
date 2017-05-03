<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{if $smarty.request.moduleaction=='new'}{#CONTACT_CREATE_FORM2#}{else}{#CONTACT_FORM_FIELDS#}{/if}</h2></div>
    <div class="HeaderText">{#CONTACT_FIELD_INFO#}</div>
</div>

<script type="text/javascript" language="JavaScript">

 function check_name() {ldelim}
   if (document.getElementById('StdWert').value == '') {ldelim}
     alert("{#CONTACT_ENTER_NAME#}");
     document.getElementById('StdWert').focus();
     return false;
   {rdelim}
   return true;
 {rdelim}

</script>
<br>

<form method="post" action="{$formaction}">
  {include file="$tpl_path/admin_formsettings.tpl"}<br />

{if $smarty.request.id != ''}
<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
  <tr class="tableheader">
    <td align="center"><img src="{$tpl_dir}/images/icon_del.gif" alt="" /></td>
    <td>{#CONTACT_FILED_NAME#}</td>
    <td>{#CONTACT_FIELD_TYPE#} </td>
    <td>{#CONTACT_DEFAULT_VALUE#}</td>
    <td>{#CONTACT_FIELD_POSITION#}</td>
    <td>{#CONTACT_REQUIRED_FIELD#}</td>
    <td>{#CONTACT_FIELD_ACTIVE#}</td>
  </tr>
  
  {foreach from=$items item=item}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
    <td width="1%"><input {popup sticky=false text=$config_vars.CONTACT_MARK_DELETE|default:''} name="del[{$item->Id}]" type="checkbox" id="del[{$item->Id}]" value="1" /></td>
    
    <td width="200"><input style="width:200px" name="FeldTitel[{$item->Id}]" type="text" id="FeldTitel[{$item->Id}]" value="{$item->FeldTitel|escape:html|stripslashes}" /></td>
    
    <td width="150">
      <select name="Feld[{$item->Id}]" id="Feld[{$item->Id}]">
        <option value="text" {if $item->Feld=='text'}selected{/if}>{#CONTACT_TEXT_FILED#}</option>
        <option value="textfield" {if $item->Feld=='textfield'}selected{/if}>{#CONTACT_MULTI_FIELD#}</option>
        <option value="checkbox" {if $item->Feld=='checkbox'}selected{/if}>{#CONTACT_CHECKBOX_FIELD#}</option>
        <option value="dropdown" {if $item->Feld=='dropdown'}selected{/if}>{#CONTACT_DROPDOWN_FIELD#}</option>
        <option value="fileupload" {if $item->Feld=='fileupload'}selected{/if}>{#CONTACT_UPLOAD_FIELD#}</option>
      </select>
    </td>
    
    <td width="200">{if $item->Feld != 'fileupload'}<input style="width:200px" type="text" name="StdWert[{$item->Id}]" value="{$item->StdWert|escape:html|stripslashes}" />{else}&nbsp;{/if}</td>
    
    <td width="50"><input name="Position[{$item->Id}]" type="text" id="Position[{$item->Id}]" size="5" maxlength="3" value="{$item->Position}" /></td>
    
    <td><input type="radio" name="Pflicht[{$item->Id}]" value="1" {if $item->Pflicht==1}checked{/if} />{#CONTACT_YES#}<input type="radio" name="Pflicht[{$item->Id}]" value="0" {if $item->Pflicht!=1}checked{/if} />{#CONTACT_NO#}</td>
    
    <td><input type="radio" name="Aktiv[{$item->Id}]" value="1" {if $item->Aktiv==1}checked{/if} />{#CONTACT_YES#}<input type="radio" name="Aktiv[{$item->Id}]" value="0" {if $item->Aktiv!=1}checked{/if} />{#CONTACT_NO#}</td>
  </tr>
  {/foreach}
</table>
<br />
{/if}
<input type="submit" class="button" value="{#CONTACT_BUTTON_SAVE#}" />
</form>

<br />
<br /> 

{if $smarty.request.id != ''}
<h4>{#CONTACT_NEW_FILED_ADD#}</h4>

  <form method="post" action="index.php?do=modules&action=modedit&mod=contact&moduleaction=save_new&cp={$sess}&id={$smarty.request.id}&pop=1" name="new">
    <table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
      <tr class="tableheader">
        <td>{#CONTACT_FILED_NAME#}</td>
        <td>{#CONTACT_FIELD_TYPE#} </td>
        <td>{#CONTACT_DEFAULT_VALUE#} </td>
        <td>{#CONTACT_FIELD_POSITION#}</td>
        <td>{#CONTACT_REQUIRED_FIELD#}</td>
        <td>{#CONTACT_FIELD_ACTIVE#}</td>
      </tr>
  
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
        <td width="200"><input style="width:200px" name="FeldTitel" type="text" id="FeldTitel" value="" /> </td>
        <td width="150">
          <select name="Feld" id="Feld">
            <option value="text">{#CONTACT_TEXT_FILED#}</option>
            <option value="textfield">{#CONTACT_MULTI_FIELD#}</option>
            <option value="checkbox">{#CONTACT_CHECKBOX_FIELD#}</option>
            <option value="dropdown">{#CONTACT_DROPDOWN_FIELD#}</option>
            <option value="fileupload">{#CONTACT_UPLOAD_FIELD#}</option>
          </select>
        </td>
    
        <td width="200"><input style="width:200px" type="text" name="StdWert" value="" id="StdWert"/></td>
        <td width="50"><input name="Position" type="text" id="Position" size="5" maxlength="3" value="1" /></td>
        <td><input type="radio" name="Pflicht" value="1" /> {#CONTACT_YES#} <input name="Pflicht" type="radio" value="0" checked="checked" /> {#CONTACT_NO#}</td>
        <td><input name="Aktiv" type="radio" value="1" checked="checked" /> {#CONTACT_YES#} <input name="Aktiv" type="radio" value="0" /> {#CONTACT_NO#}</td>
      </tr>
    </table>

    <br />
    <input type="submit" class="button" value="{#CONTACT_BUTTON_ADD#}" />
  </form>
{/if}
