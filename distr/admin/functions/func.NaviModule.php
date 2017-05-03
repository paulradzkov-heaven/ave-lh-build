<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function NaviModule() {
  $dir = BASE_DIR . "/modules/";
  $modules = array();

  $sql = $GLOBALS['db']->Query("SELECT ModulName,ModulPfad FROM " . PREFIX . "_module WHERE Status = '1' ORDER BY ModulName ASC");
  while($row = $sql->fetchrow()) {
    $modul['AdminEdit'] = 0;
    if(!include($dir . $row->ModulPfad . "/modul.php")) {
      echo "Ошибка доступа к файлам модуля " . $row->ModulPfad . "<br />";
    } elseif (($modul['AdminEdit'] == 1) && cp_perm('mod_' . $row->ModulPfad)) {
      array_push($modules, $row);
    }
  }
  $GLOBALS['tmpl']->assign('modules', $modules);
}
?>