<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('NEWTOPIC')) exit;
if(isset($_REQUEST['fid']) && $_REQUEST['fid'] != '' && is_numeric($_REQUEST['fid']) && $_REQUEST['fid'] > 0)
{
	// forum id überprüfen
	$forum_result = $GLOBALS['db']->Query("SELECT title, status FROM " . PREFIX . "_modul_forum_forum WHERE id = '" . $_REQUEST['fid'] . "'");
	$forum = $forum_result->fetchrow();
	
	// es wurde eine falsche fid übergeben
	if ($forum_result->numrows() < 1)
	{
		header("Location:index.php?module=forums");
		exit;
	}
	
	if ( ($forum->status == FORUM_STATUS_CLOSED) )
	{
		$this->msg($GLOBALS['mod']['config_vars']['ErrorClosed'], 'index.php?module=forums&show=showforum&fid=' . $_GET['fid']);
	}
	
	// ====================================================================================
	// zugriffsrechte
	// ====================================================================================
	$cat_query = $GLOBALS['db']->Query("SELECT group_id FROM " . PREFIX . "_modul_forum_forum WHERE id = '" . $_REQUEST['fid'] . "'");
	while ($category = $cat_query->fetchrow_assoc())
	{
		// miscrechte
		$group_ids = array();
		include ( BASE_DIR . "/modules/forums/internals/misc_ids.php");
		
		$perm = false;
		$groups = explode(",", $category['group_id']);
		if (array_intersect($group_ids, $groups))
		{
			$permissions = $this->getForumPermissionsByUser(addslashes($_REQUEST['fid']), UID);
			if ($permissions[FORUM_PERMISSION_CAN_CREATE_TOPIC] == 1)
			{
				$perm = true;
			}
		}
	}
	
	// user hat keine berechtigung oder ist kein admin
	if ( $perm == false)
	{
		header("Location:index.php?module=forums");
		exit;
	}
	
	if (SMILIES == 1) $GLOBALS['tmpl']->assign("smilie", 1);
	
	// navigation erzeugen
	$navigation = $this->getNavigation(addslashes($_REQUEST['fid']), "forum");
	
	$GLOBALS['tmpl']->assign("permissions", $permissions);
	$GLOBALS['tmpl']->assign("bbcodes", BBCODESITE);
	$GLOBALS['tmpl']->assign("new_topic", 1);
	$GLOBALS['tmpl']->assign("navigation", $navigation . $GLOBALS['mod']['config_vars']['ForumSep'] . "<a class='forum_links_navi' href='index.php?module=forums&amp;show=showforum&amp;fid=" . addslashes($_REQUEST['fid']). "'>" . $forum->title . "</a>");
	$GLOBALS['tmpl']->assign("maxlength_post", MAXLENGTH_POST);
	$GLOBALS['tmpl']->assign("listfonts", $this->fontDropdown());
	$GLOBALS['tmpl']->assign("sizedropdown", $this->sizeDropdown());
	$GLOBALS['tmpl']->assign("colordropdown", $this->colorDropdown());
	$GLOBALS['tmpl']->assign("posticons", ( (defined("USEPOSTICONS") && USEPOSTICONS==1) ? $this->getPosticons() : ""));
	$GLOBALS['tmpl']->assign("listemos", $this->listSmilies());
	$GLOBALS['tmpl']->assign("forum_id", $_GET["fid"]);
	$GLOBALS['tmpl']->assign("topicform", $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . "topicform.tpl"));
	$GLOBALS['tmpl']->assign("threadform", $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . "threadform.tpl"));
	$GLOBALS['tmpl']->assign("action", "index.php?module=forums&amp;show=addtopic");
	
	$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'addtopic.tpl');
	define("MODULE_CONTENT", $tpl_out);	
	define("MODULE_SITE", $GLOBALS['mod']['config_vars']['NewThread']);
		
} else {
	header("Location:index.php?module=forums");
	exit;
}
?>