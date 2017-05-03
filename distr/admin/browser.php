<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

define("SOURCE_DIR",substr(dirname(__FILE__),0,-5));

define("ACP", 1);
ob_start();
session_start();
session_name("cp");
define("SESSION", session_id());

include_once(SOURCE_DIR . "functions/func.pref.php");
include_once(SOURCE_DIR . "inc/db.config.php");
include_once(SOURCE_DIR . "inc/config.php");
include_once(SOURCE_DIR . "functions/func.session.php");

define("UPDIR", SOURCE_DIR . "uploads");

if(!isset($_SESSION["cp_loggedin"])) {
  header("Location:index.php");
  exit;
}

include_once(SOURCE_DIR . "inc/init.php");
$tpl_dir = "templates/" . @$_SESSION['cp_admin_theme'];
$tmpl    =& new cp_template($tpl_dir . "/browser");
include_once(SOURCE_DIR . "/admin/config.load.php");
$GLOBALS['tmpl']->assign("tpl_dir", $tpl_dir);

define("ADMINTHEME", $_SESSION["cp_admin_theme"]);

$sessid    = SESSION;
$mediapath = "";
$max_size  = 128; // ������������ ������ ���������
$th_pref   = "th_" . $max_size . "_"; // ������� ��������

if(isset($_REQUEST['thumb']) && $_REQUEST['thumb']==1) {
  $img_path = str_replace(array('../','..','\'','//','./'),'',$_REQUEST['bild']);
  $namepos = strrpos($img_path, '/');
  if ($namepos > 0) {
  	$img_name = substr($img_path, ++$namepos);
  	$img_dir  = substr($img_path, 0, $namepos);
  	if (substr($img_path, 0, 1) != '/') {
  	  $img_dir = '/' . $img_dir;
  	}
  } else {
  	$img_name = substr($img_path, 1);
  	$img_dir  = '/';
  }

  $thumb = imagecreatetruecolor($max_size, $max_size);

  $img_data = getimagesize(UPDIR . $img_path);
  switch($img_data[2]) {
  	case '1' :
  		if(function_exists("imagecreatefromgif")) {
  			$img_src = imagecreatefromgif(UPDIR . $img_path);
  			$header  = "image/gif";
        imagecolortransparent($thumb, imagecolorallocate($thumb, 0, 0, 0));
  		} else {
  			exit;
  		}
  	break;

  	case '2' :
  		if(function_exists("imagecreatefromjpeg")) {
    		$img_src = imagecreatefromjpeg(UPDIR . $img_path);
    		$header  = "image/jpeg";
//        imagefill($thumb, 0, 0, imagecolorallocate($thumb, 239, 243, 235));
        imagefill($thumb, 0, 0, imagecolorallocate($thumb, 255, 255, 255));
  		} else {
  			exit;
  		}
  	break;

  	case '3' :
  		if(function_exists("imagecreatefrompng")) {
  			$img_src = imagecreatefrompng(UPDIR . $img_path);
  			$header  = "image/png";
        imagecolortransparent($thumb, imagecolorallocate($thumb, 0, 0, 0));
  		} else {
  			exit;
  		}
  	break;
  }

  $thumb_id = $img_dir . $th_pref . $img_name;
  if(file_exists(UPDIR . $thumb_id)) {
  	header("Content-Type:" . $header, true);
  	readfile(UPDIR . $thumb_id);
  	exit;
  }

  if ($max_size > max($img_data[0], $img_data[1])) {
    $new_width  = $img_data[0];
    $new_height = $img_data[1];

  } elseif ($img_data[0] == $img_data[1]) {
    $new_width  = $max_size;
    $new_height = $max_size;

  } elseif ($img_data[0] > $img_data[1]) {
  	$new_width  = $max_size;
  	$new_height = round(($img_data[1]/$img_data[0]) * $max_size);

  } else {
  	$new_width  = round(($img_data[0]/$img_data[1]) * $max_size);
  	$new_height = $max_size;
  }

  imagecopyresampled($thumb, $img_src, round(($max_size-$new_width)/2), round(($max_size-$new_height)/2), 0, 0, $new_width, $new_height, $img_data[0], $img_data[1]);

  header("HTTP/1.1 200 OK", true);
  header("Date: " . gmdate("D, d M Y H:i:s GTM"), true);
  header("Content-Type:" . $header, true);

  ob_start();
  switch($img_data[2]) {
  	case '1' : imagegif($thumb); break;
  	case '2' : imagejpeg($thumb,'', 70); break;
  	case '3' : imagepng($thumb,'', 7); break;
  }
  $a = ob_get_contents();
  ob_end_clean();
  $fp = fopen(UPDIR . $thumb_id, "wb+");
  fwrite($fp, $a);
  fclose($fp);
  echo $a;
  imagedestroy($thumb);
}

