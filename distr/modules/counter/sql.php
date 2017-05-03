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

$modul_sql_update[] = "UPDATE CPPREFIX_module SET CpEngineTag = '\\\[cp_counter:([-a-zA-Z0-9\\\]*)\\]' WHERE ModulName = '".$modul['ModulName']."' LIMIT 1 ;";
$modul_sql_update[] = "UPDATE CPPREFIX_module SET Version = '".$modul['ModulVersion']."' WHERE ModulName='".$modul['ModulName']."';" ;

$modul_sql_deinstall[] = "DROP TABLE IF EXISTS CPPREFIX_modul_counter;";
$modul_sql_deinstall[] = "DROP TABLE IF EXISTS CPPREFIX_modul_counter_info;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_counter (
  Id int(10) UNSIGNED NOT NULL auto_increment,
  CName varchar(50) default NULL,
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_counter_info (
  Id int(11) UNSIGNED NOT NULL auto_increment,
  CId int(11) UNSIGNED NOT NULL,
  Ben_Ip varchar(50) default NULL,
  Ben_Os varchar(20) default NULL,
  Ben_Browser varchar(20) default NULL,
  Ben_Referer varchar(255) default NULL,
  Datum datetime default '0000-00-00 00:00:00',
  Datum_Unix int(14) UNSIGNED NOT NULL,
  Datum_Expire int(14) UNSIGNED NOT NULL,
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_counter VALUES (1, 'Основной счетчик');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_counter VALUES (2, 'Дополнительный счетчик');";
?>