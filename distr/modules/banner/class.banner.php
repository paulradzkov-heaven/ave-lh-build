<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class ModulBanner {

  var $_clickurl = "index.php?module=banner&amp;Id=";
	var $_allowed_files = array("image/jpg", "image/jpeg","image/pjpeg","image/x-png","image/png","image/gif", "application/x-shockwave-flash");
  var $_limit = 15;

  function Zufall() {
    $zufall = rand(1000, 99999);
    return $zufall;
  }

  function displayBanner($id) {
    mt_rand();
    $zufall = rand(1,3);
    $num    = '';
    $banner_id = '';
    $output = "";

    $and_kat = (!is_numeric($id) || empty($id)) ? "" : "AND KatId = '" . $id . "'";

    $Zeitspanne = "( ((ZStart <= " . date("H") . " AND ZEnde > " . date("H") . ") OR (ZStart = 0 AND ZEnde = 0)) OR ( (ZStart > ZEnde) AND ( (ZStart BETWEEN  ZStart AND " . date("H") . ") OR (ZEnde BETWEEN  " . date("H") . " AND ZEnde)) ) ) AND";

    $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_banners WHERE  Aktiv = 1 AND $Zeitspanne ( ((MaxKlicks = 0) OR (Klicks < MaxKlicks AND MaxKlicks != 0)) && ((MaxViews = 0) OR (Views < MaxViews AND MaxViews != 0)) ) " . $and_kat);
    $num = $sql->numrows();

    if($num <= 1) {
      $zufall = 4;
    }

    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_banners WHERE  Aktiv = 1 AND $Zeitspanne Gewicht <= '" . $zufall . "' AND ( ((MaxKlicks = 0) OR (Klicks < MaxKlicks AND MaxKlicks != 0)) && ((MaxViews = 0) OR (Views < MaxViews AND MaxViews != 0)) ) " . $and_kat);
    $num = $sql->numrows();

    $banner_id = ($num == 1) ? 0 : rand(0, $num-1);

    $sql->cp_dataseek($banner_id);
    $banner = $sql->fetcharray();

    if($banner['Bannertags'] != "") {
			if (stristr($banner['Bannertags'], '.swf') === false) {
				$output = "<a target=\"" . $banner['Target'] . "\" href=\"/" . $this->_clickurl . $banner['Id'] . "\"><img src=\"/modules/banner/banner/" . $banner['Bannertags'] . "\" alt=\"" . $banner['Bannername'] . ": " . $banner['BildAlt'] . "\" border=\"0\" /></a>";
			} else {
				$output = '<div style="position:relative;border:0px;width:' . $banner['Width'] . 'px;height:' . $banner['Height'] . 'px;"><a target="' . $banner['Target'] . '" href="' . $this->_clickurl . $banner['Id'] . '" style="position:absolute;z-index:2;width:' . $banner['Width'] . 'px;height:' . $banner['Height'] . 'px;_background:red;_filter:alpha(opacity=0);"></a>
          <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="' . $banner['Width'] . '" height="' . $banner['Height'] . '" id="reklama" align="middle">
          <param name="allowScriptAccess" value="sameDomain" />
          <param name="movie" value="modules/banner/banner/' . $banner['Bannertags'] . '" />
          <param name="quality" value="high" />
          <param name="wmode" value="opaque">
          <embed src="/modules/banner/banner/' . $banner['Bannertags'] . '" quality="high" wmode="opaque" width="' . $banner['Width'] . '" height="' . $banner['Height'] . '" name="reklama" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
          </object></div>';
			}

      if($banner['Id'] != '') {
        $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_banners SET Views=Views+1 WHERE Id = '" . $banner['Id'] . "'");
      }
    }
    echo $output;
  }

  function fetch_addclick($id) {


    switch($_REQUEST['action']) {

      case '':
      case 'addclick':
        $sql = $GLOBALS['db']->Query("SELECT BannerUrl FROM " . PREFIX . "_modul_banners WHERE Id = '" . $id . "'");
        $row = $sql->fetchrow();
        $sql->Close();
        if($row->BannerUrl != '') {
          $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_banners SET Klicks=Klicks+1 WHERE Id = '" . $id . "'");
          header("Location:$row->BannerUrl");
        }
        exit;
      break;
    }
  }

  function showKategs() {
    $kategs = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_banner_categories");
    while($row = $sql->fetchrow()) {
      array_push($kategs,$row);
    }
    return $kategs;
  }

  function showBanner($tpl_dir) {
    $limit = $this->_limit;
    $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_banners");
    $num = $sql->numrows();

    $seiten = ceil($num / $limit);
    $start = prepage() * $limit - $limit;

    $items = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_banners LIMIT " . $start . "," . $limit);
    while($row = $sql->fetchrow()) {
      array_push($items,$row);
    }

    $page_nav = pagenav($seiten, prepage(),
    " <a class=\"pnav\" href=\"index.php?do=modules&action=modedit&mod=banner&moduleaction=1&cp=" . SESSION . "&page={s}\">{t}</a> ");
    if($num > $limit) $GLOBALS['tmpl']->assign("page_nav", $page_nav);

    $GLOBALS['tmpl']->assign("items", $items);
    $GLOBALS['tmpl']->assign("kategs", $this->showKategs());
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "banners.tpl"));
  }

  function editBanner($tpl_dir,$id) {
    $items = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_banners WHERE Id = '" . $id . "'");
    $row = $sql->fetchrow();

		if (stristr(($row->Bannertags),'.swf') === false) $row->swf = false; else $row->swf = true;

    if(@!is_writeable(BASE_DIR . "/modules/banner/banner/")) {
      $GLOBALS['tmpl']->assign("folder_protected", 1);
    }

    $GLOBALS['tmpl']->assign("item", $row);
    $GLOBALS['tmpl']->assign("formaction", "index.php?do=modules&action=modedit&mod=banner&moduleaction=quicksave&cp=" . SESSION . "&id=" . $_REQUEST['id'] . "&pop=1");
    $GLOBALS['tmpl']->assign("kategs", $this->showKategs());
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "form.tpl"));
  }

  function deleteBanner($id) {
    $sql = $GLOBALS['db']->Query("SELECT Bannertags,Bannername FROM " . PREFIX . "_modul_banners WHERE Id = '" . $id . "'");
    $row = $sql->fetchrow();

    @unlink(BASE_DIR . "/modules/banner/banner/" . $row->Bannertags);
    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_banners WHERE Id = '" . $id . "'");
    reportLog($_SESSION['cp_uname'] . " - удалил баннер (" . $row->Bannername . ")",'2','2');
    header("Location:index.php?do=modules&action=modedit&mod=banner&moduleaction=1&cp=" . SESSION);
    exit;
  }

  function quickSave($tpl_dir, $id) {
    if(!empty($_POST['del'])) {
      $sql = $GLOBALS['db']->Query("SELECT  Bannertags FROM " . PREFIX . "_modul_banners WHERE Id = '" . $id . "'");
      $row = $sql->fetchrow();
      $sql->Close();

      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_banners SET Bannertags = '' WHERE Id='" . $id . "'");

      @unlink(BASE_DIR . "/modules/banner/banner/" . $row->Bannertags);
    }

    if(!empty($_POST['Bannername'])) {
      $d_name = strtolower($_FILES['New']['name']);
      $d_name = str_replace(" ","", $d_name);
      $d_tmp = $_FILES['New']['tmp_name'];

      if(!empty($_FILES['New']['type'])) {
        if(in_array($_FILES['New']['type'], $this->_allowed_files)) {
          $d_name = ereg_replace('([^ ._A-Za-zА-Яа-яЁё0-9-])', '_', $d_name);
          if(file_exists(BASE_DIR . "/modules/banner/banner/" . $d_name)) $d_name = $this->Zufall() . "__" . $d_name;

          if(move_uploaded_file($d_tmp, BASE_DIR . "/modules/banner/banner/" . $d_name)) {
            echo "<script>alert('" . $GLOBALS['config_vars']['BANNER_IS_UPLOADED'] . ": $d_name');</script>";

            $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_banners SET Bannertags = '" . $d_name . "' WHERE Id='" . $id . "'");

            reportLog($_SESSION['cp_uname'] . " - заменил изображение баннера на (" . $d_name . ")", '2', '2');

          } else {
            echo "<script>alert('" .$GLOBALS['config_vars']['BANNER_NO_UPLOADED']. ": " . $d_name . "');</script>";
          }

          } else {
          echo "<script>alert('" . $GLOBALS['config_vars']['BANNER_WRONG_TYPE'] . ": " . $d_name . "');</script>";
        }
      }

      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_banners
        SET
          Bannername = '" . $_REQUEST['Bannername']. "',
          BannerUrl = '" . $_REQUEST['BannerUrl']. "',
          Gewicht = '" . $_REQUEST['Gewicht']. "',
          Views = '" . $_REQUEST['Anzeigen']. "',
          Klicks = '" . $_REQUEST['Klicks']. "',
          BildAlt = '" . $_REQUEST['BildAlt']. "',
          KatId = '" . $_REQUEST['KatId'] . "',
          MaxKlicks = '" . $_REQUEST['MaxKlicks'] . "',
          MaxViews = '" . $_REQUEST['MaxViews'] . "',
          ZStart = '" . $_REQUEST['ZStart'] . "',
          ZEnde = '" . $_REQUEST['ZEnde'] . "',
          Aktiv = '" . $_REQUEST['Aktiv'] . "',
          Target = '" . $_REQUEST['Target'] . "',
          Width = '" . $_REQUEST['Width'] . "',
          Height = '" . $_REQUEST['Height'] . "'
        WHERE
          Id = '" . $id . "'
        ");
      reportLog($_SESSION['cp_uname'] . " - изменил параметры баннера (" . $_REQUEST['Bannername'] . ")", '2', '2');
    }
    echo "<script>window.opener.location.reload(); self.close();</script>";
  }

  function newBanner($tpl_dir) {

    switch($_REQUEST['sub']) {
      case '':
        if(!@is_writeable(BASE_DIR . "/modules/banner/banner/")) {
          $GLOBALS['tmpl']->assign("folder_protected", 1);
        }
        $GLOBALS['tmpl']->assign("kategs", $this->showKategs());
        $GLOBALS['tmpl']->assign("formaction", "index.php?do=modules&action=modedit&mod=banner&moduleaction=newbanner&sub=save&cp=" . SESSION . "&pop=1");
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "form.tpl"));
      break;

      case 'save':
        if(!empty($_POST['Bannername'])) {
          $upload = false;
          $file = "";

          $d_name = strtolower($_FILES['New']['name']);
          $d_name = str_replace(" ", "", $d_name);
          $d_tmp = $_FILES['New']['tmp_name'];

          if(!empty($_FILES['New']['type'])) {
            if(in_array($_FILES['New']['type'], $this->_allowed_files)) {
              $d_name = ereg_replace('([^ ._A-Za-zА-Яа-яЁё0-9-])', '_', $d_name);
              if(file_exists(BASE_DIR . "/modules/banner/banner/" . $d_name)) $d_name = $this->Zufall() . "__" . $d_name;

              if(@move_uploaded_file($d_tmp, BASE_DIR . "/modules/banner/banner/" . $d_name)) {
                echo "<script>alert('" . $GLOBALS['config_vars']['BANNER_IS_UPLOADED'] . ": " . $d_name . "');</script>";
                reportLog($_SESSION['cp_uname'] . " - добавил изображение баннера (" . $d_name . ")",'2','2');
                $file = $d_name;
              } else {
                echo "<script>alert('" . $GLOBALS['config_vars']['BANNER_NO_UPLOADED'] . ": " . $d_name . "');</script>";
              }
            } else {
              echo "<script>alert('" . $GLOBALS['config_vars']['BANNER_WRONG_TYPE'] . ": " . $d_name . "');</script>";
            }
          }

          $sql = "INSERT INTO " . PREFIX . "_modul_banners (
            Id,
            KatId,
            Bannertags,
            BannerUrl,
            Gewicht,
            Bannername,
            BildAlt,
            MaxKlicks,
            MaxViews,
            ZStart,
            ZEnde,
            Aktiv,
            Target,
            Width,
            Height
          ) VALUES (
            '',
            '" . $_REQUEST['KatId'] . "',
            '" . $file . "',
            '" . $_REQUEST['BannerUrl'] . "',
            '" . $_REQUEST['Gewicht'] . "',
            '" . $_REQUEST['Bannername'] . "',
            '" . $_REQUEST['BildAlt'] . "',
            '" . $_REQUEST['MaxKlicks'] . "',
            '" . $_REQUEST['MaxViews'] . "',
            '" . $_REQUEST['ZStart'] . "',
            '" . $_REQUEST['ZEnde'] . "',
            '" . $_REQUEST['Aktiv'] . "',
            '" . $_REQUEST['Target'] . "',
            '" . $_REQUEST['Width'] . "',
            '" . $_REQUEST['Height'] . "'
          )";

          $GLOBALS['db']->Query($sql);
          reportLog($_SESSION['cp_uname'] . " - добавил новый баннер (" . $_REQUEST['Bannername'] . ")", '2', '2');
        }
        echo "<script>window.opener.location.reload(); self.close();</script>";
      break;
    }
  }

  function bannerKategs($tpl_dir) {

    switch($_REQUEST['sub']) {

      case '' :
        $items = array();
        $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_banner_categories");
        while($row = $sql->fetchrow()) {
          array_push($items,$row);
        }
        $GLOBALS['tmpl']->assign("items", $items);
        $GLOBALS['tmpl']->assign("kategs", $this->showKategs());
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "kategs.tpl"));
      break;

      case 'save' :
        foreach($_POST['KatName'] as $id => $Kateg) {
          if(!empty($_POST['KatName'][$id])) {
            $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_banner_categories SET KatName = '" . $_POST['KatName'][$id] . "' WHERE Id = '" . $id . "'");
          }
        }

        foreach($_POST['del'] as $id => $Kateg) {
          $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_banners WHERE KatId = '" . $id . "'");
          $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_banner_categories WHERE Id = '" . $id . "'");
          reportLog($_SESSION['cp_uname'] . " - удалил категорию баннеров (" . $id . ")", '2', '2');
        }

        header("Location:index.php?do=modules&action=modedit&mod=banner&moduleaction=kategs&cp=" . SESSION);
      break;


      case 'new' :
        if(!empty($_REQUEST['KatName'])) {
          $sql = $GLOBALS['db']->Query("INSERT INTO  " . PREFIX . "_modul_banner_categories (Id, KatName) VALUES ('','" . $_REQUEST['KatName'] . "')");
          reportLog($_SESSION['cp_uname'] . " - добавил новую категорию (" . $_REQUEST['KatName'] . ")", '2', '2');
        }

        header("Location:index.php?do=modules&action=modedit&mod=banner&moduleaction=kategs&cp=" . SESSION);
      break;
    }
  }
}
?>