<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('CHANGETYPE')) exit;
	if(isset($_REQUEST['fid']) && $_REQUEST['fid'] != '' && is_numeric($_REQUEST['fid']) && $_REQUEST['fid']>0 
		&& 
		isset($_REQUEST['toid']) && $_REQUEST['toid'] != '' && is_numeric($_REQUEST['toid']) && $_REQUEST['toid']>0)
	{
	
	$_REQUEST['fid'] = addslashes($_REQUEST['fid']);
	$_REQUEST['toid'] = addslashes($_REQUEST['toid']);
	
	$f_id = $_REQUEST['fid'];
	$permissions = $this->getForumPermissionsByUser($f_id, UID);
	
	if ($permissions[FORUM_PERMISSIONS_CAN_CHANGE_TOPICTYPE] == 0)
	{
		header('Location:index.php?module=forums');
		exit;
	}
	
	//=======================================================
	// Speichern
	//=======================================================
	if(isset($_REQUEST['sub']) && $_REQUEST['sub']=='save')
	{
		$q_topic_type = "UPDATE " . PREFIX . "_modul_forum_topic SET type = '" . addslashes($_POST["type"]) . "' WHERE id = '" . $_REQUEST['toid'] . "'";
		$GLOBALS['db']->Query($q_topic_type);
		header('Location:index.php?module=forums&show=showtopic&toid='.$_REQUEST['toid'].'&fid='.$_REQUEST['fid'].'');
		exit;
	}
	
	//=======================================================
	// Auslesen
	//=======================================================
	$q_topic = "SELECT id, title, type FROM " . PREFIX . "_modul_forum_topic WHERE id =" . $_REQUEST["toid"];
	$r_topic = $GLOBALS['db']->Query($q_topic);
	$topic = $r_topic->fetchrow();
	
	$tmp_navi = $this->getNavigation($_REQUEST['toid'], 'topic');
	
	$GLOBALS['tmpl']->assign('topic', $topic);
	$GLOBALS['tmpl']->assign('navigation', $tmp_navi);
	$GLOBALS['tmpl']->assign('ref', $_SERVER['HTTP_REFERER']);
	
	$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'change_type.tpl');
		
	define('MODULE_CONTENT', $tpl_out);	
	define('MODULE_SITE',  strip_tags($tmp_navi));
		
} else {
	header('Location:index.php?module=forums');
	exit;
}
?>