<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class Logs {

  var $_limit = 15;

  function showLogs() {
    $logs = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_log ORDER BY Id DESC");

    while($row = $sql->fetchrow()) {
      array_push($logs, $row);
    }
    $GLOBALS['tmpl']->assign("logs", $logs);
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("logs/logs.tpl"));
  }

  function deleteLogs() {
    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_log");
    $GLOBALS['db']->Query("ALTER TABLE " . PREFIX . "_log PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =1");
    header("Location:index.php?do=logs&cp=" . SESSION);
  }

  function logExport($extra="") {
    $datstring = "";
    $dattype = "text/csv";
    $datname = "system_log_" . date("dmyhis",time()) . ".csv";

    $separator = ";";
    $enclosed = "\"";
    $cutter = "\r\n";

    $cutter  = str_replace('\\r', "\015", $cutter);
    $cutter  = str_replace('\\n', "\012", $cutter);
    $cutter  = str_replace('\\t', "\011", $cutter);

    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_log ORDER BY Id DESC");
    $fieldcount = $sql->NumFields();

    for ($i = 0; $i < $fieldcount; $i++) {
      $datstring .= $enclosed . $sql->FieldName($i) . $enclosed . $separator;
    }

    $datstring .= "".$cutter. "";

    while($row = $sql->fetchrow()) {

      foreach($row as $key=>$val)  {
        $val = str_replace("\r\n","\n",$val);
        $val = ($key=='Zeit') ? date("d-m-Y, H:i:s", $row->Zeit) : $val;
        $datstring .= ($val=='') ? $separator : $enclosed . stripslashes($val) . $enclosed . $separator;
      }

      $datstring .= $cutter;
    }

    $dattype = 'text/csv';
    header('Content-Type: ' . $dattype);
    header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Content-Disposition: attachment; filename="' . $datname . '"');
    if ($extra != 1) header('Content-Length: ' . strlen($datstring));
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    echo $datstring;
    exit;
  }
}
?>