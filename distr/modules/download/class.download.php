<?php
class Download {

	var $_expander = '&nbsp;&nbsp;&nbsp;&nbsp;';
	var $_shop_navi = 'shop_navi.tpl';
	var $_waitTime = 5;
	var $_newestLimit = 10;
	var $_overview_limit = 25;
	var $_maxCommentLength = 1000;
	var $_maxLenWord = 50;
	var $_anti_spam = 1;

	//=======================================================
	// Check, ob Option verfuegbar
	//=======================================================
	function CheckActive($Option)
	{
		$sql = $GLOBALS['db']->Query("SELECT {$Option} FROM " . PREFIX . "_modul_download_settings");
		$row = $sql->fetcharray();
		if($row[$Option]==1) return true;
		return false;
	}

	function secureRequest()
	{
		if(isset($_REQUEST['c'])) $_REQUEST['c'] = eregi_replace("[^_A-Za-zА-Яа-яЁё0-9]", "", $_REQUEST['c']);
		if(isset($_REQUEST['categ'])) $_REQUEST['categ'] = eregi_replace('[^0-9]', '', $_REQUEST['categ']);
		if(isset($_REQUEST['parent'])) $_REQUEST['parent'] = eregi_replace('[^0-9]', '', $_REQUEST['parent']);
		if(isset($_REQUEST['navop'])) $_REQUEST['navop'] = eregi_replace('[^0-9]', '', $_REQUEST['navop']);
		if(isset($_REQUEST['pp'])) $_REQUEST['pp'] = eregi_replace('[^0-9]', '', $_REQUEST['pp']);
		$_REQUEST['page'] = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? eregi_replace('[^0-9]', '', $_REQUEST['page']) : '1';
	}

	//=======================================================
	// Ersetzt Zeichen fьr die Umschreibung der Kategorie in
	// den Links fьr MOD_REWRITE
	//=======================================================
	function replaceWild($text)
	{
/*
		$text = str_replace('ь', 'ue', $text);
		$text = str_replace('Ь', 'Ue', $text);
		$text = str_replace('ц', 'oe', $text);
		$text = str_replace('Ц', 'Oe', $text);
		$text = str_replace('д', 'ae', $text);
		$text = str_replace('Д', 'Ae', $text);
		$text = str_replace(array('&', '&amp;'), 'and', $text);
		$text = eregi_replace("[^+_A-Za-zА-Яа-яЁё0-9]", "_", $text);
*/
		$text = cp_parse_linkname($text);
		return $text;
	}

	//=======================================================
	// Wandelt Zeichen in XHTML-Konforme Zeichen um
	//=======================================================
	function prettyChars($text)
	{
		return $text;
/*
		$text = str_replace('ь', '&uuml;', $text);
		$text = str_replace('Ь', '&Uuml;', $text);
		$text = str_replace('ц', '&ouml;', $text);
		$text = str_replace('Ц', '&Ouml;', $text);
		$text = str_replace('д', '&auml;', $text);
		$text = str_replace('Д', '&Auml;', $text);
		$text = str_replace(' & ', ' &amp; ', $text);
		$text = str_replace('»', '&raquo;', $text);
		$text = str_replace('«', '&laquo;', $text);
		$text = str_replace('>', '&gt;', $text);
		$text = str_replace('<', '&lt;', $text);
		$text = str_replace('Я', '&szlig;', $text);
		$text = str_replace('Ђ', '&euro;', $text);
		$text = str_replace('©', '&copy;', $text);
		$text = str_replace('®', '&reg;', $text);
		$text = str_replace('™', '&#8482;', $text);
		return $text;
*/
	}

	//=======================================================
	// Aktuelle URL zerlegen unter Berьcksichtigung
	// von mod_rewrite
	//=======================================================
	function localRedir()
	{
		$r = redir();
		$r2 = explode('index.php', $r);
		$r3 = $r2[0];
		$r4 = $this->DL_Rewrite("index.php" . $r2[1]);
    $r4 = str_replace( "&amp;", "&", $r4 );
		$File_URL =  $r2[0] . $r4;
		return $File_URL;
	}

	//=======================================================
	// Funktion zum kьrzen ьberlanger Wцrter (Spam)
	//=======================================================
	function cutLongWords($Kommentar)
	{
		$len = $this->_maxLenWord;
		$neutext = $Kommentar;
		do
		{
			$Kommentar = $neutext;
			$neutext = preg_replace('~(^|\s)(\S{'.$len.'})(\S)~S', '\1\2 \3', $Kommentar);
		} while ($neutext != $Kommentar);
		return $neutext;
	}

	//=======================================================
	// Ermittelt die Elter-Kategorie
	//=======================================================
	function getParentDownloadCateg($param='')
	{
		$id = (is_array($param)) ? $param['id'] : $param ;
		$parent_id = $id;
		$id = 0;
		while($parent_id != 0)
		{
			$sql = $GLOBALS['db']->Query("SELECT * FROM ".PREFIX."_modul_download_kat WHERE Id='".$parent_id."'");

			$row = $sql->fetchrow();
			@$parent_id = $row->Elter;
			@$id = $row->Id;
		}
		return($id);
	}

	//=======================================================
	// Erzeugt die Downloadkatregorien fьr die Ьbersicht
	//=======================================================
	function getCategoriesSimple($id, $prefix, $entries=0, $admin=0, $dropdown=0, $itid='')
	{
		$query = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_download_kat WHERE Elter = '$id' order by Rang ASC");

		if (!$query->numrows()) return;

		while ($item = $query->fetchrow())
		{
			$gruppen = explode("|", $item->Gruppen);
			if(in_array(UGROUP, $gruppen))
			{
				$item->visible_title = $prefix . (($item->Elter!=0 && $admin != 1) ? '' : '') . $item->KatName;
				$item->expander = $prefix;
				$item->sub = ($item->Elter==0) ? 0 : 1;
				$item->dyn_link = "index.php?module=download&amp;categ=$item->Id&amp;parent=$item->Elter&amp;navop=" . (($item->Elter==0) ? $item->Id : $this->getParentDownloadCateg($item->Elter)) . "&amp;c=" . $this->replaceWild($item->KatName);
				$item->dyn_link = $this->DL_Rewrite($item->dyn_link);

				$sql = $GLOBALS['db']->Query("SELECT Id,KatId FROM ".PREFIX."_modul_download_files WHERE KatId = '$item->Id' AND Aktiv='1'");
				$item->acount = $sql->numrows();

				// 1. Unterkategorie durchgehen
				$sub_categs = array();
				$query_sub = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_download_kat WHERE Elter = '$item->Id' order by Rang ASC");
				while($sub = $query_sub->fetchrow())
				{
					$sub->visible_title = $prefix . (($sub->Elter!=0 && $admin != 1) ? '' : '') . $sub->KatName;
					$sub->expander = $prefix;
					$sub->sub = ($sub->Elter==0) ? 0 : 1;
					$sub->dyn_link = "index.php?module=download&amp;categ=$sub->Id&amp;parent=$sub->Elter&amp;navop=" . (($sub->sub==0) ? $sub->Id : $this->getParentDownloadCateg($sub->Elter)) . "&amp;c=" . $this->replaceWild($sub->KatName);
					$sub->dyn_link = $this->DL_Rewrite($sub->dyn_link);

					$sql_sub = $GLOBALS['db']->Query("SELECT Id,KatId FROM ".PREFIX."_modul_download_files WHERE KatId = '$sub->Id' AND Aktiv='1'");
					$sub->fileCount = $sql_sub->numrows();
					array_push($sub_categs, $sub);
				}

				$item->subs = $sub_categs;
				array_push($entries,$item);
			}
			$this->getCategoriesSimple($item->Id, $prefix . (($dropdown==1) ? '&nbsp;&nbsp;' : $this->_expander), $entries, $dropdown,  $item->Id);

		}
		return $entries;
	}

