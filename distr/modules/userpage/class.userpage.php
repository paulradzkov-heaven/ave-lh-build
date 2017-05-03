<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

define("no_avatar", 1);
define("date_format", "d.m.y, H:i");

class userpage {

	var $_commentwords = 1000;
	var $_anti_spam = 1;
	var $_allowed_imagetypes = array('image/pjpeg', 'image/jpeg', 'image/jpg', 'image/x-png', 'image/png', 'image/gif');

	//=======================================================
	// Userpage Ausgabe
	//=======================================================
	function show($tpl_dir,$lang_file,$uid)
	{

		$sql = $GLOBALS['db']->Query("SELECT
			a.*,
			c.Vorname,Nachname,Land,Benutzergruppe,`UserName`,
			d.*
		FROM
			" . PREFIX . "_modul_userpage  as a,
			" . PREFIX . "_users  as c,
			" . PREFIX . "_modul_forum_userprofile  as d

		WHERE
			c.Id = '$uid' AND
			d.BenutzerId = '$uid'

		LIMIT 1");

		# LADE TEMPLATE
		$sql_tpl = $GLOBALS['db']->Query("SELECT tpl FROM " . PREFIX . "_modul_userpage_template WHERE id = '1'");
		$row_tpl = $sql_tpl->fetchrow();


		# LADE BENUTZERDATEN
		$row = $sql->fetchrow();

		if(!is_object($row)) $this->msg($GLOBALS['tmpl']->get_config_vars('ProfileError'),'',$tpl_dir);
		if($row->ZeigeProfil != '1') $this->msg($GLOBALS['tmpl']->get_config_vars('NoPublicProfile'),'',$tpl_dir);
		if(!$this->fperm('userprofile')) $this->msg($GLOBALS['tmpl']->get_config_vars('ErrornoPerm'),'',$tpl_dir);

		$sql_country = $GLOBALS['db']->Query("SELECT LandName FROM " . PREFIX . "_countries WHERE LandCode = '".$row->Land."'");
		$row_country = $sql_country->fetchrow();


		# ERSETZEN
		$tpl = $row_tpl->tpl;
		$tpl = str_replace("[cp:benutzername]", $row->UserName, $tpl);
		$tpl = str_replace("[cp:header-1]","<? userpage_header(\"userpanel_forums.tpl\",".$uid."); ?>", $tpl);
		$tpl = str_replace("[cp:header-2]","<? userpage_header(\"header_sthreads.tpl\",".$uid."); ?>", $tpl);
		$tpl = str_replace("[cp:land]", $row_country->LandName, $tpl);
		$tpl = str_replace("[cp:name]", $row->Vorname ." ". $row->Nachname, $tpl);
		$tpl = str_replace("[cp:registriert]", date(date_format, $row->Registriert), $tpl);
		$tpl = str_replace("[cp:onlinestatus]", userpage_onlinestatus($row->UserName), $tpl);
		$tpl = str_replace("[cp:onlinestatus-1]", userpage_onlinestatus($row->UserName,1), $tpl);
		$tpl = str_replace("[cp:avatar]",  userpage_avatar($row->Benutzergruppe,$row->Avatar,$row->AvatarStandard), $tpl);
		$tpl = preg_replace("/\[cp\:(.*?)-([0-9]*)\]/","<?php userpage_felder(\"\\1\",\"\\2\",".$uid."); ?>", $tpl);
		$tpl = preg_replace("/\[cp\:(.*?)\]/","<?php userpage_felder(\"\\1\",\"1\",".$uid."); ?>", $tpl);
		$tpl = preg_replace("/\[cp_forum\:([0-9]*)\]/","<?php userpage_lastposts(\"\\1\",".$uid."); ?>", $tpl);
		$tpl = preg_replace("/\[cp_downloads\:([0-9]*)\]/","<?php userpage_downloads(\"\\1\",".$uid."); ?>", $tpl);
		$tpl = preg_replace("/\[cp_guestbook\:([0-9]*)\]/","<?php userpage_guestbook(\"\\1\",".$uid."); ?>", $tpl);
		$tpl = preg_replace("/\[cp_feld\:([0-9]*)-([0-9]*)\]/","<?php userpage_getfield(\"\\1\",\"\\2\",".$uid."); ?>", $tpl);
		$tpl = preg_replace("/\[cp_feld\:([0-9]*)\]/","<?php userpage_getfield(\"\\1\",\"1\",".$uid."); ?>", $tpl);
		$tpl = preg_replace("[\[cp_lang:(.*?)\]]",  "<?php userpage_lang(\"\\1\"); ?>", $tpl);



		define("MODULE_SITE", $row->UserName.$GLOBALS['tmpl']->get_config_vars('UPheader'));


		if(!defined("MODULE_CONTENT"))
		{
			define("MODULE_CONTENT", $tpl);
		}

	}

	//=======================================================
	// G�stebucheintrag
	//=======================================================
	function displayForm($tpl_dir,$uid,$cp_theme)
	{
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_userpage WHERE id = '1'");
		$row = $sql->fetchrow();
		$gruppen = explode(',', $row->group_id);

		if($row->can_comment == 1 && in_array(UGROUP, $gruppen))
		{
			$GLOBALS['tmpl']->assign('cancomment', 1);
		}
		$GLOBALS['tmpl']->assign('MaxZeichen', $this->_commentwords);

		$im = "";

		$sql_ig = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_forum_ignorelist WHERE BenutzerId = '$uid' AND IgnoreId = '".$_SESSION['cp_benutzerid']."'");
		$num_ig = $sql_ig->numrows();

		if ($num_ig == 1) $GLOBALS['tmpl']->assign('cancomment', 1);

		// absenden
		if(isset($_POST['send']) && $_POST['send'] == 1)
		{

			$Text = substr(htmlspecialchars($_POST['Text']), 0, $this->_commentwords);
			$Text_length = strlen($Text);
			$Text .= ($Text_length > $this->_commentwords) ? '...' : '';
			$Text = cp_parse_string($Text);

			$error = array();
			if (empty($_POST['Titel'])) $error[] = $GLOBALS['tmpl']->get_config_vars('NoTitle');
			if (empty($Text)) $error[] = $GLOBALS['tmpl']->get_config_vars('NoComment');


			if (function_exists("imagettftext") && function_exists("imagepng") && $this->_anti_spam == 1)
			{
				if(empty($_POST['cpSecurecode']) || $_POST['cpSecurecode'] != $_SESSION['cpSecurecode'])
				{
					$error[] = $GLOBALS['tmpl']->get_config_vars('NoSecure');

				}
			}

			if (count($error)>0)
			{
				$GLOBALS['tmpl']->assign('errors', $error);
				$GLOBALS['tmpl']->assign('titel', $_POST['Titel']);
				$GLOBALS['tmpl']->assign('text', $_POST['Text']);
			}
			else
			{
				if (isset($_SESSION['cp_benutzerid']))  $author = $_SESSION['cp_benutzerid'];
				else $author = '0';

				if(in_array(UGROUP, $gruppen) && $Text_length > 3)
				{

					$sql = "INSERT INTO " . PREFIX . "_modul_userpage_guestbook (
						id,
						uid,
						ctime,
						author,
						title,
						message
					) VALUES (
						'',
						'" . $uid . "',
						'" . time() . "',
						'" . $author . "',
						'" . $_POST['Titel'] . "',
						'" . $Text . "'
					)";
					$GLOBALS['db']->Query($sql);

				}


				echo '<script>window.opener.location.reload(); window.close();</script>';
			}
		}

		if(function_exists("imagettftext") && function_exists("imagepng") && $this->_anti_spam == 1)
		{
			$codeid = secureCode();
			$im = $codeid;
			$sql_sc = $GLOBALS['db']->Query("SELECT Code FROM " . PREFIX . "_antispam WHERE Id = '$codeid'");
			$row_sc = $sql_sc->fetchrow();
			$GLOBALS['tmpl']->assign("im", $im);
			$_SESSION['cpSecurecode'] = $row_sc->Code;
			$_SESSION['cpSecurecode_id'] = $codeid;

			$GLOBALS['tmpl']->assign("anti_spam", 1);
		}

		$GLOBALS['tmpl']->assign('cp_theme', $cp_theme);
		$GLOBALS['tmpl']->assign('uid', $uid);
		$GLOBALS['tmpl']->display($tpl_dir . 'guestbook_form.tpl');
	}

