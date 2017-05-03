<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/


class rubs {

  var $_limit = 15;
  var $_tpl = "rubs/form.tpl";

  function delRub() {
    $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_documents WHERE RubrikId = '" .$_REQUEST['Id']. "'");
    $count = $sql->numrows();

    if($count < 1 && $_REQUEST['Id'] != 1) {
      $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_rubrics WHERE Id = '" .$_REQUEST['Id']. "'");
      $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_rubric_fields WHERE  RubrikId = '" .$_REQUEST['Id']. "'");
      $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_document_permissions WHERE  RubrikId = '" .$_REQUEST['Id']. "'");
      reportLog($_SESSION["cp_uname"] . " - удалил рубрику (".(int)$_REQUEST['Id']. ")",'2','2');
    }
    header("Location:index.php?do=rubs&cp=".SESSION. "");
  }

  function newRub() {

    switch($_REQUEST['sub']) {

      case '':
        $groups = array();
        $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_user_groups WHERE Benutzergruppe != 2");

        while($row = $sql->fetchrow()) {
          $row->doall = ($row->Benutzergruppe == 1) ? " disabled checked" : "";
          $row->doall_h = ($row->Benutzergruppe == 1) ? 1 : "";
          array_push($groups,$row);
        }

        $GLOBALS['tmpl']->assign("groups", $groups);
        $GLOBALS['tmpl']->assign("AlleVorlagen", getAllTemplates());
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("rubs/rubnew.tpl"));
      break;

      case 'save':
        if(empty($_POST['RubrikName'])) {
          header("Location:index.php?do=rubs&action=new&cp=".SESSION. "");
          exit;
        } else {
          $sql = $GLOBALS['db']->Query("SELECT RubrikName FROM " . PREFIX . "_rubrics WHERE RubrikName='" .$_POST['RubrikName']. "'");
          $count = $sql->numrows();

          if($count == 1) {
            header("Location:index.php?do=rubs&action=new&cp=".SESSION. "&error=exists");
            exit;
          } else {
            $sql = $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_rubrics (Id,RubrikName,UrlPrefix,Vorlage,RBenutzer,RDatum) VALUES ('','" .$_POST['RubrikName']. "','" .$_POST['UrlPrefix']. "','" .$_POST['Vorlage']. "','" .$_SESSION["cp_benutzerid"]. "','" .time(). "')");
            $iid = $GLOBALS['db']->InsertId();
            reportLog($_SESSION["cp_uname"] . " - добавил рубрику (".$_POST['RubrikName']. ")",'2','2');
            header("Location:index.php?do=rubs&action=edit&Id=".$iid. "&cp=".SESSION. "");
            exit;
          }
        }
      break;
    }
  }

  function saveRubTpl($data) {
    $GLOBALS['db']->Query("UPDATE " . PREFIX . "_rubrics
      SET
      RubrikTemplate = '$data' WHERE Id = '" .(int)$_REQUEST['Id']. "'");
    reportLog($_SESSION["cp_uname"] . " - отредактировал шаблон рубрики (".$_REQUEST['Id']. ")",'2','2');
    header("Location:index.php?do=rubs&cp=".SESSION. "");
  }

  function fetchRubDetails() {
    $rub_fields = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_rubric_fields WHERE RubrikId ='" .$_REQUEST['Id']. "' ORDER BY rubric_position ASC");

    while($row = $sql->fetchrow()) {
      array_push($rub_fields,$row);
    }

    $GLOBALS['tmpl']->assign("rub_fields", $rub_fields);

    $groups = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_user_groups");

    while($row = $sql->fetchrow()) {
      $row->doall = ($row->Benutzergruppe == 1) ? " disabled checked" : "";
      $row->doall_h = ($row->Benutzergruppe == 1) ? 1 : "";

      $sql_doc_perm = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_document_permissions WHERE Benutzergruppe = '$row->Benutzergruppe' AND RubrikId='" .$_REQUEST['Id']. "'");
      $row_doc_perm = $sql_doc_perm->fetchrow();
      $permissions = @explode("|", $row_doc_perm->Rechte);
      $row->permissions = $permissions;

      array_push($groups,$row);
    }
    $sql = $GLOBALS['db']->Query("SELECT RubrikName FROM " . PREFIX . "_rubrics WHERE id = '".(int)$_REQUEST['Id']."'");
    $row = $sql->fetchrow();
    $GLOBALS['tmpl']->assign('RubrikName', $row->RubrikName);
    $GLOBALS['tmpl']->assign("groups", $groups);
    $GLOBALS['tmpl']->assign("felder", fetchFields());
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("rubs/rub_fields.tpl"));
  }

  function showRubTpl($show = '', $extern = '0') {

    if($extern==1) {
      $fetchId = (isset($_REQUEST['RubrikId']) && $_REQUEST['RubrikId'] != '') ? $_REQUEST['RubrikId'] : '0';
    } else {
      $fetchId = (isset($_REQUEST['Id']) && $_REQUEST['Id'] != '') ? $_REQUEST['Id'] : '0';
    }

    $sql = $GLOBALS['db']->Query("SELECT RubrikName,RubrikTemplate FROM " . PREFIX . "_rubrics WHERE Id = '" .$fetchId. "'");
    $row = $sql->fetchrow();
    $sql->Close();

    $tags = array();
    $ddid = array();
    $sql_rf = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_rubric_fields WHERE RubrikId = '" .$fetchId. "' ORDER BY rubric_position ASC");

    while($row_rf = $sql_rf->fetchrow()) {
      $row_rf->RealType = fetchFields(1,$row_rf->RubTyp);
      array_push($tags, $row_rf);
      if ($row_rf->RubTyp == 'dropdown') array_push($ddid, $row_rf->Id);
    }
    $sql_rf->Close();

    if($show == 1 ) $row->RubrikTemplate = stripslashes($_POST['RubrikTemplate']);

    if($extern==1) {
      $GLOBALS['tmpl']->assign("tags_row", $row);
      $GLOBALS['tmpl']->assign("tags", $tags);
      $GLOBALS['tmpl']->assign("ddid", implode(",", $ddid));
    } else {
      $GLOBALS['tmpl']->assign("row", $row);
      $GLOBALS['tmpl']->assign("tags", $tags);
      $GLOBALS['tmpl']->assign("formaction", "index.php?do=rubs&amp;action=template&amp;sub=save&amp;Id=".$fetchId. "&amp;cp=".SESSION. "");
      $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($this->_tpl));
    }
  }

  function showRubs($navi = '0') {

    $items = array();
    $sql_tpl = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_rubrics");
    $num_tpl = $sql_tpl->numrows();

    $page_limit = $this->_limit;
    $seiten = ceil($num_tpl / $page_limit);
    $set_start = prepage() * $page_limit - $page_limit;

    if($navi == 1) {
      $set_start = 0;
      $page_limit = 30;
    }

    $page_nav = pagenav($seiten, prepage(), " <a class=\"pnav\" href=\"index.php?do=rubs&page={s}&amp;cp=".SESSION. "\">{t}</a> ");
    if($num_tpl > $page_limit) $GLOBALS['tmpl']->assign("page_nav", $page_nav);

    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_rubrics LIMIT $set_start,$page_limit");

    while($row = $sql->fetchrow()) {
      $sql_rcount = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_documents WHERE RubrikId = '$row->Id'");
      $count_r = $sql_rcount->numrows();
      $sql_rcount->Close();

      $row->can_deleted = ($count_r >= 1) ? 0 : 1;

      $row->doc_count = $count_r;
      $row->RBenutzer = getUserById($row->RBenutzer);
      $row->VorlageName = getTemplateById($row->Vorlage);
      $row->AlleVorlagen = getAllTemplates();
      array_push($items, $row);
    }

    if($navi == 1) {
      $GLOBALS['tmpl']->assign("rub_items", $items);
    } else {
      $GLOBALS['tmpl']->assign("items", $items);
    }
  }

  function duplicate() {

    $errors = array();
    $ok = true;
    $sql = $GLOBALS['db']->Query("SELECT RubrikName FROM " . PREFIX . "_rubrics WHERE RubrikName='" .addslashes($_REQUEST['RubrikName']). "'");
    $row = $sql->fetchrow();

    if(@$row->RubrikName != '') {
      array_push($errors, $GLOBALS['config_vars']['RUBRIK_ERROR_EXIST']);
      $GLOBALS['tmpl']->assign("errors", $errors);
      $ok = false;
    }

    if($_REQUEST['RubrikName'] == '') {
      array_push($errors, $GLOBALS['config_vars']['RUBRIK_NO_NAME']);
      $GLOBALS['tmpl']->assign("errors", $errors);
      $ok = false;
    }


    if($ok) {
      reportLog($_SESSION["cp_uname"] . " - создал копию рубрики (".(int)$_REQUEST['oId']. ")",'2','2');

      $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_rubrics WHERE Id='" .(int)$_REQUEST['Id']. "'");
      $row = $sql->fetchrow();

      $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_rubrics (Id,RubrikName,RubrikTemplate,Vorlage,RBenutzer,RDatum) VALUES ('','" .htmlspecialchars($_REQUEST['RubrikName']). "','" . $row->RubrikTemplate . "', '$row->Vorlage','" .$_SESSION["cp_benutzerid"]. "','" .time(). "')");

      $iid = $GLOBALS['db']->InsertId();

      $sql_dr = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_document_permissions WHERE RubrikId='" .(int)$_REQUEST['Id']. "'");

      while($row_dr = $sql_dr->fetchrow()) {
        $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_document_permissions
        (Id,RubrikId,BenutzerGruppe,Rechte)
        VALUES
        ('','$iid','$row_dr->BenutzerGruppe','$row_dr->Rechte')
        ");
      }

      $sql_rf = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_rubric_fields WHERE RubrikId='" .(int)$_REQUEST['Id']. "' ORDER BY rubric_position ASC");

      while($row_rf = $sql_rf->fetchrow()) {
        $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_rubric_fields
        (Id, RubrikId, Titel, RubTyp, rubric_position, StdWert)
        VALUES
        ('', '$iid', '$row_rf->Titel', '$row_rf->RubTyp', '$row_rf->rubric_position', '$row_rf->StdWert')
        ");
      }

      echo '<script>window.opener.location.reload();window.close();</script>';
    }
  }

  function savePerms() {

    if(cp_perm('rub_perms')) {
      foreach ($_POST['Benutzergruppe'] as $id => $Bg) {
        $q = "SELECT Id FROM " . PREFIX . "_document_permissions WHERE Benutzergruppe='" .$_POST['Benutzergruppe'][$id]. "' AND RubrikId = '" .$_REQUEST['Id']. "'";
        $sql = $GLOBALS['db']->Query($q);
        $count = $sql->numrows();

        if($count < 1) {
          $rechte = @implode("|", $_POST['perm'][$id]);
          $q = "INSERT INTO " . PREFIX . "_document_permissions
            (Id,RubrikId,BenutzerGruppe,Rechte)
            VALUES
            ('','" .$_REQUEST['Id']. "','" .$_POST['Benutzergruppe'][$id]. "','" .$rechte. "')
            ";
          $GLOBALS['db']->Query($q);
        } else {
          $rechte = @implode("|", $_POST['perm'][$id]);
          $q = "UPDATE " . PREFIX . "_document_permissions
            set Rechte = '" .$rechte. "'
            WHERE RubrikId = '" .$_REQUEST['Id']. "' AND BenutzerGruppe = '" .$_POST['Benutzergruppe'][$id]. "'";
          $GLOBALS['db']->Query($q);
        }
      }
      header("Location:index.php?do=rubs&action=edit&Id=$_REQUEST[Id]&cp=".SESSION. "");
    } else {
      define("NOPERM", 1);
    }
  }

  function saveFields() {
    foreach ($_POST['del'] as $id => $Del) {

      if(!empty($_POST['del'][$id])) {
        $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_rubric_fields WHERE Id='$id' AND RubrikId='" .$_REQUEST['Id']. "'");
        $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_document_fields WHERE RubrikFeld = '" . $id . "'");
        reportLog($_SESSION["cp_uname"] . " - удалил поле рубрики (".$_POST['Titel'][$id]. ")",'2','2');
      }
    }

    foreach ($_POST['Titel'] as $id => $Titel) {

      if(!empty($_POST['Titel'][$id])) {
      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_rubric_fields
        SET
        Titel = '" .htmlspecialchars($_POST['Titel'][$id]). "',
        RubTyp = '" .$_POST['RubTyp'][$id]. "',
        rubric_position = '" .$_POST['RubPosition'][$id]. "',
        StdWert = '" .$_POST['StdWert'][$id]. "'
        WHERE Id = '$id'
        ");
      reportLog($_SESSION["cp_uname"] . " - отредактировал поле рубрики (".$_POST['Titel'][$id]. ")",'2','2');
      }
    }
    header("Location:index.php?do=rubs&action=edit&Id=$_REQUEST[Id]&cp=".SESSION. "");
    exit;
  }

  function newField() {

    if(!empty($_POST['TitelNew'])) {
      $pos = (!empty($_POST['RubPositionNew'])) ? $_POST['RubPositionNew'] : 1;
      reportLog($_SESSION["cp_uname"] . " - добавил поле рубрики (".$_POST['TitelNew']. ")",'2','2');

      $sql = $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_rubric_fields
        (Id, RubrikId,Titel,RubTyp,rubric_position,StdWert)
        VALUES
        ('','" .(int)$_REQUEST['Id']. "','" .$_POST['TitelNew']. "','" .$_POST['RubTypNew']. "','" .$pos. "','" .$_POST['StdWertNew']. "')
      ");

      $Update_RubrikFeld = $GLOBALS['db']->InsertId();
      $sql_rf = $GLOBALS['db']->Query("SELECT  Id FROM " . PREFIX . "_documents WHERE RubrikId = '" .$_REQUEST['Id']. "'");

      while($row_rf = $sql_rf->fetchrow()) {
        $sql = $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_document_fields
        (Id, RubrikFeld,DokumentId)
        VALUES
        ('', '$Update_RubrikFeld', '$row_rf->Id')
        ");
      }
    }

    header("Location:index.php?do=rubs&action=edit&Id=$_REQUEST[Id]&cp=".SESSION. "");
    exit;
  }

  function quickSave() {

    if(cp_perm('rub_edit')) {
      foreach ($_POST['RubrikName'] as $id => $Rn) {

      if(!empty($_POST['RubrikName'])) {
          $sql = $GLOBALS['db']->Query("SELECT RubrikName FROM " . PREFIX . "_rubrics WHERE RubrikName='" .$_POST['RubrikName'][$id]. "'");
          $row = $sql->fetchrow();
          $update_name = (@$row->RubrikName != '' || $_POST['RubrikName'][$id] == "") ? "" : "RubrikName='" .htmlspecialchars($_POST['RubrikName'][$id]). "',";
          $update_url = "UrlPrefix='" .$_POST['UrlPrefix'][$id]. "',";
          $GLOBALS['db']->Query("UPDATE " . PREFIX . "_rubrics SET $update_name $update_url Vorlage='" .$_POST['Vorlage'][$id]. "' WHERE Id='" .$id. "'");
        }
      }

      $p = (isset($_REQUEST['page']) && $_REQUEST['page'] != "") ? "&page=" . $_REQUEST['page'] : "" ;
      header("Location:index.php?do=rubs&cp=" . SESSION . "" . $p);
    } else {
      define("NOPERM", 1);
    }
  }
}
?>