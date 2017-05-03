<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
  <title>{#COMMENT_INFO#}</title>
  <link href="/templates/{$cp_theme}/css/style.css" rel="stylesheet" type="text/css" media="screen" />
</head>

<body id="body_popup">
  <div id="module_header">
    <h2>{#COMMENT_INFO#}</h2>
  </div>

  <div id="module_content">
    <table width="100%" border="0" cellspacing="1" cellpadding="4">
      <tr>
        <td width="160">{#COMMENT_USER_NAME#}</td>
        <td>{$c.Author|stripslashes|escape:html}</td>
      </tr>
      <tr>
        <td width="160">{#COMMENT_DATE_CREATE#}</td>
        <td>{$c.Erstellt|date_format:"%d-%m-%Y г."} {#COMMENT_USER_TIME#} {$c.Erstellt|date_format:"%H:%M"}</td>
      </tr>
      <tr>
        <td width="160">{#COMMENT_USER_EMAIL#}</td>
        <td>{$c.AEmail|replace:'@':' (@) '|default:'-'}</td>
      </tr>
      <tr>
        <td width="160">{#COMMENT_USER_SITE#}</td>
        <td>{$c.AWebseite|default:'-'}</td>
      </tr>
      <tr>
        <td width="160">{#COMMENT_USER_FROM#}</td>
        <td>{$c.AOrt|stripslashes|escape:html|default:'-'}</td>
      </tr>
      <tr>
        <td width="160">{#COMMENT_USER_COMMENTS#}</td>
        <td>{$num|default:'-'}</td>
      </tr>
    </table>
    <p><input onclick="window.close();" type="button" class="button" value="{#COMMENT_CLOSE_BUTTON#}" /></p>
  </div>
</body>
</html>
