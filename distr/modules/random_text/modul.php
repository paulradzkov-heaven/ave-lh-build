<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit;

$modul = array();
$modul['ModulName'] = "Случайный вывод";
$modul['ModulPfad'] = "random_text";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "Данный модуль предназначен для вывода случайного текста или изображения в любом месте вашего сайта, используя любой HTML-код. Для использования данного модуля, Вам необходимо создать файл в папке <b>/modules/random_text/</b> с информацией, которая должна быть отображена. Обращаем внимание, что информация должна быть размещена по правилу: 1 запись на строку! Для вывода информации, разместите системный тег <b>[cp_random:<em>название_файла</em>]</b> в нужном месте вашего шаблона.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['AdminEdit'] = 0;
$modul['ModulFunktion'] = "cpRandomText";
$modul['CpEngineTagTpl'] = "[cp_random:<em>ИМЯ ФАЙЛА</em>]";
$modul['CpEngineTag'] = "\\\[cp_random:([._a-zA-Z0-9\\\]*)\\]";
$modul['CpPHPTag'] = "<?php cpRandomText(''\\\\\\\\1''); ?>";

if(!defined("BASE_DIR")) exit;

function cpRandomText($dateiname) {
    $daten = file('modules/random_text/' . stripslashes($dateiname));
    @shuffle ($daten);
    echo trim($daten[0]);
}
?>