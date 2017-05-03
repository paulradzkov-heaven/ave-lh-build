<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("USER_IMPORT"))
{
	exit;
}

		$csv_available_fields = array(
			'Id' => $GLOBALS['config_vars']['UserImport_Id'],
			'Kennwort' => $GLOBALS['config_vars']['UserImport_Kennwort'],
			'Email' => $GLOBALS['config_vars']['UserImport_Email'],
			'Strasse' => $GLOBALS['config_vars']['UserImport_Strasse'],
			'HausNr' => $GLOBALS['config_vars']['UserImport_HausNr'],
			'Postleitzahl' => $GLOBALS['config_vars']['UserImport_Postleitzahl'],
			'Ort' => $GLOBALS['config_vars']['UserImport_Ort'],
			'Telefon' => $GLOBALS['config_vars']['UserImport_Telefon'],
			'Telefax' => $GLOBALS['config_vars']['UserImport_Telefax'],
			'Bemerkungen' => $GLOBALS['config_vars']['UserImport_Bemerkungen'],
			'Vorname' => $GLOBALS['config_vars']['UserImport_Vorname'],
			'Nachname' => $GLOBALS['config_vars']['UserImport_Nachname'],
			'UserName' => $GLOBALS['config_vars']['UserImport_UserName'],
			'Benutzergruppe' => $GLOBALS['config_vars']['UserImport_Benutzergruppe'],
			'Registriert' => $GLOBALS['config_vars']['UserImport_Registriert'],
			'Status' => $GLOBALS['config_vars']['UserImport_Status'],
			'ZuletztGesehen' => $GLOBALS['config_vars']['UserImport_ZuletztGesehen'],
			'Land' => $GLOBALS['config_vars']['UserImport_Land'],
			'GebTag' => $GLOBALS['config_vars']['UserImport_GebTag'],
			'emc' => $GLOBALS['config_vars']['UserImport_emc'],
			'IpReg' => $GLOBALS['config_vars']['UserImport_IpReg'],
			'KennTemp' => $GLOBALS['config_vars']['UserImport_KennTemp'],
			'Firma' => $GLOBALS['config_vars']['UserImport_Firma'],
			'UStPflichtig' => $GLOBALS['config_vars']['UserImport_UStPflichtig']
		);

		$GLOBALS['tmpl']->assign('method', 'shop');
		$GLOBALS['tmpl']->assign('next', 0);

		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] != '')
		{

			switch($_REQUEST['sub'])
			{
				case 'importcsv':
					$TempDir = BASE_DIR . "/modules/shop/uploads/";
					$tpl_in = $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_userimport.tpl');
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
						if(!move_uploaded_file($_FILES['csvfile']['tmp_name'], $TempDir . '/CSVIMPORT_user_' . $_SESSION['cp_benutzerid'] . '_'.$fileid.'.txt'))
						{
							$GLOBALS['tmpl']->assign('error', $GLOBALS['config_vars']['ImportNotReadable']);
							$GLOBALS['tmpl']->assign('content', $tpl_in);
						}


						// ========================================================
						// Datei öffnen und Kopfzeile einlesen
						// ========================================================
						$fp = fopen($TempDir . '/CSVIMPORT_user_' . $_SESSION['cp_benutzerid'] . '_'.$fileid.'.txt', 'r');
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
							if($csv_field != 'Geloescht' && $csv_field != 'GeloeschtDatum')
							{
								$field_table[] = array(
								'id'		=> md5($csv_field),
								'csv_field'	=> $csv_field,
								'my_field'	=> $my_field
								);
							}
						}


						$ugroups = array();
						$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_user_groups");
						while($row = $sql->fetchrow())
						{
							array_push($ugroups, $row);
						}

						// ========================================================
						// Werte zuweisen
						// ========================================================
						$GLOBALS['tmpl']->assign('Ugroups', $ugroups);
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
					if(!file_exists($TempDir . '/CSVIMPORT_user_' . $_SESSION['cp_benutzerid'] . '_'.$fileid.'.txt'))
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
					$fp = fopen($TempDir . '/CSVIMPORT_user_' . $_SESSION['cp_benutzerid'] . '_'.$fileid.'.txt', 'r');
					$csv = new CSVReader($fp);
					$fields = $csv->Fields();

					if($error==true)
					{
						$GLOBALS['tmpl']->assign('content', $tpl_in);
					}

					while($row = $csv->FetchRow())
					{

						if(count($row) == $csv->NumFields())
						{
						$Id  = '';
						$Kennwort = '';
						$Email  = '';
						$Strasse  = '';
						$HausNr   = '';
						$Postleitzahl = '';
						$Ort    = '';
						$Telefon  = '';
						$Telefax  = '';
						$Bemerkungen  = '';
						$Vorname  = '';
						$Nachname  = '';
						$UserName  = '';
						$Benutzergruppe = '';
						$Registriert = '';
						$Status  = '';
						$ZuletztGesehen  = '';
						$Land  = '';
						$GebTag  = '';
						$emc  = '';
						$IpReg = '';
						$KennTemp  = '';
						$Firma = '';
						$UStPflichtig = '';

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
									case 'Kennwort' : $Kennwort = $value; break;
									case 'Email' : $Email = $value; break;
									case 'Strasse' : $Strasse = $value; break;
									case 'HausNr' : $HausNr = $value; break;
									case 'Postleitzahl' : $Postleitzahl = $value; break;
									case 'Ort' : $Ort = $value; break;
									case 'Telefon' : $Telefon = $value; break;
									case 'Telefax' : $Telefax = $value; break;
									case 'Bemerkungen' : $Bemerkungen = $value; break;
									case 'Vorname' : $Vorname = $value; break;
									case 'Nachname' : $Nachname = $value; break;
									case 'UserName' : $UserName = $value; break;
									case 'Benutzergruppe' : $Benutzergruppe = $value; break;
									case 'Registriert' : $Registriert = $value; break;
									case 'Status' : $Status = $value; break;
									case 'ZuletztGesehen' : $ZuletztGesehen = $value; break;
									case 'Land' : $Land = $value; break;
									case 'GebTag' : $GebTag = $value; break;
									case 'emc' : $emc = $value; break;
									case 'IpReg' : $IpReg = $value; break;
									case 'KennTemp' : $KennTemp = $value; break;
									case 'Firma' : $Firma = $value; break;
									case 'UStPflichtig' : $UStPflichtig = $value; break;
								}

							}

							// ========================================================
							// Wenn Produkt existiert, nicht aktualisieren
							// ========================================================
							if(trim($Id) != '')
							{
								$update = false;
								if($existing == 'replace')
								{
									$sql = $GLOBALS['db']->Query("SELECT COUNT(*) FROM " . PREFIX . "_users WHERE Id='" . $Id . "'");
									$row = $sql->FetchArray();
									if($row[0] > 0)
										$update = true;
								}

								if($update)
								{
									$Benutzergruppe = (isset($_REQUEST['Benutzergruppe']) && $_REQUEST['Benutzergruppe'] != 'FILE') ? $_REQUEST['Benutzergruppe'] : $Benutzergruppe;

									$sql = "UPDATE ".PREFIX."_users
										SET
											Id  = '$Id',
											Kennwort = '$Kennwort',
											Email  = '$Email',
											Strasse  = '$Strasse',
											HausNr   = '$HausNr',
											Postleitzahl = '$Postleitzahl',
											Ort    = '$Ort',
											Telefon  = '$Telefon',
											Telefax  = '$Telefax',
											Bemerkungen  = '$Bemerkungen',
											Vorname  = '$Vorname',
											Nachname  = '$Nachname',
											`UserName`  = '$UserName',
											Benutzergruppe = '$Benutzergruppe',
											Registriert = '$Registriert',
											Status  = '$Status',
											ZuletztGesehen  = '$ZuletztGesehen',
											Land  = '$Land',
											GebTag  = '$GebTag',
											emc  = '$emc',
											IpReg = '$IpReg',
											KennTemp  = '$KennTemp',
											Firma = '$Firma',
											UStPflichtig = '$UStPflichtig'
										WHERE
											Id = '" . $Id . "' AND
											Id != '" . $_SESSION['cp_benutzerid'] . "'
											";
											$GLOBALS['db']->Query($sql);
								} else {
									if(isset($_REQUEST['DelData']) && $_REQUEST['DelData'] == '1')
									{
										$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_users WHERE Id != '".$_SESSION['cp_benutzerid']."'");
										$GLOBALS['db']->Query("ALTER TABLE " . PREFIX . "_users PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =1");
									}

									$sql = "INSERT INTO " . PREFIX . "_users
											(
												Id,
												Kennwort,
												Email,
												Strasse,
												HausNr,
												Postleitzahl,
												Ort,
												Telefon,
												Telefax,
												Bemerkungen,
												Vorname,
												Nachname,
												`UserName`,
												Benutzergruppe,
												Registriert,
												Status,
												ZuletztGesehen,
												Land,
												GebTag,
												emc,
												IpReg,
												KennTemp,
												Firma,
												UStPflichtig
										) VALUES (
												'$Id',
												'$Kennwort',
												'$Email',
												'$Strasse',
												'$HausNr',
												'$Postleitzahl',
												'$Ort',
												'$Telefon',
												'$Telefax',
												'$Bemerkungen',
												'$Vorname',
												'$Nachname',
												'$UserName',
												'$Benutzergruppe',
												'$Registriert',
												'$Status',
												'$ZuletztGesehen',
												'$Land',
												'$GebTag',
												'$emc',
												'$IpReg',
												'$KennTemp',
												'$Firma',
												'$UStPflichtig'
											)";
										if($Id != $_SESSION['cp_benutzerid']) $GLOBALS['db']->Query($sql);
								}
							}
						}
					}

					fclose($fp);
					unset($_REQUEST['action']);
					@unlink($TempDir . '/CSVIMPORT_user_' . $_SESSION['cp_benutzerid'] . '_'.$fileid.'.txt');
					//header("Location:index.php?do=modules&action=modedit&mod=shop&moduleaction=shopimport&cp=".SESSION."&pop=1");
					echo '<script>window.close();</ script>';
					exit;
					//$GLOBALS['tmpl']->assign('ImportOk', 1);
				break;
			}
		}

		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_userimport.tpl'));
?>