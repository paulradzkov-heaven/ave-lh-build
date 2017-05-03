<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit; 

$modul['ModulName'] = "Похожие документы";
$modul['ModulPfad'] = "moredoc";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "Данный модуль предназначен для вывода списка похожих документов относительно текущего. Связующим элементом документов является первое слово из поля Ключевые слова. Результат вывода кешируется средствами Smarty.<BR /><BR />Для вывода списка похожих документов используйте системный тег <strong>[cp:moredoc]</strong> (можно использовать как в документах так и шаблоне рубрики).";
$modul['Autor'] = "censored!";
$modul['MCopyright'] = "&copy; 2008 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['AdminEdit'] = 0;
$modul['ModulFunktion'] = "cpMoreDoc";
$modul['CpEngineTagTpl'] = "[cp:moredoc]";
$modul['CpEngineTag'] = "\\\[cp:moredoc\\\]";
$modul['CpPHPTag'] = "<?php cpMoreDoc(); ?>";

include_once(BASE_DIR . "/modules/moredoc/sql.php"); 

function cpMoreDoc() {
	include_once(BASE_DIR . '/functions/func.modulglobals.php');
	modulGlobals('moredoc');

	$limit = 5; // Количество связных документов
	$flagRubric = 1; // Учитывать или нет рубрику документа (0 - нет, 1 - да)

	$GLOBALS['tmpl']->cache_lifetime = 86400; // Время жизни кэша (1 день (указывается в секундах: 60 секунд * 60 минут * 24 часа))

  $moreDoc = '';
	$inRubric = '';
	$docId = (int)$_REQUEST['id'];  // Получаем ID документа

	$GLOBALS['tmpl']->caching = true; 	// Устанавливаем флаг кэшировать

	// Если нету в кэше, то начинаем обрабатывать
	if (!$GLOBALS['tmpl']->is_cached($GLOBALS['tmpl']->cache_dir . 'moredoc.tpl', $docId)) {

		$tpl_dir   = BASE_DIR . '/modules/moredoc/templates/';	// Указываем путь до шаблона
    $GLOBALS['tmpl']->cache_dir = BASE_DIR . '/cache/moredoc/';		// Папка для создания кэша

		// Проверяем, есть ли папка для кэша, если нет (первый раз) — создаем
		if (!is_file(BASE_DIR . '/cache/moredoc/')) {
			@mkdir(BASE_DIR . '/cache/moredoc/', 0777);					
		}

		// Получаем ключевые слова, рубрику, вытаскиваем первое слово
		$sql = $GLOBALS['db']->Query("SELECT RubrikId, MetaKeywords FROM " . PREFIX . "_documents WHERE Id ='".$docId."' LIMIT 1");
		$row = $sql->fetchrow();
		$keywords = explode(',',$row->MetaKeywords);
		$keywords = trim($keywords[0]);
		$rubric = $row->RubrikId;

		if ($keywords != '') {
			if ($flagRubric) {
				$inRubric = "AND RubrikId ='".$rubric."'";
			} else {
				$inRubric = '';
			}
			// Ищем документы где встречается такое-же слово
			$sql = $GLOBALS['db']->Query("SELECT Id, Url, Titel, MetaDescription FROM " . PREFIX . "_documents WHERE (MetaKeywords LIKE '".$keywords."%') AND Id != '1' AND Id != '2' AND DokStatus != '0' AND Id != '".$docId."' ".$inRubric." ORDER BY Id DESC LIMIT 0,$limit");
			$moreDoc = array();

			while ($row = $sql->fetchrow()) {
				if ($GLOBALS['config']['doc_rewrite']) {
				} else {
					$row->Url = (CP_REWRITE==1) ? cp_rewrite('/index.php?id=' . $row->Id . '&amp;doc=' . cp_parse_linkname($row->Titel) ) : '/index.php?id=' . $row->Id . '&amp;doc=' . cp_parse_linkname($row->Titel);
				}
				array_push($moreDoc, $row);
			}
			// Закрываем соединение
			$sql->Close();
	  }
			// Назначаем переменную moreDoc для использования в шаблоне
			$GLOBALS['tmpl']->assign('moredoc', $moreDoc);
		}
	
	// Выводим шаблон moredoc.tpl
	$GLOBALS['tmpl']->display($tpl_dir . 'moredoc.tpl', $docId);
}
?>