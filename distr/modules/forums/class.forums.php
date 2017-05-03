<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class Forum
{
	var $_UserProfileTpl = 'userprofile.tpl';
	var $_ShowForumsTpl = 'show_forums.tpl';
	var $_ShowForumTpl  = 'showforum.tpl';
	var $_ShowTopicTpl = 'showtopic.tpl';
	var $_Print_ShowTopicTpl = 'showtopic_print.tpl';
	var $_MoveTpl = 'move.tpl';
	var $_allowed_imagetypes = array('image/pjpeg', 'image/jpeg', 'image/jpg', 'image/x-png', 'image/png', 'image/gif');
	var $_Fonts = array(
		"Arial" => "Arial",
		"Verdana" => "Verdana",
		"Trebuchet" => "Trebuchet MS",
		"Georgia" => "Georgia",
		"Times" =>
		"Times New Roman",
		"Serif" => "Serif"
	);

	var $_FontSizes = array("1","2","3","4","5","6","7");

	var $_FontColors = array(
		"#cccccc" => "������-�����",
		"#666666" => "�����-�����",
		"#000000" => "������",
		"#eec00a" => "������",
		"#ff9900" => "���������",
		"#ff0000" => "�������",
		"#990000" => "�����-�������",
		"#660000" => "�����-����������",
		"#00ccff" => "������-�������",
		"#003366" => "�������",
		"#006600" => "�������",
		"#ff33ff" => "��������",
		"#660099" => "�������",
		"#ff66ff" => "�������",
		"#663300" => "����������"

	);



	function getActPage()
	{
		$Page = (isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) ? $_REQUEST['page'] : 1);
		return $Page;
	}

	function getUserName($Id)
	{
		$sql = $GLOBALS['db']->Query("SELECT BenutzerName FROM " . PREFIX . "_modul_forum_userprofile WHERE BenutzerId = '$Id'");
		$row = $sql->fetchrow();
		return $row->BenutzerName;
	}
	//=======================================================
	// FORUM-�BERSICHT
	//=======================================================
	function showForums()
	{
		define("SHOWFORUMS", 1);
		include_once(BASE_DIR . '/modules/forums/internals/showforums.php');
	}

	//=======================================================
	// Forum mit Id anzeigen
	//=======================================================
	function showForum()
	{
		define("SHOWFORUM", 1);
		include_once(BASE_DIR . '/modules/forums/internals/showforum.php');
	}

	function userPopUp()
	{
		define("USERPOP", 1);
		include_once(BASE_DIR . '/modules/forums/internals/userpop.php');
	}

	//=======================================================
	// Thema anzeigen
	//=======================================================
	function showTopic()
	{
		define("SHOWTOPIC", 1);
		include_once(BASE_DIR . '/modules/forums/internals/showtopic.php');
	}

	function ignoreList()
	{
		define("IGNORELIST",1);
		include_once(BASE_DIR . "/modules/forums/internals/ignorelist.php");
	}


	function getFile()
	{
		define("GETFILE", 1);
		include_once(BASE_DIR . "/modules/forums/internals/getfile.php");
	}

	function voteTopic()
	{
		define("VOTETOPIC", 1);
		include_once(BASE_DIR . "/modules/forums/internals/votetopic.php");
	}

	function openClose($openclose = "open")
	{
		define("OPENCLOSETOPIC", 1);
		include_once(BASE_DIR . "/modules/forums/internals/open_close.php");
	}

	function moveTopic()
	{
		define("MOVETOPIC", 1);
		include_once(BASE_DIR . "/modules/forums/internals/move.php");
	}

	function delTopic()
	{
		define("DELTOPIC", 1);
		include_once(BASE_DIR . "/modules/forums/internals/deltopic.php");
	}

	function delPost()
	{
		define("DELPOST", 1);
		include_once(BASE_DIR . "/modules/forums/internals/delpost.php");
	}

	function setAbo($onoff = "on")
	{
		define("SETABO", 1);
		include_once(BASE_DIR . "/modules/forums/internals/abo.php");
	}

	function forumsLogin()
	{
		define("FLOGIN", 1);
		include_once(BASE_DIR . "/modules/forums/internals/forumlogin.php");
	}

	function changeType()
	{
		define("CHANGETYPE", 1);
		include_once(BASE_DIR . "/modules/forums/internals/changetype.php");
	}

	function markRead()
	{
		define("MARKREAD", 1);
		include_once(BASE_DIR . "/modules/forums/internals/markread.php");
	}

	function userPostings()
	{
		define("USERPOSTINGS", 1);
		include_once(BASE_DIR . "/modules/forums/internals/userpostings.php");
	}


	//=======================================================
	// Lezte aktive Themen der letzten 24 Stunden
	//=======================================================
	function last24()
	{
		define("LAST24", 1);
		include_once(BASE_DIR . "/modules/forums/internals/last24.php");
	}

	//=======================================================
	// Abos anzeigen
	//=======================================================
	function myAbos()
	{
		define("MYABOS", 1);
		include_once(BASE_DIR . "/modules/forums/internals/showabos.php");
	}

	//=======================================================
	// Form f�r neuen Beitrag
	//=======================================================
	function newTopic()
	{
		define("NEWTOPIC", 1);
		include_once(BASE_DIR . "/modules/forums/internals/newtopic.php");
	}

	//=======================================================
	// Beitrag neu
	//=======================================================
	function addTopic()
	{
		define("ADDTOPIC", 1);
		include_once(BASE_DIR . "/modules/forums/internals/addtopic.php");
	}


	function newPost()
	{
		define("NEWPOST", 1);
		include_once(BASE_DIR . "/modules/forums/internals/newpost.php");
	}

	function addPost()
	{
		define("ADDPOST", 1);
		include_once(BASE_DIR . "/modules/forums/internals/addpost.php");
	}


	//=======================================================
	// Datei anh�ngen
	//=======================================================
	function attachFile()
	{
		define("ATTACHFILE", 1);
		include_once(BASE_DIR . "/modules/forums/internals/attachfile.php");
	}

	function pMessages()
	{
		define("PN",1);
		include_once(BASE_DIR . "/modules/forums/internals/pn.php");
	}

	//=======================================================
	// Suche
	//=======================================================
	function doSearch()
	{
		define("SEARCH", 1);
		include_once(BASE_DIR . "/modules/forums/internals/search.php");
	}


	function searchMask()
	{
		$forums_dropdown = array();
		$this->getForums(0, $forums_dropdown, "");

		$GLOBALS['tmpl']->assign("navigation", "<a class='forum_links_navi' href='index.php?module=forums'>" . $GLOBALS['mod']['config_vars']['PageNameForums'] . "</a>" . $GLOBALS['mod']['config_vars']['ForumSep'] . $GLOBALS['mod']['config_vars']['ForumsSearch']);
		$GLOBALS['tmpl']->assign("forums_dropdown", $forums_dropdown);

		$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'search_mask.tpl');
		define("MODULE_CONTENT", $tpl_out);
		define("MODULE_SITE", $GLOBALS['mod']['config_vars']['ForumsSearch']);
	}

	function myProfile()
	{
		define("MYPROFILE", 1);
		include_once(BASE_DIR . "/modules/forums/internals/myprofile.php");
	}

	//=======================================================
	// Erzeugt ein Array mit Schriftarten f�r Formulare
	//=======================================================
	function fontDropdown()
	{
		$set['fonts'] = $this->_Fonts;
		$listfonts = array();
		foreach ( $set['fonts'] AS $font => $fontcode )
		{
			$listfonts[] = array('font' => $font,'fontname' => $fontcode);
		}
		return $listfonts;
	}

	//=======================================================
	// Schriftgroesse f�r Beitrag-Feld
	//=======================================================
	function sizeDropdown()
	{
		$set['size'] = $this->_FontSizes;
		$listfontsize = array();
		foreach ( $set['size'] AS $size => $fontsize )
		{
			$listfontsize[] = array('size' => $fontsize);
		}
		return $listfontsize;
	}

	//=======================================================
	// Schriftfarbe f�r Beitrag-Feld
	//=======================================================
	function colorDropdown()
	{
		$set['color'] = $this->_FontColors;
		$listfontcolor = array();
		foreach ( $set['color'] AS $color => $fontcolor )
		{
			$listfontcolor[] = array('color' => $color,'fontcolor' => $fontcolor);
		}
		return $listfontcolor;
	}

	//=======================================================
	// Listet Beitrags-Icons auf
	// Icons m�ssen sich in dem Themen-Verzeichnis befinden!
	//=======================================================
	function getPosticons($icon = "0")
	{
		$i = 1;
		$posticons = "";
		$sql = $GLOBALS['db']->Query("SELECT id,path FROM ".PREFIX."_modul_forum_posticons WHERE active='1' order by posi asc");
		while($rows = $sql->fetchrow())
		{
			$br = "&nbsp;";
			if ( ($i % 6) == 0 ) $br = "<br />";
			if ($icon == $rows->id || (isset($_POST['posticon']) && $_POST['posticon']==$rows->id)) $checked = "checked";
			else $checked = "";

			$posticons .= "<input class=\"noborder\"  style=\"background-color:transparent; border:0px\" type=\"radio\" name=\"posticon\" value=\"$rows->id\" $checked />&nbsp;";
			$posticons .= "<img src=\"templates/".T_PATH."/modules/forums/posticons/$rows->path\" alt=\"\" />$br";
			$i++;
		}
		$NullChecked = ($icon==0 && empty($_POST['posticon']) ) ? "checked" : "";
		$posticons .= "<input class=\"noborder\" style=\"background-color:transparent; border:0px\" type=\"radio\" name=\"posticon\" value=\"0\"  $NullChecked />&nbsp;" . $GLOBALS['mod']['config_vars']['NoPosticon'];
		return $posticons;
	}

	//=======================================================
	// Listet Smilies auf
	//=======================================================
	function listsmilies()
	{
		$sql = $GLOBALS['db']->Query("SELECT smiliebr FROM " . PREFIX . "_modul_forum_settings");
		$row = $sql->fetchrow();
		$smiliesw = '';
		$smilie_id = 0;
		$smiliesw .= '<table border="0"><tr>';

		$sql = $GLOBALS['db']->Query("SELECT code,path FROM ".PREFIX."_modul_forum_smileys WHERE active='1'");
		$num = $sql->numrows();

		$sql = $GLOBALS['db']->Query("SELECT code,path FROM ".PREFIX."_modul_forum_smileys WHERE active='1' order by posi asc LIMIT 16");
		while($row_s = $sql->fetchrow())
		{
			$val = $row_s->code;
			$key = $row_s->path;
			$smiliesw .= '<td>';
			$smiliesw .= "<a href='javascript:InsertCode(\" $val \")'><img hspace='2' vspace='2' src='templates/".T_PATH."/modules/forums/smilies/".$key."' border='0' alt='' /></a>";
			$smilie_id++;
			$smiliesw .= '</td>';
			if($smilie_id==4)
			{
				$smiliesw .= "</tr><tr>"; $smilie_id=0;
			}

		}
		$smiliesw .= '</tr></table>';


		$smiliesw_ext = '';
		$smilie_id = 0;
		$smiliesw_ext .= "<table border=\'0\' width=\'100%\'><tr>";

		$sql = $GLOBALS['db']->Query("SELECT code,path FROM ".PREFIX."_modul_forum_smileys WHERE active='1'");
		$num = $sql->numrows();

		$sql = $GLOBALS['db']->Query("SELECT code,path FROM ".PREFIX."_modul_forum_smileys WHERE active='1' order by posi asc");
		while($row_s = $sql->fetchrow())
		{
			$val = $row_s->code;
			$key = $row_s->path;
			$smiliesw_ext .= '<td width=10>';
			$smiliesw_ext .= "<a href=javascript:InsertCode(\'$val\') onclick=javascript:nd()><img hspace=2 vspace=2 src=templates/".T_PATH."/modules/forums/smilies/".$key." border=0 alt=\'\' /></a>";
			$smiliesw_ext .= '</td><td width=25%>'.$val.'</td>';
			$smilie_id++;
			if($smilie_id==4)
			{
				$smiliesw_ext .= "</tr><tr>"; $smilie_id=0;
			}

		}
		$smiliesw_ext .= '</tr></table>';

		$more_s = $smiliesw_ext;
		$smiliesw_ext_more = '<a id="more_s" href="javascript:void(0);" onmouseover="return overlib(\''.addslashes($more_s).'\',FIXX,elemX(elemObj(\'more_s\')),FIXY,elemY(elemObj(\'more_s\'))+20,TIMEOUT,4000,MOUSEOFF,NOCLOSE,WIDTH,600,STICKY);" onmouseout="nd();">��� ������...</a>';
		$smiliesw .= $smiliesw_ext_more;
		return $smiliesw;
	}

	// ========================================================
	// FORUM KATEGORIE- LOESCHFUNKTION
	// 1. foren loeschen
	// 2. kategorie loeschen
	// ========================================================
	function deleteCategory($id)
	{
		// foren loeschen
		$query = "SELECT id FROM " . PREFIX . "_modul_forum_forum WHERE category_id = $id";
		$result = $GLOBALS['db']->Query($query);

		while ($forum = $result->fetchrow())
		{
			$this->deleteForum($forum->id);
		}
		// kategorie loeschen
		$query = "DELETE FROM " . PREFIX . "_modul_forum_category WHERE id = $id";
		$result = $GLOBALS['db']->Query($query);
	}


	// ========================================================
	// FORUM- LOESCHFUNKTION
	// 1. themen loeschen
	// 2. berechtigungen loeschen
	// 3. unterkategorien loeschen
	// 4. tforum loeschen
	// ========================================================
	function deleteForum($id)
	{
		$query = "SELECT id FROM " . PREFIX . "_modul_forum_topic WHERE forum_id = $id";
		$result = $GLOBALS['db']->Query($query);
		// themen loeschen
		while ($topic = $result->fetchrow())
		{
			$this->deleteTopic($topic->id);
		}
		// berechtigungen loeschen
		$query = "DELETE FROM " . PREFIX . "_modul_forum_permissions WHERE forum_id = $id";
		$result = $GLOBALS['db']->Query($query);
		// unterkategorien loeschen
		$query = "SELECT id FROM " . PREFIX . "_modul_forum_category WHERE parent_id = $id";
		$result = $GLOBALS['db']->Query($query);

		while ($category = $result->fetchrow())
		{
			$this->deleteCategory($category->id);
		}
		// forum loeschen
		$query = "DELETE FROM " . PREFIX . "_modul_forum_forum WHERE id = $id";
		$result = $GLOBALS['db']->Query($query);
	}


	// ========================================================
	// THEMEN- LOESCHFUNKTION
	// 1. alle beitraege zum thema loeschen
	// 2. das rating zum thema loeschen
	// 3. thema loeschen
	// ========================================================
	function deleteTopic($id)
	{
		$query = "SELECT id FROM " . PREFIX . "_modul_forum_post WHERE topic_id = $id";
		$result = $GLOBALS['db']->Query($query);
		// Beitraege loeschen
		while ($post = $result->fetchrow())
		{
			$this->deletePost($post->id);

		}
		// Rating loeschen
		$query = "DELETE FROM " . PREFIX . "_modul_forum_rating WHERE topic_id = $id";
		$result = $GLOBALS['db']->Query($query);
		// Thema loeschen
		$query = "DELETE FROM " . PREFIX . "_modul_forum_topic WHERE id = $id";
		$result = $GLOBALS['db']->Query($query);
	}

	// ========================================================
	// BEITRAGS- LOESCHFUNKTION
	// 1. alle anhaenge loeschen
	// 2. anzahl der beitraege im thema reduzieren
	// 3. anzahl der beitraege des benutzers reduzieren
	// 4. beitrag loeschen
	// ========================================================
	function deletePost($id)
	{
		$query = "SELECT uid, attachment, topic_id FROM " . PREFIX . "_modul_forum_post WHERE id = $id";
		$result = $GLOBALS['db']->Query($query);
		$post = $result->fetchrow();

		$attachments = @explode(";", $post->attachment);

		// anhaenge loeschen
		foreach ($attachments as $attachment)
		{
			if ($attachment != "")
			{
				$query = "SELECT filename FROM " . PREFIX . "_modul_forum_attachment WHERE id = $attachment";
				$result = $GLOBALS['db']->Query($query);
				$file = $result->fetchrow();
				// loesche aus dem dateisystem
				//  @unlink ("../uploads/attachment" . $file);
				// loesche aus der datenbank
				$query = "DELETE FROM " . PREFIX . "_modul_forum_attachment WHERE id = $attachment";
				$result = $GLOBALS['db']->Query($query);
			}
		}

		// reduziere die anzahl der beitraege im thema
		$query = "UPDATE " . PREFIX . "_modul_forum_topic SET replies = replies -1 WHERE id = " . $post->topic_id;
		$result = $GLOBALS['db']->Query($query);

		// reduziere die anzahl der beitraege des benutzers
		$query = "UPDATE " . PREFIX."_modul_forum_userprofile SET Beitraege = Beitraege -1 WHERE BenutzerId = " . $post->uid;
		$result = $GLOBALS['db']->Query($query);

		// loesche beitrag
		$query = "DELETE FROM " . PREFIX . "_modul_forum_post WHERE id = $id";
		$result = $GLOBALS['db']->Query($query);
	}

	//=======================================================
	// 1000er - Schritte formatieren
	//=======================================================
	function num_format($param)
	{
		return number_format($param,'0','','.');
	}

	function getonlinestatus($UserName)
	{

		$sql = $GLOBALS['db']->Query("SELECT uname, invisible FROM " . PREFIX."_modul_forum_useronline WHERE uname='$UserName' limit 1");
		$num = $sql->numrows();
		$row = $sql->fetchrow();
		if ($num == 1)
		{
			if ((UGROUP == 1) && ($row->invisible == "INVISIBLE")) {
				$img = "user_invisible.gif" ;
				$alt = $GLOBALS['mod']['config_vars']['UserIsInvisible'];
			}
			if ($row->invisible != "INVISIBLE") {
				$img = "user_online.gif" ;
				$alt = $GLOBALS['mod']['config_vars']['UserIsOnline'];
			}
			if ((UGROUP != 1) && ($row->invisible == "INVISIBLE")) {
				$img = "user_offline.gif" ;
				$alt = $GLOBALS['mod']['config_vars']['UserIsOffline'];
			}
		} else {
			$img = "user_offline.gif" ;
			$alt = $GLOBALS['mod']['config_vars']['UserIsOffline'];
		}

		$status_img = "<img class=\"absmiddle\" src=\"templates/".T_PATH."/modules/forums/statusicons/$img\" alt=\"$alt\" /> $alt";
		return $status_img;
	}


	function getInvisibleStatus($UserId)
	{
		$sql = $GLOBALS['db']->Query("SELECT Unsichtbar FROM " . PREFIX . "_modul_forum_userprofile WHERE BenutzerId = '$UserId'");
		$row = $sql->fetchrow();
		return (@$row->Unsichtbar==1) ? "INVISIBLE" : @$row->Unsichtbar;
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


	//=======================================================
	// Benutzerprofil anzeigen
	//=======================================================
	function showPoster()
	{
		if (isset($_GET['tid']) && is_numeric($_GET['tid']) && $_GET['tid'] > 0)
		{
			$q_poster = "
			SELECT
			COUNT(u.BenutzerName) AS ucount,
			u.BenutzerId,
			u.BenutzerName
			FROM
			" . PREFIX . "_modul_forum_userprofile AS u,
			" . PREFIX . "_modul_forum_post AS p
			WHERE
			p.topic_id = '" . addslashes($_GET['tid']) . "' AND
			p.uid = u.BenutzerId
			GROUP BY u.BenutzerName
			";

			$r_poster = $GLOBALS['db']->Query($q_poster);
			$poster = array();
			while ($post = $r_poster->fetchrow())
			{
				$poster[] = $post;
			}

			$GLOBALS['tmpl']->assign("poster", $poster);

			$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . "showposter.tpl");
			define("MODULE_CONTENT", $tpl_out);
			define("MODULE_SITE",  $GLOBALS['mod']['config_vars']['PageNameUserProfile']);
		}
	}

	// ================================================================
	// topicExists eueberprueft, ob thema existiert
	// ================================================================
	function topicExists($t_id)
	{
		$query = "SELECT id FROM " . PREFIX . "_modul_forum_topic WHERE id = $t_id";
		$result = $GLOBALS['db']->Query($query);
		if ($result->numrows() > 0) return 1;
		return 0;
	}


	//=======================================================
	// Benutzerprofil anzeigen
	//=======================================================
	function showUserProfile()
	{
		if(isset($_POST['SendMail']) && $_POST['SendMail'] == 1 && UGROUP != 2)
		{
			// Pr�fen, ob dieser User die E-Mail an den User senden darf
			$Absender = $_SESSION['cp_forumemail'];
			$Empfang = $_POST['ToUser'];

			$q = "
				SELECT
					a.IgnoreId,
					b.Email,
					b.BenutzerName
				FROM
					" . PREFIX . "_modul_forum_ignorelist as a,
					" . PREFIX . "_modul_forum_userprofile as b
				WHERE
					a.BenutzerId = " . addslashes($_POST['ToUser']) . " AND
					a.IgnoreId != " . $_SESSION['cp_benutzerid'] . " AND
					b.BenutzerId = " . addslashes($_POST['ToUser']) . "
			";
			$sql = $GLOBALS['db']->Query($q);
			$num = $sql->numrows();
			$row = $sql->fetchrow();

			// E-Mail darf gesendet werden
			if($num == 1)
			{
				$Prefab = $GLOBALS['mod']['config_vars']['EmailBodyUser'];
				$Prefab = str_replace('%%USER%%', $row->BenutzerName, $Prefab);
				$Prefab = str_replace('%%ABSENDER%%', $_SESSION['cp_forumname'], $Prefab);
				$Prefab = str_replace('%%BETREFF%%', stripslashes($_POST['Betreff']), $Prefab);
				$Prefab = str_replace('%%NACHRICHT%%', stripslashes($_POST['Nachricht']), $Prefab);
				$Prefab = str_replace('%%ID%%', $_SESSION['cp_benutzerid'], $Prefab);
				$Prefab = str_replace('%%N%%', "\n",$Prefab);
				$Prefab = str_replace('','',$Prefab);
				$globals = new Globals;
				$GLOBALS['globals']->cp_mail($row->Email, $Prefab, stripslashes($_POST['Betreff']), FORUMEMAIL, FORUMABSENDER, "text", "");

				// weiter leiten
				$this->msg($GLOBALS['mod']['config_vars']['MessageAfterEmail'], 'index.php?module=forums&show=userprofile&user_id=' . $_POST['ToUser']);
			} else {
				// keine e-mail
				$this->msg($GLOBALS['mod']['config_vars']['MessageNoEmail'], 'index.php?module=forums&show=userprofile&user_id=' . $_POST['ToUser']);
			}
		}

		// Nicht eingeloggt, aber versucht es trotzdem :)
		if(isset($_POST['SendMail']) && $_POST['SendMail'] == 1 && UGROUP == 2)
		{
			$this->msg($GLOBALS['mod']['config_vars']['ErrornoPerm'], 'index.php?module=forums&show=userprofile&user_id=' . $_POST['ToUser']);
		}

		$_GET['user_id'] = (isset($_GET['user_id']) and is_numeric($_GET['user_id'])) ? $_GET['user_id'] : 0;
		$sql_user = $GLOBALS['db']->Query("
			SELECT
				u.*,
				ug.Benutzergruppe,
				ugn.Name as GruppenName
			FROM
				" . PREFIX . "_modul_forum_userprofile as u,
				" . PREFIX . "_users as ug,
				" . PREFIX . "_user_groups as ugn
			WHERE
				u.BenutzerId = '".addslashes($_GET['user_id'])."'
			AND
				ug.Id = u.BenutzerId
			AND
				ugn.Benutzergruppe = ug.Benutzergruppe
				");
		$row_user = $sql_user->fetchrow();

		if($row_user->ZeigeProfil==1)
		{
			$row_user->Signatur = $this->kcodes_comments($row_user->Signatur);
			$row_user->Signatur = (SMILIES==1) ? $this->replaceWithSmileys($row_user->Signatur) : $row_user->Signatur;

			$row_user->Interessen = $this->kcodes_comments($row_user->Interessen);
			$row_user->Interessen = (SMILIES==1) ? $this->replaceWithSmileys($row_user->Interessen) : $row_user->Interessen;

			$row_user->avatar = $this->getAvatar($row_user->Benutzergruppe,$row_user->Avatar,$row_user->AvatarStandard);
			$row_user->OnlineStatus = @$this->getonlinestatus(@$row_user->BenutzerName);

			$query = "SELECT COUNT(id) AS counts FROM " . PREFIX . "_modul_forum_post WHERE uid = '" . addslashes($_GET['user_id']) . "'";
			$result = $GLOBALS['db']->Query($query);
			$r_c = $result->fetchrow();
			$row_user->postings = $this->num_format($r_c->counts);

			$GLOBALS['tmpl']->assign("Ignored", (($this->isIgnored(addslashes($_GET['user_id']))) ? 1 : 0) );
			$GLOBALS['tmpl']->assign("public", 1);
			$GLOBALS['tmpl']->assign("user", $row_user);
		}

		$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . $this->_UserProfileTpl);
		define("MODULE_CONTENT", $tpl_out);
		define("MODULE_SITE",  $GLOBALS['mod']['config_vars']['PageNameUserProfile']);
	}



	//=======================================================
	// Benutzerliste
	//=======================================================
	function getUserlist()
	{
		define("USERLIST", 1);
		include_once(BASE_DIR . "/modules/forums/internals/userlist.php");
	}

	//=======================================================
	// Benennt eine Datei um, wenn diese existiert
	//=======================================================
	function createFilename($dir)
	{
		$fn = time() . mt_rand(0, 1000000000);
		if (file_exists($dir . $fn)) {
			return createFilename($dir);
		} else {
			return $fn;
		}
	}

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


	//=======================================================
	// Funktion um Emoticons mit Smilies zu ersetzen
	//=======================================================
	function replaceWithSmileys($texts)
	{
		if(SMILIES==1)
		{
			$sql = $GLOBALS['db']->Query("SELECT code,path FROM ".PREFIX."_modul_forum_smileys WHERE active='1'");
			while($row_s = $sql->fetchrow())
			{
				$texts = @str_replace($row_s->code,'<img src="templates/' . T_PATH . '/modules/forums/smilies/'.$row_s->path.'" border="0" alt="" />',$texts);
			}
		}
		return $texts;
	}


	//=======================================================
	//BB-CODES KOMMENTARE
	//=======================================================
	function kcodes_comments($text)
	{
		global $pref;
		if (COMMENTSBBCODE == 1){
			$text = $this->pre_kcodes($text);
		}else {
			$text = nl2br(kspecialchars($text));
		}
		return $text;
	}

	//=======================================================
	// Text zensieren
	//=======================================================
	function badwordreplace($param)
	{
		global $db;
		$sql = $db->Query("SELECT badwords,badwords_replace FROM " . PREFIX . "_modul_forum_settings");
		$row = $sql->fetchrow();

		$baw = $row->badwords;
		$baw = str_replace(array("\r\n", "\n"), "", $baw);
		$baw = trim($baw);

		$bwrp = $row->badwords_replace;
		if (!$row->badwords_replace) $bwrp = "!#*$&?";

		$bwarray = explode(",", $baw);
		if ($baw) {
			while (list($key, $val) = each($bwarray)) {
				$param = @eregi_replace("$val", $bwrp, $param);
			}
		}
		return $param;
	}


	//=======================================================
	// Rekursive Forum - Navigation erzeugen
	//=======================================================
	function getNavigation($id, $type, $result = null)
	{
		if ($type == "category") $parent_id = "parent_id";
		if ($type == "forum") $parent_id = "category_id";
		if ($type == "topic") $parent_id = "forum_id";
		if ($type == "post") $parent_id = "topic_id";

		// daten des aktuellen bereichs
		$q_navi = "SELECT id, title, $parent_id AS pid FROM " . PREFIX . "_modul_forum_" . $type . " WHERE id = $id";
		$r_navi = $GLOBALS['db']->Query($q_navi);
		$navi = $r_navi->fetchRow();

		// rekursion abgeschlossen
		if (@$navi->pid == 0)
		{
			return  '<a class="forum_links_navi"  href="index.php?module=forums&amp;show=showforums">'.$GLOBALS['mod']['config_vars']['PageNameForums'].'</a>' . $result;
		}

		// typ des darueberliegenden bereiches bestimmen
		if ($type == "post") $type = "topic";
		else if ($type == "topic") $type = "forum";
		else if ($type == "forum") $type = "category";
		else if ($type == "category") $type = "forum";

		// daten des darueberliegenden bereiches
		$q_parent = "SELECT id, title FROM " . PREFIX . "_modul_forum_$type WHERE id = '" . $navi->pid . "'";
		$r_parent = $GLOBALS['db']->Query($q_parent);
		$parent = $r_parent->fetchRow();

		if ($type == "topic") $result = $GLOBALS['mod']['config_vars']['ForumSep'] . "<a class='forum_links_navi' href='index.php?module=forums&show=showtopic&amp;tid=" . $parent->id . "'>" . $parent->title . "</a>" . $result;
		else if ($type == "forum") $result = $GLOBALS['mod']['config_vars']['ForumSep'] . "<a class='forum_links_navi' href='index.php?module=forums&amp;show=showforum&amp;fid=" . $parent->id . "'>" . $parent->title . "</a>" . $result;
		else if ($type == "category") $result = $GLOBALS['mod']['config_vars']['ForumSep'] . "<a class='forum_links_navi' href='index.php?module=forums&show=showforums&cid=" . $parent->id . "'>" . $parent->title . "</a>" . $result;
		return $this->getNavigation($navi->pid, $type, $result);
	}

	//=======================================================
	// Gr�sse umrechnen
	//=======================================================
	function file_size($param)
	{
		global $db;
		$size = $param;
		$size = $size*1024;
		$sizes = Array(' ����', '��', '��', '��', '��', 'P�', 'E�');
		$ext = $sizes[0];
		for ($i = 1; (($i < count($sizes)) && ($size >= 1024)); $i++) {
			$size = $size / 1024;
			$ext = ' ' . $sizes[$i];
		}
		return round($size, 1) . $ext;
	}

	//=======================================================
	// Dateianhang-Gr�sse
	//=======================================================
	function get_attachment_size($file)
	{
		if ($fsize = @filesize(BASE_DIR . '/modules/forums/attachments/' . $file))
		{
			$fsize = $fsize/1024;
			return $this->file_size($fsize);
		}
	}

	// ========================================================
	// getForums listet alle foren auf
	// ========================================================
	function getForums($id, &$forums, $prefix, $f_id = "0")
	{
		// kategorien holen
		$q_cat = "SELECT id FROM " . PREFIX . "_modul_forum_category WHERE parent_id = $id";
		$r_cat = $GLOBALS['db']->Query($q_cat);
		// berechtigung holen
		$group_ids_misc = array();
		// ========================================================
		// miscrechte
		// ========================================================
		if (@is_numeric(UID) && UGROUP != 2)
		{
			$queryfirst = "SELECT GroupIdMisc FROM " . PREFIX."_modul_forum_userprofile WHERE BenutzerId = " . UID;
			$result = $GLOBALS['db']->Query($queryfirst);
			$user = $result->fetchrow();
			// ========================================================
			// wenn misc nicht leer ist...
			// ========================================================
			if ($user->GroupIdMisc != "") {
				$group_ids_pre = UGROUP . ";" . $user->GroupIdMisc;
				$group_ids_misc = @explode(";", $group_ids_pre);
				// ========================================================
				// wennn misc leer ist und user eingeloggt ist
				// ========================================================
			} else {
				$group_ids_misc[] = UGROUP;
			}
			// ========================================================
			// wenn user nicht eingeloggt ist
			// ========================================================
		} else {
			$group_ids_misc[] = 2;
		}

		// ========================================================
		// kategorien durchgehen
		// ========================================================
		$sub=false;
		while ($cat = $r_cat->fetchrow())
		{
			// foren zur kategorie holen
			$q_forum = "
				SELECT
				f.id,
				f.category_id,
				f.title,
				f.group_id
				FROM
				" . PREFIX . "_modul_forum_forum AS f
				WHERE
				f.category_id = '" . $cat->id . "' AND
				f.active = 1";
			$result = $GLOBALS['db']->Query($q_forum);

			// alle foren durchgehen
			while ($forum = $result->fetchrow())
			{
				// forumname
				$sql_ftitle = $GLOBALS['db']->Query("SELECT title FROM " . PREFIX . "_modul_forum_category WHERE id='$forum->category_id'");
				$row_ftitle = $sql_ftitle->fetchrow();

				$group_ids = explode(",", $forum->group_id);

				if (array_intersect($group_ids_misc, $group_ids))
				{
					foreach($group_ids_misc as $gids_misc)
					{
						if(in_array($gids_misc, $group_ids))
						{
							$permissions = $this->getForumPermissions($forum->id, $gids_misc);

							if ($permissions[FORUM_PERMISSION_CAN_SEE] == 1)
							{
								$forum->visible_title = $prefix . $forum->title;
								$forum->fname = $row_ftitle->title;

								$q_sub_cat = "SELECT id FROM " . PREFIX . "_modul_forum_category WHERE parent_id = " . $forum->id;
								$r_sub_cat = $GLOBALS['db']->Query($q_sub_cat);
								$forums[] = $forum;
								$sub=true;
							}
						}
					}

					if($sub==true)
					{
						while ($sub_cat = $r_sub_cat->fetchrow())
						{
							$this->getForums($forum->id, $forums, $prefix . "- ", $f_id);
						}
					}
				}
			}
		}
	}


	function getCategories($id, &$categories, $prefix)
	{
		//========================================================
		// kategorien holen
		//========================================================
		$q_cat = "
			SELECT
			id,
			title,
			group_id,
			position
			FROM
			" . PREFIX . "_modul_forum_category
			WHERE
			parent_id = $id
			ORDER BY
			position";

		$r_cat = $GLOBALS['db']->Query($q_cat);

		//========================================================
		// berechtigung holen
		//========================================================
		$group_ids_misc = array();

		//========================================================
		// miscrechte
		//========================================================
		if (@is_numeric(UID) && UGROUP != 2) {
			$queryfirst = "SELECT GroupIdMisc FROM " . PREFIX."_modul_forum_userprofile WHERE BenutzerId = " . UID;
			$result = $GLOBALS['db']->Query($queryfirst);
			$user = $result->fetchrow();

			//========================================================
			// wenn misc nicht leer ist...
			//========================================================
			if ($user->GroupIdMisc != "")
			{
				$group_ids_pre = UGROUP . ";" . $user->GroupIdMisc;
				$group_ids_misc = @explode(";", $group_ids_pre);

			//========================================================
			// wennn misc leer ist und user eingeloggt ist
			//========================================================
			} else {
				$group_ids_misc[] = UGROUP;
			}
			//========================================================
			// wenn user nicht eingeloggt ist
			//========================================================
		} else {
			$group_ids_misc[] = 2;
		}
		//========================================================
		// kategorien durchgehen
		//========================================================
		while ($cat = $r_cat->fetchrow())
		{
			$cat->group_id = @explode(",", $cat->group_id);
			if (array_intersect($cat->group_id, $group_ids_misc))
			{
				//========================================================
				// foren zur kategorie holen
				//========================================================
				$q_forum = "
					SELECT
					f.id,
					f.category_id,
					f.title,
					f.group_id,
					f.position
					FROM
					" . PREFIX . "_modul_forum_forum AS f
					WHERE
					f.category_id = '" . $cat->id . "' AND
					f.active = 1
					ORDER BY
					position";

				$result = $GLOBALS['db']->Query($q_forum);
				// alle foren durchgehen
				while ($forum = $result->fetchrow()) {
					// forumname
					$sql_ftitle = $GLOBALS['db']->Query("SELECT title FROM " . PREFIX . "_modul_forum_category WHERE id='$forum->category_id'");
					$row_ftitle = $sql_ftitle->fetchrow();

					$group_ids = explode(",", $forum->group_id);

					if (array_intersect($group_ids_misc, $group_ids)) {
						$forum->visible_title = $prefix . $forum->title;
						$forum->fname = $row_ftitle->title;

						$q_sub_cat = "SELECT id FROM " . PREFIX . "_modul_forum_category WHERE parent_id = " . $forum->id;
						$r_sub_cat = $GLOBALS['db']->Query($q_sub_cat);

						$cat->forums[] = $forum;

						while ($sub_cat = $r_sub_cat->fetchrow()) {
							$this->getCategories($forum->id, $categories, $prefix . "- ");
						}
					} // if array
				} // while forum
				$categories[] = $cat;
			} // if array_intersect
		}
	}


	//========================================================
	// FORUM PERMISSIONS
	// ermittelt die rechte fuer den aktuellen Benutzer
	//========================================================
	function getForumPermissions($forumid, $groupid, $ext_uid = 0)
	{
		global $db;
		$sql = $GLOBALS['db']->Query("SELECT permissions FROM " . PREFIX . "_modul_forum_permissions WHERE forum_id = '$forumid' AND group_id = '$groupid'");
		$row = $sql->fetchrow();

		if (UGROUP == 1) {
			return explode(",", "1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1");
		} else {
			return explode(",", $row->permissions);
		}
	}


	//=======================================================
	// Foren-Rechte nach Benutzer-ID auslesen
	//=======================================================
	function getForumPermissionsByUser($forum_id, $user_id)
	{
		if (@is_numeric(UID) && UID != 0)
		{
			$queryfirst = "SELECT GroupIdMisc FROM " . PREFIX."_modul_forum_userprofile WHERE BenutzerId = '$user_id'";
			$result = $GLOBALS['db']->Query($queryfirst);
			$user = $result->fetchrow();

			if ($user->GroupIdMisc != "")
			{
				$group_ids_pre = UGROUP . ";" . $user->GroupIdMisc;
				$group_ids = @explode(";", $group_ids_pre);
			} else {
				$group_ids[] = UGROUP;
			}
		} else {
			$group_ids[] = 2;
		}

		$all_permissions = array();

		foreach ($group_ids as $group_id)
		{
			$permissions = "";
			$query = "SELECT permissions FROM " . PREFIX . "_modul_forum_permissions WHERE forum_id = '$forum_id' AND group_id = '$group_id'";
			$result = $GLOBALS['db']->Query($query);

			$r_permissions = $result->fetchrow();

			$obj = $r_permissions;
			$permissions = explode(",", @$r_permissions->permissions);
			if(is_object($obj))
			{
				if ($permissions[0] == 1) {
					$all_permissions[0] = 1;
				}

				if (@$permissions[FORUM_PERMISSION_CAN_SEE_TOPIC] == 1) {
					$all_permissions[FORUM_PERMISSION_CAN_SEE_TOPIC] = 1;
				}

				if (@$permissions[FORUM_PERMISSION_CAN_SEE_DELETE_MESSAGE] == 1) {
					$all_permissions[FORUM_PERMISSION_CAN_SEE_DELETE_MESSAGE] = 1;
				}

				if (@$permissions[FORUM_PERMISSION_CAN_SEARCH_FORUM] == 1) {
					$all_permissions[FORUM_PERMISSION_CAN_SEARCH_FORUM] = 1;
				}

				if (@$permissions[FORUM_PERMISSION_CAN_DOWNLOAD_ATTACHMENT] == 1) {
					$all_permissions[FORUM_PERMISSION_CAN_DOWNLOAD_ATTACHMENT] = 1;
				}

				if (@$permissions[FORUM_PERMISSION_CAN_CREATE_TOPIC] == 1) {
					$all_permissions[FORUM_PERMISSION_CAN_CREATE_TOPIC] = 1;
				}

				if (@$permissions[FORUM_PERMISSION_CAN_REPLY_OWN_TOPIC] == 1) {
					$all_permissions[FORUM_PERMISSION_CAN_REPLY_OWN_TOPIC] = 1;
				}

				if (@$permissions[FORUM_PERMISSION_CAN_REPLY_OTHER_TOPIC] == 1) {
					$all_permissions[FORUM_PERMISSION_CAN_REPLY_OTHER_TOPIC] = 1;
				}

				if (@$permissions[FORUM_PERMISSION_CAN_UPLOAD_ATTACHMENT] == 1) {
					$all_permissions[FORUM_PERMISSION_CAN_UPLOAD_ATTACHMENT] = 1;
				}

				if (@$permissions[FORUM_PERMISSION_CAN_RATE_TOPIC] == 1) {
					$all_permissions[FORUM_PERMISSION_CAN_RATE_TOPIC] = 1;
				}

				if (@$permissions[FORUM_PERMISSION_CAN_EDIT_OWN_POST] == 1) {
					$all_permissions[FORUM_PERMISSION_CAN_EDIT_OWN_POST] = 1;
				}

				if (@$permissions[FORUM_PERMISSION_CAN_DELETE_OWN_POST] == 1) {
					$all_permissions[FORUM_PERMISSION_CAN_DELETE_OWN_POST] = 1;
				}

				if (@$permissions[FORUM_PERMISSION_CAN_MOVE_OWN_TOPIC] == 1) {
					$all_permissions[FORUM_PERMISSION_CAN_MOVE_OWN_TOPIC] = 1;
				}

				if (@$permissions[FORUM_PERMISSION_CAN_CLOSE_OPEN_OWN_TOPIC] == 1) {
					$all_permissions[FORUM_PERMISSION_CAN_CLOSE_OPEN_OWN_TOPIC] = 1;
				}

				if (@$permissions[FORUM_PERMISSION_CAN_DELETE_OWN_TOPIC] == 1) {
					$all_permissions[FORUM_PERMISSION_CAN_DELETE_OWN_TOPIC] = 1;
				}

				if (@$permissions[FORUM_PERMISSION_CAN_DELETE_OTHER_POST] == 1) {
					$all_permissions[FORUM_PERMISSION_CAN_DELETE_OTHER_POST] = 1;
				}

				if (@$permissions[FORUM_PERMISSION_CAN_EDIT_OTHER_POST] == 1) {
					$all_permissions[FORUM_PERMISSION_CAN_EDIT_OTHER_POST] = 1;
				}

				if (@$permissions[FORUM_PERMISSIONS_CAN_OPEN_TOPIC] == 1) {
					$all_permissions[FORUM_PERMISSIONS_CAN_OPEN_TOPIC] = 1;
				}

				if (@$permissions[FORUM_PERMISSIONS_CAN_CLOSE_TOPIC] == 1) {
					$all_permissions[FORUM_PERMISSIONS_CAN_CLOSE_TOPIC] = 1;
				}

				if (@$permissions[FORUM_PERMISSIONS_CAN_CHANGE_TOPICTYPE] == 1) {
					$all_permissions[FORUM_PERMISSIONS_CAN_CHANGE_TOPICTYPE] = 1;
				}

				if (@$permissions[FORUM_PERMISSIONS_CAN_MOVE_TOPIC] == 1) {
					$all_permissions[FORUM_PERMISSIONS_CAN_MOVE_TOPIC] = 1;
				}

				if (@$permissions[FORUM_PERMISSIONS_CAN_DELETE_TOPIC] == 1) {
					$all_permissions[FORUM_PERMISSIONS_CAN_DELETE_TOPIC] = 1;
				}
			}
		}

		return $all_permissions;
	}


	//=======================================================
	// Returns an array of subforum ids of forum with id $forumId.
	// @param int $forumId
	// @param Array $forumIds
	// @return Array
	//=======================================================
	function getForumIds($forumId, $forumIds = '')
	{
		if ($forumIds == '')
		{
			$forumIds = array();
			array_push($forumIds, $forumId);
		}
		$query = $GLOBALS['db']->Query("SELECT id FROM ". PREFIX ."_modul_forum_category WHERE parent_id='". $forumId ."'");
		while ($row = $query->FetchObject())
		{
			$categoryId = $row->id;
			$query2 = $GLOBALS['db']->Query("SELECT id FROM ". PREFIX ."_modul_forum_forum WHERE category_id='". $categoryId ."'");
			while ($row2 = $query2->FetchObject())
			{
				$thisForumId = $row2->id;
				array_push($forumIds, $thisForumId);
				$forumIds = $this->getForumIds($thisForumId, $forumIds);
			}
		}
		return $forumIds;
	}


	//=======================================================
	// Returns the last forum post including topic_id, title, id, uid,
	// datum and Registriert as an object
	// if there are forums - otherwise false.
	// @param int $forumId
	// @return Object
	//=======================================================
	function getLastForumPost($forumId)
	{
		$oc = (defined("UGROUP") && UGROUP==1) ? '' : ' AND opened = 1' ;
		$forumIds = $this->getForumIds($forumId);
		if (count($forumIds) > 0)
		{
			$i = 0;
			$SELECT = "SELECT id, title FROM ". PREFIX ."_modul_forum_topic WHERE ";
			foreach ($forumIds AS $aForumId) {
				if ($i > 0) $SELECT .= " OR ";
				$SELECT .= " (forum_id='". $aForumId ."' $oc) ";
				$i++;
			}
			$SELECT .= " ORDER BY last_post DESC LIMIT 0,1";

			$query = $GLOBALS['db']->Query($SELECT);
			$row = $query->FetchObject();

			$return->topic_id = $row->id;
			$return->title = $row->title;

			$query = $GLOBALS['db']->Query("SELECT id, uid, datum FROM ". PREFIX ."_modul_forum_post WHERE topic_id='". $return->topic_id ."' ORDER BY datum DESC LIMIT 0,1");
			$post = $query->FetchObject();

			$return->id = $post->id;
			$return->uid = $post->uid;
			$return->datum = $post->datum;

			$q = "SELECT
				u.Registriert,
				us.BenutzerName
			FROM
				". PREFIX."_users as u,
				". PREFIX."_modul_forum_userprofile as us
			WHERE
				u.Id = '". $return->uid ."' AND
				us.BenutzerId = u.Id
				";
			#echo "$q<br>";
			$query = $GLOBALS['db']->Query($q);
			$user = $query->FetchObject();

			if(is_object($user))
			{
				$return->TopicName = $row->title;
				$return->user_regdate = $user->Registriert;
				$return->BenutzerName = $user->BenutzerName;
			}
		} else {
			return false;
		}
		return $return;
	}

	//=======================================================
	// Returns a database query including all topic ids of all subforums.
	// @param int $forumId
	// @return DatabaseQuery
	//=======================================================
	function getNumberOfThreadsQuery($forumId)
	{
		global $db, $q_tcount_extra, $show_only_own_topics;

		$forumIds = $this->getForumIds($forumId);
		$i = 0;
		$SELECT = "SELECT id FROM ". PREFIX ."_modul_forum_topic WHERE (";
		foreach ($forumIds AS $aForumId)
		{
			if ($i > 0) $SELECT .= " OR ";
			$SELECT .= " forum_id='". $aForumId ."'";
			$i++;
		}
		$SELECT .= ") $q_tcount_extra $show_only_own_topics ";
		return $GLOBALS['db']->Query($SELECT);
	}

	//=======================================================
	// Beitrag als gelesen markieren
	//=======================================================
	function Cpengine_Board_SetTopicRead($Topic, $Usr = null)
	{
	   if (is_null($Usr)) $Usr = UID;

		$Topic  = (int) $Topic;
		$Usr    = (int) $Usr;

		$GLOBALS['db']->Query('REPLACE DELAYED INTO '.PREFIX.'_modul_forum_topic_read (Usr,Topic) VALUES ('.$Usr.','.$Topic.')');
	}


	function Cpengine_Board_SetLastPost($Board)
	{
		$Board = (int) $Board;
		$Sql = "
		SELECT
			Post.id,
			Post.datum
		FROM
			" . PREFIX . "_modul_forum_topic AS Topic
		INNER JOIN
			" . PREFIX . "_modul_forum_post AS Post
		ON
			Topic.id = Post.topic_id
		AND
			Topic.forum_id = ".$Board."
		ORDER
			BY  Post.datum DESC
		LIMIT 1";
		$Res = $GLOBALS['db']->Query($Sql);

		if ($Res !== false)
		{
			$LastPost = $Res->fetchrow();
			 $Sql = "UPDATE  ".PREFIX."_modul_forum_forum SET
					last_post       = '".$LastPost->datum."',
					last_post_id    = ".$LastPost->id."
			   WHERE
					id = ".$Board." ";
			$Res = $GLOBALS['db']->Query($Sql);
		}
	}

	function Cpengine_Board_GetTopic_Board($Topic)
	{
		$Topic = (int) $Topic;
		$Sql = "SELECT  Topic.forum_id FROM    ".PREFIX."_modul_forum_topic AS Topic WHERE   Topic.id = ".$Topic." LIMIT 1";
		$Res = $GLOBALS['db']->Query($Sql);
		$Row = $Res->fetchrow();
		return (int) $Row->forum_id;
	}

	//=======================================================
	// Forum als gelesen markieren
	//=======================================================
	function setForumAsRead($Board = '', $Usr = null, $BoardAll = '')
	{
		if (is_null($Usr))
		$Usr = UID;

		$Board  = (int) $Board;
		$BoardAll = ($_REQUEST['ReadAll']==1) ? 'WHERE Topic.forum_id != 0' :   'WHERE Topic.forum_id = '.$Board.'';
		$Usr    = (int) $Usr;

		$SQL_Post48h    = ' Topic.last_post > \''.date('Y-m-d', strtotime(BOARD_NEWPOSTMAXAGE)).'\' ';
		$sql = 'REPLACE DELAYED INTO '.PREFIX.'_modul_forum_topic_read (Usr,Topic)
			SELECT
				'.$Usr.',
				Topic.id
			FROM  '.PREFIX.'_modul_forum_topic as Topic
			'.$BoardAll.'
			AND
				'.$SQL_Post48h.'';

		$dump = $GLOBALS['db']->Query($sql);
	}


	//=======================================================
	// Lezten Beitrag auslesen
	//=======================================================
	function getLastPost($id)
	{
		global $db;
		$post = "";
		if(is_numeric($id))
		{
			$result = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_forum_post WHERE topic_id = '$id' ORDER BY id DESC LIMIT 2");
			$post = $result->fetchrow();

			if ($result->numrows() > 1)
			{
				$post->prev_post = $result->fetchrow();
			}
		}
		return $post;
	}

	//=======================================================
	// Letzte Beitr�ge eines Forum auslesen
	//=======================================================
	function getLastPostings($id)
	{
		$postings = "";
		if(is_numeric($id))
		{
			$postings = array();
			$result = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_forum_topic WHERE forum_id = '$id'");
			while ($topic = $result->fetchrow()) $postings[] = $this->getLastPost($topic->id);
		}
		return $postings;
	}

	function getPageNum($repliesCount, $limit)
	{
		//=======================================================
		// Berechnung
		// 1. ermitteln wieviele beitraege bereits im bereich 0 bis $limit liegen.
		// modulo (%) liefert den rest einer division
		// 2. ermitteln wieviele beitraege bis zum naechsten
		// hoechstwert ($limit*n) noch fehlen
		// 3. diesen wert zu den gesamtantworten addieren;
		// danach hat man eine durch $limit teilbare zahl
		// 4. durch die division durch $limit erhaelt man
		// nun die seite auf der der beitrag angezeigt wird -->>
		//=======================================================
		if (($repliesCount % $limit) == 0) return $repliesCount / $limit;
		return ($repliesCount + ($limit - ($repliesCount % $limit))) / $limit;
	}


	// ================================================================
	// setForumIcon setzt das zutreffende icon fuer die foren
	// ================================================================
	function setForumIcon(&$forum)
	{
		global $db;
		$SQL_Post48h    = ' Topic.last_post > \''.date('Y-m-d', strtotime(BOARD_NEWPOSTMAXAGE)).'\' ';

		$q_topic = "
			SELECT  COUNT(*) AS NewPostCount

			FROM    ".PREFIX."_modul_forum_topic AS Topic

				LEFT JOIN ".PREFIX."_modul_forum_topic_read AS TRead
					ON  Topic.id = TRead.Topic
					AND TRead.Usr = ".UID."

			WHERE   forum_id = ".$forum['id']."
				AND (   TRead.ReadOn < Topic.last_post
					OR  TRead.ReadOn IS NULL )
				AND ".$SQL_Post48h."
		";

		$r_topic = $GLOBALS['db']->Query($q_topic);

		$topic = $r_topic->fetchrow();
		$NewPostCount = $topic->NewPostCount;

		$icon = "forum";

		$icon .= ($NewPostCount > 0) ? "_new" : "_old";
		$icon .= ($forum['status'] == FORUM_STATUS_CLOSED) ? "_lock" : "";
		$icon .= ".gif";

		$forum['statusicon'] = $this->getIcon($icon, "forum");
	}


	//=======================================================
	// setTopicIcon setzt das zutreffende icon fuer jedes thema
	//=======================================================
	function setTopicIcon(&$topic, $forum)
	{
		$SQL_Post48h    = ' Topic.last_post > \''.date('Y-m-d', strtotime(BOARD_NEWPOSTMAXAGE)).'\' ';

		$HOT_VIEW_COUNT = 250;
		$HOT_REPLY_COUNT = 20;

		// post holen
		$r_post = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_forum_post WHERE topic_id = " . $topic['id'] . " ORDER BY id DESC");
		$post = $r_post->fetchrow();

		/* Counting, how often user has posted in this topic */
		$q_uid = "
			SELECT  COUNT(*) PostedInThread
			FROM    ".PREFIX."_modul_forum_post
			WHERE   topic_id=".$topic['id']."
				AND uid = ".UID."
		";
		$r_uid = $GLOBALS['db']->Query($q_uid);
		$uid = $r_uid->fetchrow();

		/* New Posts in Topic */
		$sql = "
			SELECT  COUNT(*) AS NewPostCount

			FROM    ".PREFIX."_modul_forum_topic AS Topic

				LEFT JOIN ".PREFIX."_modul_forum_topic_read AS TRead
					ON  Topic.id = TRead.Topic
					AND TRead.Usr = ".UID."

			WHERE   Topic.id=".$topic['id']."
				AND (   TRead.ReadOn < Topic.last_post
					OR  TRead.ReadOn IS NULL )
				AND ".$SQL_Post48h."
		";
		$res = $GLOBALS['db']->Query($sql);
		$NewPost = $res->fetchrow();

		// neu oder nicht
		$new = false;
		$file = "thread";
		if ($uid->PostedInThread > 0) $file .= "_dot";
		if (@$topic['replies'] > $HOT_REPLY_COUNT || $topic['views'] > $HOT_VIEW_COUNT) $file .= "_hot";
		if (@$topic['status'] == FORUM_STATUS_CLOSED || $forum->status == FORUM_STATUS_CLOSED) $file .= "_lock";

		if ($NewPost->NewPostCount > 0)
		{
			$file .= "_new";
			$new = true;
		}

		$file .= ".gif";

		switch ($topic['type'])
		{
			case TOPIC_TYPE_ANNOUNCE:
				if ($new)
				{
					$topic['statusicon'] = $this->getIcon("announcement_new.gif", "announcement");
				} else {
					$topic['statusicon'] = $this->getIcon("announcement_old.gif", "announcement");
				}
			break;
		case TOPIC_TYPE_STICKY:
		default:
			$topic['statusicon'] = $this->getIcon($file, $file);
		}
	}


	function getStatusIcon($params)
	{
		global $set;
		extract($params);

		if ($icon == "") return "";

		$imageName = $set["statusiconlist"][$icon] . ".gif";
		return "<img src=\"templates/".T_PATH."/modules/forums/statusicons/$imageName\" alt=\"$imageName\" />";
	}


	function getIcon($file, $alt)
	{
		return "<img src=\"templates/".T_PATH."/modules/forums/statusicons/$file\" alt=\"$alt\" title=\"$alt\"/>";
	}

	//=======================================================
	// setzt den status des forums auf $status
	// auch die subforen werden beachtet
	//=======================================================
	function switchForumStatus($id, $status)
	{
		$r_close = $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_forum_forum SET status = $status WHERE id = $id");
		$q_child = "
			SELECT f.id FROM
			" . PREFIX . "_modul_forum_category AS c,
			" . PREFIX . "_modul_forum_forum AS f
			WHERE parent_id = $id AND f.category_id = c.id";

		$r_child = $GLOBALS['db']->Query($q_child);

		if ($r_child->numrows() == 0) return;

		while ($child = $r_child->fetchrow()) {
			$this->switchForumStatus($child->id, $status);
		}
		return;
	}

	function isreaded($id)
	{

		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_forum_pn WHERE pnid = '$id'");
		$rows = $sql->fetchrow();

		if ($rows->is_readed == "yes")
			$im = "readed";
		else
			$im = "unreaded";

		if ($rows->reply == "yes") $im = "reply";
		if ($rows->forward == "yes") $im = "forward";

		$icon = '<img aling="" hspace="1" src="templates/' . T_PATH . '/modules/forums/pn/'.$im.'.gif" border="0" alt="" />';
		return $icon;
	}
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
			return $GLOBALS['mod']['config_vars']['Guest'];
		} else {
			return $row_un->BenutzerName;
		}
	}

	//=======================================================
	// Moderatoren eines Forums ermitteln
	//=======================================================
	function get_mods($fid)
	{
		if ($fid) {
			$sql = $GLOBALS['db']->Query("SELECT user_id FROM " . PREFIX . "_modul_forum_mods WHERE forum_id = '$fid'");
			while ($row = $sql->fetchrow()) {
				$mods[] = "<a class=\"forum_links_small\" href=\"index.php?module=forums&amp;show=userprofile&amp;user_id=$row->user_id\">" . $this->fetchusername($row->user_id) . "</a>";
			}
			return @implode(", ", $mods);
		}
	}

	//=======================================================
	// Text hervorheben
	//=======================================================
	function high($text)
	{
		if (isset($_GET['high']) && $_GET['high'] != "") {
			$treffer = '(>[^<]*)(' . quotemeta($_GET['high']) . ')';
			$ersatz = '\\1<span class="highlight">\\2</span>';
			$text = eregi_replace($treffer, $ersatz , $text);
		}
		return $text;
	}


	function datumtomytime($datum)
	{
		$datum_pre = @explode(' ', $datum);
		$datum_1 = $datum_pre[0];
		$datum_2 = $datum_pre[1];
		$dp2 = @explode('-', $datum_1);
		$dp3 = @explode(':', $datum_2);
		$datum_new = @mktime($dp3[0], $dp3[1], $dp3[2], $dp2[1], $dp2[2], $dp2[0]);
		$datum_new_f = $datum_new;
		return $datum_new_f;
	}

	// ====================================================================
	// <<-- HOEHE DER CODEBOXEN ERMITTELN -->>
	// ====================================================================
	function divheight($text)
	{
		static $maxlines;

		$sql = $GLOBALS['db']->Query("SELECT maxlines FROM " . PREFIX . "_modul_forum_settings");
		$row = $sql->fetchrow();

		if (!isset($maxlines)){
			$maxlines = $row->maxlines;
		}

		$lines = max(substr_count($text, "\n"), substr_count($text, "<br />")) + 1;
		if ($lines > $maxlines AND $maxlines > 0){
			$lines = $maxlines;
		}
		else if ($lines < 1){
			$lines = 1;
		}
		return ($lines) * 15 + 18;
	}


	// ====================================================================
	// <<-- BB- CODES VORBEREITEN -->>
	// ====================================================================
	function pre_kcodes($text)
	{
		global $set, $pref, $boxtype;
		if(IMGCODE==1) { };

		$divheight = $this->divheight($text);
		$sql = $GLOBALS['db']->Query("SELECT boxwidthcomm,boxwidthforums,maxlengthword FROM " . PREFIX . "_modul_forum_settings");
		$row = $sql->fetchrow();
		$bwidth = ("pagecomments") ? $row->boxwidthcomm : $row->boxwidthforums;

		$head = '<div style="MARGIN: 5px 0px 0px">%%boxtitle%%</div><div class="divcode" style="margin:0px; padding:5px; border:1px inset; width:'.$bwidth.'; height:'.$divheight.'px; overflow:auto"><code style="white-space:nowrap">';
		$foot = '</code></div>';

		$head_quote = '<div style="MARGIN: 5px 0px 0px">%%boxtitle%%</div><div class="divcode" style="margin:0px; padding:5px; border:1px inset; width:95%;"><span style="font-style:italic;">';
		$foot_quote = '</span></div>';



		$pstring = time() . mt_rand(0,10000000);
		$treffer = "/\[php\](.*?)\[\/php\]/si";
		@preg_match_all ($treffer, $text, $erg);
		for($i=0;$i<count($erg[1]);$i++) { $text = str_replace($erg[1][$i], $pstring.$i.$pstring, $text); }

		$text = htmlspecialchars($text);
		$lines = explode("\n", $text);
		$c_mlength = $row->maxlengthword;
		for($n=0;$n<count($lines);$n++) {
			$words = explode(" ",$lines[$n]);
			$pstringount_w = count($words)-1;
			if($pstringount_w >= 0) {
				for($i=0;$i<=$pstringount_w;$i++) {
					$max_length_word = $c_mlength;
					$tword = trim($words[$i]);
					$tword = preg_replace("/\[(.*?)\]/si", "", $tword);
					$displaybox = substr_count($tword, "http://") + substr_count($tword, "https://") + substr_count($tword, "www.") + substr_count($tword, "ftp://");
					if($displaybox > 0) {
						$max_length_word = 200;
					}
					if(strlen($tword)>$max_length_word) {
						$words[$i] = chunk_split($words[$i], $max_length_word, "<br>");
						$length = strlen($words[$i])-5;
						$words[$i] = substr($words[$i],0,$length);
					}
				}
				$lines[$n] = implode(" ", $words);
			} else {
				$lines[$n] = chunk_split($lines[$n], $max_length_word, "<br>");
			}
		}
		$text = implode("\n", $lines);
		$text = nl2br($text);
		$text = preg_replace("#\[color=(\#?[\da-fA-F]{6}|[a-z\ \-]{3,})\](.*?)\[/color\]+#i","<span style=\"color:\\1\">\\2</span>",$text);
		$text = preg_replace("#\[size=()?(.*?)\](.*?)\[/size\]#si", "<font size=\"\\2\">\\3</font>", $text);
		$text = preg_replace("#\[face=()?(.*?)\](.*?)\[/face\]#si", "<span style=\"font-family:\\2\">\\3</span>", $text);
		$text = preg_replace("#\[font=()?(.*?)\](.*?)\[/font\]#si", "<span style=\"font-family:\\2\">\\3</span>", $text);
		$text = preg_replace("!\[(?i)b\]!", "<b>", $text);
		$text = preg_replace("!\[/(?i)b\]!", "</b>", $text);
		$text = preg_replace("!\[(?i)u\]!", "<u>", $text);
		$text = preg_replace("!\[/(?i)u\]!", "</u>", $text);
		$text = preg_replace("!\[(?i)i\]!", "<i>", $text);
		$text = preg_replace("!\[/(?i)i\]!", "</i>", $text);
		$text = preg_replace("!\[(?i)url\](http://|ftp://)(.*?)\[/(?i)url\]+!", "<a href=\"\\1\\2\" target=\"_blank\">\\1\\2</a>", $text);
		$text = preg_replace("!\[(?i)url\](.*?)\[/(?i)url\]+!", "<a href=\"http://\\1\" target=\"_blank\">\\1</a>", $text);
		$text = preg_replace("#\[url=(http://)?(.*?)\](.*?)\[/url\]#si", "<A HREF=\"http://\\2\" TARGET=\"_blank\">\\3</A>", $text);
		$text = preg_replace("!\[(?i)email\]([a-zA-Z0-9-._]+@[a-zA-Z0-9-.]+)\[/(?i)email\]!", "<a href=\"mailto:\\1\">\\1</a>", $text);
		$text = preg_replace("#\[email=()?(.*?)\](.*?)\[/email\]#si", "<A HREF=\"mailto:\\2\">\\3</A>", $text);
		$text = preg_replace("!\[(?i)img\]([_-a-zA-Z0-9:/\?\[\]=.@-]+)\[(?i)/img\]!", "<img src=\"\\1\" border=\"0\" alt=\"\" />", $text);
		$text = preg_replace("!\[(?i)IMG\]([_-a-zA-Z0-9:/\?\[\]=.@-]+)\[(?i)/IMG\]!", "<img src=\"\\1\" border=\"0\" alt=\"\" />", $text);
		$text = preg_replace("/\[code\](.*?)\[\/code\]/si",str_replace("%%boxtitle%%", '���', $head).'\\1'.$foot, $text);
		$text = preg_replace("!\[(?i)quote\]!", str_replace("%%boxtitle%%", '������', $head_quote), $text);
		$text = preg_replace("!\[/(?i)quote\]!", $foot_quote, $text);
		$text = preg_replace("/\[list\](.*?)\[\/list\]/si","<ul>\\1</ul>", $text);
		$text = preg_replace("/\[list=(.*?)\](.*?)\[\/list\]/si","<ol type=\"\\1\">\\2</ol>", $text);
		$text = preg_replace("/\[\*\](.*?)\\n/si","<li>\\1</li>", $text);


		for($i=0;$i<count($erg[1]);$i++) {
			ob_start();
			@highlight_string(trim($erg[1][$i]));
			$highlight_string = ob_get_contents();
			ob_end_clean();
			$displaybox = str_replace("%%boxtitle%%", "PHP", $head) . $highlight_string.$foot;
			$text = str_replace("[PHP]", "[php]", $text);
			$text = str_replace("[/PHP]", "[/php]", $text);
			$text = str_replace("[php]" . $pstring . $i . $pstring . "[/php]",$displaybox,$text);
		}
		return $text;
	}

	// ====================================================================
	// <<-- URLS AUTOMATISCH UMWANDELN -->>
	// ====================================================================
	function parseurl($text)
	{
		$urlsearch[]="/([^]_a-z0-9-=\"'\/])((https?|ftp):\/\/|www\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\};<>]*)/si";
		$urlsearch[]="/^((https?|ftp):\/\/|www\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\};<>]*)/si";
		$urlreplace[]="\\1[URL]\\2\\4[/URL]";
		$urlreplace[]="[URL]\\1\\3[/URL]";
		$emailsearch[]="/([\s])([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,}))/si";
		$emailsearch[]="/^([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,}))/si";
		$emailreplace[]="\\1[EMAIL]\\2[/EMAIL]";
		$emailreplace[]="[EMAIL]\\0[/EMAIL]";
		$text = preg_replace($urlsearch, $urlreplace,$text);
		if (strpos($text, "@")) $text = preg_replace($emailsearch, $emailreplace, $text);
		return $text;

	}

	// ====================================================================
	// <<-- BB- CODES SEITE -->>
	// ====================================================================
	function kcodes($text)
	{
		if (BBCODESITE==1){
			$text = $this->pre_kcodes($text);
		} else {
			$text = nl2br(kspecialchars($text));
		}
		return $text;
	}


	function msg($msg='', $goto='', $tpl='')
	{
		$goto = ($goto=='') ? 'index.php?module=forums' : $goto;
		$msg = str_replace('%%GoTo%%', $goto, $msg);
		$GLOBALS['tmpl']->assign("t_path", T_PATH);
		$GLOBALS['tmpl']->assign("GoTo", $goto);
		$GLOBALS['tmpl']->assign("content", $msg);
		$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'redirect.tpl');
		define("MODULE_CONTENT", $tpl_out);
		define("MODULE_SITE", $GLOBALS['mod']['config_vars']['NewThread']);
		echo $tpl_out;
		exit;
	}

	function fetchuserid($name)
	{
		$sql = $GLOBALS['db']->Query("SELECT BenutzerId FROM " . PREFIX . "_modul_forum_userprofile WHERE BenutzerName='" . $name . "'");
		$row = $sql->fetchrow();
		$uid = $row->BenutzerId;
		$sql->close();
		return $uid;
	}

	function ForumStats()
	{
		$sql_threads = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_forum_topic");
		$num_threads = $sql_threads->numrows();

		$sql_posts = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_forum_post");
		$num_posts = $sql_posts->numrows();

		$sql_members = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_users WHERE Status='1'  AND Benutzergruppe!=2");
		$num_members = $sql_members->numrows();

		$sql_newest_member = $GLOBALS['db']->Query("SELECT
			u.Id as UserId,
			m.BenutzerName as `UserName`
		FROM
			" . PREFIX . "_users as u,
			" . PREFIX . "_modul_forum_userprofile as m
		WHERE
			u.Status='1' AND
			u.Benutzergruppe!=2 AND
			m.BenutzerId = u.Id
		ORDER BY
			u.Id DESC LIMIT 1");
		$row_newest_member = $sql_newest_member->fetchrow();

		$sql_lastpost = $GLOBALS['db']->Query("SELECT forum_id FROM " . PREFIX . "_modul_forum_topic ORDER BY last_post DESC LIMIT 1");
		$row_lastpost = $sql_lastpost->fetchrow();


		$loggeduser = array();
		$gosts = array();

		$sql_guests = $GLOBALS['db']->Query("SELECT DISTINCT ip FROM " . PREFIX . "_modul_forum_useronline WHERE uname='UNAME'");
		$num_guests = $sql_guests->numrows();

		$q = "SELECT
			DISTINCT u.ip,
			u.uname,
			u.uid,
			ug.Benutzergruppe,
			up.ZeigeProfil
		FROM
			" . PREFIX . "_modul_forum_useronline as u,
			" . PREFIX . "_users as ug,
			" . PREFIX . "_modul_forum_userprofile as up
		WHERE
			u.uname!='UNAME' AND
			u.invisible!='INVISIBLE' AND
			ug.Id = u.uid AND
			up.BenutzerId = u.uid
			";
		$sql_user = $GLOBALS['db']->Query($q);
		#echo $q;

		$num_user = $sql_user->numrows();

		$sql_gosts = $GLOBALS['db']->Query("SELECT DISTINCT uname FROM " . PREFIX . "_modul_forum_useronline WHERE invisible = 'INVISIBLE' and uname!='UNAME'");
		$num_gosts = $sql_gosts->numrows();

		while ($row_user = $sql_user->fetchrow())
		{
			array_push($loggeduser, $row_user);
		}


		$GLOBALS['tmpl']->assign('loggeduser', $loggeduser);
		$GLOBALS['tmpl']->assign('gosts', $gosts);
		$GLOBALS['tmpl']->assign('num_guests', $num_guests);
		$GLOBALS['tmpl']->assign('num_user', $num_user);
		$GLOBALS['tmpl']->assign('num_gosts', $num_gosts);

		$GLOBALS['tmpl']->assign('LastPost', @$this->getLastForumPost($row_lastpost->forum_id));
		$GLOBALS['tmpl']->assign('row_user', $row_newest_member);
		$GLOBALS['tmpl']->assign('num_members', $this->num_format($num_members));
		$GLOBALS['tmpl']->assign('num_posts', $this->num_format($num_posts));
		$GLOBALS['tmpl']->assign('num_threads', $this->num_format($num_threads));
		$GLOBALS['tmpl']->assign('forum_images', 'templates/' . T_PATH . '/modules/forums/');
		return  $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'stats_forums.tpl');
	}

	// ================================================================
	// downloadfunktion f�r pn
	// ================================================================
	function downloadfile($datstring, $datname, $dattype, $extra = "0")
	{
		$filetype = 'application/octet-stream';
		header('Content-Type: ' . $dattype);
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Content-Disposition: attachment; filename="' . $datname . '"');
		if ($extra != 1) header('Content-Length: ' . strlen($datstring));
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		echo $datstring;
		exit;
	}

	function isIgnored($uid)
	{
		$sql = $GLOBALS['db']->Query("SELECT IgnoreId FROM " . PREFIX . "_modul_forum_ignorelist WHERE IgnoreId = '$uid' AND BenutzerId = '" . UID . "' LIMIT 1" );
		$num = $sql->numrows();

		if($num == 1) return true;
		return false;
	}

	function pnUnreaded()
	{
		if(isset($_SESSION['cp_benutzerid']))
		{
			$sql = $GLOBALS['db']->Query("SELECT pnid FROM " . PREFIX . "_modul_forum_pn WHERE to_uid = '" . $_SESSION['cp_benutzerid'] . "' AND  is_readed != 'yes' AND typ='inbox'" );
			return $sql->numrows();
		}
	}

	function pnReaded()
	{
		if(isset($_SESSION['cp_benutzerid']))
		{
			$sql = $GLOBALS['db']->Query("SELECT pnid FROM " . PREFIX . "_modul_forum_pn WHERE to_uid = '" . $_SESSION['cp_benutzerid'] . "' AND  is_readed = 'yes' AND typ='inbox'" );
			return $sql->numrows();
		}
	}

	function popSearch()
	{
		$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'search.tpl');
		return addslashes($tpl_out);
	}

	//=======================================================
	// Daten aus Koobi �bertragen
	//=======================================================
	function importfromkoobi()
	{
		define("IMPORT", 1);
		include_once(BASE_DIR . "/modules/forums/internals/importfromkoobi.php");
	}


		//=======================================================
	// Automatisches Update wenn f�r Gruppe keine Rechte bestehen
	//=======================================================
	function AutoUpdatePerms()
	{
		// Wenn eine Gruppe in Gruppenberechtigung noch nicht angelegt wurde, anlegen!
		$sql = $GLOBALS['db']->Query("SELECT Benutzergruppe  FROM " . PREFIX . "_user_groups");
		while($row = $sql->fetchrow())
		{
			$sql2 = $GLOBALS['db']->Query("SELECT Benutzergruppe  FROM " . PREFIX . "_modul_forum_grouppermissions WHERE Benutzergruppe = '$row->Benutzergruppe'");
			$num = $sql2->numrows();
			if($num<1)
			{
				$GLOBALS['db']->Query("
				INSERT INTO
					" . PREFIX . "_modul_forum_grouppermissions
				(
					Id,
					Benutzergruppe,
					Rechte,
					MAX_AVATAR_BYTES,
					MAX_AVATAR_HEIGHT,
					MAX_AVATAR_WIDTH,
					UPLOADAVATAR,
					MAXPN,
					MAXPNLENTH,
					MAXLENGTH_POST,
					MAXATTACHMENTS,
					MAX_EDIT_PERIOD
				) VALUES (
					'',
					'$row->Benutzergruppe',
					'own_avatar|canpn|accessforums|cansearch|last24|changenick|userprofile',
					'10240',
					'90',
					'90',
					'1',
					'50',
					'5000',
					'10000',
					'5',
					'672'
				)");
			}
		}
	}



}

//=======================================================
//=======================================================
// Ende der Klasse
// Start - Sonstige Funktionen
//=======================================================
function cpEncode($param)
{
	return base64_encode($param['val']);
}

function cpDecode($param)
{
	return base64_decode($param['val']);
}

function is_mod($fid)
{
global $db;
	$sql = $GLOBALS['db']->Query("SELECT user_id FROM " . PREFIX . "_modul_forum_mods WHERE forum_id = '$fid'");
	while ($row = $sql->fetchrow())
	{
		$mods[] = $row->user_id;
	}
	$user_is_mod = (@in_array(UID, $mods)) ? 1 : 0;

	if ((UGROUP == 1) || ($user_is_mod == 1))
	{
		return true;
	} else {
		return false;
	}
}


function getPostIcon($params)
{
	global $set;
	extract($params);
	$sql = $GLOBALS['db']->Query("SELECT path FROM ".PREFIX."_modul_forum_posticons WHERE active='1' and id='$icon'");
	$row = $sql->fetchrow();
	if($row != "0") return "<img src=\"templates/".T_PATH."/modules/forums/posticons/$row->path\" alt=\"$icon\" />";

}
?>