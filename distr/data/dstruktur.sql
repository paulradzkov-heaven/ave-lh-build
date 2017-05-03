CREATE TABLE %%PRFX%%_queries (
  Id int(11) unsigned NOT NULL auto_increment,
  RubrikId int(11) unsigned default NULL,
  Zahl int(11) default NULL,
  Titel varchar(255) default NULL,
  Template text,
  AbGeruest text,
  Sortierung varchar(255) default NULL,
  Autor mediumint(5) unsigned NOT NULL default '1',
  Erstellt int(14) unsigned NOT NULL default '0',
  Beschreibung tinytext NOT NULL,
  AscDesc enum('ASC','DESC') NOT NULL default 'DESC',
  Navi tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (Id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;#inst#


CREATE TABLE %%PRFX%%_queries_conditions (
  Id mediumint(5) unsigned NOT NULL auto_increment,
  Abfrage mediumint(5) unsigned default NULL,
  Operator varchar(30) default NULL,
  Feld mediumint(5) default NULL,
  Wert varchar(255) default NULL,
  Oper varchar(5) NOT NULL default 'OR',
  PRIMARY KEY  (Id)
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#


CREATE TABLE %%PRFX%%_antispam (
  Id bigint(15) unsigned NOT NULL auto_increment,
  Code varchar(25) NOT NULL default '',
  Ctime int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (Id),
  UNIQUE KEY Code (Code)
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#



CREATE TABLE %%PRFX%%_users (
  Id int(10) unsigned NOT NULL auto_increment,
  Kennwort varchar(50) NOT NULL default '',
  Email varchar(255) NOT NULL default '',
  Strasse varchar(255) NOT NULL default '',
  HausNr varchar(10) NOT NULL default '',
  Postleitzahl varchar(15) NOT NULL default '',
  Ort varchar(255) NOT NULL default '',
  Telefon varchar(35) NOT NULL default '',
  Telefax varchar(35) NOT NULL default '',
  Bemerkungen text NOT NULL,
  Vorname varchar(255) NOT NULL default '',
  Nachname varchar(255) NOT NULL default '',
  UserName varchar(255) NOT NULL default '',
  Benutzergruppe smallint(3) unsigned NOT NULL default '2',
  BenutzergruppeMisc varchar(255) NOT NULL default '',
  Registriert int(15) unsigned NOT NULL default '0',
  Status tinyint(1) NOT NULL default '1',
  ZuletztGesehen int(14) unsigned NOT NULL default '0',
  Land char(2) NOT NULL default 'ru',
  GebTag varchar(10) NOT NULL default '',
  Geloescht tinyint(1) unsigned NOT NULL default '0',
  GeloeschtDatum int(10) unsigned NOT NULL default '0',
  emc varchar(32) NOT NULL default '0',
  IpReg varchar(20) NOT NULL default '0',
  KennTemp varchar(50) NOT NULL default '0',
  Firma varchar(255) NOT NULL default '',
  UStPflichtig tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (Id)
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#


CREATE TABLE %%PRFX%%_user_groups (
  Benutzergruppe smallint(3) unsigned NOT NULL auto_increment,
  Name varchar(150) NOT NULL default '',
  Aktiv tinyint(1) unsigned NOT NULL default '1',
  set_default_avatar tinyint(1) unsigned NOT NULL default '0',
  default_avatar varchar(200) NOT NULL default '',
  PRIMARY KEY  (Benutzergruppe)
) TYPE=MyISAM PACK_KEYS=0  DEFAULT CHARSET=cp1251;#inst#


CREATE TABLE %%PRFX%%_user_permissions (
  Id smallint(3) NOT NULL auto_increment,
  BenutzerGruppe smallint(3) NOT NULL default '0',
  Rechte TEXT NOT NULL,
  PRIMARY KEY  (Id),
  KEY BenutzerGruppe (BenutzerGruppe)
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#



CREATE TABLE %%PRFX%%_documents (
  Id int(10) unsigned NOT NULL auto_increment,
  RubrikId mediumint(5) unsigned NOT NULL default '0',
  Url varchar(255) NOT NULL default '',
  Titel varchar(255) NOT NULL default '',
  DokTyp varchar(20) NOT NULL default '',
  DokStart int(10) unsigned NOT NULL default '0',
  DokEnde int(10) unsigned NOT NULL default '0',
  DokEdi int(10) unsigned NOT NULL default '0',
  Redakteur mediumint(5) unsigned NOT NULL default '1',
  Suche tinyint(1) unsigned NOT NULL default '1',
  MetaKeywords tinytext NOT NULL,
  MetaDescription tinytext NOT NULL,
  IndexFollow enum('index,follow','index,nofollow','noindex,nofollow') NOT NULL default 'index,follow',
  DokStatus tinyint(1) unsigned NOT NULL default '1',
  Geloescht tinyint(1) unsigned NOT NULL default '0',
  Drucke int(10) unsigned NOT NULL default '0',
  Geklickt int(10) unsigned NOT NULL default '0',
  ElterNavi mediumint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (Id),
  UNIQUE KEY  (Url),
  KEY RubrikId (RubrikId),
  KEY DokStatus (DokStatus)
) TYPE=MyISAM PACK_KEYS=0  DEFAULT CHARSET=cp1251;#inst#


CREATE TABLE %%PRFX%%_document_fields (
  Id int(10) unsigned NOT NULL auto_increment,
  RubrikFeld int(10) unsigned NOT NULL default '0',
  DokumentId int(10) unsigned NOT NULL default '0',
  Inhalt longtext NOT NULL,
  Suche tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (Id),
  KEY DokumentId (DokumentId),
  KEY RubrikFeld (RubrikFeld)
) TYPE=MyISAM PACK_KEYS=0  DEFAULT CHARSET=cp1251;#inst#



CREATE TABLE %%PRFX%%_document_comments (
  Id int(10) unsigned NOT NULL auto_increment,
  DokumentId int(10) unsigned NOT NULL default '0',
  KommentarStart tinyint(1) NOT NULL default '0',
  Titel varchar(100) NOT NULL default '',
  Kommentar text NOT NULL,
  Author varchar(200) NOT NULL default '',
  Zeit int(14) unsigned NOT NULL default '0',
  Aktiv tinyint(1) unsigned NOT NULL default '1',
  AntwortEMail varchar(200) NOT NULL default '',
  PRIMARY KEY  (Id)
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#



CREATE TABLE %%PRFX%%_document_permissions (
  Id smallint(3) NOT NULL auto_increment,
  RubrikId smallint(3) NOT NULL default '0',
  BenutzerGruppe smallint(3) NOT NULL default '0',
  Rechte TEXT NOT NULL,
  PRIMARY KEY  (Id)
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#



CREATE TABLE %%PRFX%%_settings (
  Id smallint(2) NOT NULL auto_increment,
  Seiten_Name varchar(200) NOT NULL default '',
  Mail_Typ enum('mail','smtp','sendmail') NOT NULL default 'mail',
  Mail_Content enum('text/plain','text/html') NOT NULL default 'text/plain',
  Mail_Port smallint(2) NOT NULL default '25',
  Mail_Host varchar(255) NOT NULL default '',
  Mail_Username varchar(255) NOT NULL default '',
  Mail_Passwort varchar(255) NOT NULL default '',
  Mail_Sendmailpfad varchar(255) NOT NULL default '/usr/sbin/sendmail',
  Mail_WordWrap smallint(3) NOT NULL default '50',
  Mail_Absender varchar(255) NOT NULL default '',
  Mail_Name varchar(255) NOT NULL default '',
  Mail_Text_NeuReg text NOT NULL,
  Mail_Text_Fuss text NOT NULL,
  Fehlerseite mediumint(5) unsigned NOT NULL default '0',
  FehlerLeserechte TEXT NOT NULL,
  SeiteWeiter varchar(50) NOT NULL default '',
  SeiteZurueck varchar(50) NOT NULL default '',
  NaviSeiten varchar(50) NOT NULL default '',
  Zeit_Format varchar(25) NOT NULL default 'd.m.Y, H:i',
  DefLand char(2) NOT NULL default 'de',
  PRIMARY KEY  (Id),
  KEY Mail_Typ (Mail_Typ)
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#



CREATE TABLE %%PRFX%%_countries (
  Id smallint(3) unsigned NOT NULL auto_increment,
  LandCode char(2) NOT NULL default 'DE',
  LandName varchar(255) NOT NULL default '',
  Aktiv tinyint(1) NOT NULL default '1',
  IstEU tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (Id)
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#



CREATE TABLE %%PRFX%%_log (
  Id int(10) unsigned NOT NULL auto_increment,
  Zeit int(14) NOT NULL default '0',
  IpCode varchar(25) NOT NULL default '',
  Seite varchar(200) NOT NULL default '',
  Meldung text NOT NULL,
  LogTyp tinyint(3) unsigned NOT NULL default '1',
  Rub tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (Id)
) TYPE=MyISAM PACK_KEYS=0  DEFAULT CHARSET=cp1251;#inst#


CREATE TABLE %%PRFX%%_modul_banners (
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
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#



CREATE TABLE %%PRFX%%_modul_banner_categories (
  Id mediumint(5) unsigned NOT NULL auto_increment,
  KatName varchar(100) NOT NULL default '',
  PRIMARY KEY  (Id)
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#


CREATE TABLE %%PRFX%%_modul_counter (
  Id int(10) unsigned NOT NULL auto_increment,
  CName varchar(50) default NULL,
  PRIMARY KEY  (Id)
) TYPE=MyISAM PACK_KEYS=0  DEFAULT CHARSET=cp1251;#inst#



CREATE TABLE %%PRFX%%_modul_counter_info (
  Id int(11) unsigned NOT NULL auto_increment,
  CId int(11) unsigned NOT NULL default '0',
  Ben_Ip varchar(50) default NULL,
  Ben_Os varchar(20) default NULL,
  Ben_Browser varchar(20) default NULL,
  Ben_Referer varchar(255) default NULL,
  Datum datetime default '0000-00-00 00:00:00',
  Datum_Unix int(14) unsigned NOT NULL default '0',
  Datum_Expire int(14) unsigned NOT NULL default '0',
  PRIMARY KEY  (Id)
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#



CREATE TABLE %%PRFX%%_modul_contacts (
  Id mediumint(5) unsigned NOT NULL auto_increment,
  Name varchar(100) NOT NULL default '',
  MaxZeichen int(8) unsigned NOT NULL default '20000',
  Empfaenger text NOT NULL,
  Empfaenger_Multi text NOT NULL,
  AntiSpam tinyint(1) unsigned NOT NULL default '1',
  MaxUpload mediumint(5) unsigned NOT NULL default '500',
  ZeigeBetreff tinyint(1) unsigned NOT NULL default '1',
  StandardBetreff varchar(200) NOT NULL default 'Betreff',
  Gruppen varchar(255) NOT NULL default '1,2,3,4,5,6',
  ZeigeKopie tinyint(1) unsigned NOT NULL default '1',
  TextKeinZugriff text NOT NULL,
  PRIMARY KEY  (Id)
) TYPE=MyISAM PACK_KEYS=0  DEFAULT CHARSET=cp1251;#inst#


CREATE TABLE %%PRFX%%_modul_contact_fields (
  Id int(10) unsigned NOT NULL auto_increment,
  KontaktId mediumint(5) unsigned NOT NULL default '0',
  Feld varchar(100) NOT NULL default 'text',
  Position smallint(2) unsigned NOT NULL default '1',
  FeldTitel tinytext NOT NULL,
  Pflicht tinyint(1) unsigned NOT NULL default '0',
  StdWert longtext NOT NULL,
  Aktiv tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (Id)
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#



CREATE TABLE %%PRFX%%_modul_contact_info (
  Id int(10) unsigned NOT NULL auto_increment,
  FormId varchar(20) NOT NULL default '',
  Email varchar(200) NOT NULL default '',
  Datum int(14) unsigned NOT NULL default '0',
  Betreff varchar(255) NOT NULL default '',
  `Text` longtext NOT NULL,
  Aw_Zeit int(10) unsigned NOT NULL default '0',
  Aw_Email varchar(200) NOT NULL default '',
  Aw_Absender varchar(200) NOT NULL default '',
  Aw_Text longtext NOT NULL,
  FId mediumint(3) unsigned NOT NULL default '0',
  Anhang text NOT NULL,
  Aw_Anhang text NOT NULL,
  PRIMARY KEY  (Id)
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#



CREATE TABLE %%PRFX%%_modul_login (
  Id smallint(2) unsigned NOT NULL auto_increment,
  RegTyp enum('now','email','byadmin') NOT NULL default 'now',
  AntiSpam tinyint(1) NOT NULL default '0',
  IstAktiv tinyint(1) NOT NULL default '1',
  DomainsVerboten text NOT NULL,
  EmailsVerboten text NOT NULL,
  ZeigeFirma tinyint(1) unsigned NOT NULL default '0',
  ZeigeVorname tinyint(1) unsigned NOT NULL default '0',
  ZeigeNachname tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (Id)
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#



CREATE TABLE %%PRFX%%_modul_search (
  Id int(10) unsigned NOT NULL auto_increment,
  Suchbegriff varchar(255) NOT NULL default '',
  Anzahl mediumint(5) unsigned NOT NULL default '0',
  Gefunden mediumint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (Id)
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#


CREATE TABLE %%PRFX%%_modul_gallery (
  Id int(14) unsigned NOT NULL auto_increment,
  GName varchar(200) NOT NULL default '',
  Beschreibung text NOT NULL,
  Author mediumint(5) unsigned NOT NULL default '0',
  Erstellt int(14) unsigned NOT NULL default '0',
  ThumbBreite mediumint(4) unsigned NOT NULL default '120',
  MaxZeile smallint(2) unsigned NOT NULL default '4',
  ZeigeTitel tinyint(1) unsigned NOT NULL default '1',
  ZeigeBeschreibung tinyint(1) unsigned NOT NULL default '1',
  ZeigeGroesse tinyint(1) unsigned NOT NULL default '0',
  TypeOut tinyint(1) unsigned NOT NULL default '4',
  MaxBilder mediumint(4) unsigned NOT NULL default '18',
  Watermark varchar(255) default '',
  GPfad text NOT NULL,
  PRIMARY KEY  (Id)
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#

CREATE TABLE %%PRFX%%_modul_gallery_images (
  Id int(10) unsigned NOT NULL auto_increment,
  GalId int(10) unsigned NOT NULL default '0',
  Pfad varchar(255) NOT NULL default '',
  Author mediumint(5) unsigned NOT NULL default '0',
  BildTitel varchar(200) NOT NULL default '',
  BildBeschr text NOT NULL,
  Endung varchar(6) NOT NULL default '',
  Erstellt int(14) unsigned NOT NULL default '0',
  PRIMARY KEY  (Id)
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#

CREATE TABLE %%PRFX%%_modul_comments (
  Id smallint(2) unsigned NOT NULL auto_increment,
  MaxZeichen mediumint(5) unsigned NOT NULL default '1000',
  Gruppen text NOT NULL,
  Zensur tinyint(1) unsigned NOT NULL default '0',
  Aktiv tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (Id)
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#

CREATE TABLE %%PRFX%%_modul_comment_info (
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
) TYPE=MyISAM  DEFAULT CHARSET=cp1251;#inst#

CREATE TABLE %%PRFX%%_modul_newsarchive (
  id mediumint(5) unsigned NOT NULL auto_increment,
  arc_name varchar(100) NOT NULL default '',
  rubs varchar(255) NOT NULL default '',
  show_days smallint(1) unsigned NOT NULL default '1',
  show_empty smallint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (id)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;#inst#

CREATE TABLE %%PRFX%%_module (
  Id mediumint(5) unsigned NOT NULL auto_increment,
  ModulName varchar(255) NOT NULL default '',
  `Status` enum('1','0') NOT NULL default '1',
  CpEngineTag varchar(255) NOT NULL default '0',
  CpPHPTag varchar(255) NOT NULL default '0',
  ModulFunktion varchar(255) NOT NULL default '',
  IstFunktion enum('1','0') NOT NULL default '1',
  ModulPfad varchar(255) NOT NULL default '',
  Version varchar(20) NOT NULL default '1.0',
  Template mediumint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (Id),
  UNIQUE KEY ModulName (ModulName)
) TYPE=MyISAM PACK_KEYS=0  DEFAULT CHARSET=cp1251;#inst#



CREATE TABLE %%PRFX%%_navigation (
  id int(11) unsigned NOT NULL auto_increment,
  titel varchar(255) default NULL,
  ebene1 text,
  ebene2 text,
  ebene3 text,
  ebene1a text,
  ebene2a text,
  ebene3a text,
  ebene1_v text,
  ebene1_n text,
  ebene2_v text,
  ebene2_n text,
  ebene3_v text,
  ebene3_n text,
  vor text,
  nach text,
  Gruppen text NOT NULL,
  Expand tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM PACK_KEYS=0  DEFAULT CHARSET=cp1251;#inst#


CREATE TABLE %%PRFX%%_navigation_items (
  Id mediumint(5) unsigned NOT NULL auto_increment,
  Titel varchar(255) default NULL,
  Elter smallint(5) unsigned default NULL,
  Link varchar(255) default NULL,
  Ziel varchar(100) default NULL,
  Ebene enum('1','2','3') default '1',
  Rang mediumint(5) unsigned default '1',
  Rubrik int(10) unsigned default '0',
  Aktiv tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (Id)
) TYPE=MyISAM PACK_KEYS=0  DEFAULT CHARSET=cp1251;#inst#


CREATE TABLE %%PRFX%%_rubrics (
  Id mediumint(5) unsigned NOT NULL auto_increment,
  RubrikName varchar(100) NOT NULL default '',
  UrlPrefix varchar(50) NOT NULL default '/',
  RubrikTemplate text NOT NULL,
  Vorlage mediumint(5) unsigned NOT NULL default '1',
  RBenutzer mediumint(5) unsigned NOT NULL default '1',
  RDatum int(14) unsigned NOT NULL default '0',
  PRIMARY KEY  (Id),
  KEY Vorlage (Vorlage)
) TYPE=MyISAM PACK_KEYS=0  DEFAULT CHARSET=cp1251;#inst#

CREATE TABLE %%PRFX%%_rubric_fields (
  Id int(10) unsigned NOT NULL auto_increment,
  RubrikId int(10) unsigned NOT NULL default '1',
  Titel varchar(255) NOT NULL default '',
  RubTyp varchar(75) NOT NULL default '',
  rubric_position int(10) unsigned NOT NULL default '0',
  StdWert varchar(255) NOT NULL default '',
  PRIMARY KEY  (Id),
  KEY RubrikId (RubrikId)
) TYPE=MyISAM PACK_KEYS=0  DEFAULT CHARSET=cp1251;#inst#


CREATE TABLE %%PRFX%%_sessions (
  sesskey varchar(32) NOT NULL default '',
  expiry int(10) unsigned NOT NULL default '0',
  `value` text NOT NULL,
  Ip varchar(35) NOT NULL default '',
  expire_datum varchar(25) NOT NULL default '',
  PRIMARY KEY  (sesskey),
  KEY expiry (expiry),
  KEY expire_datum (expire_datum)
) TYPE=MyISAM DEFAULT CHARSET=cp1251;#inst#



CREATE TABLE %%PRFX%%_templates (
  Id int(10) unsigned NOT NULL auto_increment,
  TplName varchar(255) NOT NULL default '',
  Template longtext NOT NULL,
  TBenutzer mediumint(5) unsigned NOT NULL default '0',
  TDatum int(14) unsigned NOT NULL default '0',
  PRIMARY KEY  (Id)
) TYPE=MyISAM PACK_KEYS=0 DEFAULT CHARSET=cp1251;#inst#
