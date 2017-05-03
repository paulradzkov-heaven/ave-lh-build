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

$modul_sql_deinstall[] = "DROP TABLE IF EXISTS CPPREFIX_modul_contacts;";
$modul_sql_deinstall[] = "DROP TABLE IF EXISTS CPPREFIX_modul_contact_fields;";
$modul_sql_deinstall[] = "DROP TABLE IF EXISTS CPPREFIX_modul_contact_info;";

$modul_sql_install[] = "DROP TABLE IF EXISTS CPPREFIX_modul_contacts;";
$modul_sql_install[] = "DROP TABLE IF EXISTS CPPREFIX_modul_contact_fields;";
$modul_sql_install[] = "DROP TABLE IF EXISTS CPPREFIX_modul_contact_info;";

$modul_sql_update[] = "ALTER TABLE CPPREFIX_modul_contacts ADD ZeigeBetreff TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL;";
$modul_sql_update[] = "ALTER TABLE CPPREFIX_modul_contacts ADD StandardBetreff VARCHAR(255) DEFAULT '’Ґ¬ ' NOT NULL;";
$modul_sql_update[] = "ALTER TABLE CPPREFIX_modul_contacts ADD Gruppen VARCHAR(255) DEFAULT '1,2,3,4,5,6' NOT NULL;";
$modul_sql_update[] = "ALTER TABLE CPPREFIX_modul_contacts ADD ZeigeKopie TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL;";
$modul_sql_update[] = "ALTER TABLE CPPREFIX_modul_contacts ADD TextKeinZugriff TEXT NOT NULL;";
$modul_sql_update[] = "UPDATE CPPREFIX_module SET Version = '".$modul['ModulVersion']."' WHERE ModulName='".$modul['ModulName']."';" ;

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_contacts (
  Id mediumint(5) unsigned NOT NULL auto_increment,
  Name varchar(100) NOT NULL default '',
  MaxZeichen int(8) unsigned NOT NULL default '20000',
  Empfaenger text NOT NULL,
  Empfaenger_Multi text NOT NULL,
  AntiSpam tinyint(1) unsigned NOT NULL default '1',
  MaxUpload mediumint(5) unsigned NOT NULL default '500',
  ZeigeBetreff tinyint(1) unsigned NOT NULL default '1',
  StandardBetreff varchar(200) NOT NULL default '’Ґ¬ ',
  Gruppen varchar(255) NOT NULL default '1,2,3,4,5,6',
  ZeigeKopie tinyint(1) unsigned NOT NULL default '1',
  TextKeinZugriff text NOT NULL,
  PRIMARY KEY  (Id)
) TYPE=MyISAM PACK_KEYS=0 DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_contact_fields (
  Id int(10) unsigned NOT NULL auto_increment,
  KontaktId mediumint(5) unsigned NOT NULL default '0',
  Feld varchar(100) NOT NULL default 'text',
  Position smallint(2) unsigned NOT NULL default '1',
  FeldTitel tinytext NOT NULL,
  Pflicht tinyint(1) unsigned NOT NULL default '0',
  StdWert tinytext NOT NULL,
  Aktiv tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_contact_info (
  Id int(10) unsigned NOT NULL auto_increment,
  FormId varchar(20) NOT NULL default '',
  Email varchar(200) NOT NULL default '',
  Datum int(14) unsigned NOT NULL default '0',
  Betreff varchar(255) NOT NULL default '',
  Text longtext NOT NULL,
  Aw_Zeit int(10) unsigned NOT NULL default '0',
  Aw_Email varchar(200) NOT NULL default '',
  Aw_Absender varchar(200) NOT NULL default '',
  Aw_Text longtext NOT NULL,
  FId mediumint(3) unsigned NOT NULL default '0',
  Anhang tinytext NOT NULL,
  Aw_Anhang tinytext NOT NULL,
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_contacts VALUES (1, 'Обратная связь', 20000, 'youremail@yourdomain.ru', '', 1, 120,'1','','1,2,3,4,5,6','1','У вас недостаточно прав для использования этой формы.');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_contact_fields VALUES ('', 1, 'fileupload', 4, 'Прикрепить файл', 0, '', 1);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_contact_fields VALUES ('', 1, 'fileupload', 3, 'Прикрепить файл', 0, '', 1);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_contact_fields VALUES ('', 1, 'textfield', 1, 'Сообщение', 1, '', 1);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_contact_fields VALUES ('', 1, 'dropdown', 2, 'Как вы оценивайте наш сайт?', 0, 'Плохой,Средне,Отличный', 1);";
?>