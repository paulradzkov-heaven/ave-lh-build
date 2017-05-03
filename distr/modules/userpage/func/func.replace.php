<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

//=======================================================
// FELDER LESEN
//=======================================================
function userpage_getfield ($id,$display,$uid)
{

		$sql_a = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_userpage_items WHERE active = '1' AND id = '$id'");
		$row_a = $sql_a->fetchrow();

		$sql_b = $GLOBALS['db']->Query("SELECT * FROM  " . PREFIX . "_modul_userpage_values WHERE uid = '$uid'");
		$row_b = $sql_b->fetchrow();

		$show = true;

		if ($row_a->type == 'dropdown')
		{

			$fid = "f_".$row_a->Id;
			$drop =  explode(",", $row_a->value);

			$titel = $row_a->title;
			$wert = $drop[$row_b->$fid];

			if($wert == '') $show = false;


		}
		else if ($row_a->type == 'multi')
		{

			$fid = "f_".$row_a->Id;
			$titel = $row_a->title;
			$drop =  explode(",", $row_a->value);
			$values = explode(",", $row_b->$fid);

			if($row_b->$fid == '') $show = false;

			$wert = "<ul>";

			foreach($values as $item)
			{
				$wert .= "<li>".$drop[$item]. "</li>";
			}

			$wert .= "</ul>";

		}
		else
		{

			$fid = "f_".$row_a->Id;
			$titel = $row_a->title;
			$wert = $row_b->$fid;

			if($wert == '') $show = false;

		}

		if($show != false)
		{
			$wert = nl2br(preg_replace("/\[([\/]?)([biuBIU]{1})\]/", "<\$1\$2>", $wert));
	 		$GLOBALS['tmpl']->assign("titel", $titel);
			$GLOBALS['tmpl']->assign("wert", $wert);
			$GLOBALS['tmpl']->display(BASE_DIR . "/modules/userpage/templates/felder-".$display.".tpl");
		}

}

//=======================================================
// HEADER
//=======================================================
function userpage_header ($file,$uid)
{

	$sql = $GLOBALS['db']->Query("SELECT pnid FROM " . PREFIX . "_modul_forum_pn WHERE to_uid = '$uid' AND  is_readed != 'yes' AND typ='inbox'" );
	$GLOBALS['tmpl']->assign("PNunreaded", $sql->numrows());

	$sql_r = $GLOBALS['db']->Query("SELECT pnid FROM " . PREFIX . "_modul_forum_pn WHERE to_uid = '$uid' AND  is_readed = 'yes' AND typ='inbox'" );
	$GLOBALS['tmpl']->assign("PNreaded", $sql_r->numrows());

	$GLOBALS['tmpl']->assign("SearchPop", $GLOBALS['tmpl']->fetch(BASE_DIR . "/modules/forums/templates/search.tpl"));
	$GLOBALS['tmpl']->assign("inc_path", BASE_DIR . "/modules/forums/templates");
	$GLOBALS['tmpl']->assign("forum_images", "/templates/". T_PATH ."/modules/forums/");
	$GLOBALS['tmpl']->display(BASE_DIR . "/modules/forums/templates/". $file);


}

//=======================================================
// SPRACHE ERSETZEN
//=======================================================
function userpage_lang ($id)
{

	echo $GLOBALS['tmpl']->get_config_vars($id);

}

