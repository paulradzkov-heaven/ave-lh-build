<?php
define(REPLACE_MENT, substr($_SERVER['SCRIPT_NAME'], 0, -10));
include_once('inc/db.config.php');
include_once('inc/config.php');
include_once('functions/func.pref.php');
@include_once('functions/func.session.php');

ob_start();
session_start();
session_name("cp");
//error_reporting(E_ALL);

include_once("inc/init.php");

if(isset($_REQUEST['update_2x']) && $_REQUEST['update_2x']==1) {

  $querys = "RENAME TABLE %%PRFX%%_abfragen      TO %%PRFX%%_queries;#upgrade#"
   . "RENAME TABLE %%PRFX%%_abfragen_konditionen TO %%PRFX%%_queries_conditions;#upgrade#"
   . "RENAME TABLE %%PRFX%%_benutzer             TO %%PRFX%%_users;#upgrade#"
   . "RENAME TABLE %%PRFX%%_benutzer_gruppe      TO %%PRFX%%_user_groups;#upgrade#"
   . "RENAME TABLE %%PRFX%%_benutzer_rechte      TO %%PRFX%%_user_permissions;#upgrade#"
   . "RENAME TABLE %%PRFX%%_dokumente            TO %%PRFX%%_documents;#upgrade#"
   . "RENAME TABLE %%PRFX%%_dokumente_felder     TO %%PRFX%%_document_fields;#upgrade#"
   . "RENAME TABLE %%PRFX%%_dokumente_kommentare TO %%PRFX%%_document_comments;#upgrade#"
   . "RENAME TABLE %%PRFX%%_dokumente_rechte     TO %%PRFX%%_document_permissions;#upgrade#"
   . "RENAME TABLE %%PRFX%%_einstellungen        TO %%PRFX%%_settings;#upgrade#"
   . "RENAME TABLE %%PRFX%%_laender              TO %%PRFX%%_countries;#upgrade#"
   . "RENAME TABLE %%PRFX%%_navigation_dokumente TO %%PRFX%%_navigation_items;#upgrade#"
   . "RENAME TABLE %%PRFX%%_rubriken             TO %%PRFX%%_rubrics;#upgrade#"
   . "RENAME TABLE %%PRFX%%_rubriken_felder      TO %%PRFX%%_rubric_fields;#upgrade#"

   . "RENAME TABLE %%PRFX%%_modul_banner               TO %%PRFX%%_modul_banners;#upgrade#"
   . "RENAME TABLE %%PRFX%%_modul_bannerkategorie      TO %%PRFX%%_modul_banner_categories;#upgrade#"
   . "RENAME TABLE %%PRFX%%_modul_counter_daten        TO %%PRFX%%_modul_counter_info;#upgrade#"
   . "RENAME TABLE %%PRFX%%_modul_galerie              TO %%PRFX%%_modul_gallery;#upgrade#"
   . "RENAME TABLE %%PRFX%%_modul_galerie_bilder       TO %%PRFX%%_modul_gallery_images;#upgrade#"
   . "RENAME TABLE %%PRFX%%_modul_kommentar            TO %%PRFX%%_modul_comments;#upgrade#"
   . "RENAME TABLE %%PRFX%%_modul_kommentar_kommentare TO %%PRFX%%_modul_comment_info;#upgrade#"
   . "RENAME TABLE %%PRFX%%_modul_kontakt              TO %%PRFX%%_modul_contacts;#upgrade#"
   . "RENAME TABLE %%PRFX%%_modul_kontakt_felder       TO %%PRFX%%_modul_contact_fields;#upgrade#"
   . "RENAME TABLE %%PRFX%%_modul_kontakt_nachricht    TO %%PRFX%%_modul_contact_info;#upgrade#"
   . "RENAME TABLE %%PRFX%%_modul_suche                TO %%PRFX%%_modul_search;#upgrade#"

   . "RENAME TABLE %%PRFX%%_module_banners             TO %%PRFX%%_modul_banners;#upgrade#"
   . "RENAME TABLE %%PRFX%%_module_banner_categories   TO %%PRFX%%_modul_banner_categories;#upgrade#"
   . "RENAME TABLE %%PRFX%%_module_counter             TO %%PRFX%%_modul_counter;#upgrade#"
   . "RENAME TABLE %%PRFX%%_module_counter_info        TO %%PRFX%%_modul_counter_info;#upgrade#"
   . "RENAME TABLE %%PRFX%%_module_contacts            TO %%PRFX%%_modul_contacts;#upgrade#"
   . "RENAME TABLE %%PRFX%%_module_contact_fields      TO %%PRFX%%_modul_contact_fields;#upgrade#"
   . "RENAME TABLE %%PRFX%%_module_contact_info        TO %%PRFX%%_modul_contact_info;#upgrade#"
   . "RENAME TABLE %%PRFX%%_module_login               TO %%PRFX%%_modul_login;#upgrade#"
   . "RENAME TABLE %%PRFX%%_module_search              TO %%PRFX%%_modul_search;#upgrade#"
   . "RENAME TABLE %%PRFX%%_module_gallery             TO %%PRFX%%_modul_gallery;#upgrade#"
   . "RENAME TABLE %%PRFX%%_module_gallery_images      TO %%PRFX%%_modul_gallery_images;#upgrade#"
   . "RENAME TABLE %%PRFX%%_module_comments            TO %%PRFX%%_modul_comments;#upgrade#"
   . "RENAME TABLE %%PRFX%%_module_comment_info        TO %%PRFX%%_modul_comment_info;#upgrade#"
   . "RENAME TABLE %%PRFX%%_module_newsarchive         TO %%PRFX%%_modul_newsarchive;#upgrade#"

   . "RENAME TABLE %%PRFX%%_guestbook_settings         TO %%PRFX%%_modul_guestbook_settings;#upgrade#"
   . "RENAME TABLE %%PRFX%%_guestbook                  TO %%PRFX%%_modul_guestbook;#upgrade#"
   . "RENAME TABLE %%PRFX%%_smileys                    TO %%PRFX%%_modul_guestbook_smileys;#upgrade#"

   . "ALTER TABLE %%PRFX%%_documents          ADD Url             VARCHAR(255)        DEFAULT ''       NOT NULL AFTER RubrikId;#upgrade#"
   . "ALTER TABLE %%PRFX%%_documents          ADD MetaDescription TINYTEXT                             NOT NULL AFTER MetaKeywords;#upgrade#"
   . "ALTER TABLE %%PRFX%%_queries_conditions ADD Oper            VARCHAR(5)          DEFAULT 'OR'     NOT NULL;#upgrade#"
   . "ALTER TABLE %%PRFX%%_module_banners     ADD Target          VARCHAR(255)        DEFAULT '_blank' NOT NULL;#upgrade#"
   . "ALTER TABLE %%PRFX%%_modul_gallery      ADD Watermark       VARCHAR(255)        DEFAULT '';#upgrade#"
   . "ALTER TABLE %%PRFX%%_modul_gallery      ADD LightBox        TINYINT(1) UNSIGNED DEFAULT '1'      NOT NULL;#upgrade#"
   . "ALTER TABLE %%PRFX%%_modul_gallery      ADD LightView       ENUM('0','1')       DEFAULT '0'      NOT NULL;#upgrade#"
   . "ALTER TABLE %%PRFX%%_rubrics            ADD UrlPrefix       VARCHAR(50)         DEFAULT '/'      NOT NULL AFTER RubrikName;#upgrade#"

   . "ALTER TABLE %%PRFX%%_module_contact_fields CHANGE StdWert     StdWert         LONGTEXT NOT NULL;#upgrade#"
   . "ALTER TABLE %%PRFX%%_rubric_fields         CHANGE RubPosition rubric_position INT(10) UNSIGNED DEFAULT '0' NOT NULL;#upgrade#"
   . "ALTER TABLE %%PRFX%%_rubric_fields         CHANGE StdWert     StdWert         VARCHAR(255) DEFAULT '' NOT NULL;#upgrade#"

   . "INSERT INTO %%PRFX%%_countries VALUES (53, 'CI', 'Кот-д Ивуар (Берег Слоновой Кости)', 2, 2);#upgrade#";

  // Переименовываем названия модулей
  $GLOBALS['db']->Query("RENAME TABLE " . PREFIX . "_modules TO " . PREFIX . "_module");
  $sql = $GLOBALS['db']->Query("SELECT Id,ModulPfad FROM " . PREFIX . "_module");
  while ($row = $sql->fetchrow()) {
    if(!include("modules/" . $row->ModulPfad . "/modul.php")) {
      echo "Не найден модуль в папке /modules/" . $row->ModulPfad . "<br />";
    } else {
      $querys .= "UPDATE %%PRFX%%_module SET ModulName = '" . $modul['ModulName'] . "' WHERE Id = '" . $row->Id . "';#upgrade#";
      unset($row);
      unset($modul);
    }
  }

  // Формируем уникальные ссылки для ЧПУ
  $sql = $GLOBALS['db']->Query("SELECT Id,Titel FROM " . PREFIX . "_dokumente");
  while ($row = $sql->fetchrow()) {
  // Для уникальности используем Id и 200 левых символов транслитерированного заголовка
    $translittitel = "/" . substr(cp_parse_linkname($row->Titel), 0, 200) . "/" . $row->Id . "/";
  // Для построения ссылок без Id раскомментируйте следующую строку
  //$translittitel = "/" . substr(cp_parse_linkname($row['Titel']), 0, 253) . "/";
    if (1 == $row->Id) $translittitel = "/";
    if (2 == $row->Id) $translittitel = "/404/";
    $querys .= "UPDATE %%PRFX%%_documents SET Url = '" . $translittitel . "' WHERE Id = '" . $row->Id . "';#upgrade#";
  }

  // Если установлен "Магазин" добавляем таблицу
  $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_shop");
  if ($row = $sql->fetchrow()) {
    $querys .= "CREATE TABLE %%PRFX%%_modul_shop_downloads (
      Id int(11) NOT NULL auto_increment,
      Benutzer int(11) NOT NULL default '0',
      PName varchar(255) NOT NULL default '',
      ArtikelId varchar(50) NOT NULL default '',
      DownloadBis int(11) NOT NULL default '0',
      Lizenz varchar(20) NOT NULL default '',
      Downloads int(11) NOT NULL default '0',
      UrlLizenz varchar(255) NOT NULL default '',
      KommentarBenutzer text NOT NULL,
      KommentarAdmin text NOT NULL,
      Gesperrt tinyint(1) NOT NULL default '0',
      GesperrtGrund text NOT NULL,
      Position smallint(2) NOT NULL default '1',
      PRIMARY KEY  (Id)
    ) TYPE=MyISAM DEFAULT CHARSET=cp1251;#upgrade#";
  }

  $querys .= "ALTER TABLE %%PRFX%%_documents ADD UNIQUE(Url)";

  $querys = str_replace('%%PRFX%%', PREFIX, $querys);

  $sql_array = explode("#upgrade#", $querys);
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>Update 2x</title>
</head>

