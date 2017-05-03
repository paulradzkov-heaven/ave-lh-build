<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

$config['doc_rewrite']    = '1';
$config['mod_rewrite']    = '0';
$config['std_theme']      = 'default';
$config['std_admintheme'] = 'apanel';
$config['std_lang']       = 'ru';
$config['session_lt']     = '45';


/* Настройки для оформления скрытого текста */
$hidden['text']           = 'Содержимое скрыто. Пожалуйста, <a href="/login/register/">зарегистрируйтесь</a>'; // Текст, заменящий скрытый текст
$hidden['text_color']     = "#333333"; // Цвет шрифта
$hidden['bg_color']       = "#FFFFFF"; // Цвет фона div элемента
$hidden['border_size']    = "0px";     // Бордюр для div элемента
$hidden['border_type']    = "solid";  // Стиль бордюра (solid, dotted, dashed)
$hidden['border_color']   = "#eec00a"; // Цвет бордюра
$hidden['display']        = "inline";  // Как отображать div элемент с текстом
?>