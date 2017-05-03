<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

$modul_sql_update[] = "UPDATE CPPREFIX_module SET Version = '".$modul['ModulVersion']."' WHERE ModulName='".$modul['ModulName']."';" ;
$modul_sql_update[] = "ALTER TABLE `CPPREFIX_modul_shop_artikel` ADD `PosiStartseite` SMALLINT(2) UNSIGNED DEFAULT '1' NOT NULL ;";
$modul_sql_update[] = "ALTER TABLE `CPPREFIX_modul_shop` CHANGE `Waehrung2Multi` `Waehrung2Multi` DECIMAL( 10, 4 ) DEFAULT '0.0000';";

$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_artikel";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_artikel_bilder";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_artikel_downloads";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_artikel_kommentare";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_bestellungen";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_einheiten";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_downloads";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_gutscheine";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_hersteller";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_kategorie";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_kundenrabatte";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_merkliste";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_staffelpreise";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_ust";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_varianten";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_varianten_kategorien";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_versandarten";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_versandkosten";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_versandzeit";
$modul_sql_deinstall[] = "DROP TABLE CPPREFIX_modul_shop_zahlungsmethoden";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_downloads (
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
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";


$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop (
  Id smallint(2) unsigned NOT NULL auto_increment,
  Aktiv tinyint(1) unsigned NOT NULL default '1',
  Waehrung varchar(10) NOT NULL default 'EUR',
  WaehrungSymbol varchar(10) NOT NULL default '&euro;',
  Waehrung2 varchar(10) NOT NULL default '',
  WaehrungSymbol2 varchar(10) NOT NULL default '',
  Waehrung2Multi decimal(10,4) NOT NULL default '0.0000',
  ShopLand char(2) NOT NULL default 'DE',
  ArtikelMax mediumint(3) unsigned NOT NULL default '10',
  KaufLagerNull tinyint(1) unsigned NOT NULL default '0',
  VersandLaender tinytext NOT NULL,
  Agb text NOT NULL,
  VersFrei tinyint(1) unsigned NOT NULL default '0',
  VersFreiBetrag decimal(7,2) NOT NULL default '0.00',
  AdresseText text NOT NULL,
  AdresseHTML text NOT NULL,
  Logo varchar(255) NOT NULL default '',
  EmailFormat enum('text','html') NOT NULL default 'text',
  AbsEmail varchar(200) NOT NULL default '',
  AbsName varchar(200) NOT NULL default '',
  EmpEmail varchar(200) NOT NULL default '',
  BetreffBest varchar(255) NOT NULL default '',
  GutscheinCodes tinyint(1) unsigned NOT NULL default '1',
  ZeigeEinheit tinyint(1) unsigned NOT NULL default '1',
  ZeigeNetto tinyint(1) NOT NULL default '1',
  Steuern tinyint(1) unsigned NOT NULL default '1',
  ShopWillkommen text NOT NULL,
  KategorienStart tinyint(1) unsigned NOT NULL default '1',
  KategorienSons tinyint(1) unsigned NOT NULL default '1',
  ZufallsAngebot tinyint(1) unsigned NOT NULL default '1',
  ZufallsAngebotKat tinyint(1) unsigned NOT NULL default '1',
  BestUebersicht tinyint(1) unsigned NOT NULL default '1',
  ShopFuss text NOT NULL,
  VersandInfo text NOT NULL,
  DatenschutzInf text NOT NULL,
  Impressum text NOT NULL,
  Merkliste tinyint(1) unsigned NOT NULL default '1',
  Topseller tinyint(1) unsigned NOT NULL default '1',
  TemplateArtikel varchar(100) NOT NULL default '',
  Vorschaubilder mediumint(3) NOT NULL default '80',
  Topsellerbilder mediumint(3) NOT NULL default '40',
  GastBestellung tinyint(1) unsigned NOT NULL default '0',
  Kommentare tinyint(1) unsigned NOT NULL default '1',
  KommentareGast tinyint(1) NOT NULL default '0',
  ZeigeWaehrung2 tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_artikel (
  Id int(10) unsigned NOT NULL auto_increment,
  ArtNr varchar(200) NOT NULL default '',
  KatId int(10) unsigned NOT NULL default '0',
  KatId_Multi text NOT NULL,
  ArtName varchar(255) NOT NULL default '',
  Aktiv smallint(1) unsigned NOT NULL default '1',
  Preis decimal(7,2) NOT NULL default '0.00',
  PreisListe decimal(5,2) NOT NULL default '0.00',
  Bild varchar(255) NOT NULL default '',
  Bild_Typ varchar(10) NOT NULL default 'jpg',
  Bilder text NOT NULL,
  TextKurz text NOT NULL,
  TextLang text NOT NULL,
  Gewicht decimal(6,3) NOT NULL default '0.000',
  Angebot tinyint(1) unsigned NOT NULL default '0',
  AngebotBild varchar(255) NOT NULL default '',
  UstZone smallint(2) unsigned NOT NULL default '1',
  Erschienen int(14) unsigned NOT NULL default '0',
  Frei_Titel_1 varchar(100) NOT NULL default '',
  Frei_Text_1 text NOT NULL,
  Frei_Titel_2 varchar(100) NOT NULL default '',
  Frei_Text_2 text NOT NULL,
  Frei_Titel_3 varchar(100) NOT NULL default '',
  Frei_Text_3 text NOT NULL,
  Frei_Titel_4 varchar(100) NOT NULL default '',
  Frei_Text_4 text NOT NULL,
  Hersteller mediumint(3) default NULL,
  Schlagwoerter tinytext NOT NULL,
  Einheit decimal(7,2) NOT NULL default '0.00',
  EinheitId int(10) unsigned NOT NULL default '0',
  Lager int(10) unsigned NOT NULL default '1000',
  VersandZeitId smallint(2) unsigned NOT NULL default '1',
  Bestellungen int(10) unsigned NOT NULL default '0',
  DateiDownload varchar(255) NOT NULL default '',
  PRIMARY KEY  (Id),
  UNIQUE KEY ArtNr (ArtNr),
  KEY KatId (KatId),
  KEY ArtName (ArtName),
  KEY Hersteller (Hersteller),
  KEY Preis (Preis)
) TYPE=MyISAM PACK_KEYS=0 DEFAULT CHARSET=cp1251;";


$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_artikel_bilder (
  Id int(10) unsigned NOT NULL auto_increment,
  ArtId int(10) unsigned NOT NULL default '0',
  Bild varchar(255) NOT NULL default '',
  PRIMARY KEY  (Id)
) TYPE=MyISAM PACK_KEYS=0 DEFAULT CHARSET=cp1251;
";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_artikel_downloads (
  Id int(10) unsigned NOT NULL auto_increment,
  ArtId varchar(255) NOT NULL default '',
  Datei varchar(255) NOT NULL default '',
  DateiTyp enum('full','update','bugfix','other') NOT NULL default 'full',
  TageNachKauf mediumint(5) NOT NULL default '365',
  Bild varchar(255) NOT NULL default '',
  Titel varchar(200) NOT NULL default '',
  Beschreibung text NOT NULL,
  Position mediumint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";


$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_artikel_kommentare (
  Id int(8) unsigned NOT NULL auto_increment,
  ArtId int(10) unsigned NOT NULL default '0',
  Benutzer int(10) unsigned NOT NULL default '0',
  Datum int(14) unsigned NOT NULL default '0',
  Titel varchar(255) NOT NULL default '',
  Kommentar text NOT NULL,
  Wertung smallint(1) unsigned NOT NULL default '0',
  Publik tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_bestellungen (
  Id int(14) unsigned NOT NULL auto_increment,
  Benutzer varchar(255) NOT NULL default '',
  TransId varchar(100) NOT NULL default '',
  Datum int(14) unsigned NOT NULL default '0',
  Gesamt decimal(7,2) NOT NULL default '0.00',
  USt decimal(7,2) NOT NULL default '0.00',
  Artikel text NOT NULL,
  Artikel_Vars text NOT NULL,
  RechnungText text NOT NULL,
  RechnungHtml text NOT NULL,
  NachrichtBenutzer text NOT NULL,
  NachrichtAdmin text NOT NULL,
  Ip varchar(200) NOT NULL default '',
  ZahlungsId mediumint(5) unsigned NOT NULL default '0',
  VersandId mediumint(5) unsigned NOT NULL default '0',
  KamVon tinytext NOT NULL,
  Gutscheincode int(10) default NULL,
  Bestell_Email varchar(255) NOT NULL default '',
  Liefer_Firma varchar(100) NOT NULL default '',
  Liefer_Abteilung varchar(100) NOT NULL default '',
  Liefer_Vorname varchar(100) NOT NULL default '',
  Liefer_Nachname varchar(100) NOT NULL default '',
  Liefer_Strasse varchar(100) NOT NULL default '',
  Liefer_Hnr varchar(10) NOT NULL default '',
  Liefer_PLZ varchar(15) NOT NULL default '',
  Liefer_Ort varchar(100) NOT NULL default '',
  Liefer_Land char(2) NOT NULL default '',
  Rech_Firma varchar(100) NOT NULL default '',
  Rech_Abteilung varchar(100) NOT NULL default '',
  Rech_Vorname varchar(100) NOT NULL default '',
  Rech_Nachname varchar(100) NOT NULL default '',
  Rech_Strasse varchar(100) NOT NULL default '',
  Rech_Hnr varchar(10) NOT NULL default '',
  Rech_PLZ varchar(15) NOT NULL default '',
  Rech_Ort varchar(100) NOT NULL default '',
  Rech_Land char(2) NOT NULL default '',
  Status enum('wait','progress','ok','ok_send','failed') NOT NULL default 'wait',
  DatumBezahlt int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (Id)
) TYPE=MyISAM PACK_KEYS=0 DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_einheiten (
  Id int(10) unsigned NOT NULL auto_increment,
  Name varchar(100) NOT NULL default '',
  NameEinzahl varchar(255) NOT NULL default '',
  PRIMARY KEY  (Id),
  UNIQUE KEY Name (Name)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_gutscheine (
  Id int(10) unsigned NOT NULL auto_increment,
  Code varchar(100) NOT NULL default '',
  Prozent decimal(3,2) NOT NULL default '10.00',
  Mehrfach tinyint(1) unsigned NOT NULL default '1',
  Benutzer text NOT NULL,
  Eingeloest int(14) unsigned NOT NULL default '0',
  BestellId text NOT NULL,
  GueltigVon int(14) unsigned NOT NULL default '0',
  GueltigBis int(14) unsigned NOT NULL default '0',
  AlleBenutzer tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (Id),
  UNIQUE KEY Code (Code)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_hersteller (
  Id mediumint(5) unsigned NOT NULL auto_increment,
  Name varchar(255) NOT NULL default '',
  PRIMARY KEY  (Id),
  UNIQUE KEY Name (Name)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_kategorie (
  Id mediumint(5) unsigned NOT NULL auto_increment,
  Elter mediumint(5) unsigned NOT NULL default '0',
  KatName varchar(100) default NULL,
  KatBeschreibung tinytext NOT NULL,
  Rang smallint(3) unsigned NOT NULL default '1',
  Bild varchar(255) NOT NULL default '',
  PRIMARY KEY  (Id),
  KEY KatName (KatName)
) TYPE=MyISAM PACK_KEYS=0 DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_kundenrabatte (
  Id mediumint(3) unsigned NOT NULL auto_increment,
  GruppenId mediumint(3) unsigned NOT NULL default '0',
  Wert decimal(7,2) unsigned NOT NULL default '0.00',
  PRIMARY KEY  (Id),
  UNIQUE KEY GruppenId (GruppenId)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_merkliste (
  Id int(10) unsigned NOT NULL auto_increment,
  Benutzer int(10) unsigned NOT NULL default '0',
  Ip varchar(200) NOT NULL default '',
  Inhalt text NOT NULL,
  Inhalt_Vars text NOT NULL,
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_staffelpreise (
  Id int(10) unsigned NOT NULL auto_increment,
  ArtId int(10) unsigned NOT NULL default '0',
  StkVon mediumint(5) NOT NULL default '2',
  StkBis mediumint(5) NOT NULL default '5',
  Preis decimal(7,2) NOT NULL default '0.00',
  PRIMARY KEY  (Id),
  KEY ArtId (ArtId),
  KEY Preis (Preis)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_ust (
  Id smallint(3) unsigned NOT NULL auto_increment,
  Name varchar(100) NOT NULL default '',
  Wert decimal(4,2) NOT NULL default '16.00',
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_varianten (
  Id int(10) unsigned NOT NULL auto_increment,
  KatId int(10) unsigned NOT NULL default '0',
  ArtId int(10) unsigned NOT NULL default '0',
  Name varchar(255) NOT NULL default '',
  Wert decimal(7,2) NOT NULL default '0.00',
  Operant enum('+','-') NOT NULL default '+',
  Position smallint(2) unsigned NOT NULL default '1',
  Vorselektiert tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (Id),
  KEY KatId (KatId),
  KEY ArtId (ArtId)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_varianten_kategorien (
  Id int(10) unsigned NOT NULL auto_increment,
  KatId mediumint(5) unsigned NOT NULL default '0',
  Name varchar(200) NOT NULL default '',
  Beschreibung text NOT NULL,
  Aktiv tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (Id),
  KEY KatId (KatId)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_versandarten (
  Id int(10) unsigned NOT NULL auto_increment,
  Name varchar(200) NOT NULL default '',
  Beschreibung text NOT NULL,
  Icon varchar(255) NOT NULL default '',
  LaenderVersand tinytext NOT NULL,
  Pauschalkosten decimal(5,2) NOT NULL default '0.00',
  KeineKosten tinyint(1) unsigned NOT NULL default '0',
  Aktiv tinyint(1) unsigned NOT NULL default '1',
  NurBeiGewichtNull tinyint(1) unsigned NOT NULL default '0',
  ErlaubteGruppen tinytext NOT NULL,
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_versandkosten (
  Id int(10) unsigned NOT NULL auto_increment,
  VersandId int(10) unsigned NOT NULL default '0',
  Land char(2) NOT NULL default 'DE',
  KVon decimal(8,3) NOT NULL default '0.001',
  KBis decimal(8,3) NOT NULL default '10.000',
  Betrag decimal(7,2) NOT NULL default '0.00',
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_versandzeit (
  Id smallint(2) unsigned NOT NULL auto_increment,
  Name varchar(255) NOT NULL default '',
  Beschreibung text NOT NULL,
  Icon varchar(255) NOT NULL default '',
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "CREATE TABLE CPPREFIX_modul_shop_zahlungsmethoden (
  Id mediumint(5) unsigned NOT NULL auto_increment,
  Name varchar(255) NOT NULL default '',
  Beschreibung text NOT NULL,
  ErlaubteVersandLaender tinytext NOT NULL,
  ErlaubteVersandarten tinytext NOT NULL,
  ErlaubteGruppen tinytext NOT NULL,
  Aktiv tinyint(1) unsigned NOT NULL default '0',
  Kosten decimal(7,2) NOT NULL default '0.00',
  KostenOperant enum('Wert','%') NOT NULL default 'Wert',
  InstId varchar(100) NOT NULL default '',
  Modus int(10) unsigned default NULL,
  ZahlungsBetreff varchar(255) NOT NULL default '',
  TestModus varchar(10) NOT NULL default '',
  Extern tinyint(1) unsigned default NULL,
  Gateway varchar(100) default NULL,
  Position mediumint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (Id),
  UNIQUE KEY Name (Name)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop VALUES (1, 1, 'EUR', '&euro;', '', '', 0.0000, 'DE', 6, 0, 'DE,AT,CH', '<h2>AGB</h2>\r\n<br /><br />Allgemeine Gesch&auml;ftsbedingungen (AGB)', 1, 1000.00, '--------------------\r\ndream4\r\nPostfach 10 02 24\r\n69469 Weinheim\r\nwww.domain.de\r\n".$_SESSION["cp_email"]."\r\n--------------------', '<strong>dream4</strong><br />\r\nPostfach 10 02 24<br />\r\n69469 Weinheim<br />\r\nwww.domain.de<br />\r\n".$_SESSION["cp_email"]."<br />', '', 'html', '".$_SESSION["cp_email"]."', 'dream4 - Shop', '".$_SESSION["cp_email"]."', 'Bestellbestaetigung - dream4 - Shop', 1, 1, 1, 1, '<!--  -->', 0, 1, 1, 1, 1, '<div align=\"center\"> Alle Preise verstehen sich inkl. gesetzl. MwSt. zuz&uuml;glich eventuell entstehender Versandkosten und ggf. Nachnahmegeb&uuml;hren. <br /> <br /> <a href=\"index.php?module=shop&amp;action=infopage&amp;page=imprint\">Impressum</a> -  <a href=\"index.php?module=shop&amp;action=infopage&amp;page=shippinginf\">Versandkosten</a> -  <a href=\"index.php?module=shop&amp;action=infopage&amp;page=datainf\">Datenschutzinformationen</a> -  <a href=\"javascript:scroll(1,1)\">Nach Oben</a>  </div>', '<h2>Versandinformationen</h2>\r\n<br /><br />Die Versandkosten bei uns richten sich nach dem Gewicht der Ware, dem ausgew&auml;hlten Lieferservice und der Lieferadresse, wenn diese au&szlig;erhalb Deutschlands liegt. Aus diesem Grund k&ouml;nnen wir nicht explizit vorgeben, wie hoch die Versandkosten f&uuml;r die Lieferung sein werden. Zus&auml;tzlich fallen bei der Nachnahme-Bestellung weitere Geb&uuml;hren an.<br /><br />Die tats&auml;chlich anfallenden Kosten k&ouml;nnen Sie im Zweifelsfall ganz einfach mit Hilfe unseres Warenkorbs ermitteln. Legen Sie dazu die entsprechenden Produkte in den Warenkorb.<br /><br />Wenn Sie noch nicht eingeloggt sind, gelangen Sie &uuml;ber den  Link <strong>&gt;&gt;&nbsp;Zur Kasse gehen</strong><strong> </strong>zur Login-Seite. Hier k&ouml;nnen Sie sich wahlweise per bereits bestehender E-Mail Adresse und Kennwort anmelden oder den Vorgang ohne Login fortsetzen. In beiden F&auml;llen gelangen Sie zum Adressfeld. Nach der Eingabe bzw. &Uuml;berpr&uuml;fung der Daten gelangen Sie &uuml;ber <strong>&gt;&gt;&nbsp;Zur Kasse gehen</strong> zur Versandauswahl. W&auml;hlen Sie hier die von Ihnen gew&uuml;nschte Versandart aus, um die entsprechenden Versand- und Gesamtkosten angezeigt zu bekommen.', '<h2>Datenschutzinformationen</h2>\r\n<br /><br />Wir respektieren Ihre Privatsph&auml;re!<br /> Alle auf dieser Seite erhobenen Informationen werden ausschlie&szlig;lich vertraulich behandelt und nicht an Dritte weitergegeben!<br /> Ihre pers&ouml;nlichen Daten, die Sie uns &uuml;bermittelt haben, werden von uns mit gr&ouml;&szlig;ter Sorgfalt aufbewahrt und auf keinerlei Art und Weise verwendet, der Sie nicht ausdr&uuml;cklich zugestimmt haben. Sollten Sie irgendwelche Fragen zu dieser Datenschutzerkl&auml;rung haben, dann setzen Sie sich einfach mit uns in Verbindung.', '<h2>Impressum</h2>\r\n<br /><br />Speichern Sie hier Ihr Impressum...', 1, 1, 'shop_items.tpl', 80, 35, 1, 1, 0, 0);";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel VALUES (1, 'CP-10001', 28, '24,25,', '5 Canon PIXMA iP4200 Tintenstrahldrucker', 1, 344.20, 369.20, 'canon_demo.jpg', 'jpg', '', 'Tempo und Qualit&auml;t.. moderner Drucker mit fortschrittlichem Druckmedien-Handling. Fortschrittliche Technologie und attraktiver Look... der PIXMA iP4200 bedeutet Superqualit&auml;t zum Superpreis. Mit Tintentr&ouml;pfchen von bis zu 1 Schnelle professionelle Picoliter wird eine fast professionelle', '<strong>Tempo und Qualit&auml;t ? moderner Drucker mit fortschrittlichem Druckmedien-Handling.</strong><br /> Fortschrittliche Technologie und attraktiver Look ? der PIXMA iP4200 bedeutet Superqualit&auml;t zum Superpreis. Mit Tintentr&ouml;pfchen von bis zu 1 Picoliter wird eine fast professionelle Fotodruckqualit&auml;t erreicht. Und das mit enormer Geschwindigkeit.\\n\n<p>Mit einer Druckaufl&ouml;sung von 9.600 x 2.400 dpi erreichen der Canon FINE Druckkopf und ContrastPLUS mit zus&auml;tzlicher farbstoffbasierter Schwarztinte in hohem Tempo exzellente Druckresultate, die auch den Vergleich mit vielen Druckern mit 6-Tintensystem nicht scheuen m&uuml;ssen. Der PIXMA iP4200 ist das Angebot f&uuml;r Anwender, die Wert auf &uuml;berragende Qualit&auml;t und fortschrittliche Technologie legen.   </p>\n<p> <strong>Edel und vielseitig</strong><br />  Der PIXMA iP4200 ist ausgerichtet auf optimale Flexibilit&auml;t mit edlem, kubischen Design, minimalem Platzbedarf und fortschrittlichem Medienhandling. Beidseitiger Druck, zwei Papierzuf&uuml;hrungen und das Bedrucken geeigneter CDs und DVDs sind die logische Konsequenz dieses Systems mit Anspruch. </p>\n<p></p>', 2.000, 0, '', 1, 1148076000, 'Подробности', '&Uuml;berzeugend in Geschwindigkeit und Funktion.<br />  Der PIXMA iP4200 druckt brillante Fotoprints sehr schnell. Ein randloser 10 x 15 cm Fotoprint ist in ca. 51 Sekunden fertig. Die PictBridge Kompatibilit&auml;t erm&ouml;glicht den Fotodirektdruck auch ohne PC. Leistungsstarke Software demonstriert, dass der PIXMA iP4200 mehr ist als einfach nur ein Drucker: das kleine, pers&ouml;nliche Fotolabor.\n<p></p>\n<p> Mit zwei Papierzuf&uuml;hrungen, einer integrierten Duplexeinheit und der M&ouml;glichkeit zum Bedrucken geeigneter CDs und DVDs beweisen die neuen Pixma Modelle fortschrittliches Druckmedien-Handling. Weitere praktische Vorz&uuml;ge: die schnelle USB 2.0 Hi-Speed Schnittstelle und die wirtschaftliche Single Ink-Technologie f&uuml;r eine effiziente Nutzung der Tinten.</p>\n<p> ChromaLife100 steht f&uuml;r verbesserte Farbstabilit&auml;t und ist Canons neues System f&uuml;r die Zukunft. Bis zu einhundert Jahre im Fotoalbum, bis zu drei&szlig;ig Jahre im Bilderrahmen hinter Glas und bis zu zehn Jahre bei direkter Lufteinwirkung ? so lange bleiben Bilder in ihrer urspr&uuml;nglichen Pracht erhalten, wenn bestimmte Bedingungen erf&uuml;llt sind. Die richtige Kombination aus Drucker, Tinte und Papier spielt dabei eine entscheidende Rolle.</p>', '', '', '', '', '', '', 3, 'pixma,drucker', 5.00, 1, 998, 2, 13, '');";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel VALUES (3, 'CP-102000', 51, '24,31,50,', 'Fujitsu Siemens Scaleo Pa Desktop PC', 1, 968.00, 0.00, 'scaleo_demo.jpg', 'jpg', '', 'Mit dem brandneuen AMD Athlon? 64 X2 Dual-Core Prozessor 4200+ und der NVIDIA&reg; GeForce? 7300 GS Grafikkarte bringt er alle Multimedia-Funktionen perfekt in Einklang. Sie suchen die ultimative Bequemlichkeit? Sie finden sie beispielsweise in dem...', '<strong>Der neue Deutschland-PC ist da!</strong><br /> Mit dem brandneuen AMD Athlon? 64 X2 Dual-Core Prozessor 4200+ und der NVIDIA&reg; GeForce? 7300 GS Grafikkarte bringt er alle Multimedia-Funktionen perfekt in Einklang. Sie suchen die ultimative Bequemlichkeit? Sie finden sie beispielsweise in dem Multinorm DVD-Brenner mit Label Flash ? f&uuml;r die extra einfache Beschriftung Ihrer DVDs. Besonderes Augenmerk verdienen auch die zwei integrierten TV-Karten. Damit Fernsehspa&szlig; in jedem Fall ganz einfach wird ? ob per Satellit oder Antenne analog bzw. digital (DVB-T)\n<p></p>', 5.000, 0, '', 1, 1130277600, 'Характеристики', '<ul>\n    <li>Der neue Deutschland-PC ist da: Multimedia-Leistung mit Dual-Core Prozessor und nVIDIA GeForce 7300GS Grafik</li>\n    <li>inkl. DVD-Brenner, 2x 160 GB Festplatte, 11-in-1-Cardreader, Dual-TV-Tuner analog/DVB-T, DVB-S-Tuner und Fernbedienung</li>\n    <li>Lieferumfang: Desktop PC, Fernbedienung, Tastatur USB, Wheel Mouse USB, Handbuch, Sicherheits- und Garantieleitfaden</li>\n    <li>Software (vorinstalliert): Microsoft Windows XP Home, Works 8.0 + Office Trial, Symantec Security Suite (90 Tage), MAGIX Media Suite, Live for Speed light Online Racing Simulation, Nero, Cyberlink PowerCinema / PowerDVD, SipGate VoIP</li>\n    <li>Garantie: 24 Monate Garantie mit Vor-Ort-Service</li>\n    <br /><a href=\"/exec/obidos/tg/stores/detail/-/ce-de/B000EU88VE/tech-data/ref=ed_tec_dp_2_1/303-8340228-0984235\">Technische Informationen zu diesem Artikel</a><br /> </ul>', 'Модели', '<strong>Modellnummer:&nbsp;</strong>CUZ:P-GER-VARIO127<br /> <strong>ASIN:</strong> B000EU88VE <br /> <strong>Produktgewicht inkl. Verpackung:</strong> 17.00 Kg <br /> <strong>Im Angebot von Amazon.de seit:&nbsp;</strong>1. M&auml;rz 2006', '', '', '', '', 1, 'Scaleo,Protector', 0.00, 0, 998, 1, 1, '');";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel VALUES (4, 'CP-102001', 51, '50,', 'Fujitsu Siemens Scaleo PX Desktop PC (2)', 1, 928.00, 0.00, 'scaleo_demo.jpg', 'jpg', '', 'Mit dem brandneuen AMD Athlon? 64 X2 Dual-Core Prozessor 4200+ und der NVIDIA&reg; GeForce? 7300 GS Grafikkarte bringt er alle Multimedia-Funktionen perfekt in Einklang. Sie suchen die ultimative Bequemlichkeit? Sie finden sie beispielsweise in dem...', '<strong>Der neue Deutschland-PC ist da!</strong><br /> Mit dem brandneuen AMD Athlon? 64 X2 Dual-Core Prozessor 4200+ und der NVIDIA&reg; GeForce? 7300 GS Grafikkarte bringt er alle Multimedia-Funktionen perfekt in Einklang. Sie suchen die ultimative Bequemlichkeit? Sie finden sie beispielsweise in dem Multinorm DVD-Brenner mit Label Flash ? f&uuml;r die extra einfache Beschriftung Ihrer DVDs. Besonderes Augenmerk verdienen auch die zwei integrierten TV-Karten. Damit Fernsehspa&szlig; in jedem Fall ganz einfach wird ? ob per Satellit oder Antenne analog bzw. digital (DVB-T).\n<p></p>', 5.000, 0, '', 1, 1150322400, 'Характеристики', '<ul>\n    <li>Der neue Deutschland-PC ist da: Multimedia-Leistung mit Dual-Core Prozessor und nVIDIA GeForce 7300GS Grafik</li>\n    <li>inkl. DVD-Brenner, 2x 160 GB Festplatte, 11-in-1-Cardreader, Dual-TV-Tuner analog/DVB-T, DVB-S-Tuner und Fernbedienung</li>\n    <li>Lieferumfang: Desktop PC, Fernbedienung, Tastatur USB, Wheel Mouse USB, Handbuch, Sicherheits- und Garantieleitfaden</li>\n    <li>Software (vorinstalliert): Microsoft Windows XP Home, Works 8.0 + Office Trial, Symantec Security Suite (90 Tage), MAGIX Media Suite, Live for Speed light Online Racing Simulation, Nero, Cyberlink PowerCinema / PowerDVD, SipGate VoIP</li>\n    <li>Garantie: 24 Monate Garantie mit Vor-Ort-Service</li>\n    <br /><a href=\"/exec/obidos/tg/stores/detail/-/ce-de/B000EU88VE/tech-data/ref=ed_tec_dp_2_1/303-8340228-0984235\">Technische Informationen zu diesem Artikel</a><br /> </ul>', 'Модели', '<strong>Modellnummer:&nbsp;</strong>CUZ:P-GER-VARIO127<br /> <strong>ASIN:</strong> B000EU88VE <br /> <strong>Produktgewicht inkl. Verpackung:</strong> 17.00 Kg <br /> <strong>Im Angebot von Amazon.de seit:&nbsp;</strong>1. M&auml;rz 2006', '', '', '', '', 1, 'Scaleo', 0.00, 0, 997, 1, 5, '');";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel VALUES (5, 'CP-10055', 28, '24,25,', 'Canon PIXMA iP5200 Tintenstrahldrucker', 1, 94.00, 0.00, 'canon_demo.jpg', 'jpg', '', 'Herausragende Fotodruckqualit&auml;t und Tempo? Dieser Premium-Fotodrucker erf&uuml;llt die Anforderungen. Erfahrene Anwender werden die Vielseitigkeit des PIXMA iP5200 und die Qualit&auml;t beim Fotodruck sch&auml;tzen.', '<p><strong>Leistungsstarker Premium-Fotodrucker f&uuml;r den erfahrenen Anwender</strong><br />   Herausragende Fotodruckqualit&auml;t und Tempo? Dieser Premium-Fotodrucker  erf&uuml;llt die Anforderungen. Erfahrene Anwender werden die Vielseitigkeit  des PIXMA iP5200 und die Qualit&auml;t beim Fotodruck sch&auml;tzen. Das  innovative Design und der Anwender-Komfort &uuml;berzeugen zudem.  </p>\n<p>Au&szlig;ergew&ouml;hnlich  ist die Druckqualit&auml;t des PIXMA iP5200. Mit Pr&auml;zision platziert der  FINE Druckkopf Tintentr&ouml;pfchen von bis zu 1 Picoliter. ContrastPLUS  sorgt mit dem Einsatz einer zus&auml;tzlichen farbstoffbasierten  Schwarztinte f&uuml;r mehr Farbtiefe und Kontrast beim Fotodruck.</p>\n<p> <strong>Exzellente Qualit&auml;t, exzellente Geschwindigkeit.</strong> Der PIXMA iP5200 druckt Fotoprints in erstaunlicher Qualit&auml;t mit bis zu  1 Picoliter feinen Tintentr&ouml;pfchen und einer enormen  Druckgeschwindigkeit. Ein randloser Fotoprint ist in ca. 36 Sekunden  gedruckt. Der PictBridge Port erm&ouml;glicht den Fotodirektdruck auch ohne  PC. Das ist schon fast professionelle Fotolaborfunktionalit&auml;t.</p>', 2.000, 0, '', 1, 1148076000, 'Подробности', '<p> <strong>&Uuml;berzeugend in Stil und Vielseitigkeit.</strong><br />   Der edle Drucker hat eine schlanke Silhouette und ben&ouml;tigt nur wenig  Platz. Dennoch bietet er den vollen Komfort fortschrittlichen  Medienhandlings, wie das Bedrucken geeigneter CDs und DVDs, den  beidseitigen Druck und zwei Papierzuf&uuml;hrungen. Das ist Fotodruck im  Bestformat.</p>\n<p>Mit  zwei Papierzuf&uuml;hrungen, einer integrierten Duplexeinheit und der  M&ouml;glichkeit zum Bedrucken geeigneter CDs und DVDs beweisen die neuen  Pixma Modelle fortschrittliches Druckmedien-Handling. Weitere  praktische Vorz&uuml;ge: die schnelle USB 2.0 Hi-Speed Schnittstelle und die  wirtschaftliche Single Ink-Technologie f&uuml;r eine effiziente Nutzung der  Tinten. </p>\n<p> ChromaLife100 steht f&uuml;r verbesserte  Farbstabilit&auml;t und ist Canons neues System f&uuml;r die Zukunft. Bis zu  einhundert Jahre im Fotoalbum, bis zu drei&szlig;ig Jahre im Bilderrahmen  hinter Glas und bis zu zehn Jahre bei direkter Lufteinwirkung ? so  lange bleiben Bilder in ihrer urspr&uuml;nglichen Pracht erhalten, wenn  bestimmte Bedingungen erf&uuml;llt sind. Die richtige Kombination aus  Drucker, Tinte und Papier spielt dabei eine entscheidende Rolle.</p>', 'TEST', 'TEST', 'TEST 2', 'TEST 2...', 'TEST 3', 'TEST 3...', 3, 'pixma,drucker', 0.00, 0, 999, 1, 11, '');";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel VALUES (6, 'CP-10056', 28, '24,25,1,', 'Epson Stylus D88 Tintenstrahldrucker', 1, 71.97, 0.00, 'epson_demo.jpg', 'jpg', '', 'Die Epson Stylus D-Drucker erm&ouml;glichen die Erstellung hochwertiger Ausdrucke bei g&uuml;nstigen Folgekosten dank 4 Individual Ink-Patronen. Ideal f&uuml;r alle Druckanspr&uuml;che, einschlie&szlig;lich Fotos: Schnelle professionelle Ergebnisse f&uuml;r den gesamten privaten und', 'Die Epson Stylus D-Drucker erm&ouml;glichen die Erstellung hochwertiger Ausdrucke bei g&uuml;nstigen Folgekosten dank 4 Individual Ink-Patronen. Ideal f&uuml;r alle Druckanspr&uuml;che, einschlie&szlig;lich Fotos: Schnelle professionelle Ergebnisse f&uuml;r den gesamten privaten und', 2.000, 1, 'uploads/images/action1.gif', 1, 1148076000, '', '', '', '', '', '', '', '', 5, 'drucker', 0.00, 0, 999, 1, 12, '');";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel VALUES (7, 'CP_BUCH_0001', 4, '67,', 'Sakrileg. The Da Vinci Code', 1, 39.99, 49.99, 'sakrileg_demo.jpg', 'jpg', '', 'Bestsellerautor Dan Brown bietet mit Sakrileg erneut spannende und intelligente Unterhaltung vom Feinsten. Der Direktor des Louvre wird in seinem Museum vor einem Gem&auml;lde Leonardos ermordet aufgefunden, und der Symbolforscher Robert Langdon ger&auml;t ins Fadenkreuz der Polizei, war er doch mit dem Opfer just zur Tatzeit verabredet.  Eine Verschw&ouml;rung ist immer noch das Sch&ouml;nste. Stimmt, wenn sie schriftstellerisch so &uuml;berzeugend und raffiniert inszeniert ist, wie es dem Amerikaner Dan Brown in diesem Thriller gelingt. Genaue Recherchen an den Schaupl&auml;tzen und penible historische Studien in Zusammenarbeit mit seiner Frau Blythe, einer Kunsthistorikerin, machen das umfangreiche Werk nicht nur f&uuml;r Historiker und Religionswissenschaftler, sondern gerade auch f&uuml;r ein gro&szlig;es Publikum zu einem echten Vergn&uuml;gen.', 'Bestsellerautor Dan Brown bietet mit Sakrileg erneut spannende und intelligente Unterhaltung vom Feinsten. Der Direktor des Louvre wird in seinem Museum vor einem Gem&auml;lde Leonardos ermordet aufgefunden, und der Symbolforscher Robert Langdon ger&auml;t ins Fadenkreuz der Polizei, war er doch mit dem Opfer just zur Tatzeit verabredet.  Eine Verschw&ouml;rung ist immer noch das Sch&ouml;nste. Stimmt, wenn sie schriftstellerisch so &uuml;berzeugend und raffiniert inszeniert ist, wie es dem Amerikaner Dan Brown in diesem Thriller gelingt. Genaue Recherchen an den Schaupl&auml;tzen und penible historische Studien in Zusammenarbeit mit seiner Frau Blythe, einer Kunsthistorikerin, machen das umfangreiche Werk nicht nur f&uuml;r Historiker und Religionswissenschaftler, sondern gerade auch f&uuml;r ein gro&szlig;es Publikum zu einem echten Vergn&uuml;gen.', 0.500, 0, '', 2, 1148076000, '', '', '', '', '', '', '', '', 5, 'buch', 0.00, 0, 1000, 1, 0, '');";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel VALUES (8, 'CP_BUCH_0001_PDF', 4, '67,', 'PDF: Sakrileg. The Da Vinci Code', 1, 39.99, 49.99, 'sakrileg_demo.jpg', 'jpg', '', 'Bestsellerautor Dan Brown bietet mit Sakrileg erneut spannende und intelligente Unterhaltung vom...!!', 'Bestsellerautor Dan Brown bietet mit Sakrileg erneut spannende und intelligente Unterhaltung vom Feinsten. Der Direktor des Louvre wird in seinem Museum vor einem Gem&auml;lde Leonardos ermordet aufgefunden, und der Symbolforscher Robert Langdon ger&auml;t ins Fadenkreuz der Polizei, war er doch mit dem Opfer just zur Tatzeit verabredet. Eine Verschw&ouml;rung ist immer noch das Sch&ouml;nste. Stimmt, wenn sie schriftstellerisch so &uuml;berzeugend und raffiniert inszeniert ist, wie es dem Amerikaner Dan Brown in diesem Thriller gelingt. Genaue Recherchen an den Schaupl&auml;tzen und penible historische Studien in Zusammenarbeit mit seiner Frau Blythe, einer Kunsthistorikerin, machen das umfangreiche Werk nicht nur f&uuml;r Historiker und Religionswissenschaftler, sondern gerade auch f&uuml;r ein gro&szlig;es Publikum zu einem echten Vergn&uuml;gen.!!!', 0.000, 1, 'uploads/images/action2.gif', 2, 1150322400, '', '', '', '', '', '', '', '', 0, 'buch,sakrileg', 385.00, 4, 1000, 3, 0, '');";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel VALUES (13, 'DKJHHJ1234', 68, '69,', 'Digital Ixus 55', 1, 239.00, 319.00, 'ixxus.jpg', 'jpg', '', '<strong>Attraktiver Nachwuchs f&uuml;r Canons Erfolgsserie</strong><br /> Canon stellt in der f&uuml;nf Megapixelklasse die Digital Ixus 55 vor. Die Digital Ixus 55 kommt parallel zu ihrer &bdquo;gro&szlig;e Schwester&ldquo; Digital Ixus 50 in den Handel Die neue Kamera ist mit einem optischen 3-fach-Zoom und mit einem gro&szlig;en 2,5-Zoll- Display mit breitem Einblickwinkel zur bequemen Bildkontrolle ausger&uuml;stet. Neu sind hier die im Display einblendbaren Gitterlinien als Gestaltungshilfe sowie eine grafisch unterst&uuml;tzte Zeitzonen-Einstellung. Zur Bildwiedergabe drehen die beiden Ixus-Modelle die Aufnahmen je nach Kamerahaltung vollautomatisch in die richtige Position. Trotz des gro&szlig;en TFT- Displays verzichtet Canon bei beiden Modellen weder auf den bew&auml;hrten optischen Sucher noch auf die ergonomisch platzierten Bedienelemente. Die Digital Ixus 55 interpretiert das typische &quot;Box and circle&quot;-Styling der Reihe neu, mit einem vom Linsenring ausgehenden Wellendesign in silber-wei&szlig;em Palladium, aufgebracht auf Edelstahl. Das 3-fach-Zoomobjektiv bringt es auf eine Brennweite von 35-105 mm (&auml;quivalent zum Kleinbildformat).', '<strong>Attraktiver Nachwuchs f&uuml;r Canons Erfolgsserie</strong><br /> Canon stellt in der f&uuml;nf Megapixelklasse die Digital Ixus 55 vor. Die Digital Ixus 55 kommt parallel zu ihrer &bdquo;gro&szlig;e Schwester&ldquo; Digital Ixus 50 in den Handel Die neue Kamera ist mit einem optischen 3-fach-Zoom und mit einem gro&szlig;en 2,5-Zoll- Display mit breitem Einblickwinkel zur bequemen Bildkontrolle ausger&uuml;stet. Neu sind hier die im Display einblendbaren Gitterlinien als Gestaltungshilfe sowie eine grafisch unterst&uuml;tzte Zeitzonen-Einstellung. <br /><br />Zur Bildwiedergabe drehen die beiden Ixus-Modelle die Aufnahmen je nach Kamerahaltung vollautomatisch in die richtige Position. Trotz des gro&szlig;en TFT- Displays verzichtet Canon bei beiden Modellen weder auf den bew&auml;hrten optischen Sucher noch auf die ergonomisch platzierten Bedienelemente. <br /><br />Die Digital Ixus 55 interpretiert das typische &quot;Box and circle&quot;-Styling der Reihe neu, mit einem vom Linsenring ausgehenden Wellendesign in silber-wei&szlig;em Palladium, aufgebracht auf Edelstahl. Das 3-fach-Zoomobjektiv bringt es auf eine Brennweite von 35-105 mm (&auml;quivalent zum Kleinbildformat).', 0.600, 0, '', 1, 1151359200, 'Основное', '<ul>\n    <li>5,0 Megapixel CCD-Sensor</li>\n    <li>3fach Zoomobjektiv</li>\n    <li>2,5 Zoll TFT-Display mit DIGIC II Bildprozessor</li>\n    <li>Erweiterter Movie-Modus und neues User Interface</li>\n    <li>Lieferumfang: Digital Ixus 55 Kamera NB-4L-Lithium Ionen Akku, Akku-Ladeger&auml;t CB-2LVE, 16 MB SD-Speicherkarte, Audio/Video-Kabel, USB-Kabel, ArcSoft Photo Studio, Kamerasoftware ZoomBrowser, ImageBrowser f&uuml;r Windows usw.</li>\n</ul>', '', '', '', '', '', '', 3, 'ixxus,foto', 0.00, 0, 1000, 2, 12, '');";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel VALUES (14, '32esdfvfds', 68, '69,', 'Casio EXILIM EX-S600 Digitalkamera (6 Megapixel)', 1, 243.37, 389.00, 'casio1.jpg', 'jpg', '', 'Die neueste Digitalkamera aus dem Hause CASIO begeistert mit vielen Features und zahlreichen Innovationen. Erstmals ist in der EX-S600 eine Filmstabilisierungsfunktion eingebaut, die ein Verwackeln beim Aufzeichnen eines Movies elektronisch reduziert. Dar&uuml;ber hinaus lassen 6 Megapixel, 3fach optischer Zoom und Anti Shake DSP Fotografenherzen h&ouml;her schlagen.<strong> </strong>\\n\r\n<p>  Das One-Touch-Button-Bedienfeld aktiviert jede Funktion mit nur einer Hand. In ca. 1 Sekunde (Blitz aus, interner Speicher) ist die kreditkartengro&szlig;e Kamera eingeschaltet - immer bereit, den perfekten Schnappschuss festzuhalten oder einen Movie zu drehen. Per Direct Movie Button schaltet die Kamera blitzschnell in den Moviemodus. Die Komprimierung im MPEG4 Format mit einer Gesamtfilmdauer von bis zu einer Stunde bei Verwendung einer 1 Gigabyte Speicherkarte sorgt f&uuml;r extralanges Filmvergn&uuml;gen. Ganz egal wann und wo: Die EX-S600 ist stets griffbereit und speichert jeden Moment. <br /> <br /> Ausdauernd dank Super Life Battery - die Kombination aus schneller EXILIM Engine mit deutlich reduziertem Energieverbrauch und leistungsstarkem Lithium-Ionen-Akku erm&ouml;glicht bis zu 300 Aufnahmen (nach CIPA-Standard) mit nur einer Akkuladung. Das entspricht bei Analogkameras mehr als acht Filmen &agrave; 36 Bildern. H&auml;ufiges Aufladen geh&ouml;rt somit der Vergangenheit an. </p>', 'Die neueste Digitalkamera aus dem Hause CASIO begeistert mit vielen Features und zahlreichen Innovationen. Erstmals ist in der EX-S600 eine Filmstabilisierungsfunktion eingebaut, die ein Verwackeln beim Aufzeichnen eines Movies elektronisch reduziert. Dar&uuml;ber hinaus lassen 6 Megapixel, 3fach optischer Zoom und Anti Shake DSP Fotografenherzen h&ouml;her schlagen.<strong> </strong>\\n\r\n<p>  Das One-Touch-Button-Bedienfeld aktiviert jede Funktion mit nur einer Hand. In ca. 1 Sekunde (Blitz aus, interner Speicher) ist die kreditkartengro&szlig;e Kamera eingeschaltet - immer bereit, den perfekten Schnappschuss festzuhalten oder einen Movie zu drehen. Per Direct Movie Button schaltet die Kamera blitzschnell in den Moviemodus. Die Komprimierung im MPEG4 Format mit einer Gesamtfilmdauer von bis zu einer Stunde bei Verwendung einer 1 Gigabyte Speicherkarte sorgt f&uuml;r extralanges Filmvergn&uuml;gen. Ganz egal wann und wo: Die EX-S600 ist stets griffbereit und speichert jeden Moment. <br /> <br /> Ausdauernd dank Super Life Battery - die Kombination aus schneller EXILIM Engine mit deutlich reduziertem Energieverbrauch und leistungsstarkem Lithium-Ionen-Akku erm&ouml;glicht bis zu 300 Aufnahmen (nach CIPA-Standard) mit nur einer Akkuladung. Das entspricht bei Analogkameras mehr als acht Filmen &agrave; 36 Bildern. H&auml;ufiges Aufladen geh&ouml;rt somit der Vergangenheit an. </p>', 0.120, 0, '', 1, 1151359200, 'Характеристики', '<ul>\r\n    <li>Aufl&ouml;sung (Pixel): 2.816 x 2.112&nbsp;</li>\r\n    <li>Sensor Chip: 1/2,5&quot; CCD&nbsp;</li>\r\n    <li>Chip Aufl&ouml;sung (Mio. Pixel): 6.000.000&nbsp;</li>\r\n    <li>Anschluss f&uuml;r Netzger&auml;t: Ja&nbsp;</li>\r\n    <li>Video Ausgang: Ja&nbsp;</li>\r\n    <li>USB-Schnittstelle: Ja&nbsp;</li>\r\n    <li>Interner Speicher: 8 MB&nbsp;</li>\r\n    <li>Wechselspeicher Art: SD-Card&nbsp;</li>\r\n    <li>Brennweite (entspr. 35-mm-KB): 3x Zoom 38 - 114 mm, 4x Digitalzoom&nbsp;</li>\r\n    <li>Sch&auml;rfebereich (cm): 40 - &nbsp;</li>\r\n    <li>Nahaufnahme / Makro (cm): 15 - 50&nbsp;</li>\r\n    <li>Lichtempfindlichkeit (ISO): Auto, 50/100/200/400&nbsp;</li>\r\n    <li>Blenden: F2,7 / F4,3&nbsp;</li>\r\n    <li>Manuelle Belichtungskorrektur: +/- 2 in Stufen von 1/3 &nbsp;</li>\r\n    <li>Blitzger&auml;t eingebaut: Ja&nbsp;</li>\r\n    <li>Selbstausl&ouml;ser: Ja&nbsp;</li>\r\n    <li>PictBridge (Direktdruck ohne PC): Ja&nbsp;</li>\r\n    <li>LCD-Bildschirm: Ja&nbsp;</li>\r\n    <li>Gr&ouml;&szlig;e (cm): 5,6&nbsp;</li>\r\n    <li>Windows 98 SE: Ja&nbsp;</li>\r\n    <li>Windows XP: Ja&nbsp;</li>\r\n    <li>Windows 2000: Ja&nbsp;</li>\r\n    <li>Windows ME: Ja&nbsp;</li>\r\n    <li>Abmessungen mm (BxHxT): 90 x 59 x 16,1&nbsp;</li>\r\n    <li>Gewicht (g): 115</li>\r\n</ul>', 'Основное', '<ul>\r\n    <li>6 Megapixel CCD f&uuml;r Bilder mit hoher Aufl&ouml;sung</li>\r\n    <li>Ultraflache Digitalkamera mit einziehbarem 3fach optischer Zoom.</li>\r\n    <li>Bis zu 300 Fotos pro Akkuladung mit der Super Life Battery</li>\r\n    <li>Anti Shake DSP</li>\r\n    <li>Lieferumfang: EXILIM EX-S600, USB-Dockingstation (CA-30), AC Adapter f&uuml;r Dockingstation, USB-Kabel, AV-Kabel, Handtrageriemen, wiederaufladbarer Lithium-Ionen-Akku (NP-20), CD-ROMs (2)</li>\r\n</ul>', '', '', '', '', 0, '', 0.00, 0, 1000, 2, 15, '');";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel_bilder VALUES ('', 13, 'i3.jpg');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel_bilder VALUES ('', 13, 'i2.jpg');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel_bilder VALUES ('', 14, 'casio2.jpg');";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel_downloads VALUES (1, '8', 'Depeche Mode - Playing The Angel - 02 - Precious.mp3', 'full', 100, '', 'Depeche-Mode Lied', '<b>Ha!</b>\r\n<br />\r\nDownloaden Sie... ''&Ouml;DE&Ouml;''', 2);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel_downloads VALUES (10, '8', 'Depeche Mode - 08 - Somebody.mp3', 'full', 100, '', 'Somebody...', 'Sch&ouml;nes Lied!', 1);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel_downloads VALUES (11, '8', 'Depeche Mode - Playing The Angel - 02 - Precious.mp3', 'update', 50, '', 'Update-Datei 2', 'Nix Beschreibung...', 2);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel_downloads VALUES (21, '8', 'rng_2006744.pdf', 'full', 365, '', 'PDF - TEST', '', 10);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel_downloads VALUES (20, '4', 'LIESMICH.txt', 'other', 900, '', 'Anleitung', '', 1);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel_downloads VALUES (16, '8', 'Depeche Mode - Playing The Angel - 02 - Precious.mp3', 'update', 365, '', 'Update-Datei 1', '', 1);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel_downloads VALUES (17, '8', 'TestDatei.rar', 'bugfix', 700, '', 'Bugfix 1', 'Keine Beschreibung', 1);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel_downloads VALUES (18, '8', 'LIESMICH.txt', 'other', 365, '', 'Liesmich', '', 1);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel_downloads VALUES (19, '8', 'LIESMICH.txt', 'other', 365, '', 'Liesmich (2)', '', 1);";


$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel_kommentare VALUES (1, 13, 1, 1151408935, 'Tolle Kamera und echt guter Preis!!!', 'Ich kann diese Kamera nur empfehlen!\r\nNach langem hin- & her habe ich mich f&uuml;r diese Kamera entschieden, da Sie einfach die besten Features hat.\r\n    *  5,0 Megapixel CCD-Sensor\r\n    * 3fach Zoomobjektiv\r\n    * 2,5 Zoll TFT-Display mit DIGIC II Bildprozessor\r\n    * Erweiterter Movie-Modus und neues User Interface\r\n    * Lieferumfang: Digital Ixus 55 Kamera NB-4L-Lithium Ionen Akku, Akku-Ladeger&auml;t CB-2LVE, 16 MB SD-Speicherkarte, Audio/Video-Kabel, USB-Kabel, ArcSoft Photo Studio, Kamerasoftware ZoomBrowser, ImageBrowser f&uuml;r Windows usw.\r\n', 4, 1);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel_kommentare VALUES (11, 14, 1, 1151416550, 'WOW!', 'Super Teil...', 4, 1);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel_kommentare VALUES (14, 4, 1, 1151416859, 'Lieber nicht...', 'ich weiЯ auch nicht... :)', 1, 1);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel_kommentare VALUES (20, 1, 1, 1151564994, 'Ja ja ja...', 'So ist das!', 2, 0);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_artikel_kommentare VALUES (21, 14, 2, 1151416550, 'WOW!', 'Super Teil...', 4, 0);";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_einheiten VALUES (1, 'шт.', 'шт.');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_einheiten VALUES (2, 'Литров', 'Литр');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_einheiten VALUES (4, 'Seiten', 'Seite');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_einheiten VALUES (6, 'Palette', 'Palette');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_einheiten VALUES (7, 'Листов', 'Лист');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_einheiten VALUES (8, 'Пакетов', 'Пакет');";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_gutscheine VALUES (1, 'dream4', 10.00, 0, ',12,1', 0, ',5,6,7,8,9,12', 1072911600, 2051305199, 0);";


$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_hersteller VALUES (1, 'Fujitsu Siemens');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_hersteller VALUES (2, 'dream4 - software');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_hersteller VALUES (3, 'Canon');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_hersteller VALUES (4, 'HP');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_hersteller VALUES (5, 'Epson');";


$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (1, 0, 'Software', '', 5, '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (2, 1, 'Koobi', '', 1, '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (3, 1, 'Sicherheit & Datenschutz', '', 2, '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (4, 0, 'B&uuml;cher', '', 2, '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (24, 0, 'Hardware', 'Eine Tolle Kategorie!', 1, '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (25, 24, 'Drucker', '', 1, 'drucker.gif');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (26, 24, 'Grafikkarten', '', 2, 'grafikkarten.gif');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (27, 24, 'Speicher', '', 3, 'speicher.gif');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (28, 25, 'Tintenstrahldrucker', '', 1, '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (29, 25, 'Laserdrucker', '', 2, '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (30, 25, 'Fotodrucker', '', 3, '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (31, 25, 'Sonstige Drucker', '', 4, '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (50, 0, 'Computer', '', 4, '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (51, 50, 'Desktop PCs', '', 1, '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (52, 50, 'Notebooks', '', 1, '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (53, 50, 'TFT-Monitore', '', 1, '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (54, 50, 'Drucker', '', 1, '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (61, 53, 'TFT-Monitore 17 Zoll', '', 1, '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (67, 4, 'Romane', '', 1, '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (68, 0, 'Kamera & Foto', '', 1, '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (69, 68, 'Digitalkameras', '', 1, '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kategorie VALUES (70, 68, 'Camcorder', '', 2, '');";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kundenrabatte VALUES (1, 1, 10.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kundenrabatte VALUES (2, 2, 0.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kundenrabatte VALUES (3, 3, 0.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_kundenrabatte VALUES (4, 4, 0.00);";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_staffelpreise VALUES (1, 1, 2, 5, 324.50);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_staffelpreise VALUES (2, 1, 6, 10, 304.50);";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_ust VALUES (1, 'Normal (Deutschland)', 16.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_ust VALUES (2, 'B&uuml;cher (Deutschland)', 7.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_ust VALUES (3, 'Steuerfrei', 0.00);";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_varianten VALUES ('', 1, 1, 'TFT Monitor', 699.99, '+', 1, 0);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_varianten VALUES ('', 1, 1, 'DVD - Brenner', 59.99, '+', 3, 0);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_varianten VALUES ('', 2, 1, '1 Jahr', 59.99, '+', 1, 0);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_varianten VALUES ('', 2, 1, '2 Jahre', 159.99, '+', 2, 0);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_varianten VALUES ('', 3, 3, 'Norton Antivirus', 59.99, '+', 1, 0);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_varianten VALUES ('', 3, 4, 'Norton', 100.00, '+', 1, 0);";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_varianten_kategorien VALUES (1, 28, 'Bundle', 'Bundle - Angebot', 1);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_varianten_kategorien VALUES (2, 28, 'Support', 'W&auml;hlen Sie hier die Support - Zeit', 1);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_varianten_kategorien VALUES (3, 51, 'Antivirus', 'W&auml;hlen Sie eine Software', 1);";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandarten VALUES (1, 'DHL', 'Der Versand erfolgt durch DHL', '', 'DE,AT,CH', 0.00, 0, 1, 0, '1,4,2,3');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandarten VALUES (2, 'DHL Express-Service', 'DHL Schnell Lieferung', '', 'DE', 0.00, 0, 1, 0, '1,2,3,4,5');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandarten VALUES (3, 'Download', 'Sie erhalten die Ware elektronisch per E-Mail bzw. per Download in unserem Kundenbereich', '', 'DE,AT,CH', 0.00, 1, 1, 1, '1,4,3');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandarten VALUES (4, 'Download & CD - Versand', 'Sie erhalten die Ware elektronisch per E-Mail bzw. per Download in unserem Kundenbereich', '', 'DE', 0.00, 0, 1, 1, '1,4,2,3');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandarten VALUES (5, 'Abholung', 'Sie zahlen bei Abholung in unserem Ladenlokal', '', 'DE', 0.00, 1, 0, 0, '1,4,2,3');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandarten VALUES (6, 'Eigen 1', 'Bitte Beschreibung angeben', '', 'DE,AT,CH', 0.00, 0, 0, 0, '1,2,3,4,5');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandarten VALUES (7, 'Eigen 2', 'Bitte Beschreibung angeben', '', 'DE,AT,CH', 0.00, 0, 0, 0, '1,2,3,4,5');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandarten VALUES (8, 'Eigen 3', 'Bitte Beschreibung angeben', '', 'DE,AT,CH', 0.00, 0, 0, 0, '1,2,3,4,5');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandarten VALUES (9, 'Eigen 4', 'Bitte Beschreibung angeben', '', 'DE,AT,CH', 0.00, 0, 0, 0, '1,2,3,4,5');";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (1, 1, 'DE', 0.001, 5.000, 7.50);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (2, 1, 'DE', 5.001, 10.000, 10.50);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (3, 1, 'DE', 10.001, 20.000, 14.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (4, 1, 'DE', 20.001, 25.000, 21.50);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (5, 1, 'DE', 25.001, 30.000, 25.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (6, 1, 'DE', 30.001, 40.000, 28.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (7, 1, 'DE', 40.001, 50.000, 38.50);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (8, 1, 'DE', 50.001, 60.000, 42.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (9, 1, 'DE', 70.001, 80.000, 50.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (10, 1, 'DE', 80.001, 90.000, 57.50);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (11, 2, 'DE', 0.001, 5.000, 19.50);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (12, 2, 'DE', 5.001, 10.000, 28.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (13, 2, 'DE', 10.001, 20.000, 26.50);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (14, 2, 'DE', 20.001, 25.000, 34.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (15, 2, 'DE', 25.001, 30.000, 37.50);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (16, 2, 'DE', 30.001, 40.000, 40.50);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (17, 2, 'DE', 40.001, 50.000, 51.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (18, 2, 'DE', 50.001, 60.000, 54.50);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (19, 2, 'DE', 70.001, 80.000, 62.50);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (20, 2, 'DE', 80.001, 90.000, 70.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (21, 1, 'AT', 0.001, 5.000, 17.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (22, 1, 'AT', 5.001, 10.000, 22.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (23, 1, 'AT', 10.001, 20.000, 32.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (24, 1, 'AT', 20.001, 25.000, 42.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (25, 1, 'AT', 25.001, 30.000, 50.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (26, 1, 'AT', 30.001, 40.000, 64.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (27, 1, 'AT', 40.001, 50.000, 74.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (28, 1, 'AT', 50.001, 60.000, 85.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (29, 1, 'AT', 70.001, 80.000, 100.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (30, 1, 'AT', 80.001, 90.000, 100.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (31, 1, 'CH', 0.001, 5.000, 17.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (32, 1, 'CH', 5.001, 10.000, 22.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (33, 1, 'CH', 10.001, 20.000, 32.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (34, 1, 'CH', 20.001, 25.000, 42.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (35, 1, 'CH', 25.001, 30.000, 50.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (36, 1, 'CH', 30.001, 40.000, 64.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (37, 1, 'CH', 40.001, 50.000, 74.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (38, 1, 'CH', 50.001, 60.000, 85.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (39, 1, 'CH', 70.001, 80.000, 100.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (40, 1, 'CH', 80.001, 90.000, 100.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (41, 2, 'DE', 90.001, 5000.000, 499.99);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (42, 2, 'DE', 5000.001, 10000.000, 500.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (43, 1, 'DE', 90.001, 400.000, 99.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (46, 1, 'DE', 400.001, 100000.000, 299.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (47, 1, 'AT', 0.000, 0.000, 0.00);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandkosten VALUES (48, 1, 'CH', 0.000, 0.000, 0.00);";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_zahlungsmethoden VALUES (1, 'Vorkasse', 'Wenn Sie sich bei Ihrer  Bestellung f&uuml;r die Zahlart \"Vorkasse\" entscheiden, bekommen Sie von uns  nach dem Absenden Ihrer Bestellung eine Email mit allen wichtigen  Informationen, beispielsweise der Bankverbindung und dem exakten  Betrag. Diese Informationen finden Sie zus&auml;tzlich als Druckversion in  unserem Online-Shop - zum Anschauen oder Ausdrucken. Wenn das Geld  Ihrer &Uuml;berweisung bei uns eingegangen ist, k&ouml;nnen Sie sich &uuml;ber das  baldige Eintreffen Ihrer Ware freuen. <br>\r\nBitte beachten Sie bei Vorkasseauftr&auml;gen, dass eine  Bank&uuml;berweisung in der Regel 2-3 Werktage dauert. Dementsprechend  verz&ouml;gert sich die Auslieferung der Produkte. Achtung! Um einen  reibungslosen und schnellen Ablauf gew&auml;hrleisten zu k&ouml;nnen, bitten wir  Sie, einige Punkte zu beachten:\r\n<ul>\r\n  <li>Bitte geben Sie den genauen Rechnungsbetrag an. Wenn Sie auf- oder  abrunden, kann Ihr Zahlungseingang nicht sofort zugeordnet werden und  die Auslieferung verz&ouml;gert sich gegebenenfalls.</li>\r\n  <li>Bitte &uuml;berweisen Sie den Rechnungsbetrag in einer Summe. Wenn Sie  verschiedene &Uuml;berweisungen mit Teilbetr&auml;gen ausf&uuml;hren, kann eine  Zuordnung nur verz&ouml;gert oder gar nicht erfolgen.</li>\r\n  <li>Bitte f&uuml;llen Sie pro Auftrag nur einen &Uuml;berweisungstr&auml;ger aus.  Andernfalls kann der Rechnungsbetrag nicht eindeutig zugeordnet werden,  was zu einer Verz&ouml;gerung der Lieferung f&uuml;hren kann.</li>\r\n</ul>\r\n<br>\r\n<strong>Bankverbindung</strong> <br>\r\nVolksbank Weinheim<br>\r\nKonto Nr: 0002892804<br>\r\nBLZ: 67092300<br>\r\nKnt.Inh. dream4 <br>\r\nVerwendungszweck: Bestellnummer (Zu finden in der Bestellbest&auml;tigung) <br>\r\n<strong><br>\r\nNur f&uuml;r &Uuml;berweisungen aus dem Ausland </strong><br>\r\nswift: genode61wnm<br>\r\niban: de72670923000002892804<br>\r\n<br>\r\nHinweis: Sollte binnen 7 Werktagen kein Zahlungseingang auf unserem  Konto festzustellen sein, gehen wir davon aus, dass kein Geldeingang  mehr erfolgen wird und bitten um Verst&auml;ndnis, dass wir den an uns  gerichteten Bestellwunsch stornieren werden.', 'DE,AT,CH', '1,2,3,4', '1,2,3,4', 1, 0.00, 'Wert', '', NULL, '', '', NULL, NULL, 3);";

$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_zahlungsmethoden VALUES (2, 'Nachnahme', 'Sie erhalten die Sendung direkt nach Hause zugestellt. Die Ware m&uuml;ssen Sie direkt beim Zusteller beazahlen. Bitte beachten Sie, dass Sie 2,00 ? Geb&uuml;hr bereitlegen m&uuml;ssen.', 'DE', '1', '1,2', 1, 4.00, 'Wert', '', NULL, '', '', NULL, NULL, 4);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_zahlungsmethoden VALUES (3, 'Rechnung', 'Bezahlen SIe bequem per Rechnung innerhalb von 10 Tagen', 'DE', '1,2,3,4', '1', 1, 0.00, 'Wert', '', NULL, '', '', NULL, NULL, 5);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_zahlungsmethoden VALUES (4, 'PayPal', 'Bezahlen Sie bequem &uuml;ber Ihr Paypalkonto.<br /> Weiter Informationen finden Sie unter <a target=\"_blank\" href=\"http://www.paypal.de/\">Paypal.de</a>.', 'DE,AT,CH', '1,2,3,4', '1,4,2,3', 1, 0.00, 'Wert', 'IHRE PAYPAL E-MAIL ADRESSE', 0, 'Bestellung', '', 1, 'paypal', 1);";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_zahlungsmethoden VALUES (5, 'Kreditkarte', 'Bezahlen Sie Ihre gew&uuml;nschte Ware bequem per Kreditkarte.<br>\r\nAls Zahlungsgateway haben wir uns vertrauensvoll f&uuml;r <a target=\"_blank\" href=\"http://www.worldpay.de\">Worldpay</a> entschieden.\r\n<br />\r\n<br />\r\n<b>WorldPay</b> ist einer der weltweit f&uuml;hrenden Anbieter von ePayment-L&ouml;sungen. \r\n<br />\r\nWorldPay ist ein Tochterunternehmen der Royal Bank of Scotland.', 'DE,AT,CH', '1,2,3,4', '1,2,3,4', 1, 0.00, 'Wert', 'IHRE WORLDPAY INSTALLATIONS-ID', 0, 'Bestellung', '0', 1, 'worldpay', 2);";


$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandzeit VALUES (1, 'Gew&ouml;hnlich versandfertig in 24 Stunden', '', '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandzeit VALUES (2, 'Gew&ouml;hnlich versandfertig in 3-5 Tagen', '', '');";
$modul_sql_install[] = "INSERT INTO CPPREFIX_modul_shop_versandzeit VALUES (3, 'Diesen Artikel k&ouml;nnen Sie downloaden', '', '');";

$modul_sql_install[] = "ALTER TABLE `CPPREFIX_modul_shop_artikel` ADD `PosiStartseite` SMALLINT(2) UNSIGNED DEFAULT '1' NOT NULL ;";

$modul_sql_install[] = "ALTER TABLE `CPPREFIX_modul_shop_gutscheine` CHANGE `Prozent` `Prozent` DECIMAL( 4, 2 ) DEFAULT '10.00';";
$modul_sql_update[]  = "ALTER TABLE `CPPREFIX_modul_shop_gutscheine` CHANGE `Prozent` `Prozent` DECIMAL( 4, 2 ) DEFAULT '10.00';";

$modul_sql_install[] = "ALTER TABLE `CPPREFIX_modul_shop_artikel` CHANGE `Preis` `Preis` DECIMAL( 10, 2 ) DEFAULT '0.00';";
$modul_sql_update[]  = "ALTER TABLE `CPPREFIX_modul_shop_artikel` CHANGE `Preis` `Preis` DECIMAL( 10, 2 ) DEFAULT '0.00';";

$modul_sql_install[] = "ALTER TABLE `CPPREFIX_modul_shop_artikel` CHANGE `PreisListe` `PreisListe` DECIMAL( 10, 2 ) DEFAULT '0.00';";
$modul_sql_update[]  = "ALTER TABLE `CPPREFIX_modul_shop_artikel` CHANGE `PreisListe` `PreisListe` DECIMAL( 10, 2 ) DEFAULT '0.00';";

$modul_sql_install[] = "ALTER TABLE `CPPREFIX_modul_shop` ADD `ShopKeywords` VARCHAR(255);";
$modul_sql_update[]  = "ALTER TABLE `CPPREFIX_modul_shop` ADD `ShopKeywords` VARCHAR(255);";

$modul_sql_install[] = "ALTER TABLE `CPPREFIX_modul_shop` ADD `ShopDescription` VARCHAR(255);";
$modul_sql_update[]  = "ALTER TABLE `CPPREFIX_modul_shop` ADD `ShopDescription` VARCHAR(255);";

$modul_sql_install[] = "ALTER TABLE `CPPREFIX_modul_shop_artikel` ADD `ProdKeywords` VARCHAR(255);";
$modul_sql_update[]  = "ALTER TABLE `CPPREFIX_modul_shop_artikel` ADD `ProdKeywords` VARCHAR(255);";

$modul_sql_install[] = "ALTER TABLE `CPPREFIX_modul_shop_artikel` ADD `ProdDescription` VARCHAR(255);";
$modul_sql_update[]  = "ALTER TABLE `CPPREFIX_modul_shop_artikel` ADD `ProdDescription` VARCHAR(255);";
?>