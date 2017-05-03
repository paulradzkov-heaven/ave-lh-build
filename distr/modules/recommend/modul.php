<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit;

$modul['ModulName'] = "�������������";
$modul['ModulPfad'] = "recommend";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "������ ������ ��������� ����������� ��������� �������� ��������� � ������, ���� ������������ ������� ������ ���� ��� �������� ���������� � ��������. ����� ������������ ������ ������, ���������� �������� ��� <strong>[cp:recommend]</strong> � ������ ����� ������ ������� ����� ��� �� �����-���� ��������. ������ ������ ����� ����������� � ���� ������, �� ������� �� ������� ��������� �������������� ���� ��� ����� ����������.";
$modul['Autor'] = "Arcanum";
$modul['MCopyright'] = "&copy; 2007 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['ModulTemplate'] = 0;
$modul['AdminEdit'] = 0;
$modul['ModulFunktion'] = "cpRecommend";
$modul['CpEngineTagTpl'] = "[cp:recommend]";
$modul['CpEngineTag'] = "\\\[cp:recommend\\\]";
$modul['CpPHPTag'] = "<?php cpRecommend(); ?>";

include_once(BASE_DIR . '/modules/recommend/class.recommend.php');
include_once(BASE_DIR . '/functions/func.modulglobals.php');

function cpRecommend() {
  modulGlobals('recommend');
  $recommend =& new Recommend;
  $recommend->displayLink();
}

if(isset($_REQUEST['module']) && $_REQUEST['module'] != '' && $_REQUEST['module'] == 'recommend' && isset($_REQUEST['action'])) {

  switch($_REQUEST['action']) {

    case 'form':
      modulGlobals('recommend');
      $recommend =& new Recommend;
      $recommend->displayForm($_REQUEST['cp_theme']);
    break;

    case 'recommend':
      modulGlobals('recommend');
      $recommend =& new Recommend;
      $recommend->sendForm($_REQUEST['cp_theme']);
    break;
  }
}
?>