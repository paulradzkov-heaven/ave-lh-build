<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("SETABO") || UGROUP == 2) exit;
switch($onoff)
{
	case 'on':
		$r_subscriptions = $GLOBALS['db']->Query("SELECT notification FROM " . PREFIX . "_modul_forum_topic WHERE id = '" . addslashes($_GET['t_id']) . "'");
		$subscriptions = $r_subscriptions->fetchrow();
		$user_id = split(",", $subscriptions->notification);
		
		if (!in_array(UID, $user_id))
		{
			$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_forum_topic SET notification = CONCAT(notification, ';', '".UID."') WHERE id = '" . addslashes($_GET['t_id']) . "'");
		}
	break;
	
	case 'off':
		$sql = $GLOBALS['db']->Query("SELECT notification FROM " . PREFIX . "_modul_forum_topic WHERE id = '" . addslashes($_GET['t_id']) . "'");
		$row = $sql->fetchrow();
	
		$new_insert = str_replace(";" . UID, "", $row->notification);
		$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_forum_topic set notification='$new_insert' WHERE id = '" . addslashes($_GET['t_id']) . "'");
	break;
}

header("Location:" . $_SERVER['HTTP_REFERER']);
exit;
?>