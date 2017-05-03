<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(defined("ACP")) {
  define("SOURCE_DIR",substr(dirname(__FILE__),0,-5));

  ob_start();
  session_start();
  session_name("cp");
  define ("SESSION", session_id());
  include_once(SOURCE_DIR . "functions/func.pref.php");
  include_once(SOURCE_DIR . "inc/db.config.php");
  include_once(SOURCE_DIR . "inc/config.php");
  include_once(SOURCE_DIR . "functions/func.session.php");
  include_once(SOURCE_DIR . "class/class.user.php");
  include_once(SOURCE_DIR . "admin/functions/func.astat.php");
  include_once(SOURCE_DIR . "admin/functions/func.cpreadfile.php");
  include_once(SOURCE_DIR . "admin/functions/func.usercheck.php");
  include_once(SOURCE_DIR . "admin/functions/func.prettycode.php");
  include_once(SOURCE_DIR . "admin/functions/func.checkIfPhpCode.php");
  include_once(SOURCE_DIR . "admin/functions/func.fetchTplTags.php");
  include_once(SOURCE_DIR . "admin/functions/func.mime.php");
  include_once(SOURCE_DIR . "admin/functions/func.user.php");
  include_once(SOURCE_DIR . "admin/functions/func.template.php");
  include_once(SOURCE_DIR . "admin/functions/func.fields.php");
  include_once(SOURCE_DIR . "admin/functions/func.NaviModule.php");
  include_once(SOURCE_DIR . "admin/editor/fckeditor.php");
  include_once(SOURCE_DIR . "inc/init.php");

  $tpl_dir = "templates/" . @$_SESSION['cp_admin_theme'];
  $tmpl    =& new cp_template($tpl_dir . "/");
  include_once(SOURCE_DIR . "/admin/config.load.php");
  $GLOBALS['tmpl']->assign("tpl_dir", $tpl_dir);
} else {
  echo "Извините, но Вы не имеете права доступа к данному разделу!";
}
?>