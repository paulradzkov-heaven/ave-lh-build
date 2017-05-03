<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

define('CP_APPNAME', 'AVE.cms');
define('CP_VERSION', '2.08');
define('CO_INFO', CP_APPNAME . ' ' . CP_VERSION . ' &copy; 2008 <a target="_blank" href="http://www.overdoze.ru/">Overdoze.Ru</a>');

function startsWith($str, $in) {
  return(substr($in, 0, strlen($str)) == $str);
}

function page_init($file) {
  if(!@include('../inc/init.php')) {
    echo 'Ошибка доступа к файлу /inc/'.$file.'.php';
    exit;
  }
}

function checkSeePerm($id) {
//  $sql = $GLOBALS['db']->Query("SELECT Gruppen FROM " . PREFIX . "_navigation WHERE  id = '" . $id . "'");
//  $row = $sql->fetchrow();
//  $sql->Close();

  if (!isset($_SESSION['naviGruppen'][$id])) {
    $sql = $GLOBALS['db']->Query("SELECT Gruppen FROM " . PREFIX . "_navigation WHERE  id = '" . $id . "'");
    $row = $sql->fetchrow();
    $sql->Close();
    $_SESSION['naviGruppen'][$id] = $row->Gruppen;
  }

  if ($_SESSION['naviGruppen'][$id] != '') {
    $Gruppen = explode(',',$_SESSION['naviGruppen'][$id]);
    if(!defined('UGROUP')) define('UGROUP', 2);
    if(!in_array(UGROUP,$Gruppen)) return false;

    return true;
  } else {

    return false;
  }
}

function style_hidden_text(){
 global $hidden;
 if ($hidden['display'] != 'none'){
   if (trim($hidden['text']) != '') {
     $text = "'<div style=\"color:".$hidden[text_color]."; background:".$hidden[bg_color]."; display:".$hidden[display].";border:".$hidden[border_size]." ".$hidden[border_type]." ".$hidden[border_color]."\">".$hidden[text]."</div>'";
   } else {
     $text = "";
   }
 }
 return $text;

}

function hide($data) {
  preg_match_all("/\[hide:([0-9]*)(,([0-9)]*)){0,}\]/", $data, $match);
  $count = sizeof($match[0]);
  $grp = array();

  for($i=0; $i < $count; $i++) {
    array_push($grp, explode(",", str_replace(array('[hide:', ']'), '',$match[0][$i])));
  }

  for($i=0; $i < $count; $i++) {

    if (in_array(UGROUP, $grp[$i])){
      $data = preg_replace("/\[hide(.*?)".UGROUP."((,[^0-9]*.?))*((,[0-9]*))*\](.*?)\[\/hide\]/e", style_hidden_text(), $data);
    } else {
      $params = implode(",",$grp[$i]);
      $data = preg_replace("/\[hide:$params\](.*?)\[\/hide\]/e", "'\\1'", $data);
    }

  }

  return $data;
}

function PrintError() {
  echo 'Запрашиваемая страница не может быть распечатана.';
  exit;
}

function ModuleError() {
  echo 'Запрашиваемый модуль не может быть загружен.';
  exit;
}

function Add_Array($array) {
  reset($array);
  while (list($feld, $wert) = each($array)) {
    if (is_string($wert)) {
      $array[$feld] = addslashes($wert);
    } else {

      if (is_array($wert)) {
        $array[$feld] = Add_Array($wert);
      }
    }
    }

    return $array;
}

function cpQuote($value) {
  if (get_magic_quotes_gpc()) {
     $value = stripslashes($value);
  }

  if (!is_numeric($value)) {
    $value = (function_exists('mysql_real_escape_string')) ? mysql_real_escape_string($value) : mysql_escape_string($value);
  }

  return $value;
}

if (isset($HTTP_POST_VARS)) {
  $_POST     = $HTTP_POST_VARS;
  $_GET      = $HTTP_GET_VARS;
  $_REQUEST  = array_merge($_POST, $_GET);
  $_COOKIE   = $HTTP_COOKIE_VARS;
  $_SESSION  = @$HTTP_SESSION_VARS;
}

if (!get_magic_quotes_gpc()) {
  $_REQUEST  = Add_Array($_REQUEST);
  $_POST     = Add_Array($_POST);
  $_GET      = Add_Array($_GET);
  $_COOKIE   = Add_Array($_COOKIE);
}