<body style="font-size:12px; line-height:1.5">';
//  echo '<h2>Список запросов</h2>
//<ol>';
  foreach ($sql_array as $q) {
    $GLOBALS['db']->Query($q);
//echo "<li>$q</li>
//";
  }
//  echo '</ol>';
  echo '<h2>Обновление структуры БД завершено!</h2>
</body>
</html>';

  $tmpl = new cp_template('/templates/'.STDTPL.'/');
  $GLOBALS['tmpl']->clear_compiled_tpl();


} else {
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>Update 2x</title>
</head>

<body style="font-size:14px; line-height:1.5; padding:30px">
  <form method="post" action="update.php?update_2x=1">
  	<h2>Обновление структуры БД с версий 1.4 - 2.0(SP1 и SP2) до 2.01 и старше</h2>

  	<p>С изменённой БД могут работать файлы любой версии начиная с 2.01 и старше. <br />
    После обновления структуры БД надо зайти в раздел "Модули" административной панели и завершить обновление некоторых модулей. <br />
    В зависимости от версии используемых файлов, в колонке "Действия", для модулей требующих обновление, появится соответствующая иконка.</p>

  	<p>Перед обновлением необходимо сделать копии всех файлов и базы данных.<br />
    После этого надо скопировать в корневую папку сайта все файлы сборки кроме файла /inc/db.config.php</p>

  	<p><b>Внимание!</b><br />
  	После обновления структуры БД могут возникнуть проблемы с некоторыми вариантами модулей "FAQ" и "Download".</p><br />

  	<input type="submit" value="Обновить БД">
	</form>
</body>
</html>';
}
?>