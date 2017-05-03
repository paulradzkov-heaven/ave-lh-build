<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function prettyCode($code) {
  $code = eregi_replace("<b>", "<strong>", $code);
  $code = eregi_replace("</b>", "</strong>", $code);
  $code = eregi_replace("<i>", "<em>", $code);
  $code = eregi_replace("</i>", "</em>", $code);
  $code = eregi_replace("<br>", "<br />", $code);
  $code = eregi_replace("<br/>", "<br />", $code);
  return $code;
}
?>