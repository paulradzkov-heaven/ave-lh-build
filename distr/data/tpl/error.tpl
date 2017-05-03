<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>{$pref.k_version_setup}</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />

<link href="style.css" rel="stylesheet" type="text/css" />

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
          <div class="SideTitleLHS">{$error_header}</div>
          <div class="SideTitleRHS"></div>
          <div class="clearer">&nbsp;</div>
        </div>
</div>
				{if $step == "no"}
				{$la.config_isnt_writeable}
				{/if}
				{foreach from=$error_is_required item="inc"}
				<div class="helpError">&bull; {$inc}</div>
				{/foreach}
          <br />
          <br />
          {$la.helpeconn}
          <br />
          <br />
<form action="" method="post" enctype="multipart/form-data" name="s" id="s">


<div class="helpTitle">{$pref.k_version}</div>

<table cellspacing="10" cellpadding="0" border="0">
<tr>
	<td colspan="2">
			  <div align="right">
            
              {if $step == "no"}
			  <input name="step" type="hidden" id="step" value="">
			  {else}
			  <input name="step" type="hidden" id="step" value="check">
			  {/if}
              {*
			  <input accesskey="e" name="Submit" type="submit" class="button" value="{$la.secondchance}" />
			  *}
			  Исправьте возникшую ошибку и обновите страницу (клавиша F5 или кнопка Обновить в браузере)
              <input onclick="if(confirm('{$la.confirm_exit}')) location.href='data/exit.html'" type="button" class="button" value="{$la.exit}" />
           <br />
          </div>
	</td>
</tr>
<tr>
	<td>
<input onclick="alert('{$la.warning_force}');" name="force" type="checkbox" id="force" value="1">
	</td>
	<td>
{$la.force}
	</td>
</tr>
</table>

</form>

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