function def_id() {
  if(!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id'])) {
    $_REQUEST['id'] = 1;
  }

  $id = (int)$_REQUEST['id'];

  return $id;
}

function numFormat($param) {
  if(is_array($param)) {
    $out = number_format($param['val'] ,2, ',','.');
  }

  return $out;
}

function redir() {
  global $HTTP_GET_VARS, $HTTP_SERVER_VARS, $HTTP_HOST, $htt;
  $uri = $_SERVER['PHP_SELF'];

  $prefl = (defined('SSLMODE') && SSLMODE==1) ? 'https://' : 'http://';

  if (!empty($HTTP_GET_VARS)) {
    $params = array();
    foreach($HTTP_GET_VARS as $paname => $value) {
      $params[] = @urlencode($paname) . '=' . @urlencode($value);
    }
        $uri .= '?' . implode('&amp;', $params);
  }

  $htt = $prefl;
  return($htt . $_SERVER['HTTP_HOST'] . $uri);
}

function homeLink() {
  $hl = (CP_REWRITE == 1) ? 'index.html' : '/index.php';

  return $hl;
}

// !переписал
function cp_print()
{
  $urldetectUrl = $_REQUEST['urldetectUrl'];

  if (strpos($urldetectUrl, '?') || (isset($_REQUEST['module']) && $_REQUEST['module'] != ''))
    {
    $PrintLink = "/index.php?id=".addslashes($_REQUEST['id'])."". htmlspecialchars((isset($_REQUEST['doc']) && $_REQUEST['doc'] != '') ? "&amp;doc=$_REQUEST[doc]" : "") . htmlspecialchars((isset($_REQUEST['apage']) && $_REQUEST['apage'] != "") ? "&amp;apage=$_REQUEST[apage]" : "") . htmlspecialchars((isset($_REQUEST['artpage']) && $_REQUEST['artpage'] != "") ? "&amp;artpage=$_REQUEST[artpage]" : "") . htmlspecialchars((isset($_REQUEST['page']) && $_REQUEST['page'] != "") ? "&page=$_REQUEST[page]" : "") . "&amp;print=1";
    }
  elseif (strpos($urldetectUrl, '.html') || strpos($urldetectUrl, '.php'))
    {
    $PrintLink = $urldetectUrl.'?print=1';
    }
  else
    {
    $PrintLink = $urldetectUrl.'print.html';
    }

return  $PrintLink;
}

function cp_perm($action) {
    global $_SESSION;
    if (isset($_SESSION[$action]) && $_SESSION[$action] == 1) return true;
    if ((isset($_SESSION['alles']) && $_SESSION['alles'] == 1) || (UGROUP == 1) ) return true;
    return false;
}

function displayNotice($var) {
  echo '<div style="background-color:#ffffff; padding:5px; border: 1px solid #000000"><b>Системное сообщение: </b>' . $var . '</div>';
}

function cp_parse_lang($code = 0) {
  echo @cp_parse_string($lang[$code]);
}

function cp_parse_string($string) {
  $string = str_replace("©", "&copy;", $string);
  $string = str_replace("®", "&reg;", $string);
  $string = eregi_replace("<b>", "<strong>", $string);
  $string = eregi_replace("</b>", "</strong>", $string);
  $string = eregi_replace("<i>", "<em>", $string);
  $string = eregi_replace("</i>", "</em>", $string);
  $string = eregi_replace("<br>", "<br />", $string);
  $string = eregi_replace("<br/>", "<br />", $string);
  return $string;
}

