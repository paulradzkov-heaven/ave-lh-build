<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class Counter {

  var $UserAgent    = "не определен";
  var $AOL          = false;
  var $_daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
  var $_limit       = 25;

  function getLastMonthDays() {
    return $this->_daysInMonth[date("m")-2];
  }

  function InsertNew($id) {

    $expire  = mktime(23,59,59,date("m"),date("d"), date("Y"));
    $referer = (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != "") ? $_SERVER['HTTP_REFERER'] : "";
    while (ereg('%([0-9A-F]{2})',$referer)) {
      $val=ereg_replace('.*%([0-9A-F]{2}).*','\1',$referer);
      $newval=chr(hexdec($val));
      $referer=str_replace('%'.$val,$newval,$referer);
    }
    $remote  = (isset($_SERVER['REMOTE_ADDR'])  && $_SERVER['REMOTE_ADDR']  != "") ? $_SERVER['REMOTE_ADDR'] : "";

    $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_counter_info
      WHERE Ben_Ip = '" . $_SERVER['REMOTE_ADDR'] . "'
      AND Datum_Expire > " . time() . "
      AND CId = '" . (int)$id. "'");
    $num = $sql->numrows();
    $sql->Close();

    if($num < 1 && $remote != "") {
      $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_counter_info (
        Id,
        CId,
        Ben_Ip,
        Ben_Os,
        Ben_Browser,
        Ben_Referer,
        Datum,
        Datum_Unix,
        Datum_Expire
        ) VALUES (
        '',
        '$id',
        '" . $remote . "',
        '" . $this->browser("Platform") . "',
        '" . $this->browser("Name") . " " . $this->browser("Version") . "',
        '" . $referer . "',
        NOW(),
        '" . time() . "',
        '" . $expire . "'
        )");
    }
  }

  function cpMktimeStart($day=0,$month=0,$year=0) {$t = mktime(0,0,1,date("m")+$month, date("d")+$day, date("Y")+$year);return $t;}

  function cpMktimeEnd($day=0,$month=0,$year=0) {$t = mktime(23,59,59,date("m")+$month, date("d")+$day, date("Y")+$year);return $t;}

  function cpMktimeStartLm($day=0,$month=0) {$t = mktime(0,0,1,date("m")-1, $day, date("Y"));return $t;}

  function cpMktimeEndLm($day=0,$month=0) {$t = mktime(23,59,59,date("m")-1, $day, date("Y"));return $t;}

  function cpMktimeStartLy() {$t = mktime(0,0,1,1,1, date("Y")-1);return $t;}

  function cpMktimeEndLy() {$t = mktime(23,59,59,12,31, date("Y")-1);return $t;}

  function showReferer($tpl_dir,$lang_file,$id) {

    $GLOBALS['tmpl']->config_load($lang_file, "admin");
    $config_vars = $GLOBALS['tmpl']->get_config_vars();

    $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_counter_info WHERE CId = '$id'");
    $num = $sql->numrows();

    $limit = $this->_limit;
    $seiten = ceil($num / $limit);
    $start = prepage() * $limit - $limit;

    $sort = " ORDER BY Datum_Unix DESC";
    $sort_navi = "";

    if(isset($_REQUEST['sort']) && $_REQUEST['sort'] != "") {
      switch($_REQUEST['sort']) {

        case 'zeit_desc' :
          $sort = " ORDER BY Datum_Unix DESC";
          $sort_navi = "&amp;sort=zeit_desc";
        break;

        case 'zeit_asc' :
          $sort = " ORDER BY Datum_Unix ASC";
          $sort_navi = "&amp;sort=zeit_asc";
        break;

        case 'ip_desc' :
          $sort = " ORDER BY Ben_Ip DESC";
          $sort_navi = "&amp;sort=ip_desc";
        break;

        case 'ip_asc' :
          $sort = " ORDER BY Ben_Ip ASC";
          $sort_navi = "&amp;sort=ip_asc";
        break;

        case 'referer_desc' :
          $sort = " ORDER BY Ben_Referer DESC";
          $sort_navi = "&amp;sort=referer_desc";
        break;

        case 'referer_asc' :
          $sort = " ORDER BY Ben_Referer ASC";
          $sort_navi = "&amp;sort=referer_asc";
        break;

        case 'os_desc' :
          $sort = " ORDER BY Ben_Os DESC";
          $sort_navi = "&amp;sort=os_desc";
        break;

        case 'os_asc' :
          $sort = " ORDER BY Ben_Os ASC";
          $sort_navi = "&amp;sort=os_asc";
        break;

        case 'browser_desc' :
          $sort = " ORDER BY Ben_Browser DESC";
          $sort_navi = "&amp;sort=browser_desc";
        break;

        case 'browser_asc' :
          $sort = " ORDER BY Ben_Browser ASC";
          $sort_navi = "&amp;sort=browser_asc";
        break;

      }
    }

    $items = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_counter_info WHERE CId = '$id' $sort LIMIT $start,$limit");

    while($row = $sql->fetchrow()) {
      array_push($items, $row);
    }

    $page_nav = pagenav($seiten, prepage()," <a class=\"pnav\" href=\"index.php?do=modules&action=modedit&mod=counter&moduleaction=view_referer&id=" . $_REQUEST['id'] . "&cp=" . SESSION . "&pop=1&page={s}$sort_navi\">{t}</a> ");
    if($num > $limit) $GLOBALS['tmpl']->assign("page_nav", $page_nav);

    $GLOBALS['tmpl']->assign("items", $items);
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_entries.tpl"));
  }

  function showCounter($tpl_dir,$lang_file) {

    $GLOBALS['tmpl']->config_load($lang_file, "admin");
    $config_vars = $GLOBALS['tmpl']->get_config_vars();

    $items = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_counter ORDER BY Id ASC");

    while($row = $sql->fetchrow()) {
      $sql_all = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_counter_info WHERE CId='$row->Id'");
      $num_all = $sql_all->numrows();

      $sql_today = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_counter_info WHERE CId='$row->Id' AND (Datum_Unix BETWEEN " . $this->cpMktimeStart(). " AND " . $this->cpMktimeEnd(). ")");
      $num_today = $sql_today->numrows();

      $sql_yest = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_counter_info WHERE CId='$row->Id' AND (Datum_Unix BETWEEN " . $this->cpMktimeStart(-1). " AND " . $this->cpMktimeEnd(-1). ")");
      $num_yest = $sql_yest->numrows();

      $sql_lastm = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_counter_info WHERE CId='$row->Id' AND (Datum_Unix BETWEEN " . $this->cpMktimeStartLm(1,-1). " AND " . $this->cpMktimeEndLm($this->getLastMonthDays(),-1). ")");
      $num_lastm = $sql_lastm->numrows();

      $sql_lasty = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_counter_info WHERE CId='$row->Id' AND (Datum_Unix BETWEEN " . $this->cpMktimeStartLy() . " AND " . $this->cpMktimeEndLy() . ")");
      $num_lasty = $sql_lasty->numrows();

      $row->all   = $num_all;
      $row->yest  = $num_yest;
      $row->today = $num_today;
      $row->lastm = $num_lastm;
      $row->lasty = $num_lasty;

      array_push($items, $row);
    }

    $GLOBALS['tmpl']->assign("items", $items);
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_counter.tpl"));
  }

  function newCounter() {
    $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_counter (Id, CName) VALUES ('','" . addslashes(htmlspecialchars($_POST['CName'])) . "')");
    header("Location:index.php?do=modules&action=modedit&mod=counter&moduleaction=1&cp=" . SESSION);
    exit;
  }

  function quickSave() {

    foreach($_POST['del'] as $id => $del) {
      $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_counter WHERE Id = '$id'");
      $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_counter_info WHERE CId = '$id'");
    }

    foreach($_POST['CName'] as $id => $CName) {
      $GLOBALS['db']->Query("UPDATE  " . PREFIX . "_modul_counter SET CName='" . $_POST['CName'][$id]. "' WHERE Id = '$id'");
    }

    header("Location:index.php?do=modules&action=modedit&mod=counter&moduleaction=1&cp=" . SESSION);
    exit;
  }

  function browser($c_action) {
    $agent          = $_SERVER['HTTP_USER_AGENT'];
    $bd['platform'] = "не определена";
    $bd['browser']  = "Не определен";
    $bd['version']  = "не определена";
    $this->UserAgent = $agent;

    if (eregi("win", $agent))
    $bd['platform'] = "Windows";
    elseif (eregi("mac", $agent))
    $bd['platform'] = "MacIntosh";
    elseif (eregi("linux", $agent))
    $bd['platform'] = "Linux";
    elseif (eregi("OS/2", $agent))
    $bd['platform'] = "OS/2";
    elseif (eregi("BeOS", $agent))
    $bd['platform'] = "BeOS";

    if (eregi("opera",$agent)) {
      $val = stristr($agent, "opera");

      if (eregi("/", $val)) {
        $val = explode("/",$val);
        $bd['browser'] = $val[0];
        $val = explode(" ",$val[1]);
        $bd['version'] = $val[0];

      } else {

        $val = explode(" ",stristr($val,"opera"));
        $bd['browser'] = $val[0];
        $bd['version'] = $val[1];
      }

    } elseif(eregi("webtv",$agent)) {
      $val = explode("/",stristr($agent,"webtv"));
      $bd['browser'] = $val[0];
      $bd['version'] = $val[1];

    } elseif(eregi("microsoft internet explorer", $agent)) {
      $bd['browser'] = "MSIE";
      $bd['version'] = "1.0";
      $var = stristr($agent, "/");

      if (ereg("308|425|426|474|0b1", $var)) {
        $bd['version'] = "1.5";
      }
    } elseif(eregi("msie",$agent) && !eregi("opera",$agent)) {
      $val = explode(" ",stristr($agent,"msie"));
      $bd['browser'] = $val[0];
      $bd['version'] = $val[1];

    } elseif(eregi("mspie",$agent) || eregi('pocket', $agent)) {
      $val = explode(" ",stristr($agent,"mspie"));
      $bd['browser'] = "MSPIE";
      $bd['platform'] = "WindowsCE";
      if (eregi("mspie", $agent))
      $bd['version'] = $val[1];
      else {
        $val = explode("/",$agent);
        $bd['version'] = $val[1];
      }

    } elseif(eregi("galeon",$agent)) {
      $val = explode(" ",stristr($agent,"galeon"));
      $val = explode("/",$val[0]);
      $bd['browser'] = $val[0];
      $bd['version'] = $val[1];

    } elseif(eregi("Konqueror",$agent)) {
      $val = explode(" ",stristr($agent,"Konqueror"));
      $val = explode("/",$val[0]);
      $bd['browser'] = $val[0];
      $bd['version'] = $val[1];

    } elseif(eregi("icab",$agent)) {
      $val = explode(" ",stristr($agent,"icab"));
      $bd['browser'] = $val[0];
      $bd['version'] = $val[1];

    } elseif(eregi("omniweb",$agent)) {
      $val = explode("/",stristr($agent,"omniweb"));
      $bd['browser'] = $val[0];
      $bd['version'] = $val[1];

    } elseif(eregi("Phoenix", $agent)) {
      $bd['browser'] = "Phoenix";
      $val = explode("/", stristr($agent,"Phoenix/"));
      $bd['version'] = $val[1];

    } elseif(eregi("firebird", $agent)) {
      $bd['browser']="Firebird";
      $val = stristr($agent, "Firebird");
      $val = explode("/",$val);
      $bd['version'] = $val[1];

    } elseif(eregi("Firefox", $agent)) {
      $bd['browser']="Firefox";
      $val = stristr($agent, "Firefox");
      $val = explode("/",$val);
      $bd['version'] = $val[1];

    } elseif(eregi("mozilla",$agent) && eregi("rv:[0-9].[0-9][a-b]",$agent) && !eregi("netscape",$agent)) {
      $bd['browser'] = "Mozilla";
      $val = explode(" ",stristr($agent,"rv:"));
      eregi("rv:[0-9].[0-9][a-b]",$agent,$val);
      $bd['version'] = str_replace("rv:","",$val[0]);

    } elseif(eregi("mozilla",$agent) && eregi("rv:[0-9]\.[0-9]",$agent) && !eregi("netscape",$agent)) {
      $bd['browser'] = "Mozilla";
      $val = explode(" ",stristr($agent,"rv:"));
      eregi("rv:[0-9]\.[0-9]\.[0-9]",$agent,$val);
      $bd['version'] = str_replace("rv:","",$val[0]);

    } elseif(eregi("libwww", $agent)) {
      if (eregi("amaya", $agent)) {
        $val = explode("/",stristr($agent,"amaya"));
        $bd['browser'] = "Amaya";
        $val = explode(" ", $val[1]);
        $bd['version'] = $val[0];
      } else {
        $val = explode("/",$agent);
        $bd['browser'] = "Lynx";
        $bd['version'] = $val[1];
      }

    } elseif(eregi("safari", $agent)) {
      $bd['browser'] = "Safari";
      $bd['version'] = "";

    } elseif(eregi("netscape",$agent)) {
      $val = explode(" ",stristr($agent,"netscape"));
      $val = explode("/",$val[0]);
      $bd['browser'] = $val[0];
      $bd['version'] = $val[1];
    } elseif(eregi("mozilla",$agent) && !eregi("rv:[0-9]\.[0-9]\.[0-9]",$agent)) {
      $val = explode(" ",stristr($agent,"mozilla"));
      $val = explode("/",$val[0]);
      $bd['browser'] = "Netscape";
      $bd['version'] = $val[1];
    }

    $bd['browser'] = ereg_replace("[^a-z,A-Z]", "", $bd['browser']);
    $bd['version'] = ereg_replace("[^0-9,.,a-z,A-Z]", "", $bd['version']);

    if (eregi("AOL", $agent)) {
      $var = stristr($agent, "AOL");
      $var = explode(" ", $var);
      $bd['aol'] = ereg_replace("[^0-9,.,a-z,A-Z]", "", $var[1]);
    }

    if($c_action == 'Name') return $bd['browser'];
    if($c_action == 'Version') return $bd['version'];
    if($c_action == 'Platform') return $bd['platform'];

  }

  function showStat($tpl_dir,$lang_file,$id) {

    $GLOBALS['tmpl']->config_load($lang_file, "user");
    $config_vars = $GLOBALS['tmpl']->get_config_vars();

    $items = array();

    $sql = $GLOBALS['db']->Query("SELECT COUNT(*) AS nums FROM " . PREFIX . "_modul_counter_info WHERE CId='$id'");
    $row = $sql->fetchrow();
    $items['all'] = $row->nums;

    $sql = $GLOBALS['db']->Query("SELECT COUNT(*) AS nums FROM " . PREFIX . "_modul_counter_info WHERE CId='$id' AND (Datum_Unix BETWEEN " . $this->cpMktimeStart(). " AND " . $this->cpMktimeEnd(). ")");
    $row = $sql->fetchrow();
    $items['today'] = $row->nums;

    $sql = $GLOBALS['db']->Query("SELECT COUNT(*) AS nums FROM " . PREFIX . "_modul_counter_info WHERE CId='$id' AND (Datum_Unix BETWEEN " . $this->cpMktimeStart(-1). " AND " . $this->cpMktimeEnd(-1). ")");
    $row = $sql->fetchrow();
    $items['yest'] = $row->nums;

    $sql = $GLOBALS['db']->Query("SELECT COUNT(*) AS nums FROM " . PREFIX . "_modul_counter_info WHERE CId='$id' AND (Datum_Unix BETWEEN " . $this->cpMktimeStartLm(1,-1). " AND " . $this->cpMktimeEndLm($this->getLastMonthDays(),-1). ")");
    $row = $sql->fetchrow();
    $items['lastm'] = $row->nums;

    $sql = $GLOBALS['db']->Query("SELECT COUNT(*) AS nums FROM " . PREFIX . "_modul_counter_info WHERE CId='$id' AND (Datum_Unix BETWEEN " . $this->cpMktimeStartLy() . " AND " . $this->cpMktimeEndLy() . ")");
    $row = $sql->fetchrow();
    $items['lasty'] = $row->nums;

    $GLOBALS['tmpl']->assign("items", $items);
    $GLOBALS['tmpl']->display($tpl_dir . 'show_stat-'.$id.'.tpl');
  }

}
?>