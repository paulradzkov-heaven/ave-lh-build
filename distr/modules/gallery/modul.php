<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("BASE_DIR")) exit;

$modul['ModulName'] = "�������";
$modul['ModulPfad'] = "gallery";
$modul['ModulVersion'] = "2.0.01";
$modul['Beschreibung'] = "Gallery + Watermark + Lightbox + Lightview ��������! � ���������� /modules/gallery/uploads/ ������ ���� ����� �� ������!<br />�� ������ ���������� ���������� ��������� �����������, ������ ����� Gallery-ID ���������: -3 (� ���� ������ ���������� ����� ���������� ����� ������������� �� ��������)";
$modul['Autor'] = "cron";
$modul['MCopyright'] = "&copy; 2008 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['AdminEdit'] = 1;
$modul['ModulFunktion'] = "cpGallery";
$modul['CpEngineTagTpl'] = "[cp_gallery:XXX<em>-�����</em>]";
$modul['CpEngineTag'] = "\\\[cp_gallery:([-0-9\\\]*)\\]";
$modul['CpPHPTag'] = "<?php cpGallery(''\\\\\\\\1''); ?>";

include_once(BASE_DIR . "/modules/gallery/sql.php");

//=======================================================
// ���������� ����� �������
//=======================================================
include(BASE_DIR . "/modules/gallery/class.gallery.php");

function cpGallery($id)
{
  $own_lim = @explode("-", stripslashes($id));
  $lim = (!empty($own_lim[1])) ? $own_lim[1] : '';
  $id = $own_lim[0];

  $tpl_dir = BASE_DIR . "/modules/gallery/templates/";
  $lang_file = BASE_DIR . "/modules/gallery/lang/" . STD_LANG . ".txt";

  $GLOBALS['tmpl']->config_load($lang_file, "admin");
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  $gallery =& new Gallery;
  $gallery->showGallery($tpl_dir,$id,$lim);
}

if(isset($_REQUEST['module']) && $_REQUEST['module'] == 'gallery')
{
  $tpl_dir = BASE_DIR . "/modules/gallery/templates/";
  $lang_file = BASE_DIR . "/modules/gallery/lang/" . STD_LANG . ".txt";

  $GLOBALS['tmpl']->config_load($lang_file, "admin");
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  define("ONLYCONTENT", 1);

  if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'allimages')
  {
    $gallery =& new Gallery;
    $gallery->showGallery($tpl_dir,(int)$_REQUEST['gal_id'],'',1);
  } else {
    $gallery =& new Gallery;
    $gallery->displayImage($tpl_dir,(int)$_REQUEST['iid']);
  }
}

//=======================================================
// �������� � �����-������
//=======================================================
if(defined("ACP") && $_REQUEST['action'] != 'delete')
{
  $tpl_dir = BASE_DIR . "/modules/gallery/templates/";
  $lang_file = BASE_DIR . "/modules/gallery/lang/" . $_SESSION['cp_admin_lang'] . ".txt";
  $gallery =& new Gallery;

  $GLOBALS['tmpl']->config_load($lang_file, "admin");
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '')
  {
    switch($_REQUEST['moduleaction'])
    {
      // �������� ������ �������
      case '1':
        $gallery->showGalleries($tpl_dir);
      break;

      // ������� ����� �������
      case 'new':
        $gallery->newGallery($tpl_dir);
      break;

      // �������� ����������� � �������
      case 'add':
        $gallery->uploadForm($tpl_dir,(int)$_REQUEST['id']);
      break;

      // �������� ����������� �������
      case 'showimages':
        $gallery->showImages($tpl_dir,(int)$_REQUEST['id']);
      break;

      // �������� �������
      case 'delgallery':
        $gallery->delGallery((int)$_REQUEST['id']);
      break;

      // ��������� �������
      case 'galleryinfo':
        $gallery->galleryInfo($tpl_dir,(int)$_REQUEST['id']);
      break;

    }
  }
}
?>