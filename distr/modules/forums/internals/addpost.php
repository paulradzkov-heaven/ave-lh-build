<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("ADDPOST")) exit;

$q_forum = "
	SELECT
		f.id,
		t.uid,
		t.title 
	FROM
		" . PREFIX . "_modul_forum_forum AS f,
		" . PREFIX . "_modul_forum_topic AS t
	WHERE
		t.id = '" . @$_POST['toid'] . "' AND
		t.forum_id = f.id
	";

$r_forum = $GLOBALS['db']->Query($q_forum);
$forum = $r_forum->fetchrow();

if(!is_object($forum))
{
	$this->msg($GLOBALS['mod']['config_vars']['ErrornoPerm']);
}

$TopicTitle = stripslashes($forum->title);

// =========================================================
// <<-- zugriffsrechte -->>
// =========================================================
$cat_query = $GLOBALS['db']->Query("SELECT group_id FROM " . PREFIX . "_modul_forum_forum WHERE id = '" . $forum->id . "'");
while ($category = $cat_query->fetchrow_assoc())
{
	// miscrechte
	include_once(BASE_DIR . "/modules/forums/internals/misc_ids.php");
	
	$groups = explode(",", $category['group_id']);
	if (array_intersect($group_ids, $groups))
	{
		$permissions = $this->getForumPermissionsByUser($forum->id, UID);
	}
}

// =========================================================

// ist user moderator
$is_moderator = false;

$query = "
	SELECT
		user_id
	FROM
		" . PREFIX . "_modul_forum_mods
	WHERE
		forum_id = '" . $forum->id . "'";


$result = $GLOBALS['db']->Query($query);

while ($user = $result->fetchrow()) {
	if ($user->user_id == UID) $is_moderator = true;
}

// wenn user nicht auf eigenes thema antworten kann
if ($forum->uid == UID) {
	if ($permissions[FORUM_PERMISSION_CAN_REPLY_OWN_TOPIC] == 0)
	{
		$this->msg($GLOBALS['mod']['config_vars']['ErrornoPerm']);
	}
} else {
	// kann nicht auf andere themen antworten
	if ($permissions[FORUM_PERMISSION_CAN_REPLY_OTHER_TOPIC] == 0 && UGROUP != 1 && !$is_moderator)
	{
		$this->msg($GLOBALS['mod']['config_vars']['ErrornoPerm']);
	}
}

$error_array = array();


// kein text eingegeben
if ($_POST["text"] == "")
{
	array_push($error_array, $GLOBALS['mod']['config_vars']['CommentMissing']);
}