//=======================================================
// ONLINESTATUS
//=======================================================
function userpage_onlinestatus ($UserName,$type='')
{

	$sql = $GLOBALS['db']->Query("SELECT uname, invisible FROM " . PREFIX."_modul_forum_useronline WHERE uname='$UserName' limit 1");
		$num = $sql->numrows();
		$row = $sql->fetchrow();
		if ($num == 1)
		{
			if ((UGROUP == 1) && ($row->invisible == "INVISIBLE")) {
				$img = "user_invisible.gif" ;
				$alt = $GLOBALS['tmpl']->get_config_vars('UserIsInvisible');
			}
			if ($row->invisible != "INVISIBLE") {
				$img = "user_online.gif" ;
				$alt = $GLOBALS['tmpl']->get_config_vars('UserIsOnline');
			}
			if ((UGROUP != 1) && ($row->invisible == "INVISIBLE")) {
				$img = "user_offline.gif" ;
				$alt = $GLOBALS['tmpl']->get_config_vars('UserIsOffline');
			}
		} else {
			$img = "user_offline.gif" ;
			$alt = $GLOBALS['tmpl']->get_config_vars('UserIsOffline');
		}

		$status_img = "$alt <img class=\"absmiddle\" src=\"templates/".T_PATH."/modules/forums/statusicons/$img\" alt=\"$alt\" />";

		if($type==1) return $status_img;
		else return $alt;

}

//=======================================================
// AVATAR
//=======================================================
	function userpage_avatar($group, $avatar, $usedefault)
	{

		if ($avatar != '' and $usedefault == '0')
		{
			$avatar_file = BASE_DIR . "/modules/forums/avatars/$avatar";
			if (@is_file($avatar_file))
			{
					$avatar = "<img src=\"modules/forums/avatars/$avatar\" alt=\"\" border=\"\" />";
					$aprint = true;
			}
		} else {
			$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX."_modul_forum_groupavatar WHERE Benutzergruppe = '$group'");
			$row = $sql->fetchrow();
			if (is_object($row) && ($row->IstStandard == 1) && ($row->StandardAvatar != ""))
			{
				$avatar = "<img src=\"modules/forums/avatars_default/$row->StandardAvatar\" alt=\"\" border=\"\" />";
				$aprint = true;

			}
			else
			{
				if (no_avatar == 1)
				{

					$avatar = "<img src=\"modules/userpage/img/no_avatar.gif\" alt=\"\" border=\"\" />";
					$aprint = true;
				}
			}
		}

		if ($avatar == '') $avatar = '';
		if ($aprint == true) return $avatar;



}