	//=======================================================
	// Erzeugt den Navigations-Baum
	//=======================================================
	function getNavigationPath($id, $result = null, $extra = 0, $nav_op="0")
	{
		// daten des aktuellen bereichs
		$q_item = "SELECT Id,KatName,Elter FROM " . PREFIX . "_modul_download_kat WHERE Id = $id";
		$r_item = $GLOBALS['db']->Query($q_item);
		$item = $r_item->fetchrow();

		if(is_object($item))
		{
			$esn  = $item->Elter;
			$result_link = $this->DL_Rewrite("index.php?module=download&amp;categ={$item->Id}&amp;parent=$item->Elter&amp;navop=" . $this->getParentDownloadCateg($item->Id) . "&amp;c=" . $this->replaceWild($item->KatName));
			if ($item->Elter == 0) return "<a class='mod_download_navi' href='".$this->DL_Rewrite("index.php?module=download")."'>".$GLOBALS['mod']['config_vars']['PageName']."</a>".$GLOBALS['mod']['config_vars']['PageSep']."<a class='mod_download_navi' href='$result_link'>" . $item->KatName . "</a>" . ($result ? $GLOBALS['mod']['config_vars']['PageSep'] : '') . $result;

			// Daten des darьberliegenden Bereiches
			$q_parent = "SELECT Id,KatName,Elter FROM " . PREFIX . "_modul_download_kat WHERE Id = " . $item->Elter;
			$r_parent = $GLOBALS['db']->Query($q_parent);
			$parent = $r_parent->fetchrow();

			$result_link = $this->DL_Rewrite("index.php?module=download&amp;categ={$item->Id}&amp;parent=$item->Elter&amp;navop=" . $this->getParentDownloadCateg($item->Id) . "&amp;c=" . $this->replaceWild($item->KatName));
			$result = "<a class='mod_download_navi' href='$result_link'>" . $item->KatName . "</a>"  . ($result ? $GLOBALS['mod']['config_vars']['PageSep'] : '') . $result ;
			return $this->getNavigationPath($item->Elter, $result, $extra, $nav_op);
		}
	}

	function DL_Rewrite($print_out)
	{
		if(defined("CP_REWRITE") && CP_REWRITE==1)
		{
			$print_out = DownloadRewrite($print_out);
		}
		return $print_out;
	}

