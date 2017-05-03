<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("USERPOP")) exit;
$limit = 20;

$Phrase = (isset($_REQUEST['Phrase']) && $_REQUEST['Phrase'] != '' && $_REQUEST['Phrase'] > 0 && is_numeric($_REQUEST['Phrase']) && $_REQUEST['Phrase']==1) ? " = " : " LIKE ";
$searchUser = (isset($_REQUEST['BenutzerName']) && !empty($_REQUEST['BenutzerName'])) ? " a.BenutzerName $Phrase '" . addslashes($_REQUEST['BenutzerName']). "%%' AND " : "";

$query  = "
SELECT 
	a.BenutzerName,
	a.BenutzerId,
	b.Id 
FROM
	" . PREFIX . "_modul_forum_userprofile as a,
	" . PREFIX . "_users as b 
WHERE
	a.BenutzerId = b.Id AND  
	a.Pnempfang = 1 AND 
	$searchUser 
	b.Status = 1
ORDER BY
	a.BenutzerName ASC
";

$r_poster = $GLOBALS['db']->Query($query);
$num = $r_poster->numrows();

$seiten = ceil($num / $limit);
$a = prepage() * $limit - $limit;

$r_poster = $GLOBALS['db']->Query($query . "LIMIT $a,$limit");

$poster = array();
while ($post = $r_poster->fetchrow())
{
	$poster[] = $post;
}

$GLOBALS['tmpl']->assign("poster", $poster);

//=======================================================
// Navigation erzeugen
//=======================================================
if($num > $limit){
	$nav = pagenav($seiten, $this->getActPage(), " <a class=\"page_navigation\" href=\"index.php?module=forums&show=userpop&pop=1&cp_theme=" . $_GET['cp_theme'] . "&BenutzerName=" . @$_REQUEST['BenutzerName'] . "&Phrase=" . @$_REQUEST['Phrase'] . "&page={s}\">{t}</a> ");
	$GLOBALS['tmpl']->assign("nav", $nav) ;
}

$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . "users.tpl");
define("MODULE_CONTENT", $tpl_out);	
define("MODULE_SITE",  $GLOBALS['mod']['config_vars']['UserpopName']);
?>