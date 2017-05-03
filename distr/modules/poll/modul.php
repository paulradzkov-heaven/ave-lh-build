<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit;

$modul['ModulName'] = "Опросы";
$modul['ModulPfad'] = "poll";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "Данный модуль предназачен для организации системы опросов на сайте. Возможности модуля позволяют создавать неограниченное количество опросных листов, а также неограниченное количество вопросов.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2008 Overdoze.Ru";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['ModulTemplate'] = 1;
$modul['AdminEdit'] = 1;
$modul['ModulFunktion'] = "cp_poll";
$modul['CpEngineTagTpl'] = "[cp_poll:XXX]";
$modul['CpEngineTag'] = "\\\[cp_poll:([0-9\\\]*)\\]";
$modul['CpPHPTag'] = "<?php cp_poll(''\\\\\\\\1''); ?>";

include_once(BASE_DIR . '/modules/poll/sql.php');
include_once(BASE_DIR . '/modules/poll/class.poll.php');

function cp_poll($id) {
	$tpl_dir   = BASE_DIR . "/modules/poll/templates/";
	$lang_file = BASE_DIR . "/modules/poll/lang/" . STD_LANG . ".txt";

	$GLOBALS['tmpl']->config_load($lang_file, "user");
	$config_vars = $GLOBALS['tmpl']->get_config_vars();
	$GLOBALS['tmpl']->assign("config_vars", $config_vars);

	$poll = new poll;
	$poll->showPoll($tpl_dir,$lang_file,stripslashes($id));
}

if(isset($_REQUEST['module']) && $_REQUEST['module'] != '' && $_REQUEST['module'] == 'poll' && isset($_REQUEST['action'])) {
	$poll = new poll;

	$tpl_dir   = BASE_DIR . "/modules/poll/templates/";
	$lang_file = BASE_DIR . "/modules/poll/lang/" . STD_LANG . ".txt";

	$GLOBALS['tmpl']->config_load($lang_file, "user");
	$config_vars = $GLOBALS['tmpl']->get_config_vars();
	$GLOBALS['tmpl']->assign("config_vars", $config_vars);

	switch($_REQUEST['action']) {

    case 'result':
      $pid = (int)$_REQUEST['pid'];
      $poll->showResult($tpl_dir,$lang_file,$pid);
		break;

		case 'vote':
      $pid = (int)$_REQUEST['pid'];
      $poll->vote($pid);
		break;

		case 'archive':
			$poll->showArchive($tpl_dir,$lang_file,$_REQUEST['order'],$_REQUEST['by']);
		break;

		case 'form':
      $pid = (int)$_REQUEST['pid'];
      $theme = addslashes($_REQUEST['cp_theme']);
			$poll->displayForm($tpl_dir,$pid,$theme,'','','');
		break;

		case 'comment':
      $pid = (int)$_REQUEST['pid'];
			$poll->sendForm($tpl_dir,$pid);
		break;

	}
}

if(defined("ACP") && $_REQUEST['action'] != 'delete') {
	$tpl_dir   = BASE_DIR . "/modules/poll/templates/";
	$lang_file = BASE_DIR . "/modules/poll/lang/" . STD_LANG . ".txt";

	$poll = new poll;

	$GLOBALS['tmpl']->config_load($lang_file, "admin");
	$config_vars = $GLOBALS['tmpl']->get_config_vars();
	$GLOBALS['tmpl']->assign("config_vars", $config_vars);

	if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '') {

    switch($_REQUEST['moduleaction']) {

			case '1':
				$poll->showPolls($tpl_dir);
			break;

			case 'edit':
			  $id = (int)$_REQUEST['id'];
				$poll->editPolls($tpl_dir,$id);
			break;

			case 'save_new':
			  $id = (int)$_REQUEST['id'];
				$poll->saveFieldsNew($tpl_dir,$id);
			break;

			case 'save':
			  $id = (int)$_REQUEST['id'];
				$poll->savePolls($tpl_dir,$id);
			break;

			case 'new':
			  $id = (int)$_REQUEST['id'];
				$poll->newPolls($tpl_dir,$id);
			break;

			case 'delete':
			  $id = (int)$_REQUEST['id'];
				$poll->deletePolls($id);
			break;

			case 'comments':
			  $id = (int)$_REQUEST['id'];
				$poll->showComments($tpl_dir,$id);
			break;
		}
	}
}
?>