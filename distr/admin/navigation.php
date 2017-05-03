<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("ACP")) {
  header("Location:index.php");
  exit;

} else {

  define("REPLACEMENT", substr($_SERVER['SCRIPT_NAME'],0,-15));
  include_once(SOURCE_DIR . "/class/class.navigation.php");
  include_once(SOURCE_DIR . "/class/class.user.php");

  $cpNavi =& new Navigation;

  $GLOBALS['tmpl']->assign("navi", $GLOBALS['tmpl']->fetch("navi/navi.tpl"));

  $GLOBALS['tmpl']->config_load(SOURCE_DIR . "/admin/lang/" . $_SESSION['cp_admin_lang'] . "/navigation.txt", 'navi');
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  $_REQUEST['sub']    = (!isset($_REQUEST['sub']))    ? '' : addslashes($_REQUEST['sub']);
  $_REQUEST['action'] = (!isset($_REQUEST['action'])) ? '' : addslashes($_REQUEST['action']);
  $_REQUEST['submit'] = (!isset($_REQUEST['submit'])) ? '' : addslashes($_REQUEST['submit']);

  switch($_REQUEST['action']) {

    case '':
      if(permCheck('navigation')) {
        $cpNavi->showNavis();
      }
    break;

    case 'entries':
    if(permCheck('navigation')) {
        $cpNavi->showEntries($_REQUEST['id']);

      }
    break;

    case 'quicksave':
    if(permCheck('navigation_edit')) {
        $cpNavi->quickSave($_REQUEST['id']);
      }
    break;

    case 'templates':
    if(permCheck('navigation_edit')) {
        $cpNavi->naviTemplate($_REQUEST['id']);
      }
    break;

    case 'new':
    if(permCheck('navigation_new')) {
        $cpNavi->naviTemplateNew();
      }
    break;

    case 'copy':
    if(permCheck('navigation_new')) {
        $cpNavi->copyNaviTemplate($_REQUEST['id']);
      }
    break;

    case 'delete':
    if(permCheck('navigation_edit')) {
        $cpNavi->deleteNavi($_REQUEST['id']);
      }
    break;
  }

}
?>