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
$modul['ModulName'] = "��������� �����";
$modul['ModulPfad'] = "random_text";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "������ ������ ������������ ��� ������ ���������� ������ ��� ����������� � ����� ����� ������ �����, ��������� ����� HTML-���. ��� ������������� ������� ������, ��� ���������� ������� ���� � ����� <b>/modules/random_text/</b> � �����������, ������� ������ ���� ����������. �������� ��������, ��� ���������� ������ ���� ��������� �� �������: 1 ������ �� ������! ��� ������ ����������, ���������� ��������� ��� <b>[cp_random:<em>��������_�����</em>]</b> � ������ ����� ������ �������.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['AdminEdit'] = 0;
$modul['ModulFunktion'] = "cpRandomText";
$modul['CpEngineTagTpl'] = "[cp_random:<em>��� �����</em>]";
$modul['CpEngineTag'] = "\\\[cp_random:([._a-zA-Z0-9\\\]*)\\]";
$modul['CpPHPTag'] = "<?php cpRandomText(''\\\\\\\\1''); ?>";

if(!defined("BASE_DIR")) exit;

function cpRandomText($dateiname) {
    $daten = file('modules/random_text/' . stripslashes($dateiname));
    @shuffle ($daten);
    echo trim($daten[0]);
}
?>