<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit;

$modul['ModulName'] = "Текущая дата";
$modul['ModulPfad'] = "curentdate";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "Простой модуль, предназначенный для вывода текущей даты. Разместите системный тег <strong>[cp:datum]</strong> в нужном месте вашего сайта для вывода.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['AdminEdit'] = 0;
$modul['ModulFunktion'] = "cpDatum";
$modul['CpEngineTagTpl'] = "[cp:datum]";
$modul['CpEngineTag'] = "\\\[cp:datum\\\]";
$modul['CpPHPTag'] = "<?php cpDatum(); ?>";

function cpDatum() {

  switch (date("F")){

    case 'January':   $month = 'января';   break;
    case 'February':  $month = 'февраля';  break;
    case 'March':     $month = 'марта';    break;
    case 'April':     $month = 'апреля';   break;
    case 'May':       $month = 'мая';      break;
    case 'June':      $month = 'июня';     break;
    case 'July':      $month = 'июля';     break;
    case 'August':    $month = 'августа';  break;
    case 'September': $month = 'сентября'; break;
    case 'October':   $month = 'октября';  break;
    case 'November':  $month = 'ноября';   break;
    case 'December':  $month = 'декабря';  break;
  }

  switch (date("w")){
    case '0': $day = 'Воскресенье'; break;
    case '1': $day = 'Понедельник'; break;
    case '2': $day = 'Вторник'; break;
    case '3': $day = 'Среда'; break;
    case '4': $day = 'Четверг'; break;
    case '5': $day = 'Пятница'; break;
    case '6': $day = 'Суббота'; break;
  }

  $now_date = date("$day, d ".$month." Y г.");
  echo 'Сегодня: ' . $now_date;
}
?>