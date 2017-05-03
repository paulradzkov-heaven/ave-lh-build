<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit; 

$modul['ModulName'] = "Тестовый модуль";
$modul['ModulPfad'] = "example";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "Данный модуль предназначен для того, чтобы показать как сделать запрос и вывести его в шаблоне. Результат запроса кешируются средствами Smarty.<BR /><BR />Для вывода результатов используйте системный тег <strong>[cp:example]</strong>";
$modul['Autor'] = "censored!";
$modul['MCopyright'] = "&copy; 2008 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['AdminEdit'] = 0;
$modul['ModulFunktion'] = "cpExample";
$modul['CpEngineTagTpl'] = "[cp:example]";
$modul['CpEngineTag'] = "\\\[cp:example\\\]";
$modul['CpPHPTag'] = "<?php cpExample(); ?>";

function cpExample() {
  $tpl_dir   = BASE_DIR . '/modules/example/templates/'; // Указываем путь до шаблона
	$GLOBALS['tmpl']->caching = true;
  $GLOBALS['tmpl']->cache_lifetime = 86400;  // Время жизни кэша (1 день)
	$GLOBALS['tmpl']->cache_dir = BASE_DIR . '/cache/example/';  // Папка для создания кэша

	// Если нету в кэше, то выполняем запрос
	if (!$GLOBALS['tmpl']->is_cached('example.tpl')) {
		// Проверяем, есть ли папка для кэша, если нет (первый раз) — создаем
		if (!is_file(BASE_DIR . '/cache/example/')) {
			@mkdir(BASE_DIR . '/cache/example/', 0777);					
		}

		$example = array();
    // Запрос трех последних документов (ссылка и название) из рубрики с ID 2 и с сортировкой ID по убыванию
		$sql = $GLOBALS['db']->Query("SELECT Url, Titel FROM " . PREFIX . "_documents WHERE Id != '1' AND Id != '2' AND RubrikId = '2' AND Geloescht != 1 AND DokStatus != 0 ORDER BY Id DESC LIMIT 1");

		while($row = $sql->fetchrow()) {
			array_push($example,$row);
		}
		// Закрываем соединение
		$sql->Close();

		// Назначаем переменную example для использования в шаблоне
		$GLOBALS['tmpl']->assign('example', $example);
	}

	// Выводим шаблон example.tpl
	$GLOBALS['tmpl']->display($tpl_dir . 'example.tpl');
	$GLOBALS['tmpl']->caching = false;
	}
?>