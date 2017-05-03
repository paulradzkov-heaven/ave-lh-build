<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function limit() {
  $limitx = (isset($_REQUEST['set']) && $_REQUEST['set'] != '') ? (int)$_REQUEST['set'] : 15;
  return $limitx;
}

function prepage($aabfrage = '0') {
  if($aabfrage != 0) {
    $page = (isset($_REQUEST['apage']) && $_REQUEST['apage'] != '') ? (int)$_REQUEST['apage'] : 1;
    if(!defined('AABFRAGE')) define('AABFRAGE',1);
  } else {
    $page = (isset($_REQUEST['page']) && $_REQUEST['page'] != '') ? (int)$_REQUEST['page'] : 1;
  }
  return $page;
}

function fetchSettings($field, $table = '') {
  $sql = $GLOBALS['db']->Query('SELECT '. $field .' FROM ' . PREFIX . '_settings');
  $row = $sql->fetcharray();
  $sql->Close();
  return $row[$field];
}

function pagenav($anzahl_seiten, $tpl_on, $tpl_off, $nav = '') {
  $aabfrage = '';
  if(defined('AABFRAGE')) $aabfrage = 1;

  $aktuelle_seite = prepage($aabfrage);

  $seiten = array (
  $aktuelle_seite - 4,
  $aktuelle_seite - 3,
  $aktuelle_seite - 2,
  $aktuelle_seite - 1,
  $aktuelle_seite,
  $aktuelle_seite + 1,
  $aktuelle_seite + 2,
  $aktuelle_seite + 3,
  $aktuelle_seite + 4
  );

  $seiten = array_unique($seiten);
  if($anzahl_seiten > 1) {
    $nav .= str_replace('{t}', '<big>&laquo;</big>', str_replace('{s}', 1, $tpl_off));
  }

  if($aktuelle_seite > 1) {
    $nav .= str_replace('{t}', '<big>&larr;</big>', str_replace('{s}', ($aktuelle_seite - 1), $tpl_off));
  }

  while(list($key,$val) = each($seiten)) {
    if($val >= 1 && $val <= $anzahl_seiten) {
      if($aktuelle_seite == $val) {
        $nav .= str_replace(array('{s}', '{t}'), $val, '<span class="page_navigation">' .$tpl_on . '</span>');
      } else {
        $nav .= str_replace(array('{s}', '{t}'), $val, $tpl_off);
      }
    }
  }

  if($aktuelle_seite < $anzahl_seiten) {
    $nav .= str_replace('{t}', '<big>&rarr;</big>', str_replace('{s}', ($aktuelle_seite + 1), $tpl_off));
  }

  if($anzahl_seiten > 1){
    $nav .= str_replace('{t}', '<big>&raquo;</big>', str_replace('{s}', $anzahl_seiten, $tpl_off));
  }
  return $nav;
}

function docPage($anzahl_seiten, $tpl_on, $tpl_off) {

  $nav = '';
  $aktuelle_seite = (int)$_REQUEST['artpage'];
  $seiten = array (
  $aktuelle_seite - 4,
  $aktuelle_seite - 3,
  $aktuelle_seite - 2,
  $aktuelle_seite - 1,
  $aktuelle_seite,
  $aktuelle_seite + 1,
  $aktuelle_seite + 2,
  $aktuelle_seite + 3,
  $aktuelle_seite + 4
  );

  $seiten = array_unique($seiten);
  if($aktuelle_seite > 1) {
    $nav .= str_replace("{t}", fetchSettings('SeiteZurueck'), str_replace("{s}", ($aktuelle_seite - 1), $tpl_off));
  }

  while(list($key,$val) = each($seiten)) {
    if($val >= 1 && $val <= $anzahl_seiten) {
      if($aktuelle_seite == $val) {
        $nav .= str_replace(array("{s}", "{t}"), $val, $tpl_on);
      }
    }
  }

  if($aktuelle_seite < $anzahl_seiten) {
    $nav .= str_replace("{t}", fetchSettings('SeiteWeiter'), str_replace("{s}", ($aktuelle_seite + 1), $tpl_off));
  }

  return $nav;
}
?>