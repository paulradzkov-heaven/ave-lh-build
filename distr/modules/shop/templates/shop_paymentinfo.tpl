<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>{#CommentTitle#}</title>
<link href="templates/{$cp_theme}/css/style.css" rel="stylesheet" type="text/css" media="all" />
<script src="templates/{$cp_theme}/js/common.js" type="text/javascript"></script>
</head>
<body id="body_popup">
<h2>{$row->Name|stripslashes}</h2>
<br />
{$row->Beschreibung|stripslashes} 
<br /><br />
<div align="center">
  <input class="button" type="button" onclick="window.close();" value="{#WindowClose#}" />
</div>
</body>
</html>
