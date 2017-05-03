<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

$modul['ModulName'] = "Внутренняя рассылка";
$modul['ModulPfad'] = "newsletter_sys";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "Данный модуль предназначен для массовой рассылки сообщений группам пользователей через Панель управления.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007-2008 Overdoze.Ru";
$modul['Status'] = 1;
$modul['IstFunktion'] = 0;
$modul['ModulTemplate'] = 0;
$modul['AdminEdit'] = 1;
$modul['ModulFunktion'] = "NULL";
$modul['CpEngineTagTpl'] = "";
$modul['CpEngineTag'] = "NULL";
$modul['CpPHPTag'] = "NULL";

if( (defined('ACP')) && (isset($_REQUEST['module']) && $_REQUEST['module'] == 'newsletter_sys') || (isset($_REQUEST['mod']) && $_REQUEST['mod'] == 'newsletter_sys')) {
	if($_REQUEST['action'] != 'delete') {
		include_once(BASE_DIR . '/modules/newsletter_sys/sql.php');
	}

	include_once(BASE_DIR . '/modules/newsletter_sys/class.newsletter_admin.php');
	include_once(BASE_DIR . '/functions/func.modulglobals.php');
	include_once(BASE_DIR . '/class/class.user.php');

	if(defined('T_PATH')) $GLOBALS['tmpl']->assign('cp_theme', T_PATH);
	$_REQUEST['action'] = (!isset($_REQUEST['action']) || $_REQUEST['action'] == '') ? 'overview' : $_REQUEST['action'];

	$tpl_dir        = BASE_DIR . '/modules/newsletter_sys/templates_admin/';
	$tpl_dir_source = BASE_DIR . '/modules/newsletter_sys/templates_admin';
	$lang_file      = BASE_DIR . '/modules/newsletter_sys/lang/' . $_SESSION['cp_admin_lang'] . '.txt';

	$newsletter = new systemNewsletter;

	$GLOBALS['tmpl']->config_load($lang_file, "admin");
	$config_vars = $GLOBALS['tmpl']->get_config_vars();
	$GLOBALS['tmpl']->assign("config_vars", $config_vars);
	$GLOBALS['tmpl']->assign('source', $tpl_dir_source);

	if(defined('ACP') && $_REQUEST['action'] != 'delete') {
		switch($_REQUEST['moduleaction']) {
			case '':
			case '1':
				$newsletter->sentList($tpl_dir);
			break;

			case 'new':
				$newsletter->sendNew($tpl_dir);
			break;

			case 'shownewsletter':
        $id = (int)$_REQUEST['id'];
        $format = ($_REQUEST['format'] == 'html') ? 'html' : 'text';
				$newsletter->showNewsletter($tpl_dir,$id, $format);
			break;

			case 'delete':
				$newsletter->deleteNewsletter();
			break;

			case 'getfile':
				$newsletter->getFile($_REQUEST['file']);
			break;
		}
	}
}
?>