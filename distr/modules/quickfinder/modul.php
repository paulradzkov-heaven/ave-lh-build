<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
 
  
 Modified: interpaul, rez, June 20, 2010
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit;

$modul['ModulName'] = "Ѕыстрый переход";
$modul['ModulPfad'] = "quickfinder";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "ƒанный модуль €вл€етс€ альтернативным способом организации меню навигации на сайте. ќн представлен в виде выпадающего списка разделов и подразделов вашего сайта. ƒл€ использовани€ модул€, разместите системный тег <strong>[cp:quickfinder]</strong> в нужном месте вашего шаблона.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007 Overdoze Team";
$modul['Status'] = 0;
$modul['IstFunktion'] = 1;
$modul['ModulFunktion'] = "cpQuickfinder";
$modul['CpEngineTagTpl'] = "[cp:quickfinder]";
$modul['CpEngineTag'] = "\\\[cp:quickfinder\\\]";
$modul['CpPHPTag'] = "<?php cpQuickfinder(); ?>";

function cpQuickfinder($id = '')  {
  $gnavtitel = "¬ыберите раздел";
  echo'<select class="cp_quickfinder" name="select" onchange="eval(this.options[this.selectedIndex].value);">';
  echo'<option class="label">'.$gnavtitel.'</option>';
  printQNavi(1, 0, 0);
  echo'</select>';
}

function printQNavi($ebene, $id = '0', $not_form = '0') {

  //$qnavcut = '';
  //$dots = ($not_form == 1) ? str_repeat("&nbsp;&nbsp;&nbsp;", $ebene * 2) . "<span class=\"bull\">&bull;</span>&nbsp;" : str_repeat("&nbsp;&nbsp;", $ebene * 2) . "&middot;&nbsp;";

  $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_navigation_items WHERE Aktiv = '1' AND Elter = '$id' AND Ebene = '$ebene' ORDER BY Rubrik, Rang ASC");

  while($row = $sql->FetchArray()) {
    if(checkSeePerm($row['Rubrik'])) {
      $rewrite = false;
      $allowed_start = array('index.php?');

      foreach($allowed_start as $allowed) {
        if(startsWith($allowed, $row['Link'])) $rewrite = true;
      }

      if(strpos($row['Link'],"module=") !== false) $rewrite = false;

      if($rewrite == true) {
        @$akt = str_replace("[cp:link]", $row['Link'] . "&amp;doc=" . cp_parse_linkname($row['Titel']), $akt);
      }

      if($rewrite) {
        $link = stripslashes($row['Link']);
        $link .= "&amp;doc=" . cp_parse_linkname($row['Titel']);
        $rewrite = 1;

      } else {

        $link = stripslashes($row['Link']);
        $link = str_replace("[cp:replacement]", REPLACE_MENT, $link);

        if(startsWith("www.", stripslashes($row['Link']))) {
          $link = str_replace("www.", "http://www.", $link);
        }
        $rewrite = 0;
      }

      $t = '';

      
      if(!startsWith("javascript:", $link)) {

        if($row['Ziel'] == '_blank') {
          $link = "javascript:window.open('$link','','')";

        } elseif($row['Ziel'] == '' || $row['Ziel'] == '_self') {

          $link = "window.location.href = '$link'";
        }
      }

      if($ebene == 1) {
        //echo '<option>'.$qnavcut.'</option>';
      }

      $gid = (isset($_GET['id']) && $_GET['id'] != '') ? (int)$_GET['id'] : '1';      
      $akt_link = $row['Link'];
      $akt_link = str_replace("[cp:replacement]", "/", $akt_link);
      $sql_akt = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_documents WHERE Url = '".$akt_link."'");
      $row_akt = $sql_akt->FetchArray();
      $akt_link = $row_akt['Id'];

      $link = (CP_REWRITE == 1) ? cp_rewrite($link) : $link;
      

      if (CP_REWRITE == 1){
	$link_gid = $_SERVER['REQUEST_URI'];
	$sql_gid = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_documents WHERE Url = '".$link_gid."'");
	$row_gid = $sql_gid->FetchArray();
	$gid = $row_gid['Id'];
	
      }	
      
     			echo  '<option value="'.$link.'"' . (@$akt_link==$gid ? ' selected="selected"' : '') . ' class="level'.$ebene.'" >'.stripslashes(cp_parse_string($row['Titel'])).'</option>';


      if($ebene < 3) {
        printQNavi($ebene+1, $row['Id'], $not_form);
      }
    }
  }
  $sql->Close();
}
?>