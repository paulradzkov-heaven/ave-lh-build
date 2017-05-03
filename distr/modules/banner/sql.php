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

$modul_sql_update[] = "ALTER TABLE CPPREFIX_modul_banners ADD COLUMN `Width` int(10) unsigned NOT NULL default '0';";
$modul_sql_update[] = "ALTER TABLE CPPREFIX_modul_banners ADD COLUMN `Height` int(10) unsigned NOT NULL default '0';";
$modul_sql_update[] = "UPDATE CPPREFIX_module SET Version = '" . $modul['ModulVersion'] . "' WHERE ModulName='" . $modul['ModulName'] . "';" ;

$modul_sql_deinstall[] = "DROP TABLE IF EXISTS CPPREFIX_modul_banner_categories;";
$modul_sql_deinstall[] = "DROP TABLE IF EXISTS CPPREFIX_modul_banners;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_banner_categories (
  Id mediumint(5) unsigned NOT NULL auto_increment,
  KatName varchar(100) NOT NULL default '',
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "DROP TABLE IF EXISTS CPPREFIX_modul_banners;";
$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_banners (
  Id int(10) unsigned NOT NULL auto_increment,
  KatId mediumint(5) unsigned NOT NULL default '1',
  Bannertags varchar(255) NOT NULL default '',
  BannerUrl varchar(255) NOT NULL default '',
  Gewicht tinyint(3) unsigned NOT NULL default '0',
  Bannername varchar(100) NOT NULL default '',
  Views int(10) unsigned NOT NULL default '0',
  Klicks int(10) unsigned NOT NULL default '0',
  BildAlt varchar(255) NOT NULL default '',
  MaxKlicks int(10) unsigned NOT NULL default '0',
  MaxViews int(10) unsigned NOT NULL default '0',
  ZStart smallint(2) unsigned NOT NULL default '0',
  ZEnde smallint(2) unsigned NOT NULL default '0',
  Aktiv tinyint(1) unsigned NOT NULL default '1',
  Target varchar(255) NOT NULL default '_blank',
  Width int(10) unsigned NOT NULL default '0',
  Height int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_banner_categories VALUES (1, '��������� 1');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_banner_categories VALUES (2, '��������� 2');";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_banners VALUES ('', 1, 'banner.jpg', 'http://www.overdoze.ru', 1, 'Overdoze-Banner', 0, 0, '������� CMS, ���������� �������, ����� � ��������� �������������', 0, 0, 0, 0, 1, '_self', 0, 0);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_banners VALUES ('', 1, 'banner2.gif', 'http://www.google.de', 1, 'Google-Banner', 0, 0, '�������� ���� Google', 0, 0, 0, 0, 1, '_blank', 0, 0);";
?>