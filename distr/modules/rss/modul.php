<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("BASE_DIR")) exit;

$modul['ModulName'] = "RSS потоки";
$modul['ModulPfad'] = "rss";
$modul['ModulVersion'] = "1.0beta2";
$modul['Beschreibung'] = "Данный модуль предзназначен для организации RSS потоков на вашем сайте. ";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007-2008 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['AdminEdit'] = 1;
$modul['ModulFunktion'] = "rss";
$modul['CpEngineTagTpl'] = "[rss:XXX]";
$modul['CpEngineTag'] = "\\\[rss:([0-9\\\]*)\\]";
$modul['CpPHPTag'] = "<?php rss(''\\\\\\\\1''); ?>";

// Подключение основного класса и конструктора таблиц в БД
require_once(BASE_DIR . "/modules/rss/sql.php");
require_once(BASE_DIR . "/modules/rss/class.rss.php");

function rss($id)
{
  $tpl_dir   = BASE_DIR . "/modules/rss/templates/";
  $lang_file = BASE_DIR . "/modules/rss/lang/" . STD_LANG . ".txt";
  $GLOBALS['tmpl']->config_load($lang_file);
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);
  $GLOBALS['tmpl']->assign("id",$id);
  $GLOBALS['tmpl']->display($tpl_dir . 'rss_link.tpl');
}

if (isset($_REQUEST['module']) && $_REQUEST['module'] == 'rss' && isset($_REQUEST['do']) && $_REQUEST['do'] == 'show') {
  header("Location:/rss/index.php?id=" . (int)$_GET['id']);
}

if (defined("ACP") && $_REQUEST['action'] != 'delete') {
  $tpl_dir   = BASE_DIR . "/modules/rss/templates/";
  $lang_file = BASE_DIR . "/modules/rss/lang/" . STD_LANG . ".txt";

  $GLOBALS['tmpl']->config_load($lang_file);
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  if (isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '') {

    switch ($_REQUEST['moduleaction']) {

      case '1':
        Rss::rssList($tpl_dir);
      break;

      case 'add':
        Rss::rssAdd();
      break;

      case 'del':
        Rss::rssDelete();
      break;

      case 'edit':
        Rss::rssEdit($tpl_dir);
      break;

      case 'saveedit':
       Rss::rssSave();
      break;
    }
  }
}
?>