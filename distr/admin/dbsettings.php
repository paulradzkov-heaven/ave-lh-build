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

  if(!cp_perm('gen_settings')) {
    define("NOPERM", 1);
  }

  $tempdir = BASE_DIR . "/attachments/";
  define("DUMPDIR", $tempdir);
  include_once(SOURCE_DIR . "/class/class.dbdump.php");
  $kdump =& new sqlDump;

if (@$_REQUEST['action']=="dboption") {

  if(isset($_REQUEST['whattodo']) && $_REQUEST['whattodo'] == 'dump') {
    $dump = $kdump->writeDump();
    $kdump = $kdump->getDump($dump);
    exit;
  }

  $kdump->optimizeRep();
  $tabellen = $kdump->showTables();
} else {
  $tabellen = $kdump->showTables();
}

if(isset($_REQUEST['restore']) && $_REQUEST['restore']==1) {
  $kdump->dbRestore(DUMPDIR);
}
  $GLOBALS['tmpl']->assign('tabellen', $tabellen);
  $GLOBALS['tmpl']->config_load(SOURCE_DIR . "/admin/lang/" . $_SESSION['cp_admin_lang'] . "/dbactions.txt", 'db');
  $GLOBALS['tmpl']->assign("navi", $GLOBALS['tmpl']->fetch("navi/navi.tpl"));
  $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("dbactions/actions.tpl"));
}
?>