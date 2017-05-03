{strip}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>{#MAIN_PAGE_TITLE#} - {#SUB_TITLE#} ({$smarty.session.cp_uname})</title>
  <meta name="robots" content="noindex,nofollow">
  <meta http-equiv="pragma" content="no-cache">
  <meta name="generator" content="">
  <meta name="Expires" content="Mon, 06 Jan 1990 00:00:01 GMT">
  <link href="{$tpl_dir}/css/style.css" rel="stylesheet" type="text/css">
  <script src="{$tpl_dir}/js/cpcode.js" type="text/javascript"></script>
  <script src="{$tpl_dir}/overlib/overlib.js" type="text/javascript"></script>
  <script src="{$tpl_dir}/ajax/jquery.js" type="text/javascript"></script>
  <script src="{$tpl_dir}/ajax/jquery.pngFix.js" type="text/javascript"></script>
</head>

<body>
<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td width="100%" valign="top">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td id="tableheader_main">
            <div id="noticeAreaLogin">
              <a href="index.php"><strong>{#MAIN_PAGE_TITLE#}</strong><br />{#MAIN_LINK_HOME#}</a>
            </div>

            <div id="noticeAreaProfileSection">
              <div>{#MAIN_USER_ONLINE#} <strong style="color:#fff;">{$smarty.session.cp_uname}</strong></div>
              <div><a onClick="return confirm('{#MAIN_LOGOUT_CONFIRM#}')" href="admin.php?do=logout">{#MAIN_LINK_LOGOUT#}</a></div>
            </div>

            <div id="noticeAreaReturnToSite">
              <a target="_blank" href="../index.php?module=login&action=wys_adm&sub=off"><strong>{#MAIN_LINK_SITE#}</strong></a><br />
              <a target="_blank" href="../index.php?module=login&action=wys_adm&sub=on">{#MAIN_LINK_SITE_GO#}</a>
            </div>

            <div class="noticeAreaHelp">
              <a id="noticeHelpIcon" href="#" title="Помощь по данному разделу.">&nbsp;<br />&nbsp;</a>
            </div>
          </td>
        </tr>
      </table>
		</td>
	</tr>

	<tr>
		<td height="100%" width="100%" valign="top">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        {if $smarty.request.do != ''}
          <tr>
            <td valign="top" height="30">
              <br /><br />
              <div id="mainSectionLinks" style="height: 30px;">
                <ul>
                  {$navi}
                </ul>
              </div>
            </td>
          </tr>
        {/if}

        <tr>
          <td valign="top" id="content" height="100%">{/strip}{$content}{strip}</td>
        </tr>
      </table>
		</td>
	</tr>

	<tr>
		<td width="100%" valign="bottom">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td id="tablebottom">{$cfooter}</td>
        </tr>
      </table>
		</td>
	</tr>
</table>
</body>
</html>
{/strip}