<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit;

$modul['ModulName'] = "ѕоиск";
$modul['ModulPfad'] = "search";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "ƒанный модуль позвол€ет организвать поиск необходимой информации на вашем сайте. ѕоиск информации осуществл€етс€ как по заголовкам документов, так и по содержимому. ƒл€ того, чтобы вывести форму дл€ поиска на вашем сайте, разместите системный тег <strong>[cp:search]</strong> в нужном месте вашего шаблона сайта.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['ModulTemplate'] = 1;
$modul['AdminEdit'] = 1;
$modul['ModulFunktion'] = "cpSearch";
$modul['CpEngineTagTpl'] = "[cp:search]";
$modul['CpEngineTag'] = "\\\[cp:search\\\]";
$modul['CpPHPTag'] = "<?php cpSearch(); ?>";

include_once(BASE_DIR . "/modules/search/sql.php");
include_once(BASE_DIR . "/modules/search/class.porter.php");

if(!@include(BASE_DIR . "/modules/search/class.search.php")) ModuleError();

function cpSearch() {
  $tpl_dir = BASE_DIR . '/modules/search/templates/';
  $lang_file = BASE_DIR . '/modules/search/lang/' . STD_LANG . '.txt';
  $search =& new Search;
  $search->fetchForm($tpl_dir,$lang_file);
}

if(isset($_REQUEST['module']) && $_REQUEST['module'] != '' && $_REQUEST['module'] == 'search') {
  $tpl_dir = BASE_DIR . '/modules/search/templates/';
  $lang_file = BASE_DIR . '/modules/search/lang/' . STD_LANG . '.txt';
  $search = new Search;
  $search->getSearchResults($tpl_dir,$lang_file);
}

if(defined('ACP') && $_REQUEST['action'] != 'delete') {
  $tpl_dir   = BASE_DIR .'/modules/search/templates/';
  $lang_file = BASE_DIR .'/modules/search/lang/' . $_SESSION['cp_admin_lang'] . '.txt';

  $search =& new Search;

  $GLOBALS['tmpl']->config_load($lang_file);
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '') {
    switch($_REQUEST['moduleaction']) {

      case '1':
        $search->showWords($tpl_dir);
      break;

      case 'delwords':
        $search->delWords();
      break;
    }
  }
}
?>