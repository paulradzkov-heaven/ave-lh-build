<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

define('REPLACE_MENT', substr($_SERVER['SCRIPT_NAME'],0,-9));

if (filesize('inc/db.config.php') < 0) header("Location:install.php");

session_start();
session_name('cp');
ob_start();

$_SESSION['dbq'] = isset($_SESSION['dbq']) ? $_SESSION['dbq'] : 0;

include_once('inc/db.config.php');
include_once('inc/config.php');
include_once('functions/func.pref.php');
@include_once('functions/func.session.php');
include_once('inc/init.php');

// Если включен модреврайт для документов, то ссылки на документы обрабатываем по нему
if ($GLOBALS['config']['doc_rewrite']) include_once('functions/func.urldetect.php');

function getmicrotime() {list($usec,$sec)=explode(' ',microtime());return((float)$usec+(float)$sec);}

$Anfangszeit = getmicrotime();

$cp_engine = new CPENGINE;

$tmpl = new cp_template('/templates/'.STDTPL.'/');

if(!defined('T_PATH')) $GLOBALS['tmpl']->assign('img_path', '/templates/'.STDTPL.'/images/');
if(!isset($_REQUEST['sub'])) $_REQUEST['sub'] = '';

$cp_engine->displaySite(def_id());

if(isset($_REQUEST['output']) && $_REQUEST['output']=='false') {
} else {
  if($_REQUEST['id'] == 2) header("HTTP/1.0 404 Not Found", true);
  $tmod = getmicrotime();
  header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $tmod) . ' GMT');
  $CONTENT = ob_get_contents();
  ob_end_clean();
  eval ('?>' . $CONTENT . '<?');
}

$Endzeit = getmicrotime();
//echo '<!-- Время генерации: ' . number_format($Endzeit-$Anfangszeit, 3, ',', '.') . " сек. / Количество запросов к БД: " . $_SESSION['dbq'] . " шт. -->";
unset($_SESSION['dbq']);
unset($_SESSION['Doc_Arr']);
unset($_SESSION['InstallModules']);
unset($_SESSION['naviGruppen']);

?>