<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class sqlDump {

  function getDump($file) {
    $dattype = 'text/plain';
    header('Content-Type: ' . $dattype);
    header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Content-Disposition: attachment; filename='.$_SERVER['SERVER_NAME']. '_' ."DB_BackUP" .  '_'.date('d.m.y').'.sql');

    if ($extra != 1) header('Content-Length: ' . strlen($file));
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');

    echo $file;

    reportLog($_SESSION['cp_uname'] . ' - выполнил резервное копирование базы данных.','2','2');
    exit;
  }

  function writeDump() {

    $arr = $_REQUEST['ta'];

    while (list($key, $val) = each ($arr)) {
      $doit = true;

      if(!ereg("^" . preg_quote(PREFIX), $val)) {
        $doit = false;
      }

      if ($doit) {
        $dump .= ($this->TableDef($val));
        $felder = $this->Felder($val);
        $sql = "SELECT * FROM " . $val . "";
        $zeilen = $GLOBALS['db']->Query($sql);

        while ($zrow = $zeilen->fetcharray()) {
          $def = "";
          $cnt = "";

          for ($i=1; $i<=count($felder); $i++) {
            $def .= ", " . $felder[$i];
            $cnt .= ", '" . str_replace('\n', '\n', addslashes($zrow[$felder[$i]])) . "'";
          }
          $def = substr($def, 2);
          $cnt = substr($cnt, 2);
          $dump .= "INSERT INTO " . $val . " (" . $def . ") VALUES (" . str_replace('\n','\n',$cnt) . ");#####systemdump#####\n";
        }
        $dump .= "\n";

        $zeilen->Close();
      }
    }

    return $dump;
  }

  function TableDef($table) {
    global $db,$verbindung;

    $result = $db->Query("SHOW FIELDS FROM $table");

    $felder = array();
    $prim = array();

    while ($row = $result->fetchrow()) {
      if (!$row->Extra == '') {
        $extra = ' ' . $row->Extra;
      } else {
        $extra = '';
      }
      if ($row->Default == '') {
        $default = ' NULL';
      } else {
        $default = " '" . $row->Default . "'";
      }
      if ($row->Key == 'PRI') {
        $prim_array[] = $row->Field;
      }

      $felder[count($felder)+1] = "  " . $row->Field . " " . $row->Type . " DEFAULT$default$extra";
    }
    if($prim_array != ''){
      $prim[count($prim)+1] = '  PRIMARY KEY (' . @implode(',', $prim_array) . ')';
      }

    $res .= "DROP TABLE IF EXISTS ".$table.";#####systemdump#####\n";
    $res .= "CREATE TABLE $table (\n";
    $frak = array_merge($felder, $prim);
    for ($i=0; $i<=count($frak)-1; $i++) {
      $res .= $frak[$i];
      if ($i == count($frak)-1) {
        $res .= "\n";
      } else {
        $res .= ",\n";
      }
    }
    $res .= ") TYPE=MyISAM;#####systemdump#####\n\n";
    $result->Close();
    return $res;
  }

  function Felder($table) {

    global $db;

    $res = array();
    $result = $db->Query("SHOW FIELDS FROM $table");
    while ($row = $result->fetchrow()) {
      $res[count($res)+1] = $row->Field;
    }
    $result->Close();
    return $res;
  }

  function dbRestore($tempdir) {
    $insert = false;
    if($_FILES['file']['size']!=0) {
      $fupload_name = $_FILES['file']['name'];
      $end = substr($fupload_name,-3);
      if($end == "sql") {
        if(!@move_uploaded_file($_FILES['file']['tmp_name'], $tempdir . $fupload_name)) die ("Ошибка при загрузке файла!");
        @chmod($target . $fupload_name,0777);
        $insert = true;
      } else {
        $GLOBALS['tmpl']->assign('msg', '<span style="color:red">'.$GLOBALS['config_vars']['MAIN_SQL_FILE_ERROR'].'</span>');
      }
    }


    if($insert) {
      if($fupload_name != '' && file_exists($tempdir . $fupload_name)) {

      $handle = @fopen($tempdir . $fupload_name, 'r');
      $db_q = @fread($handle, filesize($tempdir . $fupload_name));
      fclose($handle);

      $m_ok = 0;
      $m_fail = 0;

      $ar = @explode('#####systemdump#####', $db_q);

      while (@list($key,$val) = @each($ar)) {
        if (chop($val) != '') {
          $q = str_replace('\n','',$val);
          $q = $q . ';';
          if ($GLOBALS['db']->Query($q)) {
            $m_ok++;
          } else {
            $m_fail++;
          }
        }
      }


      @unlink($tempdir . $fupload_name);
      $msg = $GLOBALS['config_vars']['MAIN_RESTORE_OK'];
      $msg .= "<br /><br />".$GLOBALS['config_vars']['MAIN_TABLE_SUCC']."<span style=\"color:green\">$m_ok</span><br/> " . $GLOBALS['config_vars']['MAIN_TABLE_ERROR'] . " <span style=\"color:red\">$m_fail</span><br />";
      $GLOBALS['tmpl']->assign('msg', $msg);

      } else {

        $GLOBALS['tmpl']->assign('msg', '<span style="color:red">Ошибка! Импорт базы данных не выполнен, т.к. отсутсвует файл дампа или он поврежден.</span>');
      }
    }
    reportLog($_SESSION["cp_uname"] . " - выполнил востановление базы данных из резервной копии",'2','2');
  }

  function optimizeRep() {

    $arr = $_REQUEST['ta'];
    reset ($arr);

    while (list($key, $val) = each ($arr)) {
      $tables .= ", $val";
    }

    $tables = substr($tables, 1);
    if ($_REQUEST['whattodo'] == 'optimize') {
      $query = 'OPTIMIZE TABLE  ';
      reportLog($_SESSION['cp_uname'] . ' - выполнил оптимизацию базы данных','2','2');
    } else {
      $query = "REPAIR TABLE ";
      reportLog($_SESSION["cp_uname"] . ' - выполнил востановление таблиц базы данных','2','2');
    }
    $query .= $tables;
    $GLOBALS['db']->Query($query);
    $tabellen = $this->showTables();
    return $tabellen;
  }

  function showTables() {
    $tabellen = '';
    $sql = $GLOBALS['db']->Query("SHOW TABLES");
    while ($row = $sql->FetchArray()) {
      $titel = $row[0];
      if (substr($titel, 0, strlen(PREFIX)) == PREFIX) {
        $tabellen .= "<option value=\"$titel\" selected>$titel</option>\n";
      }
    }
    $sql->Close();
    return $tabellen;
  }
}
?>