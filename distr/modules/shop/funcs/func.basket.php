<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("INC_BASKET")) exit;

	
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
			//$item->EPreis = '';
			$item->Id = $key;
			$item->Val = $value;
			$SummVars = '';
			//$IncVat = '';
			
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
						$row_vars->Wert = @$row_vars->Wert;
						$row_vars->WertE =@$row_vars->Wert;
						array_push($Vars, $row_vars);
					}
				}
			}
			// echo $SummVars . "<br>";
			$SummVarsE = $SummVars; 
			$SummVars = $SummVars*$value;
			
			$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_artikel WHERE Id = '$key'");
			$row = $sql->fetchrow();
			$Einzelpreis = $row->Preis;
			// Preis des Artikels
			// 
			$row->Preis = (!$this->getNewPrice($key, $value) ) ? (($value >= 2) ? $this->getNewPrice($key, $value, 1, $this->getDiscountVal($row->Preis)) : $row->Preis ) : $this->getNewPrice($key, $value);
			
			// Wenn Benutzer registriert ist, muss hier geschaut werden, welches Land der 
			// Benutzer bei der Registrierung angegeben hat, damit der richtige Preis angezeigt wird
			// Wenn nach dem Ansenden des Formulars (Checkout) ein anderes Versandland angegeben wird
			// als bei der Registrierung, muss dieses Land verwendet werden um die Versandkosten zu berechnen
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
			if(is_object($row_ieu) && $row_ieu->IstEU==2)
			{
				// Benutzer ist angemeldet, hat Umsatzsteuerbefreiung
				if(isset($_SESSION['cp_benutzerid']) && 
				$_SESSION['cp_benutzerid'] != '' && 
				$_SESSION['GewichtSumm'] >= '0.001' && 
				($this->getUserInfo($_SESSION['cp_benutzerid'],'UStPflichtig')!=1) || $this->getUserInfo($_SESSION['cp_benutzerid'],'UStPflichtig')==1)
				{
					$PayUSt = false;
				} 
				
				// Benutzer ist angemeldet, hat keine Umsatzsteuerbefreiung
				elseif(isset($_SESSION['cp_benutzerid']) && 
				$_SESSION['cp_benutzerid'] != '' && 
				$_SESSION['GewichtSumm'] < '0.001' && 
				($this->getUserInfo($_SESSION['cp_benutzerid'],'UStPflichtig')==1))
				{
					$PayUSt = true;
					$_SESSION['ShowNoVatInfo'] = 1;
				}
				
				// Downloadbare Ware?
				// Benutzer ist nicht angemeldet, Versandgewicht ist gegeben!
				elseif(!isset($_SESSION['cp_benutzerid']) && 
				$_SESSION['cp_benutzerid'] == '' && 
				$_SESSION['GewichtSumm'] >= '0.001')
				{
					$PayUSt = false;
				}
				
				// Downloadbare Ware?
				// Benutzer ist nicht angemeldet, Versandgewicht ist nicht gegeben!
				elseif(!isset($_SESSION['cp_benutzerid']) && 
				$_SESSION['cp_benutzerid'] == '' && 
				$_SESSION['GewichtSumm'] < '0.001')
				{
					$PayUSt = true;
					$_SESSION['ShowNoVatInfo'] = 1;
				}
				else
					$PayUSt = true;
			} else {
				$PayUSt = true;
			}
			
			if($PayUSt != true)
			{
				$row->Preis = $this->getDiscountVal($row->Preis) - $this->getVat($key);
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
			$item->EPreis = (($PayUSt != true) ? (($this->getDiscountVal($Einzelpreis)+$SummVarsE) / $this->getVat($key)) : ($this->getDiscountVal($Einzelpreis)+$SummVarsE));
		
			// Summe unter Berücksichtung der Anzahl
			$item->EPreisSumme = (($PayUSt != true) ? (($this->getDiscountVal($Einzelpreis*$value)+$SummVarsE) / $this->getVat($key)) : ($this->getDiscountVal($Einzelpreis)+$SummVarsE)*$value);
			
			$item->Gewicht = $row->Gewicht*$value;
			$item->ArtNr = $row->ArtNr;
			
			
			// Endpreis aller Artikel
			$PreisGesamt += $item->EPreisSumme;
			$GewichtGesamt += $item->Gewicht;
			
			// Preis 2.Währung
			if(defined("WaehrungSymbol2") && defined("Waehrung2") && defined("Waehrung2Multi"))
			{
				@$item->PreisW2 = (($PayUSt != true) ? (($this->getDiscountVal($Einzelpreis * Waehrung2Multi)+$SummVarsE) / $this->getVat($key)) : ($this->getDiscountVal($Einzelpreis)+$SummVarsE));;//($row->Preis * Waehrung2Multi);
				@$item->PreisW2Summ = (($PayUSt != true) ? (($this->getDiscountVal($Einzelpreis * Waehrung2Multi * $value)+$SummVarsE) / $this->getVat($key)) : ($this->getDiscountVal($Einzelpreis)+$SummVarsE)*$value);;//($row->Preis * Waehrung2Multi*$value);
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
				@$_SESSION[$item->Vat] += ($IncVat * 1) ; // * $value
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
		$_SESSION['GewichtSumm'] = $GewichtGesamt;
		
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
?>