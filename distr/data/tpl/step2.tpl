<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>{$pref.k_version_setup}</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<script language="javascript" src="data/tpl/agree.js" type="text/javascript"></script>
<link href="data/tpl/style.css" rel="stylesheet" type="text/css" />

</head>

<body>
<!-- Start page --><div id="container">

<!-- Header --><div id="header">

</div><!-- /header -->

<!-- Content -->
<div id="content">


<div id="pageLogo"><img src="data/tpl/ave_logo.jpg" width="186" height="76" alt="Logotype" /></div>
<div id="pageHeaderTitle">{$pref.k_version_setup}</div>
<div class="clearer">&nbsp;</div>

<div class="Item">
        <div class="SideTitle">
          <div class="SideTitleLHS">{$la.install_step} <span class="TitleStep">3</span></div>
          <div class="SideTitleRHS">{$la.liztexttitle}</div>
          <div class="clearer">&nbsp;</div>
        </div>
</div>

<div class="helpTitle">{$la.liztext}</div>



<table cellspacing="10" cellpadding="0" border="0">
<tr>
	<td>
<div class="databody">
{include file="../eula/ru.tpl"}
</div>
	</td>
</tr>
</table>

<form action="install.php" method="post" enctype="multipart/form-data" name="s" id="s" onSubmit="return defaultagree(this)">


<table cellspacing="5" cellpadding="0" border="0" class="TdNotice">
<tr>
	<td><input name="agreecheck" type="checkbox" onClick="agreesubmit(this)"></td>
	<td>{$la.lizagree}</td>
</tr>
</table>

<div align="right">
              <input name="demo" type="hidden" id="demo" value="{$smarty.get.demo}">
              <input name="step" type="hidden" id="step" value="1">
              <input accesskey="e" name="Submit" type="submit" class="button" value="{$la.eulaok}" disabled />
              <input onclick="if(confirm('{$la.confirm_exit}')) location.href='data/exit.html'" type="button" class="button" value="{$la.exit}" />
</div>
</form>
<script>
document.forms.s.agreecheck.checked=false
</script>

<!-- DO NOT TRY THIS -->
<div id="clearfooter">&nbsp;</div>
</div><!-- /content -->
</div><!-- /container -->
<!-- /DO NOT TRY THIS -->


<!-- Footer --><div id="footer">
</div><!-- /footer -->

<!-- /End page -->
</body>
</html>
