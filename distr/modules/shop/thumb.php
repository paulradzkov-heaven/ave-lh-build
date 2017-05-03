<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

$base_dir = dirname(__FILE__) . '/uploads/';
$source_dir = dirname(__FILE__) . '/thumbnails/';

$x_width = (isset($_REQUEST['x_width']) && is_numeric($_REQUEST['x_width']) && $_REQUEST['x_width']  > 10 && $_REQUEST['x_width'] < 500) ? $_REQUEST['x_width'] : 80;
$shrink = (isset($_REQUEST['shrink']) && is_numeric($_REQUEST['shrink']) && $_REQUEST['shrink'] < 100) ? $_REQUEST['shrink'] : 100;
$type = (isset($_REQUEST['type']) && $_REQUEST['type'] != '') ? $_REQUEST['type'] : 'jpg';
$file = (isset($_REQUEST['file']) && $_REQUEST['file'] != '') ? $_REQUEST['file'] : '';
$file = str_replace(array('..','\'',':','http','ftp','/'),'',$file);

if(!file_exists($base_dir . $file))
{
	header("Content-Type:image/gif", true);
	readfile($base_dir . 'nicht_loeschen.gif');
	exit;
}

switch($type)
{
	// JPG - BILDER
	case 'jpg' :
		$bild = imagecreatefromjpeg($base_dir . $file);
		$header = "image/jpeg";
		$func_create = "imagejpeg";
	break;

	// PNG - BILDER
	case 'png' :
		if(function_exists("imagecreatefrompng"))
		{
			$bild = imagecreatefrompng($base_dir . $file);
			$header = "image/png";
			$func_create = "imagepng";
		} else {
			header("Content-Type:image/png", true);
			readfile($source_dir . $file);
			exit;
		}
	break;

	// GIF - BILDER
	case 'gif' :
		if(function_exists("imagecreatefromgif"))
		{
			$bild = imagecreatefromgif($base_dir . $file);
			$header = "image/gif";
			$func_create = "imagegif";
		} else {
			header("Content-Type:image/gif", true);
			readfile($source_dir . $file);
			exit;
		}
	break;
}

$alt_breite = imagesx($bild);
$alt_hoehe = imagesy($bild);
$neu_breite = $x_width;
$neu_hoehe = round(($alt_hoehe/$alt_breite) * $neu_breite);
$thumb = imagecreatetruecolor($neu_breite, $neu_hoehe);

$small = (isset($_REQUEST['x_width']) && $_REQUEST['x_width'] != '') ? $_REQUEST['x_width'] . "__" : '';
$thumb_id = "shopthumb__$small" . $file;

//===============================================================
// Wenn temporäre Datei existiert bzw. schon generiert wurde,
// wird diese einfach ausgegeben, ohne dynamisch erstellt zuwerden
//===============================================================
if(file_exists($source_dir . $thumb_id) && $_REQUEST['compile'] != 1)
{
	header("Content-Type:$header", true);
	readfile($source_dir . $thumb_id);
	exit;
} else {
	//
}

//===============================================================
// Thumbnail generieren
//===============================================================
imagecopyresampled($thumb, $bild, 0, 0, 0, 0, $neu_breite,$neu_hoehe, $alt_breite, $alt_hoehe);
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
chmod($source_dir . $thumb_id, 0777);
echo $a;
exit;
?>