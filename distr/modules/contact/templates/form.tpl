{if $no_access==1}
  <p>{$TextKeinZugriff}</p>
{else}
  <br />
  <script language="javascript" type="text/javascript">
    function check_form_{$im}()
      {assign var=ecolor value=''}
  {ldelim}
     errors = '';
    
     if(document.getElementById('l_Betreff').value == '')
      {ldelim}
        alert("{#CONTACT_CHECK_TITLE#}");
        document.getElementById('l_Betreff').focus(); 
        return false;
      {rdelim}
  
  
     if(document.getElementById('l_Email').value == '')
      {ldelim}
        alert("{#CONTACT_CHECK_EMAIL#}");
        document.getElementById('l_Email').focus(); 
        return false;
      {rdelim}
     
     if(document.getElementById('l_Email').value.indexOf('@') == -1)
      {ldelim}
        alert("{#CONTACT_CHECK_EMAIL2#}");
        document.getElementById('l_Email').focus(); 
        return false;
      {rdelim}
  
    {foreach from=$felder item=feld}
      {if $feld->Pflicht=='1'}
      {if $feld->Feld == 'textfield' || $feld->Feld == 'text'}
      
      if(document.getElementById('cp_{$feld->Id}').value == '')
      {ldelim}
        alert("{#CONTACT_CHECK_START#} {$feld->FeldTitel}{#CONTACT_CHECK_END#}");
        document.getElementById('cp_{$feld->Id}').focus(); 
        return false;
      {rdelim}
    
      {elseif $feld->Feld=='checkbox'}
      
      if(document.getElementById('cp_{$feld->Id}').checked == false)
      {ldelim}
        alert("{#CONTACT_CHECK_START2#} {$feld->FeldTitel}{#CONTACT_CHECK_END2#}");
        return false;
      {rdelim}
    
      {elseif $feld->Feld=='dropdown'}
        
      if(document.getElementById('cp_{$feld->Id}').value == "")
      {ldelim}
        alert("{#CONTACT_CHECK_START2#} {$feld->FeldTitel}{#CONTACT_CHECK_END2#}");
        return false;
      {rdelim}
      
      {/if}
     
      {/if}
    {/foreach}
  
    if(document.getElementById('secure_{$im}').value == '')
    {ldelim}
      alert("{#CONTACT_CHECK_CODE#}");
      document.getElementById('secure_{$im}').focus(); 
      return false;
    {rdelim}
  
      return true;
    
{rdelim}
</script>

