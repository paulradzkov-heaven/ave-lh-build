<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit;

$modul['ModulName'] = "Навигация";
$modul['ModulPfad'] = "navigation";
$modul['ModulVersion'] = "1.1";
$modul['Beschreibung'] = "Данный модуль предназначен не только для создания различных видов меню (горизонтального или вертикального), но и меню навигаций, состоящих из различного количества пунктов и уровней вложенности. Помните, что максимальная глубина уровней вложенности не может быть больше 3. Для создания меню, перейдите в раздел <strong>Навигация</strong>. Для отображения меню на сайте разместите системный тег <strong>[cp_navi:XXX]</strong> в нужном месте вашего шаблона. ХХХ - это порядковый номер меню.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['ModulFunktion'] = "cp_navi";
$modul['CpEngineTagTpl'] = "[cp_navi:XXX]";
$modul['CpEngineTag'] = "\\\[cp_navi:([0-9\\\]*)\\]";
$modul['CpPHPTag'] = "<?php cp_navi(''\\\\\\\\1''); ?>";

if(defined('ACP') && $_REQUEST['action'] != 'delete') {
  $modul_sql_update = array();
  $modul_sql_update[] = "ALTER TABLE CPPREFIX_navigation ADD Expand TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL;";
  $modul_sql_update[] = "UPDATE CPPREFIX_module SET Version = '" . $modul['ModulVersion'] . "' WHERE ModulName = '" . $modul['ModulName'] . "';" ;
}

function cp_navi($id) {
  ob_start();
  $id  = ereg_replace('([^0-9]*)', '', $id);
  $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_navigation WHERE id = '$id'");
  if ($row = $sql->FetchRow()) {
    $row->Gruppen = explode(',', $row->Gruppen);
  } else {
  	echo $GLOBALS['config_vars']['NAVI_MENU_NOT_FOUND'] . $id;
    $sql->Close();
  	return;
  }
  $sql->Close();

  if(!defined('UGROUP')) define('UGROUP', 2);

  if(!in_array(UGROUP, $row->Gruppen)) {
    echo '';

  } else {
    $r_id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : 1;

    $sql = $GLOBALS['db']->Query("SELECT Url,ElterNavi FROM " . PREFIX . "_documents WHERE Id = '$r_id'");
    $row_doc = $sql->FetchRow();
    $sql->Close();

    // Исправлено для ЧПУ
    // $refurl = 'index.php?id=' . $r_id;
    if ($GLOBALS['config']['doc_rewrite'] && (!isset($_REQUEST['module']) || $_REQUEST['module'] == '')) {
      $refurl = $row_doc->Url;
      $refurl = str_replace("/", "[cp:replacement]", $refurl);
    } else {
      $refurl = 'index.php?id=' . $r_id;
    }
    $edoc = $row_doc->ElterNavi;
    $extended_by_module = (isset($_REQUEST['module']) && $_REQUEST['module'] != '') ? ("Link like '[cp:replacement]index.php?module=" . $_REQUEST['module'] . "%%'") : "Link='$refurl'";
    $sql = $GLOBALS['db']->Query("SELECT Id,Ebene,Elter FROM " . PREFIX . "_navigation_items WHERE  (Aktiv = '1' AND $extended_by_module AND Rubrik = '$id') OR (Aktiv = '1' AND Id = '$edoc') ORDER BY Rang ASC LIMIT 1");


    while ($row_navitems = $sql->FetchRow()) {
      $item_id = (!empty($row_navitems->Id)) ? $row_navitems->Id : '';
      $emax    = (!empty($row_navitems->Ebene)) ? $row_navitems->Ebene : '';
      $parent  = (!empty($row_navitems->Elter)) ? $row_navitems->Elter : '';
    }
    $sql->Close();

    $way = array();
    array_push($way, $item_id);

    for($i = $emax; $i > 0; $i--) {
      $sql = $GLOBALS['db']->Query("SELECT Id,ebene,Elter FROM " . PREFIX . "_navigation_items WHERE Aktiv = '1' AND Id = '" . $parent . "' ORDER BY Rang ASC");
      while($row_navitems = $sql->FetchRow()) {
        $parent = $row_navitems->Elter;
        array_push($way, $row_navitems->Id);
      }
      $sql->Close();
    }

    $ebene1  = str_replace('[cp:mediapath]', 'templates/' . T_PATH . '/', stripslashes($row->ebene1));
    $ebene1a = str_replace('[cp:mediapath]', 'templates/' . T_PATH . '/', stripslashes($row->ebene1a));
    $ebene2  = str_replace('[cp:mediapath]', 'templates/' . T_PATH . '/', stripslashes($row->ebene2));
    $ebene2a = str_replace('[cp:mediapath]', 'templates/' . T_PATH . '/', stripslashes($row->ebene2a));
    $ebene3  = str_replace('[cp:mediapath]', 'templates/' . T_PATH . '/', stripslashes($row->ebene3));
    $ebene3a = str_replace('[cp:mediapath]', 'templates/' . T_PATH . '/', stripslashes($row->ebene3a));
    $vor     = str_replace('[cp:mediapath]', 'templates/' . T_PATH . '/', stripslashes($row->vor));
    $nach    = str_replace('[cp:mediapath]', 'templates/' . T_PATH . '/', stripslashes($row->nach));

    $ebenen = array(
      1 =>  array('aktiv' => $ebene1a,'inaktiv' => $ebene1),
      2 =>  array('aktiv' => $ebene2a,'inaktiv' => $ebene2),
      3 =>  array('aktiv' => $ebene3a,'inaktiv' => $ebene3)
    );

    echo $vor;
    $p_id = ($id == '') ? 0 : $id;
    printNavi(1, $emax, $refurl, $ebenen, $way, $id, $row);
    echo $nach;

    $END = ob_get_contents();
    ob_end_clean();

    $END = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $END);
    $END = str_replace(array("\r\n","\n","\r"),"",$END);
    $search = array (
    	$row->ebene1_v . $row->ebene1_n,
    	$row->ebene2_v . $row->ebene2_n,
    	$row->ebene3_v . $row->ebene3_n,
    	'</li>' . $row->ebene2_v . '<li',
    	'</li>' . $row->ebene3_v . '<li',
    	'</li>' . $row->ebene1_n . '<li',
    	'</li>' . $row->ebene2_n . '<li',
    	'</li>' . $row->ebene1_n . $row->ebene2_n . '<li',
    	'</li>' . $row->ebene1_n . $row->ebene2_n . $row->ebene3_n,
    	'</li>' . $row->ebene1_n . $row->ebene2_n,
    	'</li>' . $row->ebene2_n . $row->ebene3_n
    );
    $replace = array (
    	'',
    	'',
    	'',
    	$row->ebene2_v . '<li',
    	$row->ebene3_v . '<li',
    	'</li>' . $row->ebene1_n . '</li><li',
    	'</li>' . $row->ebene2_n . '</li><li',
    	'</li>' . $row->ebene1_n . '</li>' . $row->ebene2_n . '</li><li',
    	'</li>' . $row->ebene1_n . '</li>' . $row->ebene2_n . '</li>' . $row->ebene3_n,
    	'</li>' . $row->ebene1_n . '</li>' . $row->ebene2_n,
    	'</li>' . $row->ebene2_n . '</li>' . $row->ebene3_n
    );
    $END = str_replace($search, $replace, $END);

    echo $END;
  }
}