$_REQUEST['action'] = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : "";

if ($_REQUEST['action']=="upload") {
  $GLOBALS['tmpl']->assign('sess', $sessid);
  $GLOBALS['tmpl']->display('browser_upload.tpl');
  exit;
}

if ($_REQUEST['action']=="upload2") {
  for ($i=0;$i<count($_FILES['upfile']['tmp_name']);$i++) {
    $d_name = strtolower(ltrim(rtrim($_FILES['upfile']['name'][$i])));
    $d_name = str_replace(" ", "", $d_name);
    $d_tmp = $_FILES['upfile']['tmp_name'][$i];
    $endg = strtolower(substr($d_name, strlen($d_name) - 4));

    if ($_FILES['upfile']['type'][$i]=="image/pjpeg" || $_FILES['upfile']['type'][$i]=="image/jpeg" || $_FILES['upfile']['type'][$i]=="image/x-png" || $_FILES['upfile']['type'][$i]=="image/png") {
      if (file_exists(UPDIR . $_REQUEST['pfad'] . $d_name )) {
        $expl = explode(".", $d_name);
        $d_name = $expl[0] . date("dhi"). "." . $expl[1];
      }

      reportLog($_SESSION["cp_uname"] . " - �������� ����������� � (". $_REQUEST['pfad'] . $d_name. ")",'2','2');

      @move_uploaded_file($d_tmp, UPDIR . $_REQUEST['pfad'] . $d_name);
      @chmod(UPDIR . $_REQUEST['pfad'] . $d_name, 0777);
      if (isset($_REQUEST['resize']) && $_REQUEST['resize']==1) {
        $error = 0;

        if (function_exists("imagecreatetruecolor")) {
          $sowhat = "imagecreatetruecolor";

        } else {
          $sowhat = "imagecreate";
        }

        $neues_bild = $sowhat($_REQUEST['w'], $_REQUEST['h']);
        if ($_FILES['upfile']['type'][$i]=="image/pjpeg" || $_FILES['upfile']['type'][$i]=="image/jpeg") {
          $altes_bild = imagecreatefromjpeg(UPDIR . $_REQUEST['pfad'] . $d_name);
        }

        if ($_FILES['upfile']['type'][$i]=="image/png" || $_FILES['upfile']['type'][$i]=="x/png") {
          $altes_bild = imagecreatefrompng(UPDIR . $_REQUEST['pfad'] . $d_name);
        }

        if ($_FILES['upfile']['type'][$i]=="image/gif") {
          $error = 1;
        }

        if (isset($altes_bild)) {
          imagecopyresampled($neues_bild, $altes_bild, 0, 0, 0, 0, imagesx($neues_bild), imagesy($neues_bild), imagesx($altes_bild), imagesy($altes_bild));

          if ($_FILES['upfile']['type'][$i]=="image/pjpeg" || $_FILES['upfile']['type'][$i]=="image/jpeg") {
            unlink(UPDIR . $_REQUEST['pfad'] . $d_name);
            imagejpeg($neues_bild, UPDIR . $_REQUEST['pfad'] . $d_name, 95);
          }

          if ($_FILES['upfile']['type'][$i]=="image/png" || $_FILES['upfile']['type'][$i]=="x/png") {
            unlink(UPDIR . $_REQUEST['pfad'] . $d_name);
            imagepng($neues_bild, UPDIR . $_REQUEST['pfad'] . $d_name, 95);
          }
        }

      } else {
        $d_tmp = $_FILES['upfile']['tmp_name'];
        move_uploaded_file($d_tmp, UPDIR . $_REQUEST['pfad'] . $d_name);
        @chmod("media" . $_REQUEST['pfad'] . $d_name, 0777);
      }

    } else {
      move_uploaded_file($d_tmp, UPDIR . $_REQUEST['pfad'] . $d_name);
      @chmod("media" . $_REQUEST['pfad'] . $d_name, 0777);
    }
  }

  echo"
  <script language=\"javascript\">
  <!--
  window.opener.parent.frames['zf'].location.href = window.opener.parent.frames['zf'].location.href;
  window.close();
  //-->
  </script>
  ";
  exit();
}

