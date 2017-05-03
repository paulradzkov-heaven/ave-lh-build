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

  $cpUser =& new cpUser;

  $cpUser->listUser();

  $GLOBALS['tmpl']->assign("navi", $GLOBALS['tmpl']->fetch("navi/navi.tpl"));

  $GLOBALS['tmpl']->config_load(SOURCE_DIR . "/admin/lang/" . $_SESSION['cp_admin_lang'] . "/user.txt", 'user');
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  $_REQUEST['sub'] = (!isset($_REQUEST['sub']))       ? '' : addslashes($_REQUEST['sub']);
  $_REQUEST['action'] = (!isset($_REQUEST['action'])) ? '' : addslashes($_REQUEST['action']);
  $_REQUEST['submit'] = (!isset($_REQUEST['submit'])) ? '' : addslashes($_REQUEST['submit']);

  switch($_REQUEST['action']) {

    case '':
      if(permCheck('user')) {
        $cpUser->listUser();
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("user/users.tpl"));
      }
    break;

    case 'edit':
      if(permCheck('user_edit')) {
        $cpUser->editUser($_REQUEST['Id']);
      }
    break;

    case 'new':
      if(permCheck('user_new')) {
        $cpUser->newUser();
      }
    break;

    case 'delete':
      if(permCheck('user_loesch')) {
        $cpUser->deleteUser($_REQUEST['Id']);
      }
    break;

    case 'quicksave':
      if(permCheck('user_edit')) {
        $cpUser->quicksaveUser();
      }
    break;
  }

}
?>