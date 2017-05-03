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

  include_once(SOURCE_DIR . "/class/class.queries.php");
  include_once(SOURCE_DIR . "/class/class.docs.php");
  include_once(SOURCE_DIR . "/class/class.rubs.php");

  $cpRub   =& new rubs;
  $cpQuery =& new Query;
  $cpDoc   =& new docs;
  $cpDoc->rediRubs();

  $GLOBALS['tmpl']->config_load(SOURCE_DIR . "/admin/lang/" . $_SESSION['cp_admin_lang'] . "/query.txt", 'query');

  include_once(SOURCE_DIR . "/admin/inc/pre.inc.php");

  switch($_REQUEST['action']) {

    case '':
      if(permCheck('abfragen')) {
        $cpQuery->showQueries();
      }
    break;

    case 'edit':
      if(permCheck('abfragen')) {
        $cpRub->showRubTpl(0,1);
        $cpQuery->editQuery($_REQUEST['Id']);
      }
    break;

    case 'copy':
      if(permCheck('abfragen')) {
        $cpQuery->copyQuery($_REQUEST['Id']);
      }
    break;

    case 'new':
      if(permCheck('abfragen_neu')) {
        $cpRub->showRubTpl(0,1);
        $cpQuery->newQuery();
      }
    break;

    case 'delete_query':
      if(permCheck('abfragen_loesch')) {
        $cpQuery->deleteQuery($_REQUEST['Id']);
      }
    break;

    case 'konditionen':
      if(permCheck('abfragen')) {
        $cpRub->showRubTpl(0,1);
        $cpQuery->editConditions($_REQUEST['Id']);
      }
    break;
  }

}
?>