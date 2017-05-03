<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit;

$modul['ModulName'] = "Топ комментаторов";
$modul['ModulPfad'] = "top_users";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "Данный модуль предназначен для вывода 5 самых активных комментаторов на сайте.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['ModulTemplate'] = 0;
$modul['AdminEdit'] = 0;
$modul['ModulFunktion'] = "topUsers";
$modul['CpEngineTagTpl'] = "[cp:top_users]";
$modul['CpEngineTag'] = "\\\[cp:top_users\\\]";
$modul['CpPHPTag'] = "<?php topUsers(); ?>";

include_once(BASE_DIR . '/functions/func.modulglobals.php');

function topUsers() {
  $limit = 5;
  modulGlobals('top_users');
  $sql = $GLOBALS['db']->Query("
    SELECT Author, COUNT(Author_Id) as top_comments
    FROM " . PREFIX . "_modul_comment_info
    WHERE Author_Id != '0'
    GROUP BY (Author)
    ORDER BY top_comments DESC
    LIMIT 0,$limit
  ");

  $top_users = array();

  while ($row = $sql->fetchrow()){
    array_push($top_users, $row);
  }
  $sql->Close();
  $GLOBALS['tmpl']->assign("items", $top_users);
  $GLOBALS['tmpl']->display($GLOBALS['mod']['tpl_dir']."top_users.tpl");
}

?>