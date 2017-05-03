<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>{#CommentTitle#}</title>
<link href="/templates/{$smarty.request.cp_theme}/css/style.css" rel="stylesheet" type="text/css" media="screen" />
<script src="/templates/{$smarty.request.cp_theme}/js/common.js" type="text/javascript"></script>
<script src="/templates/{$smarty.request.cp_theme}/overlib/overlib.js" type="text/javascript"></script>
</head>
{strip}
<body style="margin:0px">
	<div style="padding:30px">
		<div style="padding:10px">

			<div id="module_header" style="text-align:center">
				<h1>{$galname|stripslashes}</h1>
			</div>

			<div style="text-align:left">{$Beschreibung|stripslashes}</div>

			<div id="module_content" style="background-color:#fff">
				{include file="$path/galerie.tpl"}<br />
				<br />

				<div align="center">
					<input type="button" class="button" onclick="window.close();" title="Закрыть" alt="Закрыть" value="Закрыть"/><br />
					<br />
				</div>
			</div>

		</div>
	</div>
</body>
{/strip}
</html>