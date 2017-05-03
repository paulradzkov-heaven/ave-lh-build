<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("ACP")) {
  header("Location:index.php");
  exit;

} else {

  include_once(SOURCE_DIR . "admin/functions/func.prefab.php");

  $GLOBALS['tmpl']->config_load(SOURCE_DIR . "/admin/lang/" . $_SESSION['cp_admin_lang'] . "/templates.txt");
  $config_vars = $GLOBALS['tmpl']->get_config_vars();

  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  $_REQUEST['action'] = (!isset($_REQUEST['action'])) ? '' : addslashes($_REQUEST['action']);
  $formaction = (isset($_REQUEST['action']) && $_REQUEST['action']=="new") ? "index.php?do=templates&amp;action=new&amp;sub=savenew" : "index.php?do=templates&amp;action=edit&amp;sub=save";
  $GLOBALS['tmpl']->assign("formaction", $formaction);

  switch($_REQUEST['action']) {

  case'':
    if(cp_perm('vorlagen')) {
      $items   = array();
      $sql_tpl = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_templates");
      $num_tpl = $sql_tpl->numrows();

      $page_limit = limit();
      $seiten     = ceil($num_tpl / $page_limit);
      $set_start  = prepage() * $page_limit - $page_limit;

      $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_templates LIMIT $set_start,$page_limit");

      $page_nav = pagenav($seiten, prepage(), " <a class=\"pnav\" href=\"index.php?do=templates&page={s}&amp;cp=".SESSION. "\">{t}</a> ");
      if($num_tpl > $page_limit) $GLOBALS['tmpl']->assign("page_nav", $page_nav);

      while($row = $sql->fetchrow()) {
        $row_rub = "";
        $sql_rub = $GLOBALS['db']->Query("SELECT Vorlage FROM " . PREFIX . "_rubrics WHERE Vorlage='$row->Id'");
        $row_rub = $sql_rub->fetchrow();

        if(@$row_rub->Vorlage != $row->Id) {
          $row->can_deleted = 1;
        }

        $row->TBenutzer = getUserById($row->TBenutzer);
        array_push($items, $row);
        unset($row);
      }

      $GLOBALS['tmpl']->assign("items", $items);
      $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("templates/templates.tpl"));
    } else {
      define("NOPERM", 1);
    }
  break;


  case 'new':
    if(cp_perm('vorlagen_neu')) {
      $_REQUEST['sub'] = (!isset($_REQUEST['sub'])) ? '' : addslashes($_REQUEST['sub']);
      switch($_REQUEST['sub']) {

        case 'savenew':
          $save = true;

          $row->Template = prettyCode($_REQUEST['Template']);
          $row->Template = stripslashes($row->Template);
          $row->TplName  = stripslashes($_REQUEST['TplName']);

          fetchPrefabTemplates();

          if(empty($_REQUEST['TplName']) || empty($_REQUEST['Template'])) {
            $save = false;
          }

          $check_code = strtolower($_REQUEST['Template']);
          if(isPhpCode($check_code) && cp_perm('vorlagen_php') ) {
            $GLOBALS['tmpl']->assign("php_forbidden", 1);
            $save = false;
          }

          if(!$save) {
            $GLOBALS['tmpl']->assign("row", $row);
            $GLOBALS['tmpl']->assign("tags", fetchTplTags(SOURCE_DIR . "/inc/data/vorlage.php"));
            $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("templates/form.tpl"));
          } else {
            $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_templates (Id,TplName,Template,TBenutzer,TDatum) VALUES ('','" .$_REQUEST['TplName']. "','" .prettyCode($_REQUEST['Template']). "','" .$_SESSION['cp_benutzerid']. "','" .time(). "')");
            reportLog($_SESSION["cp_uname"] . " - создал шаблон (".$_REQUEST['TplName']. ")",'2','2');
            header("Location:index.php?do=templates");
          }
        break;

        case '':
          fetchPrefabTemplates();
          $GLOBALS['tmpl']->assign("tags", fetchTplTags(SOURCE_DIR . "/inc/data/vorlage.php"));
          $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("templates/form.tpl"));
        break;
      }

    } else {
      define("NOPERM", 1);
    }
  break;


  case 'delete' :
    if(cp_perm('vorlagen_loesch')) {

      $sql_rub = $GLOBALS['db']->Query("SELECT Vorlage FROM " . PREFIX . "_rubrics WHERE Vorlage = '" .(int)$_REQUEST['Id']. "'");
      $Used = $sql_rub->numrows();

      if($Used >= 1 || $_REQUEST['Id'] == 1) {
        reportLog($_SESSION["cp_uname"] . " - попытка удаления основного шаблона (".(int)$_REQUEST['Id']. ")",'2','2');
        header("Location:index.php?do=templates");
        exit;

      } else {

        $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_templates WHERE Id = '" .(int)$_REQUEST['Id']. "'");
        $GLOBALS['db']->Query("ALTER TABLE " . PREFIX . "_templates PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =1");
        reportLog($_SESSION["cp_uname"] . " - удалил шаблон (".(int)$_REQUEST['Id']. ") ",'2','2');
        header("Location:index.php?do=templates");
        exit;
      }

    } else {
      define("NOPERM", 1);
    }
  break;


  case 'edit':
  if(cp_perm('vorlagen_edit')) {
    $_REQUEST['sub'] = (!isset($_REQUEST['sub'])) ? '' : addslashes($_REQUEST['sub']);
    switch($_REQUEST['sub']) {

      case '':
        $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_templates WHERE Id='" .addslashes($_REQUEST['Id']). "'");
        $row = $sql->fetchrow();

        $check_code = strtolower($row->Template);
        if(isPhpCode($check_code) && !cp_perm('vorlagen_php') ) {
          $GLOBALS['tmpl']->assign("php_forbidden", 1);
          $GLOBALS['tmpl']->assign("read_only", "readonly");
        }

        $GLOBALS['tmpl']->assign("tags", fetchTplTags(SOURCE_DIR . "/inc/data/vorlage.php"));

        $row->Template = prettyCode($row->Template);
        $row->Template = stripslashes($row->Template);
        $GLOBALS['tmpl']->assign("row", $row);
      break;

      case 'save':
        $ok = true;
        $check_code = strtolower($_REQUEST['Template']);
        if(isPhpCode($check_code) && !cp_perm('vorlagen_php') ) {
          reportLog($_SESSION["cp_uname"] . " - попытка использования PHP кода в шаблоне (".$_REQUEST['TplName']. ")",'2','2');
          $GLOBALS['tmpl']->assign("php_forbidden", 1);
          $ok = false;
        }

        if(!$ok) {
          reportLog($_SESSION["cp_uname"] . " - ошибка при изменении шаблона (".$_REQUEST['TplName']. ")",'2','2');
          $row->Template = stripslashes($_REQUEST['Template']);
          $GLOBALS['tmpl']->assign("row", $row);
          $GLOBALS['tmpl']->assign("tags", fetchTplTags(SOURCE_DIR . "/inc/data/vorlage.php"));

        } else {
          reportLog($_SESSION["cp_uname"] . " - изменил шаблон (".$_REQUEST['TplName']. ")",'2','2');
          $q = "UPDATE " . PREFIX . "_templates SET TplName = '" .$_REQUEST['TplName']. "', Template = '" .$_REQUEST['Template']. "' WHERE Id = '" .(int)$_REQUEST['Id']. "'";
          $GLOBALS['db']->Query($q);
          header("Location:?do=templates");
        }

      break;
    }

    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("templates/form.tpl"));
  } else {
    define("NOPERM", 1);
  }
  break;


  case 'multi':
  if(cp_perm('vorlagen_multi')) {
    $_REQUEST['sub'] = (!isset($_REQUEST['sub'])) ? '' : addslashes($_REQUEST['sub']);
    $errors = array();
    switch($_REQUEST['sub']) {

      case 'save':
        $ok = true;
        $sql = $GLOBALS['db']->Query("SELECT TplName FROM " . PREFIX . "_templates WHERE TplName='" .addslashes($_REQUEST['TplName']). "'");
        $row = $sql->fetchrow();

        if(@$row->TplName != '') {
          array_push($errors, $GLOBALS['config_vars']['TEMPLATES_EXIST']);
          $GLOBALS['tmpl']->assign("errors", $errors);
          $ok = false;
        }

        if($_REQUEST['TplName'] == '') {
          array_push($errors, $GLOBALS['config_vars']['TEMPLATES_NO_NAME']);
          $GLOBALS['tmpl']->assign("errors", $errors);
          $ok = false;
        }

        if($ok) {
          reportLog($_SESSION["cp_uname"] . " - создал копию шаблона (".(int)$_REQUEST['oId']. ")",'2','2');
          $sql = $GLOBALS['db']->Query("SELECT Template FROM " . PREFIX . "_templates WHERE Id='" .(int)$_REQUEST['Id']. "'");
          $row = $sql->fetchrow();

          $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_templates (Id,TplName,Template,TBenutzer,TDatum) VALUES ('','" .htmlspecialchars($_REQUEST['TplName']). "','" .addslashes($row->Template). "','" .$_SESSION["cp_benutzerid"]. "','" .time(). "')");
          echo '<script>window.opener.location.reload();window.close();</script>';
        }

      break;
    }

    } else {
      define("NOPERM", 1);
    }
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("templates/multi.tpl"));
  break;
  }

}
?>