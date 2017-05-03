<?php
if(!defined("BASE_DIR")) exit;

$modul['ModulName'] = "Новости";
$modul['ModulPfad'] = "news";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "Новости+превьюхи";
$modul['Autor'] = "cron";
$modul['MCopyright'] = "cron";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['AdminEdit'] = 1;
$modul['ModulFunktion'] = "cpNews";
$modul['CpEngineTagTpl'] = "[cp_news:XXX-LIMIT]";
$modul['CpEngineTag'] = "\\\[cp_news:([-0-9\\\]*)\\]";
$modul['CpPHPTag'] = "<?php cpNews(''\\\\\\\\1''); ?>";

if(defined('ACP')){
	$modul_sql_update = array();
	$modul_sql_deinstall = array();
	$modul_sql_install = array();
	include_once(BASE_DIR . '/modules/news/sql.php');
}
include(BASE_DIR . "/modules/news/class.news.php");

function cpNews($id) {
	$own_lim = @explode("-", stripslashes($id));
	$lim = (!empty($own_lim[1])) ? $own_lim[1] : '';
	$id = $own_lim[0];

	$tpl_dir = BASE_DIR . $GLOBALS['tmpl']->_tpl_vars['tpl_path'] . "/modules/news/";
	$lang_file = BASE_DIR . "/modules/news/lang/" . STD_LANG . ".txt";

	$GLOBALS['tmpl']->config_load($lang_file, "user");
	$config_vars = $GLOBALS['tmpl']->get_config_vars();
	$GLOBALS['tmpl']->assign("config_vars", $config_vars);

	$news = new News;
	$news->showNews($tpl_dir,$id,$lim);
}

if(defined("ACP") && $_REQUEST['action'] != 'delete') {
	$tpl_dir = BASE_DIR . "/modules/news/templates/";
	$lang_file = BASE_DIR . "/modules/news/lang/" . STD_LANG . ".txt";

	$news = new News;

	$GLOBALS['tmpl']->config_load($lang_file, "admin");
	$config_vars = $GLOBALS['tmpl']->get_config_vars();
	$GLOBALS['tmpl']->assign("config_vars", $config_vars);

	if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '') {

		switch($_REQUEST['moduleaction']) {

			case '1':
				$news->adminSettings($tpl_dir);
			break;

			case 'new':
				$news->newRub();
			break;

			case 'newsr':
				$news->newSubrub();
			break;

			case 'addnews':
				$news->addNews();
			break;

			case 'editnews':
				$news->editNews();
			break;

			case 'delrub':
				$news->delRub($_REQUEST['id']);
			break;

			case 'delsubrub':
				$news->delSubrub($_REQUEST['id']);
			break;

			case 'editrub':
				$news->editRub($tpl_dir,$_REQUEST['id']);
			break;

			case 'editsubrub':
				$news->editSubrub($_REQUEST['id']);
			break;
		}
	}
}
?>