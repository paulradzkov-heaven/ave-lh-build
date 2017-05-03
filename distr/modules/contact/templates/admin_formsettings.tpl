<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
  <tr>
    <td colspan="3" class="tableheader">{if $smarty.request.moduleaction=='new'}{#CONTACT_CREATE_FORM2#}{else}{#CONTACT_FORM_FIELDS#}{/if}</td>
  </tr>
  
  <tr>
    <td width="1%" class="first"><a {popup sticky=false text=$config_vars.CONTACT_FORM_TITEL|default:''} href="#"><img src="{$tpl_dir}/images/icon_help.gif" alt="" border="0" /></a></td>
    <td width="200" class="first">{#CONTACT_FORM_NAME2#}</td>
    <td class="second"><input name="Name" type="text" id="Name" value="{$row->Name}" size="50" /></td>
  </tr>
  
  <tr>
    <td width="1%" class="first"><a {popup sticky=false text=$config_vars.CONTACT_MAX_CHARS|default:''} href="#"><img src="{$tpl_dir}/images/icon_help.gif" alt="" border="0" /></a></td>
    <td width="200" class="first">{#CONTACT_MAX_CHARS_FIELD#}</td>
    <td class="second"><input name="MaxZeichen" type="text" id="MaxZeichen" value="{$row->MaxZeichen|default:20000}" size="10" maxlength="10" /></td>
  </tr>
  
  <tr>
    <td width="1%" class="first"><a {popup sticky=false text=$config_vars.CONTACT_DEFAULT_EMAIL|default:''} href="#"><img src="{$tpl_dir}/images/icon_help.gif" alt="" border="0" /></a></td>
    <td width="200" class="first">{#CONTACT_DEFAULT_RECIVER#}</td>
    <td class="second"><input name="Empfaenger" type="text" id="Empfaenger" value="{$row->Empfaenger}" size="50" /></td>
  </tr>
  
  <tr>
    <td width="1%" class="first"><a {popup width=350 sticky=false text=$config_vars.CONTACT_MULTI_LIST|default:''} href="#"><img src="{$tpl_dir}/images/icon_help.gif" alt="" border="0" /></a></td>
    <td width="200" class="first">{#CONTACT_MULTI_LIST_FIELD#}</td>
    <td class="second"><input name="Empfaenger_Multi" type="text" id="Empfaenger_Multi" value="{$row->Empfaenger_Multi}" style="width:90%" /></td>
  </tr>
  
  <tr>
    <td width="1%" class="first"><a {popup width=350 sticky=false text=$config_vars.CONTACT_SCODE_INFO|default:''} href="#"><img src="{$tpl_dir}/images/icon_help.gif" alt="" border="0" /></a></td>
    <td width="200" class="first">{#COUNACT_USE_SCODE_FIELD#}</td>
    <td class="second"><input type="radio" name="AntiSpam" value="1" {if $row->AntiSpam==1}checked{/if} />{#CONTACT_YES#}<input type="radio" name="AntiSpam" value="0" {if $row->AntiSpam!=1}checked{/if} />{#CONTACT_NO#}</td>
  </tr>
  
  <tr>
    <td class="first"><a {popup width=350 sticky=false text=$config_vars.CONTACT_MAX_SIZE_INFO|default:''} href="#"><img src="{$tpl_dir}/images/icon_help.gif" alt="" border="0" /></a></td>
    <td width="200" class="first">{#CONTACT_MAX_UPLOAD_FIELD#}</td>
    <td class="second"><input name="MaxUpload" type="text" id="MaxUpload" value="{$row->MaxUpload|default:120}" size="10" maxlength="5" /></td>
  </tr>
  
  <tr>
    <td class="first"><a {popup width=350 sticky=false text=$config_vars.CONTACT_SUBJECT_FIELD_INFO|default:''} href="#"><img src="{$tpl_dir}/images/icon_help.gif" alt="" border="0" /></a>  </td>
    <td width="200" class="first">{#CONTACT_USE_SUBJECT_FIELD#}</td>
    <td class="second"><input type="radio" name="ZeigeBetreff" value="1"  {if $row->ZeigeBetreff==1}checked{/if} />{#CONTACT_YES#}<input type="radio" name="ZeigeBetreff" value="0" {if $row->ZeigeBetreff!=1}checked{/if} />{#CONTACT_NO#}</td>
  </tr>
  
  <tr>
    <td class="first">&nbsp;</td>
    <td class="first">{#CONTACT_USE_COPY_FIELD#}</td>
    <td class="second"><input type="radio" name="ZeigeKopie" value="1"  {if $row->ZeigeKopie==1}checked{/if} />{#CONTACT_YES#}<input type="radio" name="ZeigeKopie" value="0" {if $row->ZeigeKopie!=1}checked{/if} />{#CONTACT_NO#}</td>
  </tr>
  
  <tr>
    <td class="first"><a {popup width=350 sticky=false text=$config_vars.CONTACT_DEFAULT_SUBJ_INFO|default:''} href="#"><img src="{$tpl_dir}/images/icon_help.gif" alt="" border="0" /></a> </td>
    <td width="200" class="first">{#CONTACT_DEFAULT_SUBJECT#}</td>
    <td class="second"><input name="StandardBetreff" type="text" id="StandardBetreff" value="{$row->StandardBetreff|stripslashes|escape:html}" size="50" /></td>
  </tr>
  
  <tr>
    <td class="first">&nbsp;</td>
    <td width="200" valign="top" class="first">{#CONTACT_PERMISSIONS_FIELD#}<br /><small>{#CONTACT_GROUPS_INFO#}</small></td>
    <td class="second">
      <select style="width:200px"  name="Gruppen[]" size="5" multiple="multiple">
      {foreach from=$groups item=group}
        <option value="{$group->Benutzergruppe}" {if @in_array($group->Benutzergruppe, $groups_form) || $smarty.request.moduleaction=="new"}selected="selected"{/if}>{$group->Name}</option>
      {/foreach}
      </select>
    </td>
  </tr>
  
  <tr>
    <td class="first">&nbsp;</td>
    <td width="200" valign="top" class="first">{#CONTACT_TEXT_NO_PERMISSION#}</td>
    <td class="second"><textarea style="width:500px; height:100px" name="TextKeinZugriff" id="TextKeinZugriff">{$row->TextKeinZugriff|escape:html|stripslashes}</textarea></td>
  </tr>
</table>