//=======================================================
// SCHON DEFINIERTE FELDER (SIGNATUR / INTERESSEN / KONTAKT )
//=======================================================
function userpage_felder ($type,$display,$uid)
{

		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_forum_userprofile WHERE BenutzerId = '$uid'");
		$row = $sql->fetchrow();

		$show = true;

		switch($type)
		{
			case 'signatur':
				if($row->Signatur == '') $show = false;
				$titel = $GLOBALS['tmpl']->get_config_vars('Signatur');
				$wert = $row->Signatur;
			break;

			case 'interessen':
				if($row->Interessen == '') $show = false;
				$titel = $GLOBALS['tmpl']->get_config_vars('Interessen');
				$wert = $row->Interessen;
			break;

			case 'kontakt':
			$sql_ig = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_forum_ignorelist WHERE BenutzerId = '$uid' AND IgnoreId = '".$_SESSION['cp_benutzerid']."'");
			$num_ig = $sql_ig->numrows();

				if ($num_ig == 1) $show = false;

				$titel = $GLOBALS['tmpl']->get_config_vars('Kontakt');
				if($row->Emailempfang == '1')
				{
					$wert .= " <a href=\"javascript:void(0);\" onclick=\"popup('index.php?module=userpage&amp;action=contact&amp;method=email&amp;uid=$uid&amp;pop=1&amp;cp_theme=".T_PATH."','comment','600','400','1');\"> ". $GLOBALS['tmpl']->get_config_vars('Emailc') ."</a> ";
				}
				if($row->Pnempfang == '1')
				{
					$wert .= " <a href=\"index.php?module=forums&amp;show=pn&amp;action=new&amp;to=".base64_encode($row->BenutzerName)."\"> ". $GLOBALS['tmpl']->get_config_vars('Pn') ."</a> ";
				}
				if($row->Icq != '')
				{
					$wert .= " <a href=\"javascript:void(0);\" onclick=\"popup('index.php?module=userpage&amp;action=contact&amp;method=icq&amp;uid=$uid&amp;pop=1&amp;cp_theme=".T_PATH."','comment','500','200','1');\"> ". $GLOBALS['tmpl']->get_config_vars('Icq') ."</a> ";
				}
				if($row->Aim != '')
				{
					$wert .= " <a href=\"javascript:void(0);\" onclick=\"popup('index.php?module=userpage&amp;action=contact&amp;method=aim&amp;uid=$uid&amp;pop=1&amp;cp_theme=".T_PATH."','comment','500','200','1');\"> ". $GLOBALS['tmpl']->get_config_vars('Aim') ."</a> ";
				}
				if($row->Skype != '')
				{
					$wert .= " <a href=\"javascript:void(0);\" onclick=\"popup('index.php?module=userpage&amp;action=contact&amp;method=skype&amp;uid=$uid&amp;pop=1&amp;cp_theme=".T_PATH."','comment','500','200','1');\"> ". $GLOBALS['tmpl']->get_config_vars('Skype') ."</a> ";
				}
			break;

			case 'webseite':
				if($row->Webseite == '') $show = false;
				$titel = $GLOBALS['tmpl']->get_config_vars('Webseite');
				$wert = ' <a href="'.$row->Webseite.'" target="_blank">'. $row->Webseite .'</a> ';
			break;

			case 'geschlecht':
				if($row->Geschlecht == '') $show = false;
				$titel = $GLOBALS['tmpl']->get_config_vars('Geschlecht');
				$wert = $GLOBALS['tmpl']->get_config_vars($row->Geschlecht);
			break;

			case 'geburtstag':
				if($row->GeburtsTag == '') $show = false;
				$titel = $GLOBALS['tmpl']->get_config_vars('Geburtstag');
				$wert = $row->GeburtsTag;
			break;
		}

	$wert = nl2br(preg_replace("/\[([\/]?)([biuBIU]{1})\]/", "<\$1\$2>", $wert));

	if($show == true)
	{

	 		$GLOBALS['tmpl']->assign("titel", $titel);
			$GLOBALS['tmpl']->assign("wert", $wert);
			$GLOBALS['tmpl']->display(BASE_DIR . "/modules/userpage/templates/felder-".$display.".tpl");
	}

}

//=======================================================
// Zufallscode erzeugen
//=======================================================
function secureCode($c=0)
{
	$tdel = time() - 1200; // 20 Minuten
	$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_antispam WHERE Ctime < " . $tdel . "");
	$pass = "";
	$chars = array(2,3,4,5,6,7,8,9);
	$ch = ($c!=0) ? $c : 7;
	$count = count($chars) - 1;
	srand((double)microtime() * 1000000);
	for($i = 0; $i < $ch; $i++)
	{
		$pass .= $chars[rand(0, $count)];
	}

	$code = $pass;
	$sql = $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_antispam (Id,Code,Ctime) VALUES ('','" .$code. "','" .time(). "')");
	$codeid = $GLOBALS['db']->InsertId();

	return $codeid;
}

