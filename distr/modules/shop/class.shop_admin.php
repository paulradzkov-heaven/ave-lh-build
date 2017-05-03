<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class Shop
{
	var $_allowed = array('.jpg','jpeg','.jpe','.gif','.png');
	var $_allowed_images = array('image/jpeg','image/pjpeg','image/jpe','image/gif','image/png','image/x-png');
	var $_coupon_limit = 10;
	var $_orders_limit = 10;
	var $_expander = '---';

	//=======================================================
	// Shop - Startseite (Магазин - стартовая страница)
	//=======================================================
	function shopStart($tpl_dir)
	{
		$home = true;
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] != '')
		{
			$home = false;
			switch($_REQUEST['sub'])
			{
				case 'topseller':
					$GLOBALS['tmpl']->assign('TopSeller', $this->topSeller('','',100));
					$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_home_topseller.tpl'));
				break;

				case 'flopseller':
					$GLOBALS['tmpl']->assign('TopSeller', $this->topSeller('','1',100));
					$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_home_flopseller.tpl'));
				break;

				case 'rez':
					$GLOBALS['tmpl']->assign('Rez', $this->getRez(100));
					$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_rez100.tpl'));
				break;
			}
		}

		$GLOBALS['tmpl']->assign('Rez', $this->getRez(10));
		$GLOBALS['tmpl']->assign('TopSeller', $this->topSeller());
		$GLOBALS['tmpl']->assign('FlopSeller', $this->topSeller(0,1));
		if($home==true) $GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_home.tpl'));
	}

	//=======================================================
	// Rezensionen / Bewertungen (Рецензии / Оценки)
	//=======================================================
	function getRez($limit='')
	{
		$L = ($limit != '') ? $limit : 10;
		$rez = array();
		$sql = $GLOBALS['db']->Query("SELECT DISTINCT(ArtId) FROM " . PREFIX . "_modul_shop_artikel_kommentare WHERE Publik != 1  LIMIT $L");

		while($row = $sql->fetchrow())
		{
			$sql_a = $GLOBALS['db']->Query("SELECT Id,ArtNr,ArtName FROM " . PREFIX . "_modul_shop_artikel WHERE Id = '$row->ArtId'");
			@$row_a = $sql_a->fetchrow();
			@$row->Artname = $row_a->ArtName;
			@$row->ArtNr = $row_a->Id;
			array_push($rez,$row);
		}
		return $rez;
	}


	//=======================================================
	// Shop Einstellungen auslesen (Получение настроек магазина)
	//=======================================================
	function getShopSetting($feld)
	{
		$sql = $GLOBALS['db']->Query("SELECT $feld FROM " . PREFIX . "_modul_shop");
		$row = $sql->fetchrow();
		$sql->close();
		return $row->$feld;
	}

	//=======================================================
	// Dateiendung ermitteln (Определение типа файла по расширению)
	//=======================================================
	function getEndung($file)
	{
		$Endg = substr(strtolower($file),-4);
		switch($Endg)
		{
			case '.jpg':
			case '.jpe':
			case 'jpeg':
			default :
				$End = 'jpg';
			break;

			case '.png':
				$End = 'png';
			break;

			case '.gif':
				$End = 'gif';
			break;
		}
		return $End;
	}

	function topSeller($type='', $flop='', $limit='')
	{
		$topSeller = array();
		$L = ($limit!='') ? $limit : 10;
		$db_categ = (isset($_REQUEST['categ']) && (int)$_REQUEST['categ'] != '') ? " AND KatId = '".$_REQUEST['categ']."'" : "";
		$AscDesc = ($flop==1) ? 'ASC' : 'DESC';
		$sql = $GLOBALS['db']->Query("SELECT
				Id,
				ArtNr,
				KatId,
				ArtName,
				TextKurz,
				Bild,
				Preis,
				Bestellungen
			FROM
				" . PREFIX . "_modul_shop_artikel
			WHERE
				Aktiv = '1' AND Erschienen <= '".time()."'  $db_categ
			ORDER BY Bestellungen $AscDesc LIMIT $L");
		while($row = $sql->fetchrow())
		{
			$row->Img = "";
			if(file_exists(BASE_DIR . '/modules/shop/thumbnails/shopthumb__' . $this->getShopSetting('Topsellerbilder') . '__' . $row->Bild))
			{
				$row->Img = "<img src=\"../modules/shop/thumbnails/shopthumb__" . $this->getShopSetting('Topsellerbilder') . "__" . $row->Bild . "\" alt=\"\" border=\"\" />";
			} else {
				$type = $this->getEndung($row->Bild);
				$row->Img = "<img src=\"../modules/shop/thumb.php?file=$row->Bild&amp;type=$type&amp;x_width=" . $this->getShopSetting('Topsellerbilder') . "\" alt=\"\" border=\"\" />";
			}


			$row->TextKurz =  $row->Img . substr(strip_tags($row->TextKurz,'<b>,<strong>,<em>,<i>'), 0, 250) . '...';
			$row->Detaillink = 'index.php?module=shop&amp;action=product_detail&amp;product_id=' . $row->Id . '&amp;categ=' . $row->KatId . '&amp;navop=' . getParentShopcateg($row->KatId);
			array_push($topSeller, $row);
		}
		return $topSeller;
	}

	//=======================================================
	// Downloads einlesen (Файлы для скачивания)
	//=======================================================
	function fetchEsdFiles()
	{
		$verzname = BASE_DIR . '/modules/shop/files/';
		$dh = @opendir( $verzname );
		$esds = array();
		while ( @gettype( $datei = @readdir ( $dh )) != @boolean )
		{
			if ( @is_file( "$verzname/$datei" ))
			{
				if ($datei != "." && $datei != ".." && $datei != ".htaccess")
				{
					@array_push($esds, $datei);
				}
			}
		}
		@closedir( $dh );
		return $esds;
	}

	//=======================================================
	// Wandelt eine falsche Eingabe um (10,50 in 10.5)
	//=======================================================
	function kReplace($string)
	{
		return str_replace(',','.', $string);
	}

	function randomVar($c=0)
	{
		$transid = "";
		$chars = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0",
			"a", "A", "b", "B", "c", "C", "d", "D", "e", "E", "f", "F", "g", "G", "h", "H", "i", "I", "j", "J",
			"k", "K", "l", "L", "m", "M", "n", "N", "o", "O", "p", "P", "q", "Q", "r", "R", "s", "S", "t", "T",
			"u", "U", "v", "V", "w", "W", "x", "X", "y", "Y", "z", "Z");
		$ch = ($c!=0) ? $c : 6;
		$count = count($chars) - 1;
		srand((double)microtime() * 1000000);
		for($i = 0; $i < $ch; $i++) {
			$transid .= $chars[rand(0, $count)];
		}
		return(strtoupper($transid));
	}

	//=======================================================
	// Funktion zum umbenennen einer Datei (Функция переименовывания файлов)
	//=======================================================
	function renameFile($file)
	{
		mt_rand();
		$zufall = rand(1,999);
		$rn_file = $zufall . '_' . $file;
		return $rn_file;
	}

	//=======================================================
	// Einen Hersteller auslesen (Выборка производителей)
	//=======================================================
	function fetchManufacturer($Hersteller)
	{
		$sql = $GLOBALS['db']->Query("SELECT Name FROM " . PREFIX . "_modul_shop_hersteller WHERE Id = '$Hersteller' LIMIT 1");
		$row = $sql->fetchrow();
		$sql->close();
		return @$row->Name;
	}

	function displayManufacturer()
	{
		$manufacturer = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_hersteller ORDER BY Name ASC");
		while($row = $sql->fetchrow())
		{
			array_push($manufacturer, $row);
		}
		return $manufacturer;
	}

	function getUnit($Unit)
	{
		$sql = $GLOBALS['db']->Query("SELECT Name FROM " . PREFIX . "_modul_shop_einheiten WHERE Id = '$Unit' LIMIT 1");
		$row = $sql->fetchrow();
		$sql->close();
		return @$row->Name;
	}

	function fetchVatZones()
	{
		$VatZones = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_ust ORDER BY Wert DESC");
		while($row = $sql->fetchrow())
		{
			array_push($VatZones, $row);
		}
		return $VatZones;
	}


	function displayPaymentMethods()
	{
		$paymentMethods = array();
		$sql = $GLOBALS['db']->Query("SELECT Id,Name FROM " . PREFIX . "_modul_shop_zahlungsmethoden ORDER BY Name ASC");
		while($row = $sql->fetchrow())
		{
			array_push($paymentMethods, $row);
		}
		return $paymentMethods;
	}

	function displayShippingMethods()
	{
		$ShippingMethods = array();
		$sql = $GLOBALS['db']->Query("SELECT Id,Name FROM " . PREFIX . "_modul_shop_versandarten ORDER BY Name ASC");
		while($row = $sql->fetchrow())
		{
			array_push($ShippingMethods, $row);
		}
		return $ShippingMethods;
	}

	function displayUnits()
	{
		$Units = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_einheiten ORDER BY Name ASC");
		while($row = $sql->fetchrow())
		{
			array_push($Units, $row);
		}
		return $Units;
	}

	//=======================================================
	// Versand - Zeiten (Сроки доставки)
	//=======================================================
	function shippingTime()
	{
		$shippingTime = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_versandzeit ORDER BY Name ASC");
		while($row = $sql->fetchrow())
		{
			array_push($shippingTime, $row);
		}
		return $shippingTime;
	}

	//=======================================================
	// Steuersдtze (Налоги)
	//=======================================================
	function vatZones($tpl_dir)
	{
		// Neu
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'new')
		{
			$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_shop_ust (Id,Name,Wert) VALUES ('','$_POST[Name]','".$this->kreplace($_POST['Wert'])."')");
		}

		// Speichern
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			foreach($_POST['Name'] as $id => $Name)
			{
				$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_ust
				SET
					Wert = '" . $this->kreplace($_POST['Wert'][$id]) . "',
					Name = '" . $_POST['Name'][$id] . "'
				WHERE
					Id = '$id'");
			}
			// Lцschen
			if(isset($_POST['Del']) && $_POST['Del'] >= 1)
			{
				foreach($_POST['Del'] as $id => $Del)
				{
					$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_shop_ust WHERE Id = '$id'");
				}
			}
		}



		$GLOBALS['tmpl']->assign('vatZones', $this->fetchVatZones());
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_vatzones.tpl'));
	}


	//=======================================================
	// Datei - Downloads fьr einen Artikel (Файл - загрузки для товара)
	//=======================================================
	function esdDownloads($tpl_dir)
	{
		// Neu anlegen
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'new')
		{
			$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_shop_artikel_downloads
			(
				Id,
				ArtId,
				Datei,
				DateiTyp,
				TageNachKauf,
				Bild,
				Titel,
				Beschreibung,
				Position
			) VALUES (
				'',
				'" . @$_REQUEST['Id'] . "',
				'" . @$_POST['Datei'] . "',
				'" . @$_POST['DateiTyp'] . "',
				'" . @$_POST['TageNachKauf'] . "',
				'" . @$_POST['Bild'] . "',
				'" . @$_POST['Titel'] . "',
				'" . @$_POST['Beschreibung'] . "',
				'" . @$_POST['Position'] . "')
			");
		}


		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
		// Speichern
			if(isset($_POST['Del']) && $_POST['Del'] >= 1)
			{
				foreach($_POST['Del'] as $id => $Datei) $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_shop_artikel_downloads WHERE Id = '$id'");
			}

			foreach($_POST['Datei'] as $id => $Datei)
			{
				$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_artikel_downloads
				SET
					Datei = '" . @$_POST['Datei'][$id] . "',
					DateiTyp = '" . @$_POST['DateiTyp'][$id] . "',
					TageNachKauf = '" . @$_POST['TageNachKauf'][$id] . "',
					Bild = '" . @$_POST['Bild'][$id] . "',
					Titel = '" . @$_POST['Titel'][$id] . "',
					Beschreibung = '" . @$_POST['Beschreibung'][$id] . "',
					Position = '" . @$_POST['Position'][$id] . "'
				WHERE Id = '$id'");
			}

		}


		$downloads_full = array();
		$downloads_updates = array();
		$downloads_bugfixes = array();
		$downloads_other = array();

		$sql_full = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_artikel_downloads  WHERE  DateiTyp='full' AND ArtId='$_REQUEST[Id]' ORDER BY Position ASC");
		$sql_updates = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_artikel_downloads  WHERE  DateiTyp='update' AND ArtId='$_REQUEST[Id]' ORDER BY Position ASC");
		$sql_bugfixes = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_artikel_downloads  WHERE  DateiTyp='bugfix' AND ArtId='$_REQUEST[Id]' ORDER BY Position ASC");
		$sql_other = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_artikel_downloads  WHERE  DateiTyp='other' AND ArtId='$_REQUEST[Id]' ORDER BY Position ASC");

		while($row_full = $sql_full->fetchrow()) array_push($downloads_full, $row_full);
		while($row_updates = $sql_updates->fetchrow()) array_push($downloads_updates, $row_updates);
		while($row_bugfixes = $sql_bugfixes->fetchrow()) array_push($downloads_bugfixes, $row_bugfixes);
		while($row_other = $sql_other->fetchrow()) array_push($downloads_other, $row_other);

		$GLOBALS['tmpl']->assign('downloads_full', $downloads_full);
		$GLOBALS['tmpl']->assign('downloads_updates', $downloads_updates);
		$GLOBALS['tmpl']->assign('downloads_bugfixes', $downloads_bugfixes);
		$GLOBALS['tmpl']->assign('downloads_other', $downloads_other);

		$GLOBALS['tmpl']->assign('esds', $this->fetchEsdFiles());
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_product_files.tpl'));
	}

	//=======================================================
	// Dateiupload-Funktion (Функция загрузки файлов)
	//=======================================================
	function uploadFile($maxupload=4000)
	{
		global $_FILES;
		$attach = "";
		define("UPDIR", BASE_DIR . "/attachments/");
		if(isset($_FILES['upfile']) &&  is_array($_FILES['upfile']))
		{
			for($i=0;$i<count($_FILES['upfile']['tmp_name']);$i++)
			{
				if($_FILES['upfile']['tmp_name'][$i] != "")
				{
					$d_name = strtolower(ltrim(rtrim($_FILES['upfile']['name'][$i])));
					$d_name = str_replace(" ","", $d_name);
					$d_tmp = $_FILES['upfile']['tmp_name'][$i];

					$fz = filesize($_FILES['upfile']['tmp_name'][$i]);
					$mz = $maxupload*1024;;

					if($mz >= $fz)
					{
						if(file_exists(UPDIR . $d_name)) $d_name = $this->renameFile($d_name);
						@move_uploaded_file($d_tmp, UPDIR . $d_name);
						$attach[] = $d_name;
					}
				}
			}
			return $attach;
		}
	}

	function showMoney($tpl_dir)
	{

		if(isset($_REQUEST['search']) && $_REQUEST['search'] == 1)
		{
			$ZeitStart = mktime(0,0,1,$_REQUEST['start_Month'],$_REQUEST['start_Day'],$_REQUEST['start_Year']);
			$ZeitEnde  = mktime(23,59,59,$_REQUEST['end_Month'],$_REQUEST['end_Day'],$_REQUEST['end_Year']);
		} else {
			$ZeitStart = mktime(0,0,1,date("m"),date("d")-(date("d")-1),date("Y"));
			$ZeitEnde  = mktime(23,59,59,date("m"),date("d"),date("Y"));
		}

		$ZahlungsId = (isset($_REQUEST['ZahlungsId']) && $_REQUEST['ZahlungsId'] != 'egal') ? "AND ZahlungsId = '$_REQUEST[ZahlungsId]'" : "";
		$VersandId = (isset($_REQUEST['VersandId']) && $_REQUEST['VersandId'] != 'egal') ? "AND VersandId = '$_REQUEST[VersandId]'" : "";
		$Benutzer = (isset($_REQUEST['Benutzer']) && $_REQUEST['Benutzer'] != '') ? "AND Benutzer = '$_REQUEST[Benutzer]'"  : "";

		$sql = $GLOBALS['db']->Query("SELECT SUM(Gesamt) AS GesamtUmsatz FROM " . PREFIX . "_modul_shop_bestellungen WHERE (Status = 'ok' || Status = 'ok_send') AND (Datum BETWEEN $ZeitStart AND $ZeitEnde) $ZahlungsId $VersandId $Benutzer");
		$row = $sql->fetchrow();

		$sql2 = $GLOBALS['db']->Query("SELECT SUM(Gesamt) AS GesamtUmsatz FROM " . PREFIX . "_modul_shop_bestellungen WHERE Datum BETWEEN $ZeitStart AND $ZeitEnde $ZahlungsId $VersandId $Benutzer");
		$row2 = $sql2->fetchrow();

		$sql3 = $GLOBALS['db']->Query("SELECT SUM(Gesamt) AS GesamtUmsatz FROM " . PREFIX . "_modul_shop_bestellungen WHERE (Status = 'wait') AND (Datum BETWEEN $ZeitStart AND $ZeitEnde) $ZahlungsId $VersandId $Benutzer");
		$row3 = $sql3->fetchrow();

		$sql4 = $GLOBALS['db']->Query("SELECT SUM(Gesamt) AS GesamtUmsatz FROM " . PREFIX . "_modul_shop_bestellungen WHERE (Status = 'progress') AND (Datum BETWEEN $ZeitStart AND $ZeitEnde) $ZahlungsId $VersandId $Benutzer");
		$row4 = $sql4->fetchrow();

		$sql5 = $GLOBALS['db']->Query("SELECT SUM(Gesamt) AS GesamtUmsatz FROM " . PREFIX . "_modul_shop_bestellungen WHERE (Status = 'failed') AND (Datum BETWEEN $ZeitStart AND $ZeitEnde) $ZahlungsId $VersandId $Benutzer");
		$row5 = $sql5->fetchrow();

		$row->GesamtUmsatz = number_format($row->GesamtUmsatz,'2',',','.');
		$row->GesamtUmsatzAlle = number_format($row2->GesamtUmsatz,'2',',','.');
		$row->GesamtUmsatzWartend = number_format($row3->GesamtUmsatz,'2',',','.');
		$row->GesamtUmsatzBearbeitung = number_format($row4->GesamtUmsatz,'2',',','.');
		$row->GesamtFehlgeschlagen = number_format($row5->GesamtUmsatz,'2',',','.');

		$GLOBALS['tmpl']->assign('paymentMethods', $this->displayPaymentMethods());
		$GLOBALS['tmpl']->assign('shippingMethods', $this->displayShippingMethods());

		$GLOBALS['tmpl']->assign('row', $row);
		$GLOBALS['tmpl']->assign('ZeitStart', $ZeitStart);
		$GLOBALS['tmpl']->assign('ZeitEnde', $ZeitEnde);
		$GLOBALS['tmpl']->assign('currency', $this->getShopSetting('WaehrungSymbol'));
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_money.tpl'));
	}

	//=======================================================
	//=======================================================
	// Bestellungen (ЗАКАЗЫ)
	//=======================================================
	//=======================================================
	function ZahlungsArt($id)
	{
		$sql = $GLOBALS['db']->Query("SELECT Name FROM " . PREFIX . "_modul_shop_zahlungsmethoden WHERE Id = '$id'");
		$row = $sql->fetchrow();
		return $row->Name;
	}


	function VersandArt($id)
	{
		$sql = $GLOBALS['db']->Query("SELECT Name FROM " . PREFIX . "_modul_shop_versandarten WHERE Id = '$id'");
		@$row = $sql->fetchrow();
		return @$row->Name;
	}


	function getUserName($id)
	{
		$sql = $GLOBALS['db']->Query("SELECT Vorname,Nachname FROM " . PREFIX . "_users WHERE Id = '$id'");
		$row = $sql->fetchrow();
		return @substr($row->Vorname,0,1) . '. ' . @$row->Nachname;
	}

	function mailPage($tpl_dir,$orderid)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			$globals = new Globals;
			$SystemMail = $GLOBALS['globals']->cp_settings("Mail_Absender");
			$SystemMailName = $GLOBALS['globals']->cp_settings("Mail_Name");
			$GLOBALS['globals']->cp_mail($_REQUEST['mto'], stripslashes($_POST['Message']), $_POST['Subject'], $SystemMail, $SystemMailName, "text", $this->uploadFile());
			echo '<script>window.close();</script>';
		}
		else
		{
			$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_bestellungen WHERE Id = '$orderid'");
			$row = $sql->fetcharray();
			$row['ProductOrdersMailPreBody'] = $GLOBALS['config_vars']['ProductOrdersMailPreBody'];
			$row['ProductOrdersMailPreBody'] = str_replace("%N%", "\n", $row['ProductOrdersMailPreBody']);

			$UploadSize = @ini_get('upload_max_filesize');
			$PostSize = @ini_get('post_max_size');

			if(strtolower(@ini_get('file_uploads'))=='off' || @ini_get('file_uploads')==0)	$GLOBALS['tmpl']->assign('no_uploads', 1);

			$GLOBALS['tmpl']->assign('UploadSize', (($PostSize < $UploadSize) ? $PostSize : $UploadSize) );
			$GLOBALS['tmpl']->assign('row', $row);
			$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_mailpage.tpl'));
		}
	}

	function getArticleName($id)
	{
		$sql = $GLOBALS['db']->Query("SELECT ArtName FROM " . PREFIX . "_modul_shop_artikel WHERE Id = '$id'");
		$row = $sql->fetchrow();
		return (@strlen($row->ArtName)>60) ? @substr($row->ArtName,0,60) . '...' : @$row->ArtName;
	}


	function varCategory($id)
	{
		$sql = $GLOBALS['db']->Query("SELECT Name FROM " . PREFIX . "_modul_shop_varianten_kategorien WHERE Id = '$id'");
		$row = $sql->fetchrow();
		return @$row->Name;
	}

	function splitVars($vars, $out = '')
	{
		$v = explode(',', $vars);
		if(count($v)>=1)
		{
			foreach($v as $var)
			{
				if($var)
				{
					$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_varianten WHERE Id = '$var'");
					$row = $sql->fetchrow();
					$out .= '<b>' . $this->varCategory($row->KatId) . '</b>: ' . stripslashes($row->Name) . ' ' . $row->Operant . number_format($row->Wert,'2',',','.') . '<br />';
				}
			}
		}
		return $out;
	}


	//=======================================================
	// Alle Bestellungen (Все заказы)
	//=======================================================
	function showOrders($tpl_dir)
	{
		$Status  = "";
		$Status_nav = "";
		$Query = "";
		$Query_nav = "";
		$Betrag = "";
		$Betrag_nav = "";
		$ZahlungsId = "";
		$ZahlungsId_nav = "";
		$VersandId = "";
		$VersandId_nav = "";
		$Order = "ORDER BY Id DESC";
		$Order_nav = "";


		// Schnellstatus
		if(isset($_REQUEST['StatusOrder']) && $_REQUEST['StatusOrder'] != 'nothing')
		{
			reset ($_POST);
			while(list($key,$val) = each($_POST))
			{
				if (substr($key,0,7)=="orders_")
				{
					$aktid = str_replace("orders_","",$key);
					$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_bestellungen SET Status='$_REQUEST[StatusOrder]' WHERE Id = '$aktid'");
				}
			}
			header("Location:index.php?do=modules&action=modedit&mod=shop&moduleaction=showorders&cp=" . SESSION);
			exit;
		}

		// Sortierung
		if(isset($_REQUEST['Order']) && $_REQUEST['Order'] != '')
		{
			switch($_REQUEST['Order'])
			{
				case 'IdAsc': $Order = "ORDER BY Id ASC"; $Order_nav = "&Order=IdAsc"; break;
				case 'IdDesc': $Order = "ORDER BY Id DESC"; $Order_nav = "&Order=IdDesc"; break;
				case 'PaymentIdAsc': $Order = "ORDER BY ZahlungsId ASC"; $Order_nav = "&Order=PaymentIdAsc"; break;
				case 'PaymentIdDesc': $Order = "ORDER BY ZahlungsId Desc"; $Order_nav = "&Order=PaymentIdDesc"; break;
				case 'ShippingIdAsc': $Order = "ORDER BY VersandId ASC"; $Order_nav = "&Order=ShippingIdAsc"; break;
				case 'ShippingIdDesc': $Order = "ORDER BY VersandId Desc"; $Order_nav = "&Order=ShippingIdDesc"; break;
				case 'CustomerAsc': $Order = "ORDER BY Benutzer ASC"; $Order_nav = "&Order=CustomerAsc"; break;
				case 'CustomerDesc': $Order = "ORDER BY Benutzer Desc"; $Order_nav = "&Order=CustomerDesc"; break;
				case 'SummAsc': $Order = "ORDER BY Gesamt ASC"; $Order_nav = "&Order=SummAsc"; break;
				case 'SummDesc': $Order = "ORDER BY Gesamt Desc"; $Order_nav = "&Order=SummDesc"; break;
			}
		}

		if(isset($_REQUEST['start_Day']))
		{
			$ZeitStart = mktime(0,0,0,$_REQUEST['start_Month'],$_REQUEST['start_Day'],$_REQUEST['start_Year']);
			$ZeitStart_nav = "&start_Month=$_REQUEST[start_Month]&start_Day=$_REQUEST[start_Day]&start_Year=$_REQUEST[start_Year]";
		} else {
			$ZeitStart = (isset($_REQUEST['date']) && $_REQUEST['date']=='today') ? mktime(0,0,1,date("m"),date("d"),date("Y")) : mktime(0,0,1,date("m")-1,date("d"),date("Y"));
			$ZeitStart_nav = (isset($_REQUEST['date']) && $_REQUEST['date']=='today') ? "&amp;date=today" : "";
		}

		if(isset($_REQUEST['end_Day']))
		{
			$ZeitEnde = mktime(23,59,59,$_REQUEST['end_Month'],$_REQUEST['end_Day'],$_REQUEST['end_Year']);
			$ZeitEnde_nav = "&end_Month=$_REQUEST[end_Month]&end_Day=$_REQUEST[end_Day]&end_Year=$_REQUEST[end_Year]";
		} else {
			$ZeitEnde = mktime(23,59,59,date("m"),date("d"),date("Y"));
			$ZeitEnde_nav = "";
		}

		if(isset($_REQUEST['Status']) && $_REQUEST['Status'] != 'egal')
		{
			$Status = " AND Status = '$_REQUEST[Status]'";
			$Status_nav = "&status=$_REQUEST[Status]";
		}

		if(isset($_REQUEST['Query']) && $_REQUEST['Query'] != '')
		{
			$Query = " AND (Id = '$_REQUEST[Query]' || TransId = '$_REQUEST[Query]' || Benutzer = '$_REQUEST[Query]' || Bestell_Email = '$_REQUEST[Query]' || Liefer_Nachname = '$_REQUEST[Query]' || Rech_Nachname = '$_REQUEST[Query]') ";
			$Query_nav = "&Query=$_REQUEST[Query]";
		}

		if(isset($_REQUEST['price_start']) && $_REQUEST['price_start'] != '' && isset($_REQUEST['price_end']) && $_REQUEST['price_end'] != '')
		{
			$Betrag = " AND (Gesamt BETWEEN ". $this->kreplace($_REQUEST['price_start']) ." AND ". $this->kreplace($_REQUEST['price_end']) .")";
			$Betrag_nav = "&price_start=$_REQUEST[price_start]&price_end=$_REQUEST[price_end]";
		}

		if(isset($_REQUEST['ZahlungsId']) && $_REQUEST['ZahlungsId'] != 'egal')
		{
			$ZahlungsId = " AND ZahlungsId = '$_REQUEST[ZahlungsId]' ";
			$ZahlungsId_nav = "&ZahlungsId=$_REQUEST[ZahlungsId]";
		}

		if(isset($_REQUEST['VersandId']) && $_REQUEST['VersandId'] != 'egal')
		{
			$VersandId = " AND VersandId = '$_REQUEST[VersandId]' ";
			$VersandId_nav = "&VersandId=$_REQUEST[VersandId]";
		}

		$ZeitSpanne = (isset($_REQUEST['search']) && $_REQUEST['search'] == 1 || isset($_REQUEST['date'])) ? "AND (Datum BETWEEN $ZeitStart AND $ZeitEnde) " : "";

		$Orders = array();
		$sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_shop_bestellungen WHERE Id != '0' $VersandId $ZahlungsId $Betrag $Query $Status $ZeitSpanne");
		$num = $sql->numrows();

		if(isset($_REQUEST['recordset']) && $_REQUEST['recordset'] != '')
		{
			$limit = $_REQUEST['recordset'];
			$limit_nav = "&recordset=$_REQUEST[recordset]";
		} else {
			$limit = $this->_orders_limit;
			$limit_nav = "";
		}

		@$seiten = @ceil($num / $limit);
		$start = prepage() * $limit - $limit;

		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_bestellungen WHERE Id != '0' $VersandId $ZahlungsId $Betrag $Query $Status $ZeitSpanne $Order LIMIT $start,$limit");
		while($row = $sql->fetcharray())
		{
			$row['ArtikelS'] = "";
			$row['ArtikelSVars'] = "";
			$row['UserId'] = $row['Benutzer'];
			$Artikel = unserialize($row['Artikel']);
			$ArtikelVars = unserialize($row['Artikel_Vars']);
			if(is_array($Artikel))
			{
				foreach ($Artikel as $key => $value)
				{
					$row['ArtikelS'] .= "<br />$value x <a href=javascript:void(0) onclick=cp_pop(\'index.php?do=modules&action=modedit&mod=shop&moduleaction=edit_product&cp=".SESSION."&pop=1&Id=$key\',\'980\',\'740\',\'1\',\'edit_product\');>".$this->getArticleName($key)."</a>";
					if(is_array($ArtikelVars))
					{
						foreach ($ArtikelVars as $keys => $values)
						{
							if($key == $keys) $row['ArtikelS'] .= "<br />" . $this->splitVars($values);
						}
					} // Ende Varianten
				} // Ende enthaltene Artikel
			}

			$row['N'] = $this->getUserName($row['Benutzer']);
			$row['Zahlart'] = $this->ZahlungsArt($row['ZahlungsId']);
			$row['VersandArt'] = $this->VersandArt($row['VersandId']);
			$row['Gesamt'] = number_format($row['Gesamt'],'2',',','.');
			$row['BenId'] = (is_numeric($row['Benutzer'])) ? $row['Benutzer'] : '';
			$row['Benutzer'] = (is_numeric($row['Benutzer']) && ($row['Benutzer']>0) ) ? '<a href="javascript:void(0);" onclick="cp_pop(\'index.php?do=user&action=edit&Id='.$row['Benutzer'].'&cp='.SESSION.'&pop=1\')">' . (  strlen($this->getUserName($row['Benutzer']))< 3 ?  $row['Benutzer'] : $this->getUserName($row['Benutzer'])) . '</a>' : '<b>'.$row['Benutzer'].'</b>';
			$row['BenutzerMail'] = '<a href="javascript:void(0);" onclick="cp_pop(\'index.php?do=modules&action=modedit&mod=shop&moduleaction=mailpage&OrderId='.$row['Id'].'&cp='.SESSION.'&pop=1\')">E-Mail</a>';


			array_push($Orders, $row);
		}

		$page_nav = pagenav($seiten, prepage()," <a class=\"pnav\" href=\"index.php?do=modules&action=modedit&mod=shop&moduleaction=showorders&cp=".SESSION."&page={s}$Order_nav$limit_nav$VersandId_nav$ZahlungsId_nav$Query_nav$Status_nav$ZeitStart_nav$ZeitEnde_nav\">{t}</a> ");
		if($num > $limit) $GLOBALS['tmpl']->assign('page_nav', $page_nav);

		$GLOBALS['tmpl']->assign('Orders', $Orders);
		$GLOBALS['tmpl']->assign('ZeitStart', $ZeitStart);
		$GLOBALS['tmpl']->assign('ZeitEnde', $ZeitEnde);
		$GLOBALS['tmpl']->assign('paymentMethods', $this->displayPaymentMethods());
		$GLOBALS['tmpl']->assign('shippingMethods', $this->displayShippingMethods());
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_orders.tpl'));
	}

	// Einzelne Bestellung
	function showOrder($tpl_dir,$id)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{

			$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_bestellungen WHERE Id = '$id'");
			$row = $sql->fetchrow();

			$db_gesamt = (isset($_REQUEST['Gesamt']) && $_REQUEST['Gesamt'] != '') ?  ",Gesamt = '".$this->kreplace($_REQUEST['Gesamt'])."'" : "";

			$Bez = ($_POST['Status'] == 'ok' || $_POST['Status'] == 'ok_send') ? time() : '';
			$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_bestellungen
			SET
				DatumBezahlt = '$Bez',
				NachrichtBenutzer = '$_POST[NachrichtBenutzer]',
				NachrichtAdmin = '$_POST[NachrichtAdmin]',
				Status = '$_POST[Status]',
				RechnungText = '$_POST[Text]',
				RechnungHtml = '$_POST[RechnungHtml]'
				$db_gesamt
			WHERE Id = '$id'");

			// Artikel - Bestand verringern
			$Artikel = unserialize($row->Artikel);
			$ArtikelVars = unserialize($row->Artikel_Vars);
			if(is_array($Artikel))
			{
				foreach ($Artikel as $key => $value)
				{
					$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_artikel SET Lager=Lager-$value WHERE Id = '$key'");
				}
			}

			// Mail an Kдufer senden
			if(isset($_REQUEST['SendMail']) && $_REQUEST['SendMail'] == 1)
			{
				$globals = new Globals;
				$SystemMail = $GLOBALS['globals']->cp_settings("Mail_Absender");
				$SystemMailName = $GLOBALS['globals']->cp_settings("Mail_Name");

				$_POST['Message'] = str_replace("%%ORDER_NUMBER%%", $row->Id, $_POST['Message']);

				$GLOBALS['globals']->cp_mail($row->Bestell_Email, stripslashes($_POST['Message'] . $_POST['Text']), $_POST['Subject'], $SystemMail, $SystemMailName, "text", $this->uploadFile());
			}
			echo '<script>window.opener.location.reload();window.close();</script>';

		} else {
			$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_bestellungen WHERE Id = '$id'");
			$row = $sql->fetcharray();

			$oFCKeditor->BasePath = SOURCE_DIR . "admin/editor/" ;
			$oFCKeditor = new FCKeditor('RechnungHtml');
			$oFCKeditor->Height = '300';
			$oFCKeditor->ToolbarSet = 'Simple';
			$oFCKeditor->Value= $row['RechnungHtml'];
			$html = $oFCKeditor->Create();

			$row['IPC'] = $this->gethost($row['Ip']);
			$GLOBALS['tmpl']->assign('row', $row);
			$GLOBALS['tmpl']->assign('html', $html);
			$GLOBALS['tmpl']->assign('text', $row['RechnungText']);
			$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_edit_order.tpl'));
		}
	}

	function markFailed($tpl_dir,$id)
	{
		$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_bestellungen SET Status = 'failed' WHERE Id = '$id'");
		echo '<script>window.opener.location.reload();window.close();</script>';
	}

	//=======================================================
	// Kategorien (Категории)
	//=======================================================

	// Neue Kategorie
	function newCateg($tpl_dir,$elter)
	{
		// Speichern
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			$DbImage = '';
			$upload_dir = BASE_DIR . '/modules/shop/uploads/';

			if(isset($_FILES) && $_FILES['Bild']['tmp_name'] != '')
			{
				$name = str_replace(array(' ', '+','-'),'_',strtolower($_FILES['Bild']['name']));
				$name = ereg_replace("_+", "_", $name);
				$temp = $_FILES['Bild']['tmp_name'];
				$endung = strtolower(substr($name, -3));
				$fupload_name = $name;

				if(in_array($_FILES['Bild']['type'], $this->_allowed_images))
				{
					// Wenn Bild existiert, Bild umbenennen
					if(file_exists($upload_dir . $fupload_name))
					{
						$fupload_name = $this->renameFile($fupload_name);
						$name = $fupload_name;
					}

					// Bild hochladen
					@move_uploaded_file($_FILES['Bild']['tmp_name'], $upload_dir . $fupload_name);
					@chmod($upload_dir . $fupload_name, 0777);

					$DbImage = $fupload_name;
				}
			}
			$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_shop_kategorie
			(
				Id,
				Elter,
				KatName,
				KatBeschreibung,
				Rang,
				Bild
			) VALUES (
				'',
				'" . $_POST['Elter'] . "',
				'" . $_POST['KatName'] . "',
				'" . $_POST['KatBeschreibung'] . "',
				'" . $_POST['Rang'] . "',
				'" . $DbImage . "'
			)");
			echo '<script>window.opener.location.reload(); window.close();</script>';
		}

		$GLOBALS['tmpl']->assign('ProductCategs', $this->fetchShopNavi(1));
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_newcateg.tpl'));
	}

	// Bearbeiten
	function editCateg($tpl_dir,$id)
	{
		// Speichern
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			$DbImage = '';
			$upload_dir = BASE_DIR . '/modules/shop/uploads/';

			if(isset($_REQUEST['ImgDel']) && $_REQUEST['ImgDel'] == 1)
			{
				@unlink($upload_dir . $_REQUEST['Old']);
				$DbImage = ", Bild = ''";
			}

			if(isset($_FILES) && $_FILES['Bild']['tmp_name'] != '')
			{
				$name = str_replace(array(' ', '+','-'),'_',strtolower($_FILES['Bild']['name']));
				$name = ereg_replace("_+", "_", $name);
				$temp = $_FILES['Bild']['tmp_name'];
				$endung = strtolower(substr($name, -3));
				$fupload_name = $name;

				if(in_array($_FILES['Bild']['type'], $this->_allowed_images))
				{
					// Wenn Bild existiert, Bild umbenennen
					@unlink($upload_dir . $_REQUEST['Old']);
					if(file_exists($upload_dir . $fupload_name))
					{
						$fupload_name = $this->renameFile($fupload_name);
						$name = $fupload_name;
					}

					// Bild hochladen
					@move_uploaded_file($_FILES['Bild']['tmp_name'], $upload_dir . $fupload_name);
					@chmod($upload_dir . $fupload_name, 0777);

					$DbImage = ", Bild = '$fupload_name'";
				}
			}

			$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_kategorie
			SET
				KatName = '" . $_POST['KatName'] . "',
				KatBeschreibung = '" . @$_POST['KatBeschreibung'] . "',
				Rang = '" . @$_POST['Rang'] . "'
				$DbImage
			WHERE Id = '$id'");
			echo '<script>window.opener.location.reload(); window.close();</script>';
		}

		// Anzeigen
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_kategorie WHERE Id = '$id'");
		$row = $sql->fetchrow();

		$row->Bild = ($row->Bild != "" && file_exists(BASE_DIR . "/modules/shop/uploads/$row->Bild")) ? $row->Bild : "";

		$GLOBALS['tmpl']->assign('row', $row);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_editcateg.tpl'));
	}

	// Lцschaufruf
	function delCategCall($id)
	{
		$this->delCateg($id);
		header("Location:index.php?do=modules&action=modedit&mod=shop&moduleaction=product_categs&cp=" . SESSION);
		exit;
	}

	// Lцschfunktion von Kategorien
	function delCateg($id)
	{
		$query = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_kategorie  WHERE Elter = '$id'");

		while ($item = $query->fetchrow())
		{
			$sql = $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_shop_kategorie WHERE Id = '$item->Id'");
			$this->delCateg($item->Id);
		}

		$query = $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_shop_kategorie WHERE Id = '$id'");
	}

	function productCategs($tpl_dir)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			foreach($_REQUEST['KatName'] as $id => $KatName)
			{
				$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_kategorie
				SET
					KatName = '" . $_REQUEST['KatName'][$id] . "',
					Rang = '" . $_REQUEST['Rang'][$id] . "'
				WHERE
					Id = '$id'");
			}
		}

		$categs = array();
		$GLOBALS['tmpl']->assign('ProductCategs', $this->getCategoriesSimple(0, '', $categs,'0'));
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_productcategs.tpl'));
	}


	//=======================================================
	// Gutschein - Codes (Купоны на скидку)
	//=======================================================
	function getOrderDate($id)
	{
		$sql = $GLOBALS['db']->Query("SELECT Datum FROM " . PREFIX . "_modul_shop_bestellungen WHERE Id = '$id'");
		$row = $sql->fetchrow();
		return @$row->Datum;
	}

	// Neuer Code
	function couponCodesNew($tpl_dir)
	{
		$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_shop_gutscheine
		(
			Id,
			Code,
			Prozent,
			Mehrfach,
			AlleBenutzer,
			GueltigVon,
			GueltigBis
		) VALUES (
			'',
			'" . $_POST['Code'] . "',
			'" . $this->kReplace($_POST['Prozent']) . "',
			'" . $_POST['Mehrfach'] . "',
			'" . $_POST['AlleBenutzer'] . "',
			'" . @mktime(0,0,0,date("m"),date("d")-1,date("Y")) . "',
			'" . @mktime(23,59,59,date("m")+1,date("d"),date("Y")) . "'
		)");
		header("Location:index.php?do=modules&action=modedit&mod=shop&moduleaction=couponcodes&cp=" . SESSION);
		exit;
	}

	// Anzeigen
	function couponCodes($tpl_dir)
	{
		// Lцschen
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			if(isset($_POST['Del']) && $_POST['Del'] >= 1)
			{
				foreach($_POST['Del'] as $id => $Del)
				{
					$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_shop_gutscheine WHERE Id = '$id'");
				}
			}
			// Speichern
			foreach($_POST['Code'] as $id => $Code)
			{
				if(@ereg("([0-9]{2})([.])([0-9]{2})([.])([0-9]{4})", $_POST['GueltigVon'][$id]) &&
					@ereg("([0-9]{2})([.])([0-9]{2})([.])([0-9]{4})", $_POST['GueltigBis'][$id]))
				{
					$gueltig_von = explode('.', $_POST['GueltigVon'][$id]);
					$gueltig_bis = explode('.', $_POST['GueltigBis'][$id]);

					$gvon = @mktime(0,0,0,$gueltig_von[1],$gueltig_von[0],$gueltig_von[2]);
					$gbis = @mktime(23,59,59,$gueltig_bis[1],$gueltig_bis[0],$gueltig_bis[2]);

					$DB_von_bis = ",GueltigVon = '" . $gvon . "', GueltigBis = '" . $gbis . "' ";
				} else {
					$DB_von_bis = "";
				}

				$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_gutscheine SET
					Code = '" . $_POST['Code'][$id] . "',
					Prozent = '" . $this->kReplace($_POST['Prozent'][$id]) . "',
					Mehrfach = '" . $_POST['Mehrfach'][$id] . "',
					AlleBenutzer = '" . $_POST['AlleBenutzer'][$id] . "'
					$DB_von_bis
				WHERE
					Id = '$id'
					");
			}
		}

		// Auslesen
		$couponCodes = array();
		$sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_shop_gutscheine");
		$num = $sql->numrows();

		$limit = $this->_coupon_limit;
		@$seiten = @ceil($num / $limit);
		$start = prepage() * $limit - $limit;

		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_gutscheine LIMIT $start,$limit");

		while($row = $sql->fetchrow())
		{
			$row->BIdLink = '';
			$BestellIds = explode(',', $row->BestellId);
			foreach($BestellIds as $BId)
			{
				if($BId != '') $row->BIdLink .= '<a href=\'javascript:void(0);\' onclick=cp_pop(\'index.php?do=modules&action=modedit&mod=shop&moduleaction=showorder&cp='.SESSION.'&Id='.$BId.'&pop=1\',\'980\',\'740\',\'1\',\'show_order\')>'.date("d.m.Y, H:i",$this->getOrderDate($BId)).'</a><br />';
			}

			$row->GueltigVon = date('d.m.Y', $row->GueltigVon);
			$row->GueltigBis = date('d.m.Y', $row->GueltigBis);
			array_push($couponCodes, $row);
		}

		$page_nav = pagenav($seiten, prepage()," <a class=\"pnav\" href=\"index.php?do=modules&action=modedit&mod=shop&moduleaction=couponcodes&cp=".SESSION."&page={s}\">{t}</a> ");
		if($num > $limit) $GLOBALS['tmpl']->assign('page_nav', $page_nav);

		$GLOBALS['tmpl']->assign('randomVar', $this->randomVar());
		$GLOBALS['tmpl']->assign('couponCodes', $couponCodes);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_couponcodes.tpl'));
	}


	//=======================================================
	// Produkte anzeigen (Последние поступления)
	//=======================================================
	function lastArticles()
	{
		$limit = 10;

		$dbextra = '';
		$dbextra_n = '';
		$manufacturer = '';
		$manufacturer_n = '';
		$product_query = '';
		$product_query_n = '';
		$price_query = '';
		$price_query_n = '';
		$product_categ = '';
		$product_categ_n = '';
		$recordset_n = '';
		$active = '';
		$active_n = '';
		$lager = '';
		$lager_n = '';
		$best = '';
		$best_n = '';
		$angebot = '';
		$angebot_n = '';

		if(isset($_REQUEST['recordset']) && is_numeric($_REQUEST['recordset']))
		{
			$limit = $_REQUEST['recordset'];
			$recordset_n = "&amp;recordset=$_REQUEST[recordset]";
		}

		if(isset($_REQUEST['categ']) && is_numeric($_REQUEST['categ']))
		{
			$dbextra = " AND (KatId = '" . (int)$_REQUEST['categ'] . "'
			OR
				a.KatId_Multi like '%," . (int)$_REQUEST['categ'] . ",%'
			OR
				a.KatId_Multi like '%," . (int)$_REQUEST['categ'] . "'
			OR
				a.KatId_Multi like '" . (int)$_REQUEST['categ'] . ",%'
				)";
			$dbextra_n = "&amp;categ=$_REQUEST[categ]";
		}

		if(isset($_REQUEST['manufacturer']) && is_numeric($_REQUEST['manufacturer']))
		{
			$manufacturer = " AND a.Hersteller = '$_REQUEST[manufacturer]'";
			$manufacturer_n = "&amp;manufacturer=$_REQUEST[manufacturer]";
		}

		if(isset($_REQUEST['product_query']) && $_REQUEST['product_query'] != '')
		{
			$product_query = " AND (a.ArtNr = '$_REQUEST[product_query]' OR a.ArtName LIKE '%$_REQUEST[product_query]%' OR a.TextKurz LIKE '%$_REQUEST[product_query]%')";
			$product_query_n = "&amp;product_query=" . urlencode($_REQUEST['product_query']);
		}

		if(isset($_REQUEST['price_start']) && is_numeric($_REQUEST['price_start']) && isset($_REQUEST['price_end']) && is_numeric($_REQUEST['price_end']) && $_REQUEST['price_start'] >= 0 && $_REQUEST['price_end'] >= 0 && $_REQUEST['price_start'] < $_REQUEST['price_end'])
		{
			$price_query = " AND (a.Preis BETWEEN $_REQUEST[price_start] AND $_REQUEST[price_end])";
			$price_query_n = "&amp;price_start=$_REQUEST[price_start]&amp;price_end=$_REQUEST[price_end]";
		}

		if(isset($_REQUEST['product_categ']) && is_numeric($_REQUEST['product_categ']))
		{
			$product_categ = " AND a.KatId = '$_REQUEST[product_categ]'";
			$product_categ_n = "&amp;product_categ=$_REQUEST[product_categ]";
		}

		if(isset($_REQUEST['active']) && $_REQUEST['active'] != '' && $_REQUEST['active'] != 'all')
		{
			$active = " AND a.Aktiv = '$_REQUEST[active]'";
			$active_n = "&amp;active=$_REQUEST[active]";
		}

		if(isset($_REQUEST['Lager']) && $_REQUEST['Lager'] != '' && $_REQUEST['Lager'] != 'egal')
		{
			$lager = " AND a.Lager < '$_REQUEST[Lager]'";
			$lager_n = "&amp;Lager=$_REQUEST[Lager]";
		}

		if(isset($_REQUEST['Bestellungen']) && $_REQUEST['Bestellungen'] != '' && $_REQUEST['Bestellungen'] != 'egal')
		{
			$best = " AND a.Bestellungen < '$_REQUEST[Bestellungen]'";
			$best_n = "&amp;Bestellungen=$_REQUEST[Bestellungen]";
		}

		if(isset($_REQUEST['Angebot']) && $_REQUEST['Angebot'] != '' && $_REQUEST['Angebot'] != 'egal')
		{
			$angebot = " AND a.Angebot = '1'";
			$angebot_n = "&amp;Angebot=1";
		}

		// Sortierung
		$db_sort = "ORDER BY a.Id DESC";
		$navi_sort = "";

		if(isset($_REQUEST['sort']) && $_REQUEST['sort'] != '')
		{
			switch($_REQUEST['sort'])
			{
				case 'NameAsc':
					$db_sort = "ORDER BY a.ArtName ASC";
					$navi_sort = "&sort=NameAsc";
				break;

				case 'NameDesc':
					$db_sort = "ORDER BY a.ArtName DESC";
					$navi_sort = "&sort=NameDesc";
				break;

				case 'PriceAsc':
					$db_sort = "ORDER BY a.Preis ASC";
					$navi_sort = "&sort=PriceAsc";
				break;

				case 'PriceDesc':
					$db_sort = "ORDER BY a.Preis DESC";
					$navi_sort = "&sort=PriceDesc";
				break;

				case 'LagerAsc':
					$db_sort = "ORDER BY a.Lager ASC";
					$navi_sort = "&sort=LagerAsc";
				break;

				case 'LagerDesc':
					$db_sort = "ORDER BY a.Lager DESC";
					$navi_sort = "&sort=LagerDesc";
				break;

				case 'GekauftAsc':
					$db_sort = "ORDER BY a.Bestellungen ASC";
					$navi_sort = "&sort=GekauftAsc";
				break;

				case 'GekauftDesc':
					$db_sort = "ORDER BY a.Bestellungen DESC";
					$navi_sort = "&sort=GekauftDesc";
				break;

				case 'PositionAsc':
					$db_sort = "ORDER BY a.PosiStartseite ASC";
					$navi_sort = "&sort=PositionAsc";
				break;

				case 'PositionDesc':
					$db_sort = "ORDER BY a.PosiStartseite DESC";
					$navi_sort = "&sort=PositionDesc";
				break;
			}
		}

		$shopitems = array();

		$sql = $GLOBALS['db']->Query("SELECT
			a.Id
		FROM
			" . PREFIX . "_modul_shop_artikel as a
		WHERE
			a.Id != '0' $angebot $best $lager $active $product_categ $price_query $product_query $dbextra $manufacturer ORDER BY a.Id DESC");
		$num = $sql->numrows();


		$limit = $limit;
		@$seiten = @ceil($num / $limit);
		$start = prepage() * $limit - $limit;

		$q = "SELECT
			a.*
		FROM
			" . PREFIX . "_modul_shop_artikel as a
		WHERE
			a.Id != '0' $angebot $best $lager $active $product_categ $price_query $product_query $dbextra $manufacturer $db_sort  LIMIT $start,$limit";
		$sql = $GLOBALS['db']->Query($q);

		while($row = $sql->fetchrow())
		{
			$row->NavOp = getParentShopcateg($row->KatId);
			$this->globalProductInfo($row);

			$sql_a = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_shop_artikel_kommentare WHERE ArtId = '" . $row->Id . "'");
			$num_a = $sql_a->numrows();

			$row->comments = $num_a;

			array_push($shopitems, $row);
		}

		$the_nav_title = '';
		if(isset($_REQUEST['categ']) && is_numeric($_REQUEST['categ']))
		{
			$the_nav = $this->getNavigationPath((int)$_REQUEST['categ'], '', '1', (int)$_REQUEST['navop']);
			define("TITLE_EXTRA", strip_tags($the_nav));
			$GLOBALS['tmpl']->assign('topnav', $this->getNavigationPath((int)$_REQUEST['categ'], '', '1', (int)$_REQUEST['navop']));
		}

		$page_nav = pagenav($seiten, prepage()," <a class=\"pnav\" href=\"index.php?do=modules&action=modedit&mod=shop&moduleaction=products&cp=".SESSION."$navi_sort$angebot_n$best_n$lager_n$active_n$recordset_n$product_categ_n$price_query_n$product_query_n$dbextra_n$manufacturer_n&amp;page={s}\">{t}</a> ");
		if($num > $limit) $GLOBALS['tmpl']->assign('page_nav', $page_nav);
		return $shopitems;
	}


	// Rezensionen bearbeiten
	function editComments($tpl_dir)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub']=='save')
		{

			foreach($_POST['Titel'] as $id => $Titel)
			{
				$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_artikel_kommentare
					SET
						Titel = '" . $_POST['Titel'][$id] . "',
						Kommentar = '" . $_POST['Kommentar'][$id] . "',
						Wertung = '" . (($_POST['Wertung'][$id]<1 || $_POST['Wertung'][$id]>5) ? 3 : $_POST['Wertung'][$id]) . "',
						Publik = '" . $_POST['Publik'][$id] . "'
				 WHERE Id = '$id'");
			}

			if(isset($_REQUEST['Del']) && $_REQUEST['Del']>=1)
			{
				foreach($_POST['Del'] as $id => $Del)
				{
					$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_shop_artikel_kommentare WHERE Id = '$id'");
				}
			}
			echo '<script>window.opener.location.reload();window.close();</script>';
		}

		$comments = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_artikel_kommentare WHERE ArtId = '" . (int)$_REQUEST['Id'] . "' ORDER BY Id DESC");
		while($row = $sql->fetchrow())
		{
			array_push($comments, $row);
		}

		$GLOBALS['tmpl']->assign('comments', $comments);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_comments.tpl'));
	}



	function globalProductInfo($row = '')
	{
		//
	}

	//=======================================================
	// Shop - Navi erzeugen (Магазин - Навигация по категориям)
	//=======================================================
	function getCategoriesSimple($id, $prefix, &$entries, $admin=0, $dropdown=0, $itid='')
	{
		$query = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_kategorie WHERE Elter = '$id' order by Rang ASC");

   		if (!$query->numrows()) return;

		while ($item = $query->fetchrow())
		{
			$item->visible_title = $prefix . (($item->Elter!=0 && $admin != 1) ? '' : '') . $item->KatName;
			$item->expander = $prefix;
			$item->sub = ($item->Elter==0) ? 0 : 1;
			$item->dyn_link = "index.php?module=shop&amp;categ=$item->Id&amp;parent=$item->Elter&amp;navop=" . (($item->sub==0) ? $item->Id : getParentShopcateg($item->Elter));
			$sql = $GLOBALS['db']->Query("SELECT Id,KatId FROM ".PREFIX."_modul_shop_artikel WHERE KatId = '$item->Id' AND Aktiv='1'");
			$item->acount = $sql->numrows();

			array_push($entries,$item);
			if($admin == 1)
			{
				$this->getCategoriesSimple($item->Id, $prefix . '', $entries, $admin, $dropdown);
			} else {
				$this->getCategoriesSimple($item->Id, $prefix . (($dropdown==1) ? '&nbsp;&nbsp;' : $this->_expander), $entries, $dropdown,  $item->Id);
			}
		}
    	return $entries;
	}

	//=======================================================
	// Shop - Navi (Магазин - Навигация)
	//=======================================================
	function fetchShopNavi($noprint='')
	{
		$shopitems = array();
		$categs = array();
		$fetchcat = (isset($_GET['categ']) && is_numeric($_GET['categ'])) ? $_GET['categ'] : '0';

		if($noprint != 1)
		{
			$ShopCategs = $this->getCategoriesSimple(0, '', $categs,'0');
			$GLOBALS['tmpl']->assign('shopStart', $this->shopRewrite('index.php?module=shop'));
			$GLOBALS['tmpl']->assign('shopnavi', $ShopCategs);
			$GLOBALS['tmpl']->display($GLOBALS['mod']['tpl_dir'] . $this->_shop_navi);
		} else {
			$ShopCategs = $this->getCategoriesSimple(0, '', $categs,'0',1);
			return $ShopCategs;
		}
	}


	//=======================================================
	// Funktion zum Auslesen der Lдnder (Выборка списка стран)
	//=======================================================
	function displayCountries()
	{
		$laender = array();
		$sql = $GLOBALS['db']->Query("SELECT LandCode,LandName FROM " . PREFIX . "_countries WHERE Aktiv='1' ORDER BY LandName ASC");
		while($row = $sql->fetchrow()) array_push($laender,$row);
		return $laender;
	}

	//=======================================================
	// Funktion zum Auslesen der Versandkosten einer Versandart (Функция расчета стоимости пересылки в зависимости от вида отправки)
	//=======================================================
	function displayShippingCost($arr = '', $vid = '')
	{
		$shippingcost = array();
		$sql = $GLOBALS['db']->Query("SELECT LandCode,LandName FROM " . PREFIX . "_countries WHERE Aktiv='1' ORDER BY LandName ASC");

		while($row = $sql->fetchrow())
		{
			$vcost = array();
			if(in_array($row->LandCode,$arr))
			{
				$sql_vcost = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_versandkosten WHERE Land='$row->LandCode' AND VersandId='$vid' ORDER BY KVon ASC");
				while($row_vcost = $sql_vcost->fetchrow())
				{
					array_push($vcost, $row_vcost);
				}
				$row->versandkosten = $vcost;
				array_push($shippingcost, $row);
			}
		}
		return $shippingcost ;
	}

	//=======================================================
	// Funktion zum Auslesen der Versandarten (Выборка видов отправки)
	//=======================================================
	function displayShipper()
	{
		$shipper = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_versandarten ORDER BY Name ASC");
		while($row = $sql->fetchrow()) array_push($shipper,$row);
		return $shipper;
	}

	//=======================================================
	// Funktion zum Auslesen der Gruppen (Выборка групп пользователей)
	//=======================================================
	function displayGroups()
	{
		$Groups = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_user_groups ORDER BY Name ASC");
		while($row = $sql->fetchrow()) array_push($Groups,$row);
		return $Groups;
	}



	//=======================================================
	// Versandarten (Виды отправки)
	//=======================================================
	function shopShipper($tpl_dir)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			foreach($_POST['Name'] as $id => $Name)
			{
				if(!empty($_POST['Name'][$id])) $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_versandarten SET Name = '".$_POST['Name'][$id]."', KeineKosten = '".$_POST['KeineKosten'][$id]."', Aktiv = '".$_POST['Aktiv'][$id]."' WHERE Id = '$id'");
			}
		}

		$GLOBALS['tmpl']->assign('shopShipper',$this->displayShipper());
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_shipper.tpl'));
	}

	function editShipper($tpl_dir)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			$Icon = '';
			$q = "UPDATE " . PREFIX . "_modul_shop_versandarten SET
				Name = '" . $_POST['Name'] . "',
				Beschreibung = '" . $_POST['Beschreibung'] . "',
				Icon = '" . $Icon . "',
				LaenderVersand = '" . @implode(',', $_POST['LaenderVersand']) . "',
				Pauschalkosten = '" . str_replace(',','.',$_POST['Pauschalkosten']) . "',
				KeineKosten = '" . $_POST['KeineKosten'] . "',
				Aktiv = '" . $_POST['Aktiv'] . "',
				NurBeiGewichtNull = '" . $_POST['NurBeiGewichtNull'] . "',
				ErlaubteGruppen = '" . @implode(',', $_POST['ErlaubteGruppen']) . "'
			WHERE
				Id = '$_REQUEST[Id]'";
			$sql = $GLOBALS['db']->Query($q);
			echo '<script>window.opener.location.reload(); window.close();</script>';
		}

		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_versandarten WHERE Id = '$_REQUEST[Id]'");
		$row = $sql->fetchrow();
		$row->VersandLaender = explode(',', $row->LaenderVersand);
		$row->Gruppen = explode(',', $row->ErlaubteGruppen);

		$oFCKeditor->BasePath = SOURCE_DIR . "admin/editor/" ;
		$oFCKeditor = new FCKeditor('Beschreibung') ;
		$oFCKeditor->Height = '200';
		$oFCKeditor->ToolbarSet = 'Basic';
		$oFCKeditor->Value	= $row->Beschreibung;
		$Edi = $oFCKeditor->Create();

		$GLOBALS['tmpl']->assign('Edi', $Edi);
		$GLOBALS['tmpl']->assign('laender', $this->displayCountries());
		$GLOBALS['tmpl']->assign('gruppen', $this->displayGroups());
		$GLOBALS['tmpl']->assign('ss', $row);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_editshipper.tpl'));
	}

	//=======================================================
	// Versandkosten bearbeiten (Стоимость пересылки)
	//=======================================================
	function editshipperCost($tpl_dir)
	{
		$close_window = true;
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{

			// Einzelne lцschen
			if(isset($_POST['Del']) && count($_POST['Del']) >= 1)
			{
				foreach($_POST['Del'] as $id => $Del)
				{
					$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_shop_versandkosten WHERE Id = '$id'");
				}
			}

			// Neue Werte
			if(!empty($_REQUEST['NeuVon']) && !empty($_REQUEST['NeuBis']) && !empty($_REQUEST['NeuBetrag']))
			{
				$close_window = false;
				foreach($_POST['NeuVon'] as $land => $NeuVon)
				{
					if(!empty($_POST['NeuVon'][$land]) && !empty($_POST['NeuBis'][$land]) && !empty($_POST['NeuBetrag'][$land]))
					{
						$q = "INSERT INTO " . PREFIX . "_modul_shop_versandkosten (
							Id,
							VersandId,
							Land,
							KVon,
							KBis,
							Betrag
						) VALUES (
							'',
							'" . $_REQUEST['Id'] . "',
							'" . $land . "',
							'" . $this->kReplace($_POST['NeuVon'][$land]) . "',
							'" . $this->kReplace($_POST['NeuBis'][$land]) . "',
							'" . $this->kReplace($_POST['NeuBetrag'][$land]) . "'
						)";
						$GLOBALS['db']->Query($q);
					}
				}
			}

			// Vorhandene Versandkosten aktualisieren
			if(isset($_POST['KVon']))
			{
				foreach($_POST['KVon'] as $id => $KVon)
				{
					if(!empty($_POST['KVon'][$id]) && !empty($_POST['KBis'][$id]) && !empty($_POST['Betrag']))
					{
						$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_versandkosten
						SET
							KVon = '" . $this->kReplace($_POST['KVon'][$id]) . "',
							KBis = '" . $this->kReplace($_POST['KBis'][$id]) . "',
							Betrag = '" . $this->kReplace($_POST['Betrag'][$id]) . "'
						WHERE Id = '$id'
						");
					}
				}
			}
			if($close_window == true) echo '<script>window.opener.location.reload(); window.close();</script>';
		}

		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_versandarten WHERE Id = '$_REQUEST[Id]'");
		$row = $sql->fetchrow();
		$row->VersandLaender = explode(',', $row->LaenderVersand);
		$GLOBALS['tmpl']->assign('ss', $row);

		$GLOBALS['tmpl']->assign('laender', $this->displayShippingCost($row->VersandLaender, $_REQUEST['Id']));
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_shipper_cost.tpl'));
	}

	//=======================================================
	// Einstellungen E-Mail (Установки E-mail)
	//=======================================================
	function emailSettings($tpl_dir)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			$q = "UPDATE " . PREFIX . "_modul_shop SET
			 AdresseText = '" . $_POST['AdresseText'] . "',
			 AdresseHTML = '" . $_POST['AdresseHTML'] . "',
			 Logo = '" . $_POST['Logo'] . "',
			 EmailFormat = '" . $_POST['EmailFormat'] . "',
			 AbsEmail = '" . chop($_POST['AbsEmail']) . "',
			 AbsName = '" . chop($_POST['AbsName']) . "',
			 EmpEmail = '" . chop($_POST['EmpEmail']) . "',
			 BetreffBest = '" . $_POST['BetreffBest'] . "'
			 WHERE Id = 1
			";
			$GLOBALS['db']->Query($q);
		}

		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop LIMIT 1");
		$row = $sql->fetchrow();
		$row->VersandLaender = explode(',', $row->VersandLaender);

		$GLOBALS['tmpl']->assign('row', $row);
		$GLOBALS['tmpl']->assign('laender', $this->displayCountries());
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_settings_email.tpl'));
	}

	//=======================================================
	// Einstellungen (Настройки)
	//=======================================================
	function Settings($tpl_dir)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			$q = "UPDATE " . PREFIX . "_modul_shop SET
			 ZeigeWaehrung2 = '" . $_POST['ZeigeWaehrung2'] . "',
			 Kommentare = '" . @$_POST['Kommentare'] . "',
			 KommentareGast = '" . @$_POST['KommentareGast'] . "',
			 Aktiv = '" . $_POST['Aktiv'] . "',
			 Waehrung = '" . (($_POST['Waehrung']=='') ? 'EUR' : $_POST['Waehrung']) . "',
			 WaehrungSymbol = '" . str_replace('Ђ','&euro;',$_POST['WaehrungSymbol']) . "',
			 Waehrung2 = '" . @$_POST['Waehrung2'] . "',
			 WaehrungSymbol2 = '" . @$_POST['WaehrungSymbol2'] . "',
			 Waehrung2Multi = '" . $this->kreplace(@$_POST['Waehrung2Multi']) . "',
			 ShopLand = '" . strtoupper($_POST['ShopLand']) . "',
			 ArtikelMax = '" . (($_POST['ArtikelMax']=='' || $_POST['ArtikelMax']<'2') ? '10' : $_POST['ArtikelMax']) . "',
			 KaufLagerNull = '" . $_POST['KaufLagerNull'] . "',
			 VersandLaender = '" . (implode(',', $_POST['VersandLaender'])) . "',
			 GastBestellung = '" . $_POST['GastBestellung'] . "',
			 VersFrei = '" . $_POST['VersFrei'] . "',
			 VersFreiBetrag = '" . str_replace(',','.',$_POST['VersFreiBetrag']) . "',
			 GutscheinCodes = '" . $_POST['GutscheinCodes'] . "',
			 ZeigeEinheit = '" . $_POST['ZeigeEinheit'] . "',
			 ZeigeNetto = '" . $_POST['ZeigeNetto'] . "',
			 KategorienStart = '" . $_POST['KategorienStart'] . "',
			 KategorienSons = '" . $_POST['KategorienSons'] . "',
			 ZufallsAngebot = '" . @$_POST['ZufallsAngebot'] . "',
			 ZufallsAngebotKat = '" . @$_POST['ZufallsAngebotKat'] . "',
			 BestUebersicht = '" . $_POST['BestUebersicht'] . "',
			 Merkliste = '" . @$_POST['Merkliste'] . "',
			 Topseller = '" . @$_POST['Topseller'] . "',
			 TemplateArtikel = '" . @$_POST['TemplateArtikel'] . "',
			 Vorschaubilder = '" . ( (isset($_POST['Vorschaubilder']) && $_POST['Vorschaubilder']!='') ? $_POST['Vorschaubilder'] : 80 ) . "',
       Topsellerbilder = '" . ( (isset($_POST['Topsellerbilder']) && $_POST['Topsellerbilder']!='') ? $_POST['Topsellerbilder'] : 40 ) . "',
       ShopKeywords = '" . @$_POST['ShopKeywords'] . "',
       ShopDescription = '" . @$_POST['ShopDescription'] . "'
			 WHERE Id = 1
			";
			$GLOBALS['db']->Query($q);
		}

		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop LIMIT 1");
		$row = $sql->fetchrow();
		$row->VersandLaender = explode(',', $row->VersandLaender);

		$GLOBALS['tmpl']->assign('row', $row);
		$GLOBALS['tmpl']->assign('laender', $this->displayCountries());
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_settings.tpl'));
	}

	//=======================================================
	// Hilfeseiten bearbeiten (Страница помощи)
	//=======================================================
	function helpPages($tpl_dir)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub']=='save')
		{
			$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop
			SET
				VersandInfo = '" . @$_POST['VersandInfo'] . "',
				DatenschutzInf = '" . @$_POST['DatenschutzInf'] . "',
				Impressum = '" . @$_POST['Impressum'] . "',
				ShopWillkommen = '" . @$_POST['ShopWillkommen'] . "',
				ShopFuss = '" . @$_POST['ShopFuss'] . "',
				Agb = '" . @$_POST['Agb'] . "'
			WHERE
				Id = '1'");

		}

		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop LIMIT 1");
		$row = $sql->fetchrow();

		$oFCKeditor->BasePath = SOURCE_DIR . "admin/editor/" ;
		$oFCKeditor = new FCKeditor('VersandInfo');
		$oFCKeditor->Height = '200';
		$oFCKeditor->ToolbarSet = 'Simple';
		$oFCKeditor->Value= $row->VersandInfo;
		$row->VersandInfo = $oFCKeditor->Create();

		$oFCKeditor->BasePath = SOURCE_DIR . "admin/editor/" ;
		$oFCKeditor = new FCKeditor('DatenschutzInf');
		$oFCKeditor->Height = '200';
		$oFCKeditor->ToolbarSet = 'Simple';
		$oFCKeditor->Value= $row->DatenschutzInf;
		$row->DatenschutzInf = $oFCKeditor->Create();

		$oFCKeditor->BasePath = SOURCE_DIR . "admin/editor/" ;
		$oFCKeditor = new FCKeditor('Impressum');
		$oFCKeditor->Height = '200';
		$oFCKeditor->ToolbarSet = 'Simple';
		$oFCKeditor->Value= $row->Impressum;
		$row->Impressum = $oFCKeditor->Create();

		$oFCKeditor->BasePath = SOURCE_DIR . "admin/editor/" ;
		$oFCKeditor = new FCKeditor('ShopWillkommen');
		$oFCKeditor->Height = '200';
		$oFCKeditor->ToolbarSet = 'Simple';
		$oFCKeditor->Value= $row->ShopWillkommen;
		$row->ShopWillkommen = $oFCKeditor->Create();

		$oFCKeditor->BasePath = SOURCE_DIR . "admin/editor/" ;
		$oFCKeditor = new FCKeditor('ShopFuss');
		$oFCKeditor->Height = '200';
		$oFCKeditor->ToolbarSet = 'Simple';
		$oFCKeditor->Value= $row->ShopFuss;
		$row->ShopFuss = $oFCKeditor->Create();

		$oFCKeditor->BasePath = SOURCE_DIR . "admin/editor/" ;
		$oFCKeditor = new FCKeditor('Agb');
		$oFCKeditor->Height = '200';
		$oFCKeditor->ToolbarSet = 'Simple';
		$oFCKeditor->Value= $row->Agb;
		$row->Agb = $oFCKeditor->Create();

		$GLOBALS['tmpl']->assign('row', $row);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_helppages.tpl'));
	}

	//=======================================================
	// Versandkosten (Стоимость пересылки)
	//=======================================================

	// Versandart lцschen
	function deleteMethod($id)
	{
		if($id != 1 && $id != 2 && $id != 3 && $id != 4 && $id != 5)
		{
			$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_shop_zahlungsmethoden WHERE Id = '$id'");
		}
		header("Location:index.php?do=modules&action=modedit&mod=shop&moduleaction=paymentmethods&cp=" . SESSION);
		exit;
	}

	// Versandarten auslesen
	function displayMethods()
	{
		$methods = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_zahlungsmethoden ORDER BY Position ASC");
		while($row = $sql->fetchrow()) array_push($methods,$row);
		return $methods;
	}

	// Neue Zahlungsmethode
	function newPaymentMethod()
	{
		$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_shop_zahlungsmethoden (Id,Name) VALUES ('','$_POST[Name]')");
		header("Location:index.php?do=modules&action=modedit&mod=shop&moduleaction=paymentmethods&cp=" . SESSION);
		exit;
	}

	// Versandarten anzeigen & Schnellspeicherung
	function paymentMethods($tpl_dir)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			foreach($_POST['Name'] as $id => $Name)
			{
				if(!empty($_POST['Name'][$id])) $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_zahlungsmethoden SET Name = '".$_POST['Name'][$id]."',  Aktiv = '".$_POST['Aktiv'][$id]."', Position = '".$_POST['Position'][$id]."' WHERE Id = '$id'");
			}
		}

		$GLOBALS['tmpl']->assign('methods', $this->displayMethods());
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_paymentmethods.tpl'));
	}

	// Versandart bearbeiten
	function editPaymentMethod($tpl_dir,$id)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			$Icon = '';
			$q = "UPDATE " . PREFIX . "_modul_shop_zahlungsmethoden SET
				Name = '" . $_POST['Name'] . "',
				Beschreibung = '" . $_POST['Beschreibung'] . "',
				ErlaubteVersandLaender = '" . @implode(',', $_POST['ErlaubteVersandLaender']) . "',
				ErlaubteVersandarten = '" . @implode(',', $_POST['ErlaubteVersandarten']) . "',
				ErlaubteGruppen = '" . @implode(',', $_POST['ErlaubteGruppen']) . "',
				Aktiv = '" . $_POST['Aktiv'] . "',
				Kosten = '" . $this->kReplace($_POST['Kosten']) . "',
				KostenOperant = '" . $_POST['KostenOperant'] . "',
				InstId = '" . chop(@$_POST['InstId']) . "',
				ZahlungsBetreff = '" . @$_POST['ZahlungsBetreff'] . "',
				TestModus = '" . chop(@$_POST['TestModus']) . "',
				Extern = '" . @$_POST['Extern'] . "',
				Gateway = '" . chop(@$_POST['Gateway']) . "'
			WHERE
				Id = '$_REQUEST[Id]'";
			$GLOBALS['db']->Query($q);
			echo '<script>window.opener.location.reload(); window.close();</script>';
		}

		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_zahlungsmethoden WHERE Id = '$id'");
		$row = $sql->fetchrow();
		$row->VersandLaender = explode(',', $row->ErlaubteVersandLaender);
		$row->Gruppen = explode(',', $row->ErlaubteGruppen);
		$row->Versandarten = explode(',', $row->ErlaubteVersandarten);

		$oFCKeditor->BasePath = SOURCE_DIR . "admin/editor/" ;
		$oFCKeditor = new FCKeditor('Beschreibung') ;
		$oFCKeditor->Height = '200';
		$oFCKeditor->ToolbarSet = 'Basic';
		$oFCKeditor->Value	= $row->Beschreibung;
		$Edi = $oFCKeditor->Create();

		$GLOBALS['tmpl']->assign('Edi', $Edi);
		$GLOBALS['tmpl']->assign('laender', $this->displayCountries());
		$GLOBALS['tmpl']->assign('gruppen', $this->displayGroups());
		$GLOBALS['tmpl']->assign('shipper', $this->displayShipper());
		$GLOBALS['tmpl']->assign('ss', $row);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_editpaymentmethod.tpl'));
	}

	//=======================================================
	// Versandzeiten (Срок доставки)
	//=======================================================
	function displaySt()
	{
		$st = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_versandzeit ORDER BY Name ASC");
		while($row = $sql->fetchrow()) array_push($st,$row);
		return $st;
	}

	// Neue Versanndzeit
	function shipperTimeNew($tpl_dir)
	{
		$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_shop_versandzeit (Id,Name) VALUES ('','$_POST[Name]')");
		header("Location:index.php?do=modules&action=modedit&mod=shop&moduleaction=timeshipping&cp=" . SESSION);
		exit;
	}

	// Versandzeiten
	function shipperTime($tpl_dir)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			if(isset($_POST['Del']) && count($_POST['Del']) >= 1)
			{
				foreach($_POST['Del'] as $id => $Name) $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_shop_versandzeit  WHERE Id = '$id'");
			}

			foreach($_POST['Name'] as $id => $Name)
			{
				if(!empty($_POST['Name'][$id])) $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_versandzeit SET Name = '".$_POST['Name'][$id]."' WHERE Id = '$id'");
			}
		}

		$GLOBALS['tmpl']->assign('st', $this->displaySt());
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_shippertime.tpl'));
	}

	//=======================================================
	// Produkt - Varianten (Товар - Варианты)
	//=======================================================
	function displayVariantCategories()
	{
		$variantCateg = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_varianten_kategorien ORDER BY Name ASC");
		while($row = $sql->fetchrow()) array_push($variantCateg,$row);
		return $variantCateg;
	}

	function variantsCategories($tpl_dir)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			foreach($_POST['Name'] as $id => $Name)
			{
				if(!empty($_POST['Name'][$id])) $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_varianten_kategorien SET KatId = '".$_POST['KatId'][$id]."', Name = '".$_POST['Name'][$id]."', Aktiv = '".$_POST['Aktiv'][$id]."' WHERE Id = '$id'");
			}
		}

		$GLOBALS['tmpl']->assign('ProductCategs', $this->fetchShopNavi(1));
		$GLOBALS['tmpl']->assign('variantCateg', $this->displayVariantCategories());
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_variantscategs.tpl'));
	}

	function newVariantsCategories()
	{
		$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_shop_varianten_kategorien (Id,Name,KatId) VALUES ('','$_POST[Name]','$_POST[KatId]')");
		header("Location:index.php?do=modules&action=modedit&mod=shop&moduleaction=variants_categories&cp=" . SESSION);
		exit;
	}

	function editVariantsCategory($tpl_dir,$id)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_varianten_kategorien SET Beschreibung = '$_POST[Beschreibung]' WHERE Id = '$id'");
			echo '<script>window.close()</script>';
		}

		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_varianten_kategorien WHERE Id = '$id'");
		$row = $sql->fetchrow();

		$oFCKeditor->BasePath = SOURCE_DIR . "admin/editor/" ;
		$oFCKeditor = new FCKeditor('Beschreibung') ;
		$oFCKeditor->Height = '400';
		$oFCKeditor->ToolbarSet = 'Simple';
		$oFCKeditor->Value	= $row->Beschreibung;
		$Edi = $oFCKeditor->Create();

		$GLOBALS['tmpl']->assign('Edi', $Edi);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_editvariant_categ.tpl'));
	}

	function deleteVariantsCategory($id)
	{
		$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_shop_varianten_kategorien WHERE Id = '$id'");
		header("Location:index.php?do=modules&action=modedit&mod=shop&moduleaction=variants_categories&cp=" . SESSION);
		exit;
	}

	//=======================================================
	// Produkte (Товар)
	//=======================================================
	function displayProducts($tpl_dir)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			foreach($_POST['Preis'] as $id => $Preis)
			{
				$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_artikel
				SET
					Preis = '" . $this->kReplace($_POST['Preis'][$id]) . "',
					KatId = '" . $_POST['KatId'][$id] . "',
					Lager = '" . (int)$_POST['Lager'][$id] . "',
					Bestellungen = '" . (int)$_POST['Bestellungen'][$id] . "',
					PosiStartseite = '" . (int)$_POST['PosiStartseite'][$id] . "'
				WHERE Id = '$id'");
			}


			$dbAct = '';
			reset ($_POST);
			while(list($key,$val) = each($_POST))
			{
				if(isset($_REQUEST['SubAction']) && $_REQUEST['SubAction'] != 'nothing')
				{
					switch($_REQUEST['SubAction'])
					{
						case 'close':
							$dbAct = "SET Aktiv = '0'";
						break;

						case 'open':
							$dbAct = "SET Aktiv = '1'";
						break;

						case 'del':
							$dbAct = "del";
						break;

						case '':
						default:
							$dbAct = '';
						break;
					}
				}

				if (substr($key,0,12)=="shopartikel_" && $dbAct != '')
				{
					$aktid = str_replace("shopartikel_","",$key);
					if($dbAct=='del')
					{
						$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_shop_artikel WHERE Id = '$aktid'");
					} else {
						$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_artikel $dbAct WHERE Id = '$aktid'");
					}
				}
			}
			header("Location:index.php?do=modules&action=modedit&mod=shop&moduleaction=products&cp=" . SESSION);
			exit;

		}

		$GLOBALS['tmpl']->assign('products', $this->lastArticles());
		$GLOBALS['tmpl']->assign('ProductCategs', $this->fetchShopNavi(1));
		$GLOBALS['tmpl']->assign('Manufacturer', $this->displayManufacturer());
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_products.tpl'));
	}

	//=======================================================
	// Einheiten (Единицы)
	//=======================================================
	function Units($tpl_dir)
	{
		// Speichern
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			foreach($_POST['Name'] as $id => $Name) $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_einheiten SET Name = '" . $_POST['Name'][$id] . "', NameEinzahl = '" . $_POST['NameEinzahl'][$id] . "' WHERE Id = '$id'");

			// Einzelne EInheit lцschen
			if(isset($_POST['Del']) && $_POST['Del'] >= 1)
			{
				foreach($_POST['Del'] as $id => $Del) $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_shop_einheiten WHERE Id = '$id'");
			}
		}


		$Units = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_einheiten ORDER BY Name ASC");
		while($row = $sql->fetchrow())
		{
			array_push($Units, $row);
		}

		$GLOBALS['tmpl']->assign('Units', $Units);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_units.tpl'));
	}

	// Neue Einheit
	function UnitsNew($tpl_dir)
	{
		$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_shop_einheiten (Id,Name,NameEinzahl) VALUES ('','" . $_POST['NameEinzahl'] . "','" . $_POST['NameEinzahl'] . "')");
		header("Location:index.php?do=modules&action=modedit&mod=shop&moduleaction=units&cp=" . SESSION);
		exit;
	}


	//=======================================================
	// Hersteller (Производитель)
	//=======================================================
	function Manufacturer($tpl_dir)
	{
		// Speichern
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			foreach($_POST['Name'] as $id => $Name) $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_hersteller SET Name = '" . $_POST['Name'][$id] . "' WHERE Id = '$id'");

			// Hersteller lцschen
			if(isset($_POST['Del']) && $_POST['Del'] >= 1)
			{
				foreach($_POST['Del'] as $id => $Del) $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_shop_hersteller WHERE Id = '$id'");
			}
		}

		$Manufacturer = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_hersteller ORDER BY Name ASC");
		while($row = $sql->fetchrow())
		{
			array_push($Manufacturer, $row);
		}

		$GLOBALS['tmpl']->assign('Manufacturer', $Manufacturer);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_manufacturer.tpl'));
	}

	// Neuer Hersteller
	function ManufacturerNew($tpl_dir)
	{
		$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_shop_hersteller (Id,Name) VALUES ('','" . $_POST['Name'] . "')");
		header("Location:index.php?do=modules&action=modedit&mod=shop&moduleaction=manufacturer&cp=" . SESSION);
		exit;
	}

	//=======================================================
	// Produktvarianten zuweisen (Варианты товаров)
	//=======================================================
	function prouctVars($tpl_dir,$product_id,$kat_id)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{

			if(isset($_POST['NameNew'])  && count($_POST['NameNew']) >= 1)
			{
				foreach($_POST['NameNew'] as $id => $NameNew)
				{
					if($_REQUEST['NameNew'][$id] != '')
					{
						$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_shop_varianten
						(
							Id,
							KatId,
							ArtId,
							Name,
							Wert,
							Operant,
							Position
						) VALUES (
							'',
							'$id',
							'$_REQUEST[Id]',
							'" . $_REQUEST['NameNew'][$id] . "',
							'" . $this->kReplace(chop($_REQUEST['WertNew'][$id])) . "',
							'" . $_REQUEST['OperantNew'][$id] . "',
							'" . $_REQUEST['PositionNew'][$id] . "'
						)
						");
					}
				}
			}

			// Varianten - Kategorienamen aktualisieren
			// Varianten aktualisieren
			if(isset($_POST['Name']) && $_POST['Name'] != '')
			{
				foreach($_POST['Name'] as $id => $Name)
				{
					$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_varianten
					SET
						Name = '" . $_POST['Name'][$id] . "',
						Operant = '" . $_POST['Operant'][$id] . "',
						Wert = '" . $_POST['Wert'][$id] . "',
						Position = '" . $_POST['Position'][$id] . "'
					WHERE Id = '$id'");
				}
			}

			// Varianten - Positionen lцschen
			if(isset($_POST['Del']) && $_POST['Del'] != '')
			{
				foreach($_POST['Del'] as $id => $Name) $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_shop_varianten WHERE Id = '$id'");
			}
		}

		//NameVar
		$Vars = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_varianten_kategorien WHERE KatId = '$kat_id'");
		while($row = $sql->fetchrow())
		{
			$SubVars = array();
			$sql_v = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_varianten WHERE ArtId = '$product_id' AND KatId = '$row->Id' ORDER BY Position ASC");
			while($row_v = $sql_v->fetchrow())
			{
				array_push($SubVars, $row_v);
			}

			$row->SubVars = $SubVars;
			array_push($Vars, $row);
		}
		$GLOBALS['tmpl']->assign('Vars', $Vars);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_productvars.tpl'));
	}


	//=======================================================
	// Produkt bearbeiten (Редактирование товара)
	//=======================================================
	function editProduct($tpl_dir,$id)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			$endung = '';
			$DbNewImage = '';
			$DbBilder = '';

			// Wenn neues Bild gespeichert werden soll
			if(isset($_FILES) && $_FILES['Bild']['tmp_name'] != '')
			{
				$upload_dir = BASE_DIR . '/modules/shop/uploads/';
				$name = str_replace(array(' ', '+','-'),'_',strtolower($_FILES['Bild']['name']));
				$name = ereg_replace("_+", "_", $name);
				$temp = $_FILES['Bild']['tmp_name'];
				$endung = strtolower(substr($name, -3));
				$fupload_name = $name;

				if(in_array($_FILES['Bild']['type'], $this->_allowed_images))
				{
					// Wenn Bild existiert, Bild umbenennen
					if(file_exists($upload_dir . $fupload_name))
					{
						$fupload_name = $this->renameFile($fupload_name);
						$name = $fupload_name;
					}

					// Bild hochladen
					@move_uploaded_file($_FILES['Bild']['tmp_name'], $upload_dir . $fupload_name);
					@chmod($upload_dir . $fupload_name, 0777);

					// Altes Bild lцschen
					@unlink($upload_dir . $_REQUEST['del_old']);

					$DbNewImage = "Bild = '$fupload_name', Bild_Typ = '$endung',";
				}
			}

			// Weitere Bilder
			if(isset($_FILES) && count(@$_FILES['file']['tmp_name']) >= 1 && $_FILES['file']['tmp_name'] != '')
			{
				$fupload_name = '';
				$upload_dir = BASE_DIR . '/modules/shop/uploads/';

				for($i=0;$i<count(@$_FILES['file']['tmp_name']);$i++)
				{
					$size = $_FILES['file']['size'][$i];
					$name = str_replace(array(' ', '+','-'),'_',strtolower($_FILES['file']['name'][$i]));
					$name = ereg_replace("_+", "_", $name);
					$temp = $_FILES['file']['tmp_name'][$i];
					$fupload_name = $name;

					if(file_exists($upload_dir . $fupload_name))
					{
						$fupload_name = $this->renameFile($fupload_name);
						$name = $fupload_name;
					}

					if(!empty($name) && in_array($_FILES['file']['type'][$i], $this->_allowed_images) )
					{
						@move_uploaded_file($_FILES['file']['tmp_name'][$i], $upload_dir . $fupload_name);
						@chmod($upload_dir . $fupload_name, 0777);
						$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_shop_artikel_bilder (Id,ArtId,Bild) VALUES ('','$_REQUEST[Id]','$fupload_name')");
					}
				}

			}

			// Eventuelle Bilder lцschen
			if(isset($_POST['del_multi']) && count($_POST['del_multi']) >= 1)
			{
				$upload_dir = BASE_DIR . '/modules/shop/uploads/';
				foreach($_POST['del_multi'] as $did => $del_multi)
				{
					$sql_del = $GLOBALS['db']->Query("SELECT Bild FROM " . PREFIX . "_modul_shop_artikel_bilder WHERE Id = '$did'");
					$row_del = $sql_del->fetchrow();
					@unlink($upload_dir . $row_del->Bild);
					$q = $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_shop_artikel_bilder WHERE Id = '$did'");
				}
			}

			$ArtNr = (isset($_POST['ArtNr']) && $_POST['ArtNr'] != '') ? "ArtNr = '" . chop($_POST['ArtNr']) . "'," : "";
			$q = "UPDATE " . PREFIX . "_modul_shop_artikel
			SET
				$ArtNr
				$DbNewImage
				Artname = '".$_POST['ArtName']."',
				Aktiv = '" . $_POST['Aktiv'] . "',
				KatId = '" . $_POST['KatId'] . "',
				KatId_Multi = '" . implode(',', $_POST['KatId_Multi']) . ",',
				TextKurz = '" . chop($_POST['TextKurz']) . "',
				TextLang = '" . chop($_POST['TextLang']) . "',
				Schlagwoerter = '" . chop($_POST['Schlagwoerter']) . "',
				Preis = '" . $this->kReplace(chop($_POST['Preis'])) . "',
				PreisListe = '" . $this->kReplace(chop($_POST['PreisListe'])) . "',
				Gewicht = '" . $this->kReplace(chop($_POST['Gewicht'])) . "',
				UstZone = '" . $_POST['UstZone'] . "',
				Hersteller = '" . $_POST['Hersteller'] . "',
				Einheit = '" . $this->kReplace(chop($_POST['Einheit'])) . "',
				EinheitId = '" . $_POST['EinheitId'] . "',
				Lager = '" . chop($_POST['Lager']) . "',
				VersandZeitId = '" . $_POST['VersandZeitId'] . "',
				Erschienen = '" . mktime(0,0,0,$_POST['ErschMonth'],$_POST['ErschDay'],$_POST['ErschYear']) . "',
				Angebot = '" . $_POST['Angebot'] . "',
				Frei_Titel_1 = '" . @$_POST['Frei_Titel_1'] . "',
				Frei_Titel_2 = '" . @$_POST['Frei_Titel_2'] . "',
				Frei_Titel_3 = '" . @$_POST['Frei_Titel_3'] . "',
				Frei_Titel_4 = '" . @$_POST['Frei_Titel_4'] . "',
				Frei_Text_1 = '" . @$_POST['Frei_Text_1'] . "',
				Frei_Text_2 = '" . @$_POST['Frei_Text_2'] . "',
				Frei_Text_3 = '" . @$_POST['Frei_Text_3'] . "',
				Frei_Text_4 = '" . @$_POST['Frei_Text_4'] . "',
				DateiDownload = '" . @$_POST['DateiDownload'] . "',
        AngebotBild = '" . @$_POST['AngebotBild'] . "',
        ProdKeywords = '" . @$_POST['ProdKeywords'] . "',
        ProdDescription = '" . @$_POST['ProdDescription'] . "'
			WHERE Id = '" . $_REQUEST['Id'] . "' ";

			$errors = '';
			$muster_name = "[^ -_A-Za-zА-Яа-яЁё0-9-]";
			$muster = "[^-_A-Za-zА-Яа-яЁё0-9-]";
			if(empty($_POST['ArtName']) || ereg($muster_name, $_POST['ArtName'])) $errors[] = $GLOBALS['config_vars']['ProductNewPCheck'] . $GLOBALS['config_vars']['ProductName'];
			if(empty($_POST['ArtNr']) || ereg($muster, $_POST['ArtNr'])) $errors[] = $GLOBALS['config_vars']['ProductNewPCheck'] . $GLOBALS['config_vars']['ProductNrS'];
			if((isset($_POST['ArtNr']) && $_POST['ArtNr'] != $_POST['ArtNrOld'] && !$this->checkArtNumber($_POST['ArtNr']))) $errors[] = $GLOBALS['config_vars']['ProductNewArtnumberUnique'];
			if(empty($_POST['TextKurz'])) $errors[] = $GLOBALS['config_vars']['ProductNewPCheck'] . $GLOBALS['config_vars']['ProductTs'];
			if(empty($_POST['TextLang'])) $errors[] = $GLOBALS['config_vars']['ProductNewPCheck'] . $GLOBALS['config_vars']['ProductTl'];
			if(empty($_POST['Preis'])) $errors[] = $GLOBALS['config_vars']['ProductNewPCheck'] . $GLOBALS['config_vars']['ProductPrice'];
			if($_POST['Lager']=='' || !is_numeric($_POST['Lager'])) $errors[] = $GLOBALS['config_vars']['ProductNewPCheck'] . $GLOBALS['config_vars']['ProductStored'];
			if(isset($_FILES['Bild']) & $_FILES['Bild']['tmp_name'] != '' && (!in_array($_FILES['Bild']['type'],$this->_allowed_images)) ) $errors[] = $GLOBALS['config_vars']['ProductNewVorbittenImage'];

			if(is_array($errors))
			// Fehler
			{
				$GLOBALS['tmpl']->assign('errors', $errors);
			}
			else
			// Speichern
			{
				$GLOBALS['db']->Query($q);
				echo '<script>window.opener.location.reload(); window.close();</script>';
			}
		}

		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_artikel WHERE Id = '$id' ");
		$row = $sql->fetcharray();

		$MultiImages = array();
		$sql_bilder = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_artikel_bilder WHERE ArtId = '$row[Id]'");
		while($row_bilder = $sql_bilder->fetchrow())
		{
			array_push($MultiImages, $row_bilder);
		}
		$row['BilderMulti'] = $MultiImages;




		$KatIds = explode(',', $row['KatId_Multi']);

		$oFCKeditor->BasePath = SOURCE_DIR . "admin/editor/" ;
		$oFCKeditor = new FCKeditor('TextLang') ;$oFCKeditor->Height = '300'; $oFCKeditor->ToolbarSet = 'Simple'; $oFCKeditor->Value	= $row['TextLang']; $Lang = $oFCKeditor->Create();
		$oFCKeditor = new FCKeditor('TextKurz'); $oFCKeditor->Height = '150'; $oFCKeditor->ToolbarSet = 'Basic'; $oFCKeditor->Value	= $row['TextKurz']; $Kurz = $oFCKeditor->Create();

		$oFCKeditor = new FCKeditor('Frei_Text_1'); $oFCKeditor->Height = '150';  $oFCKeditor->ToolbarSet = 'Basic'; $oFCKeditor->Value	= $row['Frei_Text_1']; $Frei1 = $oFCKeditor->Create();
		$oFCKeditor = new FCKeditor('Frei_Text_2'); $oFCKeditor->Height = '150';  $oFCKeditor->ToolbarSet = 'Basic'; $oFCKeditor->Value	= $row['Frei_Text_2']; $Frei2 = $oFCKeditor->Create();
		$oFCKeditor = new FCKeditor('Frei_Text_3'); $oFCKeditor->Height = '150';  $oFCKeditor->ToolbarSet = 'Basic'; $oFCKeditor->Value	= $row['Frei_Text_3']; $Frei3 = $oFCKeditor->Create();
		$oFCKeditor = new FCKeditor('Frei_Text_4'); $oFCKeditor->Height = '150';  $oFCKeditor->ToolbarSet = 'Basic'; $oFCKeditor->Value	= $row['Frei_Text_4']; $Frei4 = $oFCKeditor->Create();

		$GLOBALS['tmpl']->assign('Frei1', $Frei1);
		$GLOBALS['tmpl']->assign('Frei2', $Frei2);
		$GLOBALS['tmpl']->assign('Frei3', $Frei3);
		$GLOBALS['tmpl']->assign('Frei4', $Frei4);

		$GLOBALS['tmpl']->assign('Kurz', $Kurz);
		$GLOBALS['tmpl']->assign('Lang', $Lang);
		$GLOBALS['tmpl']->assign('row', $row);
		$GLOBALS['tmpl']->assign('KatIds', $KatIds);
		$GLOBALS['tmpl']->assign('VatZones', $this->fetchVatZones());
		$GLOBALS['tmpl']->assign('Manufacturer', $this->displayManufacturer());
		$GLOBALS['tmpl']->assign('Units', $this->displayUnits());
		$GLOBALS['tmpl']->assign('ShippingTime', $this->shippingTime());
		$GLOBALS['tmpl']->assign('ProductCategs', $this->fetchShopNavi(1));
		$GLOBALS['tmpl']->assign('esds', $this->fetchEsdFiles());
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_editproduct.tpl'));

	}

	//=======================================================
	// Neuen Artikel anlegen
	//=======================================================
	function checkArtNumber($number)
	{
		$sql = $GLOBALS['db']->Query("SELECT ArtNr FROM " . PREFIX . "_modul_shop_artikel WHERE ArtNr = '$number'");
		$num = $sql->numrows();
		if($num == 1) return false;
		return true;
	}

	function newProduct($tpl_dir)
	{
		// Speichern
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			$DbImage = '';
			$MultiBilder = '';
			$errors = '';
			$muster_name = "[^ -_A-Za-zА-Яа-яЁё0-9-]";
			$muster = "[^-_A-Za-zА-Яа-яЁё0-9-]";
			if(empty($_POST['ArtName']) || ereg($muster_name, $_POST['ArtName'])) $errors[] = $GLOBALS['config_vars']['ProductNewPCheck'] . $GLOBALS['config_vars']['ProductName'];
			if(empty($_POST['ArtNr']) || ereg($muster, $_POST['ArtNr'])) $errors[] = $GLOBALS['config_vars']['ProductNewPCheck'] . $GLOBALS['config_vars']['ProductNrS'];
			if(!$this->checkArtNumber($_POST['ArtNr'])) $errors[] = $GLOBALS['config_vars']['ProductNewArtnumberUnique'];
			if(empty($_POST['TextKurz'])) $errors[] = $GLOBALS['config_vars']['ProductNewPCheck'] . $GLOBALS['config_vars']['ProductTs'];
			if(empty($_POST['TextLang'])) $errors[] = $GLOBALS['config_vars']['ProductNewPCheck'] . $GLOBALS['config_vars']['ProductTl'];
      if(!is_numeric($_POST['Preis'])) $errors[] = $GLOBALS['config_vars']['ProductNewPCheck'] . $GLOBALS['config_vars']['ProductPrice'];
      if($_POST['Lager']=='' || !is_numeric($_POST['Lager'])) $errors[] = $GLOBALS['config_vars']['ProductNewPCheck'] . $GLOBALS['config_vars']['ProductStored'];
			if(isset($_FILES['Bild']) & $_FILES['Bild']['tmp_name'] != '' && (!in_array($_FILES['Bild']['type'],$this->_allowed_images)) ) $errors[] = $GLOBALS['config_vars']['ProductNewVorbittenImage'];


			if(is_array($errors))
			// Fehler
			{
				$GLOBALS['tmpl']->assign('errors', $errors);
			}
			else
			// Speichern
			{
				// Bildupload
				if(isset($_FILES) && $_FILES['Bild']['tmp_name'] != '')
				{
					$upload_dir = BASE_DIR . '/modules/shop/uploads/';
					$name = str_replace(array(' ', '+','-'),'_',strtolower($_FILES['Bild']['name']));
					$name = ereg_replace("_+", "_", $name);
					$temp = $_FILES['Bild']['tmp_name'];
					$endung = strtolower(substr($name, -3));
					$fupload_name = $name;

					if(in_array($_FILES['Bild']['type'], $this->_allowed_images))
					{
						// Wenn Bild existiert, Bild umbenennen
						if(file_exists($upload_dir . $fupload_name))
						{
							$fupload_name = $this->renameFile($fupload_name);
							$name = $fupload_name;
						}

						// Bild hochladen
						@move_uploaded_file($_FILES['Bild']['tmp_name'], $upload_dir . $fupload_name);
						@chmod($upload_dir . $fupload_name, 0777);

						$DbImage = $fupload_name;
					}
				}

				// Weitere Bilder
				if(isset($_FILES) && count(@$_FILES['file']['tmp_name']) >= 1 && $_FILES['file']['tmp_name'] != '')
				{
					$fupload_name = '';
					$upload_dir = BASE_DIR . '/modules/shop/uploads/';

					for($i=0;$i<count(@$_FILES['file']['tmp_name']);$i++)
					{
						$size = $_FILES['file']['size'][$i];
						$name = str_replace(array(' ', '+','-'),'_',strtolower($_FILES['file']['name'][$i]));
						$name = ereg_replace("_+", "_", $name);
						$temp = $_FILES['file']['tmp_name'][$i];
						$fupload_name = $name;

						if(file_exists($upload_dir . $fupload_name))
						{
							$fupload_name = $this->renameFile($fupload_name);
							$name = $fupload_name;
						}

						if(!empty($name) && in_array($_FILES['file']['type'][$i], $this->_allowed_images) )
						{
							@move_uploaded_file($_FILES['file']['tmp_name'][$i], $upload_dir . $fupload_name);
							@chmod($upload_dir . $fupload_name, 0777);
							$MultiBilder[] = $fupload_name;
						}
					}
				}
				// Eintrag in die DB
				$q = "INSERT INTO " . PREFIX . "_modul_shop_artikel
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
					TextKurz,
					TextLang,
					Gewicht,
					Angebot,
					UstZone,
					Erschienen,
					Frei_Titel_1,
					Frei_Titel_2,
					Frei_Titel_3,
					Frei_Titel_4,
					Frei_Text_1,
					Frei_Text_2,
					Frei_Text_3,
					Frei_Text_4,
					Hersteller,
					Schlagwoerter,
					Einheit,
					EinheitId,
					Lager,
					VersandZeitId,
					DateiDownload,
          AngebotBild,
          ProdKeywords,
          ProdDescription
				) VALUES (
					'',
					'" . $_POST['ArtNr'] . "',
					'" . $_POST['KatId'] . "',
					'" . @implode(',',$_POST['KatId_Multi']) . ",',
					'" . chop($_POST['ArtName']) . "',
					'1',
					'" . chop($this->kReplace($_POST['Preis'])) . "',
					'" . chop($this->kReplace($_POST['PreisListe'])) . "',
					'" . $DbImage . "',
					'" . @substr(@$_FILES['Bild']['name'],-3). "',
					'" . $_POST['TextKurz'] . "',
					'" . $_POST['TextLang'] . "',
					'" . chop($this->kReplace($_POST['Gewicht'])) . "',
					'" . $_POST['Angebot'] . "',
					'" . $_POST['UstZone'] . "',
					'" . mktime(0,0,0,$_POST['ErschMonth'],$_POST['ErschDay'],$_POST['ErschYear']) . "',
					'" . @$_POST['Frei_Titel_1'] . "',
					'" . @$_POST['Frei_Titel_2'] . "',
					'" . @$_POST['Frei_Titel_3'] . "',
					'" . @$_POST['Frei_Titel_4'] . "',
					'" . @$_POST['Frei_Text_1'] . "',
					'" . @$_POST['Frei_Text_2'] . "',
					'" . @$_POST['Frei_Text_3'] . "',
					'" . @$_POST['Frei_Text_4'] . "',
					'" . @$_POST['Hersteller'] . "',
					'" . chop(@$_POST['Schlagwoerter']) . "',
					'" . chop($this->kReplace($_POST['Einheit'])) . "',
					'" . @$_POST['EinheitId'] . "',
					'" . chop($_POST['Lager']) . "',
					'" . $_POST['VersandZeitId'] . "',
					'" . @$_POST['DateiDownload'] . "',
          '" . @$_POST['AngebotBild'] . "',
          '" . @$_POST['ProdKeywords'] . "',
          '" . @$_POST['ProdDescription'] . "'
				)";
				$GLOBALS['db']->Query($q);

				// ID des neuen Artikels
				$iid = $GLOBALS['db']->InsertId();

				// Weitere Bilder speichern
				if(isset($MultiBilder) && is_array($MultiBilder) && count($MultiBilder) >= 1)
				{
					foreach($MultiBilder as $Bild) $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_shop_artikel_bilder (Id,ArtId,Bild) VALUES ('','$iid','$Bild')");
				}
				// Fenster schliessen...
				echo '<script>window.opener.location.reload(); window.close();</script>';
			}
		}

		// Form anzeigen
		$oFCKeditor->BasePath = SOURCE_DIR . "admin/editor/" ;

		$oFCKeditor = new FCKeditor('TextLang') ;$oFCKeditor->Height = '300'; $oFCKeditor->ToolbarSet = 'Simple'; $oFCKeditor->Value = @$_POST['TextLang']; $Lang = $oFCKeditor->Create();
		$oFCKeditor = new FCKeditor('TextKurz'); $oFCKeditor->Height = '150'; $oFCKeditor->ToolbarSet = 'Basic'; $oFCKeditor->Value	= @$_POST['TextKurz']; $Kurz = $oFCKeditor->Create();

		$oFCKeditor = new FCKeditor('Frei_Text_1'); $oFCKeditor->Height = '150';  $oFCKeditor->ToolbarSet = 'Basic'; $oFCKeditor->Value	= @$_POST['Frei_Text_1']; $Frei1 = $oFCKeditor->Create();
		$oFCKeditor = new FCKeditor('Frei_Text_2'); $oFCKeditor->Height = '150';  $oFCKeditor->ToolbarSet = 'Basic'; $oFCKeditor->Value	= @$_POST['Frei_Text_2']; $Frei2 = $oFCKeditor->Create();
		$oFCKeditor = new FCKeditor('Frei_Text_3'); $oFCKeditor->Height = '150';  $oFCKeditor->ToolbarSet = 'Basic'; $oFCKeditor->Value	= @$_POST['Frei_Text_3']; $Frei3 = $oFCKeditor->Create();
		$oFCKeditor = new FCKeditor('Frei_Text_4'); $oFCKeditor->Height = '150';  $oFCKeditor->ToolbarSet = 'Basic'; $oFCKeditor->Value	= @$_POST['Frei_Text_4']; $Frei4 = $oFCKeditor->Create();

		$GLOBALS['tmpl']->assign('Frei1', $Frei1);
		$GLOBALS['tmpl']->assign('Frei2', $Frei2);
		$GLOBALS['tmpl']->assign('Frei3', $Frei3);
		$GLOBALS['tmpl']->assign('Frei4', $Frei4);

		$GLOBALS['tmpl']->assign('Kurz', $Kurz);
		$GLOBALS['tmpl']->assign('Lang', $Lang);
		$GLOBALS['tmpl']->assign('VatZones', $this->fetchVatZones());
		$GLOBALS['tmpl']->assign('Manufacturer', $this->displayManufacturer());
		$GLOBALS['tmpl']->assign('Units', $this->displayUnits());
		$GLOBALS['tmpl']->assign('ShippingTime', $this->shippingTime());
		$GLOBALS['tmpl']->assign('ProductCategs', $this->fetchShopNavi(1));
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_newproduct.tpl'));

	}

	//=======================================================
	// Export (Экспорт)
	//=======================================================
	function dataExport($tpl_dir)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'export')
		{
			require_once(BASE_DIR . '/modules/shop/class.export.php');
			$export = new DataExport();
			switch($_REQUEST['t'])
			{
				case 'orders':
					$table = 'orders';
					$Prefab = 'CP_BESTELLUNGEN_';
				break;

				case 'user':
					$table = 'user';
					$Prefab = 'CP_BENUTZER_';
				break;

				case 'articles':
					$table = 'articles';
					$Prefab = 'CP_SHOPARTIKEL_';
				break;
			}

			$export->Export($Prefab . date('d_m_Y_H_i') . '',$table, @$_REQUEST['format'], @$_REQUEST['groups']);
		}

		$groups = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_user_groups");
		while($row = $sql->fetcharray())
		{
			array_push($groups, $row);
		}

		$GLOBALS['tmpl']->assign('ProductCategs', $this->fetchShopNavi(1));
		$GLOBALS['tmpl']->assign('groups', $groups);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_dataexport.tpl'));
	}

	//=======================================================
	//=======================================================
	function gethost($ip)
	{
	   if ($ip=='') $ip = $_SERVER['REMOTE_ADDR'];
	   $longisp = @gethostbyaddr($ip);
	   $isp = explode('.', $longisp);
	   $isp = array_reverse($isp);
	   $tmp = @$isp[1];
	   if (preg_match("/\<(org?|com?|net)\>/i", $tmp)) {
		   $myisp = @$isp[2].'.'.@$isp[1].'.'.@$isp[0];
	   } else {
		   $myisp = @$isp[1].'.'.@$isp[0];
	   }
	   if (preg_match("/[0-9]{1,3}\.[0-9]{1,3}/", $myisp))
		 return 'ISP lookup failed.';
	   return $myisp;
	}


	//=======================================================
	// Kunden-Downloads (wie Koobi) (Загрузки клиента как в Koobi)
	//=======================================================
	function listFiles()
	{
		$files = array();
		$sql = $GLOBALS['db']->Query("SELECT
			*
		FROM
			" . PREFIX . "_modul_shop_artikel");

		while($row = $sql->fetchrow())
		{
			$sql_2 = $GLOBALS['db']->Query("SELECT ArtId FROM " . PREFIX . "_modul_shop_artikel_downloads WHERE ArtId = '$row->Id' LIMIT 1");
			$num = $sql_2->numrows();
			if($num==1) array_push($files, $row);
		}
		return $files;
	}

	function shopDownloads($tpl_dir)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] != '')
		{
			switch($_REQUEST['sub'])
			{
				// Datei neu
				case 'new':
					$q = "INSERT INTO " . PREFIX . "_modul_shop_downloads
					(
						Id,
						Benutzer,
						ArtikelId,
						DownloadBis,
						Lizenz
					) VALUES (
						'',
						'".addslashes($_REQUEST['User'])."',
						'".addslashes($_REQUEST['file'])."',
						'".mktime(23,59,59,$_REQUEST['filetimeMonth'],$_REQUEST['filetimeDay'],$_REQUEST['filetimeYear'])."',
						''
					)";
					$sql = $GLOBALS['db']->Query($q);
				break;


				// Aktualisieren
				case 'save':
					if(isset($_REQUEST['Id']))
					{
						foreach($_POST['Id'] as $id=>$post)
						{
							// Speichern
							$q = "UPDATE " . PREFIX . "_modul_shop_downloads
								SET
									Lizenz = '" . @$_POST['Lizenz'][$id] ."',
									UrlLizenz = '" . @$_POST['UrlLizenz'][$id] ."',
									DownloadBis = '" . mktime(23,59,59,$_POST['Monat'][$id],$_POST['Tag'][$id],$_POST['Jahr'][$id]) ."',
									Gesperrt = '" . @$_POST['Gesperrt'][$id] ."',
									GesperrtGrund = '" . @$_POST['GesperrtGrund'][$id] ."',
									KommentarBenutzer = '" . addslashes(@$_POST['KommentarBenutzer'][$id]) ."',
									KommentarAdmin = '" . addslashes(@$_POST['KommentarAdmin'][$id]) ."'
								WHERE
									id='{$id}'";
							$sql = $GLOBALS['db']->Query($q);
						}

						// Lцschen
						if(isset($_POST['Del']) && $_POST['Del']>=0)
						{
							foreach($_POST['Del'] as $id=>$post)
							{
								$sql = $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_shop_downloads WHERE id='{$id}'");
							}
						}
					}
				break;
			}
		}

		$Items = array();
		$sql = $GLOBALS['db']->Query("SELECT
			a.*,
			b.Artname
		FROM
			" . PREFIX . "_modul_shop_downloads as a,
			" . PREFIX . "_modul_shop_artikel as b
		WHERE
			a.Benutzer = '".addslashes($_REQUEST['User'])."'
		AND
			b.ArtNr = a.ArtikelId
		");
		while($row = $sql->fetchrow())
		{
			$row->TagEnde = date("d", $row->DownloadBis);
			$row->MonatEnde = date("m", $row->DownloadBis);
			$row->JahrEnde = date("Y", $row->DownloadBis);
			array_push($Items, $row);
		}

		$GLOBALS['tmpl']->assign('Start', date("Y")-6);



		$GLOBALS['tmpl']->assign('Items', $Items);
		$GLOBALS['tmpl']->assign('Files', $this->listFiles());
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_customer_downloads.tpl'));

	}


	//=======================================================
	//=======================================================
	function customerDiscounts($tpl_dir)
	{
		define("CUSTOMER_DISCOUNTS",1);
		@include_once(BASE_DIR . "/modules/shop/internals/customer_discounts.php");
	}

	//=======================================================
	//=======================================================
	function shopImport($tpl_dir)
	{
		define("ARTICLE_IMPORT",1);
		@include_once(BASE_DIR . "/modules/shop/class.csv.php");
		@include_once(BASE_DIR . "/modules/shop/internals/product_import.php");
	}

	//=======================================================
	//=======================================================
	function userImport($tpl_dir)
	{
		define("USER_IMPORT",1);
		@include_once(BASE_DIR . "/modules/shop/class.csv.php");
		@include_once(BASE_DIR . "/modules/shop/internals/user_import.php");
	}


	//=======================================================
	// Staffelpreise (Цены на количестве)
	//=======================================================
	function staffelPreise($tpl_dir)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] != '')
		{
			switch($_REQUEST['sub'])
			{
				// Nei
				case 'new':
					$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_shop_staffelpreise
						(
							Id,
							StkVon,
							StkBis,
							Preis,
							ArtId
						) VALUES (
							'',
							'" . (int)$_POST['Von'] . "',
							'" . (int)$_POST['Bis'] . "',
							'" . str_replace(',','.',@$_POST['Preis']) . "',
							'" . (int)$_REQUEST['Id'] . "'
						)");
				break;

				// Aktualisieren
				case 'save':
					foreach($_POST['StkVon'] as $id=>$stk)
					{
						if((is_numeric($_POST['StkVon'][$id]) && $_POST['StkVon'][$id] > 0) && (is_numeric($_POST['StkBis'][$id]) && $_POST['StkBis'][$id] > $_POST['StkVon'][$id]))
						{
							$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_staffelpreise
								SET
									StkVon = '" . (int)$_POST['StkVon'][$id] . "',
									StkBis = '" . (int)$_POST['StkBis'][$id] . "',
									Preis = '" . str_replace(',','.',@$_POST['Preis'][$id]) . "'
								WHERE
									Id = '$id'");
						}

						if(isset($_POST['Del']) && $_POST['Del']>0)
						{
							foreach($_POST['Del'] as $id=>$del)
							{
								$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_shop_staffelpreise  WHERE Id = '$id'");
							}
						}
					}
				break;
			}
		}

		$items = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_staffelpreise WHERE ArtId = '$_GET[Id]' ORDER BY StkVon ASC");
		while($row = $sql->fetchrow())
		{
			array_push($items, $row);
		}

		$GLOBALS['tmpl']->assign('Stf', $items);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_staffelpreise.tpl'));
	}


	//=======================================================
	// Fьr den Import von Benutzern aus Koobi (Импорт пользователей из Koobi)
	//=======================================================
	function dream4_userImport($Prefix='',$Truncate='')
	{
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . $Prefix . "_user WHERE uid != 1");
		$num = $sql->numrows();

		// Gibt es Benutzer in der alten Tabelle?
		if($num>0)
		{

			$Truncate=1;
			if($Truncate==1)
			{
				$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_forum_userprofile WHERE BenutzerId != 1 AND BenutzerId != '" . $_SESSION['cp_benutzerid'] . "'");
				$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_users WHERE Id != 1 AND Id != '" . $_SESSION['cp_benutzerid'] . "'");
				$GLOBALS['db']->Query("ALTER TABLE " . PREFIX . "_modul_forum_userprofile PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =1");
				$GLOBALS['db']->Query("ALTER TABLE " . PREFIX . "_users PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =1");
			}



			while($row = $sql->fetchrow())
			{
				$q = "INSERT INTO " . PREFIX . "_modul_forum_userprofile
					(
						Id,
						BenutzerId,
						BenutzerName,
						GroupIdMisc,
						Beitraege,
						ZeigeProfil,
						Signatur,
						Icq,
						Aim,
						Skype,
						Emailempfang,
						Pnempfang,
						Avatar,
						AvatarStandard,
						Webseite,
						Unsichtbar,
						Interessen,
						Email,
						Registriert,
						GeburtsTag
					) VALUES (
						'',
						'$row->uid',
						'$row->uname',
						'$row->group_id_misc',
						'$row->user_posts',
						'$row->show_public',
						'$row->user_sig',
						'$row->user_icq',
						'$row->user_aim',
						'$row->user_skype',
						'" . (($row->user_viewemail=='yes' || $row->user_viewemail=='') ? 1 : 0) . "',
						'" . (($row->user_canpn=='yes') ? 1 : 0) . "',
						'$row->user_avatar',
						'$row->usedefault_avatar',
						'$row->url',
						'" . (($row->invisible=='yes') ? 1 : 0) . "',
						'$row->user_interests',
						'$row->email',
						'$row->user_regdate',
						'$row->user_birthday'
					)";
				$GLOBALS['db']->Query($q);

				$q = "INSERT INTO " . PREFIX . "_users
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
						BenutzergruppeMisc,
						Registriert,
						Status,
						ZuletztGesehen,
						Land,
						Geloescht,
						GeloeschtDatum,
						emc,
						IpReg,
						KennTemp,
						Firma,
						UStPflichtig,
						GebTag
					) VALUES (
						'".$row->uid."',
						'".$row->pass."',
						'".$row->email."',
						'".$row->street."',
						'',
						'".$row->zip."',
						'".$row->user_from."',
						'".$row->phone."',
						'".$row->fax."',
						'',
						'".$row->name."',
						'".$row->lastname."',
						'".$row->uname."',
						'".$row->ugroup."',
						'".$row->group_id_misc."',
						'".$row->user_regdate."',
						'".$row->status."',
						'".$row->last_login."',
						'".$row->country."',
						'',
						'',
						'',
						'',
						'".$row->passtemp."',
						'".$row->company."',
						'1',
						'".$row->user_birthday."')";
				if($row->uid != 2) $GLOBALS['db']->Query($q);
				//echo "<pre>$q</pre>";
			}
		}
	}

	function imp()
	{
		$this->dream4_userImport('kpro');

		$_REQUEST['import_from_koobi'] = 1;
		if(isset($_REQUEST['import_from_koobi']) && $_REQUEST['import_from_koobi'] == 1)
		{
			//$GLOBALS['db']->Query("TRUNCATE TABLE " . PREFIX . "_modul_shop_artikel_downloads");
			$GLOBALS['db']->Query("TRUNCATE TABLE " . PREFIX . "_modul_shop_downloads");
			$sql = $GLOBALS['db']->Query("SELECT * FROM kpro_private_files_items");
			while($row = $sql->fetchrow())
			{
				$sql_ts = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_shop_artikel WHERE ArtNr='$row->artnumber' ");
				$row_ts = $sql_ts->fetchrow();

				switch($row->type)
				{
					case '0': $ft = 'full'; break;
					case '': $ft = 'full'; break;
					case '1': $ft = 'other'; break;
					case '2': $ft = 'bugfix'; break;
					case '3': $ft = 'update'; break;
					case '4': $ft = 'other'; break;
				}

				if(!empty($row_ts->Id))
				{
					$q = "INSERT INTO
						" . PREFIX . "_modul_shop_artikel_downloads
						(
							Id,
							ArtId,
							Datei,
							DateiTyp,
							Bild,
							Titel,
							Beschreibung,
							Position,
							Datum
						) VALUES (
							'',
							'$row_ts->Id',
							'$row->file_name',
							'$ft',
							'',
							'$row->file_name',
							'$row->text',
							'1',
							'$row->ctime'
						)";
						//$GLOBALS['db']->Query($q);
						//echo "<pre>$q</pre>";
				}
			}

			$sql = $GLOBALS['db']->Query("SELECT * FROM kpro_private_files");
			while($row = $sql->fetchrow())
			{
				$q = "INSERT INTO
					" . PREFIX . "_modul_shop_downloads
					(
						Id,
						Benutzer,
						PName,
						ArtikelId,
						DownloadBis,
						Lizenz,
						Downloads,
						UrlLizenz,
						KommentarBenutzer,
						KommentarAdmin,
						Gesperrt,
						GesperrtGrund,
						Position
					) VALUES (
						'',
						'$row->uid',
						'',
						'$row->artnumber',
						'$row->ptilltime',
						'$row->plicdata',
						'$row->downloads',
						'$row->url',
						'$row->comment_user',
						'$row->comment_admin',
						'$row->locked',
						'$row->locked_reason',
						'1'
					)";
				$GLOBALS['db']->Query($q);
			}

			$sql = $GLOBALS['db']->Query("SELECT * FROM kpro_shop_orders");
			while($row = $sql->fetchrow())
			{
				$row->articles = explode(',', $row->articles);
				$row->articles = serialize($row->articles);

				/*
						wait', 'progress', 'ok', 'failed', 'download')
					1 = VORK
					2 = NACHNAH
					3 = RECH
					4 = PayPal
					5 = KK
				*/

				switch($row->payment_id)
				{
					case '1' : $payid = 1; break;
					case '2' : $payid = 2; break;
					case '3' : $payid = 5; break;
					case '5' : $payid = 4; break;
				}

				$q = "INSERT INTO " . PREFIX ."_modul_shop_bestellungen (
					Id,
					Benutzer,
					TransId,
					Datum,
					Gesamt,
					USt,
					Artikel,
					Artikel_Vars,
					RechnungText,
					RechnungHtml,
					NachrichtBenutzer,
					Ip,
					ZahlungsId,
					VersandId,
					KamVon,
					Gutscheincode,
					Bestell_Email,
					Liefer_Firma,
					Liefer_Abteilung,
					Liefer_Vorname,
					Liefer_Nachname,
					Liefer_Strasse,
					Liefer_Hnr,
					Liefer_PLZ,
					Liefer_Ort,
					Liefer_Land,
					Rech_Firma,
					Rech_Abteilung,
					Rech_Vorname,
					Rech_Nachname,
					Rech_Strasse,
					Rech_Hnr,
					Rech_PLZ,
					Rech_Ort,
					Rech_Land,
					Status
				) VALUES (
					'',
					'$row->uid',
					'$row->control',
					'$row->ordertime',
					'$row->ovall',
					'$row->ust',
					'$row->articles',
					'',
					'$row->calculation2',
					'$row->calculation',
					'$row->Bemerkung',
					'$row->ip',
					'$payid',
					'',
					'',
					'$row->coupon_id',
					'',
					'',
					'$row->shipping_company',
					'$row->shipping_firstname',
					'$row->shipping_lastname',
					'$row->shipping_street',
					'$row->shipping_streetnumber',
					'$row->shipping_zip',
					'$row->shipping_town',
					'$row->shipping_country',
					'$row->rng_company',
					'$row->rng_company_reciever',
					'$row->rng_firstname',
					'$row->rng_lastname',
					'$row->rng_street',
					'$row->rng_streetnumber',
					'$row->rng_zip',
					'$row->rng_town',
					'$row->rng_country',
					'$row->status_o'
					)";

					$GLOBALS['db']->Query($q);
			}




		}
	}



}
?>