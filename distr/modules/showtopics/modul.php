<?php
/*::::::::::::::::::::::::::::::::::::::::
 Module name: Show topics
 Short Desc: Add video files any place
 Version: 1.0 alpha
 Authors:  Mad Den (mad_den@mail.ru)
 Date: november 01, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("BASE_DIR")) exit;

$modul['ModulName'] = "Избранные темы с форума";
$modul['ModulPfad'] = "showtopics";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "Выводит список выбранных топиков из форума";
$modul['Autor'] = "Mad Den";
$modul['MCopyright'] = "&copy; 2008 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['ModulFunktion'] = "cpShowTopics";
$modul['CpEngineTagTpl'] = "[show_topics]";
$modul['CpEngineTag'] = "\\\[show_topics\\\]";
$modul['CpPHPTag'] = "<?php cpShowTopics(); ?>";

// Подключаем файлы Sql и файл класса
include_once(BASE_DIR . "/modules/showtopics/sql.php");
include_once(BASE_DIR . "/modules/showtopics/class.module.php");

// Администрирование
if(defined("ACP") && $_REQUEST['action'] != 'delete') {
  $tpl_dir   = BASE_DIR . "/modules/showtopics/templates/";
  $lang_file = BASE_DIR . "/modules/showtopics/lang/" . STD_LANG . ".txt";
  
  $GLOBALS['tmpl']->config_load($lang_file);
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);
}

// Показать видео файл
function cpShowTopics() {
  $tpl_dir = BASE_DIR . "/modules/showtopics/templates/";
  $showtopic =& new showTopics;
  $showtopic->displayTopics($tpl_dir);
}

?>