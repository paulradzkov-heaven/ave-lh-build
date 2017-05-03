<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function monthFormatReplace($month)
{
	switch ($month)
		{
		case 'January': $month = 'января'; break;
		case 'February': $month = 'февраля'; break;
		case 'March': $month = 'марта'; break;
		case 'April': $month = 'апреля'; break;
		case 'May': $month = 'мая'; break;
		case 'June': $month = 'июня'; break;
		case 'July': $month = 'июля'; break;
		case 'August': $month = 'августа'; break;
		case 'September': $month = 'сентября'; break;
		case 'October': $month = 'октября'; break;
		case 'November': $month = 'ноября'; break;
		case 'December': $month = 'декабря'; break;
		}
	return $month;
}

function dayFormatReplace($day)
{
	switch ($day)
		{
		case 'Sunday': $day = 'Воскресенье'; break;
		case 'Monday': $day = 'Понедельник'; break;
		case 'Tuesday': $day = 'Вторник'; break;
		case 'Wednesday': $day = 'Среда'; break;
		case 'Thursday': $day = 'Четверг'; break;
		case 'Friday': $day = 'Пятница'; break;
		case 'Saturday': $day = 'Суббота'; break;
		}
	return $day;
}

class Settings {

  var $_climit = 25;

  function clearCode($code) {
    $code = str_replace("<", "&lt;", $code);
    $code = str_replace(">", "&gt;", $code);
    $code = eregi_replace("<b>", "<strong>", $code);
    $code = eregi_replace("</b>", "</strong>", $code);
    $code = eregi_replace("<i>", "<em>", $code);
    $code = eregi_replace("</i>", "</em>", $code);
    $code = eregi_replace("<br>", "<br />", $code);
    $code = eregi_replace("<br/>", "<br />", $code);
    return $code;
  }

