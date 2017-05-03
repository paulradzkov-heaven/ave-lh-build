<?php 
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

$modul_sql_deinstall = array();
$modul_sql_install = array();
$modul_sql_update = array();

$modul_sql_update[] = "UPDATE CPPREFIX_module SET CpEngineTag = '\\\[cp_gallery:([-0-9\\\]*)]' WHERE ModulName = '".$modul['ModulName']."' LIMIT 1 ;";
$modul_sql_update[] = "UPDATE CPPREFIX_module SET Version = '".$modul['ModulVersion']."' WHERE ModulName='".$modul['ModulName']."';" ;

$modul_sql_deinstall[] = "DROP TABLE IF EXISTS CPPREFIX_modul_gallery;";
$modul_sql_deinstall[] = "DROP TABLE IF EXISTS CPPREFIX_modul_gallery_images;";

$modul_sql_install[] = "DROP TABLE IF EXISTS CPPREFIX_modul_gallery;";
$modul_sql_install[] = "DROP TABLE IF EXISTS CPPREFIX_modul_gallery_images;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_gallery (
  Id int(14) unsigned NOT NULL auto_increment,
  GName varchar(200) NOT NULL default '',
  Beschreibung text NOT NULL,
  Author mediumint(5) unsigned NOT NULL default '0',
  Erstellt int(14) unsigned NOT NULL default '0',
  ThumbBreite mediumint(4) unsigned NOT NULL default '120',
  MaxZeile smallint(2) unsigned NOT NULL default '4',
  ZeigeTitel tinyint(1) unsigned NOT NULL default '1',
  ZeigeBeschreibung tinyint(1) unsigned NOT NULL default '1',
  ZeigeGroesse tinyint(1) unsigned NOT NULL default '0',
  TypeOut tinyint(1) unsigned NOT NULL default '4',
  MaxBilder mediumint(4) unsigned NOT NULL default '18',
  Watermark varchar(255) default '',
  GPfad text NOT NULL,
  Sort varchar(5) NOT NULL default 'asc',
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_gallery_images (
  Id int(10) unsigned NOT NULL auto_increment,
  GalId int(10) unsigned NOT NULL default '0',
  Pfad varchar(255) NOT NULL default '',
  Author mediumint(5) unsigned NOT NULL default '0',
  BildTitel varchar(200) NOT NULL default '',
  BildBeschr tinytext NOT NULL,
  Endung varchar(6) NOT NULL default '',
  Erstellt int(14) unsigned NOT NULL default '0',
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_update[] = "ALTER TABLE `CPPREFIX_modul_gallery` CHANGE `Klickbar` `TypeOut` tinyint(1) unsigned DEFAULT '4' NOT NULL;";
$modul_sql_update[] = "ALTER TABLE `CPPREFIX_modul_gallery` DROP `LightBox`;";
$modul_sql_update[] = "ALTER TABLE `CPPREFIX_modul_gallery` DROP `LightView`;";
$modul_sql_update[] = "ALTER TABLE `CPPREFIX_modul_gallery` ADD `GPfad` text NOT NULL;";
?>