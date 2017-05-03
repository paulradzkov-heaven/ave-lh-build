<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function isPhpCode($check_code) {
  $check_code = stripslashes($check_code);
  $check_code = str_replace(" ", "", $check_code);
  $check_code = strtolower($check_code);
  
  if(strpos($check_code, "<?php")!==false || 
     strpos($check_code, "<?")!==false || 
     strpos($check_code, "<? ")!==false || 
     strpos($check_code, "<?=")!==false ||
     strpos($check_code, "<script language=\"php\">")!==false || 
     strpos($check_code, "language=\"php\"")!==false || 
     strpos($check_code, "language=\'php\'")!==false || 
     strpos($check_code, "language=php")!==false
    ) 
  {
    return true;
  }
    return false;
}
?>