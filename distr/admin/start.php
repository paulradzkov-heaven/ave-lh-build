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
  cntStat();
  NaviModule();

  $GLOBALS['tmpl']->config_load(SOURCE_DIR . "/admin/lang/" . $_SESSION['cp_admin_lang'] . "/main.txt", 'index');
  $GLOBALS['tmpl']->assign("navi", $GLOBALS['tmpl']->fetch("navi/navi.tpl"));
  $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("start.tpl"));
}
?>