  function displaySettings($config_vars) {
    switch($_REQUEST['sub']) {

      case 'save':
        $sn = (isset($_REQUEST['Seiten_Name']) && $_REQUEST['Seiten_Name'] != "") ? "Seiten_Name = '$_REQUEST[Seiten_Name]'," : "";
        $mp     = (isset($_REQUEST['Mail_Port']) && $_REQUEST['Mail_Port'] != "") ? "Mail_Port = '$_REQUEST[Mail_Port]'," : "";
        $mh     = (isset($_REQUEST['Mail_Host']) && $_REQUEST['Mail_Host'] != "") ? "Mail_Host   = '$_REQUEST[Mail_Host]'," : "";
        $muname = (isset($_REQUEST['Mail_Username']) && $_REQUEST['Mail_Username'] != "") ? "Mail_Username  = '$_REQUEST[Mail_Username]', " : "";
        $mpass  = (isset($_REQUEST['Mail_Passwort']) && $_REQUEST['Mail_Passwort'] != "") ? "Mail_Passwort   = '$_REQUEST[Mail_Passwort]'," : "";
        $msmp   = (isset($_REQUEST['Mail_Sendmailpfad']) && $_REQUEST['Mail_Sendmailpfad'] != "") ? "Mail_Sendmailpfad  = '$_REQUEST[Mail_Sendmailpfad]', " : "";
        $mn = (isset($_REQUEST['Mail_Name']) && $_REQUEST['Mail_Name'] != "") ? "Mail_Name = '$_REQUEST[Mail_Name]'," : "";
        $ma = (isset($_REQUEST['Mail_Absender']) && $_REQUEST['Mail_Absender'] != "") ? "Mail_Absender = '$_REQUEST[Mail_Absender]'," : "";
        $ep = (isset($_REQUEST['Fehlerseite']) && $_REQUEST['Fehlerseite'] != "") ? "Fehlerseite = '$_REQUEST[Fehlerseite]'," : "";

        $sql = "UPDATE " . PREFIX . "_settings SET
          $sn
          DefLand = '$_REQUEST[DefLand]',
          Mail_Typ  = '$_REQUEST[Mail_Typ]',
          Mail_Content  = '$_REQUEST[Mail_Content]',
          $mp
          $mh
          $muname
          $mpass
          $msmp
          Mail_WordWrap  = '$_REQUEST[Mail_WordWrap]',
          $ma
          $mn
          Mail_Text_NeuReg   = '" . $_REQUEST['Mail_Text_NeuReg'] . "',
          Mail_Text_Fuss = '" . $_REQUEST['Mail_Text_Fuss'] . "',
          $ep
          FehlerLeserechte  = '" . $_REQUEST['FehlerLeserechte'] . "',
          SeiteWeiter  = '" . $this->clearCode($_REQUEST['SeiteWeiter']) . "',
          SeiteZurueck     = '" . $this->clearCode($_REQUEST['SeiteZurueck']) . "',
		  Zeit_Format  = '" . $_REQUEST['Zeit_Format'] . "',
          NaviSeiten  = '" . $this->clearCode($_REQUEST['NaviSeiten']) . "'
        WHERE Id = 1";

        $GLOBALS['db']->Query($sql);

        reportLog($_SESSION["cp_uname"] . " - изменил общие настройки системы",'2','2');
        header("Location:index.php?do=settings&cp=" . SESSION . "&saved=1");
        exit;
      break;

      case '':
        $globals = new Globals;
        $GLOBALS['tmpl']->assign("available_countries", $GLOBALS['globals']->fetchCountries());

		$day = dayFormatReplace(date('l'));
	    $month = monthFormatReplace(date('F'));

	    $dateFormat = array(array('format' => 'd.m.Y', 'view' => date('d.m.Y')),
                      array('format' => 'd.m.Y, H:i', 'view' => date('d.m.Y, H:i')),
                      array('format' => 'd F Y', 'view' => date('d '.$month.' Y')),
                      array('format' => 'd F Y, H:i', 'view' => date('d '.$month.' Y, H:i')),
                      array('format' => 'l, d.m.Y', 'view' => date($day.', d.m.Y')),
                      array('format' => 'l, d.m.Y (H:i)', 'view' => date($day.', d.m.Y (H:i)')),
                      array('format' => 'l, d F Y', 'view' => date($day.', d '.$month.' Y')),
                      array('format' => 'l, d F Y (H:i)', 'view' => date($day.', d '.$month.' Y (H:i)')));

	    $GLOBALS['tmpl']->assign("dateFormat", $dateFormat);
        $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_settings WHERE Id = '1'");
        $row = $sql->fetcharray();
        $GLOBALS['tmpl']->assign("row", $row);
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("settings/settings_main.tpl"));
      break;


      case 'countries':

        if(isset($_REQUEST['save']) && $_REQUEST['save']==1) {

          foreach($_POST['LandName'] as $id => $LandName) {

              $GLOBALS['db']->Query("UPDATE " . PREFIX . "_countries SET
                LandName = '" .$_POST['LandName'][$id]. "',
                Aktiv = '" .$_POST['Aktiv'][$id]. "',
                IstEU = '" .$_POST['IstEU'][$id]. "'
              WHERE Id = '$id'");
            }

          header("Location:index.php?do=settings&sub=countries");
          exit;
        }


        $laender = array();

        $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_countries ORDER BY Aktiv ASC");
        $num = $sql->numrows();
        $sql->Close();

        $limit  = $this->_climit;
        $seiten = ceil($num / $limit);
        $start  = prepage() * $limit - $limit;

        $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_countries ORDER BY Aktiv ASC LIMIT $start,$limit");

        while($row = $sql->fetcharray()) {
          array_push($laender, $row);
        }

        $sql->Close();

        $page_nav = pagenav($seiten, prepage()," <a class=\"pnav\" href=\"index.php?do=settings&sub=countries&page={s}&amp;cp=".SESSION. "\">{t}</a> ");
        if($num > $limit) $GLOBALS['tmpl']->assign("page_nav", $page_nav);

        $GLOBALS['tmpl']->assign("laender", $laender);
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("settings/settings_countries.tpl"));
      break;

      case 'clrcache':
        $GLOBALS['tmpl']->cache_dir = BASE_DIR . '/cache/';
        $GLOBALS['tmpl']->clear_all_cache();
        $GLOBALS['tmpl']->clear_compiled_tpl();

        $filename = $GLOBALS[tmpl]->compile_dir . '/.htaccess';
        if (!file_exists($GLOBALS[tmpl]->compile_dir . '/.htaccess')){
          $fp = @fopen($filename,'w');
          if ($fp){
            fputs($fp,'Deny from all');
            fclose($fp);
          }
        }

        reportLog($_SESSION['cp_uname'] . " - очистил кэш",'2','2');

        header("Location:index.php?do=settings&cp=" . SESSION);
        exit;
      break;
    }
  }
}
?>