<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit;

$modul['ModulName'] = " омментарии";
$modul['ModulPfad'] = "comment";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "ƒанный модуль предназначен дл€ организации системы комментариев дл€ документов на сайте. ƒл€ того, чтобы использовать данный модуль, разместите системный тег <strong>[cp:comment]</strong> в нужном месте шаблона рубрики.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['ModulTemplate'] = 0;
$modul['AdminEdit'] = 1;
$modul['ModulFunktion'] = "cpComment";
$modul['CpEngineTagTpl'] = "[cp:comment]";
$modul['CpEngineTag'] = "\\\[cp:comment\\\]";
$modul['CpPHPTag'] = "<?php cpComment(); ?>";

include_once(BASE_DIR . "/modules/comment/sql.php");

include_once(BASE_DIR . '/modules/comment/class.comment.php');
include_once(BASE_DIR . '/functions/func.modulglobals.php');

function cpComment() {
  modulGlobals('comment');
  $comment =& new Comment;
  $comment->displayComments();
}

if(isset($_REQUEST['module']) && $_REQUEST['module'] != '' && $_REQUEST['module'] == 'comment' && isset($_REQUEST['action'])) {

  modulGlobals('comment');
  $comment =& new Comment;

  switch($_REQUEST['action']) {

    case 'form':
      $comment->displayForm($_REQUEST['cp_theme']);
    break;

    case 'comment':
      $comment->sendForm($_REQUEST['cp_theme']);
    break;

    case 'delete':
      if(UGROUP==1) $comment->deleteComment($_REQUEST['Id']);
    break;

    case 'lock':
      if(UGROUP==1) $comment->Lock($_REQUEST['Id']);
    break;

    case 'unlock':
      if(UGROUP==1) $comment->unLock($_REQUEST['Id']);
    break;

    case 'edit':
      $sql = $GLOBALS['db']->Query("SELECT Author_Id FROM " . PREFIX . "_modul_comment_info WHERE Id = '".(int)$_REQUEST['Id']."' LIMIT 1");
      $num = $sql->numrows();
      $false = ($num == 1) ? 0 : 1;
      $comment->editComment($_REQUEST['Id'], $false);
    break;

    case 'admin_edit':
      $sql = $GLOBALS['db']->Query("SELECT Author_Id FROM " . PREFIX . "_modul_comment_info WHERE Id = '".(int)$_REQUEST['Id']."' LIMIT 1");
      $num = $sql->numrows();
      $false = ($num == 1) ? 0 : 1;
      $comment->editCommentAdmin($_REQUEST['Id'], $false);
    break;

    case 'postinfo':
      $comment->postInfo($_REQUEST['AuthorId']);
    break;

    case 'close' :
      if(UGROUP==1) $comment->close($_REQUEST['DokId']);
    break;

    case 'open' :
      if(UGROUP==1) $comment->open($_REQUEST['DokId']);
    break;
  }
}

if(defined("ACP") && $_REQUEST['action'] != 'delete') {
  $tpl_dir   = BASE_DIR . "/modules/comment/templates/";
  $lang_file = BASE_DIR . "/modules/comment/lang/" . $_SESSION['cp_admin_lang'] . ".txt";
  $comment =& new Comment;

  $GLOBALS['tmpl']->config_load($lang_file, "admin");
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '') {

    switch($_REQUEST['moduleaction'])   {

      case '1':
        $comment->showComments($tpl_dir);
      break;

      case 'settings':
        $comment->settings($tpl_dir);
      break;
    }
  }
}
?>