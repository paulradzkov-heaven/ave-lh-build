<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

$base = substr(dirname(__FILE__),0,-4);
define('ANTISPAM',1);

include_once ($base . '/inc/db.config.php');
include_once ($base . '/inc/config.php');
include_once ($base . '/functions/func.pref.php');
include_once ($base . '/functions/func.session.php');
include_once ($base . '/inc/init.php');

$si = (int)$_REQUEST['cp_secureimage'];
if(!is_numeric($si)) exit;

$sql      = $GLOBALS['db']->Query("SELECT Code FROM " . PREFIX . "_antispam WHERE Id='" . $si . "'");
$row      = $sql->fetchrow();
$text     = $row->Code;
$font     = BASE_DIR . '/inc/fonts/ft16.ttf';
$raster   = 0;
$size     = 26;
$text_top = 38;

$rect_width  = 120;
$rect_height = 40;

$bild       = imagecreate($rect_width + 1,$rect_height + 1);
$back       = imagecolorallocate($bild, 255, 255, 255);
$gelb       = imagecolorallocate($bild, 238, 192, 10);
$schwarz    = imagecolorallocate($bild, 0, 0, 0);
$grau       = imagecolorallocate($bild, 204, 204, 204);
$dunkelgrau = imagecolorallocate($bild, 119, 119, 119);

header('Content-type: image/jpeg');

if($raster == 1) {
  $count_vert = 20;
  $count_hori = 6;

  for($i=0; $i < $count_vert; $i++)  imageline($bild, $i*10, 0, $i*10, $rect_height, $grau);
  for($i=0; $i < $count_hori; $i++)  imageline($bild, 0, $i*10, $rect_width, $i*10, $grau);
} else  {
  for($a=0; $a <12; $a++) {
    for($i=0; $i < 40; $i++) {
      imagesetpixel($bild, $i*10, $a*10, $grau);
    }
  }
}

imagettftext($bild, $size, 5, 18, $text_top, $dunkelgrau, $font, $text);
imagerectangle($bild, 0, 0, $rect_width, $rect_height, $dunkelgrau);
imagejpeg($bild);
imagedestroy($bild);
?>