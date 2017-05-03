<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit;

$modul['ModulName'] = "Последние комментарии";
$modul['ModulPfad'] = "last_comments";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "Данный модуль предназначен для вывода последних комментариев.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['ModulTemplate'] = 0;
$modul['AdminEdit'] = 0;
$modul['ModulFunktion'] = "cpLastComments";
$modul['CpEngineTagTpl'] = "[cp:last_comments]";
$modul['CpEngineTag'] = "\\\[cp:last_comments\\\]";
$modul['CpPHPTag'] = "<?php cpLastComments(); ?>";

include_once(BASE_DIR . '/functions/func.modulglobals.php');

function cpLastComments() {
  $limit = 5;
  modulGlobals('last_comments');

  $sql = $GLOBALS['db']->Query("
  SELECT a.Id, a.Author, a.DokId, a.Text, a.Erstellt, b.Id, b.Titel, b.Url FROM
  " . PREFIX . "_modul_comment_info as a LEFT JOIN
  " . PREFIX . "_documents as b ON a.DokId = b.Id
  WHERE Status = 1
  ORDER BY
  a.Id DESC LIMIT 0,$limit");

  $last_comm = array();

  while ($row = $sql->fetchrow()){
		if ($GLOBALS['config']['doc_rewrite']) {
		} else {
			$row->Url = (CP_REWRITE==1) ? cp_rewrite('/index.php?id=' . $row->Id . '&amp;doc=' . cp_parse_linkname($row->Titel) ) : '/index.php?id=' . $row->Id . '&amp;doc=' . cp_parse_linkname($row->Titel);
		}
	    array_push($last_comm, $row);
  }

  $sql->Close();
  $GLOBALS['tmpl']->assign("items", $last_comm);
  $GLOBALS['tmpl']->display($GLOBALS['mod']['tpl_dir']."last_comments.tpl");
}
?>