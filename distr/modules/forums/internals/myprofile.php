<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("MYPROFILE")) exit;
if(!isset($_SESSION['cp_benutzerid']))
{
	$this->msg($GLOBALS['mod']['config_vars']['ErrornoPerm']);
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
	$muster     = "[^ ._A-Za-zÀ-ßà-ÿ¨¸0-9-]";
	$muster_geb = "([0-9]{2}).([0-9]{2}).([0-9]{4})"; 
	$muster_email = "^[-._A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,4})$";
	
	//=======================================================
	// Benutzername prüfen
	//=======================================================
	if((isset($_POST['BenutzerName'])) && ($this->checkIfUserName(addslashes($_POST['BenutzerName']),addslashes($_SESSION['cp_forumname']))))
	{
		$errors[] = $GLOBALS['mod']['config_vars']['PE_UsernameInUse'];
		$r['BenutzerName'] = trim(htmlspecialchars($_POST['BenutzerName']));
	}
	
	if(( @isset($_POST['BenutzerName']) && @empty($_POST['BenutzerName'])) || ereg($muster, str_replace($allowed,'',@$_POST['BenutzerName']) ))
	{
		$errors[] = $GLOBALS['mod']['config_vars']['PE_Username'];
	}
	
	//=======================================================
	// E-Mail prüfen
	//=======================================================
	if(!empty($_POST['Email']) && $this->checkIfUserEmail($_POST['Email'], $_SESSION['cp_forumemail']))
	{
		$errors[] = $GLOBALS['mod']['config_vars']['PE_EmailInUse'];
	}
	
	if(empty($_POST['Email']) || !ereg($muster_email, $_POST['Email']))
	{
		$errors[] = $GLOBALS['mod']['config_vars']['PE_Email'];
	}
	
	//=======================================================
	// WENN GEBURTSTAG IM FALSCHEN FORMAT
	//=======================================================
	if(!empty($_POST['GeburtsTag']) && !ereg($muster_geb, $_POST['GeburtsTag']))
	{
		$errors[] = $GLOBALS['mod']['config_vars']['PE_WrongBd'];
	}
	
	if(!empty($_POST['GeburtsTag'])) 
	{
		$check_year = explode(".", $_POST['GeburtsTag']);
		if(@$check_year[0] > 31 || @$check_year[1] > 12 || @$check_year[2] < date("Y")-75) 
		{
			$errors[] = $GLOBALS['mod']['config_vars']['PE_WrongBd'];	
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
	$r['Email_show'] = @$_POST['Email_show'];
	$r['Icq_show'] = @$_POST['Icq_show'];
	$r['Aim_show'] = @$_POST['Aim_show'];
	$r['Skype_show'] = @$_POST['Skype_show'];
	$r['GeburtsTag_show'] = @$_POST['GeburtsTag_show'];
	$r['Webseite_show'] = @$_POST['Webseite_show'];
	$r['Interessen_show'] = @$_POST['Interessen_show'];
	$r['Signatur_show'] = @$_POST['Signatur_show'];
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
		
		// Prüfen, ob Benutzername mehr als 1 mal geändert wurde und ob er das
		// recht hat, diesen zu ändern
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
				Email_show = '" . @$_POST['Email_show'] . "',
				Icq_show = '" . @$_POST['Icq_show'] . "',
				Aim_show = '" . @$_POST['Aim_show'] . "',
				Skype_show = '" . @$_POST['Skype_show'] . "',
				GeburtsTag_show = '" . @$_POST['GeburtsTag_show'] . "',
				Webseite_show = '" . @$_POST['Webseite_show'] . "',
				Interessen_show = '" . @$_POST['Interessen_show'] . "',
				Signatur_show = '" . @$_POST['Signatur_show'] . "',
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
		$this->msg($GLOBALS['mod']['config_vars']['ProfileOK'], 'index.php?module=forums&show=publicprofile');
	}
	
}

$GLOBALS['tmpl']->assign('prefabAvatars', $this->prefabAvatars(@$r['Avatar']));
$GLOBALS['tmpl']->assign('avatar_width', MAX_AVATAR_WIDTH);
$GLOBALS['tmpl']->assign('avatar_height', MAX_AVATAR_HEIGHT);
$GLOBALS['tmpl']->assign('avatar_size', round(MAX_AVATAR_BYTES/1024));
$GLOBALS['tmpl']->assign('r', $r);
$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'myprofile.tpl');
define("MODULE_CONTENT", $tpl_out);	
define("MODULE_SITE", $GLOBALS['mod']['config_vars']['MyProfilePublic']);
?>