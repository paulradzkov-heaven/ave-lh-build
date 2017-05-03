<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function limit() {
  $limitx = (isset($_REQUEST['set']) && $_REQUEST['set'] != "") ? (int)$_REQUEST['set'] : 15;
  return $limitx;
}

function prepage() {
  $page = (isset($_REQUEST['page']) && $_REQUEST['page'] != "") ? (int)$_REQUEST['page'] : 1;
  return $page;
}

function pagenav($anzahl_seiten, $tpl_on, $tpl_off, $nav='') {

  $aktuelle_seite = prepage();
  $seiten = array (
  $aktuelle_seite-4,
  $aktuelle_seite-3,
  $aktuelle_seite-2,
  $aktuelle_seite-1,
  $aktuelle_seite,
  $aktuelle_seite+1,
  $aktuelle_seite+2,
  $aktuelle_seite+3,
  $aktuelle_seite+4
  );

  $seiten = array_unique($seiten);

  if($anzahl_seiten > 1) {
    $nav .= str_replace("{t}", "&lt;&lt;", str_replace("{s}", 1, $tpl_off));
  }

  if($aktuelle_seite > 1) {
    $nav .= str_replace("{t}", "&lt;", str_replace("{s}", ($aktuelle_seite-1), $tpl_off));
  }

  while(list($key,$val) = each($seiten)) {
    if($val >= 1 && $val <= $anzahl_seiten) {
      if($aktuelle_seite == $val) {
        $nav .= str_replace(array("{s}", "{t}"), $val, $tpl_on);
      } else {
        $nav .= str_replace(array("{s}", "{t}"), $val, $tpl_off);
      }
    }
  }

  if($aktuelle_seite < $anzahl_seiten) {
    $nav .= str_replace("{t}", "&gt;", str_replace("{s}", ($aktuelle_seite+1), $tpl_off));
  }

  if($anzahl_seiten > 1){
    $nav .= str_replace("{t}", "&gt;&gt;", str_replace("{s}", $anzahl_seiten, $tpl_off));
  }
  return $nav;
}

function artpage($anzahl_seiten,$tpl_on, $tpl_off) {

  $aktuelle_seite = $_REQUEST['artpage'];
  $seiten = array($aktuelle_seite-3,
  $aktuelle_seite-2,
  $aktuelle_seite-1,
  $aktuelle_seite,
  $aktuelle_seite+1,
  $aktuelle_seite+2,
  $aktuelle_seite+3);
  $seiten = array_unique($seiten);
  if($anzahl_seiten > 1){
    $nav .= str_replace("{t}", $lang['pagestart'], str_replace("{s}", 1, $tpl_off));
  }

  if($aktuelle_seite > 1) {
    $nav .= str_replace("{t}", $lang['pageprev'], str_replace("{s}", ($aktuelle_seite-1), $tpl_off));
  }

  while(list($key,$val) = each($seiten)) {
    if($val >= 1 && $val <= $anzahl_seiten) {
      if($aktuelle_seite == $val) {
        $nav .= str_replace(array("{s}", "{t}"), $val, $tpl_on);
      } else {
        $nav .= str_replace(array("{s}", "{t}"), $val, $tpl_off);
      }
    }
  }

  if($aktuelle_seite < $anzahl_seiten) {
    $nav .= str_replace("{t}", $lang['pagenext'], str_replace("{s}", ($aktuelle_seite+1), $tpl_off));
  }

  if($anzahl_seiten > 1){
    $nav .= str_replace("{t}", $lang['pageend'], str_replace("{s}", $anzahl_seiten, $tpl_off));
  }
  return $nav;
}
?>