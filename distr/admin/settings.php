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

  include_once(SOURCE_DIR . "/class/class.dbdump.php");
  include_once(SOURCE_DIR . "/class/class.settings.php");
  $settings =& new Settings;

  $GLOBALS['tmpl']->config_load(SOURCE_DIR . "/admin/lang/" . $_SESSION['cp_admin_lang'] . "/settings.txt", 'settings');
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  include_once(SOURCE_DIR . "/admin/inc/pre.inc.php");

  function getDump($string) {
    header('Content-Type:text/plain');
    header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Content-Disposition: attachment; filename="backup.sql"');
    header('Content-Length: ' . strlen($string));
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    echo $string;
    exit;
  }

  switch($_REQUEST['action']) {

    case '':
      if(permCheck('gen_settings')) {
        $settings->displaySettings($config_vars);
      }
    break;
  }

}
?>