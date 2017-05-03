<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit;

$modul['ModulName'] = "Авторизация";
$modul['ModulPfad'] = "login";
$modul['ModulVersion'] = "2.1";
$modul['Beschreibung'] = "Данный модуль предназначен для регистрации пользователей на вашем сайте. Для вывода формы авторизации, разместите системный тег <strong>[cp:loginform]</strong> в нужном месте вашего шаблона. Также вы можете указать шаблон, в котором будет отображена форма для регистрации и авторизации.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['ModulTemplate'] = 1;
$modul['AdminEdit'] = 1;
$modul['ModulFunktion'] = "cpLogin";
$modul['CpEngineTagTpl'] = "[cp:loginform]";
$modul['CpEngineTag'] = "\\\[cp:loginform\\\]";
$modul['CpPHPTag'] = "<?php cpLogin(); ?>";

include_once(BASE_DIR . '/modules/login/sql.php');

if(isset($_REQUEST['print']) && $_REQUEST['print']==1 && isset($_REQUEST['module']) && $_REQUEST['module'] == 'login') PrintError();

if(!@include(BASE_DIR . '/modules/login/class.login.php')) ModuleError();

$tpl_dir   = BASE_DIR . '/modules/login/templates/';
$lang_file = BASE_DIR . '/modules/login/lang/' . STD_LANG . '.txt';

$login =& new Login;

function cpLogin() {

  $tpl_dir   = BASE_DIR . '/modules/login/templates/';
  $lang_file = BASE_DIR . '/modules/login/lang/' . STD_LANG . '.txt';

  $login =& new Login;

  if(!isset($_SESSION["cp_benutzerid"])) {

    if(!$login->checkAutoLogin()) {
      $login->displayLoginform($tpl_dir,$lang_file);
    } else {
      $login->displayPanel($tpl_dir,$lang_file);
    }

  } else {
    $login->displayPanel($tpl_dir,$lang_file);
  }
}

if(!defined('ACP')  && isset($_REQUEST['action']) && isset($_REQUEST['module']) && $_REQUEST['module'] == 'login') {

  $login =& new Login;

  switch($_REQUEST['action']) {

    case 'wys':
      if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'on') {
        if(cp_perm('docs')) {
          $_SESSION['cp_adminmode'] = 1;
        }
        header('Location:' . $_SERVER['HTTP_REFERER']);
        exit;
      } else {
        unset($_SESSION['cp_adminmode']);
        header('Location:' . $_SERVER['HTTP_REFERER']);
        exit;
      }
    break;

    case 'wys_adm':
      if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'on') {
        if(cp_perm('docs')) {
          $_SESSION['cp_adminmode'] = 1;
        }
        header('Location:' . $_REQUEST["HTTP_HOST"]."/");
        exit;
      } else {
        unset($_SESSION['cp_adminmode']);
        header('Location:' . $_REQUEST["HTTP_HOST"]."/");
        exit;
      }
    break;

    case 'login':
      $login->loginProcess($tpl_dir,$lang_file);
    break;

    case 'logout':
      $login->loginProcess($tpl_dir,$lang_file,1);
    break;

    case 'register':
      $login->registerNew($tpl_dir,$lang_file);
    break;

    case 'passwordreminder':
      $login->passwordReminder($tpl_dir,$lang_file);
    break;

    case 'passwordchange':
      $login->passwordChange($tpl_dir,$lang_file);
    break;

    case 'delaccount':
      $login->delAccount($tpl_dir,$lang_file);
    break;

    case 'profile':
      $login->myProfile($tpl_dir,$lang_file);
    break;

  }
}


if(defined("ACP")) {

  $tpl_dir   = BASE_DIR . '/modules/login/templates/';
  $lang_file = BASE_DIR . '/modules/login/lang/' . $_SESSION['cp_admin_lang'] . '.txt';

  $login =& new Login;

  $GLOBALS['tmpl']->config_load($lang_file, 'admin');
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign('config_vars', $config_vars);

  if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '') {
    switch($_REQUEST['moduleaction']) {

      case '1':
        $login->showConfig($tpl_dir,$lang_file);
      break;
    }
  }
}
?>