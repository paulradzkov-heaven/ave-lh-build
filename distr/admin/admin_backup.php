<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

define("ACP",1);
@include_once("init.php");

if(isset($_REQUEST['do']) && $_REQUEST['do']=='logout') {
  reportLog($_SESSION["cp_uname"] . " - закончил сеанс в Панели управления",2,2);
  @session_destroy();
  header("Location:admin.php");
}

$verzname = "lang";
$dh = opendir( $verzname );
$sel = "";
$inactive_lang = array("en","fr");

while ( gettype( $datei = readdir ( $dh )) != @boolean ) {

  if ( is_dir( "$verzname/$datei" ))
  if ($datei != "." && $datei != "..") {
    $sel .= "<option value=\"$datei\"";
    if(STD_LANG == $datei)$sel .= " selected";

    $langname = "";
    switch($datei) {
      case 'ru' : $langname = "Русский"; break;
      case 'de' : $langname = "Deutsch"; break;
      case 'en' : $langname = "English"; break;
      case 'fr' : $langname = "Francaise"; break;
      default : $datei; break;
    }

    $sel .= (in_array($datei, $inactive_lang)) ? " disabled " : "";
    $sel .= ">" . $langname . "</option>";
    $datei = "";

  }
}

$verzname = "templates";
$dht = opendir( $verzname );
$sel_theme = "";

while ( gettype( $theme = readdir ( $dht )) != @boolean ) {

  if ( is_dir( "$verzname/$theme" ))
  if ($theme != "." && $theme != "..") {
    $sel_theme .= "<option value=\"$theme\">" . strtoupper($theme) . "</option>";
    $theme = "";
  }
}

if((!isset($_SESSION['cp_admin_theme']) || !isset($_SESSION['cp_admin_lang'])) && (!isset($_REQUEST['action']) )) {
  $tpl_dir = "templates/".STDTPL_ADMIN;
  $tmpl =& new cp_template($tpl_dir . "/");
  include(BASE_DIR . "/admin/config.load.php");
  $GLOBALS['tmpl']->assign("sel_theme", $sel_theme);
  $GLOBALS['tmpl']->assign("sel", $sel);
  $GLOBALS['tmpl']->assign("tpl_dir", $tpl_dir);
  $GLOBALS['tmpl']->display("login.tpl");
  exit;
}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'login') {

  switch($_REQUEST['action']) {

    case 'login':

    if (!empty($_POST['cp_login']) && !empty($_POST['cp_password'])) {
      sleep(1);
      $spassword = addslashes($_POST['cp_password']);
      $slogin = addslashes($_POST['cp_login']);

      $qLogin   = "SELECT * FROM " . PREFIX . "_users WHERE Email = '$slogin' OR `UserName` = '$slogin' AND Kennwort = '" . md5(md5($spassword)). "'";
      $SqlLogin = $GLOBALS['db']->Query($qLogin);
      $RowLogin = $SqlLogin->fetchrow();

      switch($RowLogin->Status) {

        case 1:
          $_SESSION["cp_benutzerid"] = $RowLogin->Id;
          $_SESSION["cp_kennwort"] = $RowLogin->Kennwort;
          $_SESSION["cp_admin_lang"] = addslashes($_REQUEST['lang']);
          $_SESSION["cp_admin_theme"] = addslashes($_REQUEST['theme']);

         if(isset($_SESSION['redir']) && $_SESSION['redir'] != '') {
            $red = $_SESSION['redir'];
            unset($_SESSION['redir']);
            header("Location:" . $red);
            exit;
          }

          reportLog($RowLogin->UserName . '  - начал сеанс в Панели управления', 2, 2);
          echo '<meta http-equiv="refresh" content="0;URL=index.php">';
        break;

        default :
        @session_destroy();
        reportLog("Ошибка при входе в Панель управления - $_POST[cp_login] / $_POST[cp_password]",2,2);
        header("Location:admin.php?login=false");
        break;
      }

    break;

    } else {
      @session_destroy();
      reportLog("Ошибка при входе в Панель управления - $_POST[cp_login] / $_POST[cp_password]",2,2);

      header("Location:admin.php?login=false");
    }
  }

} else {
  $tpl_dir = "templates/".STDTPL_ADMIN;
  $tmpl =& new cp_template($tpl_dir . "/");
  include(BASE_DIR . "/admin/config.load.php");
  $GLOBALS['tmpl']->assign("sel_theme", $sel_theme);
  $GLOBALS['tmpl']->assign("sel", $sel);
  $GLOBALS['tmpl']->assign("tpl_dir", $tpl_dir);
  $GLOBALS['tmpl']->display("login.tpl");
}
?>