	//=======================================================
	// G�stebucheintr�ge l�schen
	//=======================================================
	function del_guest($tpl_dir,$gid,$uid,$page)
	{
		if(empty($page)) $page = 1;

		if(UGROUP != 1) $this->msg($GLOBALS['tmpl']->get_config_vars('ErrornoPerm'),'index.php?module=userpage&action=show&uid='.$uid.'&page='.$page,$tpl_dir);

		$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_userpage_guestbook WHERE id = '$gid' AND uid = '$uid'");

		$this->msg($GLOBALS['tmpl']->get_config_vars('Delok'),'index.php?module=userpage&action=show&uid='.$uid.'&page='.$page,$tpl_dir);


	}

	//=======================================================
	// Kontakt
	//=======================================================
	function showContact($tpl_dir,$method='',$uid,$cp_theme)
	{
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_forum_userprofile WHERE BenutzerId = '$uid'");
		$row = $sql->fetchrow();

		$sql_ig = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_forum_ignorelist WHERE BenutzerId = '$uid' AND IgnoreId = '".$_SESSION['cp_benutzerid']."'");
		$num_ig = $sql_ig->numrows();

		if ($num_ig == 1) exit();

		switch($method)
		{
			case 'email':
				$GLOBALS['tmpl']->assign('titel', $GLOBALS['tmpl']->get_config_vars('Emailc'));
				if($row->Emailempfang == '1') $GLOBALS['tmpl']->assign('email', 1);
			break;

			case 'icq':
				$GLOBALS['tmpl']->assign('titel', $GLOBALS['tmpl']->get_config_vars('Icq'));
				if($row->Icq != '')  $GLOBALS['tmpl']->assign('wert', "<a href=\"http://www.icq.com/people/about_me.php?uin=$row->Icq\" target=\"_blank\">$row->Icq</a>");
			break;

			case 'aim':
				$GLOBALS['tmpl']->assign('titel', $GLOBALS['tmpl']->get_config_vars('Aim'));
				if($row->Aim != '')  $GLOBALS['tmpl']->assign('wert', $row->Aim);
			break;

			case 'skype':
				$GLOBALS['tmpl']->assign('titel', $GLOBALS['tmpl']->get_config_vars('Skype'));
				if($row->Skype != '')  $GLOBALS['tmpl']->assign('wert', "<a href=\"skype:$row->Skype?call\">$row->Skype</a>");
			break;
		}

		if(isset($_POST['send']) && $_POST['send'] == 1 && $row->Emailempfang == '1' && UGROUP!=2)
		{

			$error = array();
			$GLOBALS['tmpl']->assign('titel', $GLOBALS['tmpl']->get_config_vars('Emailc'));
			if (empty($_POST['Titel'])) $error[] = $GLOBALS['tmpl']->get_config_vars('NoBetreff');
			if (empty($_POST['Text'])) $error[] = $GLOBALS['tmpl']->get_config_vars('NoMessage');


			if (count($error)>0)
			{
				$GLOBALS['tmpl']->assign('errors', $error);
				$GLOBALS['tmpl']->assign('titel', $_POST['Titel']);
				$GLOBALS['tmpl']->assign('text', $_POST['Text']);
			}
			else
			{
				$Absender = $_SESSION['cp_forumemail'];
				$Empfang = $uid;

				$Prefab = $GLOBALS['tmpl']->get_config_vars('EmailBodyUser');
				$Prefab = str_replace('%%USER%%', $row->BenutzerName, $Prefab);
				$Prefab = str_replace('%%ABSENDER%%', $_SESSION['cp_forumname'], $Prefab);
				$Prefab = str_replace('%%BETREFF%%', stripslashes($_POST['Titel']), $Prefab);
				$Prefab = str_replace('%%NACHRICHT%%', stripslashes($_POST['Text']), $Prefab);
				$Prefab = str_replace('%%ID%%', $_SESSION['cp_benutzerid'], $Prefab);
				$Prefab = str_replace('%%N%%', "\n",$Prefab);
				$Prefab = str_replace('','',$Prefab);
				$globals = new Globals;
				$GLOBALS['globals']->cp_mail($row->Email, $Prefab, stripslashes($_POST['Titel']), FORUMEMAIL, FORUMABSENDER, "text", "");
				$this->msg($GLOBALS['tmpl']->get_config_vars('MessageAfterEmail'),"window.close();",$tpl_dir);
			}
		}



		$GLOBALS['tmpl']->assign('cp_theme', $cp_theme);
		$GLOBALS['tmpl']->display($tpl_dir . 'popup.tpl');
	}

