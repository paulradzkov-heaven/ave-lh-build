<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#VIDEO_EDIT#}</h2></div>
    <div class="HeaderText">{#VIDEO_INSERT#}</div>
</div>

<br />

<div class="infobox">
» <a href="index.php?do=modules&action=modedit&mod=videoplayer&moduleaction=new&cp={$sess}">{#VIDEO_ADD#}</a>
&nbsp;&nbsp;&nbsp;
» <a href="index.php?do=modules&action=modedit&mod=videoplayer&moduleaction=1&cp={$sess}">{#VIDEO_LIST#}</a>
</div>

<br />

<form method="post" action="index.php?do=modules&action=modedit&mod=videoplayer&moduleaction=save&sub=savedit&cp={$sess}">
<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
    <tr class="tableheader">
    <td colspan="2">{#VIDEO_MODULE_EDIT#}</td>
  </tr>
  <tr>
    <td width="200" class="first">{#VIDEO_EDIT_NAME#}</td>
    <td class="second">
      <input type="text" name="VideoTitle" value="{$VideoTitle}" id="VideoTitle" style="width:300px;" />
    </td>
  </tr>
  <tr>
    <td width="200" class="first" valign="top">{#VIDEO_EDIT_FILE#}</td>
    <td class="second" >


<div style="" id="feld_FileName">
<div style="display: none;" id="feld_FileName">
<img style="display: none;" id="_img_feld__FileName" src="" alt="" border="0">
</div>
<div style="" id="span_feld__FileName"></div>
<input style="width: 300px;" name="FileName" value="{$FileName}" id="img_feld__FileName" type="text"> <input value="{#VIDEO_SERVER#}" class="button" onclick="cp_imagepop('img_feld__FileName','','','0');" type="button">
</div>

    </td>
  </tr>
  <tr>
    <td width="200" class="first" valign="top">{#VIDEO_EDIT_PIC#}</td>
    <td class="second">

<div id="feld_ImagePreview">
<div style="display: none;" id="feld_ImagePreview">
<img style="display: none;" id="_img_feld__ImagePreview" src="" alt="" border="0">
</div>
<div id="span_feld__ImagePreview"></div>
<input style="width: 300px;" name="ImagePreview" value="{$ImagePreview}" id="img_feld__ImagePreview" type="text"> <input value="{#VIDEO_SERVER#}" class="button" onclick="cp_imagepop('img_feld__ImagePreview','','','0');" type="button">
</div>
    </td>
  </tr>
  <tr>
    <td width="200" class="first" valign="top">{#VIDEO_EDIT_Duration#}</td>
    <td class="second">
      <input type="text" name="Duration" value="{$Duration}" id="Duration" style="width:50px;" /> {#VIDEO_EDIT_SEC#}
    </td>
  </tr>
  <tr>
    <td width="200" class="first" valign="top">{#VIDEO_EDIT_BufferLength#}</td>
    <td class="second">
      <input type="text" name="BufferLength" value="{$BufferLength}" id="BufferLength" style="width:50px;" /> {#VIDEO_EDIT_SEC#}
    </td>
  </tr>
  <tr>
    <td width="200" class="first" valign="top">{#VIDEO_EDIT_Width#}</td>
    <td class="second">
      <input type="text" name="Width" value="{$Width}" id="Width" style="width:50px;" />&nbsp;õ&nbsp;<input type="text" name="Height" value="{$Height}" id="Height" style="width:50px;" /> {#VIDEO_EDIT_PIX#}
    </td>
  </tr>
  <tr>
    <td width="200" class="first">{#VIDEO_USE_AllowFullScreen#}</td>
    <td class="second">
      <select name="AllowFullScreen" id="AllowFullScreen">
        <option value="true" {if $AllowFullScreen=='true'}selected{/if}>{#VIDEO_YES#}</option>
        <option value="false" {if $AllowFullScreen=='false'}selected{/if}>{#VIDEO_NO#}</option>
      </select>
    </td>
  </tr>
  <tr>
    <td width="200" class="first">{#VIDEO_USE_AllowScriptAccess#}</td>
    <td class="second">
      <select name="AllowScriptAccess" id="AllowScriptAccess">
        <option value="true" {if $AllowScriptAccess=='true'}selected{/if}>{#VIDEO_YES#}</option>
        <option value="false" {if $AllowScriptAccess=='false'}selected{/if}>{#VIDEO_NO#}</option>
      </select>
    </td>
  </tr>
<input type="hidden" value="savedit" name="sub" />
<input type="hidden" value="{$Id}" name="Id" />
  <tr>
    <td class="third" colspan="2"><input type="submit" class="button" value="{#VIDEO_BUTTON_SAVE#}" /></td>
  </tr>
</table>
<br />
</form>


