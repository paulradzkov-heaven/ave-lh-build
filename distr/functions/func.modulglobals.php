<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function modulGlobals($modulepath) {
  $tpl_dir   = BASE_DIR . '/modules/'.$modulepath.'/templates/';
  $lang_file = BASE_DIR . '/modules/'.$modulepath.'/lang/' . STD_LANG . '.txt';
  
  if(!file_exists($lang_file)) {
    echo "Ошибка! Отсутствует языковой файл. Пожалуйста, проверьте язык, установленный по умолчанию, в файле inc/config.php";
    exit;
  }
  
  @$GLOBALS['tmpl']->config_load($lang_file);
  $GLOBALS['tmpl']->assign('mod_dir', BASE_DIR . '/modules');
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  
  $GLOBALS['tmpl']->assign('config_vars', $config_vars);
  $GLOBALS['tmpl']->assign('tpl_dir', $tpl_dir);
  $GLOBALS['tmpl']->register_function('in_array', 'in_array');
  $GLOBALS['mod']['config_vars'] = $config_vars;
  $GLOBALS['mod']['tpl_dir'] = $tpl_dir;
  if(defined("T_PATH")) $GLOBALS['mod']['cp_theme'] = T_PATH;
  
}
?>