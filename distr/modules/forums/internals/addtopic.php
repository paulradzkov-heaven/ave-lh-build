<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("ADDTOPIC")) exit;
//=======================================================
// forum id �berpr�fen
//=======================================================
$forum_result = $GLOBALS['db']->Query("SELECT title, status FROM " . PREFIX . "_modul_forum_forum WHERE id = '" . $_POST['fid'] . "'");
$forum = $forum_result->fetchrow();

//=======================================================
// es wurde eine falsche fid �bergeben
//=======================================================
if ($forum_result->numrows() < 1)
{
	$this->msg($GLOBALS['mod']['config_vars']['ErrornoPerm']);
}

if ( ($forum->status == FORUM_STATUS_CLOSED) && (UGROUP != 1) )
{
	$this->msg($GLOBALS['mod']['config_vars']['ErrornoPerm']);
}

//=======================================================
// Zugriffsrechte
//=======================================================
$cat_query = $GLOBALS['db']->Query("SELECT group_id FROM " . PREFIX . "_modul_forum_forum WHERE id = '" . addslashes($_POST['fid']) . "'");
while ($category = $cat_query->fetchrow_assoc())
{
	//=======================================================
	// miscrechte
	//=======================================================
	include_once(BASE_DIR . "/modules/forums/internals/misc_ids.php");

	$groups = explode(",", $category['group_id']);
	if (array_intersect($group_ids, $groups))
	{
		$permissions = $this->getForumPermissionsByUser(addslashes($_POST['fid']), UID);
	}
}

if ($permissions[FORUM_PERMISSION_CAN_CREATE_TOPIC] == 0 )
{
	$this->msg($GLOBALS['mod']['config_vars']['ErrornoPerm']);
}

$error_array = array();

if (empty($_POST["topic"]))
{
	array_push($error_array, str_replace("{0}", $GLOBALS['mod']['config_vars']['MissingTopic'], $GLOBALS['mod']['config_vars']['MissingE']));
}

if (empty($_POST["text"]))
{
	array_push($error_array, str_replace("{0}", $GLOBALS['mod']['config_vars']['MissingText'], $GLOBALS['mod']['config_vars']['MissingE']));
}

