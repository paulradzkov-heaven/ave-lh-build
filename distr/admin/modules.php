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

  include_once(SOURCE_DIR . "/class/class.modules.php");
  $cpModule =& new Module;
  $GLOBALS['tmpl']->config_load(SOURCE_DIR . "/admin/lang/" . $_SESSION['cp_admin_lang'] . "/modules.txt", 'modules');
  include_once(SOURCE_DIR . "/admin/inc/pre.inc.php");

  if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '') {
    $r_module = 'mod_' . $_REQUEST['mod'];
    if(!cp_perm($r_module)) {
      echo $GLOBALS['config_vars']['MAIN_NO_PERM_MODULES'];
      exit;
    }
  }

  switch($_REQUEST['action']) {

    case '':
      if(permCheck('modules')) {
        $cpModule->showModules();
      }
    break;

    case 'quicksave':
      if(permCheck('modules_admin')) {
        $cpModule->quickSave();
      }
    break;

    case 'delete':
      if(permCheck('modules_admin')) {
        $cpModule->deleteModule($_REQUEST['module'],$_REQUEST['path']);
      }
    break;

    case 'install':
      if(permCheck('modules_admin')) {
        $cpModule->installModule($_REQUEST['module']);
      }
    break;

    case 'reinstall':
      if(permCheck('modules_admin')) {
        $cpModule->installModule($_REQUEST['path'],1,$_REQUEST['path']);
      }
    break;

    case 'update':
      if(permCheck('modules_admin')) {
        $cpModule->updateModule($_REQUEST['path'],1,$_REQUEST['path']);
      }
    break;

    case 'modedit':
      if(permCheck('modules')) {
        include(SOURCE_DIR . "modules/" . $_REQUEST['mod'] . "/modul.php");
      }
    break;

    case 'onoff':
      if(permCheck('modules_admin')) {
        $cpModule->OnOff($_REQUEST['module']);
      }
    break;
  }
}
?>