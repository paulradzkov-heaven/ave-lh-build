<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

$source_dir = dirname(__FILE__) . '/uploads/'.$_REQUEST['folder'];

$x_width = (isset($_REQUEST['x_width']) && is_numeric($_REQUEST['x_width']) && $_REQUEST['x_width']  > 10 && $_REQUEST['x_width'] < 500) ? $_REQUEST['x_width'] : 120;
$shrink = (isset($_REQUEST['shrink']) && is_numeric($_REQUEST['shrink']) && $_REQUEST['shrink'] < 100) ? $_REQUEST['shrink'] : 100;
$type = (isset($_REQUEST['type']) && $_REQUEST['type'] != '') ? $_REQUEST['type'] : 'jpg';
$file = (isset($_REQUEST['file']) && $_REQUEST['file'] != '') ? $_REQUEST['file'] : '';
$file = str_replace(array('..','\'',':','http','ftp','/'),'',$file);
$file = rawurldecode($file);
switch($type)
{
  // JPG
  case 'jpg' :
    $bild = imagecreatefromjpeg($source_dir . $file);
    $header = "image/jpeg";
    $func_create = "imagejpeg";
  break;

  // PNG
  case 'png' :
    if(function_exists("imagecreatefrompng"))
    {
      $bild = imagecreatefrompng($source_dir . $file);
      $header = "image/png";
      $func_create = "imagepng";
    } else {
      header("Content-Type:image/png", true);
      readfile($source_dir . $file);
      exit;
    }
  break;

  // GIF
  case 'gif' :
    if(function_exists("imagecreatefromgif"))
    {
      $bild = imagecreatefromgif($source_dir . $file);
      $header = "image/gif";
      $func_create = "imagegif";
    } else {
      header("Content-Type:image/gif", true);
      readfile($source_dir . $file);
      exit;
    }
  break;

  // VIDEO
  case 'video':
    header("Content-Type:image/gif", true);
    readfile($source_dir . 'dummy_video.gif');
    exit;
  break;
}

$alt_breite = imagesx($bild);
$alt_hoehe = imagesy($bild);
$neu_breite = $x_width;
$neu_hoehe = $x_width;
$thumb = imagecreatetruecolor($neu_breite, $neu_hoehe);

$thumb_id = 'thumb__' . $file;

//===============================================================
// Если не указано обязательное формирование миниатюр (&compile=1)
// и есть миниатюра сформированная ранее - выводим её
//===============================================================
if(file_exists($source_dir . $thumb_id) && $_REQUEST['compile'] != 1)
{
  header("Content-Type:$header", true);
  readfile($source_dir . $thumb_id);
  exit;
}

//===============================================================
// Формирование квадратной миниатюры
//===============================================================
// вырезаем квадратную серединку, если изображение горизонтальное
if ($alt_breite>$alt_hoehe) {
  imagecopyresampled($thumb, $bild, 0, 0, round((max($alt_breite,$alt_hoehe)-min($alt_breite,$alt_hoehe))/2), 0, $neu_breite, $neu_hoehe, min($alt_breite,$alt_hoehe), min($alt_breite,$alt_hoehe));
}

// вырезаем квадратную верхушку, если изображение вертикальное
if ($alt_breite<$alt_hoehe) {
  imagecopyresampled($thumb, $bild, 0, 0, 0, 0, $neu_breite, $neu_hoehe, min($alt_breite,$alt_hoehe), min($alt_breite,$alt_hoehe));
}

// квадратное изображение масштабируется без вырезок
if ($alt_breite==$alt_hoehe) {
  imagecopyresampled($thumb, $bild, 0, 0, 0, 0, $neu_breite, $neu_hoehe, $alt_breite, $alt_hoehe);
}

header("HTTP/1.1 200 OK", true);
header("Date: ".gmdate("D, d M Y H:i:s GTM"), true);
header("Content-Type:$header", true);

ob_start();
$func_create($thumb,'', 90);
$a = ob_get_contents();
ob_end_clean();
$fp = fopen($source_dir . $thumb_id, "wb+");
fwrite($fp, $a);
fclose($fp);
echo $a;
exit;
?>