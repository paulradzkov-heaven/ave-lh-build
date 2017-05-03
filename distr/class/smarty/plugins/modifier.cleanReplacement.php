<?php

function smarty_modifier_cleanReplacement($string)
{
$string = str_replace('[cp:replacement]', '/', $string);
return $string;
}
?>
