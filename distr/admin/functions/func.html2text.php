<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function html_to_text($dokument) {

  $suche = array ('@<script[^>]*?>.*?</script>@si',
    '@<[\/\!]*?[^<>]*?>@si',
    '@([\r\n])[\s]+@',
    '@&(quot|#34);@i',
    '@&(amp|#38);@i',
    '@&(lt|#60);@i',
    '@&(gt|#62);@i',
    '@&(nbsp|#160);@i',
    '@&(iexcl|#161);@i',
    '@&(cent|#162);@i',
    '@&(pound|#163);@i',
    '@&(copy|#169);@i',
    '@&#(\d+);@e');

  $ersetze = array ('',
    '',
    '\1',
    '"',
    '&',
    '<',
    '>',
    ' ',
    chr(161),
    chr(162),
    chr(163),
    chr(169),
    'chr(\1)');

  $text = preg_replace($suche, $ersetze, $dokument);
  return $text;
}
?>