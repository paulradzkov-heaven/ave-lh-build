<?
/*::::::::::::::::::::::::::::::::::::::::
 Module name: Video Player
 Short Desc: Add video files any place
 Version: 1.0 alpha
 Authors:  Mad Den (mad_den@mail.ru)
 Date: november 01, 2008
::::::::::::::::::::::::::::::::::::::::*/

class VideoPlayer{

// Вывод видео на странице
  function displayVideo($tpl_dir,$Id) {
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_videoplayer_files WHERE Id = '" . $Id . "'");
    $video = $sql->fetchrow();
    $GLOBALS['tmpl']->assign("Id", $video->Id);
	$GLOBALS['tmpl']->assign("VideoTitle", $video->VideoTitle);
	$GLOBALS['tmpl']->assign("FileName", $video->FileName);
	$GLOBALS['tmpl']->assign("ImagePreview", $video->ImagePreview);
	$GLOBALS['tmpl']->assign("Duration", $video->Duration);
	$GLOBALS['tmpl']->assign("BufferLength", $video->BufferLength);
	$GLOBALS['tmpl']->assign("Width", $video->Width);
	$GLOBALS['tmpl']->assign("Height", $video->Height);
	$GLOBALS['tmpl']->assign("AllowFullScreen", $video->AllowFullScreen);
	$GLOBALS['tmpl']->assign("AllowScriptAccess", $video->AllowScriptAccess);
	$GLOBALS['tmpl']->display($tpl_dir . "player.tpl");
  }

// Вывод списка текстовых блоков
  function showVideo($tpl_dir){
    $Id = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_videoplayer_files");
    while ($result = $sql->fetchrow()) {
      array_push($Id, $result);
    }
    $GLOBALS['tmpl']->assign("Id", $Id);
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_show.tpl"));
  }

// Вывод видео в превью
  function viewVideo($tpl_dir,$Id) {
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_videoplayer_files WHERE Id = '" . $Id . "'");
    $video = $sql->fetchrow();
    $GLOBALS['tmpl']->assign("Id", $video->Id);
	$GLOBALS['tmpl']->assign("VideoTitle", $video->VideoTitle);
	$GLOBALS['tmpl']->assign("FileName", $video->FileName);
	$GLOBALS['tmpl']->assign("ImagePreview", $video->ImagePreview);
	$GLOBALS['tmpl']->assign("Duration", $video->Duration);
	$GLOBALS['tmpl']->assign("BufferLength", $video->BufferLength);
	$GLOBALS['tmpl']->assign("Width", $video->Width);
	$GLOBALS['tmpl']->assign("Height", $video->Height);
	$GLOBALS['tmpl']->assign("AllowFullScreen", $video->AllowFullScreen);
	$GLOBALS['tmpl']->assign("AllowScriptAccess", $video->AllowScriptAccess);
	$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_view.tpl"));
  }

// Редактирование
  function editVideo($tpl_dir,$Id) {
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_videoplayer_files WHERE Id = '" . $Id . "'");
    $video = $sql->fetchrow();
    $GLOBALS['tmpl']->assign("Id", $video->Id);
	$GLOBALS['tmpl']->assign("VideoTitle", $video->VideoTitle);
	$GLOBALS['tmpl']->assign("FileName", $video->FileName);
	$GLOBALS['tmpl']->assign("ImagePreview", $video->ImagePreview);
	$GLOBALS['tmpl']->assign("Duration", $video->Duration);
	$GLOBALS['tmpl']->assign("BufferLength", $video->BufferLength);
	$GLOBALS['tmpl']->assign("Width", $video->Width);
	$GLOBALS['tmpl']->assign("Height", $video->Height);
	$GLOBALS['tmpl']->assign("AllowFullScreen", $video->AllowFullScreen);
	$GLOBALS['tmpl']->assign("AllowScriptAccess", $video->AllowScriptAccess);
	$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_edit.tpl"));
  }

// Редактирование / Сохранить
function saveVideo($tpl_dir,$Id) {
  if(isset($_REQUEST["sub"]) && $_REQUEST["sub"] == "savedit") {
	$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_videoplayer_files
		SET
        VideoTitle			 = '" . $_REQUEST["VideoTitle"] . "',
		FileName			 = '" . $_REQUEST["FileName"] . "',
		ImagePreview		 = '" . $_REQUEST["ImagePreview"] . "',
		Duration			 = '" . $_REQUEST["Duration"] . "',
		BufferLength		 = '" . $_REQUEST["BufferLength"] . "',
		Width				 = '" . $_REQUEST["Width"] . "',
		Height				 = '" . $_REQUEST["Height"] . "',
		AllowFullScreen		 = '" . $_REQUEST["AllowFullScreen"] . "',
		AllowScriptAccess	 = '" . $_REQUEST["AllowScriptAccess"] . "'
        WHERE Id = '" . $_REQUEST["Id"] . "'");
	header('Location:index.php?do=modules&action=modedit&mod=videoplayer&moduleaction=1&cp=' . SESSION);
	exit;

  }else{

	$sql = $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_videoplayer_files 	(VideoTitle,FileName,ImagePreview,Duration,BufferLength,Width,Height,AllowFullScreen,AllowScriptAccess) VALUES ('" . $_REQUEST["VideoTitle"] . "','" . $_REQUEST["FileName"] . "','" . $_REQUEST["ImagePreview"] . "','" . $_REQUEST["Duration"] . "','" . $_REQUEST["BufferLength"] . "','" . $_REQUEST["Width"] . "','" . $_REQUEST["Height"] . "','" . $_REQUEST["AllowFullScreen"] . "','" . $_REQUEST["AllowScriptAccess"] . "')");
	header('Location:index.php?do=modules&action=modedit&mod=videoplayer&moduleaction=1&cp=' . SESSION);
	exit;
	}
}

// Новый
  function addVideo($tpl_dir) {
	$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_new.tpl"));
  }

// Удаление видео
  function delVideo($Id) {
    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_videoplayer_files WHERE Id = '" . (int)$_REQUEST["Id"] . "'");
	header('Location:index.php?do=modules&action=modedit&mod=videoplayer&moduleaction=1&cp=' . SESSION);
	exit;
  }

}
?>