if ( count($error_array) || (isset($_REQUEST['preview']) && $_REQUEST['preview']==1) )
{
	$GLOBALS['tmpl']->assign("smilie", SMILIES);

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
				<a title="" href="javascript:;"
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
		$GLOBALS['tmpl']->assign("preview_text", $preview_text);
		$GLOBALS['tmpl']->assign("preview_text_form", stripslashes($_REQUEST['text']));



		$items = array();
		$GLOBALS['tmpl']->assign("items", $items);
	}

	$navigation = $this->getNavigation(addslashes($_POST['fid']), "forum") . $GLOBALS['mod']['config_vars']['ForumSep'] . "<a class='forum_links_navi' href='index.php?module=forums&amp;show=showforum&amp;fid=" . addslashes($_REQUEST['fid']). "'>" . $forum->title . "</a>";

	$GLOBALS['tmpl']->assign("forum_id", $_POST['fid']);
	$GLOBALS['tmpl']->assign("new_topic", 1);
	$GLOBALS['tmpl']->assign("navigation", $navigation);
	$GLOBALS['tmpl']->assign("bbcodes", BBCODESITE);
	$GLOBALS['tmpl']->assign("posticons", $this->getPosticons());
	$GLOBALS['tmpl']->assign("listemos", $this->listsmilies());
	$GLOBALS['tmpl']->assign("topic", addslashes($_POST["topic"]));
	$GLOBALS['tmpl']->assign("subject", addslashes($_POST["subject"]));
	$GLOBALS['tmpl']->assign("message", addslashes($_POST["text"]));
	$GLOBALS['tmpl']->assign("listfonts",  $this->fontdropdown());
	$GLOBALS['tmpl']->assign("sizedropdown",  $this->sizedropdown());
	$GLOBALS['tmpl']->assign("colordropdown",  $this->colordropdown());
	$GLOBALS['tmpl']->assign("errors", $error_array);

	$GLOBALS['tmpl']->assign("topicform", $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . "topicform.tpl"));
	$GLOBALS['tmpl']->assign("threadform", $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . "threadform.tpl"));

	$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'addtopic.tpl');
	define("MODULE_CONTENT", $tpl_out);
	define("MODULE_SITE", $GLOBALS['mod']['config_vars']['NewThread']);

} else {

	//=======================================================
	// TODO status: ANNOUNCEMENT | STICKY | MOVED | ...
	//=======================================================
	$status =  (isset($_POST['status']) && !empty($_POST['status'])) ? addslashes($_POST['status']) : '-';//($_POST["status"] == "") ? "-" : $_POST['status'];

	//=======================================================
	// posticon: NULL | ...
	//=======================================================
	$posticon = $_POST["posticon"];

	//=======================================================
	// topic name
	//=======================================================
	$topic = addslashes($_POST["topic"]);
	$forum_id = addslashes($_POST["fid"]);

	//=======================================================
	// antwort- benachrichtigung
	//=======================================================
	$notification = (isset($_POST['notification']) && !empty($_POST['notification'])) ? ';' . UID : '';// ($_REQUEST['notification'] != "") ? ';' . UID : '';

	//=======================================================
	// topic eintragen
	// wenn forum moderiert ist
	//=======================================================
	$sql = $GLOBALS['db']->Query("SELECT moderated,topic_emails FROM " . PREFIX . "_modul_forum_forum WHERE id = '$forum_id'");
	$row = $sql->fetchrow();
	$opened = ($row->moderated == 1) ? 2 : 1;
	$topic_emails = $row->topic_emails;


	//=======================================================
	// wenn user admin oder selbst mod dieses forum ist, ist der Beitrag nicht moderiert
	//=======================================================
	if(is_mod($forum_id)) $opened = 1;

	$announce = '';
	$type = '';

	if(@$permissions[FORUM_PERMISSIONS_CAN_CHANGE_TOPICTYPE] == 1)
	{
		if(isset($_POST['subaction']) && $_POST['subaction'] == "announce")
		{
			$announce = "type,";
			$type = "'100',";
		}
		if(isset($_POST['subaction']) && $_POST['subaction'] == "attention")
		{
			$announce = "type,";
			$type = "'1',";
		}
	}

	if(@$permissions[FORUM_PERMISSIONS_CAN_CLOSE_TOPIC] == 1)
	{
		if(isset($_POST['subaction']) && $_POST['subaction'] == 'close')
		{
			$status = 1;
		}
	}

	$new_topic_query = "INSERT INTO " . PREFIX . "_modul_forum_topic
	(
		$announce
		id,
		title,
		status,
		replies,
		datum,
		views,
		forum_id,
		posticon,
		uid,
		notification,
		opened,
		last_post_int
	) VALUES (
		$type
		'',
		'" . mysql_escape_string($topic) . "',
		'$status',
		1,
		NOW(),
		1,
		'$forum_id',
		'$posticon',
		'".UID."',
		'$notification',
		'$opened',
		'".time()."'
	)";
	$db_result = $new_topic_result = $GLOBALS['db']->Query($new_topic_query);
	$topic_id = $GLOBALS['db']->InsertId();


	//=======================================================
	// mail an mods senden
	//=======================================================
	if($opened == 2)
	{
		$sql = $GLOBALS['db']->Query("SELECT user_id FROM " . PREFIX . "_modul_forum_mods WHERE forum_id = '$forum_id'");
		while($row = $sql->fetchrow()){
			$sql2 = $GLOBALS['db']->Query("SELECT `UserName`,Email FROM " . PREFIX . "_users WHERE Id = '$row->user_id'");
			$row2 = $sql2->fetchrow();

			// link
			$link = "http://" . $_SERVER['HTTP_HOST'] . str_replace("/index.php","",$_SERVER['PHP_SELF']) . "/index.php?module=forums&show=showtopic&toid=$topic_id&fid=$forum_id";
			$username = (UGROUP==2) ? $GLOBALS['mod']['config_vars']['Guest'] : $this->getUserName($_SESSION['cp_benutzerid']);
			$body = $GLOBALS['mod']['config_vars']['BodyNewThreadEmailMod'];
			$body = str_replace("%%DATUM%%", date("d.m.Y, H:i:s"), $body);
			$body = str_replace("%%N%%", "\n", $body);
			$body = str_replace("%%USER%%", $username, $body);
			$body = str_replace(array("%%SUBJECT%%","%%BETREFF%%"),  "\"$title\"" , $body);
			$body = str_replace("%%LINK%%", $link, $body);
			$body = str_replace("%%MESSAGE%%", $message, $body);

			$globals = new Globals;
			$GLOBALS['globals']->cp_mail($row2->email, stripslashes($body_s), $GLOBALS['mod']['config_vars']['SubjectNewThreadEmail'] . $exsubject, FORUMEMAIL, FORUMABSENDER, "text", "");
		}
	}

	$q_rating    = "INSERT INTO " . PREFIX . "_modul_forum_rating (topic_id, rating, ip) VALUES ($topic_id, '', '')";
	$r_rating    = $GLOBALS['db']->Query($q_rating);
	$title       = (isset($_POST['subject']) && !empty($_POST['subject'])) ? $_POST['subject'] : '';
	$message     = (isset($_POST['parseurl']) && $_POST['parseurl']==1) ? $this->parseurl(substr($_POST['text'], 0, MAXLENGTH_POST)) : substr($_POST['text'], 0, MAXLENGTH_POST);
	$use_bbcode  = (isset($_POST['disablebb']) && $_POST['disablebb']==1) ? 0 : 1;
	$use_smilies = (isset($_POST['disablesmileys']) && $_POST['disablesmileys']==1) ? 0 : 1;
	$use_sig     = (isset($_POST['usesig']) && $_POST['usesig']==1) ? 1 : 0;


	if(isset($_POST['attach_hidden']) && $_POST['attach_hidden']>=1)
	{
		foreach($_POST['attach_hidden'] as $file)
		{
			if($file!="")
			{
				$attached_files[] = $file;
			}
		}
		$attachments = @implode(";", $attached_files);
	} else {
		$attachments = @implode(";", $_POST['attachment']);
	}

	$new_post_query = "INSERT INTO " . PREFIX . "_modul_forum_post
	(
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
	) VALUES (
		'',
		'" . mysql_escape_string($title) . "',
		'" . mysql_escape_string($message) . "',
		NOW(),
		'$topic_id',
		'".UID."',
		'$use_bbcode',
		'$use_smilies',
		'$use_sig',
		'$attachments',
		'$opened'
	)";
	$db_result = $new_post_result = $GLOBALS['db']->Query($new_post_query);
	$last_post_id = mysql_insert_id();

	if($topic_emails != "")
	{
		$headers = array();
		$mails = @explode(",",$row->topic_emails);

		//=======================================================
		// link zusammensetzen
		//=======================================================
		$link = "http://" . $_SERVER['HTTP_HOST'] . str_replace("/index.php","",$_SERVER['PHP_SELF']) . "/index.php?module=forums&show=showtopic&toid=$topic_id&fid=$forum_id";

		$username = (UGROUP==2) ? $GLOBALS['mod']['config_vars']['Guest'] : $this->getUserName($_SESSION['cp_benutzerid']);
		$body_s = ($opened==2) ? $GLOBALS['mod']['config_vars']['BodyNewThreadEmailMod'] : $GLOBALS['mod']['config_vars']['BodyNewThreadEmail'];
		$body_s = str_replace("%%DATUM%%", date("d.m.Y, H:i:s"), $body_s);
		$body_s = str_replace("%%N%%", "\n", $body_s);
		$body_s = str_replace("%%USER%%", $username, $body_s);
		$body_s = str_replace(array("%%SUBJECT%%","%%BETREFF%%"),  "\"".stripslashes($topic)."\"" , $body_s);
		$body_s = str_replace("%%LINK%%", $link, $body_s);
		$body_s = str_replace("%%MESSAGE%%", $message, $body_s);

		$exsubject = ($opened==2) ? " - " . $GLOBALS['mod']['config_vars']['HaveToModerate'] : "";

		//=======================================================
		// E-Mails an Forum-Empf�nger (Admin-Bereich) senden
		//=======================================================
		$globals = new Globals;
		foreach ($mails as $send_mail)
		{
			$GLOBALS['globals']->cp_mail($send_mail, stripslashes($body_s), $GLOBALS['mod']['config_vars']['SubjectNewThreadEmail'] . $exsubject, FORUMEMAIL, FORUMABSENDER, "text", "");
		}
	}

	// FEHLER
	if (!$db_result)
	{
		$this->msg($GLOBALS['mod']['config_vars']['ErrornoPerm']);
		//=======================================================
		// neuer topic wurde erfolgreich erstellt
		//=======================================================
	} else {
		if(UGROUP!= 2)
		{
    		$q_post_increment = "UPDATE " . PREFIX . "_modul_forum_userprofile SET Beitraege = Beitraege + 1 WHERE BenutzerId = '".UID."'";
    		$r_post_increment = $GLOBALS['db']->Query($q_post_increment);
		}

        $q_reply_increment = "UPDATE " . PREFIX . "_modul_forum_topic SET last_post = NOW(), last_post_int ='".time()."' WHERE id = '".$topic_id."'";
        $r_reply_increment = $GLOBALS['db']->Query($q_reply_increment);

        $q_update_forums = "UPDATE " . PREFIX . "_modul_forum_forum SET last_post = NOW(), last_post_id = ".$last_post_id." WHERE id = '".$forum_id."'";
        $r_update_forums = $GLOBALS['db']->Query($q_update_forums);

		$this->Cpengine_Board_SetTopicRead($topic_id);

		//=======================================================
		// Meldung zusammensetzen
		//=======================================================
		$GoTo = ($opened == 2) ? "index.php?module=forums&show=showforum&fid=$forum_id" : "index.php?module=forums&show=showtopic&toid=$topic_id&fid=$forum_id";
		$Msg = ($opened == 2) ? $GLOBALS['mod']['config_vars']['MessageTopicCreatedModerated'] : $GLOBALS['mod']['config_vars']['MessageTopicCreated'];
		$Msg = str_replace('%%GoTo%%', $GoTo, $Msg);

		$GLOBALS['tmpl']->assign("GoTo", $GoTo);
		$GLOBALS['tmpl']->assign("content", $Msg);

		//=======================================================
		// Meldung ausgeben und weiter leiten
		//=======================================================
		$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'redirect.tpl');
		define("MODULE_CONTENT", $tpl_out);
		define("MODULE_SITE", $GLOBALS['mod']['config_vars']['NewThread']);
	}
}

?>