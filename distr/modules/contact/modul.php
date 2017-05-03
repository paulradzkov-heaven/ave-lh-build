<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("BASE_DIR")) exit;

$modul['ModulName'] = " онтакты";
$modul['ModulPfad'] = "contact";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "ƒанный модуль предназначен дл€ создани€ различных веб-форм дл€ контактов, которые могут состо€ть из различного набора полей. —оздание контактной формы осуществл€етс€ в ѕанели управлени€, а дл€ вывода в ѕубличной части сайта, ¬ам необходимо разместить системный тег <strong>[cp_contact:XXX]</strong> в нужном месте ¬ашего шаблона или содержимого документа. XXX - это пор€дковый номер формы в системе.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['AdminEdit'] = 1;
$modul['ModulFunktion'] = "cpContact";
$modul['CpEngineTagTpl'] = "[cp_contact:XXX]";
$modul['CpEngineTag'] = "\\\[cp_contact:([0-9\\\]*)\\]";
$modul['CpPHPTag'] = "<?php cpContact(''\\\\\\\\1''); ?>";

include_once(BASE_DIR . "/modules/contact/sql.php");

include(BASE_DIR . "/modules/contact/class.contact.php");

if(function_exists("imagettftext") && function_exists("imagepng")) {
  define("ANTI_SPAMIMAGE", 1);
}

function cpContact($id) {
  $id = stripslashes($id);
  $tpl_dir   = BASE_DIR . "/modules/contact/templates/";
  $lang_file = BASE_DIR . "/modules/contact/lang/" . STD_LANG . ".txt";
  $contact   =& new Contact;

  if(!isset($_REQUEST['contact_action'])) {
    $globals = new Globals;
    $codeid  = $contact->secureCode();

    $sql = $GLOBALS['db']->Query("SELECT AntiSpam,MaxUpload FROM " . PREFIX . "_modul_contacts WHERE Id = '$id'");
    $row = $sql->fetchrow();
    $sql->Close();


    if(is_object($row)) {
      $im = (defined("ANTI_SPAMIMAGE") && $row->AntiSpam == 1) ? $codeid : "";
      $contact->fetchForm($tpl_dir,$lang_file,$id,$im,$row->MaxUpload);
    }
  }


  if(isset($_REQUEST['modules']) && $_REQUEST['modules'] != '' && $_REQUEST['modules'] == 'contact') {

    $tpl_dir   = BASE_DIR . "/modules/contact/templates/";
    $lang_file = BASE_DIR . "/modules/contact/lang/" . STD_LANG . ".txt";

    if(isset($_REQUEST['contact_action']) && $_REQUEST['contact_action'] != "") {

      switch($_REQUEST['contact_action']) {

        case 'DoPost':

          $sql = $GLOBALS['db']->Query("SELECT AntiSpam,MaxUpload FROM " . PREFIX . "_modul_contacts WHERE Id = '$id'");
          $row = $sql->fetchrow();
          $sql->Close();

          if(defined("ANTI_SPAMIMAGE") && @$row->AntiSpam == 1) {
            if(!defined("BLANC")) define("BLANC", 1);
            $contact->sendSecure($tpl_dir,$lang_file,$id,1,$row->MaxUpload);

          } else {
            if(!defined("BLANC")) define("BLANC", 1);
            $contact->sendSecure($tpl_dir,$lang_file,$id,0,@$row->MaxUpload);
          }

        break;
      }
    }
  }
}

if(defined("ACP") && $_REQUEST['action'] != 'delete') {

  $tpl_dir   = BASE_DIR . "/modules/contact/templates/";
  $lang_file = BASE_DIR . "/modules/contact/lang/" . $_SESSION['cp_admin_lang'] . ".txt";

  $contact = new Contact;

  $GLOBALS['tmpl']->config_load($lang_file, "admin");
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '') {

    switch($_REQUEST['moduleaction']) {

      case '1':
        $contact->showForms($tpl_dir);
      break;

      case 'edit':
        $contact->editForms($tpl_dir,$_REQUEST['id']);
      break;

      case 'save':
        $contact->saveForms($tpl_dir,$_REQUEST['id']);
      break;

      case 'save_new':
        $contact->saveFormsNew($tpl_dir,$_REQUEST['id']);
      break;

      case 'new':
        $contact->newForms($tpl_dir);
      break;

      case 'delete':
        $contact->deleteForms($_REQUEST['id']);
      break;

      case 'showmessages_new':
        $contact->showMessages($tpl_dir,$_REQUEST['id'],"new");
      break;

      case 'showmessages_old':
        $contact->showMessages($tpl_dir,$_REQUEST['id'],"old");
      break;

      case 'reply':
        $contact->replyMessage();
      break;

      case 'quicksave':
        $contact->quickSave();
      break;

      case 'get_attachment':
        $contact->getAttachment($_REQUEST['file']);
      break;
    }
  }
}
?>