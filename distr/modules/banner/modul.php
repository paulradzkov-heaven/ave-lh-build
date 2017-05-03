<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("BASE_DIR")) exit;

$modul['ModulName'] = "Баннер";
$modul['ModulPfad'] = "banner";
$modul['ModulVersion'] = "1.01";
$modul['Beschreibung'] = "Данный модуль позволяет организовать удобное управление показами рекламных баннеров на вашем сайте. Для того, чтобы отобразить рекламный баннер, разместите системный тег <strong>[cp_banner:XXX]</strong> в нужном месте вашего шаблона сайта или содержимом документа.<br>Допустимые форматы рекламных баннеров: jpg, jpeg, png, gif, swf";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['AdminEdit'] = 1;
$modul['ModulFunktion'] = "cpBanner";
$modul['CpEngineTagTpl'] = "[cp_banner:XXX]";
$modul['CpEngineTag'] = "\\\[cp_banner:([0-9\\\]*)\\]";
$modul['CpPHPTag'] = "<?php cpBanner(''\\\\\\\\1''); ?>";

include_once(BASE_DIR . "/modules/banner/sql.php");

include(BASE_DIR . "/modules/banner/class.banner.php");

function cpBanner($id) {
  $banner =& new ModulBanner;
  $banner->displayBanner(stripslashes($id));
}

if(isset($_REQUEST['module']) && $_REQUEST['module'] != '' && $_REQUEST['module'] == 'banner') {
  if(is_numeric($_REQUEST['Id'])) {
    $banner =& new ModulBanner;
    $banner->fetch_addclick($_REQUEST['Id']);
  }
}

if(defined("ACP") && $_REQUEST['action'] != 'delete') {
  $tpl_dir   = BASE_DIR . "/modules/banner/templates/";
  $lang_file = BASE_DIR . "/modules/banner/lang/" . STD_LANG . ".txt";

  $banner =& new ModulBanner;

  $GLOBALS['tmpl']->config_load($lang_file);
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '') {
    switch($_REQUEST['moduleaction']) {

      case '1':
        $banner->showBanner($tpl_dir);
      break;

      case 'quicksave':
        $banner->quickSave($tpl_dir,$_REQUEST['id']);
      break;

      case 'kategs':
        $banner->bannerKategs($tpl_dir);
      break;

      case 'editbanner':
        $banner->editBanner($tpl_dir,$_REQUEST['id']);
      break;

      case 'new':
      case 'newbanner':
        $banner->newBanner($tpl_dir);
      break;

      case 'delbanner':
        $banner->deleteBanner($_REQUEST['id']);
      break;
    }
  }
}
?>