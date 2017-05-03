<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("ARTICLE_IMPORT"))
{
	exit;
}

		$csv_available_fields = array(
			'Id' => $GLOBALS['config_vars']['ItemImport_Id'],
			'ArtNr' => $GLOBALS['config_vars']['ItemImport_ArtNr'],
			'KatId' => $GLOBALS['config_vars']['ItemImport_KatId'],
			'KatId_Multi' => $GLOBALS['config_vars']['ItemImport_KatId_Multi'],
			'ArtName' => $GLOBALS['config_vars']['ItemImport_ArtName'],
			'Aktiv' => $GLOBALS['config_vars']['ItemImport_Aktiv'],
			'Preis' => $GLOBALS['config_vars']['ItemImport_Preis'],
			'PreisListe' => $GLOBALS['config_vars']['ItemImport_PreisListe'],
			'Bild' => $GLOBALS['config_vars']['ItemImport_Bild'],
			'Bild_Typ' => $GLOBALS['config_vars']['ItemImport_Bild_Typ'],
			'TextKurz' => $GLOBALS['config_vars']['ItemImport_TextKurz'],
			'TextLang' => $GLOBALS['config_vars']['ItemImport_TextLang'],
			'Gewicht' => $GLOBALS['config_vars']['ItemImport_Gewicht'],
			'Angebot' => $GLOBALS['config_vars']['ItemImport_Angebot'],
			'AngebotBild' => $GLOBALS['config_vars']['ItemImport_AngebotBild'],
			'UstZone' => $GLOBALS['config_vars']['ItemImport_UstZone'],
			'Erschienen' => $GLOBALS['config_vars']['ItemImport_Erschienen'],
			'Frei_Titel_1' => $GLOBALS['config_vars']['ItemImport_Frei_Titel_1'],
			'Frei_Text_1' => $GLOBALS['config_vars']['ItemImport_Frei_Text_1'],
			'Frei_Titel_2' => $GLOBALS['config_vars']['ItemImport_Frei_Titel_2'],
			'Frei_Text_2' => $GLOBALS['config_vars']['ItemImport_Frei_Text_2'],
			'Frei_Titel_3' => $GLOBALS['config_vars']['ItemImport_Frei_Titel_3'],
			'Frei_Text_3' => $GLOBALS['config_vars']['ItemImport_Frei_Text_3'],
			'Frei_Titel_4' => $GLOBALS['config_vars']['ItemImport_Frei_Titel_4'],
			'Frei_Text_4' => $GLOBALS['config_vars']['ItemImport_Frei_Text_4'],
			'Hersteller' => $GLOBALS['config_vars']['ItemImport_Hersteller'],
			'Schlagwoerter' => $GLOBALS['config_vars']['ItemImport_Schlagwoerter'],
			'Einheit' => $GLOBALS['config_vars']['ItemImport_Einheit'],
			'EinheitId' => $GLOBALS['config_vars']['ItemImport_EinheitId'],
			'Lager' => $GLOBALS['config_vars']['ItemImport_Lager'],
			'VersandZeitId' => $GLOBALS['config_vars']['ItemImport_VersandZeitId'],
			'Bestellungen' => $GLOBALS['config_vars']['ItemImport_Bestellungen']
		);

		$GLOBALS['tmpl']->assign('method', 'shop');
		$GLOBALS['tmpl']->assign('next', 0);

		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] != '')
		{

			switch($_REQUEST['sub'])
			{
				case 'importcsv':
					$TempDir = BASE_DIR . "/modules/shop/uploads/";
					$tpl_in = $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_shopimport.tpl');
					$error = false;
					$gone = true;
					$ValidFiles = array('text/csv','text/plain', 'application/csv', 'application/octet-stream', 'text/comma-separated-values', 'text/x-comma-separated-values', 'text/x-csv', 'application/vnd.ms-excel');
					if(isset($_FILES['csvfile']) && ( !in_array($_FILES['csvfile']['type'],$ValidFiles) ) )
					{
						$GLOBALS['tmpl']->assign('error',  $GLOBALS['config_vars']['ImportDataWrong']);
						$GLOBALS['tmpl']->assign('content', $tpl_in);
						$error = true;
						$gone = false;
					}

					// ========================================================
					// Datei leer?
					// ========================================================
					if(($error==true || !isset($_FILES['csvfile']) || $_FILES['csvfile']['size']<10) && ($gone==true))
					{
						$GLOBALS['tmpl']->assign('error', $GLOBALS['config_vars']['ImportNoData']);
						$GLOBALS['tmpl']->assign('content', $tpl_in);
						$error = true;
					}

					// ========================================================
					// In den temporären Ordner kopieren
					// ========================================================
					if($error==false)
					{
						$fileid = md5(microtime().time().mt_rand(0, 1000));
						if(!move_uploaded_file($_FILES['csvfile']['tmp_name'], $TempDir . '/CSVIMPORT_shop_' . $_SESSION['cp_benutzerid'] . '_'.$fileid.'.txt'))
						{
							$GLOBALS['tmpl']->assign('error', $GLOBALS['config_vars']['ImportNotReadable']);
							$GLOBALS['tmpl']->assign('content', $tpl_in);
						}


						// ========================================================
						// Datei öffnen und Kopfzeile einlesen
						// ========================================================
						$fp = fopen($TempDir . '/CSVIMPORT_shop_' . $_SESSION['cp_benutzerid'] . '_'.$fileid.'.txt', 'r');
						$csv = new CSVReader($fp);
						$fields = $csv->Fields();
						fclose($fp);

						// ========================================================
						// valid?
						// ========================================================
						if($csv->NumFields() < 1)
						{
							$GLOBALS['tmpl']->assign('error', $GLOBALS['config_vars']['ImportDataError']);
							$GLOBALS['tmpl']->assign('content', $tpl_in);
						}

						// ========================================================
						// Try to guess the fields
						// ========================================================
						$field_table = array();
						foreach($fields as $csv_field)
						{
							$my_field = @$csv_assocs[$csv_field];
							if($csv_field != 'Bilder' && $csv_field != 'DateiDownload' )
							{
							$field_table[] = array(
								'id'		=> md5($csv_field),
								'csv_field'	=> $csv_field,
								'my_field'	=> $my_field
							);
							}
						}

						// ========================================================
						// Werte zuweisen
						// ========================================================
						$GLOBALS['tmpl']->assign('page', 'adressen_csv.tpl');
						$GLOBALS['tmpl']->assign('fileid', $fileid);
						$GLOBALS['tmpl']->assign('field_table', $field_table);
						$GLOBALS['tmpl']->assign('available_fields', $csv_available_fields);
						$GLOBALS['tmpl']->assign('next', 1);
						$GLOBALS['tmpl']->assign('datas', $csv->NumFields());
						$GLOBALS['tmpl']->assign('content', $tpl_in);
					}
					if($error==true)
					{
						$GLOBALS['tmpl']->assign('content', $tpl_in);
					}
				break;


				case 'importcsv2':
					$error=false;
					$TempDir = BASE_DIR . "/modules/shop/uploads/";
					// ========================================================
					// Nach temporärere Datei suchen...
					// ========================================================
					$fileid = ereg_replace("([^0-9a-zA-Z]*)", "", $_REQUEST['fileid']);
					if(!file_exists($TempDir . '/CSVIMPORT_shop_' . $_SESSION['cp_benutzerid'] . '_'.$fileid.'.txt'))
					{
						$GLOBALS['tmpl']->assign('error', $GLOBALS['config_vars']['ImportNotReadable']);
						$GLOBALS['tmpl']->assign('content', $tpl_in);
						$error=true;
					}

					switch($_REQUEST['existing'])
					{
						case 'replace': $existing = 'replace'; break;
						case 'ignore': $existing = 'ignore'; break;
						default: $existing = 'replace'; break;
					}

					// ========================================================
					// Datei öffnen
					// ========================================================
					$fp = fopen($TempDir . '/CSVIMPORT_shop_' . $_SESSION['cp_benutzerid'] . '_'.$fileid.'.txt', 'r');
					$csv = new CSVReader($fp);
					$fields = $csv->Fields();

					if($error==true)
					{
						echo "FEHLER";
						$GLOBALS['tmpl']->assign('content', $tpl_in);
					}

					while($row = $csv->FetchRow())
					{

						if(count($row) == $csv->NumFields())
						{
						$Id = '';
						$ArtNr = '';
						$KatId = '';
						$KatId_Multi = '';
						$ArtName = '';
						$Aktiv = '';
						$Preis = '';
						$PreisListe = '';
						$Bild = '';
						$Bild_Typ = '';
						$Bilder = '';
						$TextKurz = '';
						$TextLang = '';
						$Gewicht = '';
						$Angebot = '';
						$AngebotBild = '';
						$UstZone = '';
						$Erschienen = '';
						$Frei_Titel_1 = '';
						$Frei_Text_1 = '';
						$Frei_Titel_2 = '';
						$Frei_Text_2 = '';
						$Frei_Titel_3 = '';
						$Frei_Text_3 = '';
						$Frei_Titel_4 = '';
						$Frei_Text_4 = '';
						$Hersteller = '';
						$Schlagwoerter = '';
						$Einheit = '';
						$EinheitId = '';
						$Lager = '';
						$VersandZeitId = '';
						$Bestellungen = '';
						$DateiDownload = '';

						$i = 0;

							foreach($row as $key=>$value)
							{
								// ========================================================
								// Feld erkennen...
								// ========================================================
								$field = @$_REQUEST['field_'.md5($key)];

								switch($field)
								{
									case 'Id' : $Id = $value; break;
									case 'ArtNr' : $ArtNr = $value; break;
									case 'KatId' : $KatId = $value; break;
									case 'KatId_Multi' : $KatId_Multi = $value; break;
									case 'ArtName' : $ArtName = $value; break;
									case 'Aktiv' : $Aktiv = $value; break;
									case 'Preis' : $Preis = $value; break;
									case 'PreisListe' : $PreisListe = $value; break;
									case 'Bild' : $Bild = $value; break;
									case 'Bild_Typ' : $Bild_Typ = $value; break;
									case 'Bilder' : $Bilder = $value; break;
									case 'TextKurz' : $TextKurz = $value; break;
									case 'TextLang' : $TextLang = $value; break;
									case 'Gewicht' : $Gewicht = $value; break;
									case 'Angebot' : $Angebot = $value; break;
									case 'AngebotBild' : $AngebotBild = $value; break;
									case 'UstZone' : $UstZone = $value; break;
									case 'Erschienen' : $Erschienen = $value; break;
									case 'Frei_Titel_1' : $Frei_Titel_1 = $value; break;
									case 'Frei_Text_1' : $Frei_Text_1 = $value; break;
									case 'Frei_Titel_2' : $Frei_Titel_2 = $value; break;
									case 'Frei_Text_2' : $Frei_Text_2 = $value; break;
									case 'Frei_Titel_3' : $Frei_Titel_3 = $value; break;
									case 'Frei_Text_3' : $Frei_Text_3 = $value; break;
									case 'Frei_Titel_4' : $Frei_Titel_4 = $value; break;
									case 'Frei_Text_4' : $Frei_Text_4 = $value; break;
									case 'Hersteller' : $Hersteller = $value; break;
									case 'Schlagwoerter' : $Schlagwoerter = $value; break;
									case 'Einheit' : $Einheit = $value; break;
									case 'EinheitId' : $EinheitId = $value; break;
									case 'Lager' : $Lager = $value; break;
									case 'VersandZeitId' : $VersandZeitId = $value; break;
									case 'Bestellungen' : $Bestellungen = $value; break;
								}

							}

							// ========================================================
							// Wichtige Werte setzen, wenn nicht vorhanden
							// ========================================================
							$Lager = ($Lager=='') ? 1000 : $Lager;
							$UstZone = ($UstZone=='') ? 1 : $UstZone;
							$Aktiv = ($Aktiv=='') ? 1 : $Aktiv;
							/*
								$TextKurz = addslashes($TextKurz);
								$TextLang = addslashes($TextLang);
							*/

							// ========================================================
							// Wenn Produkt existiert, nicht aktualisieren
							// ========================================================
							if(trim($Id) != '')
							{
								$update = false;
								if($existing == 'replace')
								{
									$sql = $GLOBALS['db']->Query("SELECT COUNT(*) FROM " . PREFIX . "_modul_shop_artikel WHERE Id='" . $Id . "'");
									$row = $sql->FetchArray();
									if($row[0] > 0)
										$update = true;
								}

								if (isset($_REQUEST['netto_to_brutto']) && $_REQUEST['netto_to_brutto']==1)
								{
									$mp1 = '1.';
									$mp2 = $_REQUEST['mpli'];
									$multi = $mp1 . $mp2;
									//$Preis = round($Preis*$multi, 2);
									$Preis = $Preis*$multi;
								}

								if($KatId == 0 || $KatId == '')
								{
									$sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_shop_kategorie ORDER BY Id ASC LIMIT 1");
									$row_a = $sql->fetchrow();
									$articlecat = $row_a->Id;
									$articlecat_2 = $row_a->Id;
								}

								if($update)
								{
									$sql = "UPDATE ".PREFIX."_modul_shop_artikel
										SET
											Id = '$Id',
											ArtNr = '$ArtNr',
											KatId = '$KatId',
											KatId_Multi = '$KatId_Multi',
											ArtName = '$ArtName',
											Aktiv = '$Aktiv',
											Preis = '$Preis',
											PreisListe = '$PreisListe',
											Bild = '$Bild',
											Bild_Typ = '$Bild_Typ',
											Bilder = '$Bilder',
											TextKurz = '$TextKurz',
											TextLang = '$TextLang',
											Gewicht = '$Gewicht',
											Angebot = '$Angebot',
											AngebotBild = '$AngebotBild',
											UstZone = '$UstZone',
											Erschienen = '$Erschienen',
											Frei_Titel_1 = '$Frei_Titel_1',
											Frei_Text_1 = '$Frei_Text_1',
											Frei_Titel_2 = '$Frei_Titel_2',
											Frei_Text_2 = '$Frei_Text_2',
											Frei_Titel_3 = '$Frei_Titel_3',
											Frei_Text_3 = '$Frei_Text_3',
											Frei_Titel_4 = '$Frei_Titel_4',
											Frei_Text_4 = '$Frei_Text_4',
											Hersteller = '$Hersteller',
											Schlagwoerter = '$Schlagwoerter',
											Einheit = '$Einheit',
											EinheitId = '$EinheitId',
											Lager = '$Lager',
											VersandZeitId = '$VersandZeitId',
											Bestellungen = '$Bestellungen'
										WHERE
											Id = '" . $Id . "' AND ArtNr = '" . $ArtNr . "'
											";
											$GLOBALS['db']->Query($sql);
								} else {
									if(isset($_REQUEST['DelData']) && $_REQUEST['DelData'] == '1')
									{
										$GLOBALS['db']->Query("TRUNCATE TABLE " . PREFIX . "_modul_shop_artikel");
									}

									$sql = "INSERT INTO " . PREFIX . "_modul_shop_artikel
											(
											Id,
											ArtNr,
											KatId,
											KatId_Multi,
											ArtName,
											Aktiv,
											Preis,
											PreisListe,
											Bild,
											Bild_Typ,
											Bilder,
											TextKurz,
											TextLang,
											Gewicht,
											Angebot,
											AngebotBild,
											UstZone,
											Erschienen,
											Frei_Titel_1,
											Frei_Text_1,
											Frei_Titel_2,
											Frei_Text_2,
											Frei_Titel_3,
											Frei_Text_3,
											Frei_Titel_4,
											Frei_Text_4,
											Hersteller,
											Schlagwoerter,
											Einheit,
											EinheitId,
											Lager,
											VersandZeitId,
											Bestellungen
										) VALUES (
											'$Id',
											'$ArtNr',
											'$KatId',
											'$KatId_Multi',
											'$ArtName',
											'$Aktiv',
											'$Preis',
											'$PreisListe',
											'$Bild',
											'$Bild_Typ',
											'$Bilder',
											'$TextKurz',
											'$TextLang',
											'$Gewicht',
											'$Angebot',
											'$AngebotBild',
											'$UstZone',
											'$Erschienen',
											'$Frei_Titel_1',
											'$Frei_Text_1',
											'$Frei_Titel_2',
											'$Frei_Text_2',
											'$Frei_Titel_3',
											'$Frei_Text_3',
											'$Frei_Titel_4',
											'$Frei_Text_4',
											'$Hersteller',
											'$Schlagwoerter',
											'$Einheit',
											'$EinheitId',
											'$Lager',
											'$VersandZeitId',
											'$Bestellungen'
											)";
										$GLOBALS['db']->Query($sql);
								}
							}
						}
					}

					fclose($fp);
					unset($_REQUEST['action']);
					@unlink($TempDir . '/CSVIMPORT_shop_' . $_SESSION['cp_benutzerid'] . '_'.$fileid.'.txt');
					//header("Location:index.php?do=modules&action=modedit&mod=shop&moduleaction=shopimport&cp=".SESSION."&pop=1");
					echo '<script>window.opener.location.href=\'index.php?do=modules&action=modedit&mod=shop&moduleaction=products&cp='.SESSION.'\';window.close();</script>';
					exit;
				break;
			}
		}
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_shopimport.tpl'));
?>