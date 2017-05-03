<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class Navigation {

  var $_limit = 15;

  function showNavis() {
    $mod_navis = array();
    $sql_navis = $GLOBALS['db']->Query("SELECT id,titel FROM " . PREFIX . "_navigation ORDER BY id ASC");

    while($row_navis = $sql_navis->fetchrow()) {
      array_push($mod_navis,$row_navis);
    }
    $sql_navis->Close();

    $GLOBALS['tmpl']->assign("mod_navis", $mod_navis);
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("navigation/overview.tpl"));
  }

  function statusNavi($off_on,$id) {

		if ($GLOBALS['config']['doc_rewrite']) {
			// Добавлено: Для корректной работы навигации при использовании ЧПУ
			$sql = $GLOBALS['db']->Query("SELECT Url FROM " . PREFIX . "_documents WHERE Id = '" . $id . "'");
			$row = $sql->fetchrow();
			$sql->Close();
			$url = $row->Url;

			$sql = $GLOBALS['db']->Query("SELECT Id,Elter FROM " . PREFIX . "_navigation_items WHERE Link = '". $url. "'");
		} else {
			$sql = $GLOBALS['db']->Query("SELECT Id,Elter FROM " . PREFIX . "_navigation_items WHERE Link = 'index.php?id=" . $id . "'");
		}
    $row = $sql->fetchrow();
    $sql->Close();
    $id = $row->Id;

    $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_navigation_items WHERE Elter = '$row->Id'");

    while ($sub_navi = $sql->fetchrow()) {
      $sql_del = $GLOBALS['db']->Query("UPDATE " . PREFIX . "_navigation_items SET Aktiv = '$off_on' WHERE Elter = " . $sub_navi->Id);
    }

    $GLOBALS['db']->Query("UPDATE " . PREFIX . "_navigation_items SET Aktiv = '$off_on' WHERE id = '" . $id ."'");
    reportLog($_SESSION["cp_uname"] . " - " . (($offon==1) ? "активировал пункт в меню навигации" : "отключил пункт в меню навигации") . " ($id)",'2','2');
  }

  function delNavi($id) {
		if ($GLOBALS['config']['doc_rewrite']) {
			// Добавлено: Для корректного удаления навигации при использовании ЧПУ
			$sql = $GLOBALS['db']->Query("SELECT Url FROM " . PREFIX . "_documents WHERE Id = '$id'");
			$row = $sql->fetchrow();
			$sql->Close();
			$url = $row->Url;

	    $sql = $GLOBALS['db']->Query("SELECT Id,Elter FROM " . PREFIX . "_navigation_items WHERE Link = '" . $url . "'");
		} else {
			$sql = $GLOBALS['db']->Query("SELECT Id,Elter FROM " . PREFIX . "_navigation_items WHERE Link = 'index.php?id=" . $id . "'");
		}
    $row = $sql->fetchrow();
    $sql->Close();
    $id = $row->Id;

    $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_navigation_items WHERE Elter = '$row->Id'");

    while ($sub_navi = $sql->fetchrow()) {
      $sql_del = $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_navigation_items WHERE Elter = " . $sub_navi->Id);
    }

    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_navigation_items WHERE id = '$id'");
    reportLog($_SESSION["cp_uname"] . " - удалил пункт в меню навигации ($id)",'2','2');
  }

  function showEntries($id, $extern = '0') {

    $entries   = array();

    $sql_navis = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_navigation_items WHERE Rubrik='$id' AND Elter=0 AND Ebene=1 ORDER BY Rang ASC");

    while($row_navis = $sql_navis->fetchrow()) {
      $entries_2 = array();
      $sql_2 = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_navigation_items WHERE Rubrik='$id' AND Elter='$row_navis->Id' AND Ebene=2 ORDER BY Rang ASC");

      while($row_2 = $sql_2->fetchrow()) {
        $entries_3 = array();
        $sql_3 = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_navigation_items WHERE Rubrik='$id' AND Elter='$row_2->Id' AND Ebene=3 ORDER BY Rang ASC");

        while($row_3 = $sql_3->fetchrow()) {
          array_push($entries_3, $row_3);
        }
        $row_2->ebene_3 = $entries_3;
        array_push($entries_2, $row_2);
      }

      $row_navis->ebene_2 = $entries_2;
      array_push($entries, $row_navis);
    }

    if($extern == '1') {
      $GLOBALS['tmpl']->assign("navi_entries", $entries);
    } else {
      $sql_navis = $GLOBALS['db']->Query("SELECT titel FROM " . PREFIX . "_navigation WHERE id = '$id'");
      $row_navis = $sql_navis->fetchrow();

      $GLOBALS['tmpl']->assign('NavigatonName', $row_navis->titel);
      $GLOBALS['tmpl']->assign("entries", $entries);
      $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("navigation/entries.tpl"));
    }
  }

  function showAllEntries($type = '') {
    $entries   = array();

    $rubs = array();
//    $sql = $GLOBALS['db']->Query("SELECT Id,RubrikName FROM " . PREFIX . "_rubrics");
    $sql = $GLOBALS['db']->Query("SELECT Id,titel AS RubrikName FROM " . PREFIX . "_navigation");

    while($row_all = $sql->fetchrow()) {
      $sql_navis = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_navigation_items WHERE Rubrik='$row_all->Id' AND Elter=0 AND Ebene=1 ORDER BY Rang ASC");

      while($row_navis = $sql_navis->fetchrow()) {
        $entries_2 = array();
        $sql_2 = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_navigation_items WHERE Rubrik='$row_all->Id' AND Elter='$row_navis->Id' AND Ebene=2 ORDER BY Rang ASC");

        while($row_2 = $sql_2->fetchrow()) {
          if($type == '') {
            $entries_3 = array();
            $sql_3 = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_navigation_items WHERE Rubrik='$row_all->Id' AND Elter='$row_2->Id' AND Ebene=3 ORDER BY Rang ASC");
            while($row_3 = $sql_3->fetchrow()) {
              array_push($entries_3, $row_3);
            }

          $row_2->ebene_3 = $entries_3;
          }
          array_push($entries_2, $row_2);
        }

        $row_navis->ebene_2 = $entries_2;
        $row_navis->RubId= $row_all->Id;
        $row_navis->Rubname = $row_all->RubrikName;
        array_push($entries, $row_navis);
      }
      array_push($rubs,$row_all);
    }
    $GLOBALS['tmpl']->assign("rubs", $rubs);
    $GLOBALS['tmpl']->assign("navi_entries", $entries);
  }

  function replace_wildcode($code) {
    $code = ereg_replace('([^ :)/(_A-Za-zА-Яа-яЁё0-9&-])', '', $code);
    $code = htmlspecialchars($code);
    return $code;
  }

  function quickSave($id) {

    foreach ($_POST['Titel_N'] as $id => $Titel) {

      if(!empty($_POST['Titel_N'][$id])) {
      $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_navigation_items (Id,Titel,Elter,Link,Ziel,Ebene,Rang,Rubrik) VALUES (
        '',
        '" . $this->replace_wildcode($_POST['Titel_N'][$id]) . "',
        '" . $id . "',
        '" . str_replace(REPLACEMENT, "[cp:replacement]", $_POST['Link_N'][$id]) . "',
        '" . $_POST['Ziel_N'][$id] . "',
        '1',
        '" . $_POST['Rang_N'][$id] . "',
        '" . $_POST['Rubrik'] . "')");

      reportLog($_SESSION["cp_uname"] . " - добавил пункт меню навигации  (".$_POST['Titel_Neu_2'][$id]. ")",'2','2');
      }
    }

    foreach ($_POST['Titel'] as $id => $Titel) {

      if(!empty($_POST['Titel'][$id])) {

        $_POST['Link'][$id]  = (strpos($_POST['Link'][$id],"javascript")!==false) ? str_replace(array(" ","-","%"),"_",$_POST['Link'][$id]) : $_POST['Link'][$id];

        $aktiv = (empty($_POST['Aktiv'][$id]) || $_POST['Aktiv'][$id] == "0") ? "1" : "0";

        $q = "UPDATE " . PREFIX . "_navigation_items
          SET
            Titel = '" . $this->replace_wildcode($_POST['Titel'][$id]) . "',
            Link = '" . str_replace(REPLACEMENT, "[cp:replacement]", $_POST['Link'][$id]) . "',
            Rang = '" . $_POST['Rang'][$id] . "',
            Ziel = '" . $_POST['Ziel'][$id] . "',
            Aktiv = '" . $_POST['Aktiv'][$id] . "'
          WHERE
            id = '$id'
          ";
        $GLOBALS['db']->Query($q);
      }
    }



    foreach ($_POST['Titel_Neu_2'] as $id => $Titel) {

      if(!empty($_POST['Titel_Neu_2'][$id])) {
        $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_navigation_items (Id,Titel,Elter,Link,Ziel,Ebene,Rang,Rubrik) VALUES (
          '',
          '" . $this->replace_wildcode($_POST['Titel_Neu_2'][$id]) . "',
          '" . $id . "',
          '" . str_replace(REPLACEMENT, "[cp:replacement]", $_POST['Link_Neu_2'][$id]) . "',
          '" . $_POST['Ziel_Neu_2'][$id] . "',
          '2',
          '" . $_POST['Rang_Neu_2'][$id] . "',
          '" . $_POST['Rubrik'] . "')");

        reportLog($_SESSION["cp_uname"] . " - добавил пункт меню навигации  (".$_POST['Titel_Neu_2'][$id]. ")",'2','2');
      }
    }


    foreach ($_POST['Titel_Neu_3'] as $id => $Titel) {

      if(!empty($_POST['Titel_Neu_3'][$id])) {
        $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_navigation_items (Id,Titel,Elter,Link,Ziel,Ebene,Rang,Rubrik) VALUES (
          '',
          '" . $this->replace_wildcode($_POST['Titel_Neu_3'][$id]) . "',
          '" . $id . "',
          '" . str_replace(REPLACEMENT, "[cp:replacement]", $_POST['Link_Neu_3'][$id]) . "',
          '" . $_POST['Ziel_Neu_3'][$id] . "',
          '3',
          '" . $_POST['Rang_Neu_3'][$id] . "',
          '" . $_POST['Rubrik'] . "')");

          reportLog($_SESSION["cp_uname"] . " - добавил пункт меню навигации (".$_POST['Titel_Neu_3'][$id]. ")",'2','2');
      }
    }


    foreach ($_POST['del'] as $id => $del) {

      if(!empty($_POST['del'][$id])) {
        $sql = $GLOBALS['db']->Query("SELECT Elter FROM " . PREFIX . "_navigation_items WHERE Elter = $id");

        while ($sub_navi = $sql->fetchrow()) {
          $sql_del = $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_navigation_items WHERE Elter = " . $sub_navi->Elter);
        }

        $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_navigation_items WHERE id = '$id'");
        reportLog($_SESSION["cp_uname"] . " - удалил пункт меню навигации ($id)",'2','2');
      }
    }
    header("Location:index.php?do=navigation&action=entries&cp=".SESSION. "&id=$_REQUEST[id]");
    exit;
  }

  function naviTemplate($id) {

    switch($_REQUEST['sub']){

      case '':
        $group = new cpUser;
        $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_navigation WHERE id = '$id'");
        $row = $sql->fetchrow();

        $row->Gruppen = explode(",", $row->Gruppen);
        $row->AvGroups = $group->listAllGroups();
        $GLOBALS['tmpl']->assign("nav", $row);
        $GLOBALS['tmpl']->assign("formaction", "index.php?do=navigation&amp;action=templates&amp;sub=save&amp;id=$_REQUEST[id]&amp;cp=" . SESSION);
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("navigation/template.tpl"));
      break;

      case 'save':
        $GLOBALS['db']->Query("UPDATE " . PREFIX . "_navigation SET
          titel = '" . $_POST['titel'] . "',
          ebene1 = '" . $_POST['ebene1'] . "',
          ebene1a = '" . $_POST['ebene1a'] . "',
          ebene2 = '" . $_POST['ebene2'] . "',
          ebene2a = '" . $_POST['ebene2a'] . "',
          ebene3 = '" . $_POST['ebene3'] . "',
          ebene3a = '" . $_POST['ebene3a'] . "',
          ebene1_v = '" . $_POST['ebene1_v'] . "',
          ebene1_n = '" . $_POST['ebene1_n'] . "',
          ebene2_v = '" . $_POST['ebene2_v'] . "',
          ebene2_n = '" . $_POST['ebene2_n'] . "',
          ebene3_v = '" . $_POST['ebene3_v'] . "',
          ebene3_n = '" . $_POST['ebene3_n'] . "',
          vor = '" . $_POST['vor'] . "',
          nach = '" . $_POST['nach'] . "',
          Gruppen = '" . implode(",", $_REQUEST['Gruppen']) . "',
          Expand = '" . $_POST['Expand'] . "'
        WHERE id = '" . (int)$_REQUEST['id'] . "'");

        reportLog($_SESSION["cp_uname"] . " - отредактировал шаблон меню навигации ($_POST[titel])",'2','2');
        header("Location:index.php?do=navigation&cp=".SESSION);
        exit;
      break;
    }
  }

  function naviTemplateNew() {

    switch($_REQUEST['sub']) {
      case '':
        $group = new cpUser;
        $row->AvGroups = $group->listAllGroups();
        $GLOBALS['tmpl']->assign("row", $row);
        $GLOBALS['tmpl']->assign("formaction", "index.php?do=navigation&amp;action=new&amp;sub=save&amp;cp=" . SESSION);
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("navigation/template.tpl"));
      break;


      case 'save':
        $titel = (empty($_POST['titel'])) ? "не указано" : $_POST['titel'];
        $ebene1 = (empty($_POST['ebene1'])) ? '<a target="[cp:target]" href="[cp:link]">[cp:linkname]</a>' : $_POST['ebene1'];
        $ebene1a = (empty($_POST['ebene1a'])) ? '<a target="[cp:target]" class="first_active" href="[cp:link]">[cp:linkname]</a>' : $_POST['ebene1a'];

        $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_navigation (
          id,
          titel,
          ebene1,
          ebene1a,
          ebene2,
          ebene2a,
          ebene3,
          ebene3a,
          ebene1_v,
          ebene2_v,
          ebene3_v,
          ebene1_n,
          ebene2_n,
          ebene3_n,
          vor,
          nach,
          Gruppen,
          Expand
        ) VALUES (
          '',
          '" . $titel . "',
          '" . $ebene1 . "',
          '" . $ebene1a . "',
          '" . $_POST['ebene2'] . "',
          '" . $_POST['ebene2a'] . "',
          '" . $_POST['ebene3'] . "',
          '" . $_POST['ebene3a'] . "',
          '" . $_POST['ebene1_v'] . "',
          '" . $_POST['ebene2_v'] . "',
          '" . $_POST['ebene3_v'] . "',
          '" . $_POST['ebene1_n'] . "',
          '" . $_POST['ebene2_n'] . "',
          '" . $_POST['ebene3_n'] . "',
          '" . $_POST['vor'] . "',
          '" . $_POST['nach'] . "',
          '" . implode(",", $_REQUEST['Gruppen']) . "',
          '" . $_POST['Expand'] . "'
        )");

        reportLog($_SESSION["cp_uname"] . " - создал новое меню навигации ($_POST[titel])",'2','2');
        header("Location:index.php?do=navigation&cp=".SESSION);
      break;
    }
  }

  function copyNaviTemplate($id) {
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_navigation WHERE id = '$id'");
    $row = $sql->fetchrow();

    $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_navigation (
        id,
        titel,
        ebene1,
        ebene1a,
        ebene2,
        ebene2a,
        ebene3,
        ebene3a,
        vor,
        nach,
        ebene1_v,
        ebene2_v,
        ebene3_v,
        ebene1_n,
        ebene2_n,
        ebene3_n,
        Gruppen,
        Expand
      ) VALUES (
        '',
        '" . $row->titel . ' ' . $GLOBALS['config_vars']['CopyT'] . "',
        '" . $row->ebene1 . "',
        '" . $row->ebene1a . "',
        '" . $row->ebene2 . "',
        '" . $row->ebene2a . "',
        '" . $row->ebene3 . "',
        '" . $row->ebene3a . "',
        '" . $row->vor . "',
        '" . $row->nach . "',
        '" . $row->ebene1_v . "',
        '" . $row->ebene2_v . "',
        '" . $row->ebene3_v . "',
        '" . $row->ebene1_n . "',
        '" . $row->ebene2_n . "',
        '" . $row->ebene3_n . "',
        '" . $row->Gruppen . "',
        '" . $row->Expand . "'
      )");

    reportLog($_SESSION["cp_uname"] . " - создал копию шаблона меню навигации ($row->titel)",'2','2');
    header("Location:index.php?do=navigation&cp=".SESSION);
  }

  function deleteNavi($id) {

    if($id != 1) {
      $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_navigation WHERE id = '$id'");
      $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_navigation_items WHERE Rubrik = '$id'");
      reportLog($_SESSION["cp_uname"] . " - удалил меню навигации ($id)",'2','2');
    }
    header("Location:index.php?do=navigation&cp=".SESSION);
  }
}
?>