<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('USERLIST')) exit;
// Beitragszaehler aktualisieren
$sql_first = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_forum_userprofile");
while($row_first = $sql_first->fetchrow())
{
	$sql = $GLOBALS['db']->Query("SELECT COUNT(id) AS counts FROM " . PREFIX . "_modul_forum_post WHERE uid = {$row_first->Id}");
	while($row = $sql->fetchrow())
	{
		$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_forum_userprofile SET Beitraege={$row->counts} WHERE BenutzerId={$row_first->Id}");
	}
}

// Benutzerabfrage
$user = array();

if(isset($_REQUEST['orderby']) && $_REQUEST['orderby'] != '')
{
	$nav_link = '';
	switch($_REQUEST['orderby'])
	{
		case 'posts_asc': 
			$orderby = ' ORDER BY Beitraege ASC';  
			$nav_link = '&amp;orderby=posts_asc';
			$img_post = '<img hspace="5" border="0" src="templates/'. T_PATH.'/modules/forums/sortasc.gif" alt="" />';
			$GLOBALS['tmpl']->assign("img_post", $img_post);
		break;
		
		case 'posts_desc': 
			$orderby = ' ORDER BY Beitraege DESC'; 
			$nav_link = '&amp;orderby=posts_desc';
			$img_post = '<img hspace="5" border="0" src="templates/'. T_PATH.'/modules/forums/sortdesc.gif" alt="" />';
			$GLOBALS['tmpl']->assign("img_post", $img_post);
		break;
		
		case 'reg_asc': 
			$orderby = ' ORDER BY Registriert ASC'; 
			$nav_link = '&amp;orderby=reg_asc';
			$img_reg = '<img hspace="5" border="0" src="templates/'. T_PATH.'/modules/forums/sortasc.gif" alt="" />';
			$GLOBALS['tmpl']->assign("img_reg", $img_reg);
		break;
		
		case 'reg_desc': 
			$orderby = ' ORDER BY Registriert DESC'; 
			$nav_link = '&amp;orderby=reg_desc';
			$img_reg = '<img hspace="5" border="0" src="templates/'. T_PATH.'/modules/forums/sortdesc.gif" alt="" />';
			$GLOBALS['tmpl']->assign("img_reg", $img_reg);
		break;
		
		case 'name_asc': 
			$orderby = ' ORDER BY BenutzerName ASC'; 
			$nav_link = '&amp;orderby=name_asc';
			$img_name = '<img hspace="5" border="0" src="templates/'. T_PATH.'/modules/forums/sortasc.gif" alt="" />';
			$GLOBALS['tmpl']->assign("img_name", $img_name);
		break;
		
		case 'name_desc': 
			$orderby = ' ORDER BY BenutzerName DESC'; 
			$nav_link = '&amp;orderby=name_desc';
			$img_name = '<img hspace="5" border="0" src="templates/'. T_PATH.'/modules/forums/sortdesc.gif" alt="" />';
			$GLOBALS['tmpl']->assign("img_name", $img_name);
		break;
	}
} else {
	$orderby = ' ORDER BY Beitraege DESC';
}

// Aktuelle Seite f�r Links
$f_page = (isset($_REQUEST['page']) && $_REQUEST['page'] != '' && is_numeric($_REQUEST['page']) && $_REQUEST['page']>0) ? "&amp;page=". $_REQUEST['page'] : "";

// Sortierungs-Links
$Link_PostSort = (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 'posts_asc') ? "index.php?module=forums&amp;show=userlist&amp;orderby=posts_desc{$f_page}" : "index.php?module=forums&amp;show=userlist&amp;orderby=posts_asc{$f_page}";
$Link_RegSort = (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 'reg_asc') ? "index.php?module=forums&amp;show=userlist&amp;orderby=reg_desc{$f_page}" : "index.php?module=forums&amp;show=userlist&amp;orderby=reg_asc{$f_page}";
$Link_NameSort = (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 'name_asc') ? "index.php?module=forums&amp;show=userlist&amp;orderby=name_desc{$f_page}" : "index.php?module=forums&amp;show=userlist&amp;orderby=name_asc{$f_page}";

$limit = (isset($_REQUEST['pp']) && is_numeric($_REQUEST['pp']) && $_REQUEST['pp'] > 0 ) ? $_REQUEST['pp'] : 25;
$limit = (isset($_REQUEST['print']) && $_REQUEST['print']==1) ? 10000 : $limit;

$g_user = "SELECT Id FROM " . PREFIX . "_modul_forum_userprofile $orderby ";
$sql = $GLOBALS['db']->Query($g_user);
$num = $sql->numrows();

if(!isset($page)) $page = 1;
$seiten = $this->getPageNum($num, $limit);
$a = prepage() * $limit - $limit;
$a = (isset($_REQUEST['print']) && $_REQUEST['print']==1) ? 0 : $a;

$g_user = "
	SELECT 
		* 
	FROM 
		" . PREFIX . "_modul_forum_userprofile 
	WHERE 
		BenutzerName != 'Gast' 
	$orderby 
	LIMIT
		$a,$limit;
	";
$sql = $GLOBALS['db']->Query($g_user);
while($row = $sql->fetchrow())
{
	$row->UserWeb = ($row->Webseite != '' && $row->Webseite_show==1) ? 'http://' . str_replace('http://', '', $row->Webseite) : '';
	$row->UserPN = ($row->Pnempfang==1) ? 'index.php?module=forums&show=pn&amp;action=new&amp;to=' . base64_encode($row->BenutzerName) : '';
	$row->UserLink = ($row->ZeigeProfil==1) ? "<a class=\"forum_links\" href=\"index.php?module=forums&amp;show=userprofile&amp;user_id={$row->BenutzerId}\">{$row->BenutzerName}</a>" : "{$row->BenutzerName}";
	$row->Posts = $this->num_format($row->Beitraege);
	if($row->Registriert!='') array_push($user, $row);
}

$GLOBALS['tmpl']->assign("user", $user);
$GLOBALS['tmpl']->assign("Link_PostSort", $Link_PostSort);
$GLOBALS['tmpl']->assign("Link_RegSort", $Link_RegSort);
$GLOBALS['tmpl']->assign("Link_NameSort", $Link_NameSort);

// Navigation
if ($limit < $num)
{
	$nav_link = (empty($nav_link)) ? '' : $nav_link;
	$GLOBALS['tmpl']->assign('pages',
		pagenav($seiten, 
		(isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) ? $_REQUEST['page'] : 1), 
		" <a class=\"page_navigation\" href=\"index.php?module=forums&amp;show=userlist{$nav_link}&amp;pp=$limit&amp;page={s}\">{t}</a> ")
		);
}

$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'userlist.tpl');
define("MODULE_CONTENT", $tpl_out);	
define("MODULE_SITE",  $GLOBALS['mod']['config_vars']['PageNameUserProfile']);
?>