<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class Query {

  var $_limit = 15;

  function showQueries($extern = '') {
    $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_queries");
    $num = $sql->NumRows();
    $sql->Close();

    $limit  = $this->_limit;
    $seiten = ceil($num / $limit);
    $start  = prepage() * $limit - $limit;

    if($extern != '') {
      $start = 0;
      $limit = 10000;
    }

    $items = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_queries ORDER BY Titel ASC LIMIT $start,$limit");
    while($row = $sql->fetchrow()) {
      $row->Autor = getUserById($row->Autor);
      array_push($items, $row);
    }

    $page_nav = pagenav($seiten, prepage(), " <a class=\"pnav\" href=\"index.php?do=queries&page={s}&amp;cp=".SESSION. "\">{t}</a> ");
    if($num > $limit) $GLOBALS['tmpl']->assign("page_nav", $page_nav);

    if($extern != '') {
      $GLOBALS['tmpl']->assign("conditions", $items);
    } else {
      $GLOBALS['tmpl']->assign("items", $items);
      $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("queries/queries.tpl"));
    }
  }

  function copyQuery($id) {
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_queries WHERE Id = '$id'");
    $row = $sql->fetcharray();
    $sql->Close();

    $q = "INSERT INTO " . PREFIX . "_queries (
          Id,
          RubrikId,
          Zahl,
          Titel,
          Template,
          AbGeruest,
          Sortierung,
          Autor,
          Erstellt,
          Beschreibung,
          AscDesc,
          Navi
        ) VALUES (
          '',
          '$row[RubrikId]',
          '$row[Zahl]',
          '" . substr($_REQUEST['cname'],0,25) . "',
          '" . addslashes($row['Template']). "',
          '" . addslashes($row['AbGeruest']). "',
          '$row[Sortierung]',
          '" . $_SESSION["cp_benutzerid"] . "',
          '" .time(). "',
          '$row[Beschreibung]',
          '$row[AscDesc]',
          '$row[Navi]'
        )";
      $GLOBALS['db']->Query($q);

    $iid = $GLOBALS['db']->InsertId();
    reportLog($_SESSION["cp_uname"] . " - создал копию запроса ($id)",'2','2');

    $sql_ak = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_queries_conditions WHERE Abfrage = '$id'");

    while($row_ak = $sql_ak->fetcharray()) {
      $q = "INSERT INTO " . PREFIX . "_queries_conditions (
          Id,
          Abfrage,
          Operator,
          Feld,
          Wert,
          Oper
        ) VALUES (
          '',
          '" . $iid . "',
          '" . $row_ak['Operator'] . "',
          '" . $row_ak['Feld'] . "',
          '" . $row_ak['Wert'] . "',
          '" . $row_ak['Oper'] . "'
        )";
      $GLOBALS['db']->Query($q);

    }
    $sql_ak->Close();

    header("Location:index.php?do=queries&cp=" . SESSION);
    exit;
  }

  function editQuery($id) {
    switch($_REQUEST['sub']) {

      case '':
        $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_queries WHERE Id = '$id'");
        $row = $sql->fetchrow();

        $ak = array();
        $sql_ak = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_queries_conditions WHERE Abfrage = '$row->Id'");
        while($row_ak = $sql_ak->fetchrow()) {
          array_push($ak, $row_ak);
        }
        $row->Ak = $ak;

        $GLOBALS['tmpl']->assign("row", $row);
        $GLOBALS['tmpl']->assign("formaction", "index.php?do=queries&amp;action=edit&amp;sub=save&amp;Id=$id&amp;cp=" . SESSION);
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("queries/form.tpl"));
      break;


      case 'save':
        $GLOBALS['db']->Query("UPDATE " . PREFIX . "_queries
          SET
          Titel = '$_REQUEST[Titel]',
          RubrikId='$_REQUEST[RubrikId]',
          Zahl = '$_REQUEST[Zahl]',
          Template = '$_REQUEST[Template]',
          AbGeruest = '$_REQUEST[AbGeruest]',
          Sortierung = '$_REQUEST[Sortierung]',
          Beschreibung = '$_REQUEST[Beschreibung]',
          AscDesc = '$_REQUEST[AscDesc]',
          Navi = '$_REQUEST[Navi]'
        WHERE Id = '$_REQUEST[Id]'");
        reportLog($_SESSION["cp_uname"] . " - отредактировал запрос ($_REQUEST[Titel])",'2','2');

        if($_REQUEST['pop']==1) {
          echo "<script>self.close();</script>";
        } else {
          header("Location:index.php?do=queries&cp=" . SESSION);
          exit;
        }
      break;
    }
  }

  function newQuery() {
    switch($_REQUEST['sub']) {

      case '':
        $GLOBALS['tmpl']->assign("formaction", "index.php?do=queries&amp;action=new&amp;sub=save&amp;cp=" . SESSION);
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("queries/form.tpl"));
      break;


      case 'save':
        $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_queries (
          Id,
          RubrikId,
          Zahl,
          Titel,
          Template,
          AbGeruest,
          Sortierung,
          Autor,
          Erstellt,
          Beschreibung,
          AscDesc,
          Navi
        ) VALUES (
          '',
          '$_REQUEST[RubrikId]',
          '$_REQUEST[Zahl]',
          '$_REQUEST[Titel]',
          '$_REQUEST[Template]',
          '$_REQUEST[AbGeruest]',
          '$_REQUEST[Sortierung]',
          '" . $_SESSION["cp_benutzerid"] . "',
          '" .time(). "',
          '$_REQUEST[Beschreibung]',
          '$_REQUEST[AscDesc]',
          '$_REQUEST[Navi]'
        )");

        $iid = $GLOBALS['db']->InsertId();
        reportLog($_SESSION["cp_uname"] . " - добавил новый запрос ($_REQUEST[Titel])",'2','2');

        if($_REQUEST['reedit']==1) {
          header("Location:index.php?do=queries&action=edit&Id=" . $iid . "&cp=" . SESSION . "&RubrikId=$_REQUEST[RubrikId]");
        } else {
          header("Location:index.php?do=queries&cp=" . SESSION);
        }
        exit;
      break;
    }
  }

  function editConditions($id) {

    switch($_REQUEST['sub']) {

      case '':
        $felder = array();
        $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_rubric_fields WHERE RubrikId = '" .$_REQUEST['RubrikId']. "'");
        while($row = $sql->fetchrow()) {
          array_push($felder, $row);
        }

        $afkonditionen = array();
        $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_queries_conditions WHERE Abfrage = '" .$_REQUEST['Id']. "'");
        while($row = $sql->fetchrow()) {
          array_push($afkonditionen, $row);
        }

        $sql = $GLOBALS['db']->Query("SELECT Titel FROM " . PREFIX . "_queries WHERE id = '$id'");
        $row = $sql->fetchrow();

        $GLOBALS['tmpl']->assign('QureyName', $row->Titel);
        $GLOBALS['tmpl']->assign("felder", $felder);
        $GLOBALS['tmpl']->assign("afkonditionen", $afkonditionen);
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("queries/conditions.tpl"));
      break;


      case 'save':
        if(!empty($_POST['Wert_Neu'])) {
          $q = "INSERT INTO " . PREFIX . "_queries_conditions (
              Id,
              Abfrage,
              Operator,
              Feld,
              Wert,
              Oper
            ) VALUES (
              '',
              '" . $_REQUEST['Id'] . "',
              '" . $_POST['Operator_Neu'] . "',
              '" . $_POST['Feld_Neu'] . "',
              '" . $_POST['Wert_Neu'] . "',
              '" . $_POST['Oper_Neu'] . "'
            )";

          $GLOBALS['db']->Query($q);

          $iid = $GLOBALS['db']->InsertId();
          reportLog($_SESSION["cp_uname"] . " - добавил условие для запроса ($iid)",'2','2');
        }


        if(is_array($_POST['Feld'])) {

          foreach($_POST['Feld'] as $id => $F) {

            if(!empty($_POST['Wert'][$id])) {
              $q = "UPDATE " . PREFIX . "_queries_conditions SET
                Abfrage = '" . $_REQUEST['Id'] . "',
                Operator = '" . $_POST['Operator'][$id] . "',
                Feld = '" . $_POST['Feld'][$id] . "',
                Wert = '" . $_POST['Wert'][$id] . "',
                Oper = '" . $_POST['Oper_Neu'] . "'
                WHERE Id = '" . $id . "'
                ";
              $GLOBALS['db']->Query($q);
              reportLog($_SESSION["cp_uname"] . " - изменил условия для запроса ($id)",'2','2');
            }
          }
        }

        foreach($_POST['del'] as $id => $F) {
          $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_queries_conditions WHERE Id = '" . $id . "'");
          reportLog($_SESSION["cp_uname"] . " - удалил условия для запроса ($id)",'2','2');
        }

        header("Location:index.php?do=queries&action=konditionen&RubrikId=$_REQUEST[RubrikId]&Id=$_REQUEST[Id]&pop=1&cp=" . SESSION);
        exit;
      break;
      }
  }

  function deleteQuery($id) {
    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_queries WHERE Id = '" . $id . "'");
    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_queries_conditions WHERE Abfrage = '" . $id . "'");
    reportLog($_SESSION["cp_uname"] . " - удалил запрос ($id)",'2','2');
    header("Location:index.php?do=queries&cp=" . SESSION);
    exit;
  }
}
?>