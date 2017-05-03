<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class Rss {

  function rssList($tpl_dir)
  {
    $channels = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_rss");
    while ($result = $sql->fetchrow()) {
      $result->tag = "[rss:" . $result->id . "]";
      array_push($channels, $result);
    }

    $GLOBALS['tmpl']->assign("channel", $channels);
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "rss_list.tpl"));
  }

  function rssAdd()
  {
    $rss_name = htmlspecialchars($_POST['new_rss']);
    $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_rss VALUES ('', '" . $rss_name . "', '', '', 0, 0, 0, 10, 200)");
    header("Location:index.php?do=modules&action=modedit&mod=rss&moduleaction=1&cp=" . SESSION);
  }

  function rssDelete()
  {
    $id = addslashes($_GET['id']);
    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_rss WHERE id = '" . $id . "'");
    header("Location:index.php?do=modules&action=modedit&mod=rss&moduleaction=1&cp=" . SESSION);
  }

  function rssEdit($tpl_dir)
  {
    $id = (int)($_GET['id']);
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_rss WHERE id = '" . $id . "'");
    $result = $sql->fetchrow();

    if (isset($_REQUEST['RubrikId']) && is_numeric($_REQUEST['RubrikId'])) {
      $result->rub_id = (int)$_REQUEST['RubrikId'];
    }

    $rubriks = array();
    $get_rubs = $GLOBALS['db']->Query("SELECT Id, RubrikName FROM " . PREFIX . "_rubrics");
    while ($res = $get_rubs->fetchrow()) {
      array_push($rubriks,$res);
    }

    $fields = array();
    $get_fields = $GLOBALS['db']->Query("SELECT Id, RubrikId, Titel FROM " . PREFIX . "_rubric_fields WHERE RubrikId = '" . $result->rub_id . "'");
    while ($res = $get_fields->fetchrow()) {
      array_push($fields,$res);
    }

    $GLOBALS['tmpl']->assign("channel", $result);
    $GLOBALS['tmpl']->assign("rubriks", $rubriks);
    $GLOBALS['tmpl']->assign("fields", $fields);
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "rss_edit.tpl"));
  }

  function rssSave()
  {
    $rss_name  = htmlspecialchars(addslashes($_POST['rss_name']));
    $id        = (int)$_POST['id'];
    $rub_id    = (int)$_POST['rub_id'];
    $rss_des   = htmlspecialchars($_POST['site_descr']);
    $site_url  = $_POST['site_url'];
    $title     = (int)$_POST['field_title'];
    $descr     = (int)$_POST['field_descr'];
    $limit     = (int)$_POST['rss_on_page'];
    $lenght    = (int)$_POST['rss_lenght'];
    $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_rss
      SET
        rss_name = '" . $rss_name . "',
        rss_descr = '" . $rss_des . "',
        site_url = '" . $site_url . "',
        rub_id = '" . $rub_id . "',
        title_id = '" . $title . "',
        descr_id = '" . $descr . "',
        on_page = '" . $limit . "',
        lenght = '" . $lenght . "'
      WHERE
        id = '" . $id . "'
      ");
    header("Location:index.php?do=modules&action=modedit&mod=rss&moduleaction=1&cp=" . SESSION);
  }
}
?>