<?php
$base = substr(dirname(__FILE__),0,-17);
define("ANTISPAM",1);
include_once ($base . "/inc/db.config.php");
include_once ($base . "/inc/config.php");
include_once ($base . "/functions/func.pref.php");
include_once ($base . "/functions/func.session.php");
include_once ($base . "/inc/init.php");
include_once ($base . "/modules/download/funcs/func.rewrite.php");

function DL_Rewrite($print_out)
{
	if(defined("CP_REWRITE") && CP_REWRITE==1)
	{
		$print_out = DownloadRewrite($print_out);
	}
	return $print_out;
}

$query =  (isset($_REQUEST['content']) ) ?  eregi_replace('[^ A-Za-zÀ-ßà-ÿ¨¸0-9]', '', $_REQUEST['content']) : '';
$kid = (isset($kid) && !empty($kid) && $kid != '9999') ? ' AND b.Id = '.$kid.'' : '';
$sc = $query;
$allowed = UGROUP;
$query = "SELECT
		 a.Id,
		 a.Name,
		 b.Id as KatId
	FROM
		" . PREFIX . "_modul_download_files as a,
		" . PREFIX . "_modul_download_kat as b

	WHERE
		a.Aktiv=1 AND
		a.KatId=b.Id AND
		(
			b.Gruppen like '{$allowed}|%' OR
			b.Gruppen like '%|{$allowed}' OR
			b.Gruppen like '%|{$allowed}|%' OR
			b.Gruppen = '{$allowed}'
		)
	AND
		(Name LIKE '$sc%' OR Beschreibung LIKE '$sc%')
	$kid
	";

if(!empty($sc) && strlen($sc) >= 1)
{
	$sql = $GLOBALS['db']->Query($query);
	$num = $sql->numrows();
	$div = '';

	if($num>0)
	{
		echo "showDiv||";
		$div .= '<div id="cp_ajs" class="mod_download_ajaxsearchdiv">';
		while($row=$sql->fetchrow())
		{
			$row->Link = DL_Rewrite("index.php?module=download&amp;action=showfile&amp;file_id={$row->Id}&amp;categ=$row->KatId");
			$div .= "<a class=\"mod_download_ajsearch\" href=\"$row->Link\">&nbsp;" . stripslashes($row->Name) . "</a>";
		}
		$div .= '</div>';
		echo $div;
	} else {
		echo "showDiv||";
		echo "";
	}
} else {
	echo "showDiv||";
	echo "";
}
?>