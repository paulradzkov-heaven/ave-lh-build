<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 1)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: February 22, 2008
::::::::::::::::::::::::::::::::::::::::*/

/*::::::::::::::::::::::::::::::::::::::::
 Module name: Faq
 Short Desc: Frequrent Answer and Questions
 Version: 1.0 alpha
 Authors:  Freeon (php_demon@mail.ru)
 Date: april 5, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("BASE_DIR")) exit;

$modul['ModulName'] = "¬опрос/ответ";
$modul['ModulPfad'] = "faq";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "ћодуль создани€ раширенной справочной системы на основе тегов.";
$modul['Autor'] = "Freeon";
$modul['MCopyright'] = "&copy; 2007-2008 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['AdminEdit'] = 1;
$modul['ModulFunktion'] = "faq";
$modul['CpEngineTagTpl'] = "[faq:XXX]";
$modul['CpEngineTag'] = "\\\[faq:([0-9\\\]*)\\]";
$modul['CpPHPTag'] = "<?php faq(''\\\\\\\\1''); ?>";

// include sql constructor table and base class of the module
require_once(BASE_DIR . "/modules/faq/sql.php");
require_once(BASE_DIR . "/modules/faq/class.faq.php");

// show faq
function faq($id) {
  $tpl_dir   = BASE_DIR . "/modules/faq/templates/";
  $lang_file = BASE_DIR . "/modules/faq/lang/" . STD_LANG . ".txt";
  faq::ShowFaq($tpl_dir, stripslashes($id));
}

// admin edit
if(defined("ACP") && $_REQUEST['action'] != 'delete') {
  $tpl_dir   = BASE_DIR . "/modules/faq/templates/";
  $lang_file = BASE_DIR . "/modules/faq/lang/" . STD_LANG . ".txt";

  $GLOBALS['tmpl']->config_load($lang_file);
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '') {
    switch($_REQUEST['moduleaction']) {
      case '1':
        faq::faqList($tpl_dir);
      break;

      case 'add':
        faq::Addfaq();
      break;

      case 'del':
        faq::Delfaq();
      break;

      case 'savelist':
        faq::SaveList();
      break;

      case 'edit':
        faq::Editfaq($tpl_dir);
      break;

      case 'saveedit':
        faq::Savequest();
      break;
      case 'edit_quest':
        faq::edit_quest($tpl_dir);
      break;
      case 'del_quest':
        faq::del_quest();
      break;
    }
  }
}
?>