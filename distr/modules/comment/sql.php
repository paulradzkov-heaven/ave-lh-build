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

$modul_sql_deinstall[] = "DROP TABLE IF EXISTS CPPREFIX_modul_comments;";
$modul_sql_deinstall[] = "DROP TABLE IF EXISTS CPPREFIX_modul_comment_info;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_comments (
  Id smallint(2) unsigned NOT NULL auto_increment,
  MaxZeichen mediumint(5) unsigned NOT NULL default '1000',
  Gruppen text NOT NULL,
  Zensur tinyint(1) unsigned NOT NULL default '0',
  Aktiv tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_comments VALUES (1, 1000, '1,3,4', 0, 1);";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_comment_info (
  Id int(10) unsigned NOT NULL auto_increment,
  Elter int(10) unsigned NOT NULL default '0',
  DokId int(10) unsigned NOT NULL default '0',
  Author varchar(200) NOT NULL default '',
  Author_Id int(10) unsigned NOT NULL default '0',
  AEmail varchar(200) NOT NULL default '',
  AOrt varchar(200) NOT NULL default '',
  AWebseite varchar(200) NOT NULL default '',
  AIp varchar(100) NOT NULL default '',
  Erstellt int(14) unsigned NOT NULL default '0',
  Geaendert int(10) unsigned NOT NULL default '0',
  Titel varchar(200) NOT NULL default '',
  Text text NOT NULL,
  Status tinyint(1) unsigned NOT NULL default '1',
  Geschlossen tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (Id),
  KEY DokId (DokId),
  KEY Status (Status),
  KEY Elter (Elter)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";
?>