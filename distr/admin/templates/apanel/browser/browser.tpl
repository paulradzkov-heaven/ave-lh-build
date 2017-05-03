{strip}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
  <title>({$smarty.session.cp_uname})</title>
  <meta name="robots" content="noindex,nofollow">
  <meta http-equiv="pragma" content="no-cache">
  <meta name="generator" content="">
  <meta name="Expires" content="Mon, 06 Jan 1990 00:00:01 GMT">
  <link href="{$tpl_dir}/css/style.css" rel="stylesheet" type="text/css">
  <script src="{$tpl_dir}/js/cpcode.js" type="text/javascript"></script>
  <script src="{$tpl_dir}/overlib/overlib.js" type="text/javascript"></script>
  <script src="{$tpl_dir}/ajax/jquery.js" type="text/javascript"></script>
</head>

<body topmargin="0" leftmargin="0" id="mediapool">
  <script language="JavaScript" type="text/javascript">
    function selfile(src,prv) {ldelim}
      if (prv==1) {ldelim}
        parent.frames['vs'].location.href = '../uploads{$dir}' + src;
      {rdelim} else if (prv==3) {ldelim}
      {*  //parent.frames['vs'].location.href = '../uploads{$dir}' + src;
        //submitTheForm(); *}
      {rdelim} else {ldelim}
        parent.frames['vs'].location.href = 'about:blank';
        parent.frames['vs'].document.write('<img dynsrc="../uploads{$dir}' + src + '" border="0">');
      {rdelim}
      parent.document.dat.fn.value = src;
    {rdelim}

    function selcommon(src) {ldelim}
      parent.document.dat.fn.value = src;
    {rdelim}

    function delfile(src) {ldelim}
      if (window.confirm('{#MAIN_MP_DELETE_CONFIRM#} ' + src + ' ')) {ldelim}
        parent.frames['zf'].location.href = 'browser.php?df='+src+'&typ={$smarty.request.typ}&action=delfile&dir={$dir}&file={$dir}' + src + '&cpengine={$sess}';
      {rdelim}
    {rdelim}

    parent.document.dat.dateiname.value='{$dir}';
  </script>


   {if $dirup==1}

<div><div class="mb_imgcontainer">

<div class="mb_image" align="center"><a {popup sticky=false text=$lang_vars.MAIN_MP_UP_LEVEL} href="browser.php?typ={$smarty.request.typ}&cpengine={$sess}&dir={$dir}../&action=list"><img src="{$tpl_dir}/images/folder_up.gif" alt="" border="0" width="128" height="128" /></a></div>
<div class="mb_kb" align="center"><a {popup sticky=false text=$lang_vars.MAIN_MP_UP_LEVEL} href="browser.php?typ={$smarty.request.typ}&cpengine={$sess}&dir={$dir}../&action=list">..</a></div>

</div></div>
    {/if}

    {foreach from=$bfiles item=file}
<div><div class="mb_imgcontainer">

<div class="mb_image" align="center"><a href="browser.php?typ={$file->fileopen}"><img src="{$tpl_dir}/images/folder.gif" alt="" border="0" width="128" height="128" /></a></div>
<div class="mb_kb" align="center">{$file->val}</div>

</div></div>
    {/foreach}

    {foreach from=$dats item=dat}


<div><div class="mb_imgcontainer">

<div class="mb_icon_file"><img src="{$tpl_dir}/images/mediapool/{$dat->gifend}.gif" alt="" border="0" /></div>

{if $unable_delete != 1}
  {if cp_perm('mediapool_del')}<div class="mb_icon_delete"><a {popup sticky=false text=$lang_vars.MAIN_MP_FILE_DELETE} href="javascript:delfile('{$dat->val}');"><img src="{$tpl_dir}/images/icon_del.gif" alt="" border="0" /></a></div>{/if}
{/if}

<div class="mb_name">{$dat->val|truncate:20}</div>

<div class="mb_image" align="center"><a {popup sticky=false text=$lang_vars.MAIN_MP_FILE_INFO} href="javascript:;" onClick="

    {if $dat->gifend=='gif' || $dat->gifend=='png' || $dat->gifend=='jpg' || $dat->gifend=='jpeg'}
      {if $smarty.request.typ=='all'}
        javascript:selcommon('{$dat->val}');
      {elseif $smarty.request.typ=='vide'}
      javascript:selfile('{$dat->val}',3);
      {elseif $smarty.request.type=='imglink'}
      javascript:selfile('{$dat->val}',3);
      {else}
      javascript:selfile('{$dat->val}',3);
    {/if}

    {else}
      {if $smarty.request.typ=='all'}
      javascript:selcommon('{$dat->val}');
      {elseif $smarty.request.typ=='vide'}
      javascript:selfile('{$dat->val}',3);
      {elseif $smarty.request.type=='imglink'}
      javascript:selfile('{$dat->val}',3);
      {else}
      javascript:selfile('{$dat->val}',3);
      {/if}
  {/if}
  ">

  {if $dat->gifend=='gif' || $dat->gifend=='png' || $dat->gifend=='jpg' || $dat->gifend=='jpeg'}
    {$dat->bild}
  {else}
	<img src="{$tpl_dir}/images/file.gif" alt="" border="0" width="128" height="128" />
  {/if}

  </a></div>

<div class="mb_kb" align="center">{$dat->datsize}&nbsp;Кб</div>

<div class="mb_time">{$dat->moddate}</div>




</div></div>

  {/foreach}

</body>
</html>
{/strip}