	//=======================================================
	// Benutzerdaten �ndern
	//=======================================================
	function changeData($tpl_dir,$lang_file)
	{
		if(!isset($_SESSION['cp_benutzerid']))
		{
			$this->msg($GLOBALS['tmpl']->get_config_vars('ErrornoPerm'),"index.php?module=forums",$tpl_dir);
		}
		$sql = $GLOBALS['db']->Query("
		SELECT
			u.*,
			us.Status,
			us.Benutzergruppe
		FROM
			".PREFIX."_modul_forum_userprofile as u,
			".PREFIX."_users as us
		WHERE
			BenutzerId = '" . addslashes($_SESSION['cp_benutzerid']) . "' AND
			us.Status = 1 AND
			us.Id = u.BenutzerId

		");
		$n = $sql->numrows();


		if(!$n)
		{
			$this->msg($GLOBALS['mod']['config_vars']['ProfileError']);
		}

		$r = $sql->fetcharray();

		if($r['BenutzerNameChanged'] >= '1' && !$this->fperm('changenick')) $GLOBALS['tmpl']->assign('changenick', 'no');
		if(!$this->fperm('changenick')) $GLOBALS['tmpl']->assign('changenick_once', '1');

		//felder
		$felder = array();

		$sql_tpl = $GLOBALS['db']->Query("SELECT tpl FROM ".PREFIX."_modul_userpage_template WHERE id='1'");
		$row_tpl = $sql_tpl->fetchrow();

		$sql_b = $GLOBALS['db']->Query("SELECT * FROM  " . PREFIX . "_modul_userpage_values WHERE uid = '" . addslashes($_SESSION['cp_benutzerid']) . "'");
		$row_b = $sql_b->fetchrow();

		// falls nicht vorhanden
		if($sql_b->numrows() == 0)
		{
			$q = "INSERT INTO " . PREFIX . "_modul_userpage_values (
						id,
						uid
					) VALUES (
					    '',
						'".addslashes($_SESSION['cp_benutzerid'])."'
					)";
					$GLOBALS['db']->Query($q);
		}

		$sql_a = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_userpage_items WHERE active = '1'");
		while($row_a = $sql_a->fetchrow())
		{
			if(strpos($row_tpl->tpl, "[cp_feld:".$row_a->Id) !== false)
			{

				if ($row_a->type == 'dropdown')
				{

					$fid = "f_".$row_a->Id;
					$drop =  explode(",", $row_a->value);

					$row_a->wert .= "<select style=\"width:210px;\" id=\"feld[$row_a->Id]\" name=\"feld[$row_a->Id]\">";

					$count = 0;

					foreach($drop as $i)
					{

						if($row_b->$fid == $count)
						{
						$row_a->wert .= "<option selected=\"\" value=\"".$count."\" >".$i."</option>";
						}
						else
						{
						$row_a->wert .= "<option value=\"".$count."\" >".$i."</option>";
						}

						$count = $count +1;
					}

					$row_a->wert .= "</select>";

				}
				else if ($row_a->type == 'multi')
				{

					$fid = "f_".$row_a->Id;
					$drop =  explode(",", $row_a->value);
					$values = explode(",", $row_b->$fid);

					$row_a->wert .= "<select style=\"width:210px;\" id=\"feld_multi[$row_a->Id][]\" name=\"feld_multi[$row_a->Id][]\" size=\"5\" multiple=\"multiple\">";

					$count = 0;

					foreach($drop as $i)
					{

						if(in_array($count,$values))
						{
						$row_a->wert .= "<option selected=\"\" value=\"".$count."\" >".$i."</option>";
						}
						else
						{
						$row_a->wert .= "<option value=\"".$count."\" >".$i."</option>";
						}

						$count = $count +1;
					}

					$row_a->wert .= "</select>";

				}
				else if ($row_a->type == 'text')
				{

					$fid = "f_".$row_a->Id;

					$row_a->wert = "<input type=\"text\" id=\"feld[$row_a->Id]\" size=\"40\" name=\"feld[$row_a->Id]\" value=\"".$row_b->$fid."\" >";

				}
				else
				{

					$fid = "f_".$row_a->Id;
					$row_a->wert = "<textarea id=\"feld[$row_a->Id]\" name=\"feld[$row_a->Id]\" cols=\"38\" rows=\"5\">".$row_b->$fid."</textarea>";


				}
				array_push($felder, $row_a);

			}


		}
		// definierte Felder
		$def = array("webseite","geburtstag","avatar","geschlecht","interessen","signatur");

		foreach($def as $i)
		{
			if(strpos($row_tpl->tpl, "[cp:".$i) !== false)
			{
				$GLOBALS['tmpl']->assign("show_".$i, 1);
			}
		}


		$r['OwnAvatar'] = $this->getAvatar($r['Benutzergruppe'],$r['Avatar'],$r['AvatarStandard']);
		if(@!is_file(BASE_DIR . '/modules/forums/avatars/' . $r['Avatar'])) $r['Avatar'] = '';

		// avatar
		$avatar = '';
		$own = 1;
		$permown = false;

		// wenn er admin ist, fallen alle regeln weg
		if ($this->fperm('alles') || $this->fperm('own_avatar') || UGROUP == 1)
		{
			$permown = true;
		} else {
			// wenn seine gruppe die rechte besitzt, eigene avatar zu nutzen
			if ($this->fperm('own_avatar'))
			{
				$permown = true;
			}
		}

		if($permown == true)
		{
			$GLOBALS['tmpl']->assign('avatar_upload', 1);
		}

		//doupdate

		if(isset($_POST['doupdate']) && $_POST['doupdate'] == 1)
		{
			$ok = true;
			$errors = "";
			$allowed = array('*','[',']','-','=');
			$muster     = "[^ ._A-Za-z�-��-���0-9-]";
			$muster_geb = "([0-9]{2}).([0-9]{2}).([0-9]{4})";
			$muster_email = "^[-._A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,4})$";

			//=======================================================
			// Benutzername pr�fen
			//=======================================================
			if((isset($_POST['BenutzerName'])) && ($this->checkIfUserName(addslashes($_POST['BenutzerName']),addslashes($_SESSION['cp_forumname']))))
			{
					$errors[] = $GLOBALS['tmpl']->get_config_vars('PE_UsernameInUse');
					$r['BenutzerName'] = trim(htmlspecialchars($_POST['BenutzerName']));
			}

			if(( @isset($_POST['BenutzerName']) && @empty($_POST['BenutzerName'])) || ereg($muster, str_replace($allowed,'',@$_POST['BenutzerName']) ))
			{
				$errors[] = $GLOBALS['tmpl']->get_config_vars('PE_Username');
			}

			//=======================================================
			// E-Mail pr�fen
			//=======================================================
			if(!empty($_POST['Email']) && $this->checkIfUserEmail($_POST['Email'], $_SESSION['cp_forumemail']))
			{
				$errors[] = $GLOBALS['tmpl']->get_config_vars('PE_EmailInUse');
			}

			if(empty($_POST['Email']) || !ereg($muster_email, $_POST['Email']))
			{
				$errors[] = $GLOBALS['tmpl']->get_config_vars('PE_Email');
			}

			//=======================================================
			// WENN GEBURTSTAG IM FALSCHEN FORMAT
			//=======================================================
			if(!empty($_POST['GeburtsTag']) && !ereg($muster_geb, $_POST['GeburtsTag']))
			{
				$errors[] = $GLOBALS['tmpl']->get_config_vars('PE_WrongBd');
			}

			if(!empty($_POST['GeburtsTag']))
			{
				$check_year = explode(".", $_POST['GeburtsTag']);
				if(@$check_year[0] > 31 || @$check_year[1] > 12 || @$check_year[2] < date("Y")-75)
				{
					$errors[] = $GLOBALS['tmpl']->get_config_vars('PE_WrongBd');
				}
			}

			//=======================================================
			// Avatar
			//=======================================================
			if(isset($_POST['SystemAvatar']) && $_POST['SystemAvatar']!='')
			{
				$avatar = ",Avatar  = 'various/" . $_POST['SystemAvatar'] . "'";
			}

			if($this->fperm('own_avatar'))
			{
				$target = BASE_DIR . '/modules/forums/avatars/';
				if(in_array($_FILES['file']['type'], $this->_allowed_imagetypes))
				{
					$filesize = @filesize($_FILES['file']['tmp_name']);
					$file_wh = @GetImageSize($_FILES['file']['tmp_name']);
					$file_wh_w = $file_wh[0];
					$file_wh_h = $file_wh[1];


					$fupload_name = @trim($_FILES['file']['name'],1);
					$fupload_name = @$this->rand_tostring($target ,$fupload_name);


					if( (($file_wh_w <= MAX_AVATAR_WIDTH) && ($file_wh_h <= MAX_AVATAR_HEIGHT) && ($filesize <= MAX_AVATAR_BYTES) && (@$_REQUEST['delav']!=1)) || UGROUP == 1 )
					{

						@move_uploaded_file($_FILES['file']['tmp_name'], $target . $fupload_name);
						@chmod($target . $fupload_name,0777);

						$avatar = ",Avatar  = '$fupload_name'";

						#$sql_old = $GLOBALS['db']->Query("SELECT Avatar FROM " . PREFIX . "_modul_forum_userprofile WHERE BenutzerId='" . UID . "'");
						#$row_old = $sql_old->fetchrow();
						#@unlink($target . $row_old->Avatar);
						#$avatar .= "Avatar ='$fupload_name',";

					}
				}
			}

			@$r['BenutzerName'] = trim(htmlspecialchars($_POST['BenutzerName']));
			$r['ZeigeProfil'] = @$_POST['ZeigeProfil'];
			$r['Unsichtbar'] = @$_POST['Unsichtbar'];
			$r['Emailempfang'] = @$_POST['Emailempfang'];
			$r['ZeigeProfil'] = @$_POST['ZeigeProfil'];
			$r['Email'] = trim(@$_POST['Email']);
			$r['Icq'] = trim(htmlspecialchars(@$_POST['Icq']));
			$r['Aim'] = trim(htmlspecialchars(@$_POST['Aim']));
			$r['Skype'] = trim(htmlspecialchars(@$_POST['Skype']));
			$r['Email_show'] = '';
			$r['Icq_show'] = '';
			$r['Aim_show'] = '';
			$r['Skype_show'] = '';
			$r['GeburtsTag_show'] = '';
			$r['Webseite_show'] = '';
			$r['Interessen_show'] = '';
			$r['Signatur_show'] = '';
			$r['Webseite'] = trim(htmlspecialchars(@$_POST['Webseite']));
			$r['Interessen'] = trim(htmlspecialchars(@$_POST['Interessen']));
			$r['Signatur'] = trim(htmlspecialchars(@$_POST['Signatur']));
			$r['Geschlecht'] = @$_POST['Geschlecht'];
			$r['GeburtsTag'] = @$_POST['GeburtsTag'];
			$r['Pnempfang'] = @$_POST['Pnempfang'];

			//=======================================================
			if(is_array($errors) && count($errors) > 0)
			{
				$ok = false;
				$GLOBALS['tmpl']->assign("errors", $errors);
			} else {
				if(!empty($_POST['GeburtsTag']))
				{
					$GLOBALS['db']->Query("UPDATE ".PREFIX."_users SET GebTag = '" . @$_POST['GeburtsTag'] . "' WHERE Id = '" . $_SESSION['cp_benutzerid'] . "'");
				}

				if(isset($_POST['DelAvatar']) && $_POST['DelAvatar']==1)
				{
					$sql = $GLOBALS['db']->Query("SELECT Avatar FROM ".PREFIX."_modul_forum_userprofile  WHERE BenutzerId = '" . $_SESSION['cp_benutzerid'] . "'");
					$row_a = $sql->fetchrow();

					if(strpos($row_a->Avatar, 'various/') === false)
					{
						@unlink(BASE_DIR . '/modules/forums/avatars/' . $row_a->Avatar);
					}

					$GLOBALS['db']->Query("
						UPDATE
							".PREFIX."_modul_forum_userprofile
						SET
							Avatar = '',
							AvatarStandard = '1'
						WHERE
							BenutzerId = '" . $_SESSION['cp_benutzerid'] . "'");
					$avatar = '';

				}

				// Pr�fen, ob Benutzername mehr als 1 mal ge�ndert wurde und ob er das
				// recht hat, diesen zu �ndern
				$BC = '';
				$sql = $GLOBALS['db']->Query("SELECT BenutzerName,BenutzerNameChanged FROM " . PREFIX . "_modul_forum_userprofile WHERE BenutzerId = '" . $_SESSION['cp_benutzerid'] . "'");
				$row = $sql->fetchrow();
				if(($row->BenutzerName != $r['BenutzerName']) && ($row->BenutzerNameChanged < '1' || $this->fperm('changenick')) )
				{
					$BC = "
						,BenutzerNameChanged = BenutzerNameChanged+1
						,BenutzerName = '" . $r['BenutzerName'] . "'
						";
				}


				$q = "UPDATE ".PREFIX."_modul_forum_userprofile
					SET
						ZeigeProfil = '" . $r['ZeigeProfil'] . "',
						Unsichtbar = '" . $r['Unsichtbar'] . "',
						Emailempfang = '" . $r['Emailempfang'] . "',
						ZeigeProfil = '" . $r['ZeigeProfil'] . "',
						Email = '" . trim(@$_POST['Email']) . "',
						Icq = '" . trim(htmlspecialchars(@$_POST['Icq'])) . "',
						Aim = '" . trim(htmlspecialchars(@$_POST['Aim'])) . "',
						Skype = '" . trim(htmlspecialchars(@$_POST['Skype'])) . "',
						Email_show = '',
						Icq_show = '',
						Aim_show = '',
						Skype_show = '',
						GeburtsTag_show = '',
						Webseite_show = '',
						Interessen_show = '',
						Signatur_show = '',
						Webseite = '" . trim(htmlspecialchars(@$_POST['Webseite'])) . "',
						Interessen = '" . trim(htmlspecialchars(@$_POST['Interessen'])) . "',
						Signatur = '" . trim(htmlspecialchars(@$_POST['Signatur'])) . "',
						Geschlecht = '" . @$_POST['Geschlecht'] . "',
						GeburtsTag = '" . trim(@$_POST['GeburtsTag']) . "',
						AvatarStandard = '" . @$_POST['AvatarStandard'] . "',
						Pnempfang = '" . @$_POST['Pnempfang'] . "'
						$avatar
						$BC

					WHERE
						BenutzerId = '" . $_SESSION['cp_benutzerid'] . "'";

				$GLOBALS['db']->Query($q);

				// Felder
				if(isset($_POST['feld']))
				{
					foreach($_POST['feld'] as $id => $Feld)
					{

						$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_userpage_values
							SET
							f_".$id." = '" . trim(htmlspecialchars($_POST['feld'][$id])) ."'
							WHERE uid = '" . $_SESSION['cp_benutzerid'] . "'");

					}
				}

				// Multi-Felder
				if(isset($_POST['feld_multi']))
				{
					foreach($_POST['feld_multi'] as $id => $Feld)
					{
						$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_userpage_values
							SET
							f_".$id." = '" . @implode(",",@$_POST['feld_multi'][$id]) ."'
							WHERE uid = '" . $_SESSION['cp_benutzerid'] . "'");

					}
				}

				$this->msg($GLOBALS['tmpl']->get_config_vars('ProfileOK'),"index.php?module=userpage&action=change",$tpl_dir);
			}

		}
		$sql_un = $GLOBALS['db']->Query("SELECT pnid FROM " . PREFIX . "_modul_forum_pn WHERE to_uid = '$uid' AND  is_readed != 'yes' AND typ='inbox'" );
		$GLOBALS['tmpl']->assign("PNunreaded", $sql_un->numrows());

		$sql_r = $GLOBALS['db']->Query("SELECT pnid FROM " . PREFIX . "_modul_forum_pn WHERE to_uid = '$uid' AND  is_readed = 'yes' AND typ='inbox'" );
		$GLOBALS['tmpl']->assign("PNreaded", $sql_r->numrows());

		$GLOBALS['tmpl']->assign('sys_avatars', SYSTEMAVATARS);
		$GLOBALS['tmpl']->assign('prefabAvatars', $this->prefabAvatars(@$r['Avatar']));
		$GLOBALS['tmpl']->assign('avatar_width', MAX_AVATAR_WIDTH);
		$GLOBALS['tmpl']->assign('avatar_height', MAX_AVATAR_HEIGHT);
		$GLOBALS['tmpl']->assign('avatar_size', round(MAX_AVATAR_BYTES/1024));
		$GLOBALS['tmpl']->assign("forum_images", "/templates/". T_PATH ."/modules/forums/");
		$GLOBALS['tmpl']->assign("SearchPop", $GLOBALS['tmpl']->fetch(BASE_DIR . "/modules/forums/templates/search.tpl"));
		$GLOBALS['tmpl']->assign("inc_path", BASE_DIR . "/modules/forums/templates");
		$GLOBALS['tmpl']->assign('r', $r);
		$GLOBALS['tmpl']->assign('felder', $felder);
		$tpl_out = $GLOBALS['tmpl']->fetch($tpl_dir . 'myprofile.tpl');
		define("MODULE_CONTENT", $tpl_out);
		define("MODULE_SITE", $GLOBALS['tmpl']->get_config_vars('Userdata'));

}

//=======================================================================
// Admin Funktionen
//=======================================================================

	//=======================================================
	// Admin - Einstellungen
	//=======================================================
	function showSetting($tpl_dir)
	{
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_userpage WHERE Id = '1'");
		$row_e = $sql->fetchrow();

		$items = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_userpage_items ORDER BY Id ASC");
		while($row = $sql->fetchrow())
		{
			array_push($items,$row);
		}

		$Groups = array();
		$sql_g = $GLOBALS['db']->Query("SELECT Benutzergruppe,Name FROM " . PREFIX . "_user_groups");
		while($row_g = $sql_g->fetchrow())
		{
			array_push($Groups, $row_g);
		}

		$GLOBALS['tmpl']->assign("groups", $Groups);
		$GLOBALS['tmpl']->assign("groups_form", explode(",", $row_e->group_id));
		$GLOBALS['tmpl']->assign("row", $row_e);
		$GLOBALS['tmpl']->assign("items", $items);
		$GLOBALS['tmpl']->assign("tpl_path", $tpl_dir);
		$GLOBALS['tmpl']->assign("sess", SESSION);


		$GLOBALS['tmpl']->assign("formaction", "index.php?do=modules&action=modedit&mod=userpage&moduleaction=save&cp=" . SESSION);
		$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_fields.tpl"));
	}

	//=======================================================
	// Speichern
	//=======================================================
	function saveSetting($tpl_dir)
	{

		$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_userpage
			SET
				can_comment = '$_REQUEST[can_comment]',
				group_id = '" . @implode(",", $_REQUEST['Gruppen']) . "'
			WHERE
				id = '1'");


		if(!empty($_POST['del']))
		{
			foreach($_POST['del'] as $id => $Feld)
			{

				$GLOBALS['db']->Query("ALTER TABLE " . PREFIX . "_modul_userpage_values DROP f_".$id." ");

				$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_userpage_items WHERE id = '$id'");
			}
		}

		foreach($_POST['titel'] as $id => $Feld)
		{
			if(!empty($_POST['titel'][$id]))
			{


				$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_userpage_items
					SET
					title = '" . $_POST['titel'][$id] ."',
					type = '" . $_POST['type'][$id] . "',
					value = '" . $_POST['wert'][$id] . "',
					active = '" . $_POST['aktiv'][$id] . "'
					WHERE id = '$id'");
				reportLog($_SESSION["cp_uname"] . " - ������� ���� � ������ ������� ������������ (" . $_POST['Titel'] . ")",'2','2');
			}
		}
		header("Location:index.php?do=modules&action=modedit&mod=userpage&moduleaction=1&cp=" . SESSION);
	}

	//=======================================================
	// Neue Felder anlegen
	//=======================================================
	function saveFieldsNew($tpl_dir)
	{
		if(!empty($_POST['titel']))
		{
			$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_userpage_items
			 VALUES (
				'',
				'$_REQUEST[titel]',
				'$_REQUEST[type]',
				'$_REQUEST[wert]',
				'$_REQUEST[aktiv]'
			)
			");

			$sql = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_userpage_items WHERE title = '".$_REQUEST['titel']."'");
			$row = $sql->fetchrow();

			switch($_POST['type'])
			{
				case 'text':
					$GLOBALS['db']->Query("ALTER TABLE " . PREFIX . "_modul_userpage_values ADD (f_".$row->id." VARCHAR(250) NOT NULL) ;");
				break;

				case 'textfield':
					$GLOBALS['db']->Query("ALTER TABLE " . PREFIX . "_modul_userpage_values ADD (f_".$row->id." text NOT NULL) ;");
				break;

				case 'dropdown':
					$GLOBALS['db']->Query("ALTER TABLE " . PREFIX . "_modul_userpage_values ADD (f_".$row->id." VARCHAR(250) NOT NULL) ;");
				break;

				case 'multi':
					$GLOBALS['db']->Query("ALTER TABLE " . PREFIX . "_modul_userpage_values ADD (f_".$row->id." VARCHAR(250) NOT NULL) ;");
				break;
			}
		}
		reportLog($_SESSION["cp_uname"] . " - ������� ���� � ������ ������� ������������ (".$_REQUEST['Titel'].")",'2','2');
		header("Location:index.php?do=modules&action=modedit&mod=userpage&moduleaction=1&cp=" . SESSION);
	}

	//=======================================================
	// Template�nderungen
	//=======================================================
	function showTemplate($tpl_dir)
	{
		switch($_REQUEST['sub'])
		{
		default:

			$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_userpage_template WHERE Id = '1'");
			$row = $sql->fetchrow();

			$tags = array();
			$sql_tags = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_userpage_items ORDER BY Id ASC");
			while($row_tags = $sql_tags->fetchrow())
			{
				array_push($tags, $row_tags);
			}


			$GLOBALS['tmpl']->assign("row", $row);
			$GLOBALS['tmpl']->assign("tags", $tags);
			$GLOBALS['tmpl']->assign("sess", SESSION);


			$GLOBALS['tmpl']->assign("formaction", "index.php?do=modules&action=modedit&mod=userpage&moduleaction=tpl&sub=save&cp=" . SESSION);
			$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_tpl.tpl"));

	 	break;
		case 'save':

			$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_userpage_template
					SET
					tpl = '" . $_POST['Template'] ."'
					WHERE id = '1'");

			reportLog($_SESSION["cp_uname"] . " - ������� ������ � ������ ������� ������������",'2','2');
			header("Location:index.php?do=modules&action=modedit&mod=userpage&moduleaction=tpl&cp=" . SESSION);
		}
	}

	//=======================================================
	// Forum-Update
	//=======================================================
	function update($tpl_dir)
	{
		$files = array(
		"/class.forums.php",
		"/internals/last24.php",
		"/internals/pn.php",
		"/internals/search.php",
		"/internals/showabos.php",
		"/internals/showforum.php",
		"/internals/showtopic.php",
		"/internals/userlist.php",
		"/templates/categs.tpl",
		"/templates/ignorelist.tpl",
		"/templates/showpost.tpl",
		"/templates/showposter.tpl",
		"/templates/stats_forums.tpl",
		"/templates/userpanel_forums.tpl",
		"/templates/userprofile.tpl"
		);

		switch($_REQUEST['sub'])
		{
		default:

			$error = array();
			foreach($files as $i)
			{
				if(!is_writable(BASE_DIR. "/modules/forums".$i)) array_push($error, $i);

			}

			if (count($error)>0)
			{
				$GLOBALS['tmpl']->assign("error", $error);
			}
			else
			{
				$GLOBALS['tmpl']->assign("error", 0);
				$GLOBALS['tmpl']->assign("files", $files);
			}

			$GLOBALS['tmpl']->assign("formaction", "index.php?do=modules&action=modedit&mod=userpage&moduleaction=update&sub=start&cp=" . SESSION);
			$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_update.tpl"));

	 	break;
		case 'start':

		foreach($files as $i)
		{
			$content = file_get_contents(BASE_DIR. "/modules/forums".$i);

			$content = str_replace ("index.php?module=forums&amp;show=userprofile&amp;user_id=", "index.php?module=userpage&amp;action=show&amp;uid=", $content);
//  ��� ������ ���������� ������ ��������������� ���������� ������ � ���������������� ��������� ������
//	    $content = str_replace ("index.php?module=userpage&amp;action=show&amp;uid=", "index.php?module=forums&amp;show=userprofile&amp;user_id=", $content);

			if($i == "/templates/userpanel_forums.tpl")
			{
				$content = str_replace ("index.php?module=forums&amp;show=publicprofile", "index.php?module=userpage&amp;action=change", $content);
//  ��� ������ ���������� ������ ��������������� ���������� ������ � ���������������� ��������� ������
//	      $content = str_replace ("index.php?module=userpage&amp;action=change", "index.php?module=forums&amp;show=publicprofile", $content);
			}

			$write = fopen(BASE_DIR ."/modules/forums".$i, "wb");
			fwrite($write, $content);
			fclose($write);


		}

			reportLog($_SESSION["cp_uname"] . " - ������� ����� ������ �����",'2','2');
			header("Location:index.php?do=modules&action=modedit&mod=userpage&moduleaction=update&ok=1&cp=" . SESSION);
		}
	}

//=======================================================================
// Sonstige Funktionen
//=======================================================================

	//=======================================================
	// Allgemeine Forum - Rechte nach Gruppen
	//=======================================================
	function fperm($perm,$group='')
	{
		if(empty($group)) $group = UGROUP;
		$sql = $GLOBALS['db']->Query("SELECT Rechte FROM " . PREFIX . "_modul_forum_grouppermissions WHERE Benutzergruppe='$group'");
		$row = $sql->fetchrow();
		$perms = @explode("|", $row->Rechte);
		if (in_array($perm, $perms) || UGROUP==1 ) // Admin darf alles!
		{
			return true;
		}
		return false;
	}

	//=======================================================
	// System - Avatare ausgeben
	//=======================================================
	function prefabAvatars($selected='')
	{
		$verzname = BASE_DIR . "/modules/forums/avatars/various";
		$dht = opendir( $verzname );
		$sel_theme = "";
		$i = 0;
		while ( gettype( $theme = readdir ( $dht )) != @boolean )
		{
			if ( is_file( "$verzname/$theme" ))
			{
				if ($theme != "." && $theme != ".." && $theme != 'index.php')
				{
					$pres = ($selected=="various/$theme") ? "checked" : "";
					$sel_theme .= "
					<div style='float:left; text-align:center; padding:1px'><img src=\"modules/forums/avatars/various/$theme\" alt=\"\" /><br />
					<input name=\"SystemAvatar\" type=\"radio\" value=\"$theme\" $pres></div>";
					$theme = "";
					$i++;
					if($i == 6)
					{
						$sel_theme .=  "<div style='clear:both'></div>";
						$i = 0;
					}

				}
			}
		}
		return $sel_theme;

	}

	//=======================================================
	// GET AVATAR
	// ermittelt die rechte fuer den aktuellen user
	//=======================================================
	function getAvatar($group, $avatar="", $usedefault, $canupload='')
	{
		$aprint = false;
		$own = 1;
		$permown = -1;
		// nutzt er default- avatar?
		if (($usedefault == 1) && ($avatar == ""))
		{
			$own = 0;
		}

		// wenn er admin ist, fallen alle regeln weg
		if ($this->fperm('alles') || $this->fperm('own_avatar') || $group == 1)
		{
			$permown = 1;
		} else {
			// wenn seine gruppe die rechte besitzt, eigene avatar zu nutzen
			if ($this->fperm('own_avatar'))
			{
				$permown = 1;
			}
		}
		if ($permown != 1)
		{
			$own = 0;
		}
		// wenn eigenes avatar beutzt werden darf und es existiert
		if ($own == 1 && $usedefault != 1)
		{
			$avatar_file = BASE_DIR . "/modules/forums/avatars/$avatar";
			if (@is_file($avatar_file))
			{
				$fz = @getimagesize($avatar_file);
				if($fz[0] <= MAX_AVATAR_WIDTH && $fz[1] <= MAX_AVATAR_HEIGHT || $group==1)
				{
					$avatar = "<img src=\"modules/forums/avatars/$avatar\" alt=\"\" border=\"\" />";
					$aprint = true;
				}
			}
		} else {
			$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX."_modul_forum_groupavatar WHERE Benutzergruppe = '$group'");
			$row = $sql->fetchrow();
			if (is_object($row) && ($row->IstStandard == 1) && ($row->StandardAvatar != ""))
			{
				$avatar = "<img src=\"modules/forums/avatars_default/$row->StandardAvatar\" alt=\"\" border=\"\" />";
				$aprint = true;

			}
		}
		if ($avatar == '') $avatar = '';
		if ($aprint == true) return $avatar;
	}

