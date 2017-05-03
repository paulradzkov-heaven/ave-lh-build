<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit;

$modul['ModulName'] = "Популярные новости";
$modul['ModulPfad'] = "top_news";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "Данный модуль предназначен для вывода 5 самых комментируемых новостей.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['ModulTemplate'] = 0;
$modul['AdminEdit'] = 0;
$modul['ModulFunktion'] = "topNews";
$modul['CpEngineTagTpl'] = "[cp:top_news]";
$modul['CpEngineTag'] = "\\\[cp:top_news\\\]";
$modul['CpPHPTag'] = "<?php topNews(); ?>";

include_once(BASE_DIR . '/functions/func.modulglobals.php');

function topNews() {
  $limit = 5; // Количество новостей в списке
  modulGlobals('top_news');
  $sql = $GLOBALS['db']->Query("SELECT a.DokId, COUNT(a.DokId) as top_comments, b.Id as did, b.Titel as dt, b.Url FROM " . PREFIX . "_modul_comment_info as a, " . PREFIX . "_documents as b
                                WHERE a.DokId = b.Id GROUP BY (a.DokId) ORDER BY top_comments DESC LIMIT 0,$limit");

  $top_news = array();

  while ($row = $sql->fetchrow()){
		if ($GLOBALS['config']['doc_rewrite']) {
		} else {
			$row->Url = (CP_REWRITE==1) ? cp_rewrite('/index.php?id=' . $row->did . '&amp;doc=' . cp_parse_linkname($row->dt) ) : '/index.php?id=' . $row->did . '&amp;doc=' . cp_parse_linkname($row->dt);
		}
    array_push($top_news, $row);
  }
  $sql->Close();
  $GLOBALS['tmpl']->assign("items", $top_news);
  $GLOBALS['tmpl']->display($GLOBALS['mod']['tpl_dir']."top_news.tpl");
}
?>