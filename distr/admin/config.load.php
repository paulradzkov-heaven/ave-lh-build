<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(defined("ACP")) {
  if(isset($_SESSION['cp_admin_lang'])) {
    $ldir = addslashes($_SESSION['cp_admin_lang']);
    $GLOBALS['tmpl']->config_load(SOURCE_DIR . "/admin/lang/" . $ldir  . "/main.txt");
    $lang_vars = $GLOBALS['tmpl']->get_config_vars();
    $GLOBALS['tmpl']->assign("lang_vars", $lang_vars);

  } else  {

    $ldir = "ru";
    if(!defined("A_LANG")) define("A_LANG", $ldir);
    $GLOBALS['tmpl']->config_load(SOURCE_DIR . "/admin/lang/" . $ldir  . "/main.txt");
    $lang_vars = $GLOBALS['tmpl']->get_config_vars();
    $GLOBALS['tmpl']->assign("lang_vars", $lang_vars);
  }
    $GLOBALS['tmpl']->assign("cpversion", CP_VERSION);
    $GLOBALS['tmpl']->assign("cfooter", CP_APPNAME . ' ' . CP_VERSION . ' &copy; 2008 <a target="_blank" href="http://www.overdoze.ru/">Overdoze.Ru</a>');
} else {
  echo "Извините, но Вы не имеете права доступа к данному разделу!";
}
?>