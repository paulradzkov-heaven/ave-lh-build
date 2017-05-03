<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit; 

$modul['ModulName'] = "�������� ������";
$modul['ModulPfad'] = "example";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "������ ������ ������������ ��� ����, ����� �������� ��� ������� ������ � ������� ��� � �������. ��������� ������� ���������� ���������� Smarty.<BR /><BR />��� ������ ����������� ����������� ��������� ��� <strong>[cp:example]</strong>";
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
  $tpl_dir   = BASE_DIR . '/modules/example/templates/'; // ��������� ���� �� �������
	$GLOBALS['tmpl']->caching = true;
  $GLOBALS['tmpl']->cache_lifetime = 86400;  // ����� ����� ���� (1 ����)
	$GLOBALS['tmpl']->cache_dir = BASE_DIR . '/cache/example/';  // ����� ��� �������� ����

	// ���� ���� � ����, �� ��������� ������
	if (!$GLOBALS['tmpl']->is_cached('example.tpl')) {
		// ���������, ���� �� ����� ��� ����, ���� ��� (������ ���) � �������
		if (!is_file(BASE_DIR . '/cache/example/')) {
			@mkdir(BASE_DIR . '/cache/example/', 0777);					
		}

		$example = array();
    // ������ ���� ��������� ���������� (������ � ��������) �� ������� � ID 2 � � ����������� ID �� ��������
		$sql = $GLOBALS['db']->Query("SELECT Url, Titel FROM " . PREFIX . "_documents WHERE Id != '1' AND Id != '2' AND RubrikId = '2' AND Geloescht != 1 AND DokStatus != 0 ORDER BY Id DESC LIMIT 1");

		while($row = $sql->fetchrow()) {
			array_push($example,$row);
		}
		// ��������� ����������
		$sql->Close();

		// ��������� ���������� example ��� ������������� � �������
		$GLOBALS['tmpl']->assign('example', $example);
	}

	// ������� ������ example.tpl
	$GLOBALS['tmpl']->display($tpl_dir . 'example.tpl');
	$GLOBALS['tmpl']->caching = false;
	}
?>