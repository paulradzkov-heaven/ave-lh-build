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

$modul_sql_deinstall[] = "ALTER TABLE CPPREFIX_documents DROP INDEX MetaKeywords;";

$modul_sql_install[] = "ALTER TABLE CPPREFIX_documents ADD FULLTEXT (MetaKeywords)";
?>