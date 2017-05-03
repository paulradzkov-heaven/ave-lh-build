<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("BASE_DIR")) exit;

$modul['ModulName'] = "Гостевая книга";
$modul['ModulPfad'] = "guestbook";
$modul['ModulVersion'] = "0.1";
$modul['Beschreibung'] = "Модуль для организации на Вашем сайте интерактивного общения между пользователями.";
$modul['Autor'] = "Arcanum (arcanum@php.su)";
$modul['MCopyright'] = "&copy; 2007 (Участник команды overdoze.ru)";
$modul['Status'] = 1;
$modul['IstFunktion'] = 0;
$modul['AdminEdit'] = 1;
$modul['ModulTemplate'] = 1;
$modul['ModulFunktion'] = "NULL";
$modul['CpEngineTagTpl'] = "<b>Ссылка:</b> <a target='_blank' href='../index.php?module=guestbook'>/index.php?module=guestbook</a>";;
$modul['CpEngineTag'] = "NULL";
$modul['CpPHPTag'] = "NULL";

if(isset($_REQUEST['module']) && $_REQUEST['module']!='' && $_REQUEST['module'] == 'guestbook') {
  if(defined('ACP') && $_REQUEST['action'] != 'delete') {
    $modul_sql_deinstall = array();
    $modul_sql_install = array();
    include_once(BASE_DIR . '/modules/guestbook/sql.php');
  }
  //=======================================================
  // Все функции управления в публичной части
  //=======================================================
  include_once(BASE_DIR . '/functions/func.modulglobals.php');
  modulGlobals('guestbook');
  include(BASE_DIR . "/modules/guestbook/class.guest.php");
  $guest = new Guest_Module_Pub;

  // Проверяем наличие библиотеки GD и функции вывода текста на изображение
  $use_securecode = false;
  if((@extension_loaded("gd") == 1)) {
    if(function_exists("imagettftext")) {
      $use_securecode = true;
      $GLOBALS['tmpl']->assign('use_code', 1);
      $codeid=$guest->secureCode();
    } else {
      $use_securecode = false;
    }
  }

  // Генерируем секретный код и передаем в шаблон
  if($use_securecode) {
    if(!isset($_REQUEST['action']) && $_REQUEST['action']=="") {
      $GLOBALS['tmpl']->assign('pim', $guest->secureCode());
    } elseif (isset($_REQUEST['action']) && $_REQUEST['action']=='new') {
      $codeid = (int)$_REQUEST['pim'];
      $sql = $GLOBALS['db']->Query("SELECT Code FROM ".PREFIX."_antispam WHERE Id='".$codeid."'");
      $row = $sql->fetchrow();
      $securecode = $row->Code;
    }
  }

  // Получаем настройки модуля и отображаем модуль в публичной части в соответствии с ними
  $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_guestbook_settings");
  $row_guestbook_settings = $sql->fetchrow();
  define("GB_CHECK", $row_guestbook_settings->entry_censore);
  define("COMMENTSMILEYS", $row_guestbook_settings->smiles);
  define("COMMENTSBBCODE", $row_guestbook_settings->bbcodes);
  $GLOBALS['tmpl']->assign('maxpostlength', $row_guestbook_settings->maxpostlength);
  $limit = ($_REQUEST['pp']!='') ? $_REQUEST['pp'] : '15';
  $sort = ($_REQUEST['sort']!='') ? mysql_escape_string($_REQUEST['sort']) : 'desc';

  switch ($_REQUEST['action']) {
	  case '' :
	  case 'showentries':
      if($_REQUEST['sort'] == "asc") $ascsel = "selected=\"selected\"";
      if($_REQUEST['sort'] == "desc") $descsel = "selected=\"selected\"";
      $GLOBALS['tmpl']->assign("pps_array", $guest->ppsite());
      $GLOBALS['tmpl']->assign("dessel", $descsel);
      $GLOBALS['tmpl']->assign("ascsel", $ascsel);

      // Если разрешено использовать смайлы, получаем список и передаем в шаблон
      if(COMMENTSMILEYS == 1) {
         $smilies = $guest->listsmilies();
  	     $GLOBALS['tmpl']->assign('smilie', 1);
  	     $GLOBALS['tmpl']->assign('listemos', $smilies);
      }

      // Если разрешено использовать bbCode, передаем в шаблон разрешение
      if (COMMENTSBBCODE == 1) {
        $GLOBALS['tmpl']->assign('bbcodes', 1);
      }

      // Получаем количество сообщений и формируем постраничную навигацию
  	  $inserts = array();
  	  $sql = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_guestbook WHERE is_active = 1");
    	$num = $sql->numrows();

  	  $seiten = ceil($num / $limit);
  	  $a = prepage() * $limit - $limit;

  	  if($num > $limit) {
  		  $GLOBALS['tmpl']->assign('pages', pagenav($seiten, prepage(), " <a class=\"page_navigation\" href=\"index.php?module=guestbook&amp;pp=$limit&amp;sort=$_REQUEST[sort]&amp;page={s}\">{t}</a> "));
  	  }

      // Получаем список всех сообщений и передаем их в шаблон для вывода
      $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_guestbook WHERE is_active = 1 order by id $sort limit $a,$limit");
      $erg = $sql->numrows();

      while ($row = $sql->fetchrow()) {
        $row->ctime = date("m.d.y",$row->ctime);
        $text = $row->comment;

        // Если разрешено использовать смайлы и bbCode тогда обрабатываем все сообщения
        if(COMMENTSMILEYS == 1) {
          $text = $guest->kcodes_comments($text);
          $text = $guest->dosmilies($text);
        } else {
          $text = $guest->kcodes_comments($text);
        }
        $row->comment = $text;
        array_push($inserts, $row);
      }

      $GLOBALS['tmpl']->assign('comments_array', $inserts);
    break;

    //Если в запросе пришел параметр на создание нового сообщения, тогда
    case 'new' :
      $error = false;
      // Проверяем какой защитный код был введен:
      if(($_REQUEST['scode'] != $securecode) && ($use_securecode) ) {
        $text = $guest->formtext($_POST['text'], $row_guestbook_settings->maxpostlength);
        $dataString = "&gbcomment=$text&author=$_REQUEST[author]&email=$_REQUEST[email]&web=".str_replace('http://','%%webseite%%',$_REQUEST['http'])."&from=$_REQUEST[from]";
        $guest->msg($GLOBALS['mod']['config_vars']['guest_wrong_scode']);
        $error = true;
      }

      // Проверяем на время между добавлением сообщения (защита от спама)
      if(($row_guestbook_settings->spamprotect==1) && (!$error)) {
        $sql = $GLOBALS['db']->Query("SELECT ip,ctime FROM ".PREFIX."_modul_guestbook WHERE ip='".$_SERVER['REMOTE_ADDR']."' ORDER BY id DESC limit 1");
        $row = $sql->fetchrow();
        if($row) {
          if( ($row->ctime) + (60*$row_guestbook_settings->spamprotect_time) > time()) {
        		$guest->msg($GLOBALS['mod']['config_vars']['guest_wrong_spam']);
            $error = true;
      		}
        }
      }

      // Если ошибок нет
      if(!$error) {
        $entry_now = (GB_CHECK == 1) ? '0' : '1';
        $text = $guest->formtext($_POST['text'], $row_guestbook_settings->maxpostlength);
        if(strlen(chop($text)) < 2) {
          header("Location:index.php?module=guestbook");
          exit;
        }
        $sql = $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_guestbook
            (id, ctime, author, comment, email, web, ip, authfrom, is_active)
          VALUES (
            '',
            '" . time() . "',
            '" . strip_tags(substr($_REQUEST['author'],0,25)) . "',
            '$text',
            '" . strip_tags(substr($_REQUEST['email'],0,100)) . "',
            '" . strip_tags(substr($_REQUEST['http'],0,100)) . "',
            '" . $_SERVER['REMOTE_ADDR'] . "',
            '" . strip_tags(substr($_REQUEST['from'],0,100)) . "',
            '$entry_now')
        ");

        $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_antispam WHERE Code = '" . $_REQUEST[scode] . "'");

        //====================================================
        // Отправляем сообщение администратору на E-mail
        //====================================================
        if($row_guestbook_settings->mailbycomment==1) {
          $sql_m = $GLOBALS['db']->Query("SELECT mailsend FROM ".PREFIX."_modul_guestbook_settings");
          $row = $sql_m->fetchrow();
          $mail = $row->mailsend;

          $globals = new Globals;
          $SystemMail = $GLOBALS['globals']->cp_settings("Mail_Absender");
          $GLOBALS['globals']->cp_mail($mail, $text, $GLOBALS['mod']['config_vars']['guest_new_mail'], "$mail", $GLOBALS['mod']['config_vars']['guest_pub_name'], $mail, "text", "");
        }
        //Проверяем включена ли проверка сообщений модератором и выводит то или иное сообщение
        $text_thankyou = (GB_CHECK == 1) ? $GLOBALS['mod']['config_vars']['guest_check_thanks'] : $GLOBALS['mod']['config_vars']['guest_thanks'];
        $guest->msg($text_thankyou);
        $error = true;
      }
    break;
  }

  $GLOBALS['tmpl']->assign('allcomments', $num);
  $tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'guestbook.tpl');
  define("MODULE_CONTENT", $tpl_out);

}

//=======================================================
// Управление модулем в Панели управления
//=======================================================
if(defined("ACP") && $_REQUEST['action'] != 'delete') {

  $tpl_dir = BASE_DIR . "/modules/guestbook/templates/";
  $lang_file = BASE_DIR . "/modules/guestbook/lang/" . STD_LANG . ".txt";
  include(BASE_DIR . "/modules/guestbook/class.guest_admin.php");

  $guest = new Guest_Module;
  $GLOBALS['tmpl']->config_load($lang_file);
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '') {
    switch($_REQUEST['moduleaction']) {
      // Обращение к функции для вывода настроек модуля
      case '1':
        $guest->settings($tpl_dir);
      break;

      // Обращение к функции для сохранения изменений
      case 'medit':
        $guest->edit_massage($tpl_dir);
      break;
    }
  }
}
?>