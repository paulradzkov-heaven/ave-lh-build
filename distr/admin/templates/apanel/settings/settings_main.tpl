{strip}
<div id="pageHeaderTitle" style="padding-top: 7px;">
  <div class="h_settings"></div>
  <div class="HeaderTitle"><h2>{#SETTINGS_MAIN_TITLE#}</h2></div>
  <div class="HeaderText">{if $smarty.request.saved==1}{#SETTINGS_SAVED#}{else}{#SETTINGS_SAVE_INFO#}{/if}</div>
</div>
<div class="upPage"></div>

<script language="javascript">
  function presel() {ldelim}
    document.getElementById('Mail_Port').disabled = false;
    document.getElementById('Mail_Host').disabled = false;
    document.getElementById('Mail_Username').disabled = false;
    document.getElementById('Mail_Passwort').disabled = false;
    document.getElementById('Mail_Sendmailpfad').disabled = false;

    if(document.getElementById('mail').selected == true) {ldelim}
      document.getElementById('Mail_Port').disabled = true;
      document.getElementById('Mail_Host').disabled = true;
      document.getElementById('Mail_Username').disabled = true;
      document.getElementById('Mail_Passwort').disabled = true;
      document.getElementById('Mail_Sendmailpfad').disabled = true;
    {rdelim}

    if(document.getElementById('sendmail').selected == true) {ldelim}
      document.getElementById('Mail_Port').disabled = true;
      document.getElementById('Mail_Host').disabled = true;
      document.getElementById('Mail_Username').disabled = true;
      document.getElementById('Mail_Passwort').disabled = true;
    {rdelim}

    if(document.getElementById('smtp').selected == true) {ldelim}
      document.getElementById('Mail_Sendmailpfad').disabled = true;
    {rdelim}
  {rdelim}


  function openLinkWindow(target,doc) {ldelim}
    if (typeof width=='undefined' || width=='') var width = screen.width * 0.6;
    if (typeof height=='undefined' || height=='') var height = screen.height * 0.6;
    if (typeof doc=='undefined') var doc = 'Titel';
    if (typeof scrollbar=='undefined') var scrollbar=1;
    window.open('index.php?idonly=1&doc='+doc+'&target='+target+'&do=docs&action=showsimple&cp={$sess}&pop=1','pop','left=0,top=0,width='+width+',height='+height+',scrollbars='+scrollbar+',resizable=1');
  {rdelim}
</script>

<h4>Дополнительно</h4>
<table cellspacing="1" cellpadding="8" border="0" width="100%">
  <tr>
    <td class="second" width="50%">
<div id="otherLinks">
<a href="index.php?do=settings&amp;sub=countries&amp;cp={$sess}">
<div class="taskTitle">{#MAIN_COUNTRY_EDIT#}</div>
</a>
</div></td>
    <td class="second" width="50%">
<div id="otherLinks">
<a href="index.php?do=settings&amp;sub=clrcache&amp;cp={$sess}">
<div class="taskTitle">{#SETTINGS_CLEAR_CACHE#}</div>
</a>
</div></td>
    </td>
  </tr>
</table>

<h4>Общие настройки системы</h4>
<body onLoad="presel();">
<form onSubmit="return confirm('{#SETTINGS_SAVE_CONFIRM#}')"  name="settings" method="post" action="index.php?do=settings&cp={$sess}&sub=save">
  <table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
    <tr class="tableheader">
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td width="200" class="first">{#SETTINGS_SITE_NAME#}</td>
      <td class="second"><input name="Seiten_Name" type="text" id="Seiten_Name" style="width:550px" value="{$row.Seiten_Name}" maxlength="200"></td>
    </tr>

    <tr>
      <td class="first">{#SETTINGS_SITE_COUNTRY#}</td>
      <td class="second">
        <select name="DefLand">
          {foreach from=$available_countries item=land}
            <option value="{$land->LandCode}" {if $row.DefLand==$land->LandCode}selected{/if}>{$land->LandName}</option>
          {/foreach}
        </select>
      </td>
    </tr>

    <tr>
      <td class="first">{#SETTINGS_DATE_FORMAT#}</td>
      <td class="second">
        <select name="Zeit_Format" style="width:250px">
          {foreach from=$dateFormat item=date}
            <option value="{$date.format}" {if $row.Zeit_Format==$date.format}selected{/if}>{$date.view}</option>
          {/foreach}
        </select>
      </td>
    </tr>

	<tr>
      <td width="200" class="first">{#SETTINGS_EMAIL_NAME#}</td>
      <td class="second"><input name="Mail_Name" type="text" id="Mail_Name" style="width:350px" value="{$row.Mail_Name}" size="100"></td>
    </tr>

    <tr>
      <td width="200" class="first">{#SETTINGS_EMAIL_SENDER#}</td>
      <td class="second">
        <input name="Mail_Absender" type="text" id="Mail_Absender" style="width:350px" value="{$row.Mail_Absender}" size="100">
        <input type="hidden" name="Mail_Content" id="Mail_Content" value="text/plain" />
      </td>
    </tr>

    <tr>
      <td width="200" class="first">{#SETTINGS_MAIL_TRANSPORT#}</td>
      <td class="second">
        <select style="width:100px"  name="Mail_Typ" id="Mail_Typ" onChange="presel();" >
          <option id="mail" value="mail" {if $row.Mail_Typ=='mail'}selected{/if}>{#SETTINGS_MAIL#}</option>
          <option id="smtp" value="smtp" {if $row.Mail_Typ=='smtp'}selected{/if}>{#SETTINGS_SMTP#}</option>
          <option id="sendmail" value="sendmail" {if $row.Mail_Typ=='sendmail'}selected{/if}>{#SETTINGS_SENDMAIL#}</option>
        </select>
      </td>
    </tr>

    <tr>
      <td width="200" class="first">{#SETTINGS_MAIL_PORT#}</td>
      <td class="second"><input name="Mail_Port" type="text" id="Mail_Port" value="{$row.Mail_Port}" size="2" maxlength="2"></td>
    </tr>

    <tr>
      <td width="200" class="first">{#SETTINGS_SMTP_SERVER#}</td>
      <td class="second"><input name="Mail_Host" type="text" id="Mail_Host" style="width:250px" value="{$row.Mail_Host}" size="100"></td>
    </tr>

    <tr>
      <td width="200" class="first">{#SETTINGS_SMTP_NAME#}</td>
      <td class="second"><input name="Mail_Username" type="text" id="Mail_Username" style="width:250px" value="{$row.Mail_Username}" size="100"></td>
    </tr>

    <tr>
      <td width="200" class="first">{#SETTINGS_SMTP_PASS#}</td>
      <td class="second"><input name="Mail_Passwort" type="text" id="Mail_Passwort" style="width:250px" value="{$row.Mail_Passwort}" size="100"></td>
    </tr>

    <tr>
      <td width="200" class="first">{#SETTINGS_MAIL_PATH#}</td>
      <td class="second"><input name="Mail_Sendmailpfad" type="text" id="Mail_Sendmailpfad" style="width:250px" value="{$row.Mail_Sendmailpfad}" size="100"></td>
    </tr>

    <tr>
      <td width="200" class="first">{#SETTINGS_SYMBOL_BREAK#}</td>
      <td class="second"><input name="Mail_WordWrap" type="text" id="Mail_WordWrap" value="{$row.Mail_WordWrap}" size="4" maxlength="3"> {#SETTINGS_SYMBOLS#}</td>
    </tr>

    <tr>
      <td width="200" class="first">{#SETTINGS_TEXT_EMAIL#}<br /><br /><small>{#SETTINGS_TEXT_INFO#}</small></td>
      <td class="second"><textarea name="Mail_Text_NeuReg" id="Mail_Text_NeuReg" style="width:550px; height:120px">{$row.Mail_Text_NeuReg|stripslashes}</textarea></td>
    </tr>

    <tr>
      <td width="200" class="first">{#SETTINGS_EMAIL_FOOTER#}</td>
      <td class="second"> <textarea name="Mail_Text_Fuss" id="Mail_Text_Fuss" style="width:550px; height:60px">{$row.Mail_Text_Fuss|stripslashes}</textarea></td>
    </tr>
{*
    <tr>
      <td width="200" class="first">{#SETTINGS_ERROR_PAGE#}</td>
      <td class="second">
        <input name="Fehlerseite" type="text" id="Fehlerseite" value="{$row.Fehlerseite}" size="4" maxlength="4" readonly="readonly">
        <input onClick="openLinkWindow('Fehlerseite','Fehlerseite');" type="button" class="button" value="... " /> {#SETTINGS_PAGE_DEFAULT#}
      </td>
    </tr>
*}
    <tr>
      <td width="200" class="first">{#SETTINGS_TEXT_PERM#}</td>
      <td class="second"><textarea name="FehlerLeserechte" id="FehlerLeserechte" style="width:550px; height:100px">{$row.FehlerLeserechte|stripslashes}</textarea></td>
    </tr>

    <tr>
      <td width="200" class="first">{#SETTINGS_PAGE_NEXT#}</td>
      <td class="second"><input name="SeiteWeiter" type="text" id="SeiteWeiter" style="width:550px" value="{$row.SeiteWeiter|stripslashes}" size="100"></td>
    </tr>

    <tr>
      <td width="200" class="first">{#SETTINGS_PAGE_PREV#}</td>
      <td class="second"><input name="SeiteZurueck" type="text" id="SeiteZurueck" style="width:550px" value="{$row.SeiteZurueck|stripslashes}" size="100"></td>
    </tr>

    <tr>
      <td width="200" class="first">{#SETTINGS_PAGE_BEFORE#}</td>
      <td class="second"><input name="NaviSeiten" type="text" id="NaviSeiten" style="width:550px" value="{$row.NaviSeiten}"></td>
    </tr>

  </table><br />

  <input type="submit" class="button" value="{#SETTINGS_BUTTON_SAVE#}" />
</form>
{/strip}