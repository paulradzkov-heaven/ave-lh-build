<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("BASE_DIR")) exit;

$modul = array();
$modul['ModulName'] = "Статистика";
$modul['ModulPfad'] = "counter";
$modul['ModulVersion'] = "1.1";
$modul['Beschreibung'] = "Данный модуль предназначен для сбора статистики посещений страниц вашего сайта, а также дополнительных данных о посетителях. Для того, чтобы начать сбор статистики, разместите системный тег <strong>[cp_counter:XXX]</strong> на нужной вам странице или шаблоне сайта. Для отображения статистики в публичной части, разместите системный тэг <strong>[cp_counter:XXX-show]</strong> в нужном месте Вашего шаблона. ХХХ - это порядковый номер счетчика в системе.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['AdminEdit'] = 1;
$modul['ModulFunktion'] = "cpCounter";
$modul['CpEngineTagTpl'] = "[cp_counter:XXX]";
$modul['CpEngineTag'] = "\\\[cp_counter:([-a-zA-Z0-9\\\]*)\\]";
$modul['CpPHPTag'] = "<?php cpCounter(''\\\\\\\\1''); ?>";

include_once(BASE_DIR . "/modules/counter/sql.php");

include(BASE_DIR . "/modules/counter/class.browser.php");

function cpCounter($id) {
  $counterId = @explode("-", stripslashes($id));
  $counterAction = (!empty($counterId[1])) ? $counterId[1] : '';
  $id = $counterId[0];

  $tpl_dir   = BASE_DIR . "/modules/counter/templates/";
  $lang_file = BASE_DIR . "/modules/counter/lang/" . STD_LANG . ".txt";

  $counter   =& new Counter;
  if ($counterAction == "show") {
    $counter   = $counter->showStat($tpl_dir,$lang_file,$id);
  } else {
    $counter   = $counter->InsertNew($id);
  }
}

if(defined("ACP")) {

  $tpl_dir   = BASE_DIR . "/modules/counter/templates/";
  $lang_file = BASE_DIR . "/modules/counter/lang/" . $_SESSION['cp_admin_lang'] . ".txt";

  $counter =& new Counter;

  $GLOBALS['tmpl']->config_load($lang_file, "admin");
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '') {
    switch($_REQUEST['moduleaction']) {

      case '1':
        $counter->showCounter($tpl_dir,$lang_file);
      break;

      case 'view_referer':
        $counter->showReferer($tpl_dir,$lang_file,$_REQUEST['id']);
      break;

      case 'quicksave':
        $counter->quickSave();
      break;

      case 'new_counter':
        $counter->newCounter();
      break;
    }
  }
}
?>