<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

$base_dir = dirname(__FILE__);

$x_width = (isset($_REQUEST['x_width']) && is_numeric($_REQUEST['x_width']) && $_REQUEST['x_width']  > 10 && $_REQUEST['x_width'] < 801) ? $_REQUEST['x_width'] : 0;
$y_height = (isset($_REQUEST['y_height']) && is_numeric($_REQUEST['y_height']) && $_REQUEST['y_height']  > 10 && $_REQUEST['y_height'] < 601) ? $_REQUEST['y_height'] : 0;
$shrink = (isset($_REQUEST['shrink']) && is_numeric($_REQUEST['shrink']) && $_REQUEST['shrink'] < 100) ? $_REQUEST['shrink'] : 100;
$file = (isset($_REQUEST['file']) && $_REQUEST['file'] != '') ? $_REQUEST['file'] : '';
$file = htmlspecialchars(stripslashes($file));

$namepos = strrpos($file, "/");
if ($namepos !== false) {
	$filename = substr($file, ++$namepos);
	$base_dir = $base_dir . str_replace($filename,'',$file);
}

if(isset($filename) && file_exists($base_dir . $filename)) {
} else {
	header("Content-Type:image/gif", true);
	readfile(dirname(__FILE__) . '/uploads/images/noimage.gif');
	exit;
}

if(function_exists("getimagesize")) {
	$img_data = @getimagesize($base_dir . $filename);
	$type = $img_data[2];
}

switch($type) {
	case '1' :
		if(function_exists("imagecreatefromgif")) {
			$bild = imagecreatefromgif($base_dir . $filename);
			$header = "image/gif";
			$func_create = "imagegif";
		} else {
			header("Content-Type:image/gif", true);
			readfile($base_dir . $filename);
			exit;
		}
	break;

	case '2' :
		$bild = imagecreatefromjpeg($base_dir . $filename);
		$header = "image/jpeg";
		$func_create = "imagejpeg";
	break;

	case '3' :
		if(function_exists("imagecreatefrompng")) {
			$bild = imagecreatefrompng($base_dir . $filename);
			$header = "image/png";
			$func_create = "imagepng";
		} else {
			header("Content-Type:image/png", true);
			readfile($base_dir . $filename);
			exit;
		}
	break;
}

$alt_breite = imagesx($bild);
$alt_hoehe = imagesy($bild);

if ($shrink != 100) {
	$neu_breite = round($alt_breite*$shrink/100);
	$neu_hoehe = round($alt_hoehe*$shrink/100);
} else {
	if ((0 == $x_width) && (0 == $y_height)) {
		$x_width = 120;
	}
	if (0 == $y_height) {
		$neu_hoehe = round(($alt_hoehe/$alt_breite) * $x_width);
	} else {
		$neu_hoehe = $y_height;
	}
	if (0 == $x_width) {
		$neu_breite = round(($alt_breite/$alt_hoehe) * $y_height);
	} else {
		$neu_breite = $x_width;
	}
}

$thumb_id = "thumb_" . $neu_breite . "x" . $neu_hoehe . "_" . $filename;

if(!file_exists($base_dir . "thumbnail/")) {
	mkdir($base_dir . "thumbnail/");
}

if(file_exists($base_dir . "thumbnail/" . $thumb_id)) {
	header("Content-Type:$header", true);
	readfile($base_dir . "thumbnail/" . $thumb_id);
	exit;
}

$thumb = imagecreatetruecolor($neu_breite, $neu_hoehe);

imagecopyresampled($thumb, $bild, 0, 0, 0, 0, $neu_breite,$neu_hoehe, $alt_breite, $alt_hoehe);

header("HTTP/1.1 200 OK", true);
header("Date: ".gmdate("D, d M Y H:i:s GTM"), true);
header("Content-Type:$header", true);

ob_start();
$func_create($thumb,'', 90);
$a = ob_get_contents();
ob_end_clean();
$fp = fopen($base_dir . "thumbnail/" . $thumb_id, "wb+");
chmod(fp,0644);
fwrite($fp, $a);
fclose($fp);
echo $a;
exit;
?>