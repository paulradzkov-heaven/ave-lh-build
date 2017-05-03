<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit; 

$modul['ModulName'] = "������� ���������";
$modul['ModulPfad'] = "moredoc";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "������ ������ ������������ ��� ������ ������ ������� ���������� ������������ ��������. ��������� ��������� ���������� �������� ������ ����� �� ���� �������� �����. ��������� ������ ���������� ���������� Smarty.<BR /><BR />��� ������ ������ ������� ���������� ����������� ��������� ��� <strong>[cp:moredoc]</strong> (����� ������������ ��� � ���������� ��� � ������� �������).";
$modul['Autor'] = "censored!";
$modul['MCopyright'] = "&copy; 2008 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['AdminEdit'] = 0;
$modul['ModulFunktion'] = "cpMoreDoc";
$modul['CpEngineTagTpl'] = "[cp:moredoc]";
$modul['CpEngineTag'] = "\\\[cp:moredoc\\\]";
$modul['CpPHPTag'] = "<?php cpMoreDoc(); ?>";

include_once(BASE_DIR . "/modules/moredoc/sql.php"); 

function cpMoreDoc() {
	include_once(BASE_DIR . '/functions/func.modulglobals.php');
	modulGlobals('moredoc');

	$limit = 5; // ���������� ������� ����������
	$flagRubric = 1; // ��������� ��� ��� ������� ��������� (0 - ���, 1 - ��)

	$GLOBALS['tmpl']->cache_lifetime = 86400; // ����� ����� ���� (1 ���� (����������� � ��������: 60 ������ * 60 ����� * 24 ����))

  $moreDoc = '';
	$inRubric = '';
	$docId = (int)$_REQUEST['id'];  // �������� ID ���������

	$GLOBALS['tmpl']->caching = true; 	// ������������� ���� ����������

	// ���� ���� � ����, �� �������� ������������
	if (!$GLOBALS['tmpl']->is_cached($GLOBALS['tmpl']->cache_dir . 'moredoc.tpl', $docId)) {

		$tpl_dir   = BASE_DIR . '/modules/moredoc/templates/';	// ��������� ���� �� �������
    $GLOBALS['tmpl']->cache_dir = BASE_DIR . '/cache/moredoc/';		// ����� ��� �������� ����

		// ���������, ���� �� ����� ��� ����, ���� ��� (������ ���) � �������
		if (!is_file(BASE_DIR . '/cache/moredoc/')) {
			@mkdir(BASE_DIR . '/cache/moredoc/', 0777);					
		}

		// �������� �������� �����, �������, ����������� ������ �����
		$sql = $GLOBALS['db']->Query("SELECT RubrikId, MetaKeywords FROM " . PREFIX . "_documents WHERE Id ='".$docId."' LIMIT 1");
		$row = $sql->fetchrow();
		$keywords = explode(',',$row->MetaKeywords);
		$keywords = trim($keywords[0]);
		$rubric = $row->RubrikId;

		if ($keywords != '') {
			if ($flagRubric) {
				$inRubric = "AND RubrikId ='".$rubric."'";
			} else {
				$inRubric = '';
			}
			// ���� ��������� ��� ����������� �����-�� �����
			$sql = $GLOBALS['db']->Query("SELECT Id, Url, Titel, MetaDescription FROM " . PREFIX . "_documents WHERE (MetaKeywords LIKE '".$keywords."%') AND Id != '1' AND Id != '2' AND DokStatus != '0' AND Id != '".$docId."' ".$inRubric." ORDER BY Id DESC LIMIT 0,$limit");
			$moreDoc = array();

			while ($row = $sql->fetchrow()) {
				if ($GLOBALS['config']['doc_rewrite']) {
				} else {
					$row->Url = (CP_REWRITE==1) ? cp_rewrite('/index.php?id=' . $row->Id . '&amp;doc=' . cp_parse_linkname($row->Titel) ) : '/index.php?id=' . $row->Id . '&amp;doc=' . cp_parse_linkname($row->Titel);
				}
				array_push($moreDoc, $row);
			}
			// ��������� ����������
			$sql->Close();
	  }
			// ��������� ���������� moreDoc ��� ������������� � �������
			$GLOBALS['tmpl']->assign('moredoc', $moreDoc);
		}
	
	// ������� ������ moredoc.tpl
	$GLOBALS['tmpl']->display($tpl_dir . 'moredoc.tpl', $docId);
}
?>