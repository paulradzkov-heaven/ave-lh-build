<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("IMPORT")) exit;

function userImport()
{
	$sql = $GLOBALS['db']->Query("SELECT * FROM kpro_user WHERE uid != 2");
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
		if($row->uid != 1) $GLOBALS['db']->Query($q);
		//echo "<pre>$q</pre>";
	}
}
?>