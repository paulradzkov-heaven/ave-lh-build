<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function fetchFields($template_out = '', $key = '') {
  global $session;

  $GLOBALS['tmpl']->config_load(SOURCE_DIR . "/admin/lang/" . $_SESSION['cp_admin_lang'] . "/fields.txt", 'fields');
  $felder_vars = $GLOBALS['tmpl']->get_config_vars();

  $felder = array(
  array('id' => 'langtext','name' => $felder_vars['FIELD_TEXTAREA']),
  array('id' => 'smalltext','name' => $felder_vars['FIELD_TEXTAREA_S']),
  array('id' => 'kurztext','name' => $felder_vars['FIELD_TEXT']),
  array('id' => 'author','name' => $felder_vars['FIELD_AUTHOR']),
  array('id' => 'created','name' => $felder_vars['FIELD_DATE_CREATE']),
  array('id' => 'bild', 'name' => $felder_vars['FIELD_IMAGE']),
  array('id' => 'bild_links','name' => $felder_vars['FIELD_IMAGE_LEFT']),
  array('id' => 'bild_rechts','name' => $felder_vars['FIELD_IMAGE_RIGHT']),
  array('id' => 'link','name' => $felder_vars['FIELD_LINK']),
  array('id' => 'link_ex', 'name' => $felder_vars['FIELD_LINK_BLANK']),
  array('id' => 'dropdown','name' => $felder_vars['FIELD_DROPDOWN']),
  array('id' => 'php','name' => $felder_vars['FIELD_PHP']),
  array('id' => 'html','name' => $felder_vars['FIELD_HTML']),
  array('id' => 'js','name' => $felder_vars['FIELD_JAVASCRIPT']),
  array('id' => 'flash', 'name' => $felder_vars['FIELD_FLASH']),
  array('id' => 'download','name' => $felder_vars['FIELD_FILE']),
  array('id' => 'video_avi','name' => $felder_vars['FIELD_VIDEO_AVI']),
  array('id' => 'video_wmf','name' => $felder_vars['FIELD_VIDEO_WMF']),
  array('id' => 'video_wmv', 'name' => $felder_vars['FIELD_VIDEO_WMV']),
  array('id' => 'video_mov', 'name' => $felder_vars['FIELD_VIDEO_MOV'])
  );

  if($template_out==1) {
    $GLOBALS['tmpl']->assign('feld_array',$felder);
  } else {
    return $felder;
  }
}
?>