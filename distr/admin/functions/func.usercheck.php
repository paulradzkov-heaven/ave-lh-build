<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function userCheck() {
  if(!defined("LOGGED_IN") || !cp_perm('adminpanel')) {
    header("Location:admin.php");
    exit;
  }
}

function permCheck($perm) {
  if(!cp_perm($perm)) {
    define("NOPERM", 1);
    return false;
  }
  return true;
}
?>