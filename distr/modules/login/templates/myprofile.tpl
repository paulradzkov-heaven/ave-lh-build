<div id="module_header">
  <h2>{#LOGIN_CHANGE_DETAILS#}</h2>
</div>

<div id="module_content">
  <p><em>{#LOGIN_DETAILS_INFO#}</em></p>

{if $errors}
<div class="infobox">
  <h2 class="error">{#LOGIN_ERRORS#}</h2>
  <ul>
   {foreach from=$errors item=error}
    <li class="regerror">{$error}</li>
   {/foreach}
  </ul>
</div>
{/if}


{if $changed==1}
  <p>&nbsp;</p><p><h2>{#LOGIN_CHANGED_OK#}</h2></p><p>&nbsp;</p>
{/if}


<form method="post" action="/login/edit/update/">

  <div class="formleft"><label for="l_reg_Firma">{#LOGIN_YOUR_COMPANY#}</label></div>
  <div class="formright">
    <input name="Firma" type="text" id="l_reg_Firma" style="width:200px" value="{$smarty.request.Firma|default:$row.Firma}" size="80" />
  </div>
  
  <div class="clearall"></div>

  <div class="formleft"><label for="l_reg_firstname">{#LOGIN_YOUR_FIRSTNAME#}</label></div>
  <div class="formright">
   <input name="Vorname" type="text" id="l_reg_firstname" style="width:200px" value="{$smarty.request.Vorname|default:$row.Vorname}" size="80" />
  </div>
 
  <div class="clearall"></div>

  <div class="formleft"><label for="l_reg_lastname">{#LOGIN_YOUR_LASTNAME#}</label></div>
  <div class="formright">
    <input name="Nachname" type="text" id="l_reg_lastname" style="width:200px" value="{$smarty.request.Nachname|default:$row.Nachname}" size="50" />
  </div>
  
  <div class="clearall"></div>

  <div class="formleft"><label for="l_street">{#LOGIN_YOUR_STREET#}</label> / <label for="l_nr">{#LOGIN_YOUR_HOUSE#}</label></div>
  <div class="formright">
    <input name="Strasse" type="text" id="l_street" style="width:150px" value="{$smarty.request.Strasse|default:$row.Strasse|escape:html}" size="50" maxlength="50" />
    <input name="HausNr" type="text" id="l_nr" style="width:40px" value="{$smarty.request.HausNr|default:$row.HausNr|escape:html}" size="50" maxlength="10" />
  </div>

  <div class="clearall"></div>

  <div class="formleft"><label for="l_zip">{#LOGIN_YOUR_ZIP#}</label> / <label for="l_town">{#LOGIN_YOUR_TOWN#}</label></div>
  <div class="formright">
    <input name="Postleitzahl" type="text" id="l_zip" style="width:40px" value="{$smarty.request.Postleitzahl|default:$row.Postleitzahl|escape:html}" size="50" maxlength="15" />
    <input name="Ort" type="text" id="l_town" style="width:150px" value="{$smarty.request.Ort|default:$row.Ort|escape:html}" size="50" maxlength="50" />
  </div>

  <div class="clearall"></div>

  <div class="formleft"><label for="l_email">{#LOGIN_YOUR_MAIL#}</label></div>
  <div class="formright">
    <input name="Email" type="text" id="l_email" style="width:200px" value="{$smarty.request.Email|default:$row.Email}" size="80" />
  </div>
  
  <div class="clearall"></div>

  <div class="formleft"><label for="l_phone">{#LOGIN_YOUR_PHONE#}</label></div>
  <div class="formright">
    <input name="Telefon" type="text" id="l_phone" style="width:200px" value="{$smarty.request.Telefon|default:$row.Telefon}" size="80" />
  </div>

  <div class="clearall"></div>

  <div class="formleft"><label for="l_fax">{#LOGIN_YOUR_FAX#}</label></div>
  <div class="formright">
    <input name="Telefax" type="text" id="l_fax" style="width:200px" value="{$smarty.request.Telefax|default:$row.Telefax}" size="80" />
  </div>
  
  <div class="clearall"></div>

  <div class="formleft"><label for="l_geb">{#LOGIN_YOUR_BIRTHDAY#}</label></div>
  <div class="formright">
    <input name="GebTag" type="text" id="l_geb" style="width:100px" value="{$smarty.request.GebTag|default:$row.GebTag}" size="80" maxlength="10" /> 
    {#LOGIN_DATE_FORMAT#}
  </div>
  
  <div class="clearall"></div>

  <div class="formleft"><label for="l_land">{#LOGIN_YOUR_COUNTRY#}</label></div> 
  <div class="formright">
    <select name="Land" id="l_land">
      {foreach from=$available_countries item=land}
        <option value="{$land->LandCode}" {if $row.Land==$land->LandCode}selected="selected"{/if}>{$land->LandName}</option>
      {/foreach}
    </select>
  </div>

  <div class="clearall"></div> 

  <div class="formleft">&nbsp;</div>
  <div class="formright"><input class="button" type="submit" value="{#LOGIN_BUTTON_CHANGE#}" />
    <input name="Email_Old" type="hidden" id="Email_Old" value="{$row.Email}" />
  </div>

  <div class="clearall"></div>
</form>
</div>
