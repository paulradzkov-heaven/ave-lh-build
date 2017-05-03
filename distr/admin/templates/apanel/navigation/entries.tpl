<div id="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_navi"></div>
    <div class="HeaderTitle"><h2>{#NAVI_SUB_TITLE2#}<span style="color: #000;">&nbsp;&gt;&nbsp;{$NavigatonName|escape:html|stripslashes}</span></h2></div>
    <div class="HeaderText">&nbsp;</div>
</div>
<script language="javascript" type="text/javascript">
  
  function openLinkWindow(target,doc) {ldelim}
    if (typeof width=='undefined' || width=='') var width = screen.width * 0.6;
    if (typeof height=='undefined' || height=='') var height = screen.height * 0.6;
    if (typeof doc=='undefined') var doc = 'Titel';
    if (typeof scrollbar=='undefined') var scrollbar=1;
    window.open('index.php?doc='+doc+'&target='+target+'&do=docs&action=showsimple&cp={$sess}&pop=1','pop','left=0,top=0,width='+width+',height='+height+',scrollbars='+scrollbar+',resizable=1');
  {rdelim}


  function openFileWindow(target,id) {ldelim}
    if (typeof width=='undefined' || width=='') var width = screen.width * 0.6;
    if (typeof height=='undefined' || height=='') var height = screen.height * 0.6;
    if (typeof doc=='undefined') var doc = 'Titel';
    if (typeof scrollbar=='undefined') var scrollbar=1;
    window.open('browser.php?id='+id+'&typ=bild&mode=fck&target=navi&cp={$sess}','pop','left=0,top=0,width='+width+',height='+height+',scrollbars='+scrollbar+',resizable=1');
  {rdelim}

</script>
<h4>{#NAVI_ITEMS_TIP#}</h4>
<form name="navquicksave" method="post" action="index.php?do=navigation&amp;action=quicksave&amp;id={$smarty.request.id}&amp;cp={$sess}">
  <table width="100%" cellpadding="8" cellspacing="1" class="tableborder">
    <tr class="tableheader">
      <td>{#NAVI_LINK_TITLE#}</td>
      <td>{#NAVI_LINK_TO_DOCUMENT#}</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>{#NAVI_POSITION#}</td>
      <td>{#NAVI_TARGET_WINDOW#}</td>
    </tr>
    
    <tr>
      <td class="second"><input style="width:100%" name="Titel_N[]" type="text" id="Titel_N" value="" /></td>
      <td class="second"><input style="width:100%" name="Link_N[]" type="text" id="Link_N" value="" /></td>
      <td width="8" class="second" nowrap="nowrap"><input {popup sticky=false text=$config_vars.NAVI_BROWSE_DOCUMENTS} onclick="openLinkWindow('Link_N','Titel_N');" type="button" class="button" value="... " /></td>
      <td width="8" class="second" nowrap="nowrap"><input {popup sticky=false text=$config_vars.NAVI_BROWSE_MEDIAPOOL} value="#" class="button" onclick="openFileWindow('Link_N','Link_N');" type="button"></td>
      <td width="8" class="second"><input name="Rang_N[]" type="text" id="Rang_N" value="1" size="4" maxlength="3" /></td>
      <td class="second" width="300">
        <select name="Ziel_N[]" id="Ziel_N">
        <option value="_self" {if $item_3->Ziel=='_self'}selected{/if}>{#NAVI_OPEN_IN_THIS#}</option>
        <option value="_blank" {if $item_3->Ziel=='_blank'}selected{/if}>{#NAVI_OPEN_IN_NEW#}</option>
        </select>
        <input type="submit" class="button" value="{#NAVI_BUTTON_ADD#}" />
      </td>
    </tr>
  </table>

<br />
<div id="pageHeaderTitle" style="padding-top: 7px;">
    <div class="HeaderTitle"><h4 class="navi">{#NAVI_LIST#}</h4>
    <div class="Text">{#NAVI_LIST_TIP#}</div>
</div>

  <table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
    <tr class="tableheader">
      <td width="50%">{#NAVI_LINK_TITLE#}</td>
      <td width="50%">{#NAVI_LINK_TO_DOCUMENT#}</td>
      <td width="8">&nbsp;</td>
      <td width="8">&nbsp;</td>
      <td width="8">{#NAVI_POSITION#}</td>
      <td width="80">{#NAVI_TARGET_WINDOW#}</td>
      <td>&nbsp;</td>
      <td width="1%" align="center"><img src="{$tpl_dir}/images/icon_unlock.gif" alt="" border="0"  /></td>
      <td width="1%" align="center"><img src="{$tpl_dir}/images/icon_delete.gif" alt="" border="0"  /></td>
    </tr>

    {foreach from=$entries item=item}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
      <td width="50%"><input style="width: 100%" name="Titel[{$item->Id}]" type="text" id="Titel_{$item->Id}" value="{$item->Titel|stripslashes}" /></td>
      <td width="50%"><input style="width: 100%" name="Link[{$item->Id}]" type="text" id="Link_{$item->Id}" value="{$item->Link|escape:html|stripslashes|cleanReplacement}" /></td>
      <td width="1%"><input {popup sticky=false text=$config_vars.NAVI_BROWSE_DOCUMENTS} onclick="openLinkWindow('Link_{$item->Id}','Titel_{$item->Id}');" type="button" class="button" value="... " /></td>
      <td width="1%"><input {popup sticky=false text=$config_vars.NAVI_BROWSE_MEDIAPOOL} value="#" class="button" onclick="openFileWindow('Link_{$item->Id}','Link_{$item->Id}');" type="button"></td>
      <td width="1%"><input name="Rang[{$item->Id}]" type="text" id="Rang_{$item->Id}" value="{$item->Rang}" size="4" maxlength="3" /></td>
      <td width="1%">
        <select name="Ziel[{$item->Id}]" id="Ziel_{$item->Id}">
        <option value="_self" {if $item->Ziel=='_self'}selected{/if}>{#NAVI_OPEN_IN_THIS#}</option>
        <option value="_blank" {if $item->Ziel=='_blank'}selected{/if}>{#NAVI_OPEN_IN_NEW#}</option>
        </select>
      </td>
      <td width="1%"><input {popup sticky=false text=$config_vars.NAVI_ADD_SUBITEM} type="button" class="button" onclick="document.getElementById('Neu_2_{$item->Id}').style.display='';" value="{#NAVI_BUTTON_SUBITEM#}" /></td>
      <td width="1%"><input {popup sticky=false text=$config_vars.NAVI_MARK_ACTIVE|default:''} name="Aktiv[{$item->Id}]" type="checkbox"  value="1" {if $item->Aktiv==1}checked{/if} /></td>
      <td width="1%"><input {popup sticky=false text=$config_vars.NAVI_MARK_DELETE|default:''} name="del[{$item->Id}]" type="checkbox" id="del[{$item->Id}]" value="1" /></td>
    </tr>
    
    <tr id="Neu_2_{$item->Id}" style="display:none;">
      <td class="level2_new" width="50%" style="background-color: #acd18f;"><input style="width:100%" name="Titel_Neu_2[{$item->Id}]" type="text" id="Titel_Neu_2_{$item->Id}" value="" /></td>
      <td width="50%" style="background-color: #acd18f;"><input style="width:100%" name="Link_Neu_2[{$item->Id}]" type="text" id="Link_Neu_2_{$item->Id}" value="" /></td>
      <td width="8" style="background-color: #acd18f;"><input {popup sticky=false text=$config_vars.NAVI_BROWSE_DOCUMENTS} onclick="openLinkWindow('Link_Neu_2_{$item->Id}','Titel_Neu_2_{$item->Id}');" type="button" class="button" value="... " /></td>
      <td width="8" style="background-color: #acd18f;"><input {popup sticky=false text=$config_vars.NAVI_BROWSE_MEDIAPOOL} value="#" class="button" onclick="openFileWindow('Link_Neu_2_{$item->Id}','Link_Neu_2_{$item->Id}');" type="button"></td>
      <td width="8" style="background-color: #acd18f;"><input name="Rang_Neu_2[{$item->Id}]" type="text" id="Rang_Neu_2_{$item->Id}" value="1" size="4" maxlength="3" /></td>
      <td style="background-color: #acd18f;">
        <select name="Ziel_Neu_2[{$item->Id}]" id="Ziel_Neu_2_{$item->Id}">
        <option value="_self" {if $item_3->Ziel=='_self'}selected{/if}>{#NAVI_OPEN_IN_THIS#}</option>
        <option value="_blank" {if $item_3->Ziel=='_blank'}selected{/if}>{#NAVI_OPEN_IN_NEW#}</option>
        </select>
      </td>
      <td style="background-color: #acd18f;">&nbsp;</td>
      <td width="1%" style="background-color: #acd18f;">&nbsp;</td>
      <td width="1%" style="background-color: #acd18f;">&nbsp;</td>
    </tr>


    {foreach from=$item->ebene_2 item=item_2}
  <tr id="table_rows" style="background-color: #dae0d8;">
       <td class="level2" width="50%" style="background-color: #dae0d8;"><input style="width: 100%" name="Titel[{$item_2->Id}]" type="text" id="Titel_{$item_2->Id}" value="{$item_2->Titel|stripslashes}" /></td>
       <td width="50%" style="background-color: #dae0d8;"><input style="width: 100%" name="Link[{$item_2->Id}]" type="text" id="Link_{$item_2->Id}" value="{$item_2->Link|escape:html|stripslashes|cleanReplacement}" /></td>
       <td width="8" style="background-color: #dae0d8;"><input {popup sticky=false text=$config_vars.NAVI_BROWSE_DOCUMENTS} onclick="openLinkWindow('Link_{$item_2->Id}','Titel_{$item_2->Id}');" type="button" class="button" value="... " /></td>
       <td width="8" style="background-color: #dae0d8;"><input {popup sticky=false text=$config_vars.NAVI_BROWSE_MEDIAPOOL} value="#" class="button" onclick="openFileWindow('Link_{$item_2->Id}','Link_{$item_2->Id}');" type="button"></td>
       <td width="8" style="background-color: #dae0d8;"><input name="Rang[{$item_2->Id}]" type="text" id="Rang_{$item_2->Id}" value="{$item_2->Rang}" size="4" maxlength="3" /></td>
       <td style="background-color: #dae0d8;">
         <select name="Ziel[{$item_2->Id}]" id="Ziel_{$item_2->Id}">
         <option value="_self" {if $item_2->Ziel=='_self'}selected{/if}>{#NAVI_OPEN_IN_THIS#}</option>
         <option value="_blank" {if $item_2->Ziel=='_blank'}selected{/if}>{#NAVI_OPEN_IN_NEW#}</option>
         </select>
       </td>
       <td style="background-color: #dae0d8;"><input {popup sticky=false text=$config_vars.NAVI_ADD_SUBITEM} type="button" class="button_lev2" onclick="document.getElementById('Neu_3_{$item_2->Id}').style.display='';" value="{#NAVI_BUTTON_SUBITEM#}" /></td>
       <td width="1%" style="background-color: #dae0d8;"><input {popup sticky=false text=$config_vars.NAVI_MARK_ACTIVE|default:''}  name="Aktiv[{$item_2->Id}]" type="checkbox" value="1" {if $item_2->Aktiv==1}checked{/if} /></td>
       <td width="1%" style="background-color: #dae0d8;"><input {popup sticky=false text=$config_vars.NAVI_MARK_DELETE|default:''} name="del[{$item_2->Id}]" type="checkbox" id="del[{$item_2->Id}]" value="1" /></td>
    </tr>
  
    {foreach from=$item_2->ebene_3 item=item_3}
  <tr id="table_rows">
      <td class="level3" style="background-color: #cdd5ca;"><input style="width: 100%" name="Titel[{$item_3->Id}]" type="text" id="Titel_{$item_3->Id}" value="{$item_3->Titel|stripslashes}" /></td>
      <td style="background-color: #cdd5ca;"><input style="width: 100%" name="Link[{$item_3->Id}]" type="text" id="Link_{$item_3->Id}" value="{$item_3->Link|escape:html|stripslashes|cleanReplacement}" /></td>
      <td width="8" style="background-color: #cdd5ca;"><input {popup sticky=false text=$config_vars.NAVI_BROWSE_DOCUMENTS} onclick="openLinkWindow('Link_{$item_3->Id}','Titel_{$item_3->Id}');" type="button" class="button" value="... " /></td>
      <td width="8" style="background-color: #cdd5ca;"><input {popup sticky=false text=$config_vars.NAVI_BROWSE_MEDIAPOOL} value="#" class="button" onclick="openFileWindow('Link_{$item_3->Id}','Link_{$item_3->Id}');" type="button"></td>
      <td width="8" style="background-color: #cdd5ca;"><input name="Rang[{$item_3->Id}]" type="text" id="Rang_{$item_3->Id}" value="{$item_3->Rang}" size="4" maxlength="3" /></td>
      <td style="background-color: #cdd5ca;">
        <select name="Ziel[{$item_3->Id}]" id="Ziel_{$item_3->Id}">
        <option value="_self" {if $item_3->Ziel=='_self'}selected{/if}>{#NAVI_OPEN_IN_THIS#}</option>
        <option value="_blank" {if $item_3->Ziel=='_blank'}selected{/if}>{#NAVI_OPEN_IN_NEW#}</option>
        </select>
      </td>
      <td style="background-color: #cdd5ca;">&nbsp;</td>
      <td width="1%" style="background-color: #cdd5ca;"><input {popup sticky=false text=$config_vars.NAVI_MARK_ACTIVE|default:''}  name="Aktiv[{$item_3->Id}]" type="checkbox" id="del[{$item_3->Id}]" value="1" {if $item_3->Aktiv==1}checked{/if} /></td>
      <td width="1%" style="background-color: #cdd5ca;"><input {popup sticky=false text=$config_vars.NAVI_MARK_DELETE|default:''} name="del[{$item_3->Id}]" type="checkbox"  value="1" /></td>
    </tr>
    {/foreach}
    
    
    <tr id="Neu_3_{$item_2->Id}" style="display:none;">
      <td class="level3_new" style="background-color: #acd18f;"><input style="width: 100%" name="Titel_Neu_3[{$item_2->Id}]" type="text" id="Titel_Neu_3_{$item_2->Id}" value="" /></td>
      <td style="background-color: #acd18f;"><input style="width: 100%" name="Link_Neu_3[{$item_2->Id}]" type="text" id="Link_Neu_3_{$item_2->Id}" value="" /></td>
      <td width="8" style="background-color: #acd18f;"><input {popup sticky=false text=$config_vars.NAVI_BROWSE_DOCUMENTS} onclick="openLinkWindow('Link_Neu_3_{$item_2->Id}','Titel_Neu_3_{$item_2->Id}');" type="button" class="button" value="... " /></td>
      <td width="8" style="background-color: #acd18f;"><input {popup sticky=false text=$config_vars.NAVI_BROWSE_MEDIAPOOL} value="#" class="button" onclick="openFileWindow('Link_Neu_3_{$item_2->Id}','Link_Neu_3_{$item_2->Id}');" type="button"></td>
      <td width="8" style="background-color: #acd18f;"><input name="Rang_Neu_3[{$item_2->Id}]" type="text" id="Rang_Neu_3_{$item_2->Id}" value="1" size="4" maxlength="3" /></td>
      <td style="background-color: #acd18f;">
        <select name="Ziel_Neu_3[{$item_2->Id}]" id="Ziel_Neu_3_{$item_2->Id}">
        <option value="_self" {if $item_3->Ziel=='_self'}selected{/if}>{#NAVI_OPEN_IN_THIS#}</option>
        <option value="_blank" {if $item_3->Ziel=='_blank'}selected{/if}>{#NAVI_OPEN_IN_NEW#}</option>
        </select>
      </td>
      <td style="background-color: #acd18f;">&nbsp;</td>
      <td width="1%" style="background-color: #acd18f;">&nbsp;</td>
      <td width="1%" style="background-color: #acd18f;">&nbsp;</td>
    </tr>
    {/foreach}
    {/foreach}
  </table>

  <input name="Rubrik" type="hidden" id="Rubrik" value="{$smarty.request.id}" /><br />
  <input type="submit" class="button" value="{#NAVI_BUTTON_SAVE#}" />
</form>
