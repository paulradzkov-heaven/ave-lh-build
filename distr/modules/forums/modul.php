<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit;
$modul['ModulName'] = "Форумы";
$modul['ModulPfad'] = "forums";
$modul['ModulVersion'] = "1.2";
$modul['Beschreibung'] = "Система форумов для cpengine, разработанная компанией dream4";
$modul['Autor'] = "Bj&ouml;rn Wunderlich";
$modul['MCopyright'] = "&copy; 2006 dream4";
$modul['Status'] = 1;
$modul['IstFunktion'] = 0;
$modul['ModulTemplate'] = 1;
$modul['AdminEdit'] = 1;
$modul['ModulFunktion'] = "NULL";
$modul['CpEngineTagTpl'] = "<b>Ссылка:</b> <a target='_blank' href='../index.php?module=forums'>/index.php?module=forums</a>";
$modul['CpEngineTag'] = "NULL";
$modul['CpPHPTag'] = "NULL";


if( (isset($_REQUEST['module']) && $_REQUEST['module'] == 'forums') || (isset($_REQUEST['mod']) && $_REQUEST['mod'] == 'forums') )
{
	
	if(defined('ACP'))
	{
		$modul_sql_update = array();
		$modul_sql_deinstall = array();
		$modul_sql_install = array();
		include_once(BASE_DIR . '/modules/forums/sql.php');
	}

	
		define ("GET_NO_DOC", 1);
		define ("FORUM_STATUS_OPEN", 0);
		define ("FORUM_STATUS_CLOSED", 1);
		define ("FORUM_STATUS_MOVED", 2);
		define ("FORUM_DEFAULT_TOPIC_LIMIT", 10);
		define ("FORUM_AGE_LIMIT", 10);
		define ("FORUM_PERMISSION_CAN_SEE", 0);
		define ("FORUM_PERMISSION_CAN_SEE_TOPIC", 1);
		define ("FORUM_PERMISSION_CAN_SEE_DELETE_MESSAGE", 2);
		define ("FORUM_PERMISSION_CAN_SEARCH_FORUM", 3);
		define ("FORUM_PERMISSION_CAN_DOWNLOAD_ATTACHMENT", 4);
		define ("FORUM_PERMISSION_CAN_CREATE_TOPIC", 5);
		define ("FORUM_PERMISSION_CAN_REPLY_OWN_TOPIC", 6);
		define ("FORUM_PERMISSION_CAN_REPLY_OTHER_TOPIC", 7);
		define ("FORUM_PERMISSION_CAN_UPLOAD_ATTACHMENT", 8);
		define ("FORUM_PERMISSION_CAN_RATE_TOPIC", 9);
		define ("FORUM_PERMISSION_CAN_EDIT_OWN_POST", 10);
		define ("FORUM_PERMISSION_CAN_DELETE_OWN_POST", 11);
		define ("FORUM_PERMISSION_CAN_MOVE_OWN_TOPIC", 12);
		define ("FORUM_PERMISSION_CAN_CLOSE_OPEN_OWN_TOPIC", 13);
		define ("FORUM_PERMISSION_CAN_DELETE_OWN_TOPIC", 14);
		define ("FORUM_PERMISSION_CAN_DELETE_OTHER_POST", 15);
		define ("FORUM_PERMISSION_CAN_EDIT_OTHER_POST", 16);
		define ("FORUM_PERMISSIONS_CAN_OPEN_TOPIC", 17);
		define ("FORUM_PERMISSIONS_CAN_CLOSE_TOPIC", 18);
		define ("FORUM_PERMISSIONS_CAN_CHANGE_TOPICTYPE", 19);
		define ("FORUM_PERMISSIONS_CAN_MOVE_TOPIC", 20);
		define ("FORUM_PERMISSIONS_CAN_DELETE_TOPIC", 21);
	

	//=======================================================
	// Klasse einbinden
	//=======================================================
	if(defined('ACP') && $_REQUEST['action'] != 'delete') 
	{
		include_once(BASE_DIR . '/modules/forums/class.forums_admin.php');
	} else {
		include_once(BASE_DIR . '/modules/forums/class.forums.php');
		
		$forums = new Forum;
		$forums->AutoUpdatePerms();
		
		$sql_set = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX ."_modul_forum_settings");
		$row_set = $sql_set->fetchrow();
		
		$sql_gs = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_forum_grouppermissions WHERE Benutzergruppe='" . UGROUP . "'");
		$row_gs = $sql_gs->fetchrow();
		
		define ("BOARD_NEWPOSTMAXAGE", "-4 weeks");
		define ("MAX_AVATAR_WIDTH", $row_gs->MAX_AVATAR_WIDTH);
		define ("MAX_AVATAR_HEIGHT", $row_gs->MAX_AVATAR_WIDTH);
		define ("MAX_AVATAR_BYTES", $row_gs->MAX_AVATAR_BYTES);
		define ("SYSTEMAVATARS", $row_set->SystemAvatars);
		define ("UPLOADAVATAR", $row_gs->UPLOADAVATAR);
		define ("MAXPN", $row_gs->MAXPN);
		define ("MAXPNLENTH", $row_gs->MAXPNLENTH);
		define ("MISCIDSINC", 1);
		define ("FORUMEMAIL", $row_set->AbsenderMail);
		define ("FORUMABSENDER", $row_set->AbsenderName);
		define ("BBCODESITE", $row_set->BBCode);
		define ("IMGCODE", $row_set->BBCode);
		define ("SMILIES", $row_set->Smilies);
		define ("USEPOSTICONS", $row_set->Posticons);
		define ("COMMENTSBBCODE", $row_set->BBCode);
		define ("MAXLENGTH_POST", $row_gs->MAXLENGTH_POST);
		define ("MAXATTACHMENTS", $row_gs->MAXATTACHMENTS);
		define ("forum_images", "templates/". T_PATH."/modules/forums/");
		define ("TOPIC_TYPE_NONE", 0);
		define ("TOPIC_TYPE_STICKY", 1);
		define ("TOPIC_TYPE_ANNOUNCE", 100);
		define ("EXPIRE_MINUTE", time() + 60);
		define ("EXPIRE_HOURS",  time() + 60*60);
		define ("EXPIRE_DAY",    time() + 60*60*24);
		define ("EXPIRE_MONTH",  time() + 60*60*24*30);
		define ("EXPIRE_YEAR",   time() + 60*60*24*30*365);
		define ("MAX_EDIT_PERIOD", $row_gs->MAX_EDIT_PERIOD); // Zeit in Stunden, in der der ein Beitrag editiert werden kann 720 = 1 Monat
	}

	
	
	$_REQUEST['show'] = (!isset($_REQUEST['show']) || $_REQUEST['show'] == '') ? 'showforums' : $_REQUEST['show'];
	if(!defined('ACP') && isset($_REQUEST['module']) && $_REQUEST['module'] != '' && $_REQUEST['module'] == 'forums' && isset($_REQUEST['show']))
	{
		include_once(BASE_DIR . '/functions/func.modulglobals.php');
		if(defined('T_PATH')) $GLOBALS['tmpl']->assign('cp_theme', T_PATH);
	
		modulGlobals('forums');
		$forums = new Forum;
		
		define ("USERNAME", (isset($_SESSION['cp_benutzerid']) && is_numeric($_SESSION['cp_benutzerid'])) ? $forums->fetchusername($_SESSION['cp_benutzerid']) : 'UNAME');
		$_SESSION["cp_forumname"] = (isset($_SESSION['cp_benutzerid'])) ? $forums->fetchusername($_SESSION['cp_benutzerid']) : $GLOBALS['mod']['config_vars']['Guest'];
		$_SESSION["cp_forumemail"] = (isset($_SESSION['cp_benutzerid'])) ? $forums->getForumUserEmail($_SESSION['cp_benutzerid']) : "";
		
		$forums->UserOnlineUpdate();
		
		//=======================================================
		// Klasse erweitern
		//=======================================================
		class EigeneForumklasse extends Forum
		{
			// Eigene Funktionen
		}
		
		$GLOBALS['tmpl']->register_function('cpencode', 'cpencode');
		$GLOBALS['tmpl']->register_function('cpdecode', 'cpdecode');
		$GLOBALS['tmpl']->register_function('get_post_icon', 'getPostIcon');
		
		$GLOBALS['tmpl']->assign('forum_images', 'templates/' . T_PATH . '/modules/forums/');
		$GLOBALS['tmpl']->assign('sys_avatars', SYSTEMAVATARS);
		$GLOBALS['tmpl']->assign("header", $row_set->pageheader);
		$GLOBALS['tmpl']->assign("pageheader", $row_set->pageheader);
		$GLOBALS['tmpl']->assign("inc_path", BASE_DIR . "/modules/forums/templates");
		$GLOBALS['tmpl']->assign("ugroup", UGROUP);
		$GLOBALS['tmpl']->assign('PNunreaded', $forums->pnUnreaded());
		$GLOBALS['tmpl']->assign('PNreaded', $forums->pnReaded());
		$GLOBALS['tmpl']->assign('SearchPop', $forums->popSearch());
		$GLOBALS['tmpl']->assign('get_mods', $forums->get_mods(@$_REQUEST['fid']));
		$GLOBALS['tmpl']->assign('stats_user', $forums->ForumStats());
		
		$GLOBALS['tmpl']->assign('maxlength_post', MAXLENGTH_POST);
		$GLOBALS['tmpl']->assign('maxattachment', MAXATTACHMENTS);
		$GLOBALS['tmpl']->assign('max_avatar_width', MAX_AVATAR_WIDTH);
		$GLOBALS['tmpl']->assign('max_avatar_height', MAX_AVATAR_HEIGHT);
		
		// Wenn Benutzergruppe keinen Zugriff hat
		if((!$forums->fperm("accessforums")))
		{
			$forums->msg($GLOBALS['mod']['config_vars']['ForumNoAccess'], 'index.php?module=login&action=register');
		}
		
		switch($_REQUEST['show'])
		{
			
			case 'ignorelist':
				$forums = new EigeneForumklasse;
				$forums->ignoreList(); 
			break;
			
			case 'userpop':
				$forums = new EigeneForumklasse;
				$forums->userPopUp(); 
			break;
			
			case 'showforums':
				$forums = new EigeneForumklasse;
				$forums->showForums(); 
			break;
			
			case 'showforum':
				$forums = new EigeneForumklasse;
				$forums->showForum(); 
			break;
			
			case 'showtopic':
				$forums = new EigeneForumklasse;
				$forums->showTopic(); 
			break;
			
			case 'getfile':
				$forums = new EigeneForumklasse;
				$forums->getFile(); 
			break;
			
			case 'closetopic':
				$forums = new EigeneForumklasse;
				$forums->openClose('close'); 
			break;
			
			case 'opentopic':
				$forums = new EigeneForumklasse;
				$forums->openClose('open'); 
			break;
			
			case 'move':
				$forums = new EigeneForumklasse;
				$forums->moveTopic(); 
			break;
			
			case 'deltopic':
				$forums = new EigeneForumklasse;
				$forums->delTopic();
			break;
			
			case 'addtopic':
				$forums = new EigeneForumklasse;
				$forums->addTopic();
			break;
			
			case 'newtopic':
				$forums = new EigeneForumklasse;
				$forums->newTopic();
			break;
			
			case 'newpost':
				$forums = new EigeneForumklasse;
				$forums->newPost();
			break;
			
			case 'addpost':
				$forums = new EigeneForumklasse;
				$forums->addPost();
			break;
			
			case 'delpost':
				$forums = new EigeneForumklasse;
				$forums->delPost();
			break;
			
			case 'addsubscription':
				$forums = new EigeneForumklasse;
				$forums->setAbo('on');
			break;
			
			case 'unsubscription':
				$forums = new EigeneForumklasse;
				$forums->setAbo('off');
			break;
			
			case 'forumlogin':
				$forums = new EigeneForumklasse;
				$forums->forumsLogin();
			break;
			
			case 'change_type':
				$forums = new EigeneForumklasse;
				$forums->changeType();
			break;
			
			case 'markread':
				$forums = new EigeneForumklasse;
				$forums->markRead();
			break;
			
			case 'last24':
				$forums = new EigeneForumklasse;
				if(!$forums->fperm('last24')) $forums->msg($GLOBALS['mod']['config_vars']['ErrornoPerm']);
				$forums->last24();
			break;
			
			case 'myabos':
				$forums = new EigeneForumklasse;
				$forums->myAbos();
			break;
			
			case 'userpostings':
				$forums = new EigeneForumklasse;
				$forums->userPostings(); 
			break;
			
			case 'userprofile':
				$forums = new EigeneForumklasse;
				if(!$forums->fperm('userprofile')) $forums->msg($GLOBALS['mod']['config_vars']['ErrornoPerm']);
				$forums->showUserProfile(); 
			break;
			
			case 'showposter':
				$forums = new EigeneForumklasse;
				$forums->showPoster(); 
			break;
			
			case 'rating':
				$forums = new EigeneForumklasse;
				$forums->voteTopic(); 
			break;
			
			case 'attachfile':
				$forums = new EigeneForumklasse;
				$forums->attachFile();
			break;
			
			case 'search_mask':
				$forums = new EigeneForumklasse;
				if(!$forums->fperm('cansearch')) $forums->msg($GLOBALS['mod']['config_vars']['ErrornoPerm']);
				$forums->searchMask();
			break;
			
			case 'search':
				$forums = new EigeneForumklasse;
				if(!$forums->fperm('cansearch')) $forums->msg($GLOBALS['mod']['config_vars']['ErrornoPerm']);
				$forums->doSearch();
			break;
			
			case 'publicprofile':
				$forums = new EigeneForumklasse;
				$forums->myProfile();
			break;
			
			case 'pn':
				$forums = new EigeneForumklasse;
				$forums->pMessages();
			break;
			
			case 'userlist':
				$forums = new EigeneForumklasse;
				$forums->getUserlist();
			
			case 'import':
				$forums->importfromkoobi();
			break;
		}
	}
	
	
	//=======================================================
	// Admin - Aktionen
	//=======================================================
	if(defined('ACP') && $_REQUEST['action'] != 'delete')
	{
		$tpl_dir = BASE_DIR . '/modules/forums/templates_admin/';
		$tpl_dir_source = BASE_DIR . '/modules/forums/templates_admin';
		$lang_file = BASE_DIR . '/modules/forums/lang/' . $_SESSION['cp_admin_lang'] . '.txt';
	
		$forums = new Forum;
		
		$GLOBALS['tmpl']->config_load($lang_file, "admin");
		$config_vars = $GLOBALS['tmpl']->get_config_vars();
		$GLOBALS['tmpl']->assign("config_vars", $config_vars);
		$GLOBALS['tmpl']->assign('source', $tpl_dir_source);
		
		if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '')
		{
			$forums->AutoUpdatePerms();
			
			switch($_REQUEST['moduleaction'])
			{
				// Kommentare
				case '1':
					$forums->forumAdmin($tpl_dir);
				break;
				
				case 'edit_category':
					$forums->editCategory($tpl_dir, $_GET['id']);
				break;
				
				case 'edit_forum':
					$forums->editForum($tpl_dir, $_GET['id']);
				break;
				
				case 'mods':
					$forums->addMods($tpl_dir, $_REQUEST['id']);
				break;
				
				case 'delete_forum':
					$forums->deleteForum($_GET['id']);
					header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=1&cp=" . SESSION);
				break;
				
				case 'delete_topics':
					$forums->delTopics($tpl_dir);
				break;
				
				
				case 'show_page':
					$forums->showPage($tpl_dir);
				break;
				

				case 'closeforum':
					$forums->forumOpenClose($tpl_dir, $_GET['id'],'close');
				break;
				
				case 'openforum':
					$forums->forumOpenClose($tpl_dir, $_GET['id'],'open');
				break;
				
				case 'permissions':
					$forums->editPermissions($tpl_dir);
				break;
				
				case 'delcategory':
					$forums->deleteCat($tpl_dir, $_GET['id']);
				break;
				
				case 'addforum':
					$forums->addForum($tpl_dir, $_GET['id']);
				break;
				
				case 'addcategory':
					$forums->addCategory($tpl_dir);
				break;
				
				case 'attachment_manager':
					$forums->attachmentManager($tpl_dir);
				break;
				
				case 'show_attachments':
					$forums->showAttachments($tpl_dir);
				break;
				
				case 'user_ranks':
					$forums->userRanks($tpl_dir);
				break;
				
				case 'list_smilies':
					$forums->listSmilies($tpl_dir);
				break;
				
				case 'list_icons':
					$forums->listIcons($tpl_dir);
				break;
				
				case 'group_perms':
					$forums->groupPerms($tpl_dir);
				break;
				
				case 'import':
					$forums->import($tpl_dir);
				break;
				
				// Einstellungen
				case 'settings':
					$forums->settings($tpl_dir);
				break;
			}
		}
	}
}
?>