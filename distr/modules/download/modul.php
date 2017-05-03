<?php
$modul['ModulName'] = "Download";
$modul['ModulPfad'] = "download";
$modul['ModulVersion'] = "2.0";
$modul['Beschreibung'] = "Download-System ��� cpengine, � ������� ����������� ��������� ����� �� ���������� �����.";
$modul['Autor'] = "cron";
$modul['MCopyright'] = "cron";
$modul['Status'] = 1;
$modul['IstFunktion'] = 0;
$modul['ModulTemplate'] = 1;
$modul['AdminEdit'] = 1;
$modul['ModulFunktion'] = "NULL";
$modul['CpEngineTagTpl'] = "<b>������:</b> <a target='_blank' href='../index.php?module=download'>/index.php?module=download</a>";
$modul['CpEngineTag'] = "NULL";
$modul['CpPHPTag'] = "NULL";

if( (isset($_REQUEST['module']) && $_REQUEST['module'] == 'download') || (isset($_REQUEST['mod']) && $_REQUEST['mod'] == 'download') )
{
  if(defined('ACP') && $_REQUEST['action'] != 'delete')
  {
    $modul_sql_deinstall = array();
    $modul_sql_install = array();
    include_once(BASE_DIR . '/modules/download/sql.php');
  }

  if(defined("SSLMODE") && SSLMODE==1 && $_SERVER['SERVER_PORT']=='80') header("Location:".redir()."");
  if(defined("SSLMODE") && SSLMODE!=1 && $_SERVER['SERVER_PORT']=='443') header("Location:".redir()."");

  //=======================================================
  // Klasse einbinden
  //=======================================================
  if(!defined('BASE_DIR')) exit;
  if(defined('ACP') && $_REQUEST['action'] != 'delete')
  {
    include_once(BASE_DIR . '/modules/download/class.download_admin.php');
  } else {
    include_once(BASE_DIR . '/modules/download/class.download.php');
  }
  include_once(BASE_DIR . '/functions/func.modulglobals.php');
  #include_once(BASE_DIR . '/modules/download/funcs/func.parent_categ.php');
  include_once(BASE_DIR . '/modules/download/funcs/func.rewrite.php');

  if(defined('T_PATH')) $GLOBALS['tmpl']->assign('download_images', 'templates/' . T_PATH . '/modules/download/');

  if(defined('T_PATH')) $GLOBALS['tmpl']->assign('cp_theme', T_PATH);

  $_REQUEST['action'] = (!isset($_REQUEST['action']) || $_REQUEST['action'] == '') ? 'downloadstart' : $_REQUEST['action'];
  if(isset($_REQUEST['module']) && $_REQUEST['module'] != '' && $_REQUEST['module'] == 'download' && isset($_REQUEST['action']))
  {
    modulGlobals('download');
    $download = new Download;

    switch($_REQUEST['action'])
    {
      case 'downloadstart':
        $download->overView();
      break;

      case 'showfile':
        $download->showFile($_GET['file_id']);
      break;

      case 'get_file':
        $download->getFile($_GET['file_id']);
      break;

      case 'get_nouserpay_file':
        $download->getNoUserPayFile($_GET['file_id'],$_GET['diff'],$_GET['val']);
      break;

      case 'get_nopay_file':
        $download->getNoPayFile($_GET['file_id']);
      break;

      case 'get_notmine_file':
        $download->getNotMineFile($_GET['file_id']);
      break;

      case 'get_denied':
        $download->getDenied($_GET['file_id']);
      break;

      case 'checkR':
        $download->checkRecommend();
      break;

      case 'secure':
        $sc = eregi_replace('[^A-Za-z�-��-���0-9]', '', $_REQUEST['scode']);
        $download->ajaxSecure($sc);
      break;

      case 'pay':
        $download->addMoney($_GET['file_id']);
      break;

      case 'toreg':
        $download->toReg($_GET['file_id']);
      break;

      case 'pay_success':
        $download->Success_pay();
      break;

      case 'pay_fail':
        $download->Fail_pay();
      break;
    }
  }

  //=======================================================
  // Admin - Aktionen
  //=======================================================
  if(defined('ACP') && $_REQUEST['action'] != 'delete')
  {
    $tpl_dir = BASE_DIR . '/modules/download/templates_admin/';
    $tpl_dir_source = BASE_DIR . '/modules/download/templates_admin';
    $lang_file = BASE_DIR . '/modules/download/lang/' . $_SESSION['cp_admin_lang'] . '.txt';

    $download = new Download;

    $GLOBALS['tmpl']->config_load($lang_file, "admin");
    $config_vars = $GLOBALS['tmpl']->get_config_vars();
    $GLOBALS['tmpl']->assign("config_vars", $config_vars);
    $GLOBALS['tmpl']->assign('source', $tpl_dir_source);

    if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '')
    {
      switch($_REQUEST['moduleaction'])
      {
        // Kommentare
        case '1':
          $download->overView($tpl_dir);
        break;

        case 'categs':
          $download->categs($tpl_dir);
        break;

        case 'new_categ':
          $download->newCateg($tpl_dir,$_REQUEST['Id']);
        break;

        case 'edit_categ':
          $download->editCateg($tpl_dir,$_REQUEST['Id']);
        break;

        case 'delcateg':
          $download->delCategAll($_REQUEST['Id']);
        break;

        case 'overview':
          $download->overView($tpl_dir);
        break;

        case 'edit':
          $download->editDownload($tpl_dir,$_REQUEST['Id']);
        break;

        case 'new':
          $download->newDownload($tpl_dir);
        break;

        case 'licenses':
          $download->Licenses($tpl_dir);
        break;

        case 'languages':
          $download->Languages($tpl_dir);
        break;

        case 'gosettings':
          $download->Settings($tpl_dir);
        break;

        case 'editcomments':
          $download->editComments($tpl_dir,$_REQUEST['Id']);
        break;

        case 'systems':
          $download->Systems($tpl_dir);
        break;

        case 'setmodus':
          $download->SetModule($_REQUEST['Status'],$_REQUEST['Id']);
        break;

        case 'payhist':
          $download->ShowPayHist($tpl_dir);
        break;
      }
    }
  }
}
?>