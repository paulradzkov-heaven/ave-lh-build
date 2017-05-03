<?php
/*::::::::::::::::::::::::::::::::::::::::
System name: cpengine
Short Desc: Full Russian Security Power Pack
Version: 2.0 (Service Pack 2)
Authors:  Arcanum (php@211.ru) &  Censored!
Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class Gallery {

  var $_categ_limit = 10;
  var $_adminlimit_images = 10;
  var $_allowed = array('.jpg','jpeg','.jpe','.gif','.png','.avi','.mov','.wmv','.wmf');
  var $_resize = array('.jpg','jpeg','.jpe','.gif','.png');
  var $_default_limit = 15;

  function mediaType($endg) {
    switch($endg) {
      case '.avi' : $f_end = 'avi'; break;
      case '.mov' : $f_end = 'mov'; break;
      case '.wmv' : $f_end = 'avi'; break;
      case '.wmf' : $f_end = 'avi'; break;
      case '.mpg' : $f_end = 'avi'; break;
    }
    return   $f_end;
  }

  function getUserById($id, $user = "", $row = "") {
    $sql = $GLOBALS['db']->Query("SELECT Vorname,Nachname FROM " . PREFIX . "_users WHERE Id = '" . (int)$id . "'");
    $row = $sql->fetchrow();
    if(!empty($row)) $user = substr($row->Vorname,0,1) . "." . $row->Nachname;
    return $user;
  }

  function displayImage($tpl_dir, $iid) {
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_gallery_images WHERE Id = '" . $iid . "'");
    $row = $sql->fetchrow();
    $typ = $this->defType($row->Endung);

    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_gallery WHERE Id = '" . $row->GalId . "'");
    $r = $sql->fetchrow();

    if($typ == 'gif' || $typ == 'jpg' || $typ == 'png') {
      $file_size = getimagesize(BASE_DIR . '/modules/gallery/uploads/' . $r->GPfad . $row->Pfad);
      $GLOBALS['tmpl']->assign('w', ($file_size[0] < 350 ? 350 : ($file_size[0] > 950 ? 950 : $file_size[0]+10) ));
      $GLOBALS['tmpl']->assign('h', ($file_size[1] < 350 ? 350 : ($file_size[1] > 700 ? 700 : $file_size[1]+50) ));
      $GLOBALS['tmpl']->assign('scrollbars', ($file_size[0] > 950 || $file_size[1] > 700 ? 1 : '') );
      $GLOBALS['tmpl']->assign('image', '/modules/gallery/uploads/' . $r->GPfad . rawurlencode($row->Pfad));
      $GLOBALS['tmpl']->assign('titel', $row->BildTitel);
    }

    if($typ == 'video') {
      $GLOBALS['tmpl']->assign('w', 350);
      $GLOBALS['tmpl']->assign('notresizable', 1);
      $GLOBALS['tmpl']->assign('h', 400);
      $GLOBALS['tmpl']->assign('image', '/modules/gallery/uploads/' . $r->GPfad . rawurlencode($row->Pfad));
      $GLOBALS['tmpl']->assign('mediatype', $this->mediaType($row->Endung));
    }

    $GLOBALS['tmpl']->display($tpl_dir . "image.tpl");
  }

  function showGallery($tpl_dir, $id, $lim, $ext=0) {
    $images = array();

    $sql_gs = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_gallery WHERE Id = '" . $id . "'");
    $row_gs = $sql_gs->fetchrow();

    $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_gallery_images WHERE GalId = '" . $id . "' ORDER BY Id ".$row_gs->Sort);
    $num = $sql->numrows();

    $limit = (!empty($row_gs->MaxBilder) && $row_gs->MaxBilder > 0) ? $row_gs->MaxBilder : $this->_default_limit;

    $limit = ($lim!='') ? $lim : $limit;
    $limit = ($ext==1) ? 10000 : $limit;
    $seiten = ceil($num / $limit);
    $start = prepage() * $limit - $limit;

    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_gallery_images WHERE GalId = '" . $id . "' ORDER BY Id ".$row_gs->Sort." LIMIT $start,$limit");
    while($row = $sql->fetcharray()) {
      $row['Type'] = $this->defType($row['Endung']);
      $row['Author'] = $this->getUserById($row['Author']);
      $row['Size'] = @filesize(BASE_DIR . '/modules/gallery/uploads/' . $row['Pfad']) / 1024;
      $row['Size'] = @round($row['Size'], 0);
      $row['ThumbBreite'] = $row_gs->ThumbBreite;
      $row['Typ'] = $this->defType($row['Endung']);
      $row['NotResizable'] = ($row['Typ']=='video') ? 1 : 0;
      $row['BildBeschr'] = $row['BildBeschr'];
      $row['BildTitel'] = $row['BildTitel'];
      $row['Pfad'] = rawurlencode($row['Pfad']);
      $row['GPfad'] = $row_gs->GPfad;
      array_push($images, $row);
    }

    // CP_REWRITE
    //  $page_url_nav = "/index.php?id=$_REQUEST[id]" . ((isset($_REQUEST['doc']) && $_REQUEST['doc'] != '') ? '&amp;doc=' . $_REQUEST['doc'] . '' : 'index') . "&amp;page={s}";
    $page_url_nav = "/index.php?id=$_REQUEST[id]&amp;page={s}";
    $page_url_nav = (CP_REWRITE == 1) ? cp_rewrite($page_url_nav) : $page_url_nav;

    $page_nav = pagenav($seiten, prepage()," <a class=\"pnav\" href=\"$page_url_nav\">{t}</a> ");
    if($num > $limit && (empty($lim))) {
      $GLOBALS['tmpl']->assign("page_nav", $page_nav);
    }

    if(!empty($lim) && $lim < $num) $GLOBALS['tmpl']->assign('more_images', 1);

    $GLOBALS['tmpl']->assign('cp_theme', T_PATH);
    $GLOBALS['tmpl']->assign('gal_id', $id);
    $GLOBALS['tmpl']->assign('row_gs', $row_gs);
    $GLOBALS['tmpl']->assign('images', $images);

    if ($ext==1) {
      $GLOBALS['tmpl']->assign('path', BASE_DIR . '/modules/gallery/templates');
      $GLOBALS['tmpl']->assign('galname', $row_gs->GName);
      $GLOBALS['tmpl']->assign('Beschreibung', $row_gs->Beschreibung);
      $GLOBALS['tmpl']->display($tpl_dir . "galerie_popup.tpl");
    } else {
      $GLOBALS['tmpl']->display($tpl_dir . "galerie.tpl");
    }
  }

  function defType($endg) {
    switch($endg) {
      case '.jpg' :
      case 'jpeg' :
      case '.jpe' : $f_end = 'jpg'; break;
      case '.png' : $f_end = 'png'; break;
      case '.gif' : $f_end = 'gif'; break;
      case '.avi' : $f_end = 'video'; break;
      case '.mov' : $f_end = 'video'; break;
      case '.wmv' : $f_end = 'video'; break;
      case '.wmf' : $f_end = 'video'; break;
      case '.mpg' : $f_end = 'video'; break;
    }
    return $f_end;
  }

  function delGallery($id) {
    $sql = $GLOBALS['db']->Query("SELECT GPfad FROM " . PREFIX . "_modul_gallery WHERE Id = '" . $id . "'");
    $row = $sql->fetcharray();
    $gal_pfad = $row['GPfad'];

    if (trim($gal_pfad) !== '') {
      $dir = opendir(BASE_DIR . '/modules/gallery/uploads/' . $gal_pfad);
      while(($file = readdir($dir))) {
        if ( is_file (BASE_DIR . '/modules/gallery/uploads/' . $gal_pfad . $file)) {
          unlink (BASE_DIR . '/modules/gallery/uploads/' . $gal_pfad . $file);
        }
      }
      closedir ($dir);
      rmdir (BASE_DIR . '/modules/gallery/uploads/' . str_replace('/','',$gal_pfad));
    } else {
      $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_gallery_images WHERE GalId = '" . $id . "'");
      while($row = $sql->fetchrow()) {
        @unlink(BASE_DIR . '/modules/gallery/uploads/' . $row->Pfad);
        @unlink(BASE_DIR . '/modules/gallery/uploads/thumb__' . $row->Pfad);
      }
    }
    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_gallery WHERE Id = '" . $id . "'");
    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_gallery_images WHERE GalId = '" . $id . "'");
    header("Location:index.php?do=modules&action=modedit&mod=gallery&moduleaction=1&cp=" . SESSION);
    exit;
  }

  function showImages($tpl_dir, $id) {
    $sql = $GLOBALS['db']->Query("SELECT GPfad FROM " . PREFIX . "_modul_gallery WHERE Id = '" . $id . "'");
    $row = $sql->fetcharray();
    $gal_pfad = $row['GPfad'];
    if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save') {

      if(isset($_POST['del']) && count($_POST['del']) > 0) {
        foreach($_POST['del'] as $id => $del) {
          @unlink(BASE_DIR . '/modules/gallery/uploads/' . $gal_pfad . $_POST['datei'][$id]);
          @unlink(BASE_DIR . '/modules/gallery/uploads/'. $gal_pfad . 'thumb__' . $_POST['datei'][$id]);
          $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_gallery_images WHERE Id = '" . $id . "'");
        }
      }

      foreach($_POST['gimg'] as $id => $BildTitel) {
        $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_gallery_images
          SET
            BildTitel = '" . $_POST['BildTitel'][$id] . "',
            BildBeschr = '" . $_POST['BildBeschr'][$id] . "'
          WHERE
            Id = '$id' AND GalId = '" . $_REQUEST['id'] . "'");
      }
      $id = $_REQUEST['id'];
    }

    $images = array();

    $sql_gs = $GLOBALS['db']->Query("SELECT ThumbBreite FROM " . PREFIX . "_modul_gallery WHERE Id = '" . $id . "'");
    $row_gs = $sql_gs->fetchrow();

    $sql_i = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_gallery_images WHERE GalId = '" . $id . "'");
    $num = $sql_i->numrows();

    $limit = $this->_adminlimit_images;
    $seiten = ceil($num / $limit);
    $start = prepage() * $limit - $limit;

    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_gallery_images WHERE GalId = '" . $id . "' ORDER BY Id DESC LIMIT $start,$limit");
    while($row = $sql->fetcharray()) {
      $row['Type'] = $this->defType($row['Endung']);
      $row['Author'] = $this->getUserById($row['Author']);
      $row['Size'] = @filesize(BASE_DIR . '/modules/gallery/uploads/' . $gal_pfad . $row['Pfad']) / 1024;
      $row['Size'] = @round($row['Size'],2);
      $row['ThumbBreite'] = $row_gs->ThumbBreite;
      $row['GPfad'] = $gal_pfad;
      array_push($images, $row);
    }

    $page_nav = pagenav($seiten, prepage()," <a class=\"pnav\" href=\"index.php?do=modules&action=modedit&mod=gallery&moduleaction=showimages&id=$id&cp=" . SESSION . "&pop=1&page={s}\">{t}</a> ");
    if($num > $limit) {
      $GLOBALS['tmpl']->assign("page_nav", $page_nav);
    }

    $GLOBALS['tmpl']->assign('images', $images);
    $GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'admin_galeriebilder.tpl'));
  }

  function renameFile($file) {
    mt_rand();
    $zufall = rand(1,999);
    $rn_file = $zufall . '_' . $file;
    return $rn_file;
  }

  function image_overlap($background, $foreground) {
    $insertWidth  = imagesx($foreground);
    $insertHeight = imagesy($foreground);

    $imageWidth   = imagesx($background);
    $imageHeight  = imagesy($background);

    $overlapX     = $imageWidth-$insertWidth-5;
    $overlapY     = $imageHeight-$insertHeight-5;

    imagecopymerge($background,$foreground,$overlapX,$overlapY,0,0,$insertWidth,$insertHeight,70);
    return $background;
  }

  function rebuildImage($watermark_file_name, $upload_dir, $fupload_name, $bild_typ) {
    // Shrink
    if(isset($_REQUEST['shrink']) && $_REQUEST['shrink'] != '' && $_REQUEST['shrink'] < 100) {
      if($bild_typ=="image/pjpeg" || $bild_typ=="image/jpeg" || $bild_typ=="image/jpg" || $bild_typ=="image/png" || $bild_typ=="x/png" || $bild_typ=="image/gif") {
        switch($bild_typ) {
          case "image/pjpeg" :
          case "image/jpeg" :
          case "image/jpg" :
          case "image/jpe" :
            $method = "imagecreatefromjpeg";
            $method_out = "imagejpeg";
            break;

          case "image/png" :
          case "x/png" :
            $method = "imagecreatefrompng";
            $method_out = "imagepng";
            break;

          case "image/gif" :
            $method = "imagecreatefromgif";
            $method_out = "imagegif";
            break;
        }

        $file_size = getimagesize($upload_dir . $fupload_name);
        $neues_bild = @imagecreatetruecolor($file_size[0] * $_REQUEST['shrink'] / 100 , $file_size[1] * $_REQUEST['shrink'] / 100 );
        $altes_bild = $method($upload_dir . $fupload_name);
        imagecopyresampled($neues_bild, $altes_bild, 0, 0, 0, 0, imagesx($neues_bild), imagesy($neues_bild), imagesx($altes_bild), imagesy($altes_bild));
        unlink($upload_dir . $fupload_name);
        $method_out($neues_bild, $upload_dir . $fupload_name, 90);
        @chmod($upload_dir . $fupload_name, 0777);
      }
    }

    // Watermark
    if(isset($watermark_file_name) && $watermark_file_name != '' ) {
      $wmfname = dirname(__FILE__) . '/' . $watermark_file_name;
      if($wmfname != '' && is_file($wmfname)) {
        $watermark = false;
        if (strpos(strtolower($wmfname),".jpg") != false || strpos(strtolower($wmfname),".jpeg") != false || strpos(strtolower($wmfname),".jpe") != false) {
          $watermark = @imagecreatefromjpeg($wmfname);

        } elseif (strpos(strtolower($wmfname),".gif") != false){
          $watermark = @imagecreatefromgif($wmfname);

        } elseif (strpos(strtolower($wmfname),".png") != false){
          $watermark = @imagecreatefrompng($wmfname);
        }

        if ($watermark) {
          if($bild_typ=="image/pjpeg" || $bild_typ=="image/jpeg" || $bild_typ=="image/jpg" || $bild_typ=="image/png" || $bild_typ=="x/png" || $bild_typ=="image/gif") {
            switch($bild_typ) {
              case "image/pjpeg" :
              case "image/jpeg" :
              case "image/jpg" :
              case "image/jpe" :
                $method = "imagecreatefromjpeg";
                $method_out = "imagejpeg";
                break;

              case "image/png" :
              case "x/png" :
                $method = "imagecreatefrompng";
                $method_out = "imagepng";
                break;

              case "image/gif" :
                $method = "imagecreatefromgif";
                $method_out = "imagegif";
                break;
            }

            $altes_bild = $method($upload_dir . $fupload_name);
            $altes_bild = $this->image_overlap($altes_bild, $watermark);
            unlink($upload_dir . $fupload_name);
            $method_out($altes_bild, $upload_dir . $fupload_name, 90);
            @chmod($upload_dir . $fupload_name, 0777);
          }
        }
      }
    }
  }

  function uploadForm($tpl_dir, $id) {
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_gallery WHERE Id = '" . $id . "'");
    $row = $sql->fetchrow();
    $Watermark = $row->Watermark;
    $upload_dir = BASE_DIR . "/modules/gallery/uploads/" . $row->GPfad;

    if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save') {
      if(isset($_REQUEST['regfile']) && $_REQUEST['regfile'] != '') {
        $temp_dir = BASE_DIR . "/modules/gallery/uploads/temp/";
        if ($handle = opendir($temp_dir)) {
          while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
              $BildTitel = substr($file, 0, -4);
              $name = cp_parse_linkname($BildTitel);
              $endung = substr($file, -4);
              $fupload_name = $name . $endung;

              if(file_exists($upload_dir . $fupload_name)) $fupload_name = $this->renameFile($fupload_name);

              if(!empty($name) && in_array($endung,$this->_allowed) ) {
                copy($temp_dir . $file, $upload_dir . $fupload_name);
                unlink($temp_dir . $file);
                @chmod($upload_dir . $fupload_name, 0777);

                $bild_typ = $this->defType($endung);
                $this->rebuildImage($Watermark, $upload_dir, $fupload_name, "image/" . $bild_typ);

                $arr[] = '<img src="../modules/gallery/thumb.php?x_width=' . $row->ThumbBreite . '&file=' . $fupload_name . '&type=' . $bild_typ . '&folder=' . $row->GPfad . '" />';

                $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_gallery_images (Id, GalId, Pfad, Author, BildTitel, Endung, BildBeschr, Erstellt)
                  VALUES ('', '" . $_REQUEST['id'] . "', '$fupload_name', '" . $_SESSION['cp_benutzerid'] . "', '$BildTitel', '$endung', '', '" . time() . "')
                ");
              }
            }
          }
          closedir($handle);
        }
      }

      for($i=0;$i<count(@$_FILES['file']['tmp_name']);$i++) {
        $name = str_replace(array(' ', '+', '-'),'',strtolower($_FILES['file']['name'][$i]));
        $endung = substr($name, -4);
        $fupload_name = $name;

        if(file_exists($upload_dir . $fupload_name)) {
          $fupload_name = $this->renameFile($fupload_name);
          $name = $fupload_name;
        }

        if(!empty($name) && in_array($endung,$this->_allowed) ) {
          move_uploaded_file($_FILES['file']['tmp_name'][$i], $upload_dir . $fupload_name);
          @chmod($upload_dir . $fupload_name, 0777);

          $this->rebuildImage($Watermark, $upload_dir, $fupload_name, $_FILES['file']['type'][$i]);

          $arr[] = '<img src="../modules/gallery/thumb.php?x_width=' . $row->ThumbBreite . '&file=' . $name . '&type=' . $this->defType($endung) . '&folder=' . $row->GPfad . '" />';

          $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_gallery_images (Id, GalId, Pfad, Author, BildTitel, Endung, BildBeschr, Erstellt)
            VALUES ('', '" . $_REQUEST['id'] . "', '$fupload_name', '" . $_SESSION['cp_benutzerid'] . "', '" . @$_POST['BildTitel'][$i] . "', '$endung', '" . @$_POST['BildBeschr'] . "', '" . time() . "')
          ");
        }
      }
      @$GLOBALS['tmpl']->assign('arr', $arr);
      $GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'admin_uploadform_finish.tpl'));

    } else {
      if(!is_writable($upload_dir)) {
        $GLOBALS['tmpl']->assign('not_writeable', 1);
      }
      $GLOBALS['tmpl']->assign('allowed', $this->_allowed);
      $GLOBALS['tmpl']->assign('formaction', 'index.php?do=modules&action=modedit&mod=gallery&moduleaction=add&sub=save&id=' . $_REQUEST['id'] . '&cp=' . SESSION);
      $GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'admin_uploadform.tpl'));
    }
  }

  function showGalleries($tpl_dir) {
    $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_gallery");
    $num = $sql->numrows();

    $create_folders_for = $_POST['create'];
    if ($create_folders_for) {
      foreach ($create_folders_for as $gallery_id) {
        $this->dragGalleries($gallery_id,false);
      }
    }

    $limit = $this->_categ_limit;
    $seiten = ceil($num / $limit);
    $start = prepage() * $limit - $limit;
    $alert = $_REQUEST['alert'];
    if ($alert) {$al = $alert;}
    $gals = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_gallery ORDER BY Erstellt DESC LIMIT $start,$limit");
    while($row = $sql->fetcharray()) {
      $sql_2 = $GLOBALS['db']->Query("SELECT COUNT(Id) AS icount FROM " . PREFIX . "_modul_gallery_images WHERE GalId = '" . $row['Id'] . "'");
      $row_2 = $sql_2->fetchrow();

      $row['Icount'] = $row_2->icount;
      $row['Author'] = $this->getUserById($row['Author']);
      array_push($gals,$row);
    }

    $page_nav = pagenav($seiten, prepage()," <a class=\"pnav\" href=\"index.php?do=modules&action=modedit&mod=gallery&moduleaction=1&cp=" . SESSION . "&page={s}\">{t}</a> ");
    if($num > $limit) {
      $GLOBALS['tmpl']->assign("page_nav", $page_nav);
    }

    $GLOBALS['tmpl']->assign('gals', $gals);
    $GLOBALS['tmpl']->assign('alert', $al);
    $GLOBALS['tmpl']->assign('formaction', 'index.php?do=modules&action=modedit&mod=gallery&moduleaction=new&sub=save&cp=' . SESSION);
    $GLOBALS['tmpl']->assign('formnew', $GLOBALS['tmpl']->fetch($tpl_dir . 'admin_galerieform.tpl'));
    $GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'admin_galerien.tpl'));
  }

  function newGallery($tpl_dir) {
    if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save') {
      $cont = true;
      //=======================================================
      // Neue Galeir speichern
      //=======================================================
      $n = cp_parse_linkname($_POST['GPfad']);
      if (trim($n) !== '') {
        $n .= '/';
        $sql = $GLOBALS['db']->Query("SELECT Id,GPfad FROM " . PREFIX . "_modul_gallery WHERE GPfad = '" . $n . "'");
        if($row = $sql->fetcharray()) {
          if ($id !== $row['Id']) {
            $alert = "&alert=Folder_exists";
            $cont = false;
          } else {
            $alert = '';
          }
        }
      }
      if ($cont == true) {
        $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_gallery (Id,GPfad,GName,Beschreibung,Author,Erstellt) VALUES ('','" . $n . "','" . $_POST['GName'] . "','" . $_POST['Beschreibung'] . "','" . $_SESSION['cp_benutzerid'] . "','" . time() . "')");
        if (trim($n) !== '') {mkdir(BASE_DIR . '/modules/gallery/uploads/' . $n,0750);}
      }
      header("Location:index.php?do=modules&action=modedit&mod=gallery&moduleaction=1&cp=" . SESSION . $alert);
      exit;
    } else {
      //=======================================================
      // Galerieform
      //=======================================================
      $GLOBALS['tmpl']->assign('formaction', 'index.php?do=modules&action=modedit&mod=gallery&moduleaction=new&sub=save&cp=' . SESSION);
      $GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'admin_galerieform.tpl'));
    }
  }

  function galleryInfo($tpl_dir, $id) {
    $sql = $GLOBALS['db']->Query("SELECT GPfad FROM " . PREFIX . "_modul_gallery WHERE Id = '" . addslashes($id) . "'");
    $row = $sql->fetcharray();
    $gal_pfad = $row['GPfad'];
    if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save') {
      if($_REQUEST['ThumbBreite_Alt'] != $_REQUEST['ThumbBreite']) {
        $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_gallery_images WHERE GalId = '" . $id . "'");
        while($row = $sql->fetchrow()) {
          @unlink(BASE_DIR . '/modules/gallery/uploads/' . $gal_pfad . 'thumb__' . $row->Pfad);
        }

        echo '<script>window.open("index.php?do=modules&action=modedit&mod=gallery&moduleaction=showimages&id=' . $id . '&cp=' . SESSION . '&pop=1","","left=0,top=0,scrollbars=1");</script>';
      }
      $n = cp_parse_linkname($_POST['GPfad']);
      if (trim($n) !== '' && $_POST['GPfad_flag'] == '1') {
        rename(BASE_DIR . '/modules/gallery/uploads/' . str_replace('/','',$gal_pfad),BASE_DIR . '/modules/gallery/uploads/' . $n);
        chmod(BASE_DIR . '/modules/gallery/uploads/' . str_replace('/','',$gal_pfad),0777);
        $sql = $GLOBALS['db']->Query("SELECT Id,GPfad FROM " . PREFIX . "_modul_gallery WHERE GPfad = '" . $n . "/'");
        if($row = $sql->fetcharray()) {
          if ($id !== $row['Id']) {echo "<script type=\"text/javascript\" language=\"JavaScript\">alert(\"Такая папка уже существует\")</script>";$n = $gal_pfad;}
        }
      } elseif ($_POST['GPfad_flag'] == '1' && trim($n) == '') {
        $this->dragGalleries($id,true);
      }
      $n .= '/';
      if ($n=='/') $n='';

      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_gallery
        SET
           MaxBilder = '" . $_REQUEST['MaxBilder'] . "',
           TypeOut = '" . $_REQUEST['TypeOut'] . "',
           ZeigeGroesse = '" . $_REQUEST['ZeigeGroesse'] . "',
           ZeigeBeschreibung = '" . $_REQUEST['ZeigeBeschreibung'] . "',
           ZeigeTitel = '" . $_REQUEST['ZeigeTitel'] . "',
           GName = '" . $_REQUEST['GName'] . "',
           Beschreibung = '" . $_REQUEST['Beschreibung'] . "',
           ThumbBreite = '" . (isset($_REQUEST['ThumbBreite']) && $_REQUEST['ThumbBreite'] != '' && is_numeric($_REQUEST['ThumbBreite']) ? $_REQUEST['ThumbBreite'] : 120) . "',
           MaxZeile = '" . (isset($_REQUEST['MaxZeile']) && $_REQUEST['MaxZeile'] != '' && is_numeric($_REQUEST['MaxZeile']) ? $_REQUEST['MaxZeile'] : 4) . "',
           Watermark = '" . $_REQUEST['Watermark'] . "',
           GPfad = '" . $n . "',
		   Sort = '".$_REQUEST['Sort']."'
        WHERE Id = '" . $_REQUEST['id'] . "'");
      echo '<script>window.opener.location.reload(); window.close();</script>';
    }

    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_gallery WHERE Id = '" . $id . "'");
    $row = $sql->fetcharray();
    $row['GPfad'] = str_replace('/','',$row['GPfad']);
    $GLOBALS['tmpl']->assign('row', $row);
    $GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'admin_galerie.tpl'));
  }

  function dragGalleries($gal_id, $flag) {
    $sql = $GLOBALS['db']->Query("SELECT GName,GPfad FROM " . PREFIX . "_modul_gallery WHERE Id = '" . $gal_id . "'");
    $row = $sql->fetcharray();
    $continue = false;
    if (trim($row['GPfad']) == '' && $flag == false) {
      if (trim($row['GName']) !== '') {
        $folder_name = cp_parse_linkname($row['GName']);
        while(file_exists(BASE_DIR . '/modules/gallery/uploads/' . $folder_name)) {
          $folder_name .= '_';
        }
      } else {
        $folder_name = 'gal_' . $gal_id;
        while(file_exists(BASE_DIR . '/modules/gallery/uploads/' . $folder_name)) {
          $folder_name .= '_';
        }
      }
      $continue = true;
    }

    if ($continue) {
      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_gallery SET GPfad = '" . $folder_name . "/' WHERE Id = '" . $gal_id . "'");

      mkdir(BASE_DIR . '/modules/gallery/uploads/' . $folder_name,0777);

      $sql = $GLOBALS['db']->Query("SELECT Pfad FROM " . PREFIX . "_modul_gallery_images WHERE GalId = '" . $gal_id . "'");
      while ($row = $sql->fetcharray()) {
        $file_name = $row['Pfad'];
        $del = true;
        copy(BASE_DIR . "/modules/gallery/uploads/" . $file_name,BASE_DIR . "/modules/gallery/uploads/" . $folder_name . '/' . $file_name);
        copy(BASE_DIR . "/modules/gallery/uploads/thumb__" . $file_name,BASE_DIR . "/modules/gallery/uploads/" . $folder_name . '/thumb__' . $file_name);
        if ($del) {@unlink(BASE_DIR . '/modules/gallery/uploads/' . $file_name);@unlink(BASE_DIR . '/modules/gallery/uploads/thumb__' . $file_name);}
      }
    } elseif($flag == true) {
      $sql = $GLOBALS['db']->Query("SELECT GPfad FROM " . PREFIX . "_modul_gallery WHERE Id = '" . $gal_id . "'");
      $row = $sql->fetcharray();
      $gpfad = $row['GPfad'];
      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_gallery SET GPfad = '' WHERE Id = '" . $gal_id . "'");
      $sql = $GLOBALS['db']->Query("SELECT Pfad FROM " . PREFIX . "_modul_gallery_images WHERE GalId = '" . $gal_id . "'");
      while ($row = $sql->fetcharray()) {
        $file_name = $row['Pfad'];
        chmod(BASE_DIR . '/modules/gallery/uploads' , 0777);
        copy(BASE_DIR . "/modules/gallery/uploads/" . $gpfad . $file_name,BASE_DIR . "/modules/gallery/uploads/" . $file_name);
        copy(BASE_DIR . "/modules/gallery/uploads/" . $gpfad . "thumb__" . $file_name,BASE_DIR . "/modules/gallery/uploads/thumb__" . $file_name);
      }
      $dir = opendir(BASE_DIR . '/modules/gallery/uploads/' . $gpfad);
      while(($file = readdir($dir))) {
        if ( is_file (BASE_DIR . '/modules/gallery/uploads/' . $gpfad . $file)) {
          unlink (BASE_DIR . '/modules/gallery/uploads/' . $gpfad . $file);
        }
      }
      closedir ($dir);
      rmdir (BASE_DIR . '/modules/gallery/uploads/' . str_replace("/","",$gpfad));
    }
  }
}
?>