<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class Search {

  var $_limit        = 15;
  var $_adminlimit   = 15;
  var $_highlight    = 1;
  var $_allowed_tags = '';

  function fetchForm($tpl_dir,$lang_file) {
    $GLOBALS['tmpl']->config_load($lang_file);
    $config_vars = $GLOBALS['tmpl']->get_config_vars();
    $GLOBALS['tmpl']->assign("config_vars", $config_vars);
    $GLOBALS['tmpl']->display($tpl_dir . "form.tpl");
  }

  function getContent($id, $tosearch = '') {
    $query = (isset($_GET['query']) && $_GET['query'] != '') ? $_GET['query'] : '';
    $query = ereg_replace('([^ ;+_A-Za-zА-Яа-яЁё0-9-])', '', $query);

    // Двойные пробелы в один
    $query = ereg_replace(' +', ' ', $_GET['query']);

    // Удаляем пробел в начале и конце
    $query = trim($query);

     if (!strpos($query, ' '))
  {
  // Пропускаем через Стеммер Портера
  $stemmer = new Lingua_Stem_Ru();
  $query = $stemmer->stem_word($query);
  }

    $sqls = $GLOBALS['db']->Query("SELECT Inhalt FROM " . PREFIX . "_document_fields WHERE DokumentId = '$id' AND Inhalt like  '%{$query}%' AND (Inhalt NOT LIKE '[cp:replacement]%%')");
    $rows = $sqls->fetchrow();
    $sqls->Close();
    return @$rows->Inhalt;
  }

  function cp_specialchars($string) {
    $string = str_replace ( '"', '&quot;', $string );
    $string = urldecode($string);
    return $string;
  }

  function getSearchResults($tpl_dir, $lang_file) {
    $GLOBALS['tmpl']->config_load($lang_file);
    $config_vars = $GLOBALS['tmpl']->get_config_vars();
    $GLOBALS['tmpl']->assign('config_vars', $config_vars);

    define('MODULE_SITE', $config_vars['SEARCH_RESULTS']);

    $find = true;

    // Двойные пробелы в один
    $query = ereg_replace(' +', ' ', $_GET['query']);

    // Удаляем пробел в начале и конце
    $query = trim($query);

    $query = (isset($query) && $query != '' && $query != ' ') ? $query : '';
    $query = ereg_replace('([^ +_A-Za-zА-Яа-яЁё0-9-])', '', $query);

    if(strlen($query) < 3)
  {
  $find = false;
  }
    else
  {
  if (!strpos($query, ' '))
    {
    // Пропускаем через Стеммер Портера
    $stemmer = new Lingua_Stem_Ru();
    $query = $stemmer->stem_word($query);
    }
  }

    if($find == true) {

      $sw  = strtolower($query);
      $sql = $GLOBALS['db']->Query("SELECT Suchbegriff FROM " . PREFIX . "_modul_search WHERE Suchbegriff = '$sw'");
      $num = $sql->numrows();
      $sql->Close();

      if($num < 1) {
        $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_search (Id,Suchbegriff,Anzahl) VALUES ('','$sw','1')");
      } else {
        $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_search SET Anzahl=Anzahl+1 WHERE Suchbegriff = '$sw'");
      }


      $Kette = @explode(' ', $query);

      $ex_Inhalt    = '';
      $ex_Zeit      = '';
      $first        = '';
      $ex_Titel_not = '';
      $request      = '';
      $ex_rub       = '';
      $ex_docstatus = '';

      $OR_AND = (isset($_REQUEST['or']) && $_REQUEST['or'] == 1) ? ' OR ' : ' AND ';

      foreach($Kette as $Suche) {

        $Und = @explode(' +', $Suche);
        foreach($Und as $UndWort) {
          if(strpos($UndWort, '+') !== false)
            $ex_Inhalt .= " $OR_AND ( (Inhalt like '%" . substr($UndWort, 1) . "%') OR (Inhalt like '%" . $this->cp_specialchars(substr($UndWort, 1)) . "%') )";
        }

        $UndNicht = @explode(' -', $Suche);

        foreach ($UndNicht as $UndNichtWort) {

          if(strpos($UndNichtWort, '-') !== false)
            $ex_Inhalt .= " $OR_AND ( (Inhalt not like '%" . substr($UndNichtWort,1) . "%') OR (Inhalt not like '%" . $this->cp_specialchars(substr($UndNichtWort,1)) . "%') )";
        }


        $Start = explode(' +', $query);
        if(strpos($Start[0], ' -') !== false)
          $Start = explode(' -', $query);

        $Start = $Start[0];
      }

      $ex_Inhalt = " WHERE ( (Inhalt like '%" . $Start . "%') OR (Inhalt like '%" . $this->cp_specialchars($Start) . "%') ) $ex_Inhalt ";
      $nav_Titel = "&QueryTitel=" . urlencode($request);

      $count_match = 0;
      $w_rf = '';

      $rf = array();

     
      $type_search = "WHERE RubTyp = 'kurztext' OR RubTyp = 'langtext'";
      $type_navi = "";

      $i = 0;

      $w_rf = ' AND (';

      $sql_rf = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_rubric_fields  $type_search");

      while($row = $sql_rf->fetchrow()) {
        $w_rf .= (($i >= 1) ? 'OR ' : '') . "RubrikFeld = $row->Id ";
        $i++;
      }

      $w_rf .= ')';

      $Exclude_Tag = '';

      // $Exclude_Tag = " AND (Inhalt NOT LIKE '[cp:replacement]%%')";
      // echo "SELECT DokumentId,Inhalt FROM " . PREFIX . "_document_fields $ex_Inhalt $w_rf AND Suche=1 {$Exclude_Tag}";&

      $query_feld = $GLOBALS['db']->Query("SELECT DokumentId,Inhalt FROM " . PREFIX . "_document_fields $ex_Inhalt $w_rf AND Suche=1 {$Exclude_Tag}");
      $count_match = $query_feld->numrows();
      $num_erg = $count_match;

      $limit = $this->_limit;
      $seiten = ceil($count_match / $limit);
      $start = prepage() * $limit - $limit;

      $query_feld = $GLOBALS['db']->Query("SELECT DokumentId,Inhalt FROM " . PREFIX . "_document_fields $ex_Inhalt $w_rf  AND Suche=1 {$Exclude_Tag} LIMIT $start,$limit");
      $count_match = $query_feld->numrows();

      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_search SET Gefunden='$count_match' WHERE Suchbegriff = '$sw'");


      if($limit < $num_erg) {
        $nav = pagenav($seiten, prepage(), " <a class=\"pnav\" href=\"index.php?module=search&amp;query=" . urlencode($query) . $type_navi . ((isset($_REQUEST['or']) && $_REQUEST['or'] == 1) ? "&amp;or=1" : "") . "&amp;page={s}\">{t}</a>");
        $GLOBALS['tmpl']->assign('q_navi', $nav);
      }

      if($num_erg < 1) {
        $GLOBALS['tmpl']->assign('no_results', 1);
      }


      $p_begriff = $query;

      if($count_match > 0) {

        $modul_search_results = array();
        $match = array();
        $num = '';

        while($row_feld = $query_feld->fetchrow()) {
          $q = "SELECT Titel,Id,Url
          FROM
            " . PREFIX . "_documents
          WHERE Id = '$row_feld->DokumentId'
          AND Geloescht = 0
          AND DokStatus = 1
          AND ( (DokStart = 0 AND DokEnde = 0) OR (DokStart <= " . time() . " AND DokEnde >= " . time() . ") )

          ";

          $sql = $GLOBALS['db']->Query($q);
          while($row = $sql->fetchrow()) {
            $row->Text = $this->getContent($row->Id);
            $row->Text = stripslashes(strip_tags($row->Text, $this->_allowed_tags));
            $row->Text = str_replace(array("[cp:replacement]","[cp:sitemap]"), "", $row->Text);
            $res_c = $row->Text;

            eregi(".{0,100}".$p_begriff.".{0,100}", $row->Text, $fo);

            $row->Text = ' ... ';
            while (list($key, $val) = @each($fo)) {
              $row->Text .= $val . ' ... ';
            }

            $row->Doc =  cp_parse_linkname($row->Titel);

            if($this->_highlight == 1) {
              $row->Text = @eregi_replace($p_begriff, "<span class=\"mod_search_highlight\">\\0</span>", $row->Text);
            }
  					if ($GLOBALS['config']['doc_rewrite']) {
  					} else {
  						$row->Url = (CP_REWRITE==1) ? cp_rewrite('/index.php?id=' . $row->Id . '&amp;doc=' . cp_parse_linkname($row->Titel) ) : '/index.php?id=' . $row->Id . '&amp;doc=' . cp_parse_linkname($row->Titel);
  					}

            array_push($modul_search_results, $row);
          }
        }

      $GLOBALS['tmpl']->assign('searchresults', $modul_search_results);
      }
    } else {
      $GLOBALS['tmpl']->assign('no_results', 1);
    }

    $GLOBALS['tmpl']->assign('inc_path', BASE_DIR . '/modules/search/templates');
    $tpl_out = $GLOBALS['tmpl']->fetch($tpl_dir . 'results.tpl');

    if(!defined('MODULE_CONTENT')) {
      define('MODULE_CONTENT', $tpl_out);
    }
  }

  function showWords($tpl_dir) {

    $limit = $this->_adminlimit;

    $sort      = ' ORDER BY Suchbegriff ASC';
    $sort_navi = '';

    if(isset($_REQUEST['sort']) && $_REQUEST['sort'] != '') {

      switch($_REQUEST['sort']) {

        case 'begriff_desc' :
          $sort      = ' ORDER BY Suchbegriff DESC';
          $sort_navi = '&amp;sort=begriff_desc';
        break;

        case 'begriff_asc' :
          $sort      = ' ORDER BY Suchbegriff ASC';
          $sort_navi = '&amp;sort=begriff_asc';
        break;

        case 'anzahl_desc' :
          $sort      = ' ORDER BY Anzahl DESC';
          $sort_navi = '&amp;sort=anzahl_desc';
        break;

        case 'anzahl_asc' :
          $sort      = ' ORDER BY Anzahl ASC';
          $sort_navi = '&amp;sort=anzahl_asc';
        break;

        case 'gefunden_desc' :
          $sort      = ' ORDER BY Gefunden DESC';
          $sort_navi = '&amp;sort=gefunden_desc';
        break;

        case 'gefunden_asc' :
          $sort      = ' ORDER BY Gefunden ASC';
          $sort_navi = '&amp;sort=gefunden_asc';
        break;

      }
    }

    $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_search $sort");
    $num = $sql->numrows();

    $seiten = ceil($num / $limit);
    $start = prepage() * $limit - $limit;

    $items = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_search $sort LIMIT $start,$limit");

    while($row = $sql->fetchrow()) {
      array_push($items,$row);
    }

    $page_nav = pagenav($seiten, prepage(),
    " <a class=\"pnav\" href=\"index.php?do=modules&action=modedit&mod=search&moduleaction=1$sort_navi&cp=" . SESSION . "&page={s}\">{t}</a> ");

    if($num > $limit) $GLOBALS['tmpl']->assign('page_nav', $page_nav);

    $GLOBALS['tmpl']->assign('items', $items);
    $GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'words.tpl'));
  }

  function delWords() {
    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_search");
    header('Location:index.php?do=modules&action=modedit&mod=search&moduleaction=1&cp=' . SESSION);
    exit;
  }
}
?>