if ($_REQUEST['action']=="delfile") {
  if(cp_perm('mediapool_del')) {
    @copy(UPDIR . $_REQUEST['file'], SOURCE_DIR . 'uploads/recycled/' . $_REQUEST['df'] );
    if(@unlink(UPDIR . $_REQUEST['file'])) {
      $error = 0;
      reportLog($_SESSION["cp_uname"] . " - ������ ����������� (". $_REQUEST['file']  . ")",'2','2');

      $img_path = $_REQUEST['file'];
      $namepos = strrpos($img_path, '/');
      if ($namepos > 0) {
      	$img_name = substr($img_path, ++$namepos);
      	$img_dir  = substr($img_path, 0, $namepos);
      	if (substr($img_path, 0, 1) != '/') {
      	  $img_dir = '/' . $img_dir;
      	}
      } else {
      	$img_name = substr($img_path, 1);
      	$img_dir  = '/';
      }
      @unlink(UPDIR . $img_dir . $th_pref . $img_name);

      $_REQUEST['file'] = "";
      $_REQUEST['action'] = "";
      ?>
      <script language="javascript">
      <!--
      parent.frames['zf'].location.href='browser.php?typ=<?=$_REQUEST['typ'];?>&dir=<?=$_REQUEST['dir'];?>&cpengine=<?=$sessid;?>&done=1';
      -->
      </script>
      <?php
      $_REQUEST['action']="list";
    }
  } else {
  ?>
    <script language="javascript">
    <!--
    parent.frames['zf'].location.href='browser.php?typ=<?=$_REQUEST['typ'];?>&dir=<?=$_REQUEST['dir'];?>&cpengine=<?=$sessid;?>&done=1';
    -->
    </script>
  <?php
  }
  $_REQUEST['action']="list";
}

$_REQUEST['done'] = (isset($_REQUEST['done']) && $_REQUEST['done'] == 1) ? 1 : "";
$dir = (isset($_REQUEST['dir']) && $_REQUEST['dir'] != '') ? $_REQUEST['dir'] : "";
$dir  = (strpos($dir, "//")!==false || substr($dir,0,4)=='/../' ) ? '' : $dir;

