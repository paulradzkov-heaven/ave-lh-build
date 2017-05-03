<?php
$modul_sql_deinstall[] = "DROP TABLE IF EXISTS CPPREFIX_modul_wonline;";

$modul_sql_install[] = "DROP TABLE IF EXISTS CPPREFIX_modul_wonline;";
$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_wonline (
  `Id` int(11) NOT NULL auto_increment,
  `User` varchar(200) NOT NULL default '',
  `Email` varchar(200) NOT NULL default '',
  `Ip` varchar(200) NOT NULL default '',
  `Page` text NOT NULL,
  `Time` datetime NOT NULL default '0000-00-00 00:00:00',
  `Referer` varchar(200) NOT NULL default '',
  `UserAgent` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`Id`)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_update[] = "UPDATE CPPREFIX_module SET CpEngineTag = '\\\[cp:wonline\\\]' WHERE ModulName='".$modul['ModulName']."' LIMIT 1;";
$modul_sql_update[] = "UPDATE CPPREFIX_module SET Version = '".$modul['ModulVersion']."' WHERE ModulName='".$modul['ModulName']."';";
?>