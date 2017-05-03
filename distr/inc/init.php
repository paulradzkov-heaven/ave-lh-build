<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

@ini_set('arg_separator.input', '&amp;');
@ini_set('arg_separator.output', '&amp;');
@ini_set('session.use_cookies', '0');
@ini_set('url_rewriter.tags', '');

define ('BASE_DIR', substr(dirname(__FILE__),0,-4));
define ('SMARTY_DIR', BASE_DIR . '/class/smarty/');

include_once (SMARTY_DIR . '/Smarty.class.php');
include_once (BASE_DIR . '/class/class.database.php');
include_once (BASE_DIR . '/class/class.globals.php');
include_once (BASE_DIR . '/class/class.phpmailer.php');
include_once (BASE_DIR . '/class/class.cpengine.php');
include_once (BASE_DIR . '/class/class.template.php');
include_once (BASE_DIR . '/functions/func.log.php');
include_once (BASE_DIR . '/functions/func.parsefields.php');
include_once (BASE_DIR . '/functions/func.parserequest.php');
include_once (BASE_DIR . '/functions/func.pages.php');

define('CP_REWRITE', $GLOBALS['config']['mod_rewrite']);
define('STDTPL', $GLOBALS['config']['std_theme']);
define('STDTPL_ADMIN',  $GLOBALS['config']['std_admintheme']);

define('DB_HOST', $GLOBALS['config']['dbhost']);
define('DB_USER', $GLOBALS['config']['dbuser']);
define('DB_PASS', $GLOBALS['config']['dbpass']);
define('DB_NAME', $GLOBALS['config']['dbname']);
define('PREFIX', $GLOBALS['config']['dbpref']);
define('STD_LANG', $GLOBALS['config']['std_lang']);

$db   = new DB(DB_HOST, DB_USER, DB_PASS, DB_NAME);
@mysql_query('SET NAMES cp1251');
$sess_expire = time() - $GLOBALS['config']['session_lt'] + 60;

$db->Query("DELETE FROM " . PREFIX . "_sessions WHERE expiry < $sess_expire");
unset($Rechte_array, $Rechte, $HTTP_SESSION_VARS);

$Rechte_array = '';
$Rechte       = '';

if(isset($_SESSION['cp_benutzerid']) && isset($_SESSION['cp_kennwort']) ) {
  $cookie_uid   = addslashes($_SESSION['cp_benutzerid']);
  $cookie_upass = addslashes($_SESSION['cp_kennwort']);

  $qCheck   = $GLOBALS['db']->Query("SELECT `UserName`, Land, Benutzergruppe, Id, Kennwort, Status, Vorname, Email, Nachname FROM " . PREFIX . "_users WHERE Status='1' AND Id = '$cookie_uid' AND Kennwort = '$cookie_upass'");

  $RowCheck = $qCheck->fetchrow();

  if(!is_object($RowCheck)) {
    unset($_SESSION['cp_benutzerid']);
    unset($_SESSION['cp_kennwort']);
  }

  switch(@$RowCheck->Status) {
    case '' :
    case '0' :

    break;

    case 1 :
      $sql_benutzer = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_users LEFT JOIN " . PREFIX . "_user_groups USING (Benutzergruppe) WHERE (Id='" . $cookie_uid . "' AND Kennwort='" . $cookie_upass . "') LIMIT 1");
      $row_benutzer = $sql_benutzer->fetchrow();

      $q_rechte = $GLOBALS['db']->Query("SELECT Rechte FROM " . PREFIX . "_user_permissions WHERE Benutzergruppe = '" . $row_benutzer->Benutzergruppe . "'");
      $row_rechte = $q_rechte->fetchrow();

      $row_rechte->Rechte = str_replace(array(' ', '\n', '\r\n'),'',$row_rechte->Rechte);
      $Rechte_array = explode('|', $row_rechte->Rechte);

      foreach ($Rechte_array as $Rechte) {
        $_SESSION[$Rechte] = 1;
      }

      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_users SET ZuletztGesehen='" . time() . "' WHERE Id='" . $cookie_uid . "'");

      define('LOGGED_IN', 1);
      define('UGROUP', $row_benutzer->Benutzergruppe);
      define('UNAME', htmlspecialchars($RowCheck->Vorname));
      define('UID', $RowCheck->Id);

      $_SESSION['cp_ugroup'] = $RowCheck->Benutzergruppe;
      $_SESSION['cp_benutzerid'] = $RowCheck->Id;
      $_SESSION['cp_kennwort'] = $RowCheck->Kennwort;
      $_SESSION['cp_email'] = $RowCheck->Email;
      $_SESSION['cp_loggedin'] = 1;
      $_SESSION['cp_uname'] = htmlspecialchars($RowCheck->UserName);
      $_SESSION['cp_ucountry'] = strtoupper($RowCheck->Land);

    break;

    case 2 :

    break;
  }

} else {
  $_SESSION['cp_ugroup'] = 2;
  $q_rechte = $GLOBALS['db']->Query("SELECT Rechte FROM " . PREFIX . "_user_permissions WHERE Benutzergruppe = '2'");
  $row_rechte = $q_rechte->fetchrow();

  define('UGROUP', 2);
  define('UID', 0);

  $Rechte_array = @explode('|', $row_rechte->Rechte);
  foreach ($Rechte_array as $Rechte) $_SESSION[$Rechte] = 1;
}


$globals = new Globals;
$defLand = $GLOBALS['globals']->cp_settings('DefLand');
define('DEFLAND', strtolower(chop($defLand)));

if(DEFLAND == 'de') {
  @setlocale (LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
}

if(DEFLAND == 'ru') {
  @setlocale (LC_ALL, 'ru_RU.CP1251', 'ru');
} else {
  @setlocale (LC_ALL, '', strtolower(DEFLAND) . '_' . DEFLAND, strtolower(DEFLAND));
}

if(isset($_GET['action']) && $_GET['action'] == 'logout') {
  @$_SESSION['cp_benutzerid'] = '';
  @$_SESSION['cp_kennwort'] = '';
  @setcookie('cp_login', '', time() - 3600);
  @setcookie('cp_password', '', time() - 3600);
  @setcookie('cp_staylogged', '', time() - 3600);
}

if(!isset($_SESSION['cp_benutzerid']) && !defined('ANTISPAM')) {
  if(isset($_COOKIE['cp_login']) && isset($_COOKIE['cp_password']) && isset($_COOKIE['cp_staylogged']) && $_COOKIE['cp_staylogged']==1) {
    $spassword = addslashes($_COOKIE['cp_password']);
    $slogin = addslashes(base64_decode($_COOKIE['cp_login']));

    $qLogin   = $GLOBALS['db']->Query("SELECT Id,Kennwort,Status FROM " . PREFIX . "_users WHERE (Email = '$slogin' OR `UserName` = '$slogin') AND Kennwort = '" . $spassword . "'");
    $RowLogin = $qLogin->fetchrow();
    if(is_object($RowLogin) && $RowLogin->Status==1) {
      @$_SESSION['cp_benutzerid'] = $RowLogin->Id;
      @$_SESSION['cp_kennwort'] = $RowLogin->Kennwort;
      @setcookie('cp_login', $_COOKIE['cp_login'], time() + (3600 * 31 * 6));
      @setcookie('cp_password', $spassword, time() + (3600 * 31 * 6));
      @setcookie('cp_staylogged', '1', time() + (3600 * 31 * 6));
      @header('Location:' . $_SERVER['REQUEST_URI']);
      exit;
    }
  }
}
?>