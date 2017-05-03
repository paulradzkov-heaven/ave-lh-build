<?
/*::::::::::::::::::::::::::::::::::::::::
 Module name: Video, Audio Player
 Short Desc: Add video and audio files any place
 Version: 1.0 alpha
 Authors:  Mad Den (mad_den@mail.ru)
 Date: november 01, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("BASE_DIR")) exit;

$modul['ModulName'] = "Видео, Аудио плеер";
$modul['ModulPfad'] = "videoplayer";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "";
$modul['Autor'] = "Mad Den";
$modul['MCopyright'] = "&copy; 2008 Overdoze Team";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['AdminEdit'] = 1;
$modul['ModulTemplate'] = 0;
$modul['ModulFunktion'] = "cpVideoPlayer";
$modul['CpEngineTagTpl'] = "[cp_play:XXX]";
$modul['CpEngineTag'] = "\\\[cp_play:([0-9\\\]*)\\]";
$modul['CpPHPTag'] = "<?php cpVideoPlayer(''\\\\\\\\1''); ?>";

// Подключаем файлы Sql и файл класса
require_once(BASE_DIR . "/modules/videoplayer/sql.php");
require_once(BASE_DIR . "/modules/videoplayer/class.player.php");

// Показать видео файл
function cpVideoPlayer($Id) {
  $tpl_dir = BASE_DIR . "/modules/videoplayer/templates/";
  $video =& new VideoPlayer;
  $video->displayVideo($tpl_dir,$Id);
}

// Администрирование
if(defined("ACP") && $_REQUEST['action'] != 'delete') {

  $tpl_dir   = BASE_DIR . "/modules/videoplayer/templates/";
  $lang_file = BASE_DIR . "/modules/videoplayer/lang/" . STD_LANG . ".txt";

  $GLOBALS['tmpl']->config_load($lang_file);
  $config_vars = $GLOBALS['tmpl']->get_config_vars();
  $GLOBALS['tmpl']->assign("config_vars", $config_vars);

  if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] != '') {
    switch($_REQUEST['moduleaction']) {

      case '1':
        VideoPlayer::showVideo($tpl_dir);
      break;

      case 'view':
        VideoPlayer::viewVideo($tpl_dir,$Id);
      break;

      case 'del':
        VideoPlayer::delVideo($Id);
      break;

      case 'edit':
        VideoPlayer::editVideo($tpl_dir,$Id);
      break;

      case 'save':
        VideoPlayer::saveVideo($tpl_dir,$Id);
      break;

      case 'new':
        VideoPlayer::addVideo($tpl_dir);
      break;

    }
  }
}




?>