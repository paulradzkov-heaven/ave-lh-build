<?php
/*::::::::::::::::::::::::::::::::::::::::
 Module name: SysBlock
 Short Desc: System block in any place
 Version: 0.4 alpha
 Authors:  Mad Den (mad_den@mail.ru)
 Date: august 31, 2008
::::::::::::::::::::::::::::::::::::::::*/

$modul_sql_deinstall = array();
$modul_sql_install = array();
$modul_sql_update = array();

$modul_sql_update[] = "UPDATE CPPREFIX_module SET Version = '".$modul['ModulVersion']."' WHERE ModulName='".$modul['ModulName']."';" ;

$modul_sql_deinstall[] = "DROP TABLE IF EXISTS CPPREFIX_modul_sysblock;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_sysblock (
  id mediumint(5) unsigned NOT NULL auto_increment,
  sysblock_name varchar(100) NOT NULL default '',
  sysblock_text longtext NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_sysblock VALUES (1, 'Тестовый блок', 'Тестовый текст');";
?>