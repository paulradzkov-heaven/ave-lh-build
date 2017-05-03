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
  $GLOBALS['tmpl']->assign("navi_groups", $cpUser->listAllGroups());
  $GLOBALS['tmpl']->assign("navi", $GLOBALS['tmpl']->fetch("navi/navi.tpl"));

  $GLOBALS['tmpl']->config_load(SOURCE_DIR . "/admin/lang/" . $_SESSION['cp_admin_lang'] . "/groups.txt", 'groups');

  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  $_REQUEST['sub']    = (!isset($_REQUEST['sub']))    ? '' : addslashes($_REQUEST['sub']);
  $_REQUEST['action'] = (!isset($_REQUEST['action'])) ? '' : addslashes($_REQUEST['action']);
  $_REQUEST['submit'] = (!isset($_REQUEST['submit'])) ? '' : addslashes($_REQUEST['submit']);

  switch($_REQUEST['action']) {

    case '':
      if(cp_perm('group')) {
        $GLOBALS['tmpl']->assign("ugroups", $cpUser->listAllGroups());
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("groups/groups.tpl"));
      } else {
        define("NOPERM", 1);
      }
    break;

    case 'grouprights':
      if(cp_perm('group_edit')) {
        switch($_REQUEST['sub']) {

          case '':
            $get_group = (isset($_REQUEST['Id']) && $_REQUEST['Id'] != '') ? (int)$_REQUEST['Id'] : 1;
            $cpUser->fetchAllPerms($get_group);
            if($get_group == UGROUP) $GLOBALS['tmpl']->assign("own_group",1);
            $GLOBALS['tmpl']->assign("g_name", $cpUser->fetchGroupNameById($get_group));
            $GLOBALS['tmpl']->assign("modules", $cpUser->getModules());
            $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("groups/perms.tpl"));
          break;

          case 'save':
            $cpUser->savePerms($_REQUEST['Id']);
          break;
        }
      } else {
        define("NOPERM", 1);
      }
    break;


    case 'new':
      if(cp_perm('group_new')) {
        $cpUser->newGroup();
      } else {
        define("NOPERM", 1);
      }
    break;


    case 'delete':
      if(cp_perm('group_edit')) {
        $cpUser->delGroup($_REQUEST['Id']);
      } else {
        define("NOPERM", 1);
      }
    break;
  }

}
?>