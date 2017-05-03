<div id="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_group"></div>
    <div class="HeaderTitle"><h2>{#UGROUP_TITLE2#}<span style="color: #000;"> &gt; {$g_name|escape:html}</span></h2></div>

{if $own_group==1}
    <br><div class="HeaderTextError">{#UGROUP_YOUR_NOT_CHANGE#}</div><br><br>
{else}
	<div class="HeaderText">{#UGROUP_WARNING_TIP#}</div><br><br>
{if $no_group==1}
	<div class="HeaderTextError">{#UGROUP_NOT_EXIST#}</div><br><br>
{else}

</div>
<div class="upPage"></div>


<form method="post" action="index.php?do=groups&amp;action=grouprights&amp;cp={$sess}&amp;Id={$smarty.request.Id}&amp;sub=save">
  {assign var=cols value=10}
  <table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
    <tr>
      <td class="tableheader">{#UGROUP_NAME#}</td>
    </tr>
    
    <tr>
      <td class="first"><input name="Name" type="text" value="{$g_name|escape:html}" size="40" maxlength="40"></td>
    </tr>
  </table>

<br />
  <table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
    <tr>
      <td  class="tableheader">{#UGROUP_MODULES_RIGHT#}</td>
    </tr>
  
    <tr class="{cycle name='fs' values='first,second'}">
    <td valign="top">
      <select name="perms[]" style="width:300px" size="10" multiple="multiple" id="xxx">
      {foreach from=$modules item=mo}
      {if $mo->ModulPfad != 'mod_navigation'}
        <option value="{$mo->ModulPfad}" {if in_array($mo->ModulPfad, $g_group_permissions) || in_array('alles', $g_group_permissions)}selected{/if}   {if $smarty.request.Id==1 || ($smarty.request.Id==2 && $all=='alles') || $smarty.request.Id==2 }disabled{/if}>{$mo->ModulName|strip_tags}</option>
      {/if}
      {/foreach}
      </select>
    </td>
   </tr>
  </table>

<br />
  <table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
    <tr>
      <td colspan="2" class="tableheader">{#UGROUP_CONTROL_RIGHT#}</td>
    </tr>
    
    {foreach from=$g_all_permissions item=all}
    {assign var=num value=$num+1}
    <tr class="{cycle name='fs' values='first,second'}">
      <td width="1%">
        <input type="checkbox" name="perms[]" value="{$all}" {if in_array($all, $g_group_permissions) || in_array('alles', $g_group_permissions)}checked{/if}   {if $smarty.request.Id==1 || ($smarty.request.Id==2 && $all=='alles') || $smarty.request.Id==2 }disabled{/if}>
        {$x_all}
      </td>
      <td>{$config_vars.$all}</td>
    </tr>
  
    {if $num % $cols == 0}{/if}
    </tr>
  {/foreach}
</table>

<br />
<input type="submit" class="button" value="{#UGROUP_BUTTON_SAVE#}" />
</form>
{/if}
{/if}