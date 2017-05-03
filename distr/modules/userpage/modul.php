<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

$modul['ModulName'] = "Профиль";
$modul['ModulPfad'] = "userpage";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "Расширенная пользовательская система, полностью интегрируемая в модуль форума. Пользовательский профиль дополнен личной гостевой книгой и может модифицироваться индивидуально задаваемыми полями.";
$modul['Autor'] = "Michael Ruhl";
$modul['MCopyright'] = "&copy; 2007 ecombiz.de";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['ModulTemplate'] = 1;
$modul['AdminEdit'] = 1;
$modul['ModulFunktion'] = "NULL";
$modul['CpEngineTagTpl'] = "<b>Ссылка:</b> <a target='_blank' href='/index.php?module=forums'>/index.php?module=forums</a>";
$modul['CpEngineTag'] = "NULL";
$modul['CpPHPTag'] = "NULL";

if(defined('ACP')) {
	$modul_sql_update = array();
	$modul_sql_deinstall = array();
	$modul_sql_install = array();
	include_once(BASE_DIR . '/modules/userpage/sql.php');
}

//=======================================================
// Klasse einbinden
//=======================================================
if(!defined('BASE_DIR')) exit;
include_once(BASE_DIR . '/modules/userpage/class.userpage.php');
include_once(BASE_DIR . '/modules/userpage/func/func.replace.php');

if(isset($_REQUEST['module']) && $_REQUEST['module'] != '' && $_REQUEST['module'] == 'userpage' && isset($_REQUEST['action']))
{
	$userpage = new userpage;

	$tpl_dir = BASE_DIR . "/modules/userpage/templates/";
	$lang_file = BASE_DIR . "/modules/userpage/lang/" . STD_LANG . ".txt";

	$sql_set = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX ."_modul_forum_settings");
	$row_set = $sql_set->fetchrow();

	$sql_gs = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_forum_grouppermissions WHERE Benutzergruppe='" . UGROUP . "'");
	$row_gs = $sql_gs->fetchrow();

	define ("MAX_AVATAR_WIDTH", $row_gs->MAX_AVATAR_WIDTH);
	define ("MAX_AVATAR_HEIGHT", $row_gs->MAX_AVATAR_WIDTH);
	define ("MAX_AVATAR_BYTES", $row_gs->MAX_AVATAR_BYTES);
	define ("SYSTEMAVATARS", $row_set->SystemAvatars);
	define ("UPLOADAVATAR", $row_gs->UPLOADAVATAR);
	define ("FORUMEMAIL", $row_set->AbsenderMail);
	define ("FORUMABSENDER", $row_set->AbsenderName);

	$GLOBALS['tmpl']->config_load($lang_file, "user");
	$config_vars = $GLOBALS['tmpl']->get_config_vars();
	$GLOBALS['tmpl']->assign("config_vars", $config_vars);

	$_SESSION["cp_forumname"] = (isset($_SESSION['cp_benutzerid'])) ? $userpage->fetchusername($_SESSION['cp_benutzerid']) : $GLOBALS['tmpl']->get_config_vars('Guest');
	$_SESSION["cp_forumemail"] = (isset($_SESSION['cp_benutzerid'])) ? $userpage->getForumUserEmail($_SESSION['cp_benutzerid']) : "";

	$userpage->UserOnlineUpdate();

	switch($_REQUEST['action'])
	{
		// Userpage zeigen
		case 'show':
			$userpage->show($tpl_dir,$lang_file,addslashes($_REQUEST['uid']));
		break;

		// Form Eintrag
		case 'form':
			$userpage->displayForm($tpl_dir,addslashes($_REQUEST['uid']),$_REQUEST['cp_theme']);
		break;

		// Kontakt
		case 'contact':
			$userpage->showContact($tpl_dir,$_REQUEST['method'],addslashes($_REQUEST['uid']),$_REQUEST['cp_theme']);
		break;

		// Eintrag lцschen
		case 'del':
			$userpage->del_guest($tpl_dir,addslashes($_REQUEST['gid']),addslashes($_REQUEST['uid']),$_REQUEST['page']);
		break;

		// Userpage bearbeiten
		case 'change':
			$userpage->changeData($tpl_dir,$lang_file);
		break;

	}
}


//=======================================================
// Admin - Aktionen
//=======================================================
if(defined("ACP") && $_REQUEST['action'] != 'delete')
{
	$tpl_dir = BASE_DIR . "/modules/userpage/templates/";
	$lang_file = BASE_DIR . "/modules/userpage/lang/" . STD_LANG . ".txt";

	$userpage = new userpage;

	$GLOBALS['tmpl']->config_load($lang_file, "admin");
	$config_vars = $GLOBALS['tmpl']->get_config_vars();
	$GLOBALS['tmpl']->assign("config_vars", $config_vars);

	if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '')
	{
		switch($_REQUEST['moduleaction'])
		{
			// Einstellungen
			case '1':
				$userpage->showSetting($tpl_dir);
			break;

			// Neues Feld
			case 'save_new':
				$userpage->saveFieldsNew($tpl_dir);
			break;

			// Speichern
			case 'save':
				$userpage->saveSetting($tpl_dir);
			break;

			// Template
			case 'tpl':
				$userpage->showTemplate($tpl_dir);
			break;

			// Autoupdate
			case 'update':
				$userpage->update($tpl_dir);
			break;

		}
	}
}
?>