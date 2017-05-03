<?php
/*::::::::::::::::::::::::::::::::::::::::
 Module name: Video Player
 Short Desc: Add video files any place
 Version: 1.0 alpha
 Authors:  Mad Den (mad_den@mail.ru)
 Date: november 01, 2008
::::::::::::::::::::::::::::::::::::::::*/

$modul_sql_deinstall = array();
$modul_sql_install = array();
$modul_sql_update = array();

$modul_sql_update[] = "UPDATE CPPREFIX_module SET Version = '" . $modul['ModulVersion'] . "' WHERE ModulName='" . $modul['ModulName'] . "';" ;

$modul_sql_deinstall[] = "DROP TABLE IF EXISTS CPPREFIX_modul_videoplayer_files;";

$modul_sql_install[] = "DROP TABLE IF EXISTS CPPREFIX_modul_videoplayer_files;";
$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_videoplayer_files (
  Id int(10) unsigned NOT NULL auto_increment,
  VideoTitle varchar(255) NOT NULL default '',
  FileName varchar(100) NOT NULL default '',
  ImagePreview varchar(100) NOT NULL default '',
  Duration int(10) unsigned NOT NULL default '0',
  BufferLength int(10) unsigned NOT NULL default '0',
  Width int(10) unsigned NOT NULL default '0',
  Height int(10) unsigned NOT NULL default '0',
  AllowFullScreen varchar(10) NOT NULL default '0',
  AllowScriptAccess varchar(10) NOT NULL default '0',
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

?>