<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("SHOWTOPIC")) exit;

$_REQUEST['page'] = (isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) && $_REQUEST['page'] > 0) ? $_REQUEST['page'] : 1;


$printlink = redir()."&amp;print=1";
$post_count_extra = "";
$post_query_extra = "";

// alle postings f�r einen thread anzeigen
if ( isset($_GET['toid']) && $_GET['toid'] != "" )
{
	// gibt es den topic und das posting ueberhaupt schon
	if (!$this->topicExists(addslashes($_GET['toid'])))
	{
		$this->msg($GLOBALS['mod']['config_vars']['ErrorTopicWrong']);
	}
	
	$navigation = $this->getNavigation((int)$_GET['toid'], "topic");
	
	// ueberpruefen ob das forum passwortgeschuetzt ist
	// und ob der benutzer sich schon angemeldet hat
	$q_pass = "
		SELECT 
		f.id,
		f.password 
		FROM 
		" . PREFIX . "_modul_forum_forum AS f,
		" . PREFIX . "_modul_forum_topic AS t
		WHERE 
		t.id = '" . (int)$_GET['toid'] . "' AND
		t.forum_id = f.id
	";
	
	$r_pass = $GLOBALS['db']->Query($q_pass);
	$pass = $r_pass->fetchrow();
	$ForumId = $pass->id;
	$pass_b = false;

	if ($pass->password != "")
	{
		$p1 = @$_SESSION["f_pass_id_" . addslashes($pass->id)];
		$p2 = $pass->password;
		if( $p1 == $p2 )
		{
			$pass_b = true;
		} else {
			$GLOBALS['tmpl']->assign("fid", addslashes(@$_REQUEST['fid']));
			$GLOBALS['tmpl']->assign("navigation", $navigation);
			
			$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'forumlogin.tpl');
			define("MODULE_CONTENT", $tpl_out);	
			define("MODULE_SITE",  strip_tags($navigation));
		}
	} else {
		$pass_b = true;
	}
	
	if ($pass_b)
	{
		// ====================================================================================
		// zugriffsrechte
		// ====================================================================================
		$group_id = array();
		
		if (!@is_numeric(UID) || UGROUP == 2)
		{
			$group_id[] = UGROUP;
		} else {
			$query = "SELECT GroupIdMisc FROM " . PREFIX . "_modul_forum_userprofile WHERE BenutzerId = '" . UID . "'";
			$result = $GLOBALS['db']->Query($query);
			$user = $result->fetchrow();
			
			if($user->GroupIdMisc != ""){
				$group_id_ = UGROUP . ";" . $user->GroupIdMisc;
				$group_id = @explode(";", $group_id_);
				
			} else {
				$group_id[] = UGROUP;
			}
		}
		
		
		$query = "
			SELECT
				c.group_id
			FROM
				" . PREFIX . "_modul_forum_category AS c,
				" . PREFIX . "_modul_forum_forum AS f
			WHERE
				f.id = '" . $pass->id . "' AND
				f.category_id = c.id";
		
		$result = $GLOBALS['db']->Query($query);
		
		
		$category = $result->fetchrow();
		$category->group_id = @explode(",", $category->group_id);
		
		if (!array_intersect($group_id, $category->group_id))
		{
			$this->msg($GLOBALS['mod']['config_vars']['ErrornoPerm']);
		}
		
		$permissions = $this->getForumPermissionsByUser($pass->id, UID);

		if ($permissions[FORUM_PERMISSION_CAN_SEE] != 1)
		{
			$this->msg($GLOBALS['mod']['config_vars']['ErrornoPerm']);
		}	
		
		// ====================================================================================
		
		if(!is_mod(@$_GET['fid'])) { 
			$post_count_extra .= " AND opened = 1 ";
		}
		
		// freischalten
		if(isset($_REQUEST['open']) && $_REQUEST['open'] == 1)
		{
			if(is_mod(addslashes($_REQUEST['fid']))) 
			{ 
				if(isset($_REQUEST['ispost']) && $_REQUEST['ispost'] == 1)
				{
					$sql_p = "UPDATE " . PREFIX . "_modul_forum_post SET opened = '1' WHERE id='$_REQUEST[post_id]'";
					$res_p = $GLOBALS['db']->Query($sql_p);
					
					// mail an autor eines beitrages
					$link = "http://" . $_SERVER['HTTP_HOST'] . str_replace("/index.php","",$_SERVER['PHP_SELF']) . "/index.php?module=forums&show=showtopic&toid=$_REQUEST[toid]&pp=15";
					$sql = $GLOBALS['db']->Query("SELECT uid FROM ".PREFIX."_modul_forum_post WHERE id = '$_REQUEST[post_id]'");
					$row = $sql->fetchrow();
					
					$sql_2 = $GLOBALS['db']->Query("SELECT BenutzerName,Email FROM ".PREFIX."_modul_forum_userprofile WHERE BenutzerId = '$row->uid'");
					$row_2 = $sql_2->fetchrow();
					
					$body = str_replace("%%USER%%", $row_2->BenutzerName, $GLOBALS['mod']['config_vars']['BodyToUserAfterMod']);
					$body = str_replace("%%LINK%%", $link, $body);
					$body = str_replace("%%N%%","\n", $body);
					
					$globals = new Globals;
					$GLOBALS['globals']->cp_mail($row_2->Email, stripslashes($body), $GLOBALS['mod']['config_vars']['SubjectToUserAfterMod'], FORUMEMAIL, FORUMABSENDER, "text", "");
				} else {
					
					$sql_t = "UPDATE " . PREFIX . "_modul_forum_topic SET opened = '1' WHERE id='$_REQUEST[toid]'";
					$sql_p = "UPDATE " . PREFIX . "_modul_forum_post SET opened = '1' WHERE topic_id='$_REQUEST[toid]'";
					$res_t = $GLOBALS['db']->Query($sql_t);
					$res_p = $GLOBALS['db']->Query($sql_p);
					// mail an autor eines thema
					$link = "http://" . $_SERVER['HTTP_HOST'] . str_replace("/index.php","",$_SERVER['PHP_SELF']) . "/index.php?module=forums&show=showtopic&toid=$_REQUEST[toid]&fid=$_REQUEST[fid]";
					$sql = $GLOBALS['db']->Query("SELECT uid FROM ".PREFIX."_modul_forum_topic WHERE id = '$_REQUEST[toid]'");
					$row = $sql->fetchrow();
					
					$sql_2 = $GLOBALS['db']->Query("SELECT BenutzerName,Email FROM ".PREFIX."_modul_forum_userprofile WHERE BenutzerId = '$row->uid'");
					$row_2 = $sql_2->fetchrow();
					
					$body = str_replace("%%USER%%", $row_2->BenutzerName, $GLOBALS['mod']['config_vars']['BodyToUserAfterMod']);
					$body = str_replace("%%LINK%%", $link, $body);
					$body = str_replace("%%N%%","\n", $body);
					
					$globals = new Globals;
					$GLOBALS['globals']->cp_mail($row_2->Email, stripslashes($body), $GLOBALS['mod']['config_vars']['SubjectToUserAfterMod'], FORUMEMAIL, FORUMABSENDER, "text", "");
				}
			}
		}
		
		// ======================================================
		// alle beitr�ge holen
		$post_count = "SELECT 
			id
		FROM " . 
			PREFIX . "_modul_forum_post
		WHERE 
			topic_id = '" . addslashes($_GET['toid']) . "' 
			$post_count_extra 
			ORDER BY
			id DESC
		";
		
		$post_count_result = $GLOBALS['db']->Query($post_count);
		$num = $post_count_result->numrows();
		
		if($num < 1)
		{
			$this->msg($GLOBALS['mod']['config_vars']['ErrornoPerm']);
		}
		
		$this->Cpengine_Board_SetTopicRead(addslashes($_GET['toid']));
		
		// ===================================================================
		$limit = (isset($_REQUEST['pp']) && is_numeric($_REQUEST['pp']) && $_REQUEST['pp'] > 0 ) ? $_REQUEST['pp'] : 15;
		$limit = (isset($_REQUEST['print']) && $_REQUEST['print']==1) ? 10000 : $limit;
		
		if(!isset($page)) $page = 1;
		$seiten = $this->getPageNum($num, $limit);
		$a = prepage() * $limit - $limit;
		$a = (isset($_REQUEST['print']) && $_REQUEST['print']==1) ? 0 : $a;
		
		if(!is_mod(@$_GET['fid']))
		{ 
			$post_query_extra .= " AND opened = 1 ";
		}
		$post_query = "
		SELECT 
			p.id, 
			p.title, 
			p.message, 
			p.datum, 
			p.use_bbcode, 
			p.use_smilies, 
			p.use_sig, 
			p.uid, 
			p.attachment,
			p.opened
		FROM 
			" . PREFIX . "_modul_forum_post AS p 
		WHERE topic_id = '" . addslashes($_GET['toid']) . "' 
			$post_query_extra 
		ORDER BY 
			datum ASC
		LIMIT $a, $limit";
		
	
		$post_result = $GLOBALS['db']->Query($post_query);
		$post_array = array();
		$current_user = null;
		
		if (($_SESSION['cp_ugroup'] != 2))
		{
			$q_current_user = "SELECT BenutzerId, Emailempfang, Pnempfang FROM " . PREFIX . "_modul_forum_userprofile WHERE BenutzerId = '".UID."'";
			$r_current_user = $GLOBALS['db']->Query($q_current_user);
			$current_user = $r_current_user->fetchRow();
		}
		
		while ($post = $post_result->fetchrow())
		{
			
			$Attach = array();
			// der beitragverfasser
			$q_user = "
			SELECT 
				u.Avatar,
				u.AvatarStandard, 
				u.BenutzerName, 
				u.Email, 
				u.Webseite, 
				u.Registriert, 
				u.Signatur, 
				u.Unsichtbar,
				u.BenutzerId, 
				us.Vorname,  
				us.Benutzergruppe,  
				us.Nachname,  
				ug.Name  
			FROM
				" . PREFIX . "_modul_forum_userprofile AS u,
				" . PREFIX . "_users AS us,
				" . PREFIX . "_user_groups AS ug 
			WHERE 
				u.BenutzerId = '" . $post->uid . "' AND 
				us.Id = u.BenutzerId AND 
				ug.Benutzergruppe = us.Benutzergruppe AND 
				us.Status = 1
				";

			$r_user = $GLOBALS['db']->Query($q_user);
			$poster = $r_user->fetchRow();
			$query = "SELECT COUNT(id) AS count FROM " . PREFIX . "_modul_forum_post WHERE uid = '" . @$poster->BenutzerId . "'";
			$result = $GLOBALS['db']->Query($query);
			$postings = $result->fetchrow();
			$poster->user_posts = $postings->count;
		
			$query = "SELECT title, count FROM " . PREFIX . "_modul_forum_rank WHERE count < '" . $poster->user_posts . "' ORDER BY count DESC LIMIT 1";
			$result = $GLOBALS['db']->Query($query);
			$rank = $result->fetchrow();
			$poster->avatar = $this->getAvatar( @$poster->Benutzergruppe, @$poster->Avatar, @$poster->AvatarStandard);
			$poster->regdate = @$poster->Registriert;
			$poster->user_sig = $this->kcodes(@$poster->Signatur);
			$poster->rank = @$rank->title;
			$poster->OnlineStatus = @$this->getonlinestatus(@$poster->BenutzerName);
			
			$popUpInsert = addslashes ("<div style='padding:6px; line-height:1.5em'> &raquo; <a class='forum_links_small' href='index.php?module=forums&amp;show=userprofile&amp;user_id=".@$poster->BenutzerId."'>".$GLOBALS['mod']['config_vars']['ShowPosterProfile']."</a><br /> &raquo; <a class='forum_links_small' href='index.php?module=forums&amp;show=ignorelist&amp;insert=".@$poster->BenutzerId."'>".$GLOBALS['mod']['config_vars']['InsertIgnore']."</a><br />&raquo; <a class='forum_links_small' href='index.php?module=forums&amp;show=pn&action=new&amp;to=".base64_encode(@$poster->BenutzerName)."'>".$GLOBALS['mod']['config_vars']['UserSendPn']."</a></div>");
			$popUpRemove = "<div style='padding:6px; line-height:1.5em'> &raquo; <a class='forum_links_small' href='index.php?module=forums&amp;show=userprofile&amp;user_id=".@$poster->BenutzerId."'>".$GLOBALS['mod']['config_vars']['ShowPosterProfile']."</a><br /> &raquo; <a class='forum_links_small' href='index.php?module=forums&amp;show=ignorelist&amp;remove=".@$poster->BenutzerId."'>".$GLOBALS['mod']['config_vars']['RemoveIgnore']."</a><br />&raquo; <a class='forum_links_small' href='index.php?module=forums&amp;show=pn&action=new&amp;to=".base64_encode(@$poster->BenutzerName)."'>".$GLOBALS['mod']['config_vars']['UserSendPn']."</a></div>";
			
			$poster->Ignored = (($this->isIgnored(addslashes(@$poster->BenutzerId))) ? $popUpRemove : $popUpInsert);
			
			if(defined("SMILIES") && SMILIES==1) $poster->user_sig = $this->replaceWithSmileys($poster->user_sig);
			
			// der verfasser
			$post->poster = $poster;
			
			// soll bbcode verwendet werden
			if ($post->use_bbcode == 1)
			{
				$post->message = $this->kcodes($post->message);
			} else {
				$post->message = nl2br($post->message);
			}
			
			// sollen smilies angezeigt werden
			if ( ($post->use_smilies == 1) && (SMILIES==1) ) {
				$post->message = $this->replaceWithSmileys($post->message);
			}
			
			$post->message = (!$this->badwordreplace($post->message)) ? $post->message : $this->badwordreplace($post->message);
			$post->message = $this->high($post->message);

			$post->files = array();		
			// attachments
			if ($post->attachment != "")
			{
				
				$attachments = @explode(";", $post->attachment);
				$sub_query = @implode(" || id = ", $attachments);
				
				$q_file = "
				SELECT
				id,
				hits,
				orig_name,
				filename
				FROM
				" . PREFIX . "_modul_forum_attachment
				WHERE
				 id = $sub_query
				";
				
				$r_file = $GLOBALS['db']->Query($q_file);
				
				
				while ($file = $r_file->fetchrow())
				{
					$file->FileSize = $this->get_attachment_size($file->filename);
					array_push($Attach, $file);
				}
			}
			//$post->UserStatus = $this->getonlinestatus();
			
			$post->Attachments = $Attach;
			//if(!empty($poster->BenutzerId)) 
			array_push($post_array, $post);
		}
		$all_posts = $post_array;
		
		
		$GLOBALS['tmpl']->assign('files', $Attach);
		
		// anzahl der besichtigungen erh�hen
		$increment_query = $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_forum_topic SET views = views + 1 WHERE id = '" . addslashes($_REQUEST['toid']). "'");

		// daten des aktuellen topics holen
		$topic_result = $GLOBALS['db']->Query("SELECT  notification, id, title, status, forum_id, uid FROM " . PREFIX . "_modul_forum_topic WHERE id = '" . addslashes($_REQUEST['toid']) . "'");
		$topic = $topic_result->fetchRow();
		
		// hat der user das thema abonniert?
		$user_id = split(";", $topic->notification);
		if (@in_array(UID, $user_id)) {
			$GLOBALS['tmpl']->assign('canabo', 0);
		} else {
			$GLOBALS['tmpl']->assign('canabo', 1);
		}
		
		
		// hat der user schon abgestimmt?
		$sql = $GLOBALS['db']->Query("SELECT uid,ip FROM " . PREFIX . "_modul_forum_rating WHERE  topic_id = '".(int)$_GET['toid']."' ");
		$row_ip = $sql->fetchrow();
		$v_uid = @explode(",", $row_ip->uid);
		$ip = @explode(",", $row_ip->ip);
		
		if ( (!@in_array(UID, $v_uid)) && (!@in_array($_SERVER['REMOTE_ADDR'], $ip)) )
		{
			$GLOBALS['tmpl']->assign('display_rating', 1);
		}
		
		// nextes thema holen
		$query = "SELECT id FROM " . PREFIX . "_modul_forum_topic WHERE id < '" . (int)$_GET['toid'] . "' AND forum_id = '" . $pass->id . "' ORDER BY id DESC LIMIT 1";
		$result = $GLOBALS['db']->Query($query);
		$next_topic = $result->fetchrow();
		
		// vorheriges thema holen
		$query = "SELECT id FROM " . PREFIX . "_modul_forum_topic WHERE id > '" .  (int)$_GET['toid'] . "' AND forum_id = '" . $pass->id . "' ORDER BY id ASC LIMIT 1";
		$result = $GLOBALS['db']->Query($query);
		$prev_topic = $result->fetchrow();
		
		$topic->next_topic = $next_topic;
		$topic->prev_topic = $prev_topic;
		
		
		$sname = strip_tags($navigation) . " - " . stripslashes(htmlspecialchars($topic->title)) ;
		
		
		// navigation erzeugen
		$navigation = $this->getNavigation((int)$_GET['toid'], "topic");
		$tmp_navi = $navigation . $GLOBALS['mod']['config_vars']['ForumSep'] . $topic->title;
		
		$GLOBALS['tmpl']->assign("navigation", $tmp_navi);
		$GLOBALS['tmpl']->assign("treeview", @explode($GLOBALS['mod']['config_vars']['ForumSep'], $tmp_navi));
		// ende navigation
		
		if ($limit < $num)
		{
			$GLOBALS['tmpl']->assign('pages', 
				pagenav($seiten, 
				(isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) ? $_REQUEST['page'] : 1), 
				" <a class=\"page_navigation\" href=\"index.php?module=forums&amp;show=showtopic&amp;toid=" . $_GET["toid"] . "&amp;high=" . @$_GET['high'] . "&amp;pp=$limit&amp;page={s}&amp;fid=$ForumId\">{t}</a> ")
				);
		}
		
		if (UID == 1) array_fill(0, sizeof($permissions), 1);
		if(is_mod(@$_GET['fid'])) $GLOBALS['tmpl']->assign('ismod', 1);
		
		$categories = array();
		$this->getCategories(0, $categories, "");

		$this->Cpengine_Board_SetTopicRead(addslashes($_GET['toid']));
		
		$GLOBALS['tmpl']->assign("permissions", $permissions);
		$GLOBALS['tmpl']->assign("categories_dropdown", $categories);
		$GLOBALS['tmpl']->assign("navigation", $navigation);
		$GLOBALS['tmpl']->assign("next_site", $seiten);
		$GLOBALS['tmpl']->assign("currUser", $current_user);
		$GLOBALS['tmpl']->assign("printlink", $printlink);
		$GLOBALS['tmpl']->assign("topic", $topic);
		$GLOBALS['tmpl']->assign("postings", $all_posts);
		$GLOBALS['tmpl']->assign("referer", @$_SERVER['HTTP_REFERER']);
		$GLOBALS['tmpl']->register_function('getonlinestatus', 'getonlinestatus');
		
		
		$tpl = (isset($_REQUEST['print']) && $_REQUEST['print'] == 1) ? $this->_Print_ShowTopicTpl : $this->_ShowTopicTpl;
		$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . $tpl);
		
		define("MODULE_CONTENT", $tpl_out);	
		define("MODULE_SITE",  htmlspecialchars(stripslashes(strip_tags($tmp_navi))));
		
	}
	} else {
		header("Location:index.php?module=forums");
		exit;
}
?>