<?php
$modul_sql_deinstall[] = "DELETE FROM CPPREFIX_documents WHERE news=1;";
$modul_sql_deinstall[] = "ALTER TABLE CPPREFIX_documents DROP news, DROP rubid, DROP preimage, DROP pretext;";
$modul_sql_deinstall[] = "DROP TABLE IF EXISTS CPPREFIX_modul_news_rubz;";

$modul_sql_install[] = "ALTER TABLE CPPREFIX_documents ADD news TINYINT(1) UNSIGNED NOT NULL, ADD rubid MEDIUMINT UNSIGNED NOT NULL, ADD preimage VARCHAR(200) NOT NULL, ADD pretext TEXT NOT NULL;";
$modul_sql_install[] = "ALTER TABLE CPPREFIX_documents ADD INDEX (news, rubid);";
$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_news_rubz (
	id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
	parent MEDIUMINT UNSIGNED NOT NULL,
	name VARCHAR(200) NOT NULL,
	INDEX (parent),
	UNIQUE (id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_news_rubz (parent, name) VALUES (0, 'Все рубрики');";

$modul_sql_update[] = "UPDATE CPPREFIX_module SET CpEngineTag = '\\\[cp_news:([-0-9\\\]*)]' WHERE ModulName='".$modul['ModulName']."' LIMIT 1;";
$modul_sql_update[] = "UPDATE CPPREFIX_module SET Version = '".$modul['ModulVersion']."' WHERE ModulName='".$modul['ModulName']."';";
?>