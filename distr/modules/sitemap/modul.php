<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit;

$modul['ModulName'] = "Карта сайта";
$modul['ModulPfad'] = "sitemap";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "Данный модуль предназначен для построения карты вашего сайта на основании пунктов меню навигации. Для того, чтобы осуществить просмотр карты сайта, необходимо разместить системный тег <strong>[cp:sitemap]</strong> в теле какого-либо документа.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['ModulFunktion'] = "cpSitemap";
$modul['CpEngineTagTpl'] = "[cp:sitemap]";
$modul['CpEngineTag'] = "\\\[cp:sitemap\\\]";
$modul['CpPHPTag'] = "<?php cpSitemap(); ?>";

function cpSitemap() {

  $id = 1;
  ob_start();

  $r_id   = (isset($_GET['id']) && is_numeric($_GET['id']) ) ? (int)$_GET['id'] : 1;
  $refurl = 'index.php?id=' . $r_id;

  $sql_edoc = $GLOBALS['db']->Query("SELECT ElterNavi FROM " . PREFIX . "_documents WHERE Id = '1'");
  $row_edoc = $sql_edoc->fetchrow();
  $sql_edoc->Close();

  $edoc =$row_edoc->ElterNavi;
  $wrub = '';

  $sql = $GLOBALS['db']->Query("SELECT Rubrik, Id, Ebene, Elter FROM " . PREFIX . "_navigation_items
                                WHERE  Aktiv='1' AND Link like '$refurl%%' $wrub OR Id = '$edoc' ORDER BY Rang, Rubrik ASC LIMIT 1");

  while($row = $sql->FetchArray()) {
    $item_id = $row['Id'];
    $emax    = $row['Ebene'];
    $parent  = $row['Elter'];
  }

  $sql->Close();

  $way = array();
  $item_id = (!empty($item_id)) ? $item_id : '';
  $emax    = (!empty($emax))    ? $emax    : '';
  $parent  = (!empty($parent))  ? $parent  : '';

  array_push($way, $item_id);

  for($i = $emax; $i > 0; $i--) {
    $sql = $GLOBALS['db']->Query("SELECT Rubrik, Id, ebene, Elter FROM " . PREFIX . "_navigation_items WHERE Aktiv = '1' AND Id = '$parent' ORDER BY Rang ASC");

    while($row = $sql->FetchArray()) {
      $parent = $row['Elter'];
      array_push($way, $row['Id']);
    }

    $sql->Close();
  }

  $p_id = ($id == '') ? 0 : $id;

  print_cpsitemap(1, 0, 0, $way, $id, 1);

  $END = ob_get_contents();
  ob_end_clean();

  $END = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $END);
  $END = preg_replace("/\<ul\>([\n])\<\/ul\>/si", "", $END);
  $END = preg_replace("/\<ul\>\<\/ul\>/si", "", $END);
  $END = str_replace(array("\r\n","\n","\r"),"",$END);
  $END = preg_replace("/\<ul (.*?)\>\<\/ul\>/si", "", $END);
  $END = str_replace("</li><ul>","<ul>",$END);
  $END = str_replace("</ul><li>","</ul></li><li>",$END);
  echo $END;
}

function print_cpsitemap($ebene, $parent = '0', $way, $rub) {

  if ($parent != '0') {
    $nav = " AND Elter = $parent";
  } else {
    $nav = " AND Elter = '0'";
  }


  $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_navigation_items
                                WHERE Aktiv = '1' AND Ebene = '$ebene'".$nav." ORDER BY Rubrik, Rang ASC");

  switch($ebene) {
    case 1 :
    case 2 :
    case 3 : echo "<ul>"; break;
  }


  while($row = $sql->FetchArray()) {
    if(checkSeePerm($row['Rubrik'])) {

      $rewrite = false;
      $allowed_start = array('index.php?');

      foreach($allowed_start as $allowed) {
        if(startsWith($allowed, $row['Link'])) $rewrite = true;
      }

      if(strpos($row['Link'], 'module=') !== false) $rewrite = false;

      if($rewrite == true) {
        $row['Link'] .= '&amp;doc=' . cp_parse_linkname($row['Titel']);
      }

      $row['Link'] = str_replace('[cp:replacement]', REPLACE_MENT, $row['Link']);

      if(startsWith('www.', stripslashes($row['Link']))) {
        $row['Link'] = str_replace('www.', 'http://www.', $row['Link']);
      }

      $row['Link'] = (CP_REWRITE == 1) ? cp_rewrite($row['Link']) : $row['Link'];
      $akt = "<li><a style=\"". (($ebene==1) ? "font-weight:bold" : "font-weight:normal") . "\" href=\"".$row['Link']. "\" target=\"".$row['Ziel']. "\">" . cp_parse_string($row['Titel']) . "</a></li>";


      echo($akt);
      print_cpsitemap($ebene+1, $row['Id'], $way, $rub);
    }
  }

  switch($ebene) {
    case 1 :
    case 2 :
    case 3 : echo "</ul>"; break;
  }

  $sql->Close();
}
?>