<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("BASE_DIR")) exit;

$modul['ModulName'] = " арта проектов";
$modul['ModulPfad'] = "roadmap";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "ƒанный модуль предназначен дл€ организации карты проектов с текущими задачами. ¬ы можете создавать неограниченное количество проектов с неограниченным количеством задач. ¬се проекты с вложенными задачами могут быть отображены в публичной части сайта.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007-2008 Overdoze.Ru";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['ModulTemplate'] = 1;
$modul['AdminEdit'] = 1;
$modul['ModulFunktion'] = "NULL";
$modul['CpEngineTagTpl'] = "<b>—сылка:</b> <a target='_blank' href='../roadmap/'>/roadmap/</a>";
$modul['CpEngineTag'] = "NULL";
$modul['CpPHPTag'] = "NULL";

include_once(BASE_DIR . "/modules/roadmap/sql.php");

include_once(BASE_DIR . "/modules/roadmap/class.roadmap.php");

if(isset($_REQUEST['module']) && $_REQUEST['module'] != '' && $_REQUEST['module'] == 'roadmap') {
  $roadmap = new Roadmap;
  $tpl_dir   = BASE_DIR . "/modules/roadmap/templates/";
  $lang_file = BASE_DIR . "/modules/roadmap/lang/" . STD_LANG . ".txt";

  $GLOBALS['tmpl']->config_load($lang_file);
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  switch($_REQUEST['action']) {
    case 'show_p':
	default:
	  $roadmap->show_p($tpl_dir);
	break;

	case 'show_t':
	  $pid    = addslashes($_REQUEST['pid']);
	  $closed = addslashes($_REQUEST['closed']);
	  $roadmap->show_t($pid,$closed,$tpl_dir);
	break;

	}
  define("MODULE_SITE", " арта проектов");

}

if(defined("ACP") && $_REQUEST['action'] != 'delete') {

	$tpl_dir = BASE_DIR . "/modules/roadmap/templates/";
	$lang_file = BASE_DIR . "/modules/roadmap/lang/" . STD_LANG . ".txt";

	$roadmap = new Roadmap;

	$GLOBALS['tmpl']->config_load($lang_file);
	$config_vars = $GLOBALS['tmpl']->get_config_vars();
	$GLOBALS['tmpl']->assign("config_vars", $config_vars);

	if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '')	{
		switch($_REQUEST['moduleaction']) {

			case '1':
				$roadmap->list_projects($tpl_dir);
			break;

			case 'edit_project':
				$id = addslashes($_REQUEST['id']);
				$roadmap->edit_project($tpl_dir,$id);
			break;

			case 'new_project':
				$roadmap->new_project($tpl_dir);
			break;

			case 'del_project':
				$id = addslashes($_REQUEST['id']);
				$roadmap->del_project($id);
			break;

			case 'show_tasks':
				$id     = addslashes($_REQUEST['id']);
				$closed = addslashes($_REQUEST['closed']);
				$roadmap->show_tasks($tpl_dir,$id,$closed);
			break;

			case 'new_task':
				$id = (int)$_REQUEST['id'];
				$roadmap->new_task($tpl_dir,$id);
			break;

			case 'edit_task':
				$id = (int)$_REQUEST['id'];
				$roadmap->edit_task($tpl_dir,$id);
			break;

			case 'del_task':
				$id     = (int)$_REQUEST['id'];
				$pid    = (int)$_REQUEST['pid'];
				$closed = (int)$_REQUEST['closed'];
				$roadmap->del_task($id,$pid,$closed);
			break;

		}
	}
}
?>