	// Avatar Zufallsfunktion
	function rand_tostring($path, $file)
	{
		if (@file_exists($path . $file)) {
			$arr = explode(".", $file);
			$ext = $arr[count($arr)-1];
			$rand_fn = $arr[0] . mt_rand(0, 999) . "." . $ext;
		} else {
			$rand_fn = $file;
		}
		return $rand_fn;
	}

	// �nderungen ?!
	function checkIfUserName($new='',$old='')
	{
		$sql = $GLOBALS['db']->Query("SELECT
			BenutzerName
		FROM
			" . PREFIX . "_modul_forum_userprofile
		WHERE
			BenutzerName = '$new' AND
			BenutzerName != '$old'
			");

		$rc = $sql->numrows();
		if($rc==1) return true;
		return false;

	}

	// �nderungen ?!
	function checkIfUserEmail($new='',$old='')
	{
		$sql = $GLOBALS['db']->Query("SELECT
			Email
		FROM
			" . PREFIX . "_modul_forum_userprofile
		WHERE
			Email = '$new' AND
			Email != '$old'
			");

		$rc = $sql->numrows();
		if($rc==1) return true;
		return false;

	}

	function getForumUserEmail($id)
	{
		$sql = $GLOBALS['db']->Query("SELECT Email FROM " . PREFIX . "_modul_forum_userprofile WHERE BenutzerId = '$id'");
		$ru = $sql->fetchrow();
		return $ru->Email;
	}

	//=======================================================
	// Benutzername anhand ID abfagen
	//=======================================================
	function fetchusername($param)
	{
		$where = (@is_array($param)) ? $param[userid] : $param;
		$sql = $GLOBALS['db']->Query("SELECT BenutzerName,BenutzerId FROM " . PREFIX . "_modul_forum_userprofile WHERE BenutzerId='$where'");
		$row_un = $sql->fetchrow();

		if(!is_object($row_un))
		{
			return $GLOBALS['tmpl']->get_config_vars('Guest');
		} else {
			return $row_un->BenutzerName;
		}
	}

	//=======================================================
	// Online-User aktualisieren
	//=======================================================
	function UserOnlineUpdate()
	{
		$expire = time() + (60 * 10);
		$sql = $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_forum_useronline WHERE expire <= '" . time() . "'");

		if(isset($_SESSION['cp_benutzerid']))
		{
			$sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_forum_userprofile WHERE BenutzerId = '".$_SESSION['cp_benutzerid']."'");
			$num = $sql->numrows();

			// Wenn der Benutzet noch nicht im Forum-Profil gespeichert wurde,
			// wird dies hier getan
			if(!$num)
			{
				$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_users WHERE Id  = '".$_SESSION['cp_benutzerid']."'");
				$row = $sql->fetchrow();

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
					'$row->Id',
					'". (($row->UserName!='') ? $row->UserName : substr($row->Vorname,0,1) . '. ' . $row->Nachname) . "',
					'',
					'',
					'1',
					'',
					'',
					'',
					'',
					'1',
					'1',
					'',
					'',
					'',
					'0',
					'',
					'$row->Email',
					'$row->Registriert',
					'$row->GebTag'
				)";
				$GLOBALS['db']->Query($q);
				header("Location:index.php?module=forums");
			}

			$sql = $GLOBALS['db']->Query("SELECT ip FROM " . PREFIX . "_modul_forum_useronline WHERE ip='" . $_SERVER['REMOTE_ADDR'] . "' limit 1");
			$num = $sql->numrows();

			if ($num < 1)
				$sql = $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_forum_useronline (ip,expire,uname,invisible) VALUES ('" . $_SERVER['REMOTE_ADDR'] . "','$expire','" . (defined("USERNAME") ? USERNAME : "UNAME") . "','" . (defined("USERNAME") ? $this->getInvisibleStatus($_SESSION['cp_benutzerid']) : "INVISIBLE") . "')");
			 else
				$sql = $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_forum_useronline set uid = '" .  $_SESSION['cp_benutzerid']. "', uname='" . (defined("USERNAME") ? USERNAME : "UNAME") . "', invisible = '" . (defined("USERNAME") ? $this->getInvisibleStatus($_SESSION['cp_benutzerid']) : "INVISIBLE") . "'  WHERE ip='" . $_SERVER['REMOTE_ADDR'] . "'");
		} else {
			$sql = $GLOBALS['db']->Query("SELECT ip FROM " . PREFIX . "_modul_forum_useronline WHERE ip='" . $_SERVER['REMOTE_ADDR'] . "' limit 1");
			$num = $sql->numrows();
			if ($num < 1)
				$sql = $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_forum_useronline (ip,expire,uname,invisible) VALUES ('" . $_SERVER['REMOTE_ADDR'] . "','$expire','UNAME','0')");
		}
	}

	// Weiterleitung
	function msg($msg='', $goto='', $tpl='')
	{
		$goto = ($goto=='') ? 'index.php?module=forums' : $goto;
		$msg = str_replace('%%GoTo%%', $goto, $msg);
		$GLOBALS['tmpl']->assign("t_path", T_PATH);
		$GLOBALS['tmpl']->assign("GoTo", $goto);
		$GLOBALS['tmpl']->assign("content", $msg);
		$tpl_out = $GLOBALS['tmpl']->fetch($tpl . 'redirect.tpl');
		define("MODULE_CONTENT", $tpl_out);
		define("MODULE_SITE", "Weiterleitung");
		echo $tpl_out;
		exit;
	}
}
?>