	function displayTree()
	{
		$shopitems = array();
		$categs = array();

		$fetchcat = (isset($_GET['categ']) && is_numeric($_GET['categ'])) ? $_GET['categ'] : '0';
		$GLOBALS['tmpl']->assign('shopitems', $this->getCategoriesSimple($fetchcat, '', $categs, '0'));

		$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'categs.tpl');
		return $tpl_out;
	}

	function readFile($filename,$retbytes=true)
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

	//=======================================================
	// Dateigroessen ausgeben (KB,MB usw.)
	//=======================================================
	function file_size($param, $frombytes=0)
	{
		$size = $param;
		$size = ($frombytes==1) ? $size*1024 : $size;
		$sizes = Array(
			$GLOBALS['mod']['config_vars']['FZ1'],
			$GLOBALS['mod']['config_vars']['FZ2'],
			$GLOBALS['mod']['config_vars']['FZ3'],
			$GLOBALS['mod']['config_vars']['FZ4'],
			$GLOBALS['mod']['config_vars']['FZ5'],
			$GLOBALS['mod']['config_vars']['FZ6']);
		$ext = $sizes[0];
		for ($i = 1; (($i < count($sizes)) && ($size >= 1024)); $i++) {
			$size = $size / 1024;
			$ext = ' ' . $sizes[$i];
		}
		return round($size, 1) . $ext;
	}

	function getLizenzName($id)
	{
		if($id>0)
		{
			$sql = $GLOBALS['db']->Query("SELECT Name FROM " . PREFIX . "_modul_download_lizenzen WHERE Id = '$id'");
			$row = $sql->fetchrow();
			return $row->Name;
		}
	}

	function checkRecommend()
	{
		switch($_REQUEST['ajid'])
		{
			case 'myRequest':
			echo "showDiv||";
			echo 'Есть! соединение установлено<br />';
			break;

			case 'mail':
			echo "showDiv||";
			if(!empty($_SERVER['REMOTE_ADDR']) &&
					@!empty($_REQUEST['mail']) &&
					@!empty($_REQUEST['mymail'])&&
					@!empty($_REQUEST['name']) &&
					@ereg("^[ -._A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,4})$", $_REQUEST['mail']) &&
					@ereg("^[ -._A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,4})$", $_REQUEST['mymail']))
			{
				echo '<input type="submit" class="button" />';
			} else {
				echo '<input class="button" disabled="disabled" type="submit" value="'.$GLOBALS['mod']['config_vars']['ButtonFailed'].'" />';
			}
			break;
		}
	}

	//=======================================================
	// Spamfunction
	//=======================================================
	function noSpam()
	{
		$entry = true;

		$sql_s = $GLOBALS['db']->Query("SELECT Spamwoerter FROM " . PREFIX . "_modul_download_settings");
		$row_s = $sql_s->fetchrow();
		if ($row_s->Spamwoerter == '') return true;

		$spamwords_db = explode("\n", $row_s->Spamwoerter);

		foreach ($_POST as $fieldname => $fieldvalue)
		{
			$fieldvalue = str_replace(" ", "", strtolower($fieldvalue));
			foreach ($spamwords_db as  $stopwords)
			{
				$stopwords = chop(strtolower($stopwords));
				$pattern = "/.*$stopwords.*/";

				// echo "$fieldname ---> $fieldvalue --->  $pattern <br>";
				if (@preg_match($pattern,$fieldvalue))$entry = false;
			}
		}

		if($entry == true) return true;
		return false;
	}

	//=======================================================
	// Download anzeigen
	//=======================================================
	function showFile($file_id,$wm_status='')
	{
		$Perm = true;
		$file_id = (!is_numeric($file_id)) ? 0 : $file_id;
		$sql = $GLOBALS['db']->Query("SELECT
			a.*,
			b.Gruppen,
			b.Id
		FROM
			" . PREFIX . "_modul_download_files  as a,
			" . PREFIX . "_modul_download_kat  as b
		WHERE
			a.Id = '{$file_id}' AND
			b.Id = a.KatId
			AND Aktiv = 1");

		$row = $sql->fetchrow();

		if(!is_object($row))
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location:index.php?module=download");
			exit;
		}

		$sql_lim = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_download_log WHERE FileId = '{$file_id}' AND Datum = '" . date("d.m.Y") . "'");
		$Downloads_Today = $sql_lim->numrows();
		$GLOBALS['tmpl']->assign('Downloads_Today', $Downloads_Today);

		if(is_object($row))
		{
			$row->Lizenz = $this->getLizenzName($row->Lizenz);
			$row->WertungenGesamt = $row->Wertungen_top+$row->Wertungen_flop;
			$row->WertungenTop  = @round((100)/($row->WertungenGesamt/$row->Wertungen_top));
			$row->WertungenFlop = @round((100)/($row->WertungenGesamt/$row->Wertungen_flop));
			$g_array = explode('|', $row->Gruppen);
			$GLOBALS['tmpl']->assign('pname', strip_tags($row->Name));
		}

		//=======================================================
		// Benutzer hat kein Zugriff!
		//=======================================================
		$group_ids = UGROUP;
		if (!@in_array($group_ids, $g_array))
		{
			$error = true;
			$Perm = false;
			$GLOBALS['tmpl']->assign('NoPerm', 1);
		} else {

			if(@!isset($_POST['fileaction']) && @$_POST['fileaction'] != 'comment' && $this->_anti_spam == 1) {
        if(function_exists("imagettftext") && function_exists("imagejpeg")) {
        	$im = $this->secureCode();
        	$sql_sc = $GLOBALS['db']->Query("SELECT Code FROM " . PREFIX . "_antispam WHERE id = '$im'");
        	$row_sc = $sql_sc->fetchrow();
        	$GLOBALS['tmpl']->assign("im", $im);
        	$_SESSION['DLSecureCode'] = $row_sc->Code;
    			$GLOBALS['tmpl']->assign("anti_spam", 1);
        }
			}

			if(isset($_POST['fileaction']) && $_POST['fileaction'] == 'comment' && $this->CheckActive('Kommentare'))
			{
				$error = false;

    		if ($this->_anti_spam == 1) {
          if(empty($_POST['scode']) || $_POST['scode'] != $_SESSION['DLSecureCode']) {
          	$error = true;
          	$GLOBALS['tmpl']->assign('CodeCheck', 'False');
            if(function_exists("imagettftext") && function_exists("imagejpeg")) {
            	$im = $this->secureCode();
            	$sql_sc = $GLOBALS['db']->Query("SELECT Code FROM " . PREFIX . "_antispam WHERE id = '$im'");
            	$row_sc = $sql_sc->fetchrow();
            	$GLOBALS['tmpl']->assign("im", $im);
            	$_SESSION['DLSecureCode'] = $row_sc->Code;
        			$GLOBALS['tmpl']->assign("anti_spam", 1);
            }
          }
    		}

				if(empty($_POST['Titel']))
				{
					$error = true;
					$GLOBALS['tmpl']->assign('NoTitle', 1);
				}

				if(empty($_POST['Kommentar']))
				{
					$error = true;
					$GLOBALS['tmpl']->assign('NoComment', 1);
				}

				if(empty($_POST['Name']))
				{
					$error = true;
					$GLOBALS['tmpl']->assign('NoName', 1);
				}

				if(empty($_POST['Email']) || (@!ereg("^[ -._A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,4})$", $_POST['Email'])) )
				{
					$error = true;
					$GLOBALS['tmpl']->assign('NoEmail', 1);
				}

				if(!$this->noSpam())
				{
					$error = true;
					$GLOBALS['tmpl']->assign('Spam', 1);
				}

				if($error==false)
				{
					// EINTRAG
					$q = "INSERT INTO " . PREFIX . "_modul_download_comments
					(
						Id,
						FileId,
						Datum,
						Titel,
						Kommentar,
						Name,
						Email,
						Ip,
						Aktiv
					) VALUES (
						'',
						'$file_id',
						'" . time() . "',
						'" . addslashes($_POST['Titel']) . "',
						'" . substr(addslashes($_POST['Kommentar']),0, $this->_maxCommentLength) . "',
						'" . addslashes($_POST['Name']). "',
						'" . addslashes($_POST['Email']) . "',
						'" . $_SERVER['REMOTE_ADDR'] . "',
						'1'
					)";

					$sql = $GLOBALS['db']->Query($q);
					header('Location:' . $this->localRedir() . '#comments');
					exit;
				} else {
				   // Nix
				}
			}

			//=======================================================
			// Aktion: Datei empfehlen (Mail)
			//=======================================================
			if($this->CheckActive('Empfehlen') &&
				isset($_POST['fileaction']) &&
				$_POST['fileaction']=='email' &&
				!empty($_SERVER['REMOTE_ADDR']) &&
				@!empty($_POST['Email']) &&
				@!empty($_POST['myEmail'])&&
				@!empty($_POST['myName']) &&
				@ereg("^[ -._A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,4})$", $_POST['Email']) &&
				@ereg("^[ -._A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,4})$", $_POST['myEmail'])
			)
			{
				$File_URL =  $this->localRedir();

				$globals = new Globals;
				$SystemMail = $GLOBALS['globals']->cp_settings('Mail_Absender');
				$SystemMailName = $GLOBALS['globals']->cp_settings('Seiten_Name');
				$MailText = $GLOBALS['mod']['config_vars']['Recommend_Text'];
				$MailText = str_replace("%N%", "\n", $MailText);
				$MailText = str_replace('%EMAIL%', $_POST['myEmail'], $MailText);
				$MailText = str_replace('%BENUTZER%', substr($_POST['myName'],0,25), $MailText);
				$MailText = str_replace('%URL%', $File_URL, $MailText);
				$GLOBALS['globals']->cp_mail($_POST['Email'], $MailText, $GLOBALS['mod']['config_vars']['Recommend_Subject'] , $SystemMail, $SystemMailName, 'text', '');
				$_REQUEST['recommenOK'] = 1;

			}

			//=======================================================
			// Aktion: Datei bewerten
			//=======================================================
			if($this->CheckActive('Bewerten') && isset($_POST['fileaction']) && $_POST['fileaction']=='voting' && !empty($_SERVER['REMOTE_ADDR']))
			{
				$sql_v = $GLOBALS['db']->Query("SELECT Wertungen_ja,Wertungen_ip FROM " . PREFIX . "_modul_download_files WHERE Id = '$file_id'");
				$row_v = $sql_v->fetchrow();
				$Voting_Ips = @explode(',', $row_v->Wertungen_ip);

				//=======================================================
				// Nur Bewertung annehmen, wenn IP nicht gespeichert
				//=======================================================
				if(!in_array($_SERVER['REMOTE_ADDR'], $Voting_Ips) && $row_v->Wertungen_ja==1)
				{
					$UpdateWhat = (isset($_POST['voting']) && $_POST['voting']=='top') ? ' Wertungen_top=Wertungen_top+1 ' : ' Wertungen_flop=Wertungen_flop+1 ';
					$q = "UPDATE " . PREFIX . "_modul_download_files
					SET
						Wertungen_ip = CONCAT(Wertungen_ip, ',', '" . $_SERVER['REMOTE_ADDR'] . "'),
						$UpdateWhat
					WHERE
						Id = '$file_id'";

					$GLOBALS['db']->Query($q);
				}
				header('Location:' . $this->localRedir());
				exit;
			}
			// Ende Aktion: Datei bewerten

			$topNav = $this->getNavigationPath((int)@$_REQUEST['categ'], '', '1', (int)@$_REQUEST['navop']);

			$GLOBALS['tmpl']->assign('NavTop', $topNav);
			$GLOBALS['tmpl']->assign('Categs', $this->displayTree());

			if($row->Methode=='local')
			{
				$fsize = filesize(BASE_DIR . '/modules/download/files/' . $row->Pfad);
				$GLOBALS['tmpl']->assign('filesize', $this->file_size($fsize));
				$GLOBALS['tmpl']->assign('traffic', $this->file_size($row->Downloads*$fsize));
				$GLOBALS['tmpl']->assign('traffic_today', $this->file_size($Downloads_Today*$fsize));
				$GLOBALS['tmpl']->assign('DlTime', $this->_DownloadTime(BASE_DIR . '/modules/download/files/' . $row->Pfad));
			} else {
				$fsize = $row->Groesse;
				$GLOBALS['tmpl']->assign('Pfad', $row->Pfad);
				$GLOBALS['tmpl']->assign('filesize', $this->file_size($fsize,1) );
				$GLOBALS['tmpl']->assign('traffic', $this->file_size($row->Downloads*$fsize,1));
				$GLOBALS['tmpl']->assign('traffic_today', $this->file_size($Downloads_Today*$fsize,1));
				$GLOBALS['tmpl']->assign('DlTime', $this->_DownloadTime('',$row->Groesse*1024));

			}


			//=======================================================
			// Wertung erlaubt?
			//=======================================================
			$sql_v = $GLOBALS['db']->Query("SELECT Kommentar_ja, Wertungen_ja,Wertungen_ip FROM " . PREFIX . "_modul_download_files WHERE Id = '$file_id'");
			$row_v = $sql_v->fetchrow();
			$Voting_Ips = @explode(',', $row_v->Wertungen_ip);
			if($row_v->Wertungen_ja && $this->CheckActive('Bewerten'))
			{
				$GLOBALS['tmpl']->assign('ZeigeWertung', 1);
			}

			if(!in_array($_SERVER['REMOTE_ADDR'], $Voting_Ips))
			{
				$GLOBALS['tmpl']->assign('DarfWerten', 1);
			}

			//=======================================================
			// Empfehlen erlaubt?
			//=======================================================
			if($this->CheckActive('Empfehlen'))
			{
				$GLOBALS['tmpl']->assign('Empfehlen', 1);
			}

			//=======================================================
			// Kommentar erlaubt?
			//=======================================================
			if($this->CheckActive('Kommentare') && $row_v->Kommentar_ja=='1')
			{
				$GLOBALS['tmpl']->assign('Kommentare', 1);
				$Comments = array();
				$sql_k = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_download_comments WHERE FileId = '$file_id' ORDER BY Id DESC");
				while($row_k = $sql_k->fetchrow())
				{
					$row_k->Kommentar = strip_tags($row_k->Kommentar,'<b><em><u><s>');
					$row_k->Kommentar = htmlspecialchars(stripslashes($row_k->Kommentar));
					$row_k->Kommentar = $this->cutLongWords($row_k->Kommentar);
					$row_k->Kommentar = nl2br($row_k->Kommentar);

					array_push($Comments, $row_k);
				}
				$GLOBALS['tmpl']->assign('Comments', $Comments);
			}

			//=======================================================
			// Sprachen
			//=======================================================
			if($row->Sprache != '')
			{
				$Sprachen = explode(',', $row->Sprache);
				foreach($Sprachen as $Sprache)
				{
					$sql_sp = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_download_sprachen WHERE Id = '{$Sprache}'");
					$row_sp = $sql_sp->fetchrow();
					if(is_object($row_sp)) $DL_Sprachen[] = $row_sp->Name;
				}
			}
			//=======================================================
			// OS
			//=======================================================
			if($row->Os != '')
			{
				$Oss = explode(',', $row->Os);
				foreach($Oss as $Os)
				{
					$sql_sp = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_download_os WHERE Id = '{$Os}'");
					$row_sp = $sql_sp->fetchrow();
					$DL_Os[] = $row_sp->Name;
				}
			}

			if ($row->Excl_Pay==1) {
				if ($row->Excl_Chk==1) {
					$get_link='get_denied';
				} else {
					if (!$_SESSION["cp_benutzerid"]){
					  $get_link='toreg';
					} else {
						$sum = $this->checkPay($file_id,$_SESSION["cp_benutzerid"]);
						$diff = 0 + $row->Pay - $sum;
						if ($row->Pay <= 0) {
							$get_link='get_file';
						} else {
							if ($diff<=0) {
							  $get_link='get_file';
							} else {
								$get_link="get_nouserpay_file&amp;diff={$diff}&amp;val={$row->Pay_val}";
							}
						}
					}
				}
			} else {
				if ($row->Pay <= 0) {
					if ($row->Only_Pay==0) {
						$get_link='get_file';
					} else {
						if (!$_SESSION["cp_benutzerid"]){
						  $get_link='toreg';
						} else {
							if ($row->Pay_Type==0) {
								$sum = $this->checkPay($file_id,$_SESSION["cp_benutzerid"]);
								if ($sum>0) {
									$get_link='get_file';
								} else {
									$get_link='get_notmine_file';
								}
							} else {
								$get_link='get_file';
							}
						}
					}
				} else {
					if (!$_SESSION["cp_benutzerid"]){
					  $get_link='toreg';
					} else {
						if ($row->Pay_Type==0) {
								$get_link='get_nopay_file';
						} else {
							$sum = $this->checkPay($file_id,$_SESSION["cp_benutzerid"]);
							$diff = 0 + $row->Pay - $sum;
							if ($diff<=0) {
							  $get_link='get_file';
							} else {
								$get_link="get_nouserpay_file&amp;diff={$diff}&amp;val={$row->Pay_val}";
							}
						}
					}
				}
			}
			$GLOBALS['tmpl']->assign('DownloadLink',  $this->DL_Rewrite("index.php?module=download&amp;action={$get_link}&amp;file_id={$file_id}&amp;pop=1&amp;cp_theme=" . T_PATH));

			if($row->Pay > 0) {
				if (!$_SESSION["cp_benutzerid"]){
					$GLOBALS['tmpl']->assign('PayLinkText', "оплатить <span style=\"font-size: 70%; color: red\">(не зарегистрирован!)</span>");
					$GLOBALS['tmpl']->assign('PayLink',  "javascript:void(0);\" onClick=\"window.open('".$this->DL_Rewrite("index.php?module=download&amp;action=toreg&amp;file_id={$file_id}&amp;pop=1&amp;cp_theme=" . T_PATH)."','DlPop','width=600,height=300,top=0,left=0,location=no,scrollbars=1');");
				} else {
					if ($row->Excl_Pay==1 && $row->Excl_Chk==1) {
						$GLOBALS['tmpl']->assign('PayLinkText', "оплатить <span style=\"font-size: 70%; color: red\">(заблокировано!)</span>");
						$GLOBALS['tmpl']->assign('PayLink',  "javascript:void(0);\" onClick=\"window.open('".$this->DL_Rewrite("index.php?module=download&amp;action=get_denied&amp;file_id={$file_id}&amp;pop=1&amp;cp_theme=" . T_PATH)."','DlPop','width=600,height=300,top=0,left=0,location=no,scrollbars=1');");
					} else {
						$GLOBALS['tmpl']->assign('PayLinkText', "оплатить");
						$GLOBALS['tmpl']->assign('PayLink',  $this->DL_Rewrite("index.php?module=download&amp;action=pay&amp;file_id={$file_id}&amp;pop=0&amp;cp_theme=" . T_PATH));
					}
				}
			} else {
					$GLOBALS['tmpl']->assign('PayLinkText', "");
					$GLOBALS['tmpl']->assign('PayLink',  "");
			}

			$GLOBALS['tmpl']->assign('dl_images', 'templates/' . T_PATH . '/modules/download/');
			$GLOBALS['tmpl']->assign('downloads', $row->Downloads);
			$GLOBALS['tmpl']->assign('row', $row);
			$GLOBALS['tmpl']->assign('Sprachen', $DL_Sprachen);
			if(isset($DL_Os)) $GLOBALS['tmpl']->assign('Os', $DL_Os);
			$GLOBALS['tmpl']->assign('Beschreibung', $row->Beschreibung);
			$GLOBALS['tmpl']->assign('DLCategs', $this->getCategs());
			$GLOBALS['tmpl']->assign('SearchPanel', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'search_simple.tpl'));
			$GLOBALS['tmpl']->assign('Tellform', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'tellform.tpl'));
			$GLOBALS['tmpl']->assign('UserVote', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'uservote.tpl'));
			$GLOBALS['tmpl']->assign('UserComments', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'usercomments.tpl'));
			$error = false;
		}

		$wm_script="";
		if ($wm_status=='s') {
			$wm_script="window.open('".$this->DL_Rewrite("index.php?module=download&amp;action={$get_link}&amp;file_id={$file_id}&amp;pop=1&amp;cp_theme=" . T_PATH)."','DlPop','width=600,height=300,top=0,left=0,location=no,scrollbars=1');";
		} elseif ($wm_status=='f') {
			$wm_script="alert('Процесс оплаты закончился неудачно');";
		}
		$GLOBALS['tmpl']->assign('wm_script', $wm_script);

		define('MODULE_CONTENT', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'showfile.tpl'));
		$pName = (isset($_GET['categ']) && $_GET['categ'] != '' && $Perm==true) ? $this->prettyChars(strip_tags($topNav)) : $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['PageOverview'];
		define("MODULE_SITE", $pName);
	}

  function _sec_format($seconds)
	{
	$units = array("день|дня|дней" => 86400, "час|часа|часов" => 3600, "минута|минуты|минут" =>60, "секунда|секунды|секунд" => 1);
	if($seconds < 1)
	{
		return "< 1 секунды";
	} else {
		$show = FALSE;
		$ausgabe = "";

		foreach($units as $key=>$value)
		{
			$t = round($seconds/$value);
			$seconds = round($seconds);
			$seconds = $seconds%$value;
			#echo "$seconds<br>";
			list($s, $pl, $mn) = explode("|", $key);
			if($t > 0 || $show)
			{
				if (($t > 4) && ($t < 21)) {
					$ausgabe .= $t." ".$mn.", ";
			  } elseif (substr($t, -1) == 1){
					$ausgabe .= $t." ".$s.", ";
				} elseif(substr($t, -1) <5) {
					$ausgabe .= $t." ".$pl.", ";
				} else {
					$ausgabe .= $t." ".$mn.", ";
				}
			$show = TRUE;
			}

		}
		$ausgabe = substr($ausgabe, 0, strlen($ausgabe)-2);
		return $ausgabe;
		}
	}

	function _DownloadTime($file,$fsize='')
	{
		$values = array(
			"DSL20000" => 20000,
			"DSL16000" => 16000,
			"DSL10000" => 10000,
			"DSL6000" => 6164,
			"DSL5000" => 5000,
			"DSL3000" => 3072,
			"DSL2000" => 2048,
			"DSL1000" => 1024,
			"ISDN2" => 128,
			"ISDN" => 64,
			"Modem" => 56.6,
			"Modem " => 28.8);
		$size = ($fsize=='') ?  filesize($file) : $fsize;
		$ausgabe = "";
		$size *= 8;
		foreach($values as $key=>$value)
		{
			$time = "";
			$time = $this->_sec_format(round($size)/($value*1024));
			$ausgabe .= " <b>".$key."</b> (".$value." kbps) ".$time."<br>";
		}
		return $ausgabe;
	 }

	function checkPay($Id,$User_Id) {
		$sum=0;
		$sql = $GLOBALS['db']->Query("SELECT SUM(PayAmount) AS Pay FROM " . PREFIX . "_modul_download_payhistory WHERE File_Id = '{$Id}' AND User_Id = '{$User_Id}'");
		if ($row = $sql->fetchrow()) {
			$sum = $row->Pay;
		}
		return $sum;
	}

	//=======================================================
	// Download holen
	//=======================================================
	function getFile($file_id)
	{
		$file_id = (!is_numeric($file_id)) ? 0 : $file_id;
		$sql = $GLOBALS['db']->Query("SELECT
			a.*,
			b.Gruppen,
			b.Id
		FROM
			" . PREFIX . "_modul_download_files  as a,
			" . PREFIX . "_modul_download_kat  as b
		WHERE
			a.Id = '{$file_id}' AND
			b.Id = a.KatId AND Aktiv = 1");

		$row = $sql->fetchrow();
		if(is_object($row))
		{
			$g_array = explode('|', $row->Gruppen);
			$GLOBALS['tmpl']->assign('pname', strip_tags($row->Name));
		}
		$group_ids = UGROUP;

		$error = false;
		//=======================================================
		//Пользователь не имеет доступа
		//=======================================================
		if (!@in_array($group_ids, $g_array))
		{
			$error = true;
			$GLOBALS['tmpl']->assign('ErrorType', 'NoPerm');
		}

		//=======================================================
		// Файл не был найден
		//=======================================================
		if(!is_object($row) || ($row->Methode=='local' && !file_exists(BASE_DIR . '/modules/download/files/' . $row->Pfad) || empty($row->Pfad)) )
		{
			$error = true;
			$GLOBALS['tmpl']->assign('ErrorType', 'FileNotFound');
		}

		$sql_lim = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_download_log WHERE FileId = '{$file_id}' AND Datum = '" . date("d.m.Y") . "'");
		$Downloads_All = $sql_lim->numrows();

		if(($Downloads_All >= @$row->Downloads_Max) && (@$row->Downloads_Max!=0))
		{
			$error = true;
			$GLOBALS['tmpl']->assign('ErrorType', 'MaxDownloadsDayReached');
		}

		$sum = $this->checkPay($file_id,$_SESSION["cp_benutzerid"]);
		if (@$row->Excl_Pay == 1) {
			if (@$row->Excl_Chk == 1) {
				$error = true;
				$GLOBALS['tmpl']->assign('ErrorType', 'ExclDenied');
			} elseif ($sum!=@$row->Pay && @$row->Pay > 0){
				$error = true;
				$GLOBALS['tmpl']->assign('ErrorType', 'NoPayUser');
			}
		} else {
			if (@$row->Pay > 0) {
				if(@$row->Pay_Type == 0)
				{
					$error = true;
					$GLOBALS['tmpl']->assign('ErrorType', 'NoPayUser');
				} elseif(@$row->Pay_Type == 1 && $sum!=@$row->Pay && @$row->Pay>0) {
					$error = true;
					$GLOBALS['tmpl']->assign('ErrorType', 'NoPayUser');
				}
			} else {
				if(@$row->Pay_Type == 0 && @$row->Only_Pay == 1 && $sum==0) {
					$error = true;
					$GLOBALS['tmpl']->assign('ErrorType', 'NoPayUser');
				}
			}
		}

		if ($error==true)
		{
			define("MODULE_CONTENT", $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'popup.tpl'));
			$pName = (isset($_GET['categ']) && $_GET['categ'] != '') ? $this->prettyChars(strip_tags($topNav)) : $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['PageOverview'];
			define("MODULE_SITE", $pName);
		}
		else
		{
			if($row->Methode=='local')
			{
				$fsize = filesize(BASE_DIR . '/modules/download/files/' . $row->Pfad);
				$GLOBALS['tmpl']->assign('filesize', $this->file_size($fsize));
				$GLOBALS['tmpl']->assign('traffic', $this->file_size($row->Downloads*$fsize));
			} else {
				$fsize = $row->Groesse;
				$GLOBALS['tmpl']->assign('Pfad', $row->Pfad);
				$GLOBALS['tmpl']->assign('wait', $this->_waitTime);
				$GLOBALS['tmpl']->assign('filesize', $this->file_size($fsize,1) );
				$GLOBALS['tmpl']->assign('traffic', $this->file_size($row->Downloads*$fsize,1));
			}

			$GLOBALS['tmpl']->assign('wait', $this->_waitTime);
			$GLOBALS['tmpl']->assign('downloads', $row->Downloads);

			if($row->Mirrors != '')
			{
				$M = explode("\n", $row->Mirrors);
				foreach($M as $Mirror)
				{
					$File_Mirrors[] = $Mirror;
				}
				$GLOBALS['tmpl']->assign('FileMirros',$File_Mirrors);
			}

			define('MODULE_CONTENT', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'popup.tpl'));
			$pName = (isset($_GET['categ']) && $_GET['categ'] != '') ? $this->prettyChars(strip_tags($topNav)) : $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['PageOverview'];
			define("MODULE_SITE", $pName);

			#$_REQUEST['sub_action'] = 'getfile';

			if(isset($_REQUEST['sub_action']) && $_REQUEST['sub_action'] != '')
			{
				switch($_REQUEST['sub_action'])
				{
					case 'getfile':
						switch($row->Methode)
						{
							case 'local':
								sleep($this->_waitTime-1);
								@ob_start();
								$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_download_files SET Downloads=Downloads+1 WHERE Id = '{$file_id}'");
								$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_download_log (Id,FileId,Datum,Ip) VALUES ('','{$file_id}','" . date("d.m.Y") . "','" . $_SERVER['REMOTE_ADDR']. "')");
								if(!file_exists(BASE_DIR . '/modules/download/files/' . $row->Pfad) || empty($row->Pfad) )
								{
									echo "Datei nicht gefunden...";
									exit;
								}
								@ob_end_flush();
								@ob_end_clean();
								header("Pragma: public");
								header("Expires: 0");
								header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
								header("Cache-Control: private",false);
								header("Content-Type: application/octet-stream");
								header("Content-Disposition: attachment; filename=$row->Pfad");
								header("Content-Transfer-Encoding: binary");
								header("Content-Length: ".@filesize(BASE_DIR . '/modules/download/files/' . $row->Pfad));
								@set_time_limit(0);
								@$this->readFile(BASE_DIR . '/modules/download/files/' . $row->Pfad);
								$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_download_files SET Excl_Chk=1 WHERE Id = '{$file_id}' AND Excl_Pay = 1");
								exit;
							break;

							case 'ftp':
							case 'http':
								$sql = $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_download_files SET Downloads=Downloads+1 WHERE Id = '{$file_id}'");
								$GLOBALS['tmpl']->assign('Pfad', $row->Pfad);
								$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_download_files SET Excl_Chk=1 WHERE Id = '{$file_id}' AND Excl_Pay = 1");
							break;
						}
					break;
				}
			}
		}
	}

	//=======================================================
	// Downloads anzeigen
	//=======================================================
	function getPageNum($repliesCount, $limit)
	{
		if (($repliesCount % $limit) == 0) return $repliesCount / $limit;
		return ($repliesCount + ($limit - ($repliesCount % $limit))) / $limit;
	}

	//=======================================================
	// Dateien auslesen - Es muss unterschieden werden, ob
	// neueste oder beliebteste Dateien ausgelesen werden sollen.
	// Diese Funktion dient auch der Ausgabe von Dateien in
	// einer bestimmten Kategorie
	//=======================================================
	function listFiles($new_best, $limit='')
	{
		$this->secureRequest();

		if(isset($_REQUEST['categ']) && !empty($_REQUEST['categ']) && is_numeric($_REQUEST['categ']) && $_REQUEST['categ']>0)
		{
			$s_categ = ' AND a.KatId = '. $_REQUEST['categ'];
			$limit = (isset($_REQUEST['pp']) && is_numeric($_REQUEST['pp']) && $_REQUEST['pp'] > 0 ) ? $_REQUEST['pp'] : $this->_overview_limit;
			$limit = (isset($_REQUEST['print']) && $_REQUEST['print']==1) ? 10000 : $limit;
		} else {
			$s_categ = '';
		}


		$oBy = ($new_best=='new') ? 'ORDER BY a.Id DESC' : 'ORDER BY a.Downloads DESC';
		$allowed = UGROUP;

		//=======================================================
		// Wenn Kategorie angefordert, muss Navigation
		// initialisiert werden. Es gilt nun nicht mehr die
		// Regel, ob neu oder beliebt
		//=======================================================
		if(isset($_REQUEST['categ']) && !empty($_REQUEST['categ']) && is_numeric($_REQUEST['categ']) && $_REQUEST['categ']>0)
		{
			$GLOBALS['tmpl']->assign('LinkNameSort', (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 'name_asc') ? $this->DL_Rewrite("index.php?module=download&amp;categ=$_REQUEST[categ]&amp;parent=$_REQUEST[parent]&amp;navop=$_REQUEST[navop]&amp;c=$_REQUEST[c]&amp;page=$_REQUEST[page]&amp;orderby=name_desc") : $this->DL_Rewrite("index.php?module=download&amp;categ=$_REQUEST[categ]&amp;parent=$_REQUEST[parent]&amp;navop=$_REQUEST[navop]&amp;c=$_REQUEST[c]&amp;page=$_REQUEST[page]&amp;orderby=name_asc"));
			$GLOBALS['tmpl']->assign('LinkDateSort', (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 'date_asc') ? $this->DL_Rewrite("index.php?module=download&amp;categ=$_REQUEST[categ]&amp;parent=$_REQUEST[parent]&amp;navop=$_REQUEST[navop]&amp;c=$_REQUEST[c]&amp;page=$_REQUEST[page]&amp;orderby=date_desc") : $this->DL_Rewrite("index.php?module=download&amp;categ=$_REQUEST[categ]&amp;parent=$_REQUEST[parent]&amp;navop=$_REQUEST[navop]&amp;c=$_REQUEST[c]&amp;page=$_REQUEST[page]&amp;orderby=date_asc"));
			$GLOBALS['tmpl']->assign('LinkChangedSort', (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 'changed_asc') ? $this->DL_Rewrite("index.php?module=download&amp;categ=$_REQUEST[categ]&amp;parent=$_REQUEST[parent]&amp;navop=$_REQUEST[navop]&amp;c=$_REQUEST[c]&amp;page=$_REQUEST[page]&amp;orderby=changed_desc") : $this->DL_Rewrite("index.php?module=download&amp;categ=$_REQUEST[categ]&amp;parent=$_REQUEST[parent]&amp;navop=$_REQUEST[navop]&amp;c=$_REQUEST[c]&amp;page=$_REQUEST[page]&amp;orderby=changed_asc"));
			$GLOBALS['tmpl']->assign('LinkDownloadSort', (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 'download_asc') ? $this->DL_Rewrite("index.php?module=download&amp;categ=$_REQUEST[categ]&amp;parent=$_REQUEST[parent]&amp;navop=$_REQUEST[navop]&amp;c=$_REQUEST[c]&amp;page=$_REQUEST[page]&amp;orderby=download_desc") : $this->DL_Rewrite("index.php?module=download&amp;categ=$_REQUEST[categ]&amp;parent=$_REQUEST[parent]&amp;navop=$_REQUEST[navop]&amp;c=$_REQUEST[c]&amp;page=$_REQUEST[page]&amp;orderby=download_asc"));

			$nav_link = '';
			if(isset($_REQUEST['orderby']) && $_REQUEST['orderby'] != '')
			{
				switch($_REQUEST['orderby'])
				{
					case 'name_asc':
						$oBy = ' ORDER BY a.Name ASC';
						$nav_link = '&amp;orderby=name_asc';
						$GLOBALS['tmpl']->assign('nImg', '<img hspace="5" border="0" src="templates/'. T_PATH.'/modules/download/sortasc.gif" alt="" />');
					break;

					case 'name_desc':
						$oBy = ' ORDER BY a.Name DESC';
						$nav_link = '&amp;orderby=name_desc';
						$GLOBALS['tmpl']->assign('nImg', '<img hspace="5" border="0" src="templates/'. T_PATH.'/modules/download/sortdesc.gif" alt="" />');
					break;

					case 'date_asc':
						$oBy = ' ORDER BY a.Datum ASC';
						$nav_link = '&amp;orderby=date_asc';
						$GLOBALS['tmpl']->assign('dImg', '<img hspace="5" border="0" src="templates/'. T_PATH.'/modules/download/sortasc.gif" alt="" />');
					break;

					case 'date_desc':
						$oBy = ' ORDER BY a.Datum DESC';
						$nav_link = '&amp;orderby=date_desc';
						$GLOBALS['tmpl']->assign('dImg', '<img hspace="5" border="0" src="templates/'. T_PATH.'/modules/download/sortdesc.gif" alt="" />');
					break;

					case 'changed_asc':
						$oBy = ' ORDER BY a.Geaendert ASC';
						$nav_link = '&amp;orderby=changed_asc';
						$GLOBALS['tmpl']->assign('cImg', '<img hspace="5" border="0" src="templates/'. T_PATH.'/modules/download/sortasc.gif" alt="" />');
					break;

					case 'changed_desc':
						$oBy = ' ORDER BY a.Geaendert DESC';
						$nav_link = '&amp;orderby=changed_desc';
						$GLOBALS['tmpl']->assign('cImg', '<img hspace="5" border="0" src="templates/'. T_PATH.'/modules/download/sortdesc.gif" alt="" />');
					break;

					case 'download_asc':
						$oBy = ' ORDER BY a.Downloads ASC';
						$nav_link = '&amp;orderby=download_asc';
						$GLOBALS['tmpl']->assign('doImg', '<img hspace="5" border="0" src="templates/'. T_PATH.'/modules/download/sortasc.gif" alt="" />');
					break;

					case 'download_desc':
						$oBy = ' ORDER BY a.Downloads DESC';
						$nav_link = '&amp;orderby=download_desc';
						$GLOBALS['tmpl']->assign('doImg', '<img hspace="5" border="0" src="templates/'. T_PATH.'/modules/download/sortdesc.gif" alt="" />');
					break;
				}

			}

			$query = "SELECT
				 a.Id
			FROM
				" . PREFIX . "_modul_download_files as a,
				" . PREFIX . "_modul_download_kat as b

			WHERE
				a.Aktiv=1 AND
				a.KatId=b.Id AND
				(
					b.Gruppen like '{$allowed}|%' OR
					b.Gruppen like '%|{$allowed}' OR
					b.Gruppen like '%|{$allowed}|%' OR
					b.Gruppen = '{$allowed}'
				)
			$s_categ
			";
			$sql = $GLOBALS['db']->Query($query);
			$num = $sql->numrows();

			if(!isset($page)) $page = 1;
			$seiten = $this->getPageNum($num, $limit);
			$a = prepage() * $limit - $limit;
			//$a = (isset($_REQUEST['print']) && $_REQUEST['print']==1) ? 0 : 0;
		}

		$f_limit = ($limit=='') ? 'LIMIT  ' . $this->_newestLimit : " LIMIT $a,$limit";

		$query = "SELECT
			 a.Id,
			 a.KatId,
			 a.Downloads,
			 a.Datum,
			 a.Geaendert,
			 a.Name,
			 a.Wertung,
			 a.Aktiv,
			 b.Gruppen,
			 b.Elter,
			 b.KatName
		FROM
			" . PREFIX . "_modul_download_files as a,
			" . PREFIX . "_modul_download_kat as b

		WHERE
			a.Aktiv=1 AND
			a.KatId=b.Id AND
			(
				b.Gruppen like '{$allowed}|%' OR
				b.Gruppen like '%|{$allowed}' OR
				b.Gruppen like '%|{$allowed}|%' OR
				b.Gruppen = '{$allowed}'
			)
		$s_categ
		$oBy
		$f_limit";

		if(isset($num) && ($limit < $num) && isset($_REQUEST['categ']) && !empty($_REQUEST['categ']) && is_numeric($_REQUEST['categ']) && $_REQUEST['categ']>0)
		{
			$GLOBALS['tmpl']->assign('pages',
				pagenav($seiten,
				(isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) ? $_REQUEST['page'] : 1),
				" <a class=\"page_navigation\" href=\"" . $this->DL_Rewrite("index.php?module=download&amp;categ=$_REQUEST[categ]&amp;parent=$_REQUEST[parent]&amp;navop=$_REQUEST[navop]&amp;c=$_REQUEST[c]&amp;page={s}{$nav_link}") . "\">{t}</a> ")
				);
		}


		$items = array();
		$sql = $GLOBALS['db']->Query($query);
		while($row = $sql->fetchrow())
		{
			$row->KatLink = "index.php?module=download&amp;categ=$row->KatId&amp;parent=$row->Elter&amp;navop=" . (($row->Elter==0) ? $row->Id : $this->getParentDownloadCateg($row->Elter)) . "&amp;c=" . $this->replaceWild($row->KatName);
			$row->KatLink = $this->DL_Rewrite($row->KatLink);
			$row->KatName = $this->getCategoryName($row->KatId);
			$row->Link = $this->DL_Rewrite("index.php?module=download&amp;action=showfile&amp;file_id={$row->Id}&amp;categ=$row->KatId");
			// $row->Link = $this->DL_Rewrite("index.php?module=download&amp;action=get_file&amp;file_id={$row->Id}&amp;pop=1&amp;cp_theme=" . T_PATH);
			array_push($items, $row);
		}

		return $items;
	}

	function getCategoryName($id)
	{
		$sql = $GLOBALS['db']->Query("SELECT KatName FROM " . PREFIX . "_modul_download_kat WHERE Id = '{$id}'");
		$row = $sql->fetchrow();
		return $row->KatName;
	}

	//=======================================================
	// Downloadьbersicht
	//=======================================================
	function overView()
	{
		$topNav = $this->getNavigationPath((int)@$_REQUEST['categ'], '', '1', (int)@$_REQUEST['navop']);

		$GLOBALS['tmpl']->assign('DLCategs', $this->getCategs());
		$GLOBALS['tmpl']->assign('NavTop', $topNav);
		$GLOBALS['tmpl']->assign('Categs', $this->displayTree());
		$GLOBALS['tmpl']->assign('SearchPanel', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'search_simple.tpl'));

		if(isset($_REQUEST['categ']) && !empty($_REQUEST['categ']) && is_numeric($_REQUEST['categ']) && $_REQUEST['categ']>0)
		{
			//
			$GLOBALS['tmpl']->assign('Files', $this->listFiles('new','no'));
			$GLOBALS['tmpl']->assign('TheDownloads', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'downloads.tpl'));
		} else {
			$GLOBALS['tmpl']->assign('NewFiles', $this->listFiles('new'));
			$GLOBALS['tmpl']->assign('BestFiles', $this->listFiles('best'));
			$GLOBALS['tmpl']->assign('NewestDownloads', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'dl_new.tpl'));
			$GLOBALS['tmpl']->assign('TopDownloads', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'dl_top.tpl'));
		}

		$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'overview.tpl');
		define("MODULE_CONTENT", $tpl_out);
		$pName = (isset($_GET['categ']) && $_GET['categ'] != '') ? $this->prettyChars(strip_tags($topNav)) : $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['PageOverview'];
		define("MODULE_SITE", $pName);
	}

	function getCategs()
	{
		$s_categ = '';
		$allowed = UGROUP;
		$categs = array();
		$query = "SELECT
				 b.Id,
				 b.KatName
			FROM
				" . PREFIX . "_modul_download_kat as b
			WHERE
				(
					b.Gruppen like '{$allowed}|%' OR
					b.Gruppen like '%|{$allowed}' OR
					b.Gruppen like '%|{$allowed}|%' OR
					b.Gruppen = '{$allowed}'
				)
			ORDER BY
				KatName ASC
			";
		$sql = $GLOBALS['db']->Query($query);
		while($row = $sql->fetchrow())
		{
			array_push($categs, $row);
		}
		return $categs;
	}

  //функция стыковки с платежными системами, по желанию заказчика пока только WM
	function addMoney($file_id){
    include_once('config.php');
    //Заполняем дескрипшен "за что?"
		$sql_v = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_download_files WHERE Id = '$file_id'");
		$row_v = $sql_v->fetchrow();

		$sum = $this->checkPay($file_id,$_SESSION["cp_benutzerid"]);
		$diff = max(0 + $row_v->Pay - $sum,0);

		//Работа с порядковым номером
		$dat = BASE_DIR . "/modules/Download/data/num.dat";
		$num = 0;

        $num = file_get_contents($dat);



		$GLOBALS['tmpl']->assign('pay_descr', 'Взнос на открытие закачки файла: '.$row_v->Name);
		$GLOBALS['tmpl']->assign('pay_num', $num);
		$GLOBALS['tmpl']->assign('wm_purse', $row_v->Pay_val==0?$wmz_purse:$wmr_purse);
		$GLOBALS['tmpl']->assign('file_id', $row_v->Id);
		$GLOBALS['tmpl']->assign('user_id', $_SESSION["cp_benutzerid"]);
		$GLOBALS['tmpl']->assign('user_IP', $_SERVER['REMOTE_ADDR']);
		$GLOBALS['tmpl']->assign('pay_sum', $row_v->Pay);
		$GLOBALS['tmpl']->assign('pay_type', $row_v->Pay_Type);
		$GLOBALS['tmpl']->assign('pay_val', $row_v->Pay_val==0?"WMZ":"WMR");
		$GLOBALS['tmpl']->assign('diff', $diff);
		$GLOBALS['tmpl']->assign('cat_id', $row_v->KatId);
		$GLOBALS['tmpl']->assign('excl_pay', $row_v->Excl_Pay);
		$GLOBALS['tmpl']->assign('excl_chk', $row_v->Excl_Chk);


        $num++;
        $open = fopen("$dat","w");
        fwrite($open, $num);
        fclose($open);

        define('MODULE_CONTENT', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'pay.tpl'));
		$pName = (isset($_GET['categ']) && $_GET['categ'] != '' && $Perm==true) ? $this->prettyChars(strip_tags($topNav)) : $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['PageOverview'];
		define("MODULE_SITE", $pName);
	}

	function Success_pay(){
/*
		$file_id = (int)@$_REQUEST['pay_file_id'];

		$sql_v = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_download_files WHERE Id = '$file_id'");
		$row_v = $sql_v->fetchrow();

		$GLOBALS['tmpl']->assign('file_name', $row_v->Name);
		$GLOBALS['tmpl']->assign('fs', " выполнен. Спасибо за участие!!!");
		$GLOBALS['tmpl']->assign('back_link', "index.php?module=download&action=showfile&file_id=".$file_id."&categ= ".$_GET['categ']."&r=11177");

		define('MODULE_CONTENT', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'Success.tpl'));
		$pName = (isset($_GET['categ']) && $_GET['categ'] != '' && $Perm==true) ? $this->prettyChars(strip_tags($topNav)) : $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['PageOverview'];
		define("MODULE_SITE", $pName);
*/
		$file_id = (int)@$_REQUEST['pay_fileid'];
		$this->showFile($file_id,'s');
	}

	function Fail_pay(){
/*
		$file_id = (int)@$_REQUEST['pay_file_id'];

		$sql_v = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_download_files WHERE Id = '$file_id'");
		$row_v = $sql_v->fetchrow();

		$GLOBALS['tmpl']->assign('file_name', $row_v->Name);
		$GLOBALS['tmpl']->assign('fs', " не выполнен. ");
		$GLOBALS['tmpl']->assign('back_link', "index.php?module=download&action=showfile&file_id=".$file_id."&categ= ".$_GET['categ']."&r=11177");

		define('MODULE_CONTENT', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'Success.tpl'));
		$pName = (isset($_GET['categ']) && $_GET['categ'] != '' && $Perm==true) ? $this->prettyChars(strip_tags($topNav)) : $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['PageOverview'];
		define("MODULE_SITE", $pName);
*/
		$file_id = (int)@$_REQUEST['pay_fileid'];
		$this->showFile($file_id,'f');
	}

	function getNoPayFile($file_id){

			define('MODULE_CONTENT', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'nopay.tpl'));
			$pName = (isset($_GET['categ']) && $_GET['categ'] != '') ? $this->prettyChars(strip_tags($topNav)) : $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['PageOverview'];
			define("MODULE_SITE", $pName);
	}

	function getNotMineFile($file_id){

			define('MODULE_CONTENT', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'notmine.tpl'));
			$pName = (isset($_GET['categ']) && $_GET['categ'] != '') ? $this->prettyChars(strip_tags($topNav)) : $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['PageOverview'];
			define("MODULE_SITE", $pName);
	}

	function getNoUserPayFile($file_id,$diff,$val){

			$sql = $GLOBALS['db']->Query("SELECT Vorname,Nachname FROM " . PREFIX . "_users WHERE Id = '" .$_SESSION["cp_benutzerid"]. "'");
			$row = $sql->fetchrow();
			if(!empty($row)) {
				$user = substr($row->Vorname,0,1) . "." . $row->Nachname;
			} else {
				$user = 'гость';
			}
			$GLOBALS['tmpl']->assign('user',$user);

			$GLOBALS['tmpl']->assign('diff',$diff);
			if ($val == 0) {
				$GLOBALS['tmpl']->assign('val','$');
			} else {
				$GLOBALS['tmpl']->assign('val','руб');
			}
			define('MODULE_CONTENT', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'nouserpay.tpl'));
			$pName = (isset($_GET['categ']) && $_GET['categ'] != '') ? $this->prettyChars(strip_tags($topNav)) : $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['PageOverview'];
			define("MODULE_SITE", $pName);
	}

	function toReg($file_id){

			define('MODULE_CONTENT', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'toreg.tpl'));
			$pName = (isset($_GET['categ']) && $_GET['categ'] != '') ? $this->prettyChars(strip_tags($topNav)) : $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['PageOverview'];
			define("MODULE_SITE", $pName);
	}

	function getDenied($file_id){

			define('MODULE_CONTENT', $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'denied.tpl'));
			$pName = (isset($_GET['categ']) && $_GET['categ'] != '') ? $this->prettyChars(strip_tags($topNav)) : $GLOBALS['mod']['config_vars']['PageName'] . $GLOBALS['mod']['config_vars']['PageSep'] . $GLOBALS['mod']['config_vars']['PageOverview'];
			define("MODULE_SITE", $pName);
	}

  function secureCode($c=0) {
	  $tdel = time() - 1200;
	  $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_antispam WHERE Ctime < " . $tdel . "");
	  $code = "";
//	$chars = array(2,3,4,5,6,7,8,9,a,b,c,d,e,f,g,h,j,k,l,m,n,p,q,r,s,t,u,v,w,x,y,z);
//  $chars = array(2,3,4,5,6,7,8,9,A,B,C,D,E,F,G,H,J,K,L,M,N,P,Q,R,S,T,U,V,W,X,Y,Z);
	  $chars = array(A,B,C,D,E,F,G,H,J,K,M,N,P,Q,R,S,T,U,V,W,X,Y,Z,2,3,4,5,6,7,8,9,a,b,c,d,e,f,g,h,j,k,m,n,p,q,r,s,t,u,v,w,x,y,z);
//	$chars = array(2,3,4,5,6,7,8,9);
	  $ch = ($c != 0) ? $c : 7;
	  $count = count($chars) - 1;
	  srand((double)microtime() * 1000000);
	  for($i = 0; $i < $ch; $i++) {
		  $code .= $chars[rand(0, $count)];
	  }
	  $sql = $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_antispam (id,Code,Ctime) VALUES ('','" . $code . "','" . time() . "')");
	  $codeid = $GLOBALS['db']->InsertId();

    return $codeid;
  }
}
?>