<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#ModName#}</h2></div>
    <div class="HeaderText">{#SmileyInf#}</div>
</div><br />
{include file="$source/forum_topnav.tpl"}
<h4>{#Smilieys#}</h4>
<form action="index.php?do=modules&action=modedit&mod=forums&moduleaction=list_smilies&cp={$sess}&save=1" method="post" enctype="multipart/form-data" name="kform">
  <table width="100%"  border="0" cellpadding="8" cellspacing="1" class="tableborder">
    <tr class="tableheader">
      <td width="150">{#SAktive#}</td>
      <td width="100" align="center">{#SCode#}</td>
      <td width="100" align="center">{#SPath#}</td>
      <td width="100" align="center">{#Posi#}</td>
      <td width="1%"><input name="allbox" type="checkbox" id="d" onclick="selall();" value="" /></td>
	  <td></td>
    </tr>
	{foreach from=$smileys item="sm"}
   <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
      <td width="150" nowrap>
        <input type="radio" name="active[{$sm->id}]" value="1" {if $sm->active==1}checked{/if}> {#Yes#}
      <input type="radio" name="active[{$sm->id}]" value="2" {if $sm->active==2}checked{/if}> {#No#}</td>
      <td width="100" align="center" nowrap><input name="code[{$sm->id}]" type="text" id="code[{$sm->id}]" value="{$sm->code}" size="10"></td>
      <td width="100" align="center" nowrap><input name="path[{$sm->id}]" type="text" id="path[{$sm->id}]" value="{$sm->path}" size="15"></td>
      <td width="100" align="center"><input name="posi[{$sm->id}]" type="text" id="posi[{$sm->id}]" value="{$sm->posi}" size="5"></td>
      <td><input name="del[{$sm->id}]" type="checkbox" id="d" value="1" /></td>
	  <td></td>
    </tr>
  {/foreach}
  <tr >
  <td colspan="6" class="second"><input type="submit" class="button" value="{#Save#}" /></td></tr>
  </table>
</form>
<h4>{#AddSmiley#}</h4>
<form action="index.php?do=modules&action=modedit&mod=forums&moduleaction=list_smilies&cp={$sess}&new=1" method="post" enctype="multipart/form-data" name="adds" id="adds">
  <table width="100%"  border="0" cellpadding="8" cellspacing="1" class="tableborder">
    <tr class="tableheader">
      <td>{#SCode#}</td>
      <td>{#SPath#}</td>
	  <td></td>
    </tr>
 {section name="new" loop=5}
  {assign var="count" value=$count+1}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
      <td width="10%" align="center"><input name="code[]" type="text" id="code[]"></td>
      <td width="1%"><input name="path[]" type="text" id="path[]"></td>
	  <td></td>
    </tr>
 {/section}
     <tr class="second">
	 <td colspan="3"><input type="submit" class="button" value="{#AddSmiley#}" /></td>
    </tr>
  </table>
</form>
