<?php

class Forum
{
	var $x = '';

	function Settings($tpl_dir)
	{
		if(isset($_REQUEST['save']) && $_REQUEST['save']==1)
		{
			$_POST['boxwidthcomm'] = (!empty($_POST['boxwidthcomm'])) ? $_POST['boxwidthcomm'] : 300;
			$_POST['maxlengthword'] = (!empty($_POST['maxlengthword'])) ? $_POST['maxlengthword'] : 50;
			$_POST['maxlines'] = (!empty($_POST['maxlines'])) ? $_POST['maxlines'] : 15;

			$q = "
			UPDATE
				" . PREFIX . "_modul_forum_settings
			SET
				boxwidthcomm = '" . $_POST['boxwidthcomm'] . "',
				boxwidthforums = '" . $_POST['boxwidthcomm'] . "',
				maxlengthword = '" . $_POST['maxlengthword'] . "',
				maxlines = '" . $_POST['maxlines'] . "',
				badwords = '" . @trim($_POST['badwords']) . "',
				badwords_replace = '" . @$_POST['badwords_replace'] . "',
				pageheader = '" . @$_POST['pageheader'] . "',
				AbsenderMail = '" . @$_POST['AbsenderMail'] . "',
				AbsenderName = '" . @$_POST['AbsenderName'] . "',
				SystemAvatars = '" . @$_POST['SystemAvatars'] . "',
				BBCode = '" . @$_POST['BBCode'] . "',
				Smilies = '" . @$_POST['Smilies'] . "',
				Posticons = '" . @$_POST['Posticons'] . "'
			";
			$GLOBALS['db']->Query($q);
			header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=settings&cp=" . SESSION);
			exit;
		}

		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_forum_settings");
		$row = $sql->fetcharray();

		$GLOBALS['tmpl']->assign('r', $row);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'forums_settings.tpl'));
	}

	function forumAdmin($tpl_dir)
	{
		if(isset($_REQUEST['sub']) &&  !empty($_REQUEST['sub']) && isset($_REQUEST['what']) && !empty($_REQUEST['what']))
		{
			switch($_REQUEST['what'])
			{
				case 'position':
					// position der kategorien speichern
					foreach ($_POST['c_id'] as $c_id)
					{
						$position = $_POST['c_position'][$c_id];
						if (is_numeric($position))
						{
							$query = "UPDATE " . PREFIX . "_modul_forum_category SET position = $position WHERE id = '$c_id'";
							$result = $GLOBALS['db']->Query($query);
						}
					}

					// position der foren speichern
					foreach ($_POST['f_id'] as $f_id)
					{
						$position = $_POST['f_position'][$f_id];
						if (is_numeric($position))
						{
							$query = "UPDATE " . PREFIX . "_modul_forum_forum SET position = $position WHERE id = '$f_id'";
							$result = $GLOBALS['db']->Query($query);
						}
					}
				break;

			}
		}

		$categories = array();
		$_GET['id'] = (!isset($_GET['id']) || $_GET['id']==0) ? 0 : $_GET['id'];
		$this->getCategoriesAdmin(@$_GET['id'], $categories, " - ");

		$GLOBALS['tmpl']->assign("categories", $categories);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'forums_overview.tpl'));
	}


	//=======================================================
	// Neues Forum
	//=======================================================
	function addForum($tpl_dir, $id)
	{
		if(isset($_REQUEST['save']) && $_REQUEST['save']==1)
		{
			$_POST['title'] = (empty($_POST['title'])) ? 'Kein Titel' : $_POST['title'];


		if (empty($_POST['f_id']))
		{
			// passwort erstellen
			$password = (empty($_POST['password'])) ? '' : md5(md5($_POST['password']));
			$password_raw = $_POST['password'];

			// gruppen der kategorie uebernehmen
			$q_groups = "SELECT group_id FROM " . PREFIX . "_modul_forum_category WHERE id = '" . $_POST['c_id'] . "'";
			$r_groups = $GLOBALS['db']->Query($q_groups);
			$c_groups = $r_groups->fetchrow();

			$title = strip_tags($_POST["title"]);
			$comment = ($_POST['comment'] != "") ? strip_tags($_POST['comment']) : "";
			$moderated = ($_POST['moderated'] != "") ? 1 : 0;

			$query = "
			INSERT INTO " . PREFIX . "_modul_forum_forum
			(
				id,
				title,
				comment,
				category_id,
				active,
				password,
				password_raw,
				group_id,
				moderated,
				moderated_posts,
				post_emails,
				topic_emails
			) VALUES (
				'',
				'$title',
				'$comment',
				'$_POST[c_id]',
				'$_POST[active]',
				'$password',
				'$password_raw',
				'$c_groups->group_id',
				'$moderated',
				'$moderated_posts',
				'$_POST[post_emails]',
				'$_POST[topic_emails]'
			)";

			$result = $GLOBALS['db']->Query($query);
			$forum_id = $GLOBALS['db']->InsertId();

			// gruppen
			$q_groups = "SELECT Benutzergruppe as ugroup FROM " . PREFIX . "_user_groups";
			$r_groups = $GLOBALS['db']->Query($q_groups);

			// standardmaske fuer die rechte einer gruppe
			include(BASE_DIR . "/modules/forums/internals/permmask.php");

			while ($group = $r_groups->fetchrow())
			{
				// zugriffsrechte tabellen
				if($default_mask[$group->ugroup] == "") $defmask = $default_mask['2'];
				else $defmask = $default_mask[$group->ugroup];

				$query = "
				INSERT INTO
					" . PREFIX . "_modul_forum_permissions (
					forum_id,
					group_id,
					permissions
				) VALUES (
					'$forum_id',
					'" . $group->ugroup . "',
					'".$defmask."')";
				$result = $GLOBALS['db']->Query($query);
			}

		} else {

				// forum aktualisieren
				$password = ($_POST['password'] == "") ? '' : md5(md5($_POST['password']));
				$query = "
				UPDATE
					" . PREFIX . "_modul_forum_forum
				SET
					title = '" . strip_tags($_POST['title']) . "',
					comment = '" . strip_tags($_POST['comment']) . "',
					group_id = '" . @implode(",", $_POST['group_id']) . "',
					active = " . $_POST['active'] . ",
					password = '$password',
					password_raw = '$_POST[password]',
					moderated = '$_POST[moderated]',
					moderated_posts = '$_POST[moderated_posts]',
					post_emails = '$_POST[post_emails]',
					topic_emails = '$_POST[topic_emails]'
				WHERE
					id = '" . $_POST['f_id'] . "'";
				$result = $GLOBALS['db']->Query($query);
			}

			$f_id = ($_POST['f_id'] == "") ? $GLOBALS['db']->InsertId() : $_POST['f_id'];

			// status aendern
			$this->switchForumStatus($f_id, $_POST['status']);
			echo '<script>window.opener.location.reload();window.close();</script>';
		}

		$query = "
		SELECT
			Benutzergruppe as ugroup,
			Name as groupname
		FROM
			" . PREFIX . "_user_groups";

		$result = $GLOBALS['db']->Query($query);
		$groups = array();
		while ($group = $result->fetchrow())
		{
			$groups[] = $group;
		}

		$GLOBALS['tmpl']->assign("groups", $groups);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'edit_forum.tpl'));
	}


	// Forum bearbeiten
	function editForum($tpl_dir, $id)
	{
		$f_id = $_GET['id'];
		$query = "
		SELECT
			f.id,
			f.title,
			f.comment,
			f.active,
			c.group_id,
			f.category_id,
			f.status,
			f.password_raw,
			f.moderated,
			f.moderated_posts,
			f.post_emails,
			f.topic_emails
		FROM
			" . PREFIX . "_modul_forum_forum AS f,
			" . PREFIX . "_modul_forum_category AS c
		WHERE
			f.id = $f_id AND
			f.category_id = c.id
		";

		$result = $GLOBALS['db']->Query($query);
		$forum = $result->fetchrow();

		$forum->group_id = @explode(",", $forum->group_id);

		// gruppen
		$query = "
		SELECT
			Benutzergruppe as ugroup,
			Name as groupname
		FROM
			" . PREFIX . "_user_groups";

		$result = $GLOBALS['db']->Query($query);

		$groups = array();

		while ($group = $result->fetchrow())
		{
			$groups[] = $group;
		}

		$GLOBALS['tmpl']->assign("forum", $forum);
		$GLOBALS['tmpl']->assign("groups", $groups);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'edit_forum.tpl'));
	}


	// Berechtigungen Froum
	function editPermissions($tpl_dir)
	{
		if(isset($_REQUEST['sub']) && $_REQUEST['sub']=='save')
		{
			$permissions = array();
			$permissions[FORUM_PERMISSION_CAN_SEE] = $_POST['can_see'];
			$permissions[FORUM_PERMISSION_CAN_SEE_TOPIC] = $_POST['can_see_topic'];
			$permissions[FORUM_PERMISSION_CAN_SEE_DELETE_MESSAGE] = 1;
			$permissions[FORUM_PERMISSION_CAN_SEARCH_FORUM] = 1;
			$permissions[FORUM_PERMISSION_CAN_DOWNLOAD_ATTACHMENT] = $_POST['can_download_attachment'];
			$permissions[FORUM_PERMISSION_CAN_CREATE_TOPIC] = $_POST['can_create_topic'];
			$permissions[FORUM_PERMISSION_CAN_REPLY_OWN_TOPIC] = $_POST['can_reply_own_topic'];
			$permissions[FORUM_PERMISSION_CAN_REPLY_OTHER_TOPIC] = $_POST['can_reply_other_topic'];
			$permissions[FORUM_PERMISSION_CAN_UPLOAD_ATTACHMENT] = $_POST['can_upload_attachment'];
			$permissions[FORUM_PERMISSION_CAN_RATE_TOPIC] = $_POST['can_rate_topic'];
			$permissions[FORUM_PERMISSION_CAN_EDIT_OWN_POST] = $_POST['can_edit_own_post'];
			$permissions[FORUM_PERMISSION_CAN_DELETE_OWN_POST] = $_POST['can_delete_own_post'];
			$permissions[FORUM_PERMISSION_CAN_MOVE_OWN_TOPIC] = $_POST['can_move_own_topic'];
			$permissions[FORUM_PERMISSION_CAN_CLOSE_OPEN_OWN_TOPIC] = $_POST['can_close_open_own_topic'];
			$permissions[FORUM_PERMISSION_CAN_DELETE_OWN_TOPIC] = $_POST['can_delete_own_topic'];
			$permissions[FORUM_PERMISSION_CAN_DELETE_OTHER_POST] = $_POST['can_delete_other_post'];
			$permissions[FORUM_PERMISSION_CAN_EDIT_OTHER_POST] = $_POST['can_edit_other_post'];
			$permissions[FORUM_PERMISSIONS_CAN_OPEN_TOPIC] = $_POST['can_open_topic'];
			$permissions[FORUM_PERMISSIONS_CAN_CLOSE_TOPIC] = $_POST['can_close_topic'];
			$permissions[FORUM_PERMISSIONS_CAN_CHANGE_TOPICTYPE] = $_POST['can_change_topic_type'];
			$permissions[FORUM_PERMISSIONS_CAN_MOVE_TOPIC] = $_POST['can_move_topic'];
			$permissions[FORUM_PERMISSIONS_CAN_DELETE_TOPIC] = $_POST['can_delete_topic'];

			$permissions = @implode(",", $permissions);

			if($_REQUEST['settoall'] == 1){
				$sql = $GLOBALS['db']->Query("SELECT forum_id FROM " . PREFIX . "_modul_forum_permissions");
				while($row = $sql->fetchrow()){
					$sql2 = $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_forum_permissions SET permissions = '$permissions' WHERE forum_id = '$row->forum_id' AND group_id = '$_POST[g_id]'");
				}

			} else {
				$sql_first = $GLOBALS['db']->Query("SELECT forum_id FROM " . PREFIX . "_modul_forum_permissions WHERE forum_id = " . $_POST['f_id'] . " AND group_id = '" . $_POST['g_id'] . "'");
				$num_first = $sql_first->numrows();


				if($num_first < 1){
				// wenn gruppe noch nicht in den permissions existiert
				$query = $GLOBALS['db']->Query(
				"INSERT INTO " . PREFIX . "_modul_forum_permissions (
					forum_id,
					group_id,
					permissions
				) VALUES (
					'" . $_POST['f_id'] . "',
					'" . $_POST['g_id'] . "',
					'$permissions'
					)"
				);


				} else {
					// sonst aktualisieren
					$query = "UPDATE " . PREFIX . "_modul_forum_permissions SET permissions = '$permissions' WHERE forum_id = '" . $_POST['f_id'] . "' AND group_id = '" . $_POST['g_id'] . "'";
					$result = $GLOBALS['db']->Query($query);
				}
			}

			$q_parent_id = "
			SELECT
				c.parent_id
			FROM
				" . PREFIX . "_modul_forum_category AS c,
				" . PREFIX . "_modul_forum_forum AS f
			WHERE
				f.id = " . $_POST['f_id'] . " AND
				c.id = f.category_id";

			$r_parent_id = $GLOBALS['db']->Query($q_parent_id);
			$parent_id = $r_parent_id->fetchrow();
			echo '<script>window.close();</script>';
		}

		$forum_id = $_GET['f_id'];
		$group_id = $_GET['g_id'];

		$query = "SELECT permissions FROM " . PREFIX . "_modul_forum_permissions WHERE forum_id = $forum_id AND group_id = $group_id";
		$result = $GLOBALS['db']->Query($query);
		$forum = $result->fetchrow();

		$permissions = @explode(",", $forum->permissions);

		$GLOBALS['tmpl']->assign("permissions", $permissions);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'edit_permissions.tpl'));
	}


	function forumOpenClose($tpl_dir, $id, $openclose)
	{
		$this->switchForumStatus($id, (($openclose=='open') ? FORUM_STATUS_OPEN : FORUM_STATUS_CLOSED) );
		header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=1&cp=" . SESSION);
	}

	//=======================================================
	// Kategorie bearbeiten
	//=======================================================
	function editCategory($tpl_dir, $id)
	{
		// Speichern
		if(isset($_REQUEST['save']) && $_REQUEST['save']==1)
		{

			$_POST['title'] = (empty($_POST['title'])) ? 'Kein Titel' : $_POST['title'];
			$_POST['position'] = (empty($_POST['position']) || !is_numeric($_POST['position']) || $_POST['position']<1) ? 1 : $_POST['position'];

			if (empty($_POST['c_id']))
			{
				$title = $_POST['title'];
				$comment = ($_POST['comment'] != "") ? strip_tags($_POST['comment']) : "";
				$position = $_POST['position'];
				$parent_id = ($_POST['f_id'] == "") ? 0 : $_POST['f_id'];

				$query = "
				INSERT INTO  " . PREFIX . "_modul_forum_category
				(
					id,
					title,
					position,
					comment,
					parent_id,
					group_id
				) VALUES (
					'',
					'$title',
					$position,
					'$comment',
					'$parent_id',
					'" . @implode(",", $_POST['group_id']) . "'
				)";
			} else {
				$query = "
				UPDATE
					" . PREFIX . "_modul_forum_category
				SET
					position = " . $_POST['position'];

				$comment = ($_POST['comment'] != "") ? strip_tags($_POST['comment']) : "";
				if (isset($_POST['title'])) $query .= ", title = '" . strip_tags($_POST['title']) . "'";
				if (isset($comment)) $query .= ", comment = '" . $comment . "'";
				if (isset($_POST['group_id']))
				{
					// Update die foren der kategorie
					$groups = @implode(",", $_POST['group_id']);
					$q_forums = "UPDATE " . PREFIX . "_modul_forum_forum SET group_id = '$groups' WHERE category_id = " . $_POST['c_id'];
					$r_forums = $GLOBALS['db']->Query($q_forums);
					$query .= ", group_id = '$groups'";
				}
				$query .= " WHERE id = " . $_POST['c_id'];
			}
			$result = $GLOBALS['db']->Query($query);
			echo '<script>window.opener.location.reload();window.close();</script>';
		}



		// Auslesen
		$query = "
			SELECT
			id,
			title,
			comment,
			position,
			group_id
		FROM
			" . PREFIX . "_modul_forum_category
		WHERE
			id = '$id'
		";

		$result = $GLOBALS['db']->Query($query);
		$category = $result->fetchrow();
		$category->group_id = @explode(",", $category->group_id);

		// gruppen
		$query = "
		SELECT
			Benutzergruppe as ugroup,
			Name as groupname
		FROM
			" . PREFIX . "_user_groups";

		$result = $GLOBALS['db']->Query($query);

		$groups = array();
		while ($group = $result->fetchrow())
		{
			$groups[] = $group;
		}

		$GLOBALS['tmpl']->assign("groups", $groups);
		$GLOBALS['tmpl']->assign("category", $category);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'edit_category.tpl'));
	}


	//=======================================================
	// Kategorien auslesen
	//=======================================================
	function getCategoriesAdmin($id, &$categories, $prefix)
	{

		// kategorien holen
		$q_cat = "
		SELECT
			id,
			title,
			comment,
			position
		FROM
			" . PREFIX . "_modul_forum_category
		WHERE
			parent_id = $id
		ORDER BY
			position ASC";

		$r_cat = $GLOBALS['db']->Query($q_cat);
		// kategorien durchgehen
		while ($cat = $r_cat->fetchrow()) {
			$cat->forums = array();
			// foren zur kategorie holen
			$q_forum = "
			SELECT
				f.id,
				f.comment,
				f.category_id,
				f.title,
				f.active,
				f.group_id,
				f.position,
				f.status
			FROM
				" . PREFIX . "_modul_forum_forum AS f
			WHERE
				category_id = '" . $cat->id . "'
			ORDER BY
				f.position";

			$result = $GLOBALS['db']->Query($q_forum);

			// alle foren durchgehen
			while ($forum = $result->fetchrow()) {
				$group_ids = @explode(",", $forum->group_id);
				$forum->visible_title = $prefix . $forum->title;
				$q_sub_cat = "SELECT id, title, comment FROM " . PREFIX . "_modul_forum_category WHERE parent_id = " . $forum->id;
				$r_sub_cat = $GLOBALS['db']->Query($q_sub_cat);

				$forum->categories = array();
				while ($sub_cat = $r_sub_cat->fetchrow()) {
					$forum->categories[] = $sub_cat;
				}

				// mods holen
				$q_mods = "
				SELECT
					COUNT(m.forum_id) AS m_count
				FROM
					" . PREFIX . "_modul_forum_mods AS m
				WHERE
					m.forum_id = " . $forum->id;

				$r_mods = $GLOBALS['db']->Query($q_mods);
				$mods = $r_mods->fetchrow();
				$forum->mods = $mods->m_count;
				$cat->forums[] = $forum;
			}
			$categories[] = $cat;
		}
	}

	//=======================================================
	// Neue Kategorie
	//=======================================================
	function addCategory($tpl_dir)
	{
		// Speichern
		if(isset($_REQUEST['save']) && $_REQUEST['save']==1)
		{
			$this->editCategory($tpl_dir,0);
		}


		$GLOBALS['tmpl']->assign('groups', $this->getGroups());
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'edit_category.tpl'));
	}


	function addMods($tpl_dir, $f_id)
	{
		// Neu anlegen
		if(!empty($_REQUEST['user_name']))
		{
			$query = "
			SELECT
				BenutzerId
			FROM
				" . PREFIX . "_modul_forum_userprofile
			WHERE
				BenutzerName = '" . $_POST['user_name'] . "' OR
				BenutzerId = '" . $_POST['user_name'] . "'
			";

			$result = $GLOBALS['db']->Query($query);
			$user = $result->fetchrow();
			if ($result->numrows() == 0)
			{
				header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=mods&cp=" . SESSION . "&pop=1&id=" . $_POST['id']);
				exit;
			} else {
				$query = "INSERT INTO " . PREFIX . "_modul_forum_mods (
					forum_id,
					user_id
				) VALUES (
					" . $_POST['id'] . ",
					" . $user->BenutzerId . "
				)";

				$result = $GLOBALS['db']->Query($query);
				header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=mods&cp=" . SESSION . "&pop=1&id=" . $_POST['id']);
				exit;
			}
		}

		// Loeschen
		if(isset($_REQUEST['del']) && $_REQUEST['del']==1)
		{
			$forum_id = $_REQUEST['id'];
			$user_id = $_REQUEST['user_id'];

			$query = "DELETE FROM " . PREFIX . "_modul_forum_mods WHERE forum_id = $forum_id AND user_id = $user_id";
			$result = $GLOBALS['db']->Query($query);
			header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=mods&cp=" . SESSION . "&pop=1&id=$forum_id");
			exit;
		}

		$query = "
		SELECT
			u.BenutzerId,
			u.BenutzerName
		FROM
			" . PREFIX . "_modul_forum_userprofile AS u,
			" . PREFIX . "_modul_forum_mods AS m
		WHERE
			u.BenutzerId = m.user_id AND
			m.forum_id = $f_id
		";

		$result = $GLOBALS['db']->Query($query);
		$mods = array();
		while ($mod = $result->fetchrow())
		{
			$mods[] = $mod;
		}

		$GLOBALS['tmpl']->assign("mods", $mods);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'edit_mods.tpl'));
	}

	//=======================================================
	// Funktion zum Gruppen auslesen
	//=======================================================
	function getGroups()
	{
		$query = "
		SELECT
			Benutzergruppe as ugroup,
			Name as groupname
		FROM
			" . PREFIX . "_user_groups";

		$result = $GLOBALS['db']->Query($query);
		$groups = array();
		while ($group = $result->fetchrow())
		{
			$groups[] = $group;
		}
		return $groups;
	}

	//=======================================================
	// Themen lцschen
	//=======================================================
	function delTopics($tpl_dir)
	{
		if(isset($_REQUEST['del']) && $_REQUEST['del']==1)
		{
			$query = "SELECT id,status,views,replies,datum,title,forum_id FROM " . PREFIX . "_modul_forum_topic AS t WHERE ";
			$where_clausel = array();
			$where_clausel_f = "";
			// loesche themen aelter als
			// четкая тема старше
			if (isset($_POST['date']) && $_POST['date'] > 0)
			{
				$where_clausel[] = "((UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(t.datum)) / 60 / 60 / 24) >= " . $_POST['date'];
			}

			if (isset($_POST['date']) && $_POST['date'] == "all")
			{
				$where_clausel[] = "t.datum";
			}

			if(!empty($_POST['forums']))
			{
				$where_clausel_f = " AND (t.forum_id=";
				$where_clausel_f .= implode(" OR t.forum_id = ", $_POST['forums']); //"t.forum_id = " . $_POST['reply_count'];
				$where_clausel_f .= ")";
			}

			// loesche themen mit antworten weniger als
			// Темы с четким ответом меньше
			if (isset($_POST['reply_count']) && $_POST['reply_count'] != "")
			{
				$where_clausel[] = "t.replies < " . $_POST['reply_count'];
			}

			// loesche geschlossene themen
			// Закрыто четкие темы
			if (isset($_POST['topic_closed']))
			{
				$where_clausel[] = "t.status = " . FORUM_STATUS_CLOSED;
			}

			// loesche themen mit antworten weniger als
			// Темы с четким ответом меньше
			if (isset($_POST['hits_count']) && $_POST['hits_count'] != "")
			{
				$where_clausel[] = "t.views < " . $_POST['hits_count'];
			}

			$query .= @implode(( ($_POST['andor']=='and') ? ' AND ' : ' OR '), $where_clausel);
			$query .= $where_clausel_f;

			if (count($where_clausel) > 0)
			{
				// loesche die dazugehoerigen beitraege mit vorschau
				// четкое свои взносы с предварительного
				$result = $GLOBALS['db']->Query($query);
				if(!empty($_POST['preview']))
				{
					// Endgьltig lцschen
					// Удалить
					if(!empty($_POST['del_id']) && !empty($_POST['del_final']))
					{
						foreach($_POST['del_id'] as $Del => $id)
						{
							$this->deleteTopic($Del);
						}
						header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=delete_topics&cp=" . SESSION);
					}

					// Vorschau
					// Предварительный просмотр
					$Topics = array();
					while ($topic = $result->fetchrow())
					{
						array_push($Topics, $topic);
					}
					$GLOBALS['tmpl']->assign('Topics', $Topics);
					$GLOBALS['tmpl']->assign('preview', 1);


				} else {
					while ($topic = $result->fetchrow())
					{
						$this->deleteTopic($topic->id);
					}
					header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=delete_topics&cp=" . SESSION);
				}
			}

			//header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=delete_topics&cp=" . SESSION);
		}
		$GLOBALS['tmpl']->assign('forums', $this->listForums());
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'delete_topic.tpl'));
	}

	//=======================================================
	// Показать на главной
	//=======================================================
	function showPage($tpl_dir)
	{
	// Сохранить
	if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save') {

    foreach ($_POST['title'] as $id => $title) {
      		if(!empty($_POST['title'][$id])) {

        	$show_pages = (empty($_POST['show'][$id]) || $_POST['show'][$id] == "0") ? "1" : "0";

        	$q = "UPDATE " . PREFIX . "_modul_forum_topic
          	SET
            	show_page = '" . $_POST['show'][$id] . "'
          	WHERE
            	id = '" . $id . "'
          	";
       		 $GLOBALS['db']->Query($q);
      			}
    		}
		}
		$query = "SELECT id,status,views,replies,datum,title,forum_id,show_page FROM " . PREFIX . "_modul_forum_topic  ORDER BY id DESC";
		$result = $GLOBALS['db']->Query($query);
					// Предварительный просмотр
					$Topics = array();
					while ($topic = $result->fetchrow())
					{
						array_push($Topics, $topic);
					}
		$GLOBALS['tmpl']->assign('Topics', $Topics);
		$GLOBALS['tmpl']->assign('forums', $this->listForums());
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'show_page.tpl'));



	}



	//=======================================================
	// Funktion zum auflisten aller Foren
	// Список особенностью всех форумах
	//=======================================================
	function listForums()
	{
		$forums = array();
		$sql = $GLOBALS['db']->Query("SELECT title,id FROM " . PREFIX . "_modul_forum_forum ORDER BY position DESC");
		while($row = $sql->fetchrow())
		{
			$forums[] = $row;
		}
		return $forums;
	}

	//=======================================================
	// Funktion zum auflisten aller Benutzergruppen
	// Список чертой для всех групп пользователей
	//=======================================================
	function listGroups()
	{
		$groups = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_user_groups ORDER BY Name ASC");
		while($row = $sql->fetchrow())
		{
			array_push($groups, $row);
		}
		return $groups;
	}


	//=======================================================
	// Automatisches Update wenn fьr Gruppe keine Rechte bestehen
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

	//=======================================================
	// Benutzergruppen-Rechte
	//=======================================================
	function groupPerms($tpl_dir='')
	{
		if(isset($_REQUEST['group']) && $_REQUEST['group'] != '')
		{

			if(isset($_REQUEST['save']) && $_REQUEST['save']==1)
			{
				$GLOBALS['db']->Query("
				UPDATE
					" . PREFIX  . "_modul_forum_grouppermissions
				SET
					Rechte = '" . @implode("|", $_POST['perm']) . "',
					MAX_AVATAR_BYTES = '" . (@$_POST['MAX_AVATAR_BYTES']*1024) . "',
					MAX_AVATAR_HEIGHT = '" . @$_POST['MAX_AVATAR_HEIGHT'] . "',
					MAX_AVATAR_WIDTH = '" . @$_POST['MAX_AVATAR_WIDTH'] . "',
					UPLOADAVATAR = '" . @$_POST['UPLOADAVATAR'] . "',
					MAXPN = '" . @$_POST['MAXPN'] . "',
					MAXPNLENTH = '" . @$_POST['MAXPNLENTH'] . "',
					MAXLENGTH_POST = '" . @$_POST['MAXLENGTH_POST'] . "',
					MAXATTACHMENTS = '" . @$_POST['MAXATTACHMENTS'] . "',
					MAX_EDIT_PERIOD = '" . (@$_POST['MAX_EDIT_PERIOD']*24) . "'
				WHERE
					Benutzergruppe = '"  . @$_REQUEST['group'] . "'
				");
				echo '<script>window.close();</script>';
			}

			$sql = $GLOBALS['db']->Query("
			SELECT
				*
			FROM
				" . PREFIX . "_modul_forum_grouppermissions
			WHERE
				Benutzergruppe = '" .  $_REQUEST['group'] . "'
				");
			$row = $sql->fetchrow();
			$row->MAX_AVATAR_BYTES = $row->MAX_AVATAR_BYTES/1024;
			$row->MAX_EDIT_PERIOD = $row->MAX_EDIT_PERIOD/24;

			$GLOBALS['tmpl']->assign('Perms', explode("|", $row->Rechte));
			$GLOBALS['tmpl']->assign('row', $row);
			$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'group_perms_pop.tpl'));
		} else {
			$groups = array();
			$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_user_groups ORDER BY Name ASC");
			while($row = $sql->fetchrow())
			{
				array_push($groups, $row);
			}

			$GLOBALS['tmpl']->assign("groups", $groups);
			$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'group_perms.tpl'));
		}
	}

	//=======================================================
	// Benutzerrдnge
	//=======================================================
	function userRanks($tpl_dir)
	{
		// Rang lцschen
		if(isset($_REQUEST['del_rank']) && $_REQUEST['del_rank']==1)
		{
			$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_forum_rank WHERE id = '" . $_GET['id'] . "'");
			header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=user_ranks&cp=" . SESSION);
			exit;
		}

		// Neuer Rang
		if(isset($_REQUEST['add_rank']) && $_REQUEST['add_rank']==1)
		{
			$_POST['title'] = (empty($_POST['title'])) ? 'Unbekannt' : $_POST['title'];
			$_POST['count'] = (empty($_POST['count'])) ? '1000' : $_POST['count'];

			if (is_numeric($_POST['count']) && $_POST['count']>0)
			{
				$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_forum_rank (id, title, count) VALUES ('', '" . $_POST['title'] . "', '" . $_POST['count'] . "')");
				header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=user_ranks&cp=" . SESSION);
				exit;
			}
		}

		// Speichern
		if(isset($_REQUEST['save']) && $_REQUEST['save']==1)
		{
			$rank_id = array_keys($_POST['count']);

			foreach ($rank_id as $id)
			{
				if (is_numeric($_POST['count'][$id]))
				{
					$query = "
						UPDATE
							" . PREFIX . "_modul_forum_rank
						SET
							count = '" . $_POST['count'][$id] . "',
							title = '" . $_POST['title'][$id] . "'
						WHERE
							id = '$id'";
					$result = $GLOBALS['db']->Query($query);
				}
			}
			header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=user_ranks&cp=" . SESSION);
			exit;
		}

		$ranks = array();
		$result = $GLOBALS['db']->Query("SELECT id, title, count FROM " . PREFIX . "_modul_forum_rank ORDER BY count ASC");
		while ($entry = $result->fetchrow())
		{
			$ranks[] = $entry;
		}

		$GLOBALS['tmpl']->assign("ranks", $ranks);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'rank_overview.tpl'));
	}


	//=======================================================
	// Smilies
	//=======================================================
	function listSmilies($tpl_dir)
	{
		// Speichern
		if(isset($_REQUEST['save']) && $_REQUEST['save']==1)
		{
			foreach ($_POST['code'] as $id => $code)
			{
				$sql = "UPDATE
					".PREFIX."_modul_forum_smileys
				SET
					active='" . $_POST['active'][$id] . "',
					code='" . $_POST['code'][$id] . "',
					path='" . $_POST['path'][$id] . "',
					posi='" . $_POST['posi'][$id] . "'
				WHERE id = '$id'";
				$GLOBALS['db']->Query($sql);
			}

			if(isset($_POST['del']) && $_POST['del']>=1)
			{
				foreach ($_POST['del'] as $id => $del)
				{
					if( $del != "")
					{
						$result = $GLOBALS['db']->Query("DELETE FROM  ".PREFIX."_modul_forum_smileys WHERE id = '$id'");
					}
				}
			}
			header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=list_smilies&cp=" . SESSION);
			exit;
		}

		// Neu
		if(isset($_REQUEST['new']) && $_REQUEST['new']==1)
		{
			$smileys = array();
			for ($i = 0; $i < count($_POST['code']); $i++)
			{
				if( (@$_POST['code'][$i] != "" ) && (@$_POST['path'][$i] != "") )
				{
					$GLOBALS['db']->Query("INSERT INTO ".PREFIX."_modul_forum_smileys (id,active,code,path) VALUES ('','1','".$_POST['code'][$i]."','".$_POST['path'][$i]."')");
				}
			}
			header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=list_smilies&cp=" . SESSION);
			exit;
		}

		$smileys = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM ".PREFIX."_modul_forum_smileys ORDER BY posi ASC");
		while($row = $sql->fetchrow())
		{
			array_push($smileys, $row);
		}

		$GLOBALS['tmpl']->assign('smileys', $smileys);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'show_smileys.tpl'));
	}


	//=======================================================
	// Icons
	//=======================================================
	function listIcons($tpl_dir)
	{
		// Speichern
		if(isset($_REQUEST['save']) && $_REQUEST['save']==1)
		{
			foreach ($_POST['path'] as $id => $path)
			{
				$sql = "UPDATE
					".PREFIX."_modul_forum_posticons
				SET
					active='" . $_POST['active'][$id] . "',
					path='" . $_POST['path'][$id] . "',
					posi='" . $_POST['posi'][$id] . "'
				WHERE id = '$id'";
				$GLOBALS['db']->Query($sql);
			}

			if(isset($_POST['del']) && $_POST['del']>=1)
			{
				foreach ($_POST['del'] as $id => $del)
				{
					if( $del != "")
					{
						$result = $GLOBALS['db']->Query("DELETE FROM  ".PREFIX."_modul_forum_posticons WHERE id = '$id'");
					}
				}
			}
			header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=list_icons&cp=" . SESSION);
			exit;
		}

		// Neu
		if(isset($_REQUEST['new']) && $_REQUEST['new']==1)
		{
			$icons = array();
			for ($i = 0; $i < count($_POST['path']); $i++)
			{
				if( (@$_POST['path'][$i] != "" ) && (@$_POST['path'][$i] != "") )
				{
					$GLOBALS['db']->Query("INSERT INTO ".PREFIX."_modul_forum_posticons (id,active,path) VALUES ('','1','".$_POST['path'][$i]."')");
				}
			}
			header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=list_icons&cp=" . SESSION);
			exit;
		}

		$icons = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM ".PREFIX."_modul_forum_posticons ORDER BY posi ASC");
		while($row = $sql->fetchrow())
		{
			array_push($icons, $row);
		}

		$GLOBALS['tmpl']->assign('smileys', $icons);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'show_icons.tpl'));
	}


	//=======================================================
	// Alagen anzeigen
	//=======================================================
	function showAttachments($tpl_dir)
	{
		if(isset($_REQUEST['save']) && $_REQUEST['save']==1)
		{
			if(isset($_POST['del']) && $_POST['del']>=1)
			{
				foreach($_POST['del'] as $filename => $del)
				{
					if( $del != "")
					{
						$result = $GLOBALS['db']->Query("DELETE FROM  ".PREFIX."_modul_forum_attachment WHERE filename = '$filename'");
						@unlink(BASE_DIR . "/modules/forums/attachments/$filename");
					}
				}
			}
		}

		if(isset($_REQUEST['dl']) && $_REQUEST['dl']==1)
		{
			$query = "SELECT filename, orig_name FROM " . PREFIX . "_modul_forum_attachment WHERE id = '$_GET[id]'";
			$result = $GLOBALS['db']->Query($query);
			$file = $result->fetchrow();

			header("Cache-control: private");
			header("Content-type: application/octet-stream");
			header("Content-disposition:attachment; filename=" . $file->orig_name);
			header("Content-Length: " . @filesize(BASE_DIR . "/modules/forums/attachments/" . $file->filename));
			readfile(BASE_DIR . "/modules/forums/attachments/" . $file->filename);
			exit();
		}

		$limit = (!isset($_REQUEST['pp'])) ? 10 : $_REQUEST['pp'];
		$attachments = array();

		$_REQUEST['sort'] = (!isset($_REQUEST['sort'])) ? '' : $_REQUEST['sort'];
		$_REQUEST['q'] = (!isset($_REQUEST['q'])) ? '' : $_REQUEST['q'];

		switch($_REQUEST['sort'])
		{
			case "name":
			$ord = " ORDER BY  orig_name ASC";
			break;

			case "hits":
			$ord = "ORDER BY hits DESC";
			break;

			case "":
			$ord = "ORDER BY hits DESC";
			break;

		}

		$extra_ext = '';
		if(!empty($_REQUEST['ext'])) $extra_ext = " AND ( (RIGHT(orig_name,3) = '$_REQUEST[ext]') OR (RIGHT(orig_name,4) = '$_REQUEST[ext]') OR (RIGHT(orig_name,5) = '$_REQUEST[ext]') )  ";
		$extra = "WHERE orig_name like '%$_REQUEST[q]%' $extra_ext ";

		$query = "SELECT id FROM " . PREFIX . "_modul_forum_attachment $extra $ord";
		$result = $GLOBALS['db']->Query($query);
		$num = $result->numrows();

		$seiten = ceil($num / $limit);
		$a = prepage() * $limit - $limit;

		$query = "SELECT * FROM " . PREFIX . "_modul_forum_attachment $extra $ord limit $a,$limit";
		$result = $GLOBALS['db']->Query($query);

		while($row = $result->fetchrow())
		{
			$row->sizes = $this->file_size(@filesize(BASE_DIR . "/modules/forums/attachments/" . $row->filename)/1024);
			array_push($attachments, $row);
		}

		if($num>$limit) $GLOBALS['tmpl']->assign('nav', pagenav($seiten, $this->getActPage(), " <a class=\"pnav\" href=\"index.php?do=modules&action=modedit&mod=forums&moduleaction=show_attachments&cp=" . SESSION . "&q=".@$_REQUEST['q']."&sort=".@$_REQUEST['sort']."&page={s}\">{t}</a> "));
		$GLOBALS['tmpl']->assign('attachments', $attachments);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'forum_attachments.tpl'));
	}

	//=======================================================
	// Alagenmanager
	//=======================================================
	function attachmentManager($tpl_dir)
	{
		// Speichern
		if(isset($_REQUEST['save']) && $_REQUEST['save']==1)
		{
			$keys = array_keys($_POST['filesize']);
			foreach ($keys as $key)
			{
				$query = "UPDATE " . PREFIX . "_modul_forum_allowed_files SET filesize = '" . $_POST['filesize'][$key] . "' WHERE id = $key";
				$result = $GLOBALS['db']->Query($query);
			}
			header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=attachment_manager&cp=" . SESSION);
			exit;
		}

		// Neu
		if(isset($_REQUEST['new']) && $_REQUEST['new']==1)
		{
			$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_forum_allowed_files (id, filetype, filesize) VALUES ('', '" . $_POST['filetype'] . "', '" . $_POST['filesize'] . "')");
			header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=attachment_manager&cp=" . SESSION);
			exit;
		}

		// Lцschen
		if(isset($_REQUEST['del']) && $_REQUEST['del']==1)
		{
			$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_forum_allowed_files WHERE id = '" . $_GET['id'] . "'");
			header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=attachment_manager&cp=" . SESSION);
			exit;
		}

		// erlaubte dateitypen holen
		@include_once(BASE_DIR . '/modules/forums/internals/filetypes.php');
		$query = "SELECT id, filetype, filesize FROM " . PREFIX . "_modul_forum_allowed_files ORDER BY filetype";
		$result = $GLOBALS['db']->Query($query);

		$allowed_files = array();
		$allowed_mime_types = array();

		while ($allowed_file = $result->fetchrow())
		{
			$allowed_files[] = $allowed_file;
			$allowed_mime_types[] = $allowed_file->filetype;
		}

		// filtere bereits erlaubte dateitypen aus der liste
		// der moeglichen dateitypen aus
		$filtered_possible_files = array();
		foreach ($possible_files as $key => $value)
		{
			if (!@in_array($value, $allowed_mime_types))
			{
				$filtered_possible_files[$key] = $value;
			}
		}

		$GLOBALS['tmpl']->assign("possible_files_keys", array_keys($filtered_possible_files));
		$GLOBALS['tmpl']->assign("possible_files", $filtered_possible_files);
		$GLOBALS['tmpl']->assign("allowed_files", $allowed_files);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'edit_uploads.tpl'));
	}

	// ================================================================
	// setzt den status des forums auf $status
	// auch die subforen werden beachtet
	// ================================================================
	function switchForumStatus($id, $status)
	{
		$q_close = "UPDATE " . PREFIX . "_modul_forum_forum SET status = $status WHERE id = $id";
		$r_close = $GLOBALS['db']->Query($q_close);
		$q_child = "
			SELECT f.id FROM
			" . PREFIX . "_modul_forum_category AS c,
			" . PREFIX . "_modul_forum_forum AS f
			WHERE parent_id = $id AND f.category_id = c.id";

		$r_child = $GLOBALS['db']->Query($q_child);

		if ($r_child->numrows() == 0) return;

		while ($child = $r_child->fetchrow())
		{
			$this->switchForumStatus($child->id, $status);
		}
		return;
	}


	function deleteCat($tpl_dir,$id)
	{
		$this->deleteCategory($id);
		header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=1&cp=" . SESSION);
	}


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


	function deleteForum($id)
	{
		$query = "SELECT id FROM " . PREFIX . "_modul_forum_topic WHERE forum_id = $id";
		$result = $GLOBALS['db']->Query($query);
		// themen loeschen
		while ($topic = $result->fetchrow()) {
			$this->deleteTopic($topic->id);
		}
		// berechtigungen loeschen
		$query = "DELETE FROM " . PREFIX . "_modul_forum_permissions WHERE forum_id = $id";
		$result = $GLOBALS['db']->Query($query);
		// unterkategorien loeschen
		$query = "SELECT id FROM " . PREFIX . "_modul_forum_category WHERE parent_id = $id";
		$result = $GLOBALS['db']->Query($query);

		while ($category = $result->fetchrow()) {
			$this->deleteCategory($category->id);
		}
		// forum loeschen
		$query = "DELETE FROM " . PREFIX . "_modul_forum_forum WHERE id = $id";
		$result = $GLOBALS['db']->Query($query);
	}

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

	function deletePost($id)
	{
		$query = "SELECT attachment, topic_id FROM " . PREFIX . "_modul_forum_post WHERE id = $id";
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
		$query = "UPDATE " . PREFIX . "_modul_forum_topic SET replies = replies - 1 WHERE id = " . $post->topic_id;
		$result = $GLOBALS['db']->Query($query);

		// loesche beitrag
		$query = "DELETE FROM " . PREFIX . "_modul_forum_post WHERE id = $id";
		$result = $GLOBALS['db']->Query($query);
	}

	//=======================================================
	// Grцsse umrechnen
	//=======================================================
	function file_size($param)
	{
		$size = $param;
		$size = $size*1024;
		$sizes = Array(' Байт', 'Кб', 'Мб', 'Гб', 'Тб', 'Пб', 'Еб');
		$ext = $sizes[0];
		for ($i = 1; (($i < count($sizes)) && ($size >= 1024)); $i++) {
			$size = $size / 1024;
			$ext = ' ' . $sizes[$i];
		}
		return round($size, 1) . $ext;
	}
	function getActPage()
	{
		$Page = (isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) ? $_REQUEST['page'] : 1);
		return $Page;
	}

	//==============================================================================================================
	//==============================================================================================================
	// Allgemeine Importfunktion
	//==============================================================================================================
	function import($tpl_dir)
	{
		$Prefix = (isset($_REQUEST['prefix']) && $_REQUEST['prefix'] != '') ? $_REQUEST['prefix'] : 'kpro';
		$Truncate = (isset($_REQUEST['truncate']) && $_REQUEST['truncate']==1) ? 1 : '';
		$What = (isset($_REQUEST['what']) && $_REQUEST['what'] != '') ? $_REQUEST['what'] : '';

		switch($What)
		{
			case 'user':
				$this->userImport($Prefix,$Truncate);
			break;

			case 'forums':
				$this->forumImport($Prefix,$Truncate);
			break;
		}

		$sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_users");
		$num = $sql->numrows();

		$GLOBALS['tmpl']->assign('usercount', $num);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'import.tpl'));
	}


	function forumImport($Prefix='',$Truncate='')
	{

		$sql = $GLOBALS['db']->Query("SELECT id FROM " . $Prefix . "_f_forum") ;
		$num = $sql->numrows();

		$sql2 = $GLOBALS['db']->Query("SELECT id FROM " . $Prefix . "_f_post") ;
		$num2 = $sql2->numrows();

		if($num>0 && $num2>0)
		{

			/*
			$GLOBALS['db']->Query("DROP TABLE " . PREFIX . "_modul_forum_allowed_files") ;
			$GLOBALS['db']->Query("ALTER TABLE " . $Prefix . "_f_allowed_files RENAME " . PREFIX . "_modul_forum_allowed_files") ;
			$GLOBALS['db']->Query("DROP TABLE " . PREFIX . "_modul_forum_attachment") ;
			$GLOBALS['db']->Query("ALTER TABLE " . $Prefix . "_f_attachment RENAME " . PREFIX . "_modul_forum_attachment") ;
			$GLOBALS['db']->Query("DROP TABLE " . PREFIX . "_modul_forum_category") ;
			$GLOBALS['db']->Query("ALTER TABLE " . $Prefix . "_f_category RENAME " . PREFIX . "_modul_forum_category") ;
			$GLOBALS['db']->Query("DROP TABLE " . PREFIX . "_modul_forum_forum") ;
			$GLOBALS['db']->Query("ALTER TABLE " . $Prefix . "_f_forum RENAME " . PREFIX . "_modul_forum_forum") ;
			$GLOBALS['db']->Query("DROP TABLE " . PREFIX . "_modul_forum_mods") ;
			$GLOBALS['db']->Query("ALTER TABLE " . $Prefix . "_f_mods RENAME " . PREFIX . "_modul_forum_mods") ;
			$GLOBALS['db']->Query("DROP TABLE " . PREFIX . "_modul_forum_permissions") ;
			$GLOBALS['db']->Query("ALTER TABLE " . $Prefix . "_f_permissions RENAME " . PREFIX . "_modul_forum_permissions") ;
			$GLOBALS['db']->Query("DROP TABLE " . PREFIX . "_modul_forum_post") ;
			$GLOBALS['db']->Query("ALTER TABLE " . $Prefix . "_f_post RENAME " . PREFIX . "_modul_forum_post") ;
			$GLOBALS['db']->Query("DROP TABLE " . PREFIX . "_modul_forum_rank") ;
			$GLOBALS['db']->Query("ALTER TABLE " . $Prefix . "_f_rank RENAME " . PREFIX . "_modul_forum_rank") ;
			$GLOBALS['db']->Query("DROP TABLE " . PREFIX . "_modul_forum_rating") ;
			$GLOBALS['db']->Query("ALTER TABLE " . $Prefix . "_f_rating RENAME " . PREFIX . "_modul_forum_rating") ;
			$GLOBALS['db']->Query("DROP TABLE " . PREFIX . "_modul_forum_topic") ;
			$GLOBALS['db']->Query("ALTER TABLE " . $Prefix . "_f_topic RENAME " . PREFIX . "_modul_forum_topic") ;
			$GLOBALS['db']->Query("DROP TABLE " . PREFIX . "_modul_forum_topic_read") ;
			$GLOBALS['db']->Query("ALTER TABLE " . $Prefix . "_f_topic_read RENAME " . PREFIX . "_modul_forum_topic_read") ;
			*/

			$GLOBALS['db']->Query("TRUNCATE TABLE " . PREFIX . "_modul_forum_allowed_files");
			$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_forum_allowed_files SELECT * FROM " . $Prefix . "_f_allowed_files;");

			$GLOBALS['db']->Query("TRUNCATE TABLE " . PREFIX . "_modul_forum_attachment");
			$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_forum_attachment SELECT * FROM " . $Prefix . "_f_attachment;");

			$GLOBALS['db']->Query("TRUNCATE TABLE " . PREFIX . "_modul_forum_category");
			$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_forum_category SELECT * FROM " . $Prefix . "_f_category;");

			$GLOBALS['db']->Query("TRUNCATE TABLE " . PREFIX . "_modul_forum_forum");
			$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_forum_forum SELECT * FROM " . $Prefix . "_f_forum;");

			$GLOBALS['db']->Query("TRUNCATE TABLE " . PREFIX . "_modul_forum_mods");
			$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_forum_mods SELECT * FROM " . $Prefix . "_f_mods;");

			$GLOBALS['db']->Query("TRUNCATE TABLE " . PREFIX . "_modul_forum_permissions");
			$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_forum_permissions SELECT * FROM " . $Prefix . "_f_permissions;");

			$GLOBALS['db']->Query("TRUNCATE TABLE " . PREFIX . "_modul_forum_post");
			$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_forum_post SELECT * FROM " . $Prefix . "_f_post;");

			$GLOBALS['db']->Query("TRUNCATE TABLE " . PREFIX . "_modul_forum_rank");
			$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_forum_rank SELECT * FROM " . $Prefix . "_f_rank;");

			$GLOBALS['db']->Query("TRUNCATE TABLE " . PREFIX . "_modul_forum_rating");
			$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_forum_rating SELECT * FROM " . $Prefix . "_f_rating;");

			$GLOBALS['db']->Query("TRUNCATE TABLE " . PREFIX . "_modul_forum_topic");
			$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_forum_topic SELECT * FROM " . $Prefix . "_f_topic;");

			$GLOBALS['db']->Query("TRUNCATE TABLE " . PREFIX . "_modul_forum_topic_read");
			$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_forum_topic_read SELECT * FROM " . $Prefix . "_f_topic_read ;");

			$this->userImport($Prefix,$Truncate);

		}

		header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=import&cp=" . SESSION);
		exit;
	}
	//

	//=======================================================
	// Fьr den Import von Benutzern aus Koobi
	//=======================================================
	function userImport($Prefix='',$Truncate='')
	{
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . $Prefix . "_user WHERE uid != 1");
		$num = $sql->numrows();

		// Gibt es Benutzer in der alten Tabelle?
		if($num>0)
		{

			if($Truncate==1)
			{
				$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_forum_userprofile WHERE BenutzerId != 1 AND BenutzerId != '" . $_SESSION['cp_benutzerid'] . "'");
				$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_users WHERE Id != 1 AND Id != '" . $_SESSION['cp_benutzerid'] . "'");
				$GLOBALS['db']->Query("ALTER TABLE " . PREFIX . "_modul_forum_userprofile PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =1");
				$GLOBALS['db']->Query("ALTER TABLE " . PREFIX . "_users PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =1");
			}



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
						BenutzergruppeMisc,
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
						'$row->group_id_misc',
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
				if($row->uid != 2) $GLOBALS['db']->Query($q);
				//echo "<pre>$q</pre>";
			}
		}
		header("Location:index.php?do=modules&action=modedit&mod=forums&moduleaction=import&cp=" . SESSION);
		exit;
	}



}
?>