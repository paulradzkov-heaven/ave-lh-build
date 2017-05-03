<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

define("ACP",1);
include_once("init.php");

userCheck();

$globals =& new Globals;

if(isset($_SESSION['cp_admin_lang']) && $_SESSION['cp_admin_lang'] != '') {
  $lang_local = $_SESSION['cp_admin_lang'];

} else {

  if(isset($_REQUEST['RubrikId']) && $_REQUEST['RubrikId'] != "") {
    $_SESSION['redir'] = "index.php?do=docs&action=edit&RubrikId=". (int)$_REQUEST['RubrikId'] . "&Id=".(int)$_REQUEST['Id']. "&pop=1&feld=".$_REQUEST['feld']. "#".$_REQUEST['feld'];
  } else {
    unset($_SESSION['redir']);
  }

  header("Location:admin.php");
  exit;
}


$GLOBALS['tmpl']->config_load(SOURCE_DIR . "/admin/lang/" . $_SESSION['cp_admin_lang'] . "/main.txt", 'templates');
$config_vars = $GLOBALS['tmpl']->get_config_vars();
$GLOBALS['tmpl']->assign("config_vars", $config_vars);
$GLOBALS['tmpl']->assign("navi", $GLOBALS['tmpl']->fetch("navi/navi.tpl"));

switch($lang_local) {

  case 'de' :
  $s_l = 'ge';
  break;

  default:
  $s_l = $lang_local;
  break;
}

$do = (isset($_REQUEST['do']) && $_REQUEST['do'] != '') ? $_REQUEST['do'] : 'start';

$allowed_files = array('index',
            'start',
            'templates',
            'rubs',
            'user',
            'groups',
            'docs',
            'navigation',
            'logs',
            'queries',
            'modules',
            'settings',
            'dbsettings'
            );


if(in_array($do, $allowed_files)) {
  include_once(addslashes(SOURCE_DIR . "/admin/" . $do . ".php"));
} else {
  include_once(addslashes(SOURCE_DIR . "/admin/start.php"));
}

if(defined("NOPERM"))
$GLOBALS['tmpl']->assign("content", $GLOBALS['config_vars']['MAIN_NO_PERMISSION']);
$tpl_disp = (isset($_REQUEST['pop']) && $_REQUEST['pop']==1) ? ((isset($_REQUEST['css']) && $_REQUEST['css']=="inline" ) ? "iframe.tpl" : "pop.tpl") : "main.tpl";
$GLOBALS['tmpl']->display($tpl_disp);

unset($_SESSION['dbq']);
unset($_SESSION['Doc_Arr']);
unset($_SESSION['InstallModules']);
unset($_SESSION['naviGruppen']);
?>