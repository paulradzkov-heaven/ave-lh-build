<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit;

$modul['ModulName'] = "������� ����";
$modul['ModulPfad'] = "curentdate";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "������� ������, ��������������� ��� ������ ������� ����. ���������� ��������� ��� <strong>[cp:datum]</strong> � ������ ����� ������ ����� ��� ������.";
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

    case 'January':   $month = '������';   break;
    case 'February':  $month = '�������';  break;
    case 'March':     $month = '�����';    break;
    case 'April':     $month = '������';   break;
    case 'May':       $month = '���';      break;
    case 'June':      $month = '����';     break;
    case 'July':      $month = '����';     break;
    case 'August':    $month = '�������';  break;
    case 'September': $month = '��������'; break;
    case 'October':   $month = '�������';  break;
    case 'November':  $month = '������';   break;
    case 'December':  $month = '�������';  break;
  }

  switch (date("w")){
    case '0': $day = '�����������'; break;
    case '1': $day = '�����������'; break;
    case '2': $day = '�������'; break;
    case '3': $day = '�����'; break;
    case '4': $day = '�������'; break;
    case '5': $day = '�������'; break;
    case '6': $day = '�������'; break;
  }

  $now_date = date("$day, d ".$month." Y �.");
  echo '�������: ' . $now_date;
}
?>