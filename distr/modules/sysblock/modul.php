<?php
/*::::::::::::::::::::::::::::::::::::::::
 Module name: SysBlock
 Short Desc: System block in any place
 Version: 0.4 alpha
 Authors:  Mad Den (mad_den@mail.ru)
 Date: august 31, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined('BASE_DIR')) exit;

$modul['ModulName'] = "��������� �����";
$modul['ModulPfad'] = "sysblock";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "������ ������ ������������ ��� ������ ��������� ������ � ������������ ���������� � ������� ��� ���������.<br /><br />����� ������������ PHP � ���� �������<br /><br />��� ������ ����������� ����������� ��������� ���<br /><strong>[cp_sysblock:XXX]</strong>";
$modul['Autor'] = "Mad Den";
$modul['MCopyright'] = "&copy; 2008 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['AdminEdit'] = 1;
$modul['ModulTemplate'] = 0;
$modul['ModulFunktion'] = "cpSysBlock";
$modul['CpEngineTagTpl'] = "[cp_sysblock:XXX]";
$modul['CpEngineTag'] = "\\\[cp_sysblock:([0-9\\\]*)\\]";
$modul['CpPHPTag'] = "<?php cpSysBlock(''\\\\\\\\1''); ?>";

// ���������� ����� Sql � ���� ������
require_once(BASE_DIR . "/modules/sysblock/sql.php");
require_once(BASE_DIR . "/modules/sysblock/class.sysblock.php");


// �������� ��������� ����
function cpSysBlock($id) {
  sysblock::ShowSysBlock(stripslashes($id));
}

// �����������������
if(defined("ACP") && $_REQUEST['action'] != 'delete') {
  $tpl_dir   = BASE_DIR . "/modules/sysblock/templates/";
  $lang_file = BASE_DIR . "/modules/sysblock/lang/" . STD_LANG . ".txt";

  $GLOBALS['tmpl']->config_load($lang_file);
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '') {
    switch($_REQUEST['moduleaction']) {

      case '1':
        sysblock::ListBlock($tpl_dir);
      break;

      case 'del':
        sysblock::DelBlock();
      break;

      case 'edit':
        sysblock::EditBlock($tpl_dir);
      break;

      case 'saveedit':
        sysblock::SaveBlock();
      break;

    }
  }
}
?>