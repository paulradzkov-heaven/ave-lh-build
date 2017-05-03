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

  define("REPLACEMENT", substr($_SERVER['SCRIPT_NAME'],0,-15));
  include_once(SOURCE_DIR . "/class/class.rubs.php");
  include_once(SOURCE_DIR . "/class/class.docs.php");
  include_once(SOURCE_DIR . "/class/class.queries.php");
  include_once(SOURCE_DIR . "/class/class.navigation.php");

  $cpQuery =& new Query;
  $cpDoc   =& new docs;
  $cpRub   =& new rubs;
  $cpNavi  =& new Navigation;

  $cpDoc->rediRubs();
  $cpDoc->tplTimeAssign();

  $GLOBALS['tmpl']->assign("navi", $GLOBALS['tmpl']->fetch("navi/navi.tpl"));

  $GLOBALS['tmpl']->config_load(SOURCE_DIR . "/admin/lang/" . $_SESSION['cp_admin_lang'] . "/docs.txt", 'docs');
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  $_REQUEST['sub']    = (!isset($_REQUEST['sub']))    ? '' : addslashes($_REQUEST['sub']);
  $_REQUEST['action'] = (!isset($_REQUEST['action'])) ? '' : addslashes($_REQUEST['action']);
  $_REQUEST['submit'] = (!isset($_REQUEST['submit'])) ? '' : addslashes($_REQUEST['submit']);

  switch($_REQUEST['action']) {

    case '' :
      if(permCheck('docs')) {
        switch($_REQUEST['sub']) {

          case 'quicksave':
            $cpDoc->quickSave();
          break;
        }
        $cpDoc->showDocs();
      }
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("documents/docs.tpl"));
    break;

    case 'showsimple':
      if(permCheck('docs')) {
        $cpDoc->showDocs('simple');
		$GLOBALS['tmpl']->assign("formaction", "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("documents/docs_simple.tpl"));
      }
    break;

    case 'showsimple_edit':
      if(permCheck('docs')) {
        $cpDoc->showDocs('simple');
		$GLOBALS['tmpl']->assign("formaction", "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("documents/docs_simple_editor.tpl"));
      }
    break;

    case 'edit':
      if(permCheck('docs')) {
        $cpNavi->showAllEntries();
        $cpQuery->showQueries('extern');
        $cpDoc->editDoc($_REQUEST['Id']);
      }
    break;

    case 'new':
      if(permCheck('docs')) {
        $cpNavi->showAllEntries();
        $cpQuery->showQueries('extern');
        $cpDoc->newDoc((int)$_REQUEST['Id']);
      }
    break;

    case 'open':
      if(permCheck('docs')) {
        $cpNavi->statusNavi('1',$_REQUEST['Id']);
        $cpDoc->openCloseDoc($_REQUEST['Id'],'1');
      }
    break;

    case 'close':
      if(permCheck('docs')) {
        //$cpNavi->statusNavi('0',$_REQUEST['Id']);
        $cpDoc->openCloseDoc($_REQUEST['Id'],'0');
      }
    break;

    case 'delete':
      if(permCheck('docs')) {
        //$cpNavi->statusNavi('0',$_REQUEST['Id']);
        $cpDoc->delDoc($_REQUEST['Id']);
      }
    break;

    case 'redelete':
      if(UGROUP==1) {
        $cpNavi->statusNavi('1',$_REQUEST['Id']);
        $cpDoc->redelDoc($_REQUEST['Id']);
      } else {
        define("NOPERM",1);
      }
    break;

    case 'enddelete':
      if(UGROUP==1){
        $cpNavi->delNavi($_REQUEST['Id']);
        $cpDoc->enddelDoc($_REQUEST['Id']);
      } else {
        define("NOPERM",1);
      }
    break;

    case 'comment':
      if(cp_perm("docs_comments")) {
        $cpDoc->newComment($_REQUEST['Id']);
      } else {
        define("NOPERM",1);
      }
    break;

    case 'comment_reply':
      if(cp_perm("docs_comments")) {
        $cpDoc->newComment($_REQUEST['Id'],1);
      } else {
        define("NOPERM",1);
      }
    break;

    case 'openclose_discussion':
      if(cp_perm("comments_openlose")) {
        $cpDoc->openCloseDiscussion($_REQUEST['Id']);
      } else {
        define("NOPERM",1);
      }
    break;

    case 'del_comment':
      if(cp_perm("docs_comments_del")) {
        $cpDoc->delComment($_REQUEST['Id'],$_REQUEST['KommentarStart']);
      } else {
        define("NOPERM",1);
      }
    break;

    case 'change':
      if(permCheck('docs')) {
        $cpDoc->changeRubs();
      }
    break;
  }
}
?>