function cp_parse_linkname($string_p) {
  $string = strtolower($string_p);
  $string = ereg_replace('([^_A-Za-zА-Яа-яЁё0-9])', '_', $string);
  $string = str_replace(array("_amp_"), "and", $string);
  $string = str_replace(array("\"", "\'", "?", "!"), "", $string);
  $string = str_replace(array("А", "а"), "a", $string);
  $string = str_replace(array("Б", "б"), "b", $string);
  $string = str_replace(array("В", "в"), "v", $string);
  $string = str_replace(array("Г", "г"), "g", $string);
  $string = str_replace(array("Д", "д"), "d", $string);
  $string = str_replace(array("Е", "е", "Ё", "ё", "Э", "э"), "e", $string);
  $string = str_replace(array("Ж", "ж"), "zh", $string);
  $string = str_replace(array("З", "з"), "z", $string);
  $string = str_replace(array("И", "и"), "i", $string);
  $string = str_replace(array("Й", "й"), "i", $string);
  $string = str_replace(array("К", "к"), "k", $string);
  $string = str_replace(array("Л", "л"), "l", $string);
  $string = str_replace(array("М", "м"), "m", $string);
  $string = str_replace(array("Н", "н"), "n", $string);
  $string = str_replace(array("О", "о"), "o", $string);
  $string = str_replace(array("П", "п"), "p", $string);
  $string = str_replace(array("Р", "р"), "r", $string);
  $string = str_replace(array("С", "с"), "s", $string);
  $string = str_replace(array("Т", "т"), "t", $string);
  $string = str_replace(array("У", "у"), "u", $string);
  $string = str_replace(array("Ф", "ф"), "f", $string);
  $string = str_replace(array("Х", "х"), "kh", $string);
  $string = str_replace(array("Ц", "ц"), "ts", $string);
  $string = str_replace(array("Ч", "ч"), "ch", $string);
  $string = str_replace(array("Ш", "ш"), "sh", $string);
  $string = str_replace(array("Щ", "щ"), "shch", $string);
  $string = str_replace(array("Ъ", "ъ"), "", $string);
  $string = str_replace(array("Ы", "ы"), "y", $string);
  $string = str_replace(array("Ь", "ь"), "", $string);
  $string = str_replace(array("Ю", "ю"), "iu", $string);
  $string = str_replace(array("Я", "я"), "ia", $string);

// Двойные подчеркивания слепляем в одно
  $string = ereg_replace("_+", "_", $string);
  return $string;
}

function cp_rewrite($string_p,$length='') {
  $string = $string_p;

  $string = preg_replace("/index.php([?])id=([_a-zA-Z0-9]*)&amp;doc=([_a-zA-Z0-9]*)&amp;artpage=([}{_a-zA-Z0-9]*)&amp;print=1/", "\\3-\\2-page-\\4-print.html", $string);
  $string = preg_replace("/index.php([?])id=([_a-zA-Z0-9]*)&amp;doc=([_a-zA-Z0-9]*)&amp;artpage=([}{_a-zA-Z0-9]*)/", "\\3-\\2-page-\\4.html", $string);
  $string = preg_replace("/index.php([?])id=([_a-zA-Z0-9]*)&amp;doc=([_a-zA-Z0-9]*)&amp;apage=([}{_a-zA-Z0-9]*)&amp;print=1/", "\\3-\\2-\\4-print.html", $string);
  $string = preg_replace("/index.php([?])id=([_a-zA-Z0-9]*)&amp;doc=([_a-zA-Z0-9]*)&amp;apage=([}{_a-zA-Z0-9]*)/", "\\3-\\2--\\4.html", $string);
  $string = preg_replace("/index.php([?])id=([0-9]*)&amp;doc=([_a-zA-Z0-9]*)&amp;print=1/", "\\3-\\2-print.html", $string);
  $string = preg_replace("/index.php([?])id=([0-9]*)&amp;doc=([_a-zA-Z0-9]*)/", "\\3-\\2.html", $string);
  $string = preg_replace("/index.php([?])print=1/", "index-print.html", $string);
  $string = preg_replace("/index.php([?])id=([0-9]*)&amp;print=1/", "index-\\2-print.html", $string);
  $string = preg_replace("/index.php([?])id=([0-9]*)/", "index-\\2.html", $string);
  //$string = preg_replace("/index.php([?])module=([_a-zA-Z0-9]*)&amp;action=([_a-zA-Z0-9]*)&amp;sub=([_a-zA-Z0-9]*)/", "modul-\\2-\\3-\\4.html", $string);
  //$string = preg_replace("/index.php([?])module=([_a-zA-Z0-9]*)&amp;action=([_a-zA-Z0-9]*)/", "modul-\\2-\\3.html", $string);
  //$string = preg_replace("/index.php/", "index.htm", $string);
  $string = str_replace(".html&amp;print=1", ",print.html", $string);

  if(!isset($_REQUEST['id'])) {
    $string = str_replace("index.html&amp;print=1", "index-print.html", $string);
  }

  return $string;
}
?>