<div id="module_content">
<p>{#CONTACT_REQUIRED_INFO#}</p><br />
<form action="{$contact_action}" method="post" enctype="multipart/form-data" name="contact{$im}" id="contact{$im}" onsubmit="return check_form_{$im}();">
  
  {if $wrongSecureCode==1}
    <body onload="location.href='#ws'">
    <a name="ws"></a>
    <h2>{#CONTACT_WRONG_CODE#}</h2>
    <br /><br />
  {/if}


  {if $empaenger}
    <div class="mod_contact_left">{#CONTACT_FORM_RECIVER#}</div>
    <div class="mod_contact_right">
      <select style="width:200px" name="Reciever">
      {section name=em loop=$empaenger}
      {assign var=e_id value=$e_id+1}
        <option value="{$e_id}">{$empaenger[em]}</option>
      {/section}
      </select>
    </div>
    <div class="clear"></div>
   {/if}

  
  {if !$StandardBetreff}
    <div class="mod_contact_left"><label for="l_{#Subject#}">{#CONTACT_FORM_SUBJECT#} <span class="mod_contact_left_star">*</span></label></div>
    <div class="mod_contact_right"><input type="text" value="{$smarty.request.Betreff|stripslashes|escape:html}" name="Betreff" id="l_Betreff" style="width:95%" /></div>
    <div class="clear"></div>
  {else}
    <input type="hidden" value="{$StandardBetreff}" id="l_Betreff" name="Betreff" />
    {* <span id="S_Betreff"></span> *}
  {/if}


  <div class="mod_contact_left"><label for="l_Email">{#CONTACT_FORM_EMAIL#} <span class="mod_contact_left_star">*</span></label></div>
  <div class="mod_contact_right"><input type="text" value="{$smarty.request.Email|default:$smarty.session.cp_email|stripslashes|escape:html}" name="Email" id="l_Email" style="width:95%" /></div>
  <div class="clear"></div>


  {foreach from=$felder item=feld}
    <div class="mod_contact_left">
      <label for="cp_{$feld->Id}">{$feld->FeldTitel|stripslashes|escape:html}: {if $feld->Pflicht=='1'}<span class="mod_contact_left_star">*</span>{/if}</label>

      {if $feld->Feld=='fileupload' && $maxupload >= 1}
         <br /><small>{#CONTACT_FORM_MAX_FILE#} {$maxupload} {#CONTACT_FILE_KB#}</small>
      {/if}
    </div>

    <div class="mod_contact_right">
      {if $feld->Feld=='textfield'}
        {* <small><span id="S_cp_{$feld->Id}"></span></small> *}
        <textarea id="cp_{$feld->Id}" name="{$feld->FeldTitel|replace:' ':'_'}" style="width:95%;height:100px">{$feld->value|stripslashes|escape:html}</textarea>
      {/if}

      {if $feld->Feld=='text'}
        {* <small><span id="S_cp_{$feld->Id}"></span></small> *}
        <input type="text" value="{$feld->value|default:$feld->StdWert|stripslashes|escape:html}" name="{$feld->FeldTitel|replace:' ':'_'}" id="cp_{$feld->Id}" style="width:95%" />
      {/if}

      {if $feld->Feld=='checkbox'}
        {* <small><span id="S_cp_{$feld->Id}"></span></small> *}
        <input style="border:0px; background-color:transparent" type="checkbox" name="{$feld->FeldTitel|replace:' ':'_'}" id="cp_{$feld->Id}" value="{$feld->StdWert|default:1}" />
        {$feld->StdWert}
      {/if}

      {if $feld->Feld=='fileupload'}
        {*<small><span id="S_cp_{$feld->Id}"></span></small>*}
        <input name="upfile[]" type="file" size="20" /><br />
      {/if}

      {if $feld->Feld=='dropdown'}
        {*<small><span id="S_cp_{$feld->Id}"></span></small>*}
        <select style="width:200px" id="cp_{$feld->Id}" name="{$feld->FeldTitel|replace:' ':'_'}">
          <option>&nbsp;</option>
          {foreach from=$feld->StdWert item=v}
          <option value="{$v}">{$v}</option>
          {/foreach}
        </select>
      {/if}
    </div>
    <div class="clear"></div>
  {/foreach}


  {if $ZeigeKopie==1}
    <div class="mod_contact_left"><label for="l_sendcopy">{#CONTACT_SEND_COPY#}</label></div>
    <div class="mod_contact_right"><input style="border:0px; background-color:transparent"  name="sendcopy" type="checkbox" id="l_sendcopy" value="1" /></div>
    <div class="clear"></div>
  {/if}

  {if $im != ''}
    <div class="mod_contact_left"><label for="secure_{$im}">{#CONTACT_FORM_CODE#}</label></div>
    <div class="mod_contact_right"><img src="/nospam_{$im}.jpeg" alt="" width="121" height="41" border="0" /></div>
    
    <div class="clear"></div>
    
    <div class="mod_contact_left"><label for="secure_{$im}">{#CONTACT_FORM_CODE_ENTER#}</label></div>
    <div class="mod_contact_right">
      {*<small><span id="S_secure_{$im}"></span></small> *}
      <input name="cpSecurecode" type="text" id="secure_{$im}" style="width:100px" maxlength="7" />
    </div>
  {/if}

    <div class="clear">&nbsp;</div>


    <div class="mod_contact_left"></div>
    <div class="mod_contact_right">
      <input name="SecureImageId" type="hidden" value="{$im}" />
      <input name="FormId" type="hidden" value="{$formid}" />
      <input name="FId" type="hidden" value="{$fid}" />
      <input name="contact_action" type="hidden" value="DoPost" />
      <input name="modules" type="hidden" value="contact" />
      <input type="submit" class="button" value="{#CONTACT_BUTTON_SEND#}" />
      <input class="button" type="reset" />
    </div>

</form>
</div>
{/if}


