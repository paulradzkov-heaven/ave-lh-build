<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function fetchPrefabTemplates() {

  $verzname = SOURCE_DIR . "/inc/data/prefabs/templates";
  $dht = opendir( $verzname );
  $sel_theme = "";

  while ( gettype( $theme = readdir ( $dht )) != @boolean ) {

    if ( is_file( "$verzname/$theme" ))
    if ($theme != "." && $theme != "..") {
      $sel_theme .= "<option value=\"$theme\">" . strtoupper(substr($theme,0,-4)) . "</option>";
      $theme = "";
    }
  }
  $GLOBALS['tmpl']->assign("sel_theme", $sel_theme);


  if(isset($_REQUEST['theme_pref']) && $_REQUEST['theme_pref'] != '') {
    ob_start();
    @readfile(SOURCE_DIR . "/inc/data/prefabs/templates/" . $_REQUEST['theme_pref']);
    $prefab = ob_get_contents();
    ob_end_clean();
    $GLOBALS['tmpl']->assign("prefab", $prefab);
  }
}
?>