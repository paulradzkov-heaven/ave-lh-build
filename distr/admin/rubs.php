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

  include_once(SOURCE_DIR . "/class/class.rubs.php");
  $cpRub =& new rubs;

  $cpRub->showRubs(1);
  $GLOBALS['tmpl']->assign("navi", $GLOBALS['tmpl']->fetch("navi/navi.tpl"));

  $GLOBALS['tmpl']->config_load(SOURCE_DIR . "/admin/lang/" . $_SESSION['cp_admin_lang'] . "/rubs.txt", 'rubs');
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  $_REQUEST['sub']    = (!isset($_REQUEST['sub']))    ? '' : addslashes($_REQUEST['sub']);
  $_REQUEST['action'] = (!isset($_REQUEST['action'])) ? '' : addslashes($_REQUEST['action']);
  $_REQUEST['submit'] = (!isset($_REQUEST['submit'])) ? '' : addslashes($_REQUEST['submit']);

  switch($_REQUEST['action']) {

    case '' :
      if(cp_perm('rubs')) {
        switch($_REQUEST['sub']) {
          case 'quicksave':
            $cpRub->quickSave();
          break;
        }

        $cpRub->showRubs();

      } else {
        define("NOPERM", 1);
      }
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("rubs/rubs.tpl"));
    break;

    case 'new':
      if(cp_perm('rub_neu')) {
        $cpRub->newRub();
      } else {
        define("NOPERM", 1);
      }
    break;


    case 'template':
      if(cp_perm('rub_edit')) {
        $_REQUEST['sub'] = (!isset($_REQUEST['sub'])) ? '' : addslashes($_REQUEST['sub']);
        switch($_REQUEST['sub']) {

          case '':
            $cpRub->showRubTpl();
          break;

          case 'save':
            $Rtemplate = $_POST['RubrikTemplate'];
            $check_code = strtolower($Rtemplate);
            $ok = true;

            if(isPhpCode($check_code) && !cp_perm('rub_php') ) {
              $GLOBALS['tmpl']->assign("php_forbidden", 1);
              $ok = false;

            }

            if(!$ok) {
              $cpRub->showRubTpl(1);
            } else {
              $cpRub->saveRubTpl($_POST['RubrikTemplate']);
            }
          break;
        }

      } else {
        define("NOPERM", 1);
      }
    break;


    case 'delete':
      if(cp_perm('rub_loesch')) {
        $cpRub->delRub();
      } else {
        define("NOPERM", 1);
      }
    break;


    case 'multi':
      if(cp_perm('rub_multi')) {
        $_REQUEST['sub'] = (!isset($_REQUEST['sub'])) ? '' : addslashes($_REQUEST['sub']);

        switch($_REQUEST['sub']) {

          case 'save':
            $cpRub->duplicate();
          break;
        }
      } else {
        define("NOPERM", 1);
      }
      $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("rubs/multi.tpl"));
    break;


    case 'edit':
      if(cp_perm('rub_edit')) {
        $_REQUEST['sub'] = (!isset($_REQUEST['sub'])) ? '' : addslashes($_REQUEST['sub']);
        switch($_REQUEST['sub']) {

          case '':
            switch($_REQUEST['submit']) {

              case 'saveperms':
                $cpRub->savePerms();
              break;

              case 'save':
                $cpRub->saveFields();
              break;

              case 'next':
                header("Location:index.php?do=rubs&action=template&Id=$_REQUEST[Id]&cp=".SESSION. "");
                exit;
              break;

              case 'newfield':
                $cpRub->newField();
              break;
            }

            $cpRub->fetchRubDetails();
          break;
        }

      } else {
        define("NOPERM", 1);
      }
    break;
  }

}
?>