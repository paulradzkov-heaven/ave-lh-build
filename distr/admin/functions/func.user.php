<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function getUserById($id, $user = "", $row = "") {

  $sql = $GLOBALS['db']->Query("SELECT `UserName` FROM " . PREFIX . "_users WHERE Id = '" .(int)$id. "'");
  $row = $sql->fetchrow();
  if(!empty($row)) $user = $row->UserName;
  return $user;
}
?>