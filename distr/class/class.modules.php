<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class Module {

  function mainTemplates() {
    $items = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_templates");
      while($row = $sql->fetchrow()) {
        array_push($items,$row);
      }
    return $items;
  }

  function quickSave() {

    foreach($_POST['Template'] as $id => $Template) {
      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_module SET Template = '" . $_POST['Template'][$id] . "' WHERE Id = '$id'");
    }
    header("Location:index.php?do=modules&cp=" . SESSION);
    exit;
  }

  function showModules() {
    $dir     = BASE_DIR . "/modules";
    $modules = array();
    $modul   = '';
    $d = dir($dir);

    while (false !== ($entry = $d->read())) {
      if($entry!='.' && $entry!='..' && $entry!='.svn' && $entry!='_svn') {
        $entry = $dir.'/'.$entry;
          if(is_dir($entry)) {
            if(!include($entry . "/modul.php")) echo $GLOBALS['config_vars']['MODULES_ERROR'].$entry ."<br />";

          $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_module WHERE ModulName = '" . $modul['ModulName'] . "'");
          $r = $sql->fetchrow();

          if($r) {
            $row->status = $r->Status;
            $row->id = $r->Id;
            $row->version = $r->Version;
          } else {
            $row->status = 0;
            $row->id = $modul['ModulPfad'];
            $row->version = "&nbsp;";
          }

          $row->adminedit = (!empty($modul['AdminEdit'])) ? 1 : 0;
          $row->copyright = $modul['MCopyright'];
          $row->pfad      = $modul['ModulPfad'];
          $row->mod_r     = 'mod_' . $modul['ModulPfad'];
          $row->name      = $modul['ModulName'];
          $row->tag       = $modul['CpEngineTagTpl'];
          $row->autor     = $modul['Autor'];
          $row->descr     = $modul['Beschreibung'];
          if(@$r->Version < $modul['ModulVersion']) $row->UpdateV = 1;
          $row->all_templates = $this->mainTemplates();
          @$row->ModId = $r->Id;

          if(!empty($modul['ModulTemplate'])) {
            $row->mt   = $modul['ModulTemplate'];
            @$row->tid = $r->Template;
          }
          $row->ol_info = $row->descr  . "<br /><br />"."<strong>" . $GLOBALS['config_vars']['MODULES_AUTHOR'] . "</strong><br />" . $row->autor . "<br /><em>" . $row->copyright . "</em>";

          array_push($modules, $row);

          $modul = '';
          $row   = '';
          $r     = '';
         }
       }
     }

     $d->close();
     $GLOBALS['tmpl']->assign("modules", $modules);
     $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("modules/modules.tpl"));
  }

  function deleteModule($id,$path='') {
    include(BASE_DIR . "/modules/$path/modul.php");
    @include_once(BASE_DIR . "/modules/$path/sql.php");

    foreach($modul_sql_deinstall as $deinstall) {
      $deinstall = str_replace("CPPREFIX", PREFIX, $deinstall);
      $GLOBALS['db']->Query($deinstall);
    }

    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_module WHERE Id = '$id'");
    reportLog($_SESSION["cp_uname"] . " - удалил модуль ($id)",'2','2');
    header("Location:index.php?do=modules&cp=" . SESSION);
    exit;
  }

  function updateModule($id, $reinstall='0', $path='') {
    include(BASE_DIR . "/modules/$id/modul.php");
    @include_once(BASE_DIR . "/modules/$id/sql.php");

    foreach($modul_sql_update as $update) {
      $update = str_replace("CPPREFIX", PREFIX, $update);
      $GLOBALS['db']->Query($update);
      reportLog($_SESSION["cp_uname"] . " - обновил модуль ($id)",'2','2');
    }
    header("Location:index.php?do=modules&cp=" . SESSION);
    exit;
  }

  function installModule($id, $reinstall='0', $path='') {
    include(BASE_DIR . "/modules/$id/modul.php");

    if($reinstall==1) {
      $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_module WHERE ModulPfad = '$id'");
      reportLog($_SESSION["cp_uname"] . " - удалил модуль ($id)",'2','2');
      $id = $path;
    }

    foreach($modul_sql_deinstall as $deinstall) {
      $deinstall = str_replace("CPPREFIX", PREFIX, $deinstall);
      $GLOBALS['db']->Query($deinstall);
    }

    foreach($modul_sql_install as $install) {
      $install = str_replace("CPPREFIX", PREFIX, $install);
      $GLOBALS['db']->Query($install);
    }

    $modul['ModulTemplate'] = (!empty($modul['ModulTemplate'])) ? $modul['ModulTemplate'] : "0";

    $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_module (
      Id,
      ModulName,
      Status,
      CpEngineTag,
      CpPHPTag,
      ModulFunktion,
      IstFunktion,
      ModulPfad,
      Version,
      Template
      ) VALUES (
      '',
      '" .$modul['ModulName']. "',
      '1',
      '" .$modul['CpEngineTag']. "',
      '" .$modul['CpPHPTag']. "',
      '" .$modul['ModulFunktion']. "',
      '" .$modul['IstFunktion']. "',
      '" .$modul['ModulPfad']. "',
      '" .$modul['ModulVersion']. "',
      '" .$modul['ModulTemplate']. "'
      )
    ");

    reportLog($_SESSION["cp_uname"] . " - установил модуль (".$modul['ModulName']. ")",'2','2');
    header("Location:index.php?do=modules&cp=" . SESSION);
    exit;
  }

  function editModule($id) {
    $content = "";
    $emsg = "";

    switch($_REQUEST['sub'])
    {
      case '' :
        $edit = true;
        $modulopen = BASE_DIR . "/modules/$id/modul.php";

        if(!@is_writeable($modulopen))
        {
          $edit = false;
          $emsg = $GLOBALS['config_vars']['EditFalseNW'];
        }

        if($edit)
        {
          if ($fd = @fopen($modulopen, "r"))
          {
            $content = @fread($fd, filesize($modulopen));
            fclose($fd);
          } else {
            $edit = false;
            $emsg = $GLOBALS['config_vars']['EditFalseOpen'];
          }
        }
        $GLOBALS['tmpl']->assign("data", $content);
        $GLOBALS['tmpl']->assign("emsg", $emsg);
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("modules/form.tpl"));
      break;

      case 'save':
        $fd = @fopen(BASE_DIR . "/modules/".$_REQUEST['path']. "/modul.php", "w");
        $data = $_REQUEST['data'];
        $data = str_replace("&doc=", "&amp;doc=", $data);
        @fwrite($fd, stripslashes($data));
        @fclose($fd);
        reportLog($_SESSION["cp_uname"] . " - изменил модуль (".$_REQUEST['path']. ")",'2','2');
        echo "<script>window.opener.location.reload(); self.close();</script>";
      break;
    }
  }

  function OnOff($id) {
    $sql = $GLOBALS['db']->Query("SELECT Status FROM " . PREFIX . "_module WHERE Id = '$id'");
    $row = $sql->fetchrow();

    $GLOBALS['db']->Query("UPDATE " . PREFIX . "_module SET Status = '" . (($row->Status == 0) ? 1 : 0) . "' WHERE Id = '$id'");
    header("Location:index.php?do=modules&cp=" . SESSION);
    exit;
  }
}
?>