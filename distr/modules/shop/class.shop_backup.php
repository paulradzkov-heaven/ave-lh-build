<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

//include(BASE_DIR . '/modules/shop/inc/rewrite_rules.php');
class Shop
{
	var $_sep_dec = ',';
	var $_sep_thousand = '.';
	var $_product_detail = 'index.php?module=shop&amp;action=product_detail&amp;product_id=';
	var $_delete_item = 'index.php?module=shop&amp;action=delitem&amp;product_id=';
	var $_add_item = 'index.php?add=1&amp;module=shop&amp;action=addtobasket&amp;product_id=';
	var $_add_item_wishlist = 'index.php?add=1&amp;module=shop&amp;action=addtowishlist&amp;product_id=';
	var $_link_manufaturer = 'index.php?module=shop&amp;manufacturer=';
	var $_expander = '&nbsp;&nbsp;&nbsp;&nbsp;';
	var $_shop_navi = 'shop_navi.tpl';
	var $_shop_start_tpl = 'shop_start.tpl';
	var $_shop_product_detailpage = 'shop_product_detail.tpl';
	var $_limit_shoparticles = 1;

	function checkShop()
	{
		if($this->getShopSetting('Aktiv') != 1)
		{
			$tpl_out = $GLOBALS['mod']['config_vars']['NotActive'];
			define("MODULE_CONTENT", $tpl_out);
		}
	}

	function transId($c=0)
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

	function textReplace($text)
	{
		$out = str_replace('&euro;', '€', $text);
		$out = str_replace('&auml;', 'ä', $out);
		$out = str_replace('&Auml;', 'Ä', $out);
		$out = str_replace('&uuml;', 'ü', $out);
		$out = str_replace('&Uuml;', 'Ü', $out);
		$out = str_replace('&ouml;', 'ö', $out);
		$out = str_replace('&Ouml;', 'Ö', $out);
		$out = str_replace('&szlig;', 'ß', $out);
		$out = strip_tags($out);
		return $out;
	}

	function getShopSetting($feld)
	{
		$sql = $GLOBALS['db']->Query("SELECT $feld FROM " . PREFIX . "_modul_shop");
		$row = $sql->fetchrow();
		$sql->close();
		return $row->$feld;
	}

