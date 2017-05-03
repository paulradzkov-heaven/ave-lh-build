<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function getTemplateById($id, $tpl = "", $row = "") {

  $sql = $GLOBALS['db']->Query("SELECT TplName FROM " . PREFIX . "_templates WHERE Id = '" .(int)$id. "'");
  $row = $sql->fetchrow();
  if(!empty($row)) $tpl = $row->TplName;
  return $tpl;
}

function getAllTemplates() {

  $sql = $GLOBALS['db']->Query("SELECT Id,TplName FROM " . PREFIX . "_templates");
  $vorlagen_array = array();

  while($row = $sql->fetchrow()) {
    array_push($vorlagen_array, $row);
  }

  return $vorlagen_array;
}
?>