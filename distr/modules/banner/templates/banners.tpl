{strip}
<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#BANNER_MODULE_NAME#}</h2></div>
    <div class="HeaderText">{#BANNER_MODULE_WELCOME#}</div>
</div><br />
<div class="infobox">
<strong>{#BANNER_SHOW_ALL#}</strong> |
<a href="javascript:void(0);"onclick="window.location.href='index.php?do=modules&action=modedit&mod=banner&moduleaction=1&cp={$sess}';cp_pop('index.php?do=modules&action=modedit&mod=banner&moduleaction=newbanner&cp={$sess}&pop=1','860','700','1','modbannenews');">{#BANNER_NEW_LINK#}</a> |
<a href="index.php?do=modules&action=modedit&mod=banner&moduleaction=kategs&cp={$sess}">{#BANNER_CATEG_LINK#}</a></div>
<br>
<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
  <tr class="tableheader">
    <td width="10">&nbsp;</td>
    <td>{#BANNER_NAME_TABLE#} </td>
    <td>{#BANNER_FILE_TABLE#}</td>
    <td>{#BANNER_INCATEG_TABLE#}</td>
    <td>{#BANNER_SHOW_TABLE#}</td>
    <td>{#BANNER_HITS_TABLE#}</td>
    <td>{#BANNER_VIEWS_TABLE#}</td>
    <td colspan="2">{#BANNER_ACTIONS#}</td>
  </tr>
  {foreach from=$items item=item}

  {if ($item->Aktiv != 1) || ($item->Bannertags=='') || ($item->Klicks >= $item->MaxKlicks && $item->MaxKlicks != 0) || ($item->Views >= $item->MaxViews && $item->MaxViews != 0)}
    {assign var=active value=0}
  {else}
    {assign var=active value=1}
  {/if}

  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows">
    <td width="10">
      {if $active!=1}
        <img {popup sticky=false text=$config_vars.BANNER_NOT_ACTIVE|default:''} src="{$tpl_dir}/images/icon_lock.gif" alt="" />
      {else}
        <img {popup sticky=false text=$config_vars.BANNER_IS_ACTIVE|default:''} src="{$tpl_dir}/images/icon_unlock.gif" alt="" />
      {/if}
    </td>

    <td><a href="javascript:void(0);" {popup sticky=false text=$config_vars.BANNER_EDIT_LINK|default:''} onclick="cp_pop('index.php?do=modules&action=modedit&mod=banner&moduleaction=editbanner&cp={$sess}&id={$item->Id}&pop=1','860','700','1','modbanneredit');">{$item->Bannername}</a></td>
    <td><a href="../modules/banner/banner/{$item->Bannertags}" {popup sticky=false text=$config_vars.BANNER_VIEW_LINK|default:''} target="_blank">{$item->Bannertags}</a></td>
    <td>
      {foreach from=$kategs item=k}
        {if $k->Id==$item->KatId}{$k->KatName}{/if}
      {/foreach}</td>
    <td align="center">{if $item->ZStart<10}0{/if}{$item->ZStart}:00  - {if $item->ZEnde<10}0{/if}{$item->ZEnde}:00</td>
    <td align="center">{$item->Klicks} / {if $item->MaxKlicks==0}~{else}{$item->MaxKlicks}{/if}</td>
    <td align="center">{$item->Views} / {if $item->MaxViews==0}~{else}{$item->MaxViews}{/if}</td>
    <td width="1%" align="center">
       <a {popup sticky=false text=$config_vars.BANNER_EDIT_LINK|default:''} href="javascript:void(0);" onclick="cp_pop('index.php?do=modules&action=modedit&mod=banner&moduleaction=editbanner&cp={$sess}&id={$item->Id}&pop=1','860','700','1','modbanneredit');">
       <img src="{$tpl_dir}/images/icon_edit.gif" alt="" border="0" /></a>
    </td>
    <td width="1%" align="center">
      <a {popup sticky=false text=$config_vars.BANNER_DELETE_LINK|default:''} onclick="return confirm('{#BANNER_DELETE_CONFIRM#}')" href="index.php?do=modules&action=modedit&mod=banner&moduleaction=delbanner&cp={$sess}&id={$item->Id}">
      <img src="{$tpl_dir}/images/icon_del.gif" alt="" border="0" /></a>
    </td>
  </tr>
  {/foreach}
</table>

<br  />
{if $page_nav}<div class="infobox">{$page_nav}</div>{/if}

{/strip}