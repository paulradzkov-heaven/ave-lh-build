<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("CUSTOMER_DISCOUNTS"))
{
	exit;
}
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save')
		{
			// Speichern
			foreach($_POST['Wert'] as $id => $Wert)
			{
				$s = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_shop_kundenrabatte WHERE GruppenId = '$id'");
				$q = $s->numrows($s);
				if($q<1)
				{
					$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_shop_kundenrabatte
					(
						Id,
						GruppenId,
						Wert
					) VALUES (
						'',
						'$id',
						'" . $this->kreplace($_POST['Wert'][$id]) . "'
					)");
				}
				
				$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_shop_kundenrabatte SET 
					Wert = '" . (($_POST['Wert'][$id]<100) ? $this->kreplace($_POST['Wert'][$id]) : 99 ) . "' 
				WHERE 
					GruppenId = '$id'
					");
			}
		}
		
		$ugroups = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_user_groups");
		while($row = $sql->fetchrow())
		{
			$sql_r = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_shop_kundenrabatte WHERE GruppenId = '$row->Benutzergruppe'");
			$row_r = $sql_r->fetchrow();
			
			$row->Wert = is_object($row_r) ? $row_r->Wert : '0.00';
			array_push($ugroups, $row);
		}
		
	
		// Auslesen
		$Discounts = array();
		
		while($row = $sql->fetchrow())
		{
			array_push($Discounts, $row);
		}
		
		$GLOBALS['tmpl']->assign('Discounts', $Discounts);
		$GLOBALS['tmpl']->assign('Groups', $ugroups);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'shop_discounts.tpl'));
?>