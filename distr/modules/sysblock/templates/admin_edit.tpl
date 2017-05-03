<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#SYSBLOCK_INSERT_H#}</h2></div>
    <div class="HeaderText">{#SYSBLOCK_INSERT#}</div>
</div>

<br />

<div class="infobox">
» <a href="index.php?do=modules&action=modedit&mod=sysblock&moduleaction=1&cp={$sess}">{#SYSBLOCK_LIST_LINK#}</a>
</div>

<br />

{if $smarty.request.id != ''}
<form action="index.php?do=modules&action=modedit&mod=sysblock&moduleaction=saveedit&cp={$sess}" method="post">
  <table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
    <tr class="tableheader">
      <td>{#SYSBLOCK_NAME#}</td>
    </tr>
    <tr>
      <td class="second">
<input name="sysblock_name" type="text" value="{$sysblock_name|escape:html|stripslashes}" size="40" />
      </td>
    </tr>
    <tr class="tableheader">
      <td>{#SYSBLOCK_INTEXT#}</td>
    </tr>
    <tr>
      <td class="second">
<div>
<input type="hidden" id="sysblock_text" name="sysblock_text" value="{$sysblock_text|escape:html|stripslashes}" style="display:none" />
<input type="hidden" id="sysblock_text___Config" value="" style="display:none" />
<div id="sysblock_text_data"><iframe id="sysblock_text___Frame" src="editor/editor/fckeditor.html?InstanceName=sysblock_text&amp;Toolbar=cpengine" width="100%" height="400px" frameborder="0" scrolling="no"></iframe></div>
</div>
      </td>
    </tr>
    <tr>
      <td class="first">
        <input type="hidden" name="id" value="{$id}">
        <input name="submit" type="submit" class="button" value="{#SYSBLOCK_SAVEDIT#}" />
      </td>
    </tr>
  </table>
</form>

{else}

<form action="index.php?do=modules&action=modedit&mod=sysblock&moduleaction=saveedit&cp={$sess}" method="post">
  <table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
    <tr class="tableheader">
      <td>{#SYSBLOCK_NAME#}</td>
    </tr>
    <tr>
      <td class="second">
<input name="sysblock_name" type="text" value="" size="40" />
      </td>
    </tr>
    <tr class="tableheader">
      <td>{#SYSBLOCK_INTEXT#}</td>
    </tr>
    <tr>
      <td class="second">
<div>
<input type="hidden" id="sysblock_text" name="sysblock_text" value="{$sysblock_text}" style="display:none" />
<input type="hidden" id="sysblock_text___Config" value="" style="display:none" />
<div id="sysblock_text_data"><iframe id="sysblock_text___Frame" src="editor/editor/fckeditor.html?InstanceName=sysblock_text&amp;Toolbar=cpengine" width="100%" height="400px" frameborder="0" scrolling="no"></iframe></div>
</div>
      </td>
    </tr>
    <tr>
      <td class="first">
        <input type="hidden" name="id" value="">
        <input name="submit" type="submit" class="button" value="{#SYSBLOCK_SAVE#}" />
      </td>
    </tr>
  </table>
</form>
{/if}