function printNavi($ebene, $emax, $url, $ebenen, $way, $rub, $row_ul, $parent = '0') {
  $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_navigation_items WHERE Aktiv='1' AND Rubrik = '$rub' AND Ebene='$ebene'" .($parent!="0"?"AND Elter=$parent ":"AND Elter='0' "). "ORDER BY Rubrik,Rang ASC");

  switch($ebene) {
    case 1 : echo $row_ul->ebene1_v;  break;
    case 2 : echo $row_ul->ebene2_v;  break;
    case 3 : echo $row_ul->ebene3_v;  break;
  }

  while($row = $sql->FetchArray()) {
    $other = "index.php?" . $_SERVER['QUERY_STRING'];
    $aktiv = ($row['Link']==$url || (strpos($row['Link'], $url) === true) || $other == $row['Link']) ? 'aktiv' : 'inaktiv';

    $expand = false;

    foreach($way as $item) {
      if($item == $row['Id'] || $other == $row['Link']) $expand = true;
    }

    $akt = $ebenen[$ebene][($aktiv=='inaktiv' ? ($expand ? 'aktiv' : 'inaktiv') : 'aktiv')];
    $akt = str_replace("[cp:linkname]", cp_parse_string($row['Titel']), $akt);

    $rewrite = false;
    $allowed_start = array('index.php?');

    foreach($allowed_start as $allowed) {
      if(startsWith($allowed, $row['Link'])) $rewrite = true;
    }

    if(strpos($row['Link'],'module=') !== false) $rewrite = false;

    if($rewrite == true) {
      $akt = str_replace('[cp:link]', $row['Link'] . "&amp;doc=" . cp_parse_linkname($row['Titel']), $akt);

    } else {
      $row['Link'] = str_replace("[cp:replacement]", REPLACE_MENT, $row['Link']);
      $akt = str_replace("[cp:link]", stripslashes($row['Link']), $akt);

      if(startsWith("www.", stripslashes($row['Link']))) {
        $akt = str_replace('www.', 'http://www.', $akt);
      }
    }

    $akt = str_replace('[cp:target]', stripslashes($row['Ziel']), $akt);
    $akt = (CP_REWRITE == 1) ? cp_rewrite($akt) : $akt;
	
    echo($akt);

    if($expand || 1 == $row_ul->Expand) {
      printNavi($ebene + 1, $emax, $url, $ebenen, $way, $rub, $row_ul, $row['Id']);
    }
  }

  switch($ebene) {
    case 1 : echo $row_ul->ebene1_n;  break;
    case 2 : echo $row_ul->ebene2_n;  break;
    case 3 : echo $row_ul->ebene3_n;  break;
  }
  $sql->Close();
}
?>