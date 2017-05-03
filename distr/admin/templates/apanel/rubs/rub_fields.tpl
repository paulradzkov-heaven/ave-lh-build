<div id="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_rubs"></div>
    <div class="HeaderTitle"><h2>{#RUBRIK_EDIT_FIELDS#}<span style="color: #000;"> &gt; {$RubrikName}</span></h2></div>
{if !$rub_fields}
  <div class="HeaderText">{#RUBRIK_NO_FIELDS#}</div>
{else}
  <div class="HeaderText">{#RUBRIK_FIELDS_INFO#}</div>
{/if}
</div>
<div class="upPage"></div>
<br>


{if $rub_fields}
<form name="kform" action="index.php?do=rubs&amp;action=edit&amp;Id={$smarty.request.Id}&amp;cp={$sess}" method="post">
<table width="100%" border="0" cellspacing="1" cellpadding="7" class="tableborder">
  <tr class="tableheader">
    <td width="1%" align="center"><input name="allbox" type="checkbox" id="d" onclick="selall();" value="" /></td>
    <td width="5%">{#RUBRIK_ID#}</td>
    <td>{#RUBRIK_FIELD_NAME#}</td>
    <td>{#RUBRIK_FIELD_TYPE#}</td>
    <td>{#RUBRIK_FIELD_DEFAULT#}</td>
    <td width="5%">{#RUBRIK_POSITION#}</td>
  </tr>

  {foreach from=$rub_fields item=rf}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
    <td width="1%"><input {popup sticky=false text=$config_vars.RUBRIK_MARK_DELETE|default:''} name="del[{$rf->Id}]" type="checkbox" id="del[{$rf->Id}]" value="1" /></td>
    <td width="5%">[cprub:{$rf->Id}]</td>
    <td><input name="Titel[{$rf->Id}]" type="text" id="Titel[{$rf->Id}]" value="{$rf->Titel|escape:html}" style="width: 300px;" /></td>
    <td>
    <select name="RubTyp[{$rf->Id}]" id="RubTyp[{$rf->Id}]">
    {section name=feld loop=$felder}
      <option value="{$felder[feld].id}" {if $rf->RubTyp==$felder[feld].id}selected{/if}>{$felder[feld].name}</option>
    {/section}
    </select>
    </td>
    <td><input name="StdWert[{$rf->Id}]" type="text" id="StdWert[{$rf->Id}]" value="{$rf->StdWert}" style="width: 200px;" /></td>
    <td><input name="RubPosition[{$rf->Id}]" type="text" id="RubPosition[{$rf->Id}]" value="{$rf->rubric_position}" size="5" maxlength="5" /></td>
  </tr>
  {/foreach}
</table>

<br />

 <input type="hidden" name="submit" value="" id="nf_save_next" />
 <input class="button" type="submit" value="{#RUBRIK_BUTTON_SAVE#}" onclick="document.getElementById('nf_save_next').value='save'" />
 <input class="button" type="submit" value="{#RUBRIK_BUTTON_TEMPL#}" onclick="document.getElementById('nf_save_next').value='next'" />
</form>

<br /><br />
{/if}

<h4>{#RUBRIK_NEW_FIELD#}</h4>
<form action="index.php?do=rubs&amp;action=edit&amp;Id={$smarty.request.Id}&amp;cp={$sess}" method="post">
<table width="100%" border="0" cellspacing="1" cellpadding="7" class="tableborder">
  <tr class="tableheader">
    <td>{#RUBRIK_FIELD_NAME#}</td>
    <td>{#RUBRIK_FIELD_TYPE#}</td>
    <td>{#RUBRIK_FIELD_DEFAULT#}</td>
    <td width="5%">{#RUBRIK_POSITION#}</td>
  </tr>

 <tr>
   <td class="second">
     <input name="TitelNew" type="text" id="TitelNew" value="" style="width: 300px;" >
   </td>

   <td class="second">
     <select name="RubTypNew" id="RubTypNew">
     {section name=feld loop=$felder}
       <option value="{$felder[feld].id}">{$felder[feld].name}</option>
     {/section}
     </select>
   </td>

   <td class="second">
      <input name="StdWertNew" type="text" id="StdWertNew" value="" style="width: 200px;" />
   </td>

   <td class="second">
      <input name="RubPositionNew" type="text" id="RubPositionNew" value="10" size="5" maxlength="5" />
   </td>
 </tr>
</table>

<br />

<input type="hidden" name="submit" value="" id="nf_hidd" />
<input class="button" type="submit" value="{#RUBRIK_BUTTON_ADD#}" onclick="document.getElementById('nf_hidd').value='newfield'" />
<br /> <br />
</form>

{if cp_perm('rub_perms')}
<br />
<form action="index.php?do=rubs&amp;action=edit&amp;Id={$smarty.request.Id}&amp;cp={$sess}" method="post">
<h4>{#RUBRIK_SET_PERMISSION#}</h4>
  <table width="100%" border="0" cellspacing="1" cellpadding="7" class="tableborder">
    <tr class="tableheader">
      <td width="10%">{#RUBRIK_USER_GROUP#}</td>
      <td width="15%" align="center">{#RUBRIK_DOC_READ#}</td>
      <td width="15%"><div align="center">{#RUBRIK_ALL_PERMISSION#}</div></td>
      <td width="15%"><div align="center">{#RUBRIK_CREATE_DOC#}</div></td>
      <td width="15%"><div align="center">{#RUBRIK_CREATE_DOC_NOW#}</div></td>
      <td width="15%"><div align="center">{#RUBRIK_EDIT_OWN#}</div></td>
      <td width="15%"><div align="center">{#RUBRIK_EDIT_OTHER#}</div></td>
    </tr>

    {foreach from=$groups item=group}
    {assign var=doall value=$group->doall}
    <tr>
      <td class="second"><strong>{$group->Name|escape:html}</strong></td>
      <td align="center" class="first">
      {if $group->doall_h==1}
        <input type="hidden" name="perm[{$group->Benutzergruppe}][]" value="docread" />
        <input {popup sticky=false text=$config_vars.RUBRIK_VIEW_TIP|default:''}  name="perm[{$group->Benutzergruppe}][]" type="checkbox" value="docread" checked disabled="disabled"/>
      {else}
        <input {popup sticky=false text=$config_vars.RUBRIK_VIEW_TIP|default:''}  name="perm[{$group->Benutzergruppe}][]" type="checkbox" value="docread" {if in_array('docread', $group->permissions) || in_array('alles', $group->permissions)}checked{/if}  />{/if}
      </td>

      <td class="second">
       <div align="center">
       {if $group->doall_h==1}
         <input type="hidden" name="perm[{$group->Benutzergruppe}][]" value="alles" />
         <input {popup sticky=false text=$config_vars.RUBRIK_ALL_TIP|default:''} name="perm[{$group->Benutzergruppe}][]" type="checkbox" value="alles" checked disabled="disabled"/>
       {else}
         <input {popup sticky=false text=$config_vars.RUBRIK_ALL_TIP|default:''} name="perm[{$group->Benutzergruppe}][]" type="checkbox" value="alles" {if in_array('alles', $group->permissions)}checked{/if} {if $group->Benutzergruppe==2}disabled{/if} />
       {/if}
       </div>
      </td>

      <td class="first">
      <div align="center">
        <input type="hidden" name="Benutzergruppe[{$group->Benutzergruppe}]" value="{$group->Benutzergruppe}" />
        {if $group->doall_h==1}
          <input name="{$group->Benutzergruppe}" type="checkbox" value="1"  {$doall} />
          <input {popup sticky=false text=$config_vars.RUBRIK_DOC_TIP|default:''} type="hidden" name="perm[{$group->Benutzergruppe}][]" value="new" />
        {else}
          <input {popup sticky=false text=$config_vars.RUBRIK_DOC_TIP|default:''} onclick="document.getElementById('newnow_{$group->Benutzergruppe}').checked = '';" id="new_{$group->Benutzergruppe}" name="perm[{$group->Benutzergruppe}][]" type="checkbox" value="new" {if in_array('new', $group->permissions) || in_array('alles', $group->permissions)}checked{/if} {if $group->Benutzergruppe==2}disabled{/if} />
        {/if}
      </div>
      </td>



      <td class="second">
      <div align="center">
        <input type="hidden" name="Benutzergruppe[{$group->Benutzergruppe}]" value="{$group->Benutzergruppe}" />
         {if $group->doall_h==1}
           <input name="{$group->Benutzergruppe}" type="checkbox" value="1"  {$doall} />
           <input {popup sticky=false text=$config_vars.RUBRIK_DOC_NOW_TIP|default:''} type="hidden" name="perm[{$group->Benutzergruppe}][]" value="newnow" />
         {else}
           <input {popup sticky=false text=$config_vars.RUBRIK_DOC_NOW_TIP|default:''} onclick="document.getElementById('new_{$group->Benutzergruppe}').checked = '';" id="newnow_{$group->Benutzergruppe}"  name="perm[{$group->Benutzergruppe}][]" type="checkbox" value="newnow" {if in_array('newnow', $group->permissions) || in_array('alles', $group->permissions)}checked{/if} {if $group->Benutzergruppe==2}disabled{/if} />
         {/if}
      </div>
      </td>

      <td class="first">
      <div align="center">
        {if $group->doall_h==1}
          <input name="{$group->Benutzergruppe}" type="checkbox" value="1"  {$doall} />
          <input {popup sticky=false text=$config_vars.RUBRIK_OWN_TIP|default:''} type="hidden" name="perm[{$group->Benutzergruppe}][]" value="editown" />
        {else}
          <input {popup sticky=false text=$config_vars.RUBRIK_OWN_TIP|default:''} id="editown_{$group->Benutzergruppe}" name="perm[{$group->Benutzergruppe}][]" type="checkbox" value="editown" {if in_array('editown', $group->permissions) || in_array('alles', $group->permissions)}checked{/if} {if $group->Benutzergruppe==2}disabled{/if} />
        {/if}
      </div>
      </td>

    <td class="second">
      <div align="center">
        {if $group->doall_h==1}
          <input {popup sticky=false text=$config_vars.RUBRIK_OTHER_TIP|default:''} name="{$group->Benutzergruppe}" type="checkbox" value="1"  {$doall} />
        {else}
          <input {popup sticky=false text=$config_vars.RUBRIK_OTHER_TIP|default:''} name="perm[{$group->Benutzergruppe}][]" type="checkbox" value="editall" {if in_array('editall', $group->permissions) || in_array('alles', $group->permissions)}checked{/if} {if $group->Benutzergruppe==2}disabled{/if} />
        {/if}
      </div>
    </td>
  </tr>
  {/foreach}
 </table>
<br />

<input type="hidden" name="submit" value="" id="nf_sub" />
<input class="button" type="submit" value="{#RUBRIK_BUTTON_PERM#}" onclick="document.getElementById('nf_sub').value='saveperms'" />

</form>
{/if}