<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function fetchTplTags($srcfile) {

  if(@include_once($srcfile)) {
    reset ($vorlage);
    $vl = array();

    while (list($key, $value) = each ($vorlage)) {
       $tag->cp_tag = $key;
       $tag->cp_desc = $value;
       array_push($vl,$tag);
       unset($tag);
    }
    return $vl;
  } else {
    return "";
  }
}
?>