if ($_REQUEST['action']=="list" || $_REQUEST['done']==1) {
  if (substr($dir,strlen($dir)-4)=="/../") {
    $zerlegen = explode("/", $dir);
    $myf = count($zerlegen) - 4;
    $myd = "";
    for ($i=0; $i<$myf; $i++) {
      if ($zerlegen[$i]!="") {
        $myd .= "/" . $zerlegen[$i];
      }
    }
    if (substr($myd, strlen($myd)-1)=="/") {
      $dir = $myd;
    } else {
      $dir = $myd . "/";
    }
  }

  if (!($dir=="/")) {
    $GLOBALS['tmpl']->assign('dir', $dir);
    $GLOBALS['tmpl']->assign('sess', $sessid);
    $GLOBALS['tmpl']->assign('dirup', 1);
  }

  $resuld = @mkdir(UPDIR . $mediapath. "" . $dir . $_REQUEST['newdir']);
  $d = @dir(UPDIR . $mediapath. "" . $dir);

  while (false !== ($entry = @$d->read())) {
    if ($entry != "." &&
        $entry != ".." &&
        $entry != "index.php" &&
        !($entry == "thumbnails") &&
        substr($entry, 0, strlen($th_pref)) != $th_pref) {
      if (is_dir(UPDIR . $mediapath. "" . $dir . $entry)) {
        $elem['dir'][] = $entry;
      } else {
        $elem['file'][] = $entry;
      }
    }
  }
  $d->Close();

  @asort($elem['dir']);
  $bfiles = array();
  while (list($key,$val) = @each($elem['dir'])) {
    unset($row);
    $row->fileopen = $_REQUEST['typ'] . "&amp;cpengine=".$sessid. "&amp;dir=" . $dir . $val . "/&amp;action=list";
    $row->val = $val;
    array_push($bfiles,$row);
  }

  @asort($elem['file']);
  $unable_delete = 0;
  $dats = array();
  while (list($key,$val) = @each($elem['file'])) {
    unset($row);
    $endg = strtolower(substr($val, strlen($val) - 3));
    $endg_r = strtolower(substr($val, strlen($val) - 4));
    $end = $endg;

    $file_allowed = array('.swf','.fla','.rar','.zip','.pdf','.exe','.avi','.mov','r.gz','.doc','.wmf','.wmv','.mp3','.mp4','.mpg','.tif','.psd','.txt','.xls','.pps');
    $allowed_images =  array('.jpg','jpeg','.png','.gif');

    if (isset($_REQUEST['target']) && $_REQUEST['target']=='link') {
      $allowed = $file_allowed;
    }

    $val_allowed = substr($val,-4);


    $row->gifends = (file_exists($tpl_dir . "/images/mediapool/".$endg. ".gif")) ? $endg : "attach";
    $row->gifend = $row->gifends;
    $row->datsize = @round(@filesize("../uploads" . $dir . $val)/1024,2);
    $row->val = $val;
    $row->moddate = date("d.m.y, H:i", @filemtime("../uploads" . $dir . $val));
    $row->rowval = $dir . $val;

    if(in_array($val_allowed,$allowed_images) && function_exists("getimagesize") && function_exists("imagecreatetruecolor")) {
      $row->bild = "<img border=\"0\" src=\"browser.php?thumb=1&bild=" . $dir . $val . "\">";
    }

    $unable_delete = (strpos($dir, 'recycled')!==false) ? 1 : 0;
    array_push($dats,$row);
    unset($row);
  }

  $GLOBALS['tmpl']->assign('unable_delete',$unable_delete);
  $GLOBALS['tmpl']->assign('dats',$dats);
  $GLOBALS['tmpl']->assign('bfiles',$bfiles);
  $GLOBALS['tmpl']->assign('dir', $dir);

  $_REQUEST['newdir'] = (isset($_REQUEST['newdir'])) ? $_REQUEST['newdir'] : "";
  if ($_REQUEST['newdir'] != "" && isset($_REQUEST['newdir'])) {
    if ($resuld) {
      @chmod(UPDIR . $dir . $_REQUEST['newdir'], 0777);

    } else {
      echo '
      <script language="JavaScript" type="text/javascript">
      alert("������! ���������� ������� ���������� �� �������. ����������, ��������� ���� ���������.");
      </script>
      ';
    }
  }

  $GLOBALS['tmpl']->display('browser.tpl');

} else {
  $self = substr($_SERVER['PHP_SELF'],0,-18);

  $sub_target = @explode("__", $_REQUEST['target']);
  if(is_array($sub_target)) $sub = @$sub_target[1];

  $GLOBALS['tmpl']->assign('target_img', $sub_target[0]);
  $GLOBALS['tmpl']->assign('pop_id', $sub);
  $GLOBALS['tmpl']->assign('cppath', $self);
  $GLOBALS['tmpl']->assign('sess', $sessid);
  $GLOBALS['tmpl']->display('browser_2frames.tpl');
}
?>