	function getTplSettings()
	{
		$sql = $GLOBALS['db']->Query("SELECT
			KategorienStart,
			KategorienSons,
			ShopWillkommen,
			ZufallsAngebot,
			ZufallsAngebotKat,
			WaehrungSymbol,
			BestUebersicht,
			ShopFuss,
			Merkliste,
			Topseller,
			TemplateArtikel,
			Vorschaubilder,
			Topsellerbilder,
			Agb,
			GastBestellung,
			Kommentare,
			KommentareGast,
			WaehrungSymbol2,
			Waehrung2,
			Waehrung2Multi,
			ZeigeWaehrung2
		FROM
			" . PREFIX . "_modul_shop
		WHERE Id = '1'");
		$row = $sql->fetchrow();
		$sql->close();

		$categ = (isset($_REQUEST['categ']) && $_REQUEST['categ'] != '') ? (int)$_REQUEST['categ'] : '';

		$this->globalProductInfo();

		define('WidthTsThumb', @$row->Topsellerbilder);
		define('GastBestellung', @$row->GastBestellung);
		define('Kommentare', @$row->Kommentare);
		define('WidthThumbs', @$row->Vorschaubilder);
		define('WidthThumbsTopseller', @$row->Topsellerbilder);
		define('Waehrung2', ((@$row->Waehrung2 != '') ? @$row->Waehrung2 : ''));
		define('WaehrungSymbol2', ((@$row->WaehrungSymbol2) ? @$row->WaehrungSymbol2 : ''));
		define('Waehrung2Multi', @$row->Waehrung2Multi);

		$GLOBALS['tmpl']->assign('ZeigeWaehrung2', @$row->ZeigeWaehrung2);
		$GLOBALS['tmpl']->assign('Currency2', @$row->WaehrungSymbol2);
		$GLOBALS['tmpl']->assign('Kommentare', @$row->Kommentare);
		$GLOBALS['tmpl']->assign('KommentareGast', @$row->KommentareGast);
		$GLOBALS['tmpl']->assign('GastBestellung', @$row->GastBestellung);
		$GLOBALS['tmpl']->assign('ShopAgb', strip_tags($row->Agb,'<b><strong><br><p><br /><em><i>'));
		$GLOBALS['tmpl']->assign('WidthThumb', @$row->Vorschaubilder);
		$GLOBALS['tmpl']->assign('WidthTsThumb', @$row->Topsellerbilder);
		$GLOBALS['tmpl']->assign('MyIp', $_SERVER['REMOTE_ADDR']);
		$GLOBALS['tmpl']->assign('TemplateArtikel', @$row->TemplateArtikel);
		$GLOBALS['tmpl']->assign('TopsellerActive', @$row->Topseller);
		$GLOBALS['tmpl']->assign('FooterText', @$row->ShopFuss);
		$GLOBALS['tmpl']->assign('WishListActive', @$row->Merkliste);
		$GLOBALS['tmpl']->assign('CanOrderHere', @$row->BestUebersicht);
		$GLOBALS['tmpl']->assign('Currency', @$row->WaehrungSymbol);
		$GLOBALS['tmpl']->assign('KategorienStart', @$row->KategorienStart);
		$GLOBALS['tmpl']->assign('KategorienSons', @$row->KategorienSons);
		$GLOBALS['tmpl']->assign('ShopWillkommen', @$row->ShopWillkommen);
		$GLOBALS['tmpl']->assign('RandomOfferKateg', ((@$row->ZufallsAngebotKat==1) ? $this->RandomOffer($categ) : ''));
		$GLOBALS['tmpl']->assign('RandomOffer', ((@$row->ZufallsAngebot==1) ? $this->RandomOffer() : ''));
		$GLOBALS['tmpl']->assign('TopSeller', $this->topSeller());
		$GLOBALS['tmpl']->assign('ShopNavi', $this->fetchShopNavi());
		$GLOBALS['tmpl']->assign('UserPanel', $this->shopLogin());
		$GLOBALS['tmpl']->assign('MyOrders', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'shop_myorders.tpl'));
		$GLOBALS['tmpl']->assign('Basket', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'shop_basket_small.tpl'));
		$GLOBALS['tmpl']->assign('Search', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'shop_smallsearch.tpl'));
		$GLOBALS['tmpl']->assign('Topseller', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'shop_topseller.tpl'));
		$GLOBALS['tmpl']->assign('TopNav', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'shop_topnav.tpl'));
		$GLOBALS['tmpl']->assign('InfoBox', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'shop_infobox.tpl'));
	}

	//=======================================================
	// Zufallsangebot mit Berücksichtigung von Kategorien
	//=======================================================
	function RandomOffer($categ='')
	{
		$db_categ = ($categ != '') ? " AND KatId = '$categ'" : "";
		$sql = $GLOBALS['db']->Query("SELECT
			Id,
			ArtNr,
			KatId,
			ArtName,
			Angebot,
			AngebotBild
		FROM
			" . PREFIX . "_modul_shop_artikel
		WHERE
			Angebot = '1' AND Aktiv = '1' AND Erschienen <= '".time()."' $db_categ
		ORDER BY rand() LIMIT 1");
		$row = $sql->fetchrow();
		if(is_object($row))
		{
			$row->Detaillink = $this->shopRewrite($this->_product_detail . $row->Id . '&amp;categ=' . $row->KatId . '&amp;navop=' . getParentShopcateg($row->KatId));
			$img = (file_exists(BASE_DIR . '/' . $row->AngebotBild)) ? "<!-- START OFFER --><a title=\"$row->ArtName\" href=\"$row->Detaillink\"><img src=\"$row->AngebotBild\" alt=\"$row->ArtName\" border=\"\"></a><br /><br /><!-- END OFFER -->" : "";
			return $img;
		}
	}

	//=======================================================
	// Dateiendung ermitteln
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

	//=======================================================
	// Topseller mit Berücksichtigung von Kategorien
	//=======================================================
	function topSeller($type='')
	{
		$topSeller = array();
		$db_categ = (isset($_REQUEST['categ']) && (int)$_REQUEST['categ'] != '') ? " AND KatId = '".$_REQUEST['categ']."'" : "";
		$sql = $GLOBALS['db']->Query("SELECT
				Id,
				ArtNr,
				KatId,
				ArtName,
				TextKurz,
				Bild,
				Preis
			FROM
				" . PREFIX . "_modul_shop_artikel
			WHERE
				Aktiv = '1' AND Erschienen <= '".time()."'  $db_categ
			ORDER BY Bestellungen DESC LIMIT 5");
		while($row = $sql->fetchrow())
		{
			$row->Img = "";
			$row->Preis = $this->getDiscountVal($row->Preis);

			if(defined("WaehrungSymbol2") && defined("Waehrung2") && defined("Waehrung2Multi"))
			{
				@$row->PreisW2 = ($row->Preis * Waehrung2Multi);
			}

			if(file_exists('modules/shop/thumbnails/shopthumb__' . WidthThumbsTopseller . '__' . $row->Bild))
			{
				$row->Img = "<img src=\"modules/shop/thumbnails/shopthumb__" . WidthThumbsTopseller . "__" . $row->Bild . "\" alt=\"\" border=\"\" />";
			} else {
				$type = $this->getEndung($row->Bild);
				$row->Img = "<img src=\"modules/shop/thumb.php?file=$row->Bild&amp;type=$type&amp;x_width=" . WidthTsThumb . "\" alt=\"\" border=\"\" />";
			}


			$row->TextKurz =  $row->Img . substr(strip_tags($row->TextKurz,'<b>,<strong>,<em>,<i>'), 0, 250) . '...';
			$row->Detaillink = $this->shopRewrite($this->_product_detail . $row->Id . '&amp;categ=' . $row->KatId . '&amp;navop=' . getParentShopcateg($row->KatId));
			array_push($topSeller, $row);
		}
		return $topSeller;
	}

	//=======================================================
	// Hilfeseiten
	//=======================================================
	function getInfoPage()
	{
		$sql = $GLOBALS['db']->Query("SELECT Agb,VersandInfo,DatenschutzInf,Impressum FROM " . PREFIX . "_modul_shop LIMIT 1");
		$row = $sql->fetchrow();

		$Inf = '';
		$InfName = '';

		if(isset($_REQUEST['page']) && $_REQUEST['page'] != '')
		{
			switch($_REQUEST['page'])
			{
				case 'shippinginf':
					$Inf = $row->VersandInfo;
					$InfName = $GLOBALS['mod']['config_vars']['ShippingInf'];
				break;

				case 'datainf':
					$Inf = $row->DatenschutzInf;
					$InfName = $GLOBALS['mod']['config_vars']['DataInf'];
				break;

				case 'imprint':
					$Inf = $row->Impressum;
					$InfName = $GLOBALS['mod']['config_vars']['Imprint'];
				break;

				case 'agb':
					$Inf = $row->Agb;
					$InfName = $GLOBALS['mod']['config_vars']['AGB'];
				break;
			}
		}

		$GLOBALS['tmpl']->assign('Inf', $Inf);
		$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'shop_infopage.tpl');
		define("MODULE_CONTENT", $tpl_out);
		define("MODULE_SITE",  $InfName);
	}

	function myWishlist()
	{
		$GLOBALS['tmpl']->assign('MyWishlist', $this->showWishlist());
		$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'shop_wishlist.tpl');
		define("MODULE_CONTENT", $tpl_out);
		define("MODULE_SITE",  $GLOBALS['mod']['config_vars']['Wishlist']);
	}

	//=======================================================
	// Ausgabe der Loginform bzw. Benutzer-Panel
	// Modul 'LOGIN' ist hier Vorraussetzung!
	//=======================================================
	function shopLogin()
	{
		$tpl_dir = BASE_DIR . "/modules/login/templates/";
		$lang_file = BASE_DIR . "/modules/login/lang/" . STD_LANG . ".txt";

	//	$login = new Login;

		if(!isset($_SESSION["cp_benutzerid"]))
			return $this->displayLoginform($tpl_dir,$lang_file);
		else
			return $this->displayPanel($tpl_dir,$lang_file);
	}

	function displayLoginform($tpl_dir,$lang_file)
	{
		$GLOBALS['tmpl']->config_load($lang_file);
		$config_vars = $GLOBALS['tmpl']->get_config_vars();
		$GLOBALS['tmpl']->assign("config_vars", $config_vars);
		$GLOBALS['tmpl']->assign("active", 1);
		$output = $GLOBALS['tmpl']->fetch($tpl_dir . "loginform.tpl");
		return $output;
	}

	function displayPanel($tpl_dir,$lang_file)
	{
		$GLOBALS['tmpl']->config_load($lang_file);
		$config_vars = $GLOBALS['tmpl']->get_config_vars();
		$GLOBALS['tmpl']->assign("config_vars", $config_vars);
		$output = $GLOBALS['tmpl']->fetch($tpl_dir . "userpanel.tpl");
		return $output;
	}

	//=======================================================
	// Zahlungsinfo
	//=======================================================
	function PaymentInfo($cpt,$id)
	{
		$sql = $GLOBALS['db']->Query("SELECT Name,Beschreibung FROM " . PREFIX . "_modul_shop_zahlungsmethoden WHERE Id = '$id'");
		$row = $sql->fetchrow();
		//$GLOBALS['tmpl']->assign('config_vars', $config_vars);
		$GLOBALS['tmpl']->assign('cp_theme', $cpt);
		$GLOBALS['tmpl']->assign('row', $row);
		$GLOBALS['tmpl']->display($GLOBALS['mod']['tpl_dir'] . 'shop_paymentinfo.tpl');
	}

	//=======================================================
	// URL's umschreiben
	//=======================================================
	function shopRewrite($print_out)
	{
		if(defined("CP_REWRITE") && CP_REWRITE==1)
		{
			$print_out = shopRewrite($print_out);
		}
		return $print_out;
	}

	//=======================================================
	// Alle Steuersätze anzeigen
	//=======================================================
	function showVatZones($unset='')
	{
		$vatPercent = array();
		$sql = $GLOBALS['db']->Query("SELECT Id,Wert FROM " . PREFIX . "_modul_shop_ust WHERE Wert > '0.00'");
		while($row = $sql->fetchrow())
		{
			if($unset==1) unset($_SESSION[$row->Wert]);
			array_push($vatPercent, $row);
		}
		return $vatPercent;
	}

	//=======================================================
	// Steuersatz - Session zurücksetzen
	//=======================================================
	function resetVatZoneSessions()
	{
		$sql = $GLOBALS['db']->Query("SELECT Id,Wert FROM " . PREFIX . "_modul_shop_ust WHERE Wert > '0.00'");
		while($row = $sql->fetchrow()) $_SESSION[$row->Wert] = '';
	}

	//=======================================================
	// Eine Einstellung auslesen
	//=======================================================
	function getSetting($zeile)
	{
		$sql = $GLOBALS['db']->Query("SELECT $zeile FROM ".PREFIX."_modul_shop LIMIT 1");
		$row = $sql->fetchrow();
		$sql->close();
		return $row->$zeile;
	}

	//=======================================================
	// Downloads für einen Kunden anzeigen
	//=======================================================
	// Downloadfunktion
	function cpReadFile($filename,$retbytes=true)
	{
	   $chunksize = 1*(1024*1024);
	   $buffer = '';
	   $cnt =0;

	   $handle = fopen($filename, 'rb');
	   if ($handle === false)
	   {
		   return false;
	   }
	   while (!feof($handle))
	   {
		   $buffer = fread($handle, $chunksize);
		   echo $buffer;
		   flush();
		   if ($retbytes) {
			   $cnt += strlen($buffer);
		   }
	   }
		   $status = fclose($handle);
	   if ($retbytes && $status)
	   {
		   return $cnt;
	   }
	   return $status;
	}

	// Aktionen
	function myDownloads()
	{
		$this->globalProductInfo();

		if(isset($_SESSION['cp_benutzerid']) && $_SESSION['cp_benutzerid'] != '')
		{
			if(isset($_REQUEST['sub']) && $_REQUEST['sub'] != '')
			{
				switch($_REQUEST['sub'])
				{
					case 'getfile':
						$download = false;
					 	//=======================================================
						// Update am 07.1.2006
						// Downloads wie bei Koobi :)
						//=======================================================
						$sql = $GLOBALS['db']->Query("SELECT
							a.*,
							b.Datei
						FROM
							" . PREFIX . "_modul_shop_downloads as a,
							" . PREFIX . "_modul_shop_artikel_downloads as b
						WHERE
							a.ArtikelId = '" . @addslashes($_REQUEST['FileId']) . "' AND
							a.Benutzer = '" . $_SESSION['cp_benutzerid'] . "' AND
							((a.DownloadBis >= '" . time(). "') OR (b.DateiTyp='other')) AND
							a.Gesperrt != 1 AND
							b.Id = '" . @addslashes($_GET['getId']) . "'
						");

						$row = $sql->fetchrow();
						$num = $sql->numrows();
						if($num >= 1) $download = true;

						if($download == true)
						{
							ob_start();
							#ob_end_flush();
							#ob_end_clean();
							header("Cache-control: private");
							header("Content-type: application/octet-stream");
							header("Content-disposition:attachment; filename=" . str_replace(array(' '), '', $row->Datei));
							@$this->cpReadFile(BASE_DIR . '/modules/shop/files/' . $row->Datei);
							exit;
						} else {
							echo "<script>alert('".$GLOBALS['mod']['config_vars']['DownloadJsError']."');</script>";
						}
					break;
				}
			}

			// Nur anzeigen Start

			$downloads = array();
		    //=======================================================
			// Update am 07.1.2006
			// Downloads wie bei Koobi :)
			//=======================================================
			$downloads = array();
			$sql = $GLOBALS['db']->Query("SELECT
				a.*,
				b.Id as ARTIKELNUMMER,
				b.ArtName
			FROM
				" . PREFIX . "_modul_shop_downloads as a,
				" . PREFIX . "_modul_shop_artikel as b
			WHERE
				a.Benutzer = '" . $_SESSION['cp_benutzerid'] . "' AND
				b.ArtNr = a.ArtikelId
			ORDER BY
				a.Position ASC");

			while($row = $sql->fetchrow())
			{
				if(is_object($row))
				{
					// Vollversionen
					$DataFiles = array();
					$sql_df = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_artikel_downloads WHERE ArtId = '$row->ARTIKELNUMMER' AND DateiTyp='full' ORDER BY Position ASC,Id DESC");
					while($row_df = $sql_df->fetchrow())
					{
						if($row->DownloadBis < time())
						{
							$row_df->Abgelaufen = 1;
						}
						$row_df->Beschreibung = str_replace('"','&quot;',$row_df->Beschreibung);
						$row_df->size = (file_exists(BASE_DIR . '/modules/shop/files/' . $row_df->Datei) ) ? round(@filesize(BASE_DIR . '/modules/shop/files/'.$row_df->Datei)/1024,2) : '';
						array_push($DataFiles, $row_df);
					}
					$row->DataFiles = $DataFiles;

					// Updates
					$DataFilesUpdates = array();
					$sql_df = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_artikel_downloads WHERE ArtId = '$row->ARTIKELNUMMER' AND DateiTyp='update' ORDER BY Position ASC");
					while($row_df = $sql_df->fetchrow())
					{
						$row_df->Beschreibung = str_replace('"','&quot;',$row_df->Beschreibung);
						$row_df->size = (file_exists(BASE_DIR . '/modules/shop/files/' . $row_df->Datei) ) ? round(@filesize(BASE_DIR . '/modules/shop/files/'.$row_df->Datei)/1024,2) : '';
						array_push($DataFilesUpdates, $row_df);
					}
					$row->DataFilesUpdates = $DataFilesUpdates;

					// Sonstiges
					$DataFilesOther = array();
					$sql_df = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_artikel_downloads WHERE ArtId = '$row->ARTIKELNUMMER' AND DateiTyp='other' ORDER BY Position ASC");
					while($row_df = $sql_df->fetchrow())
					{
						$row_df->Beschreibung = str_replace('"','&quot;',$row_df->Beschreibung);
						$row_df->size = (file_exists(BASE_DIR . '/modules/shop/files/' . $row_df->Datei) ) ? round(@filesize(BASE_DIR . '/modules/shop/files/'.$row_df->Datei)/1024,2) : '';
						array_push($DataFilesOther, $row_df);
					}
					$row->DataFilesOther = $DataFilesOther;

					// Bugfixes
					$DataFilesBugfixes = array();
					$sql_df = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_artikel_downloads WHERE ArtId = '$row->ARTIKELNUMMER' AND DateiTyp='bugfix' ORDER BY Position ASC");
					while($row_df = $sql_df->fetchrow())
					{
						$row_df->Beschreibung = str_replace('"','&quot;',$row_df->Beschreibung);
						$row_df->size = (file_exists(BASE_DIR . '/modules/shop/files/' . $row_df->Datei) ) ? round(@filesize(BASE_DIR . '/modules/shop/files/'.$row_df->Datei)/1024,2) : '';
						array_push($DataFilesBugfixes, $row_df);
					}
					$row->DataFilesBugfixes = $DataFilesBugfixes;
				}


				array_push($downloads, $row);
			}

			$GLOBALS['tmpl']->assign('downloads', $downloads);
			$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'shop_mydownloads.tpl');
			$tpl_out = $this->shopRewrite($tpl_out);

			define("MODULE_CONTENT", $tpl_out);
			define("MODULE_SITE", $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['DownloadsOverviewShowLink']);
			// Nur anzeigen Ende
		}
	}


	//=======================================================
	// Bestellungen für einen Kunden anzeigen
	//=======================================================
	function myOrders()
	{
		if(isset($_SESSION['cp_benutzerid']) && $_SESSION['cp_benutzerid'] != '')
		{
			if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'request')
			{
				$globals = new Globals;
				$GLOBALS['globals']->cp_mail($_SESSION['cp_email'], stripslashes($_POST['text']), $_POST['subject'], $_SESSION['cp_email'], $_SESSION['cp_uname'], "text", "");
				$GLOBALS['tmpl']->assign('orderRequestOk', 1);
			}

			$this->globalProductInfo();

			$my_orders = array();
			$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_bestellungen WHERE Benutzer = '" . $_SESSION['cp_benutzerid'] . "' ORDER BY Datum DESC");
			while($row = $sql->fetchrow())
			{
				array_push($my_orders, $row);
			}

			$GLOBALS['tmpl']->assign('my_orders', $my_orders);

			$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'shop_orders.tpl');
			$tpl_out = $this->shopRewrite($tpl_out);

			define("MODULE_CONTENT", $tpl_out);
			define("MODULE_SITE", $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['OrderOverviewShowLink']);
		}
	}

	//=======================================================
	// Kategorien erzeugen
	//=======================================================
	function getCategories($id, $prefix, &$dl, $extra = '', $sc = '')
	{
		$dbextra = (!empty($extra)) ? "and Id = '$extra' " : "";

		$query = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_kategorie WHERE Elter = '$id' $dbextra order by Rang ASC");
		if (!$query->numrows()) return;

		$num = $query->numrows();

		while ($item = $query->fetchrow())
		{
			$item->ntr = "";
			$item->visible_title = $prefix . '' . $item->KatName;
			$item->sub = ($item->Elter == 0) ? 0 : 1;

			$item->dyn_link = "index.php?module=shop&amp;categ=$item->Id&amp;parent=$item->Elter&amp;navop=" . (($item->sub==0) ? $item->Id : getParentShopcateg($item->Elter));
			$item->dyn_link = $this->shopRewrite($item->dyn_link);

			if($item->Elter == 0) $item->ntr = 1;

			$mdl = array();
			$this->getCategories($item->Id, $prefix, $mdl, $extra, $sc);
			$item->sub = $mdl;
			array_push($dl, $item);
		}

		return $dl;
	}

	//=======================================================
	// Shop - Navi erzeugen
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
			$item->dyn_link = $this->shopRewrite($item->dyn_link);

            array_push($entries,$item);
			if($admin == 1)
			{
				$this->getCategoriesSimple($item->Id, $prefix . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $entries, $admin, $dropdown);
			} else {
				$this->getCategoriesSimple($item->Id, $prefix . (($dropdown==1) ? '&nbsp;&nbsp;' : $this->_expander), $entries, $dropdown,  $item->Id);
			}
		}
    return $entries;
}

	//=======================================================
	// Shop - Navi
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
			$nav = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . $this->_shop_navi);
			return $nav;
			// $nav = $GLOBALS['tmpl']->display($GLOBALS['mod']['tpl_dir'] . $this->_shop_navi);
		} else {
			$ShopCategs = $this->getCategoriesSimple(0, '', $categs,'0',1);
			return $ShopCategs;
		}
	}


	//=======================================================
	// Shop - Startseite
	//=======================================================
	function lastArticles()
	{
		$limit = $this->getSetting('ArtikelMax');

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
		$db_sort = 'ORDER BY PosiStartseite ASC, Erschienen DESC';
		$nav_sort = '';

		if(isset($_REQUEST['recordset']) && is_numeric($_REQUEST['recordset']))
		{
			$limit = $_REQUEST['recordset'];
			$recordset_n = "&amp;recordset=$_REQUEST[recordset]";
		}

		if(isset($_REQUEST['categ']) && is_numeric($_REQUEST['categ']))
		{
			$dbextra = " AND (KatId = '" . (int)$_REQUEST['categ'] . "'
			OR
				KatId_Multi like '%," . (int)$_REQUEST['categ'] . ",%'
			OR
				KatId_Multi like '%," . (int)$_REQUEST['categ'] . "'
			OR
				KatId_Multi like '" . (int)$_REQUEST['categ'] . ",%'
				)";
			$dbextra_n = "&amp;categ=$_REQUEST[categ]";
		}

		if(isset($_REQUEST['manufacturer']) && is_numeric($_REQUEST['manufacturer']))
		{
			$manufacturer = " AND Hersteller = '$_REQUEST[manufacturer]'";
			$manufacturer_n = "&amp;manufacturer=$_REQUEST[manufacturer]";
		}

		if(isset($_REQUEST['product_query']) && $_REQUEST['product_query'] != '')
		{
			$product_query = " AND (ArtNr = '$_REQUEST[product_query]' OR ArtName LIKE '%$_REQUEST[product_query]%' OR TextKurz LIKE '%$_REQUEST[product_query]%')";
			$product_query_n = "&amp;product_query=" . urlencode($_REQUEST['product_query']);
		}

		if(isset($_REQUEST['price_start']) && is_numeric($_REQUEST['price_start']) && isset($_REQUEST['price_end']) && is_numeric($_REQUEST['price_end']) && $_REQUEST['price_start'] >= 0 && $_REQUEST['price_end'] >= 0 && $_REQUEST['price_start'] < $_REQUEST['price_end'])
		{
			$price_query = " AND (Preis BETWEEN $_REQUEST[price_start] AND $_REQUEST[price_end])";
			$price_query_n = "&amp;price_start=$_REQUEST[price_start]&amp;price_end=$_REQUEST[price_end]";
		}

		if(isset($_REQUEST['product_categ']) && is_numeric($_REQUEST['product_categ']))
		{
			$product_categ = " AND KatId = '$_REQUEST[product_categ]'";
			$product_categ_n = "&amp;product_categ=$_REQUEST[product_categ]";
		}

		if(isset($_REQUEST['sort']) && $_REQUEST['sort'] != '')
		{
			switch($_REQUEST['sort'])
			{
				case 'price_desc':
					$db_sort = "ORDER BY Preis DESC";
					$nav_sort = "&amp;sort=price_desc";
				break;

				case 'price_asc':
					$db_sort = "ORDER BY Preis ASC";
					$nav_sort = "&amp;sort=price_asc";
				break;

				case 'time_desc':
					$db_sort = "ORDER BY Erschienen DESC";
					$nav_sort = "&amp;sort=time_desc";
				break;

				case 'time_asc':
					$db_sort = "ORDER BY Erschienen ASC";
					$nav_sort = "&amp;sort=time_asc";
				break;
			}
		}

		$shopitems = array();


		$sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_shop_artikel WHERE Aktiv = '1' AND Erschienen <= '" . time() . "' $product_categ $price_query $product_query $dbextra $manufacturer $db_sort");
		$num = $sql->numrows();

		$limit = $limit;
		@$seiten = @ceil($num / $limit);
		$start = prepage() * $limit - $limit;

		$q = "SELECT *  FROM " . PREFIX . "_modul_shop_artikel WHERE Aktiv = '1' AND Erschienen <= '" . time() . "' $product_categ $price_query $product_query $dbextra $manufacturer $db_sort LIMIT $start,$limit";
		$sql = $GLOBALS['db']->Query($q);

		while($row = $sql->fetchrow())
		{
			$row = $this->globalProductInfo($row);
			array_push($shopitems, $row);
		}

		$the_nav_title = '';
		$nop= "";
		if(isset($_REQUEST['categ']) && is_numeric($_REQUEST['categ']))
		{
			$the_nav = $this->getNavigationPath((int)$_REQUEST['categ'], '', '1', (int)$_REQUEST['navop']);
			$nop = "&amp;navop=24";
			define("TITLE_EXTRA", strip_tags($the_nav));
			$GLOBALS['tmpl']->assign('topnav', $this->getNavigationPath((int)$_REQUEST['categ'], '', '1', (int)$_REQUEST['navop']));
		}

		$GLOBALS['tmpl']->assign('PageNumbers', $seiten);
		$page_nav = pagenav($seiten, prepage()," <a class=\"page_navigation\" href=\"index.php?module=shop$recordset_n$product_categ_n$price_query_n$product_query_n$dbextra_n$manufacturer_n$nav_sort$nop&amp;page={s}\">{t}</a> ");
		if($num > $limit) $GLOBALS['tmpl']->assign('page_nav', $page_nav);
		return $shopitems;
	}


	//=======================================================
	// Wunschliste
	//=======================================================
	function showWishlist()
	{
		global $_SESSION;
		$items = '';
		$Preis = '';
		$Vars = '';
		$SummVarsE = '';
		$PreisV = '';
		$PreisVarianten = '';
		$PreisGesamt = '';
		$GewichtGesamt = '';
		$row_ieu = '';

		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_merkliste WHERE Benutzer = '".@$_SESSION['cp_benutzerid']."'");
		$row = $sql->fetchrow();

		// Anzahl der Artikel in der WUnschliste aktualisieren
		if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] == 1)
		{

			if(isset($_POST['del_product']) && is_array($_POST['del_product']))
			{
				foreach($_POST['del_product'] as $id => $Artikel)
				{
					unset($_SESSION['Product_Wishlist'][$id]);
					unset($_SESSION['Product_Wishlist_Vars'][$id]);
				}
				$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_merkliste SET Inhalt = '".serialize($_SESSION['Product_Wishlist'])."', Inhalt_Vars = '".serialize($_SESSION['Product_Wishlist_Vars'])."' WHERE Benutzer = '".@$_SESSION['cp_benutzerid']."'");
				header("Location:" . $_SERVER['HTTP_REFERER']);
				exit;
			}


			if(isset($_POST['amount']) && is_array($_POST['amount']))
			{

				foreach($_POST['amount'] as $id => $Artikel)
				{
					if($_POST['amount'][$id] >= 1) $_SESSION['Product_Wishlist'][$id] =  $_POST['amount'][$id];
				}
				$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_merkliste SET Inhalt = '".serialize($_SESSION['Product_Wishlist'])."' WHERE Benutzer = '".@$_SESSION['cp_benutzerid']."'");
				header("Location:" . $_SERVER['HTTP_REFERER']);
				exit;
			}

		}


		if(is_object($row) && $row->Inhalt != '')
		{
			$arr = @unserialize($row->Inhalt);
			$vars = @unserialize($row->Inhalt_Vars);

			@$_SESSION['Product_Wishlist'] = $arr;
			if($row->Inhalt_Vars != '') @$_SESSION['Product_Wishlist_Vars'] = $vars;

			$items = array();
			$SummVars = '';

			foreach ($arr as $key => $value)
			{
				$item->Id = $key;
				$item->Val = $value;
				$SummVars = '';



				// mögliche Produkt - Varianten auslesen und Preis berechnen
				$Vars = array();
				if(isset($vars) && $vars != '')
				{

					$ExVars = explode(',', @$vars[$item->Id]);
					foreach($ExVars as $ExVar)
					{

						if(!empty($ExVar))
						{
							$sql_vars = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_varianten WHERE Id = '" . $ExVar . "' ORDER BY Position ASC");
							$row_vars = $sql_vars->fetchrow();
							$sql_vars->close();

							@$row_vars->Wert = $this->getDiscountVal(@$row_vars->Wert);

							if($row_vars->Operant == '+')
								$SummVars += @$row_vars->Wert;
							else
								$SummVars -= @$row_vars->Wert;



							$sql_var_cat = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_varianten_kategorien WHERE Id = '" . $row_vars->KatId . "'");
							$row_var_cat = $sql_var_cat->fetchrow();
							$sql_var_cat->close();

							$row_vars->VarName = $row_var_cat->Name;
							$row_vars->Wert = ($this->checkPayVat()==true) ? @$row_vars->Wert : @$row_vars->Wert / $this->getVat($key);
							$row_vars->WertE = $row_vars->Wert;
							array_push($Vars, $row_vars);
						}
					}
				}
				$SummVarsE = $SummVars;
				$SummVars = $SummVars*$value;

				$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_artikel WHERE Id = '$key'");
				$row = $sql->fetchrow();
				if(is_object($row))
				{
					$Einzelpreis = $row->Preis;

					// Preis des Artikels
					$Einzelpreis = $this->getNewPrice($key, $value, 0, $Einzelpreis);

					// Wenn Benutzer registriert ist, muss hier geschaut werden, welches Land der
					// Benutzer bei der Registrierung angegeben hat, damit der richtige Preis angezeigt wird
					// Wenn nach dem Ansenden des Formulars (Checkout) ein anderes Versandland angegeben wird
					// als bei der Registrierung, muss dieses Land verwendet werden um die Versandkosten zu berechnen
					$PayUSt = $this->checkPayVat();
					if($PayUSt != true)
					{
						$row->Preis = $this->getDiscountVal($Einzelpreis) / $this->getVat($row->Id);
					}


					// Anzahl jedes Artikels
					$item->Anzahl = $value;

					// Preis Zusammenrechnen
					$Preis+=$row->Preis;

					// Name des Artikels
					$item->ArtName = $row->ArtName;

					$item->ProdLink = $this->shopRewrite(($this->_product_detail . $row->Id .'&amp;categ=' . $row->KatId . '&amp;navop=' . getParentShopcateg($row->KatId)));
					$item->Hersteller_Name = $this->fetchManufacturer($row->Hersteller);
					$item->DelLink = $this->_delete_item . $row->Id;

					// Einzelpreis unter Berücksichtigung von Kundengruppe und Varianten
					// Summe unter Berücksichtung der Anzahl
					if($value>1)
					{
						$item->EPreis = (($PayUSt != true) ? (($this->getDiscountVal($this->getNewPrice($key, $value, 0, $Einzelpreis))+$SummVarsE) / $this->getVat($key)) : ($this->getDiscountVal($this->getNewPrice($key, $value, 0, $Einzelpreis))+$SummVarsE));
						$item->EPreisSumme = $item->EPreis * $value;
					} else {
						$item->EPreis = (($PayUSt != true) ? (($this->getDiscountVal($Einzelpreis)+$SummVarsE) / $this->getVat($key)) : ($this->getDiscountVal($Einzelpreis)+$SummVarsE));
						$item->EPreisSumme = (($PayUSt != true) ? (($this->getDiscountVal($Einzelpreis*$value)+$SummVarsE) / $this->getVat($key)) : ($this->getDiscountVal($Einzelpreis)+$SummVarsE)*$value);
					}

					$item->Gewicht = $row->Gewicht*$value;
					$item->ArtNr = $row->ArtNr;


					// Endpreis aller Artikel
					$PreisGesamt += $item->EPreisSumme;
					$GewichtGesamt += $item->Gewicht;

					// Preis 2.Währung
					if(defined("WaehrungSymbol2") && defined("Waehrung2") && defined("Waehrung2Multi"))
					{
						if($value>1)
						{
							@$item->PreisW2 = (($PayUSt != true) ? (($this->getDiscountVal($this->getNewPrice($key, $value, 0, ($Einzelpreis * Waehrung2Multi)))+$SummVarsE) / $this->getVat($key) * Waehrung2Multi) : ($this->getDiscountVal($this->getNewPrice($key, $value, 0, ($Einzelpreis))* Waehrung2Multi)+$SummVarsE));
							@$item->PreisW2Summ = @$item->PreisW2 * $value;
						} else {
							@$item->PreisW2 = (($PayUSt != true) ? (($this->getDiscountVal($Einzelpreis)+$SummVarsE) / $this->getVat($key) * Waehrung2Multi) : ($this->getDiscountVal($Einzelpreis)+$SummVarsE) * Waehrung2Multi);
							@$item->PreisW2Summ = @$item->PreisW2;
						}
					}

					if($Vars) $item->Vars = $Vars;
					$item->Bild = $row->Bild;
					$item->Versandfertig = $this->getTimeTillSend($row->VersandZeitId);

					if(!file_exists('modules/shop/uploads/' . $row->Bild)) $item->BildFehler = 1;

					if($PayUSt == true)
					{
						$item->Vat = $this->getVat($key,1);
						$mu = explode('.', $item->Vat);
						$multiplier = (strlen($mu[0])==1) ? '1.0' . $mu[0] . $mu[1] : '1.' . $mu[0] . $mu[1];

						$PreisNettoAll = $item->EPreisSumme / $multiplier;
						$PreisNettoAll = $item->EPreisSumme - $PreisNettoAll;
						$PreisNettoAll = round($PreisNettoAll,2);

						$IncVat = $PreisNettoAll;
						@$_SESSION['VatInc'][] = $item->Vat;
						@$_SESSION[$item->Vat] += ($IncVat);
					}

					array_push($items, $item);
					$item = '';
					$row = '';
				}
			}
		}

		return $items;
	}

	//=======================================================
	// Warenkorb
	//=======================================================
	function showBasketItems()
	{
		global $_SESSION;
		$items = '';
		$Preis = '';
		$Vars = '';
		$SummVarsE = '';
		$PreisV = '';
		$PreisVarianten = '';
		$PreisGesamt = '';
		$GewichtGesamt = '';
		$row_ieu = '';

		if(isset($_SESSION['Product']))
		{
			unset($_SESSION['BasketSumm']);
			unset($_SESSION['BasketOverall']);
			unset($_SESSION['VatInc']);
			unset($_SESSION['ShowNoVatInfo']);
			unset($_SESSION['RabattWert']);
			unset($_SESSION['Rabatt']);
			unset($_SESSION['Zwisumm']);
			unset($_SESSION['BasketSummW2']);

			$this->resetVatZoneSessions();

			$arr = $_SESSION['Product'];
			$items = array();
			$SummVars = '';

			foreach ($arr as $key => $value)
			{
				$item->Id = $key;
				$item->Val = $value;
				$SummVars = '';

				// mögliche Produkt - Varianten auslesen und Preis berechnen
				$Vars = array();
				if(isset($_SESSION['ProductVar'][$item->Id]) && $_SESSION['ProductVar'][$item->Id] != '')
				{
					$ExVars = explode(',', $_SESSION['ProductVar'][$item->Id]);
					foreach($ExVars as $ExVar)
					{

						if(!empty($ExVar))
						{
							$sql_vars = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_varianten WHERE Id = '" . $ExVar . "' ORDER BY Position ASC");
							$row_vars = $sql_vars->fetchrow();
							$sql_vars->close();

							@$row_vars->Wert = $this->getDiscountVal(@$row_vars->Wert);

							if($row_vars->Operant == '+')
								$SummVars += @$row_vars->Wert;
							else
								$SummVars -= @$row_vars->Wert;



							$sql_var_cat = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_varianten_kategorien WHERE Id = '" . $row_vars->KatId . "'");
							$row_var_cat = $sql_var_cat->fetchrow();
							$sql_var_cat->close();

							$row_vars->VarName = $row_var_cat->Name;
							$row_vars->Wert = ($this->checkPayVat()==true) ? @$row_vars->Wert : @$row_vars->Wert / $this->getVat($key);
							$row_vars->WertE = $row_vars->Wert;
							array_push($Vars, $row_vars);
						}
					}
				}
				$SummVarsE = $SummVars;
				$SummVars = $SummVars*$value;

				$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_artikel WHERE Id = '$key'");
				$row = $sql->fetchrow();
				$Einzelpreis = $row->Preis;

				// Preis des Artikels
				$Einzelpreis = $this->getNewPrice($key, $value, 0, $Einzelpreis);

				// Wenn Benutzer registriert ist, muss hier geschaut werden, welches Land der
				// Benutzer bei der Registrierung angegeben hat, damit der richtige Preis angezeigt wird
				// Wenn nach dem Ansenden des Formulars (Checkout) ein anderes Versandland angegeben wird
				// als bei der Registrierung, muss dieses Land verwendet werden um die Versandkosten zu berechnen
				$PayUSt = $this->checkPayVat();
				if($PayUSt != true)
				{
					$row->Preis = $this->getDiscountVal($Einzelpreis) / $this->getVat($row->Id);
				}

				// Anzahl jedes Artikels
				$item->Anzahl = $value;

				// Preis Zusammenrechnen
				$Preis+=$row->Preis;

				// Name des Artikels
				$item->ArtName = $row->ArtName;

				$item->ProdLink = $this->shopRewrite(($this->_product_detail . $row->Id .'&amp;categ=' . $row->KatId . '&amp;navop=' . getParentShopcateg($row->KatId)));
				$item->Hersteller_Name = $this->fetchManufacturer($row->Hersteller);
				$item->DelLink = $this->_delete_item . $row->Id;

				// Einzelpreis unter Berücksichtigung von Kundengruppe und Varianten
				// Summe unter Berücksichtung der Anzahl
				if($value>1)
				{
					$item->EPreis = (($PayUSt != true) ? (($this->getDiscountVal($this->getNewPrice($key, $value, 0, $Einzelpreis))+$SummVarsE) / $this->getVat($key)) : ($this->getDiscountVal($this->getNewPrice($key, $value, 0, $Einzelpreis))+$SummVarsE));
					$item->EPreisSumme = $item->EPreis * $value;
				} else {
					$item->EPreis = (($PayUSt != true) ? (($this->getDiscountVal($Einzelpreis)+$SummVarsE) / $this->getVat($key)) : ($this->getDiscountVal($Einzelpreis)+$SummVarsE));
					$item->EPreisSumme = (($PayUSt != true) ? (($this->getDiscountVal($Einzelpreis*$value)+$SummVarsE) / $this->getVat($key)) : ($this->getDiscountVal($Einzelpreis)+$SummVarsE)*$value);
				}

				$item->Gewicht = $row->Gewicht*$value;
				$item->ArtNr = $row->ArtNr;


				// Endpreis aller Artikel
				$PreisGesamt += $item->EPreisSumme;
				$GewichtGesamt += $item->Gewicht;

				// Preis 2.Währung
				if(defined("WaehrungSymbol2") && defined("Waehrung2") && defined("Waehrung2Multi"))
				{
					if($value>1)
					{
						@$item->PreisW2 = (($PayUSt != true) ? (($this->getDiscountVal($this->getNewPrice($key, $value, 0, ($Einzelpreis * Waehrung2Multi)))+$SummVarsE) / $this->getVat($key) * Waehrung2Multi) : ($this->getDiscountVal($this->getNewPrice($key, $value, 0, ($Einzelpreis))* Waehrung2Multi)+$SummVarsE));
						@$item->PreisW2Summ = @$item->PreisW2 * $value;
					} else {
						@$item->PreisW2 = (($PayUSt != true) ? (($this->getDiscountVal($Einzelpreis)+$SummVarsE) / $this->getVat($key) * Waehrung2Multi) : ($this->getDiscountVal($Einzelpreis)+$SummVarsE) * Waehrung2Multi);
						@$item->PreisW2Summ = @$item->PreisW2;
					}
				}

				if($Vars) $item->Vars = $Vars;
				$item->Bild = $row->Bild;
				$item->Bild_Typ = $row->Bild_Typ;
				$item->Versandfertig = $this->getTimeTillSend($row->VersandZeitId);

				if(!file_exists('modules/shop/uploads/' . $row->Bild)) $item->BildFehler = 1;

				if($PayUSt == true)
				{
					$item->Vat = $this->getVat($key,1);
					$mu = explode('.', $item->Vat);
					$multiplier = (strlen($mu[0])==1) ? '1.0' . $mu[0] . $mu[1] : '1.' . $mu[0] . $mu[1];

					$PreisNettoAll = $item->EPreisSumme / $multiplier;
					$PreisNettoAll = $item->EPreisSumme - $PreisNettoAll;
					$PreisNettoAll = round($PreisNettoAll,2);

					$IncVat = $PreisNettoAll;
					@$_SESSION['VatInc'][] = $item->Vat;
					@$_SESSION[$item->Vat] += ($IncVat);
				}

				array_push($items, $item);
				$item = '';
				$row = '';
			}
			// Eventuellen Kundengruppen - Rabatt berücksichtigen!

			$PreisVorher = '';

			$_SESSION['Zwisumm'] = ($PreisVorher != '')  ? $PreisGesamt : $PreisGesamt;
			$_SESSION['BasketSumm'] = $PreisGesamt;
			$_SESSION['BasketSummW2'] = ($PreisGesamt * @Waehrung2Multi);
			$_SESSION['BasketOverall'] = $PreisGesamt;
			$_SESSION['GewichtSumm'] = str_replace(',','.',$GewichtGesamt);;

			// Gutscheincode löschen
			if(isset($_POST['couponcode_del']) && $_POST['couponcode_del'] == '1' && $this->getShopSetting('GutscheinCodes') == 1)
			{
				unset($_SESSION['CouponCode']);
				unset($_SESSION['CouponCodeId']);
			}

			// Gutscheincode dem Warenwert abziehen
			if(isset($_SESSION['CouponCode']) && $_SESSION['CouponCode'] != '' && !isset($_POST['couponcode']))
			{
				$_SESSION['BasketSumm'] = $_SESSION['BasketSumm'] - ($_SESSION['Zwisumm'] / 100 * $_SESSION['CouponCode']);
			}
		}
		return $items;
	}

	function checkPayVat()
	{
		if(!isset($_SESSION['cp_ucountry'])) $_SESSION['cp_ucountry'] = '';
		if(isset($_POST['Land']) && $_POST['Land'] != $_SESSION['cp_ucountry']) $_SESSION['cp_ucountry'] = $_POST['Land'];
		if(isset($_SESSION['cp_ucountry']) && $_SESSION['cp_ucountry'] != '')
		{
			$sql_ieu = $GLOBALS['db']->Query("SELECT IstEU FROM " . PREFIX . "_countries WHERE Aktiv = 1 AND LandCode = '".$_SESSION['cp_ucountry']."'");
			$row_ieu = $sql_ieu->fetchrow();
		}

		// Muss der Käufer USt. zahlen?
		// ShipperId
		$PayUSt = true;
		if(is_object(@$row_ieu) && $row_ieu->IstEU==2)
		{
			// Benutzer ist angemeldet, hat Umsatzsteuerbefreiung
			if(isset($_SESSION['cp_benutzerid']) &&
			@$_SESSION['cp_benutzerid'] != '' &&
			@$_SESSION['GewichtSumm'] >= '0.001' &&
			($this->getUserInfo(@$_SESSION['cp_benutzerid'],'UStPflichtig')!=1) || $this->getUserInfo($_SESSION['cp_benutzerid'],'UStPflichtig')==1)
			{
				$PayUSt = false;
			}

			// Benutzer ist angemeldet, hat keine Umsatzsteuerbefreiung
			elseif(isset($_SESSION['cp_benutzerid']) &&
			@$_SESSION['cp_benutzerid'] != '' &&
			@$_SESSION['GewichtSumm'] < '0.001' &&
			($this->getUserInfo($_SESSION['cp_benutzerid'],'UStPflichtig')==1))
			{
				$PayUSt = true;
				$_SESSION['ShowNoVatInfo'] = 1;
			}

			// Downloadbare Ware?
			// Benutzer ist nicht angemeldet, Versandgewicht ist gegeben!
			elseif(!isset($_SESSION['cp_benutzerid']) &&
			@$_SESSION['cp_benutzerid'] == '' &&
			@$_SESSION['GewichtSumm'] >= '0.001')
			{
				$PayUSt = false;
			}

			// Downloadbare Ware?
			// Benutzer ist nicht angemeldet, Versandgewicht ist nicht gegeben!
			elseif(!isset($_SESSION['cp_benutzerid']) &&
			@$_SESSION['cp_benutzerid'] == '' &&
			@$_SESSION['GewichtSumm'] < '0.001')
			{
				$PayUSt = true;
				$_SESSION['ShowNoVatInfo'] = 1;
			}
			else
			{
				if($this->getUserInfo($_SESSION['cp_benutzerid'],'UStPflichtig')!=1)
				{
					$PayUSt = false;
				} else {
					$PayUSt = true;
				}
			}
		} else {
			$PayUSt = true;
		}
	return $PayUSt;
	}

	//=======================================================
	// Shop - Startseite
	//=======================================================
	function displayShopStart($theme='')
	{
		global $_SESSION;

		$shopitems = array();
		$categs = array();
		$fetchcat = (isset($_GET['categ']) && is_numeric($_GET['categ'])) ? $_GET['categ'] : '0';

		$categories_tree = $this->getCategories($fetchcat, '', $categs,'0');

		$this->globalProductInfo();

		$GLOBALS['tmpl']->assign('shopitems', $categories_tree);
		$GLOBALS['tmpl']->assign('ShopArticles', $this->lastArticles());
		$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . $this->_shop_start_tpl);
		define("MODULE_CONTENT", $tpl_out);
		define("MODULE_SITE",  (defined('TITLE_EXTRA') ?  TITLE_EXTRA : $GLOBALS['mod']['config_vars']['PageName'] ));
	}

	//=======================================================
	// Produkt - Details
	//=======================================================
	// Rezensionen
	function fetchArticleComments()
	{
		$comments = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_artikel_kommentare WHERE Publik = '1' AND ArtId = '" . (int)$_REQUEST['product_id'] . "' ORDER BY Id DESC");
		while($row = $sql->fetchrow())
		{
			$sql_u = $GLOBALS['db']->Query("SELECT Vorname,Nachname FROM " . PREFIX . "_users WHERE Id = '$row->Benutzer'");
			$row_u = $sql_u->fetchrow();

			$row->Titel = htmlspecialchars($row->Titel);
			$row->Kommentar = nl2br(htmlspecialchars($row->Kommentar));
			$row->Autor = substr($row_u->Vorname,0,1) . '. ' . $row_u->Nachname;
			array_push($comments, $row);
		}
		return $comments;
	}

	// Details anzeigen
	function showDetails($product_id)
	{
		if(Kommentare==1 && (isset($_REQUEST['sendcomment']) && $_REQUEST['sendcomment']==1 ) && (isset($_SESSION['cp_benutzerid'])) )
		{
			$sql = $GLOBALS['db']->Query("SELECT Benutzer FROM " . PREFIX . "_modul_shop_artikel_kommentare
			WHERE
				Benutzer = '" . $_SESSION['cp_benutzerid'] . "' AND
				ArtId = '" . $_REQUEST['product_id'] . "'");

			$num = $sql->numrows();

			if($num<1)
			{
				$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_shop_artikel_kommentare
				(
					Id,
					ArtId,
					Benutzer,
					Datum,
					Titel,
					Kommentar,
					Wertung,
					Publik
				) VALUES (
					'',
					'" . $_REQUEST['product_id'] . "',
					'" . $_SESSION['cp_benutzerid'] . "',
					'" . time() . "',
					'" . $_REQUEST['ATitel'] . "',
					'" . $_REQUEST['AKommentar'] . "',
					'" . (((int)$_REQUEST['AWertung'] > 5 || (int)$_REQUEST['AWertung'] < 1) ? '3' : (int)$_REQUEST['AWertung'] ) . "',
					'0'
				)");

				$globals = new Globals;
				$SystemMail = $GLOBALS['globals']->cp_settings("Mail_Absender");
				$SystemMailName = $GLOBALS['globals']->cp_settings("Mail_Name");

				$URLAdmin = explode("?", $_SERVER['HTTP_REFERER']);
				$URLAdmin = str_replace("index.php", "", $URLAdmin[0]) . "admin/index.php?do=modules&action=modedit&mod=shop&moduleaction=edit_comments&pop=1&Id=$_REQUEST[product_id]";

				$Text = $GLOBALS['mod']['config_vars']['CommentATextMail'];
				$Text = str_replace("%N%", "\n", $Text);
				$Text = str_replace("%URL%", $_SERVER['HTTP_REFERER'], $Text);
				$Text = str_replace("%URLADMIN%", $URLAdmin, $Text);

				$GLOBALS['globals']->cp_mail($SystemMail, $Text . stripslashes($_REQUEST['AKommentar']), $GLOBALS['mod']['config_vars']['CommentASubject'], $SystemMail, $SystemMailName, "text", "");
				}
			header("Location:index.php?module=shop&action=product_detail&product_id=$_REQUEST[product_id]&categ=$_REQUEST[categ]&navop=$_REQUEST[navop]");
			exit;
		}


		$product_id = (int)$product_id;
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_artikel WHERE Aktiv = '1' AND Id = '$product_id' AND Erschienen <= '" . time() . "'");
		$row = $sql->fetchrow();

		$the_nav = $this->getNavigationPath((int)$_REQUEST['categ'], '', '1', (int)$_REQUEST['navop']);
		$GLOBALS['tmpl']->assign('StPrices', $this->getStPrices($product_id));

		$row = $this->globalProductInfo($row);

		$MultiImages = array();
		$sql_bilder = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_artikel_bilder WHERE ArtId = '$row->Id'");
		while($row_bilder = $sql_bilder->fetchrow())
		{
			$bild_typ = strtolower(substr($row_bilder->Bild,-4));
			switch($bild_typ)
			{
				case 'jpeg' :
				case '.jpe' :
				case 'jpg' :
				default :
					$row_bilder->endung = 'jpg';
				break;

				case '.png' :
					$row_bilder->endung = 'png';
				break;

				case '.gif' :
					$row_bilder->endung = 'gif';
				break;

			}
			if(file_exists(BASE_DIR . '/modules/shop/uploads/' . $row_bilder->Bild))  array_push($MultiImages, $row_bilder);
		}

		$row->MultiImages = $MultiImages;

		$GLOBALS['tmpl']->assign('row', $row);

		if(is_object($row))
		{
			$GLOBALS['tmpl']->assign('equalProducts', $this->equalProducts($row->Schlagwoerter));
			$GLOBALS['tmpl']->assign('Variants', $this->getVariants($row->KatId, $row->Id));
			if(isset($_SESSION['Product']) && isset($_SESSION['Product'][$row->Id])) $GLOBALS['tmpl']->assign('InBasket', 1);
		}
		$GLOBALS['tmpl']->assign('topnav', $the_nav);

		// Sind Rezensionen erlaubt ?
		if(Kommentare==1)
		{
			$sql_w = $GLOBALS['db']->Query("SELECT SUM(Wertung) as Wertung FROM " . PREFIX . "_modul_shop_artikel_kommentare WHERE Publik = '1' AND ArtId = '" . (int)$_REQUEST['product_id'] . "'");
			$row_w = $sql_w->fetchrow();

			$sql_a = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_shop_artikel_kommentare WHERE Publik = '1' AND ArtId = '" . (int)$_REQUEST['product_id'] . "'");
			$num_a = $sql_a->numrows();

			$row_w->Anz = $num_a;
			@$row_w->Proz = round($row_w->Wertung / $num_a);

			$GLOBALS['tmpl']->assign('rez', $row_w);
			$GLOBALS['tmpl']->assign('AllowComments', 1);
			$GLOBALS['tmpl']->assign('Comments', $this->fetchArticleComments());
		}
		if(Kommentare==1 && isset($_SESSION['cp_benutzerid']))
		{
			$GLOBALS['tmpl']->assign('CanComment', 1);
			$GLOBALS['tmpl']->assign('Comments', $this->fetchArticleComments());
		}

		$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . $this->_shop_product_detailpage);
		$tpl_out = $this->shopRewrite($tpl_out);

		define("MODULE_CONTENT", $tpl_out);
		define("MODULE_SITE", $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . stripslashes(@$row->ArtName));
	}

	//=======================================================
	// Verwandte Produkte
	//=======================================================
	function equalProducts($Matchwords)
	{
		$shopitems = array();
		$prod_id = array();
		if($Matchwords)
		{
			$Matchword = @explode(',', $Matchwords);
			foreach ($Matchword as $Match)
			{
				$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_artikel WHERE Aktiv = '1' AND Id != '".(int)$_REQUEST['product_id']."' AND  Schlagwoerter LIKE '%$Match%' ORDER BY rand() LIMIT 5");
				while($row = $sql->fetchrow())
				{
					$row = $this->globalProductInfo($row);
					if(!in_array($row->Id, $prod_id)) array_push($shopitems, $row);
					$prod_id[] = $row->Id;
				}
			}
		}
		return 	$shopitems;
	}

	//=======================================================
	// Produkt in den Warenkorb legen
	//=======================================================
	function addtoBasket($product_id, $can_update = '0', $to_wishlist = '0')
	{
		$to_wishlist = (isset($_REQUEST["wishlist_$_REQUEST[product_id]"]) && $_REQUEST["wishlist_$_REQUEST[product_id]"] == 1 ) ? '1' : '0';
		if($to_wishlist==1) $can_update=1;

		$product_id = (int)$product_id;
		$amount = (int)$_REQUEST['amount'];
		if (($amount > '0') && ($can_update == '1' || !isset($_SESSION['Product'][$_REQUEST['product_id']]) || !in_array($_REQUEST['id'], $_SESSION['Product'])))
		{
			if(isset($_REQUEST['product_id']) && is_numeric($_REQUEST['product_id']))
			{

				if($to_wishlist!=1)
				{
					$_SESSION['Product'][$_REQUEST['product_id']] = $amount;

					// Mögliche Varianten in Session
					// Hier wird ein Produkt aus dem Wunschzettel abgelegt
					if(isset($_REQUEST['vars']) && $_REQUEST['vars'] != '')
					{
						if(isset($_REQUEST['vars']) && $_REQUEST['vars'] != '')
						{
							$Vars_To_Session =  $_REQUEST['vars'];
							$_SESSION['ProductVar'][$_REQUEST['product_id']] = $Vars_To_Session;

						} else {
							$Vars_To_Session = chop(base64_decode($_REQUEST['vars']));
						}


						$_SESSION['ProductVar'][$_REQUEST['product_id']] = $Vars_To_Session;
					}

					// Hier wird ein normal (nicht aus dem Wunschzettel) abgelegt
					if(isset($_POST['product_vars']) && is_array($_POST['product_vars']))
					{
						$Vars_To_Session = implode(',', $_REQUEST['product_vars']);
						$_SESSION['ProductVar'][$_REQUEST['product_id']] = $Vars_To_Session;
					}
				}

				// In Wunschliste?
				if($to_wishlist==1)
				{
					$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_merkliste WHERE Benutzer = '".$_SESSION['cp_benutzerid']."'");
					$row = $sql->fetchrow();
					if(is_object($row) && $row->Id != '')
					{
						// Vorhandene Einträge auslesen und aktualisieren
						$_SESSION['Product_Wishlist'] = unserialize($row->Inhalt);
						$_SESSION['Product_Wishlist_Vars'] = unserialize($row->Inhalt_Vars);
						$_SESSION['Product_Wishlist'][$_REQUEST['product_id']] = $amount;
						$Vars_To_Session = implode(',', $_REQUEST['product_vars']);
						$_SESSION['Product_Wishlist_Vars'][$_REQUEST['product_id']] = $Vars_To_Session;
						$_SESSION['Product_Wishlist'][$_REQUEST['product_id']] = $amount;

						$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_merkliste
						SET
							Inhalt = '".serialize($_SESSION['Product_Wishlist'])."',
							Inhalt_Vars = '".serialize($_SESSION['Product_Wishlist_Vars'])."'
						WHERE
							Benutzer = '".$_SESSION['cp_benutzerid']."'");

					} else {
						// Neu
						// mögliche Varianten
						$Db_Vars = '';
						if(isset($_POST['product_vars']) && is_array($_POST['product_vars']))
						{
							$Vars_To_Session = implode(',', $_REQUEST['product_vars']);
							$_SESSION['Product_Wishlist_Vars'][$_REQUEST['product_id']] = $Vars_To_Session;
							$Db_Vars = serialize($_SESSION['Product_Wishlist_Vars']);
						}

						$_SESSION['Product_Wishlist'][$_REQUEST['product_id']] = $amount;
						$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_shop_merkliste
						(
							Id,
							Benutzer,
							Ip,
							Inhalt,
							Inhalt_Vars
						) VALUES (
							'',
							'".$_SESSION['cp_benutzerid']."',
							'".$_SERVER['REMOTE_ADDR']."',
							'".serialize($_SESSION['Product_Wishlist'])."',
							'$Db_Vars'
						)");
					}
				}
			}
		}
		header("Location:" . $_SERVER['HTTP_REFERER']);
		exit;
	}

	//=======================================================
	// Produkt aus dem Warenkorb entfernen
	//=======================================================
	function delItem($product_id)
	{
		$product_id = (int)$product_id;
		unset($_SESSION['Product'][$product_id]);
		unset($_SESSION['ProductVar'][$product_id]);
		unset($_SESSION['BasketSumm']);
		unset($_SESSION['ShipperId']);
		unset($_SESSION['GewichtSumm']);
		unset($_SESSION['PaymentId']);
		header("Location:" . $_SERVER['HTTP_REFERER']);
		exit;
	}

	//=======================================================
	// Globale Produkt-Infos
	// Wurd für mehrere Ausgaben benötigt
	//=======================================================
	function globalProductInfo($row = '')
	{
		if(is_object($row))
		{
			$PayUSt = $this->checkPayVat();

			$mu = explode('.', $this->getUstVal($row->UstZone));
			$multiplier = (strlen($mu[0])==1) ? '1.0' . $mu[0] . $mu[1] : '1.' . $mu[0] . $mu[1];

			$row->Preis = ($PayUSt==true) ? $this->getDiscountVal($row->Preis) : ($this->getDiscountVal($row->Preis) / $this->getVat($row->Id));
			$row->PreisListe_Raw = $row->PreisListe;
			$row->PreisDiff = ($row->PreisListe_Raw > $row->Preis) ? ($row->PreisListe_Raw-$row->Preis) : '';
			$row->Preis_Raw = $row->Preis;
			$row->Preis_Netto = $row->Preis / $multiplier;
			$row->Preis_Netto_Out = $row->Preis / $multiplier;
			$row->Preis_USt = $row->Preis - $row->Preis_Netto;
			$row->Preis = $row->Preis;
			$row->NettoAnzeigen = ($PayUSt==true) ? 1 : 0;

			$row->AddToLink = $this->shopRewrite($this->_add_item . $row->Id);
			$row->AddToWishlist = $this->shopRewrite($this->_add_item_wishlist . $row->Id);
			$row->Detaillink = $this->shopRewrite($this->_product_detail . $row->Id . '&amp;categ=' . $row->KatId . '&amp;navop=' . getParentShopcateg($row->KatId));
			$row->StPrices = $this->getStPrices($row->Id);
			if($row->Hersteller) $row->Hersteller_Name = $this->fetchManufacturer($row->Hersteller);
			if($row->Hersteller) $row->Hersteller_Link = $this->shopRewrite($this->_link_manufaturer . $row->Hersteller);
			if($row->Einheit > '0' && $this->getShopSetting('ZeigeEinheit')==1) $row->Einheit_Preis = $row->Preis / $row->Einheit;
			if($row->Einheit > '0') $row->Einheit_Art = $this->getUnit($row->EinheitId);
			if($row->Einheit > '0') $row->Einheit_Art_S = $this->getUnit($row->EinheitId,1);
			if($row->VersandZeitId > '0') $row->Versandfertig = $this->getTimeTillSend($row->VersandZeitId);
			if(isset($_SESSION['Product']) && isset($_SESSION['Product'][$row->Id])) $row->InBasket = 1;

			if($row->Bild == '' || !file_exists('modules/shop/uploads/' . $row->Bild))
			{
				$row->BildFehler = 1;
			} else {
				// Thumbnail existiert, also einfacher Pfad (schneller)
				if(file_exists('modules/shop/thumbnails/shopthumb__' . WidthThumbs . '__' . $row->Bild))
					$row->ImgSrc = 'modules/shop/thumbnails/shopthumb__' . WidthThumbs . '__' . $row->Bild;
				// Wenn Thumbnail nicht existiert, erzeugen
				else
					$row->ImgSrc = 'FALSE';
			}

			if($this->getShopSetting('ZeigeNetto')==1) $row->ZeigeNetto = 1;

			if(Kommentare==1)
			{
				$sql_w = $GLOBALS['db']->Query("SELECT SUM(Wertung) as Wertung FROM " . PREFIX . "_modul_shop_artikel_kommentare WHERE Publik = '1' AND ArtId = '" . $row->Id . "'");
				$row_w = $sql_w->fetchrow();

				$sql_a = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_shop_artikel_kommentare WHERE Publik = '1' AND ArtId = '" . $row->Id . "'");
				$num_a = $sql_a->numrows();

				$row_w->Anz = $num_a;
				@$row->Prozwertung = round($row_w->Wertung / $num_a);
			}
		}

		$sql_w2 = $GLOBALS['db']->Query("SELECT WaehrungSymbol2,Waehrung2,Waehrung2Multi FROM " . PREFIX . "_modul_shop LIMIT 1");
		$row_w2 = $sql_w2->fetchrow();
		$sql_w2->close();

		if($row_w2->Waehrung2 != '' && $row_w2->WaehrungSymbol2 != '' && $row_w2->Waehrung2Multi != '')
		{
			@$row->PreisW2 = ($row->Preis_Raw * $row_w2->Waehrung2Multi);
		}

		$GLOBALS['tmpl']->register_function('get_parent_shopcateg', 'getParentShopcateg');
		$GLOBALS['tmpl']->assign('BasketItems', $this->showBasketItems());
		$GLOBALS['tmpl']->assign('Currency', $this->getSetting('WaehrungSymbol'));
		$GLOBALS['tmpl']->assign('KaufLagerNull', $this->getSetting('KaufLagerNull'));
	  //	$GLOBALS['tmpl']->assign('ProductCategs', $this->fetchShopNavi(1));
		$GLOBALS['tmpl']->assign('Manufacturer', $this->displayManufacturer());
		$GLOBALS['tmpl']->assign('ShopStartLink', $this->shopRewrite('index.php?module=shop'));
		$GLOBALS['tmpl']->assign('ShowBasketLink', $this->shopRewrite('index.php?module=shop&amp;action=showbasket'));
		$GLOBALS['tmpl']->assign('ShowPaymentLink', $this->shopRewrite('index.php?module=shop&amp;action=checkout'));
		$GLOBALS['tmpl']->assign('CheckoutLink', $this->shopRewrite('index.php?module=shop&amp;action=checkout'));
		$GLOBALS['tmpl']->register_function('get_parent_shopcateg', 'getParentShopcateg');
		return $row;
	}

	// ================================================================
	// Erzeugt einen Pfad zur aktuellen Position
	// ================================================================
	function getNavigationPath($id, $result = null, $extra = 0, $nav_op="0")
	{
		// daten des aktuellen bereichs
		$q_item = "SELECT Id,KatName,Elter FROM " . PREFIX . "_modul_shop_kategorie WHERE Id = $id";
		$r_item = $GLOBALS['db']->Query($q_item);
		$item = $r_item->fetchrow();

		if(is_object($item))
		{
			$esn  = $item->Elter;
			$result_link = $this->shopRewrite("index.php?module=shop&amp;categ={$item->Id}&amp;parent=$item->Elter&amp;navop=".getParentShopcateg($item->Id));
			if ($item->Elter == 0) return "<a class='mod_shop_navi' href='index.php?module=shop'>".$GLOBALS['mod']['config_vars']['PageName']."</a>".$GLOBALS['mod']['config_vars']['PageSep']."<a class='mod_shop_navi' href='$result_link'>" . $item->KatName . "</a>" . ($result ? $GLOBALS['mod']['config_vars']['PageSep'] : '') . $result;

			// Daten des darüberliegenden Bereiches
			$q_parent = "SELECT Id,KatName,Elter FROM " . PREFIX . "_modul_shop_kategorie WHERE Id = " . $item->Elter;
			$r_parent = $GLOBALS['db']->Query($q_parent);
			$parent = $r_parent->fetchrow();

			$result_link = $this->shopRewrite("index.php?module=shop&amp;categ={$item->Id}&amp;parent=$item->Elter&amp;navop=".getParentShopcateg($item->Id));
			$result = "<a class='mod_shop_navi' href='$result_link'>" . $item->KatName . "</a>"  . ($result ? $GLOBALS['mod']['config_vars']['PageSep'] : '') . $result ;
			return $this->getNavigationPath($item->Elter, $result, $extra, $nav_op);
		}
	}

	//=======================================================
	// Benutzerinformationen abfragen
	//=======================================================
	function getUserInfo($user='',$field='')
	{
		if($user != '')
		{
			$sql = $GLOBALS['db']->Query("SELECT $field FROM " . PREFIX . "_users WHERE Status = '1' AND Id = '$user'");
			$row = $sql->fetchrow();
			$sql->close();
			return $row->$field;
		}
	}

	//=======================================================
	// Umsatz - Steuer (Prozentsatz auslesen)
	//=======================================================
	function getUstVal($UstId)
	{
		$sql = $GLOBALS['db']->Query("SELECT Wert FROM " . PREFIX . "_modul_shop_ust WHERE Id = '$UstId'");
		$row = $sql->fetchrow();
		$sql->close();
		return $row->Wert;
	}

	//=======================================================
	// Funktion zur Preisberechnung und Ausgabe
	//=======================================================
	function getVat($productId, $showPercent = '')
	{
		$sql = $GLOBALS['db']->Query("SELECT Preis,UstZone FROM " . PREFIX . "_modul_shop_artikel WHERE Id = '$productId'");
		$row = $sql->fetchrow();
		$sql->close();

		$sql2 = $GLOBALS['db']->Query("SELECT Wert FROM " . PREFIX . "_modul_shop_ust WHERE Id = '$row->UstZone'");
		$row2 = $sql2->fetchrow();
		$sql2->close();

		$mu = explode('.', $row2->Wert);
		$multiplier = (strlen($mu[0])==1) ? '1.0' . $mu[0] . $mu[1] : '1.' . $mu[0] . $mu[1];

		$vat = ($showPercent==1) ? $row2->Wert : $multiplier;
		return $vat;//echo "$row->Preis / 100 * $row2->Wert --> $vat € (Multi:$multiplier)<br>";
	}

	//=======================================================
	// Hersteller anzeigen
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

	//=======================================================
	// Einheiten
	//=======================================================
	function getUnit($Unit,$NameEinzahl='')
	{
		$sql = $GLOBALS['db']->Query("SELECT Name,NameEinzahl FROM " . PREFIX . "_modul_shop_einheiten WHERE Id = '$Unit' LIMIT 1");
		$row = $sql->fetchrow();
		$sql->close();
		if(@$row->NameEinzahl != '' && $NameEinzahl == 1) return @$row->NameEinzahl;
		return @$row->Name;
	}

	//=======================================================
	// Varianten
	//=======================================================
	function getVariants($KatId,$ArtId)
	{
		$Variants = array();
		$Printout = false;
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_varianten_kategorien WHERE Aktiv = 1 AND KatId = '$KatId'");
		while($row = $sql->fetchrow())
		{
			$row->Beschreibung = str_replace("\"","'",$row->Beschreibung);
			$Variants_Items = array();
			$sql_v = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_varianten WHERE KatId = '$row->Id' AND ArtId = '$ArtId' ORDER BY Position ASC");
			while($row_v = $sql_v->fetchrow())
			{
				$Printout = true;
				$row_v->Wert = ($this->checkPayVat()==true) ? $this->getDiscountVal($row_v->Wert) : $this->getDiscountVal($row_v->Wert) / $this->getVat($ArtId);
				array_push($Variants_Items, $row_v);
			}

			$row->VarItems = $Variants_Items;
			$row->VarName = $row->Name;
			array_push($Variants, $row);
		}
		if($Printout == true) return $Variants;
	}

	function getStPrices($ArtId)
	{
		$StPrices = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_staffelpreise WHERE ArtId = '$ArtId' ORDER BY StkVon ASC");
		while($row = $sql->fetchrow())
		{
			$row->Preis = ($this->checkPayVat()==true) ? $this->getDiscountVal($row->Preis) : $this->getDiscountVal($row->Preis) / $this->getVat($ArtId);
			array_push($StPrices, $row);
		}
		$sql->close();
		return $StPrices;
	}

	function getNewPrice($ArtId, $Amount = '', $Max = '', $Price = '')
	{
		if($Max == 1)
		{
			$sql = $GLOBALS['db']->Query("SELECT Preis FROM " . PREFIX . "_modul_shop_staffelpreise WHERE ArtId = '$ArtId' ORDER BY StkBis DESC LIMIT 1");
			$row = $sql->fetchrow();
			$sql->close();
			if(is_object($row)) return  $row->Preis;
			return $Price;

		} else {
			$sql = $GLOBALS['db']->Query("SELECT Preis FROM " . PREFIX . "_modul_shop_staffelpreise WHERE ArtId = '$ArtId' AND StkVon <= $Amount AND StkBis >= $Amount ");
			//echo "SELECT Preis FROM " . PREFIX . "_modul_shop_staffelpreise WHERE ArtId = '$ArtId' AND StkVon <= $Amount AND StkBis >= $Amount ";
			$row = $sql->fetchrow();
			$sql->close();
			if(!is_object($row)) return $Price;
			//echo $row->Preis."<br>";
			return $row->Preis;
		}
	}

	function getTimeTillSend($Id, $Zeile = 'Name')
	{
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_versandzeit WHERE Id = '$Id' LIMIT 1");
		$row = $sql->fetchrow();
		$sql->close();
		return @$row->$Zeile;
	}

	//=======================================================
	// Warenkorb
	//=======================================================
	function showBasket()
	{
		if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] == 1)
		{

			if(isset($_POST['del_product']) && is_array($_POST['del_product']))
			{
				foreach($_POST['del_product'] as $id => $Artikel)
				{
					unset($_SESSION['Product'][$id]);
				}
				header("Location:" . $_SERVER['HTTP_REFERER']);
				exit;
			}


			if(isset($_POST['amount']) && is_array($_POST['amount']))
			{

				foreach($_POST['amount'] as $id => $Artikel)
				{
					if($_POST['amount'][$id] >= 1) $_SESSION['Product'][$id] =  $_POST['amount'][$id];
				}
				header("Location:" . $_SERVER['HTTP_REFERER']);
				exit;
			}

		}

		$this->globalProductInfo();

		$GLOBALS['tmpl']->assign('VatZones', $this->showVatZones());
		$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'shop_basket.tpl');
		define("MODULE_CONTENT", $tpl_out);
		define("MODULE_SITE", $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['ShopBasket']);

	}


	//==============================================================================================================
	//==============================================================================================================
	// Ab hier folgen alle Funktionen zur Zahlung
	//==============================================================================================================
	//==============================================================================================================
	//
	function customerDiscount()
	{
		$sql = $GLOBALS['db']->Query("SELECT Wert FROM " . PREFIX . "_modul_shop_kundenrabatte WHERE GruppenId = '".UGROUP."' LIMIT 1");
		$row = $sql->fetchrow();
		$sql->close();
		if(is_object($row))
			return $row->Wert;
	}

	function getDiscountVal($val)
	{
		if(defined("UGROUP"))
		{
			$sql = $GLOBALS['db']->Query("SELECT Wert FROM " . PREFIX . "_modul_shop_kundenrabatte WHERE GruppenId = '".UGROUP."' LIMIT 1");
			$row = $sql->fetchrow();
			$sql->close();
			if(is_object($row))
			{
				$prozent_wert = $row->Wert;
				$neuer_wert = $val - ($val / 100 * $prozent_wert);
				$val = $neuer_wert;
			}
		}
		return $val;
	}

	function displayCountries()
	{
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_versandzeit WHERE Id = '$Id' LIMIT 1");
	}

	//=======================================================
	// Login
	//=======================================================

	function loginProcess()
	{
		global $_SESSION;
		if (!empty($_POST['shop_cp_loginemail']) && !empty($_POST['shop_cp_loginkennwort']))
		{
				sleep(1);
				$spassword = addslashes($_POST['shop_cp_loginkennwort']);
				$slogin = addslashes($_POST['shop_cp_loginemail']);

				$qLogin   = "SELECT * FROM " . PREFIX . "_users WHERE Email = '$slogin' OR `UserName` = '$slogin' AND Kennwort = '" .md5(md5($spassword)). "'";
				$SqlLogin = $GLOBALS['db']->Query($qLogin);
				$RowLogin = $SqlLogin->fetchrow();

				switch(@$RowLogin->Status)
				{
					case 1:
						// Login erfolgreich
						$_SESSION["cp_benutzerid"] = $RowLogin->Id;
						$_SESSION["cp_kennwort"] = $RowLogin->Kennwort;
						# header("Location:index.php");
						header("Location:index.php?module=shop&action=checkout");
						exit;
					break;

					case '':
						// Login fehlgeschlagen
						unset($_SESSION["cp_benutzerid"]);
						unset($_SESSION["cp_kennwort"]);
						$GLOBALS['tmpl']->assign("login", "false");
					break;
				}
		} else {
			$GLOBALS['tmpl']->assign("login", "false");
		}
	}


	//=======================================================
	// Versandmethoden
	//=======================================================
	function fetchShipper($id)
	{
		$sql = $GLOBALS['db']->Query("SELECT Name FROM " . PREFIX . "_modul_shop_versandarten WHERE Id='$id' AND Aktiv = 1");
		$row = $sql->fetchrow();
		return @$row->Name;
	}

	function shipperSumm($land='',$Id='', $landcode='')
	{
		$sql = $GLOBALS['db']->Query("SELECT Betrag FROM " . PREFIX . "_modul_shop_versandkosten  WHERE Land = '$land' AND VersandId = '$Id' AND  (".$_SESSION['GewichtSumm']." BETWEEN KVon AND KBis) LIMIT 1");
		$row = $sql->fetchrow();
		return @$row->Betrag;
	}

	function showShipper($land = '', $su = '')
	{
		$shipper = array();
		$si_count = 0;
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_versandarten WHERE Aktiv = 1");
		while($row = $sql->fetchrow())
		{
			$row->L = explode(',', $row->LaenderVersand);
			if($row->NurBeiGewichtNull==1 && $_SESSION['GewichtSumm'] > '0.00')
			{
				// Versabdart anzeigen, die nur für Downloads gedacht sind!
			} else {
				if(in_array($land,$row->L))
				{
					$dbex = ($row->KeineKosten==1) ? "" : "AND  (".$_SESSION['GewichtSumm']." BETWEEN KVon AND KBis)";

					if ($row->KeineKosten==1)
					{
						$row->cost = '0,00';
					} else if ($row->Pauschalkosten>0)
					{
						$row->cost = $row->Pauschalkosten;
						$row->is_pauschal = 1;
					} else {
						$sql_cost = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_versandkosten  WHERE VersandId = '$row->Id' $dbex AND Land='$land' LIMIT 1");
						$row_cost = $sql_cost->fetchrow();
						$row->cost = (is_object($row_cost)) ? $row_cost->Betrag : '';
					}

					@$row->Groups = @explode(',', $row->ErlaubteGruppen);
					if($row->cost && in_array($_SESSION['cp_ugroup'], $row->Groups)) array_push($shipper, $row);
					if($row->cost) $si_count++;
				}
			}
		}
		$sql->close();
		$GLOBALS['tmpl']->assign('si_count', $si_count);
		return $shipper;
	}


	//=======================================================
	// Zahlungsmethoden
	//=======================================================
	function showPaymentMethod($id)
	{
		$sql = $GLOBALS['db']->Query("SELECT Name FROM " . PREFIX . "_modul_shop_zahlungsmethoden WHERE Aktiv = 1 AND Id='$id'");
		$row = $sql->fetchrow();
		return $row->Name;
	}

	function showPaymentMethods($gruppen='')
	{
		// unset($_SESSION['PaymentId']);
		$PaymentMethods = array();
		if(isset($_SESSION['ShipperId']) && isset($_POST['Land']) )
		{
			$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_zahlungsmethoden WHERE Aktiv = 1 ORDER BY Position ASC");
			while($row = $sql->fetchrow())
			{
				$Laender = explode(',', $row->ErlaubteVersandLaender);
				$Gruppen = explode(',', $row->ErlaubteGruppen);
				$Versand = explode(',', $row->ErlaubteVersandarten);
				$row->Kosten = $row->Kosten;

				// Nur Zahlungsarten für erlaubte Benutzergruppe ausgeben
				if(in_array($_SESSION['ShipperId'], $Versand) && in_array($_POST['Land'],$Laender) && in_array($_SESSION['cp_ugroup'], $Gruppen)) array_push($PaymentMethods, $row);

			}
		}
		return $PaymentMethods;
	}

	function getPaymentText($id)
	{
		$sql = $GLOBALS['db']->Query("SELECT Beschreibung FROM " . PREFIX . "_modul_shop_zahlungsmethoden WHERE Id = '$id'");
		$row = $sql->fetchrow();
		$sql->close();
		return $row->Beschreibung;
	}


	function getDiscount()
	{
		if(defined("UGROUP"))
		{
			$sql = $GLOBALS['db']->Query("SELECT Wert FROM " . PREFIX . "_modul_shop_rabatte WHERE Gruppe = '" . UGROUP . "'");
			$row = $sql->fetchrow();
			$sql->close();
			return $row->Wert;
		}
	}

	//=======================================================
	// Prüfung
	//=======================================================
	function checkOut()
	{
		if(!isset($_SESSION['Product']) || count($_SESSION['Product']) < '1')
		{
			header("Location:index.php?module=shop&action=showbasket");
			exit;
		}

		$checkoutinfo = false;
		$orderok = false;

		// Formular auf fehlende Angaben überprüfen
		if(isset($_REQUEST['send']) && $_REQUEST['send'] == 1)
		{
			$errors = array();
			if(isset($_POST['billing_firstname']) && $_POST['billing_firstname'] == '') array_push($errors, $GLOBALS['mod']['config_vars']['Errors_NoFirstName']);
			if(isset($_POST['billing_lastname']) && $_POST['billing_lastname'] == '') array_push($errors, $GLOBALS['mod']['config_vars']['Errors_NoLastName']);
			if(isset($_POST['billing_street']) && $_POST['billing_street'] == '') array_push($errors, $GLOBALS['mod']['config_vars']['Errors_NoStreet']);
			if(isset($_POST['billing_streetnumber']) && $_POST['billing_streetnumber'] == '') array_push($errors, $GLOBALS['mod']['config_vars']['Errors_NoStreetNr']);
			if(isset($_POST['billing_zip']) && $_POST['billing_zip'] == '' || (!preg_match("/^[0-9]{5}$/", $_POST['billing_zip']) && $_POST['Land']=='DE') ) array_push($errors, $GLOBALS['mod']['config_vars']['Errors_NoZip']);
			if(isset($_POST['billing_town']) && $_POST['billing_town'] == '') array_push($errors, $GLOBALS['mod']['config_vars']['Errors_NoTown']);
			if(isset($_POST['OrderEmail']) && $_POST['OrderEmail'] == '' || !@ereg("^[ -._A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,4})$", chop($_POST['OrderEmail']))) array_push($errors, $GLOBALS['mod']['config_vars']['Errors_NoEmail']);

			// Formularwerte in Session schreiben
			if(isset($_POST['OrderEmail'])) $_SESSION['OrderEmail'] = chop($_POST['OrderEmail']);
			if(isset($_POST['OrderPhone'])) $_SESSION['OrderPhone'] = $_POST['OrderPhone'];
			if(isset($_POST['billing_company'])) $_SESSION['billing_company'] = $_POST['billing_company'];
			if(isset($_POST['billing_company_reciever'])) $_SESSION['billing_company_reciever'] = $_POST['billing_company_reciever'];
			if(isset($_POST['billing_firstname'])) $_SESSION['billing_firstname'] = $_POST['billing_firstname'];
			if(isset($_POST['billing_lastname'])) $_SESSION['billing_lastname'] = $_POST['billing_lastname'];
			if(isset($_POST['billing_street'])) $_SESSION['billing_street'] = $_POST['billing_street'];
			if(isset($_POST['billing_streetnumber'])) $_SESSION['billing_streetnumber'] = $_POST['billing_streetnumber'];
			if(isset($_POST['billing_zip'])) $_SESSION['billing_zip'] = $_POST['billing_zip'];
			if(isset($_POST['billing_town'])) $_SESSION['billing_town'] = $_POST['billing_town'];
			if(isset($_POST['Land'])) $_SESSION['billing_country'] = $_POST['Land'];

			// Formularwerte (Rechnungsadresse)
			if(isset($_POST['shipping_company'])) $_SESSION['shipping_company'] = $_POST['shipping_company'];
			if(isset($_POST['shipping_company_reciever'])) $_SESSION['shipping_company_reciever'] = $_POST['shipping_company_reciever'];
			if(isset($_POST['shipping_firstname'])) $_SESSION['shipping_firstname'] = $_POST['shipping_firstname'];
			if(isset($_POST['shipping_lastname'])) $_SESSION['shipping_lastname'] = $_POST['shipping_lastname'];
			if(isset($_POST['shipping_street'])) $_SESSION['shipping_street'] = $_POST['shipping_street'];
			if(isset($_POST['shipping_streetnumber'])) $_SESSION['shipping_streetnumber'] = $_POST['shipping_streetnumber'];
			if(isset($_POST['shipping_zip'])) $_SESSION['shipping_zip'] = $_POST['shipping_zip'];
			if(isset($_POST['shipping_town'])) $_SESSION['shipping_town'] = $_POST['shipping_town'];

			// Es sind Fehler vorhanden. Benutzer wird zurück geleitet!
			if(count($errors) > 0)
			{
				$errors = base64_encode(serialize($errors));
				header("Location:index.php?module=shop&action=checkout&create_account=" . $_REQUEST['create_account'] . "&errors=$errors");
				exit;
			}
		}

		// Wenn kein Artikel im Warenkorb liegt, zum Shop weiterleiten
		if(!isset($_SESSION['Product']))
		{
			header("Location:index.php?module=shop");
			exit;
		}

		// Alles ausgefüllt...
		if(isset($_REQUEST['zusammenfassung']) && $_REQUEST['zusammenfassung'] == 1)
		{
			if(!isset($_SESSION['ShipperId']) || @$_SESSION['ShipperId'] = '' || !isset($_SESSION['PaymentId']) || @$_SESSION['PaymentId'] == '')
			{
				header("Location:index.php?module=shop");
				exit;
			} else {
				// Bestellung zusammenfassen udn weiterleiten...
				$checkoutinfo = true;
			}
		}

		$this->globalProductInfo();
		$create_account = true;

		if(isset($_REQUEST['ShipperId']) && $_REQUEST['ShipperId'] != '')  $_SESSION['ShipperId'] = $_REQUEST['ShipperId'];
		if(isset($_REQUEST['create_account']) && $_REQUEST['create_account'] == 'no') $create_account = false;
		if(!isset($_SESSION['cp_benutzerid']) && $create_account==true)
		{
			if(isset($_POST['do']) && $_POST['do'] == 'login')
			{
				$this->loginProcess();
			}

			$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'shop_checkout.tpl');
			$tpl_out = $this->shopRewrite($tpl_out);
			define("MODULE_CONTENT", $tpl_out);
			define("MODULE_SITE", $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['ShopPaySite']);
		} else {

			//=======================================================
			// Liefer & Versandadresse
			//=======================================================
			if(isset($_POST['Land']) && $_POST['Land'] != '')
			{
				$GLOBALS['tmpl']->assign('showShipper',$this->showShipper($_POST['Land']));
				$_SESSION['IsShipper'] = 1;
			}

			if(isset($_SESSION['ShipperId'])) $_POST['ShipperId'] = $_SESSION['ShipperId'];
			if(isset($_POST['ShipperId']) && $_POST['ShipperId'] != '' )
			{
				$_SESSION['ShipperId'] = $_POST['ShipperId'][0];
				$sql_su = $GLOBALS['db']->Query("SELECT KeineKosten,Pauschalkosten FROM " . PREFIX . "_modul_shop_versandarten WHERE Id = '$_SESSION[ShipperId]'");
				$row_su = $sql_su->fetchrow();

				$VersFrei = $this->getSetting('VersFrei');
				$VersFreiBetrag = $this->getSetting('VersFreiBetrag');

				//@$_SESSION['BasketSumm'] = ($VersFrei==1 && $_SESSION['BasketSumm'] >= $VersFreiBetrag) ? '0,00' : ($row_su->KeineKosten != 1 && $row_su->Pauschalkosten > 0) ? $_SESSION['BasketSumm'] + $row_su->Pauschalkosten : $_SESSION['BasketSumm'] = $_SESSION['BasketSumm'] + $this->shipperSumm(@$_POST['Land'],$_SESSION['ShipperId']);

				// Endsumme
				if($VersFrei==1 && $_SESSION['BasketSumm'] >= $VersFreiBetrag)
				{
					@$_SESSION['BasketSumm'] = @$_SESSION['BasketSumm'];
				}
				elseif ($row_su->KeineKosten != 1 && $row_su->Pauschalkosten > 0)
				{
					// Pauschale Versandkosten
					@$_SESSION['BasketSumm'] = $_SESSION['BasketSumm'] + $row_su->Pauschalkosten;
				}
				else
				{
					@$_SESSION['BasketSumm'] = $_SESSION['BasketSumm'] + $this->shipperSumm(@$_POST['Land'],$_SESSION['ShipperId']);
				}




				if($VersFrei==1 && $_SESSION['BasketSumm'] >= $VersFreiBetrag)
				{
					@$_SESSION['ShippingSumm'] = '0,00';
				}
				elseif ($row_su->KeineKosten != 1 && $row_su->Pauschalkosten > 0)
				{
					@$_SESSION['ShippingSumm'] = $row_su->Pauschalkosten;
				}
				else
				{
					@$_SESSION['ShippingSumm'] = $this->shipperSumm(@$_POST['Land'],$_SESSION['ShipperId'],$_POST['Land']);
				}

				@$_SESSION['ShippingSummOut'] = @$_SESSION['ShippingSumm'];
			}



			// Preisberechnung
			if(!isset($_POST['PaymentId']) && isset($_SESSION['PaymentId']))
			{
				$_POST['PaymentId'][0] = $_SESSION['PaymentId'];
			}


			if(isset($_POST['PaymentId']) && $_POST['PaymentId'] != '')
			{
				$_SESSION['PaymentId'] = $_POST['PaymentId'][0];
				$sql = $GLOBALS['db']->Query("SELECT Kosten,KostenOperant FROM " . PREFIX . "_modul_shop_zahlungsmethoden WHERE Id = '$_SESSION[PaymentId]'");
				$row = $sql->fetchrow();

				$Kosten =  $_SESSION['BasketSumm'];

				$PluMin = substr($row->Kosten,0,1);
				switch($PluMin)
				{
					case '-':
						$row->Kosten = str_replace('-','',$row->Kosten);
						$Kosten = ($row->KostenOperant=='%') ? $_SESSION['BasketSumm']-$_SESSION['BasketSumm']/100*$row->Kosten : $_SESSION['BasketSumm'] - $row->Kosten;
						$KostenZahlungOp = ($row->KostenOperant=='%') ? '%'  : $this->getSetting('WaehrungSymbol');
						$_SESSION['KostenZahlung'] = '- ' . $row->Kosten . ' ' . $KostenZahlungOp;
						$_SESSION['KostenZahlungOut'] = ($row->KostenOperant=='%') ? $row->Kosten  : $row->Kosten;
						$_SESSION['KostenZahlungPM'] = '-';
					break;

					case '':
					case '+':
					default:
						$row->Kosten = str_replace('+','',$row->Kosten);
						$Kosten = ($row->KostenOperant=='%') ? $_SESSION['BasketSumm']+$_SESSION['BasketSumm']/100*$row->Kosten  : $_SESSION['BasketSumm'] + $row->Kosten;
						$KostenZahlungOp = ($row->KostenOperant=='%') ? '%'  : $this->getSetting('WaehrungSymbol');
						$_SESSION['KostenZahlung'] = $row->Kosten . ' ' . $KostenZahlungOp;
						$_SESSION['KostenZahlungOut'] = ($row->KostenOperant=='%') ? $row->Kosten  : $row->Kosten;
						$_SESSION['KostenZahlungPM'] = '+';
					break;
				}

				$_SESSION['BasketSumm'] = $Kosten;
				$_SESSION['KostenZahlungSymbol'] = $KostenZahlungOp;
			}
			$globals = new Globals;


			if(isset($_SESSION['cp_benutzerid']))
			{
				$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_users WHERE Id = '$_SESSION[cp_benutzerid]'");
				$row = $sql->fetchrow();
				$sql->close();
				$GLOBALS['tmpl']->assign('row', $row);
			}

			$GLOBALS['tmpl']->assign('shippingCountries', explode(',', $this->getSetting('VersandLaender')));
			$GLOBALS['tmpl']->assign('Endsumme', $_SESSION['BasketSumm']);
			$GLOBALS['tmpl']->assign('available_countries', $GLOBALS['globals']->fetchCountries());
			$GLOBALS['tmpl']->assign('PaymentMethods', $this->showPaymentMethods());

			if(isset($_REQUEST['errors']) && $_REQUEST['errors'] != '')
			{
				$errors = base64_decode($_REQUEST['errors']);
				$errors = unserialize($errors);
				$GLOBALS['tmpl']->assign('errors', $errors);
			}

			// Zusammenfassung
			if($checkoutinfo==true)
			{
				if(isset($_REQUEST['sendorder'])  && $_REQUEST['sendorder'] == 1 && !isset($_REQUEST['agb_accept']))
				{
					$GLOBALS['tmpl']->assign('NoAGB', 1);
					$orderok = false;
				}

				if(isset($_REQUEST['sendorder'])  && $_REQUEST['sendorder'] == 1 && isset($_REQUEST['agb_accept']) && $_REQUEST['agb_accept']==1)
				{
					$orderok = true;
				}


				// Gutscheincode löschen
				if(isset($_POST['couponcode_del']) && $_POST['couponcode_del'] == '1' && $this->getShopSetting('GutscheinCodes') == 1)
				{
					unset($_SESSION['CouponCode']);
					unset($_SESSION['CouponCodeId']);
					$GLOBALS['tmpl']->assign('NoAGB', '0');
				}

				// Gutscheincode einlösen
				if(isset($_REQUEST['couponcode']) && $_REQUEST['couponcode'] != '' && $this->getShopSetting('GutscheinCodes') == 1)
				{
					$use_coupon = true;
					$sql_cc = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_gutscheine WHERE Code = '".chop($_POST['couponcode'])."'");
					$row_cc = $sql_cc->fetchrow();
					$sql_cc->close();
					if(is_object($row_cc) && $row_cc->Prozent != '' && $row_cc->Prozent < 100)
					{
						$Benutzer = explode(',', $row_cc->Benutzer);
						if(isset($_SESSION['cp_benutzerid']) && in_array($_SESSION['cp_benutzerid'], $Benutzer)/* && $row_cc->Mehrfach != 1*/) $use_coupon = false;
						if($_SESSION['cp_ugroup'] == 2 && $row_cc->AlleBenutzer != 1) $use_coupon = false;
						if($row_cc->GueltigVon > time()) $use_coupon = false;
						if($row_cc->GueltigBis < time()) $use_coupon = false;

						if($use_coupon == true)
						{
							$_SESSION['CouponCode'] = $row_cc->Prozent;
							$_SESSION['CouponCodeId'] = $row_cc->Id;
							$_SESSION['BasketSumm'] = $_SESSION['BasketSumm'] - ($_SESSION['Zwisumm'] / 100 * $row_cc->Prozent);
						}
					}
					$GLOBALS['tmpl']->assign('NoAGB', '0');
				}

				$GLOBALS['tmpl']->assign('AGB', $this->getSetting('Agb'));
				$GLOBALS['tmpl']->assign('step', '2');
				$GLOBALS['tmpl']->assign('ShipperName', $this->fetchShipper($_SESSION['ShipperId']));
				$GLOBALS['tmpl']->assign('PaymentMethod', $this->showPaymentMethod($_SESSION['PaymentId']));
				$GLOBALS['tmpl']->assign('PaymentOverall', $_SESSION['BasketSumm']);
				$GLOBALS['tmpl']->assign('PaymentOverall2', (defined("Waehrung2Multi") ? $_SESSION['BasketSumm'] * @Waehrung2Multi : ''));

				if($orderok==true)
				// Bestellung eintragen und weitere Aktionen durchführen
				{
					$ProductsOrder = (isset($_SESSION['Product']) && $_SESSION['Product'] != '') ? serialize($_SESSION['Product']) : '';
					$ProductsOrderVars = (isset($_SESSION['ProductVar']) && $_SESSION['ProductVar'] != '') ? serialize($_SESSION['ProductVar']) : '';
					// $transId = 'CPE_' . $this->transId('12') . '_' . date('dmy');
					$transId = '' . $this->transId('7') . '' . date('dmy');
					$_SESSION['TransId'] = $transId;

					//echo $_REQUEST['create_account'];

					// Besteller
					$Benutzer = (isset($_SESSION['cp_benutzerid'])) ? $_SESSION['cp_benutzerid'] : $_SESSION['OrderEmail'];

					// Enthaltene Umsatzssteuer
					$USt = '';

					// Rechnung (Text - Format)
					$RechnungText = '';

					// Rechnung (HTML - Format)
					$RechnungHtml = '';

					// Kam der Käufer von einer bestimmten Seite?
					$KamVon = (isset($_SESSION['Referer'])) ? $_SESSION['Referer'] : '';

					// Gutscheincode - Wert
					$Gutscheincode = '';

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
							Rech_Land
						) VALUES (
							'',
							'$Benutzer',
							'$transId',
							'" . time() . "',
							'" . str_replace(',','.',$_SESSION['BasketSumm']) . "',
							'" . $USt . "',
							'" . $ProductsOrder . "',
							'" . $ProductsOrderVars . "',
							'" . $RechnungText . "',
							'" . $RechnungHtml . "',
							'" . nl2br(stripslashes($_POST['Msg'])) ."',
							'" . $_SERVER['REMOTE_ADDR'] . "',
							'" . $_SESSION['PaymentId'] . "',
							'" . $_SESSION['ShipperId'] . "',
							'" . $KamVon . "',
							'" . (isset($_SESSION['CouponCodeId']) ? $_SESSION['CouponCodeId'] : '') . "',
							'" . $_SESSION['OrderEmail'] . "',
							'" . (isset($_SESSION['billing_company']) ? $_SESSION['billing_company'] : '') . "',
							'" . (isset($_SESSION['billing_company_reciever']) ? $_SESSION['billing_company_reciever'] : '') . "',
							'" . $_SESSION['billing_firstname'] . "',
							'" . $_SESSION['billing_lastname'] . "',
							'" . $_SESSION['billing_street'] . "',
							'" . $_SESSION['billing_streetnumber'] . "',
							'" . $_SESSION['billing_zip'] . "',
							'" . $_SESSION['billing_town'] . "',
							'" . $_POST['Land'] . "',
							'" . (isset($_SESSION['shipping_company']) && $_SESSION['shipping_company'] != '' ? $_SESSION['shipping_company'] : (isset($_SESSION['billing_company']) ? $_SESSION['billing_company'] : '') ) . "',
							'" . (isset($_SESSION['shipping_company_reciever']) && $_SESSION['shipping_company_reciever'] != '' ? $_SESSION['shipping_company_reciever'] : (isset($_SESSION['billing_company_reciever']) ? $_SESSION['billing_company_reciever'] : '')) . "',
							'" . (isset($_SESSION['shipping_firstname']) && $_SESSION['shipping_firstname'] != '' ? $_SESSION['shipping_firstname'] : $_SESSION['billing_firstname']) . "',
							'" . (isset($_SESSION['shipping_lastname']) && $_SESSION['shipping_lastname'] != '' ? $_SESSION['shipping_lastname'] : $_SESSION['billing_lastname']) . "',
							'" . (isset($_SESSION['shipping_street']) && $_SESSION['shipping_street'] != '' ? $_SESSION['shipping_street'] : $_SESSION['billing_street']) . "',
							'" . (isset($_SESSION['shipping_streetnumber']) && $_SESSION['shipping_streetnumber'] != '' ? $_SESSION['shipping_streetnumber'] : $_SESSION['billing_streetnumber']) . "',
							'" . (isset($_SESSION['shipping_zip']) && $_SESSION['shipping_zip'] != '' ? $_SESSION['shipping_zip'] : $_SESSION['billing_zip']) . "',
							'" . (isset($_SESSION['shipping_town']) && $_SESSION['shipping_town'] != '' ? $_SESSION['shipping_town'] :  $_SESSION['billing_town']) . "',
							'" . $_POST['RLand'] . "'
						)
					";
					$sql = $GLOBALS['db']->Query($q);
					$OrderId = $GLOBALS['db']->insertid();

					// TransId in Gutscheine eintragen
					if(isset($_SESSION['CouponCode']) && $_SESSION['CouponCode'] != '')
					{
						$sql_cc = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_gutscheine WHERE Id = '" . $_SESSION['CouponCodeId'] . "'");
						$row_cc = $sql_cc->fetchrow();

						$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_gutscheine SET BestellId = CONCAT(BestellId, ',', '" . $OrderId . "') WHERE Id = '" . $_SESSION['CouponCodeId'] . "' ");
						if(isset($_SESSION['cp_benutzerid']) && $_SESSION['cp_benutzerid'] != '')
						{
							$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_gutscheine SET Benutzer = CONCAT(Benutzer, ',', '" . $_SESSION['cp_benutzerid']. "') WHERE Id = '" . $_SESSION['CouponCodeId'] . "' ");
						}

						if($row_cc->Mehrfach != 1)
						{
							$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_gutscheine SET Eingeloest = 1 WHERE Id = '" . $_SESSION['CouponCodeId'] . "' ");
						}

					}

					// Anzahl der Käufe im Artikel erhöhen
					$arr = $_SESSION['Product'];
					foreach ($arr as $key => $value)
					{
						$sql = $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_artikel SET Bestellungen=Bestellungen+$value WHERE Id='$key'");
					}

					$GLOBALS['tmpl']->assign('PaymentText', ($this->getShopSetting('EmailFormat') == 'html' ? $this->getPaymentText($_SESSION['PaymentId']) : strip_tags($this->getPaymentText($_SESSION['PaymentId']))));
					$GLOBALS['tmpl']->assign('TransCode', $transId);
					$GLOBALS['tmpl']->assign('CompanyHead', ($this->getShopSetting('EmailFormat') == 'html' ? $this->getShopSetting('AdresseHTML') : $this->getShopSetting('AdresseText')));
					$GLOBALS['tmpl']->assign('CompanyLogo', ($this->getShopSetting('Logo')!= '' ? $this->getShopSetting('Logo') : ''));
					$GLOBALS['tmpl']->assign('OrderId', $OrderId);
					$GLOBALS['tmpl']->assign('OrderTime', time());
					$GLOBALS['tmpl']->assign('VatZones', $this->showVatZones());

					// HTML- & Text E-Mail Template laden
					$mail_html = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'shop_orderconfirm_html.tpl');
					$mail_text = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'shop_orderconfirm_text.tpl');
					$mail_text = $this->textReplace($mail_text);

					// E-Mail mit Bestellbestätigung an Käufer senden
					$globals = new Globals;

					// Soll E-Mail als Text oder HTML versendet werden?
					if($this->getShopSetting('EmailFormat') == 'html')
					{
						$GLOBALS['globals']->cp_mail($this->getShopSetting('EmpEmail'), $mail_html, $this->getShopSetting('BetreffBest'), $this->getShopSetting('AbsEmail'), $this->getShopSetting('AbsName'), 'html', '','1');
						$GLOBALS['globals']->cp_mail($_SESSION['OrderEmail'], $mail_html, $this->getShopSetting('BetreffBest'), $this->getShopSetting('AbsEmail'), $this->getShopSetting('AbsName'), 'html', '','1');
						$GLOBALS['tmpl']->assign('innerhtml', htmlspecialchars($mail_html));
					} else {
						$GLOBALS['globals']->cp_mail($this->getShopSetting('EmpEmail'), $mail_text, $this->getShopSetting('BetreffBest'), $this->getShopSetting('AbsEmail'), $this->getShopSetting('AbsName'), 'html', '','');
						$GLOBALS['globals']->cp_mail($_SESSION['OrderEmail'], $mail_html, $this->getShopSetting('BetreffBest'), $this->getShopSetting('AbsEmail'), $this->getShopSetting('AbsName'), 'html', '','');
						$GLOBALS['tmpl']->assign('innerhtml', $mail_text);
					}
					// E-Mail mit Bestellbestätigung an Admin senden

					// Rechnung als HTML & Text in Bestellung aktualisieren
					$sql = $GLOBALS['db']->Query("UPDATE " . PREFIX ."_modul_shop_bestellungen
						SET
							RechnungHtml = '$mail_html',
							RechnungText = '$mail_text'
						WHERE Id = '$OrderId'");

					// Gibt es ein Zahlunsg - Gateway?
					$extern = false;
					$sql_gw = $GLOBALS['db']->Query("SELECT * FROM  " . PREFIX ."_modul_shop_zahlungsmethoden WHERE Aktiv = 1 AND Id = '$_SESSION[PaymentId]'");
					$row_gw = $sql_gw->fetchrow();
					if(is_object($row_gw) && $row_gw->Extern==1 && file_exists(BASE_DIR . '/modules/shop/gateways/' . $row_gw->Gateway . '.php'));
					{
						$Waehrung = $this->getShopsetting('Waehrung');
						if(@include(BASE_DIR . '/modules/shop/gateways/' . $row_gw->Gateway . '.php'))
						{
							$extern = true;
						}
					}
					if($extern == true)
					{
						exit;
					}

					// Wenn es keinen Zahlungs - Gateway gibt, Bestätigungs - Seite anzeigen
					unset($_SESSION['Zwisumm']);
					unset($_SESSION['BasketSumm']);
					unset($_SESSION['BasketOverall']);
					unset($_SESSION['ShippingSummOut']);
					unset($_SESSION['ShippingSumm']);
					unset($_SESSION['ShipperId']);
					unset($_SESSION['IsShipper']);
					unset($_SESSION['Product']);
					unset($_SESSION['VatInc']);
					unset($_SESSION['GewichtSumm']);
					unset($_SESSION['PaymentId']);
					unset($_SESSION['CouponCode']);
					unset($_SESSION['CouponCodeId']);
					unset($_SESSION['KostenZahlung']);
					unset($_SESSION['KostenZahlungOut']);

					$GLOBALS['tmpl']->assign('step', '3');
					$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'shop_confirm_thankyou.tpl');
					$tpl_out = $this->shopRewrite($tpl_out);
					define("MODULE_CONTENT", $tpl_out);
					define("MODULE_SITE", $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['ShopPaySite']);

				}
				// Zusammenfassung anzeigen
				else
				{
					if(!isset($_SESSION['ShipperId']) || $_SESSION['ShipperId'] == '')
					{
						header("Location:index.php?module=shop&action=checkout&create_account=no");
						exit;
					}

					if($this->getShopSetting('GutscheinCodes') == 1) $GLOBALS['tmpl']->assign('couponcodes', '1');
					$GLOBALS['tmpl']->assign('VatZones', $this->showVatZones());
					$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'shop_checkoutinfo.tpl');
					$tpl_out = $this->shopRewrite($tpl_out);
					define("MODULE_CONTENT", $tpl_out);
					define("MODULE_SITE", $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['ShopPaySite']);
				}
			}
			else
			{
				if(!isset($_SESSION['cp_benutzerid']) && GastBestellung != 1)
				{
					header("Location:index.php?module=shop&action=checkout");
					exit;
				}

				$GLOBALS['tmpl']->assign('step', '1');
				$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'shop_billing.tpl');
				$tpl_out = $this->shopRewrite($tpl_out);
				define("MODULE_CONTENT", $tpl_out);
				define("MODULE_SITE", $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['ShopPaySite']);
			}
		}
	}


}
?>