// wenn fehler oder vorschau
if ( count($error_array) || ($_REQUEST['preview']==1) ) {
	
	$GLOBALS['tmpl']->assign("smilie", SMILIES);
	#$navigation = $this->getNavigation($_POST["toid"], "topic");
	
	$navigation = $this->getNavigation($_POST["toid"], "topic") 
		. $GLOBALS['mod']['config_vars']['ForumSep'] 
		. "<a class='forum_links_navi' href='index.php?module=forums&amp;show=showtopic&amp;toid=" . $_POST['toid'] . "&amp;fid=" . $_POST['fid']. "'>" . $TopicTitle . "</a>" 
		. $GLOBALS['mod']['config_vars']['ForumSep'] 
		. $GLOBALS['mod']['config_vars']['ReplyToPost'];
	
	// vorschau darstellen
	if($_REQUEST['preview']==1)
	{
		$preview_text = stripslashes($_REQUEST['text']);
		if ( ($_REQUEST['parseurl']) == 1) $preview_text = $this->parseurl($preview_text);
		if ( (BBCODESITE == 1) )
		{
			$preview_text = (isset($_REQUEST['disablebb']) && $_REQUEST['disablebb']==1) ? nl2br($preview_text) : $this->kcodes($preview_text);
		} else {
			$preview_text = nl2br($preview_text);
		}
		
		$preview_text = ((isset($_POST['disablesmileys']) && $_POST['disablesmileys']==1) || (SMILIES!=1)) ? $preview_text : $this->replaceWithSmileys($preview_text);
		
		
		// attachments anhaengen
		if(isset($_POST['attach_hidden']) && $_POST['attach_hidden']>=1)
		{ 
			foreach($_POST['attach_hidden'] as $attachment)
			{
				$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX ."_modul_forum_attachment WHERE id='$attachment'");
				$row_a = $sql->fetchrow();
				
				$h_attachments_only_show[] = '
				<div id="div_'.$row_a->id.'" style="display:">
				<input type="hidden" name="attach_hidden[]" id="att_'.$row_a->id.'" value="'.$row_a->id.'" />
				&bull; '.$row_a->orig_name.'
				<a href="javascript:;" 
				onclick="if(confirm(\''.$GLOBALS['mod']['config_vars']['DelAttach'].'\'))
				{
					document.getElementById(\'att_'.$row_a->id.'\').value=\'\';
					document.getElementById(\'div_'.$row_a->id.'\').style.display=\'none\';
					document.getElementById(\'hidden_count\').value = document.getElementById(\'hidden_count\').value - 1;
				}
				;"><img src="templates/' . T_PATH . '/modules/forums/forum/del_attach.gif" alt="" border="0" hspace="2" /></a>
				</div>';
			}
			
			$GLOBALS['tmpl']->assign("h_attachments_only_show", $h_attachments_only_show);
		}
		$GLOBALS['tmpl']->assign("permissions", $permissions);
		$GLOBALS['tmpl']->assign("pre_error", 1);
		$GLOBALS['tmpl']->assign("preview_text", $preview_text);
		$GLOBALS['tmpl']->assign("preview_text_form", stripslashes($_REQUEST['text']));
		$GLOBALS['tmpl']->assign("fid", $_POST['fid']);
		$GLOBALS['tmpl']->assign("toid", $_POST['toid']);

		$items = array();
		include (BASE_DIR . "/modules/forums/internals/addpost_last.php");
		$GLOBALS['tmpl']->assign("items", $items);
	}
	
	
	$GLOBALS['tmpl']->assign("topic_id", $_POST["toid"]);
	$GLOBALS['tmpl']->assign("navigation", $navigation);
	$GLOBALS['tmpl']->assign("bbcodes", BBCODESITE);
	$GLOBALS['tmpl']->assign("posticons", $this->getPosticons());
	$GLOBALS['tmpl']->assign("listemos", $this->listsmilies());
	$GLOBALS['tmpl']->assign("subject", stripslashes(@$_POST["subject"]));
	$GLOBALS['tmpl']->assign("message", $_POST["text"]);
	$GLOBALS['tmpl']->assign("listfonts",  $this->fontdropdown());
	$GLOBALS['tmpl']->assign("sizedropdown",  $this->sizedropdown());
	$GLOBALS['tmpl']->assign("colordropdown",  $this->colordropdown());
	$GLOBALS['tmpl']->assign("errors", $error_array);
	$GLOBALS['tmpl']->assign("forum_id", $_POST['fid']);
	$GLOBALS['tmpl']->assign("threadform", $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . "threadform.tpl"));
	$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'addtopic.tpl');
	define("MODULE_CONTENT", $tpl_out);	
	define("MODULE_SITE", $GLOBALS['mod']['config_vars']['NewThread']);
	
} else {
	
	$topic_id = $_POST["toid"];
	$title = ( $_POST["subject"] == "" ) ? '' : addslashes($_POST["subject"]);
	$message = substr($_POST["text"], 0, MAXLENGTH_POST);
	
	// automatisch url umwandeln
	if ($_POST["parseurl"]) { $message = $this->parseurl($message);}
	
	// ueberpruefung bbcodes
	$disable_bbcode  = (isset($_POST['disablebb']) && $_POST['disablebb']==1) ? 0 : 1;
	$disable_smilies = (isset($_POST['disablesmileys']) && $_POST['disablesmileys']==1) ? 0 : 1;
	$use_sig     = (isset($_POST['usesig']) && $_POST['usesig']==1) ? 1 : 0;
	$notification = (isset($_POST["notification"]) && $_POST["notification"]==1) ? 1 : 0;
	
	// abos auslesen
	$q_notification = "SELECT notification, forum_id FROM " . PREFIX . "_modul_forum_topic WHERE id = '$topic_id'";
	$r_notification = $GLOBALS['db']->Query($q_notification);
	$r_notification = $r_notification->fetchrow();
	
	$forum_id = $r_notification->forum_id;
	$user_ids = explode(";",$r_notification->notification);
	
	// attachments anhaengen
	if(isset($_POST['attach_hidden']) && $_POST['attach_hidden'] >= 1)
	{
		foreach($_POST['attach_hidden'] as $file){
			if($file!=""){
				$attached_files[] = $file;
			}
		}
		$attachments = @implode(";", $attached_files);
	} else {
		$attachments = @implode(";", $_POST['attachment']);
	}
	
	// wenn topic, keine posts
	$last_post_id = -1;
	
	// editieren
	if ($_POST['action'] == "edit") {
		
		$announce = "";
		$status = "";
		if($permissions[FORUM_PERMISSIONS_CAN_CHANGE_TOPICTYPE] == 1) {
			if($_REQUEST['subaction'] == "announce"){
				$announce = "type='100'";
			}
			if($_REQUEST['subaction'] == "attention"){
				$announce = "type='1'";
			}
		}
		
		if($permissions[FORUM_PERMISSIONS_CAN_CLOSE_TOPIC] == 1) {
			if($_REQUEST['subaction'] == "close"){
				$status = "status='1'";
			}
		}
		
		if( ($status!="") || ($announce!="") ){
			if($announce!="" && $status==1) $sep = ",";
			$sql = $GLOBALS['db']->Query("UPDATE ".PREFIX."_modul_forum_topic SET
			$announce 
			$sep 
			$status 
			WHERE
			id = '$_REQUEST[toid]'");
			
		}
		
		// befinden sich anhaenge am beitrag?
		$attachments = ($attachments!="") ? "attachment = '$attachments'," : "attachment = '',";
		
		$q_post_query = "
		UPDATE
		" . PREFIX . "_modul_forum_post
		SET
		$attachments
		title = '$title',
		message = '$message \n[size=2]Отредактировано: " . date("d.m.Y, H:i:s") . "[/size]',
		use_bbcode = '$disable_bbcode',
		use_smilies = '$disable_smilies',
		use_sig = '$use_sig'
		
		WHERE
		id = '" . $_POST['p_id'] ."'";
		
		// u.U. das Thema aendern
		$query = "
			SELECT
				t.uid,
				t.id
			FROM
				" . PREFIX . "_modul_forum_topic AS t,
				" . PREFIX . "_modul_forum_post AS p
			WHERE
				p.id = " . $_POST['p_id'] . " AND
				t.id = p.topic_id
		";
		
		$result = $GLOBALS['db']->Query($query);
		$topic = $result->fetchrow();
		
		// nur der themenstarter (admin und moderator auch) darf das thema aendern
		if ($topic->uid == UID || UGROUP == 1 || $is_moderator)
		{
			if ($_POST['topic'] != '') {
				$title = (isset($_POST['topic']) && !empty($_POST['topic'])) ? addslashes($_POST['topic']) : "";
				$query = "
					UPDATE
						" . PREFIX . "_modul_forum_topic
					SET
						title = '$title',
						posticon = '" . $_POST['posticon'] . "'
					WHERE
						id = '" . $topic->id . "'";
				
				$result = $GLOBALS['db']->Query($query);
			}
		}
		
		// neu einfuegen
	} else {
				
		// muessen beitraege moderiert werden?
		$sql = $GLOBALS['db']->Query("SELECT moderated_posts,post_emails FROM " . PREFIX . "_modul_forum_forum WHERE id = '$forum_id'");
		$row = $sql->fetchrow();
		
		$opened = ($row->moderated_posts == 1) ? 2 : 1;
		if(is_mod($forum_id)) { $opened = 1; }
		
		// aktionen
		if(@$permissions[FORUM_PERMISSIONS_CAN_CLOSE_TOPIC] == 1) {
			if($_REQUEST['subaction'] == "close"){
				$sql = $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_forum_topic SET status='1' WHERE id = '" . $topic_id ."'");
			}
		}
		
		if(@$permissions[FORUM_PERMISSIONS_CAN_CHANGE_TOPICTYPE] == 1) {
			if($_REQUEST['subaction'] == "announce"){
				$announce = "type='100'";
				$sql = $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_forum_topic SET type='100' WHERE id = '" . $topic_id ."'");
			}
			if($_REQUEST['subaction'] == "attention"){
				$sql = $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_forum_topic SET type='1' WHERE id = '" . $topic_id ."'");
			}
		}
		
		$attachment = ($_REQUEST['action']=="quote") ? '' : $attachments;
		
		// post eintragen
		$q_post_query = "
		INSERT INTO 
		" . PREFIX . "_modul_forum_post (
		id, 
		title, 
		message, 
		datum, 
		topic_id, 
		uid, 
		use_bbcode, 
		use_smilies, 
		use_sig,
		attachment,
		opened 
		
		)  VALUES (
		'', 
		'$title', 
		'$message', 
		NOW(), 
		'$topic_id', 
		'".UID."', 
		'$disable_bbcode', 
		'$disable_smilies', 
		'$use_sig',
		'$attachment',
		'$opened' 
		
		)";
		
		
		
		
		// letzten beitrag holen
		$q_last_post = "SELECT id FROM " . PREFIX . "_modul_forum_post WHERE topic_id = $topic_id ORDER BY id DESC";
		$r_last_post = $GLOBALS['db']->Query($q_last_post);
		$last_post = $r_last_post->fetchrow();
		$last_post_id = $last_post->id;
		
		
		$datum = date("d.m.Y H:i");
		// ================================================================
		// <<-- wenn im admin empfaenger fьr jeden post eingetragen wurden,
		//      werden hier nun die mails versendet. -->>
		// ================================================================
		if ($_POST['action'] != "edit") {
		
			if($row->post_emails != ""){
				$mails = @explode(",",$row->post_emails);
				
				// welche seite?
				$sql = $GLOBALS['db']->Query("SELECT topic_id FROM " . PREFIX . "_modul_forum_post WHERE topic_id = '$topic_id'");
				$count = $sql->numrows();
				$page = $this->getPageNum($count, 15);
				
				// link
				$link = "http://" . $_SERVER['HTTP_HOST'] . str_replace("/index.php","",$_SERVER['PHP_SELF']) . "/index.php?module=forums&show=showtopic&toid=$topic_id&pp=15&page=$page#pid_$last_post_id";
				
				$username = (UNAME == 'UNAME') ? $GLOBALS['mod']['config_vars']['Guest'] : $this->getUserName($_SESSION['cp_benutzerid']);
				$body_msg = ($opened==2) ? $GLOBALS['mod']['config_vars']['BodyNewPostEmailMod'] : $GLOBALS['mod']['config_vars']['BodyNewPostEmail'];
				$subject_msg = ($opened==2) ? $GLOBALS['mod']['config_vars']['SubjectNewPostEmailMod'] : $GLOBALS['mod']['config_vars']['SubjectNewPostEmail'];
				
				$body = $body_msg;
				$body = str_replace("%%DATUM%%", $datum, $body);
				$body = str_replace("%%USER%%", $username, $body);
				$body = str_replace("%%SUBJECT%%", $title, $body);
				$body = str_replace("%%LINK%%", $link, $body);
				$body = str_replace("%%MESSAGE%%", $message, $body);
				$body = str_replace("%%N%%", "\n", $body);
				
				foreach ($mails as $send_mail)
				{
					$globals = new Globals;
					$GLOBALS['globals']->cp_mail($send_mail, stripslashes($body), $subject_msg, FORUMEMAIL, FORUMABSENDER, "text", "");
				
				}
			}
		}
		// ================================================================
		// <<-- EMAILS AN ABONNENTEN -->>
		// ================================================================
		// welche seite?
		$sql = $GLOBALS['db']->Query("SELECT topic_id FROM " . PREFIX . "_modul_forum_post WHERE topic_id = '$topic_id'");
		$count = $sql->numrows();
		$page = $this->getPageNum($count, 15);
		
		// link
		$link = "http://" . $_SERVER['HTTP_HOST'] . str_replace("/index.php","",$_SERVER['PHP_SELF']) . "/index.php?module=forums&show=showtopic&toid=$topic_id&pp=15&page=$page#pid_$last_post_id";
		$users = @explode(";", $r_notification->notification);
		$globals = new Globals;
		foreach ($users as $mail_to)
		{
			if ($mail_to != "")
			{
				$sql = $GLOBALS['db']->Query ("SELECT BenutzerName,Email FROM " . PREFIX . "_modul_forum_userprofile WHERE BenutzerId = '$mail_to'");
				$row_u = $sql->fetchrow();
				
				$Autor = (UNAME == 'UNAME') ? $GLOBALS['mod']['config_vars']['Guest'] : $this->getUserName($_SESSION['cp_benutzerid']);
				
				$n_body = $GLOBALS['mod']['config_vars']['BodyNewPostToUser'];
				$n_body = str_replace("%%DATUM%%", $datum, $n_body);
				$n_body = str_replace("%%USER%%", @$row_u->UserName, $n_body);
				$n_body = str_replace("%%AUTOR%%", $Autor, $n_body);
				$n_body = str_replace("%%SUBJECT%%", $title, $n_body);
				$n_body = str_replace("%%LINK%%", $link, $n_body);
				$n_body = str_replace("%%MESSAGE%%", $message, $n_body);
				$n_body = str_replace("%%N%%", "\n", $n_body);
				$GLOBALS['globals']->cp_mail($row_u->Email, stripslashes($n_body), $GLOBALS['mod']['config_vars']['SubjectNewPostEmail'], FORUMEMAIL, FORUMABSENDER, "text", "");
			}
		}
	}
	
	if ($notification)
	{
		// moechte user benachrichtigt werden?
		if (!in_array(UID,$user_ids))
		{
			$q_newrec = "UPDATE " . PREFIX . "_modul_forum_topic SET notification = CONCAT(notification, ';', '".UID."') WHERE id = '$topic_id'";
			$r_newrec = $GLOBALS['db']->Query($q_newrec);
		}
	} else {
		// ansonsten user aus notification entfernen
		$sql = $GLOBALS['db']->Query("SELECT notification FROM " . PREFIX . "_modul_forum_topic WHERE id = '$topic_id'");
		$row = $sql->fetchrow();
		$new = @str_replace(";" . UID, "", $row->notification);
		$sql = $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_forum_topic SET notification='$new' WHERE id = '$topic_id'");
	}
	
	$db_result = $new_post_result = $GLOBALS['db']->Query($q_post_query);
	$new_id = $GLOBALS['db']->InsertId();
	
	// FEHLER
	if (!$db_result)
	{
		$this->msg($GLOBALS['mod']['config_vars']['ErrornoPerm']);
	}
    
    
    if ($_POST['action'] != 'edit')
	{
    	// POST erfolgreich eingetragen
    	$q_post_increment = "UPDATE " . PREFIX . "_modul_forum_userprofile SET Beitraege = Beitraege + 1 WHERE BenutzerId = '".UID."'";
    	$r_post_increment = $GLOBALS['db']->Query($q_post_increment);
    	
    	$q_reply_increment = "UPDATE " . PREFIX . "_modul_forum_topic SET replies = replies + 1, last_post = NOW(), last_post_int ='".time()."' WHERE id = '".$topic_id."'";
    	$r_reply_increment = $GLOBALS['db']->Query($q_reply_increment);
    	
    	$q_update_forums = "UPDATE " . PREFIX . "_modul_forum_forum SET last_post = NOW(), last_post_id = ".$new_id." WHERE id = '".$forum_id."'";        
    	$r_update_forums = $GLOBALS['db']->Query($q_update_forums);
    }

    $this->Cpengine_Board_SetTopicRead($topic_id);
	
	$sql = $GLOBALS['db']->Query("SELECT topic_id FROM " . PREFIX . "_modul_forum_post WHERE topic_id = '".$topic_id."'");
	$count = $sql->numrows();
	$page = $this->getPageNum($count, 15);
	$page = ($page < 1) ? 1 : $page;
	
	$GoTo = "index.php?module=forums&amp;show=showtopic&amp;toid=$topic_id&amp;pp=15&amp;page=$page#pid_$last_post_id";
	
	if ($_POST['p_id'] != "")
	{
		$this->msg($GLOBALS['mod']['config_vars']['PostSuccess'], $GoTo);
	} else {
		$Msg = ($opened == 2) ? $GLOBALS['mod']['config_vars']['MessageTopicCreatedModerated'] : $GLOBALS['mod']['config_vars']['MessageTopicCreated'];
		$this->msg($Msg, $GoTo);
	}
}
?>