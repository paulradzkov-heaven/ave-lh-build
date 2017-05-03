{$pageheader}
<title>{$pname}</title>
<meta http-equiv="pragma" content="no-cache" />
<meta name="robots" content="noindex,nofollow" />
{if $smarty.request.t_path!=''}{assign var=t_path value=$smarty.request.t_path}{/if}
<link href="templates/{$t_path|default:$smarty.session.T_PATH}/css/print.css" rel="stylesheet" type="text/css" media="print" />
<link href="templates/{$t_path|default:$smarty.session.T_PATH}/css/style.css" rel="stylesheet" type="text/css" media="screen" />
<script src="templates/{$t_path|default:$smarty.session.T_PATH}/js/common.js" type="text/javascript"></script>
<script src="templates/{$t_path|default:$smarty.session.T_PATH}/overlib/overlib.js" type="text/javascript"></script>
</head>
<body id="guest_pop">
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="500" border="0" align="center" cellpadding="15" cellspacing="0" class="guest_tableborder">
  <tr>
    <td align="center" class="guest_info_meta" style="padding:25px">
	{$content}
	</td>
  </tr>
</table><meta http-equiv="refresh" content="5;URL={$GoTo}" />

</body>
</html>
