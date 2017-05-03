<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

// Определяем базовый класс по работе с модулем
class Newsarchive {

  // Метод, отвечающий за вывод списка всех архивов в Панели управления
  function archiveList($tpl_dir) {
    $archives = array();
    $ids = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_newsarchive");
    while ($result = $sql->fetchrow()){
      array_push($archives, $result);
    }

    $cnt = count($archives);

    for($i=0; $i< $cnt; $i++) {
      $ids[] = explode(",",$archives[$i]->rubs);
    }

    $sql_2 = $GLOBALS['db']->Query("SELECT Id, RubrikName FROM " . PREFIX . "_rubrics");

    while($result_2 = $sql_2->fetchrow()){
      for($i=0; $i< $cnt; $i++) {
         if (in_array($result_2->Id, $ids[$i])){
          @$archives[$i]->RubrikName = strstr(@$archives[$i]->RubrikName.", " . @$result_2->RubrikName, " ");
        }
      }
    }
    $GLOBALS['tmpl']->assign("archives", $archives);
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_archive_list.tpl"));
  }

  // Метод, отвечающий за добавление нового архива в Панели управления
  function addArchive(){
    $arc_name = htmlspecialchars($_POST['new_arc']);
    $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_newsarchive VALUES ('','" . $arc_name . "', '', '1','1')");
    header("Location:index.php?do=modules&action=modedit&mod=newsarchive&moduleaction=1&cp=" . SESSION);

  }

  // Метод, отвечающий за удаление выбранного архива из Панели управления
  function delArchive(){
    $id = addslashes($_GET['id']);
    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_newsarchive WHERE id = '" . $id . "'");
    header("Location:index.php?do=modules&action=modedit&mod=newsarchive&moduleaction=1&cp=" . SESSION);
  }

  // Метод, отвечающий за схранение изменений в списке всех архивов в Панели управления
  function saveList(){
    foreach($_POST['arc_name'] as $id => $arc_name) {
      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_newsarchive SET arc_name = '" . $arc_name . "' WHERE id = '" . $id . "'");
    }
    header("Location:index.php?do=modules&action=modedit&mod=newsarchive&moduleaction=1&cp=" . SESSION);
  }

  // Метод, отвечающий за вывод данных для редактирования для выбранного архива
  function editArchive($tpl_dir){
    $id = intval($_GET['id']);
    $rubs = array();

    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_newsarchive WHERE id = '" . $id . "'");
    $archives = $sql->fetchrow();

    $sql_2 = $GLOBALS['db']->Query("SELECT Id, RubrikName FROM " . PREFIX . "_rubrics");

    $ids = explode(",",$archives->rubs);
    $count = count($ids);

    while($result_2 = $sql_2->fetchrow()) {
      if(in_array($result_2->Id, $ids)) {
        $result_2->sel = 1;
        array_push($rubs, $result_2);
      } else {
        $result_2->sel = 0;
        array_push($rubs, $result_2);
      }
    }

    $GLOBALS['tmpl']->assign("archives", $archives);
    $GLOBALS['tmpl']->assign("rubs", $rubs);
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_archive_edit.tpl"));
  }

  // Метод, отвечающий за сохранение изменений для редактируемого архива
  function saveArchive() {
      $id = intval($_POST['id']);
      $arc_name = htmlspecialchars($_POST['arc_name']);
      $data = implode(",", $_POST['rubs']);
      $show_days = $_POST['show_days'];
      $show_empty = $_POST['show_empty'];

      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_newsarchive
        SET
          arc_name = '" . $arc_name . "',
          rubs = '" . $data . "',
          show_days = '" . $show_days . "',
          show_empty = '" . $show_empty . "'
        WHERE
          id = '" . $id . "'
        ");
      header("Location:index.php?do=modules&action=modedit&mod=newsarchive&moduleaction=1&cp=" . SESSION);
  }

  // Метод, отвечающий за вывод списка месяцев в публичной части сайта (Основная функция вывода)
  function showArchive($tpl_dir, $id) {
    $months = array(null, "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
    $mid    = array(null, "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
    $dd     = array();

    $query = $GLOBALS['db']->Query("
      SELECT
        MONTH(FROM_UNIXTIME(DokStart)) AS `month`,
        YEAR(FROM_UNIXTIME(DokStart)) AS `year`,
        COUNT(*) AS nums,
        show_empty
      FROM
        " . PREFIX . "_documents AS doc,
        " . PREFIX . "_modul_newsarchive AS arc
      WHERE RubrikId IN (rubs)
        AND arc.id = '" . $id . "'
        AND doc.Id != 1
        AND doc.Id != 2
        AND Geloescht != 1
        AND DokStatus != 0
        AND DokStart > UNIX_TIMESTAMP(DATE_FORMAT((CURDATE() - INTERVAL 11 MONTH),'%Y-%m-01'))
        AND (DokEnde = 0 || DokEnde > UNIX_TIMESTAMP())
        AND (DokStart = 0 || DokStart < UNIX_TIMESTAMP())
      GROUP BY `month` ORDER BY `year`,`month` DESC
    ");

    while ($res = $query->fetchrow()) {
      $res->mid   = $mid[$res->month];
      $res->month = $months[$res->month];
      array_push($dd, $res);
    }

    $GLOBALS['tmpl']->assign("archiveid", $id);
    $GLOBALS['tmpl']->assign("months", $dd);
    $GLOBALS['tmpl']->display($tpl_dir . 'public_archive-' . $id . '.tpl');
  }
}
?>