//=======================================================
// GÄSTEBUCH
//=======================================================
function userpage_guestbook ($limit,$uid)
{

		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_userpage WHERE id = '1'");
		$row = $sql->fetchrow();

		if($row->can_comment == '1')
		{


			$sql = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_userpage_guestbook WHERE uid = '$uid'");
			$num = $sql->numrows();
			$count = $sql->numrows();

			$seiten = ceil($num / $limit);
			$start = prepage() * $limit - $limit;

			if(isset($_REQUEST['page'])) $count = $count - $limit * ($_REQUEST['page'] - 1);


			$guests = array();
			$sql_gb = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_userpage_guestbook WHERE uid = '$uid' ORDER BY id DESC LIMIT $start,$limit");
			while($row_gb = $sql_gb->fetchrow())
			{
					$row_gb->ctime = date(date_format ,$row_gb->ctime);

					if($row_gb->author != '0')
					{
					$sql_dd = $GLOBALS['db']->Query("SELECT `UserName` FROM " . PREFIX . "_users WHERE Id = '".$row_gb->author."'");
					$row_dd = $sql_dd->fetchrow();

					$row_gb->uname = $row_dd->UserName;

					}else{

					$row_gb->uname = $GLOBALS['tmpl']->get_config_vars('Guest');

					}

					$row_gb->gid = $count;
					$count = $count - 1;

				array_push($guests,$row_gb);
			}

			$page_nav = pagenav($seiten, prepage(),
			" <a class=\"pnav\" href=\"index.php?module=userpage&action=show&uid=".$uid."&page={s}\">{t}</a> ");
			if($num > $limit) $GLOBALS['tmpl']->assign("page_nav", $page_nav);

			$sql_ig = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_forum_ignorelist WHERE BenutzerId = '$uid' AND IgnoreId = '".$_SESSION['cp_benutzerid']."'");
			$num_ig = $sql_ig->numrows();


			$group_id = ($row->group_id == "") ? $row->group_id : explode(",", $row->group_id);
			if (@in_array(UGROUP, $group_id) and $num_ig == 0) $GLOBALS['tmpl']->assign("cc", 1);


			$GLOBALS['tmpl']->assign("guests", $guests);
			$GLOBALS['tmpl']->assign("item", $row);
			$GLOBALS['tmpl']->assign("num", $num);
			$GLOBALS['tmpl']->assign("formaction", "index.php?module=userpage&amp;action=form&amp;uid=$uid&amp;cp_theme=".T_PATH."&amp;pop=1");
			$GLOBALS['tmpl']->display(BASE_DIR . "/modules/userpage/templates/guestbook.tpl");


	  }
}

//=======================================================
// FORUM
//=======================================================
function userpage_lastposts ($limit,$uid)
{

		$num = 1;

		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_forum_post WHERE uid = '".$uid."' ORDER by id DESC LIMIT 0,".$limit."");
		while($row = $sql->fetchrow())
		{
			$sql_t = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_forum_topic WHERE id = '$row->topic_id' ");
			$row_t = $sql_t->fetchrow();

			$row->topic_title = $row_t->title;

			if(is_object($row))
			{
				$wert .= $num .". <a href=\"index.php?module=forums&show=showtopic&toid=". $row->topic_id ."#pid_". $row->id ."\">". $row_t->title ."</a> <br />";
			}

			$num = $num + 1;
		}


		$titel = $GLOBALS['tmpl']->get_config_vars('Lastposts');

		$GLOBALS['tmpl']->assign("titel", $titel);
		$GLOBALS['tmpl']->assign("wert", $wert);

		if(!empty($wert))	$GLOBALS['tmpl']->display(BASE_DIR . "/modules/userpage/templates/felder-1.tpl");



}

//=======================================================
// DOWNLOADS
//=======================================================
function userpage_downloads ($limit,$uid)
{

		$num = 1;

		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_download_files WHERE Autor_Erstellt = '".$uid."' AND Aktiv = '1' ORDER by id DESC LIMIT 0,".$limit."");
		while($row = $sql->fetchrow())
		{
			if(is_object($row))
			{
				$wert .= $num .". <a href=\"index.php?module=download&action=showfile&file_id=". $row->Id ."&categ=". $row->KatId ."\">". $row->Name ."</a> <br />";
			}

			$num = $num + 1;
		}


		$titel = $GLOBALS['tmpl']->get_config_vars('Downloads');

		$GLOBALS['tmpl']->assign("titel", $titel);
		$GLOBALS['tmpl']->assign("wert", $wert);

		if(!empty($wert))	$GLOBALS['tmpl']->display(BASE_DIR . "/modules/userpage/templates/felder-1.tpl");



}

?>