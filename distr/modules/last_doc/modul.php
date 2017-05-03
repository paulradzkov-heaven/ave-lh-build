<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit;

$modul['ModulName'] = "ѕоследние документы";
$modul['ModulPfad'] = "last_doc";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "ƒанный модуль предназначен дл€ вывода 5 последних документов.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['ModulTemplate'] = 0;
$modul['AdminEdit'] = 0;
$modul['ModulFunktion'] = "lastDoc";
$modul['CpEngineTagTpl'] = "[cp:last_doc]";
$modul['CpEngineTag'] = "\\\[cp:last_doc\\\]";
$modul['CpPHPTag'] = "<?php lastDoc(); ?>";

include_once(BASE_DIR . '/functions/func.modulglobals.php');

function lastDoc() {
  $limit = 5; //  оличество документов в списке
  modulGlobals('last_doc');
  $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_documents
		WHERE Id != '1'
		AND Id != '2'
		AND Geloescht != 1
		AND DokStatus != 0
		AND (DokEnde = 0 || DokEnde > " . time() . ")
		AND (DokStart = 0 || DokStart < " . time() . ")
		ORDER BY DokStart DESC
		LIMIT 0,$limit");

  $last_doc = array();

  while ($row = $sql->fetchrow()){
		if ($GLOBALS['config']['doc_rewrite']) {
		} else {
			$row->Url = (CP_REWRITE==1) ? cp_rewrite('/index.php?id=' . $row->Id . '&amp;doc=' . cp_parse_linkname($row->Titel) ) : '/index.php?id=' . $row->Id . '&amp;doc=' . cp_parse_linkname($row->Titel);
		}
    array_push($last_doc, $row);
  }
  $sql->Close();
  $GLOBALS['tmpl']->assign("items", $last_doc);
  $GLOBALS['tmpl']->display($GLOBALS['mod']['tpl_dir']."last_doc.tpl");
}
?>