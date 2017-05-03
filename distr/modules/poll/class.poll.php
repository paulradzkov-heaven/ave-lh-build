<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class poll {

	var $_adminlimit = 15;
	var $_limit = 5;
	var $_commentwords = 1000;
	var $_anti_spam = 1;

  function secureCode($c=0) {
	  $tdel = time() - 1200;
	  $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_antispam WHERE Ctime < " . $tdel . "");
	  $pass = "";
	  $chars = array(2,3,4,5,6,7,8,9);
	  $ch = ($c!=0) ? $c : 7;
	  $count = count($chars) - 1;
	  srand((double)microtime() * 1000000);
	  for($i = 0; $i < $ch; $i++) {
		  $pass .= $chars[rand(0, $count)];
	  }
	  $code = $pass;
	  $sql = $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_antispam (id,Code,Ctime) VALUES ('','" .$code. "','" .time(). "')");
	  $codeid = $GLOBALS['db']->InsertId();

    return $codeid;
  }

  function showPoll($tpl_dir,$lang_file,$id) {
			$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_poll WHERE id = '".$id."'");
			$row = $sql->fetchrow();

			$uid  = ($row->uid == "") ? $row->uid : explode(",", $row->uid);
			$ip_a = ($row->ip == "")  ? $row->ip  : explode(",", $row->ip);
			$group_id = ($row->group_id == "") ? $row->group_id : explode(",", $row->group_id);

			if ($row->ende < time()) $row->message = '1';
			if (!(@in_array(UGROUP, $group_id))) $row->message = '2';
			if (@in_array($_SERVER['REMOTE_ADDR'], $ip_a) or  @in_array($_SESSION["cp_benutzerid"], $uid) or $_COOKIE['poll_'.$id] == '1') $row->message = '3';
      if (empty($row->title)) return;
			if  ($row->start > time()) return;

			$sql_hits = $GLOBALS['db']->Query("SELECT hits FROM " . PREFIX . "_modul_poll_items WHERE pollid= '".$id."'");

      while($row_hits = $sql_hits->fetchrow()) {
			  $hits = floor($hits + $row_hits->hits);
		  }

      $items = array();
			$sql_items = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_poll_items WHERE pollid= '".$id."' ORDER BY posi ASC");

      while($row_items = $sql_items->fetchrow()) {
			  if ($hits != '0') {
				  $row_items->sum = round(($row_items->hits * 100) / $hits);
			  } else {
				  $row_items->sum = '0';
			  }
			  array_push($items,$row_items);
		  }

		  $GLOBALS['tmpl']->assign("formaction", "/poll-$id/vote/");
		  $GLOBALS['tmpl']->assign("formaction_result", "/poll-$id/results/");
		  $GLOBALS['tmpl']->assign("formaction_archive", "/poll/archives/");
		  $GLOBALS['tmpl']->assign("poll", $row);
		  $GLOBALS['tmpl']->assign("items", $items);
		  $GLOBALS['tmpl']->assign("hits", $hits);

		  if(isset($row->message)) {
				if ($_REQUEST['module'] != 'poll') $GLOBALS['tmpl']->display($tpl_dir . "poll_nav_result.tpl");
			}	else {
				if ($_REQUEST['module'] != 'poll') $GLOBALS['tmpl']->display($tpl_dir . "poll_nav.tpl");
			}
	}

	function vote($pid) {
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_poll WHERE id = '".mysql_real_escape_string($pid)."'");
		$row = $sql->fetchrow();

    $uid  = ($row->uid == "") ? $row->uid : explode(",", $row->uid);
		$ip_a = ($row->ip == "")  ? $row->ip  : explode(",", $row->ip);
		$group_id = ($row->group_id == "") ? $row->group_id : explode(",", $row->group_id);

    $back = "/poll-$pid/results/";

    if (@in_array($_SERVER['REMOTE_ADDR'], $ip_a) or  @in_array($_SESSION["cp_benutzerid"], $uid) or $_COOKIE['poll_'.$pid] == '1') {
		  header("Location:" . $back);
			return;
		}

    if (!(@in_array(UGROUP, $group_id))) {
		  header("Location:" . $back);
			return;
		}

    $save = $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_poll_items SET hits = hits + 1 WHERE id = '". mysql_real_escape_string($_POST['p_item']) ."'");
		if(UGROUP != '2') $str = " , uid = CONCAT(uid, ',', '" . $_SESSION["cp_benutzerid"] . "')";
		setcookie("poll_".$pid, '1', time() + 3600 * 3600);
		$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_poll SET ip = CONCAT(ip, ',', '" . $_SERVER['REMOTE_ADDR'] . "') $str WHERE id = '". mysql_real_escape_string($pid) ."'");
		header("Location:" . $back);
	}

  function showResult($tpl_dir,$lang_file,$pid) {

			$pid = mysql_real_escape_string($pid);

			$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_poll WHERE id = '".$pid."'");
			$row = $sql->fetchrow();
			if ($row->active != '1') return;

			$sql_hits = $GLOBALS['db']->Query("SELECT hits FROM " . PREFIX . "_modul_poll_items WHERE pollid= '".$pid."'");
			while($row_hits = $sql_hits->fetchrow()) {
			  $hits = floor($hits + $row_hits->hits);
		  }


			$items = array();
			$sql_items = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_poll_items WHERE pollid= '".$pid."' ORDER BY posi ASC");

      while($row_items = $sql_items->fetchrow()) {
			  if ($hits != '0') {
				  $row_items->sum = round(($row_items->hits * 100) / $hits);
			  } else {
				  $row_items->sum = '0';
			  }
			  array_push($items,$row_items);
		  }


			if($row->can_comment == '1') {

			  $comments = array();
			  $sql_comments = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_poll_comments WHERE pollid = '".$pid."' ORDER BY ctime DESC");

        while($row_comments = $sql_comments->fetchrow()) {
			 	  if($row_comments->author != '0') {
						$sql_u = $GLOBALS['db']->Query("SELECT Vorname, Nachname FROM " . PREFIX . "_users WHERE Id = '".$row_comments->author."'");
					  $row_u = $sql_u->fetchrow();
					  $row_comments->firstname = $row_u->Vorname;
					  $row_comments->lastname = $row_u->Nachname;
					} else {
            $row_comments->firstname = '';
						$row_comments->lastname = $GLOBALS['tmpl']->get_config_vars('POLL_GUEST');
					}
			    array_push($comments,$row_comments);
			  }
			  $GLOBALS['tmpl']->assign("count_comments", $sql_comments->numrows());
			}


			$uid  = ($row->uid == "") ? $row->uid : explode(",", $row->uid);
			$ip_a = ($row->ip == "")  ? $row->ip  : explode(",", $row->ip);
			$group_id = ($row->group_id == "") ? $row->group_id : explode(",", $row->group_id);

			if (@in_array($_SERVER['REMOTE_ADDR'], $ip_a) ||  @in_array($_SESSION["cp_benutzerid"], $uid) || $_COOKIE['poll_'.$pid] == '1') {
				$GLOBALS['tmpl']->assign("vote", 1);
			}

      if (!(@in_array(UGROUP, $group_id))) {
				$GLOBALS['tmpl']->assign("rights", 0);
      }

			if( $row->start > time() ) {
			 	$GLOBALS['tmpl']->assign("start", 0);
			}


			if ($row->ende < time()) {
			  $GLOBALS['tmpl']->assign("end", 1);
			}

		$groups = array();
		$sql_g = $GLOBALS['db']->Query("SELECT Benutzergruppe,Name FROM " . PREFIX . "_user_groups");
		while($row_g = $sql_g->fetchrow()) {
			array_push($groups, $row_g);
		}
		$GLOBALS['tmpl']->assign("groups", $groups);
		$GLOBALS['tmpl']->assign("groups_form", explode(",", $row->group_id));

		define("MODULE_SITE", "Результаты голосования / ".$row->title);

		$GLOBALS['tmpl']->assign("formaction", "/poll-$pid/vote/");
		$GLOBALS['tmpl']->assign("formaction_result", "/poll-$pid/results/");
		$GLOBALS['tmpl']->assign("formaction_archive", "/poll/archives/");
		$GLOBALS['tmpl']->assign("formaction_comment", "/poll-$pid/form/".T_PATH."/");

		$GLOBALS['tmpl']->assign("poll", $row);
		$GLOBALS['tmpl']->assign("items", $items);
		$GLOBALS['tmpl']->assign("comments", $comments);

		$GLOBALS['tmpl']->assign("hits", $hits);
		$GLOBALS['tmpl']->assign("start", $row->start);
		$GLOBALS['tmpl']->assign("end", $row->ende);
		$GLOBALS['tmpl']->assign("can_comment", $row->can_comment);
		$GLOBALS['tmpl']->assign("cp_theme", T_PATH);

		$tpl_out = $GLOBALS['tmpl']->fetch($tpl_dir . "result.tpl");
		define("MODULE_CONTENT", $tpl_out);
	}

	//=======================================================
	// ARCHIVE
	//=======================================================
	function showArchive($tpl_dir,$lang_file,$order,$by)
	{

		$order =  mysql_real_escape_string($order);
		$by =  mysql_real_escape_string($by);

		if(empty($order)) $order = 'id';
		if (empty($by)) $by = 'DESC';

		$items = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_poll WHERE Active = '1' ORDER BY ".$order." ". $by ."");
		while($row = $sql->fetchrow())
		{
			$id = $row->id;
			$row->plink = "/poll-$id/results/";

			$sql_hits = $GLOBALS['db']->Query("SELECT hits FROM " . PREFIX . "_modul_poll_items WHERE pollid='$row->id'");
			while($row_hits = $sql_hits->fetchrow())
				{

					$hits[$row->id] = ($hits[$row->id] + $row_hits->hits);
					$row->sum_hits = $hits[$id];

				}

			array_push($items,$row);
		}


		define("MODULE_SITE", "Архив опросов");

		$GLOBALS['tmpl']->assign("items", $items);

		$tpl_out = $GLOBALS['tmpl']->fetch($tpl_dir . "archive.tpl");
		define("MODULE_CONTENT", $tpl_out);
	}

  function displayForm($tpl_dir,$pid,$theme,$errors,$text,$title)	{
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_poll WHERE id = '".$pid."' LIMIT 1");
		$row = $sql->fetchrow();
		$groups = explode(',', $row->group_id);

		if($row->active == 1 && $row->can_comment == 1 && in_array(UGROUP, $groups)) {
			$GLOBALS['tmpl']->assign('cancomment', 1);
		}
		$GLOBALS['tmpl']->assign('max_chars', $this->_commentwords);

		$im = "";

		if(function_exists("imagettftext") && function_exists("imagejpeg") && $this->_anti_spam == 1) {
			$codeid = $this->secureCode();
			$im = $codeid;
			$sql_sc = $GLOBALS['db']->Query("SELECT Code FROM " . PREFIX . "_antispam WHERE id = '$codeid'");
			$row_sc = $sql_sc->fetchrow();
			$GLOBALS['tmpl']->assign("im", $im);
			$_SESSION['cpSecurecode'] = $row_sc->Code;
			$_SESSION['cpSecurecode_id'] = $codeid;
			$GLOBALS['tmpl']->assign("anti_spam", 1);
		}

		if (!empty($errors)) $GLOBALS['tmpl']->assign('errors', $errors);

		$GLOBALS['tmpl']->assign('cp_theme', $theme);
		$GLOBALS['tmpl']->assign('title', $title);
		$GLOBALS['tmpl']->assign('text', $text);
		$GLOBALS['tmpl']->display($tpl_dir . 'poll_form.tpl');
	}

  function sendForm($tpl_dir,$pid) {

		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_poll WHERE id = '" .$pid . "' AND active = '1' AND can_comment = '1'");
		$row = $sql->fetchrow();
		$groups = explode(',', $row->group_id);

		$text = substr(htmlspecialchars($_POST['comment_text']), 0, $this->_commentwords);
		$text_length = strlen($text);
		$text .= ($text_length > $this->_commentwords) ? '...' : '';
		$text = cp_parse_string($text);

  	$errors = array();
		if (empty($_POST['comment_title'])) $error[] = $GLOBALS['tmpl']->get_config_vars('POLL_ENTER_TITLE');
		if (empty($text)) $error[] = $GLOBALS['tmpl']->get_config_vars('POLL_ENTER_TEXT');

		if ($this->_anti_spam == 1) {
			if(!empty($_POST['comment_code']) && $_POST['comment_code'] == $_SESSION['cpSecurecode']) {
				$sql_sc = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_antispam WHERE Code = '" .addslashes($_POST['cpSecurecode']). "'");
				$row_sc = $sql_sc->fetchrow();
				$im = $row_sc->id;
			} else 	$errors[] = $GLOBALS['tmpl']->get_config_vars('POLL_ENTER_CODE');
		}

		if (count($errors)>0) {
			$this->displayForm($tpl_dir,$pid,T_PATH,$errors,$text,$_POST['comment_title']);
			exit();
		}

		if (isset($_SESSION['cp_benutzerid']))  $author = $_SESSION['cp_benutzerid'];
		else $author = '0';

		if($row->active == 1 && in_array(UGROUP, $groups) && $text_length > 3) {
			$sql = "INSERT INTO " . PREFIX . "_modul_poll_comments (
				id,
				pollid,
				ctime,
				author,
				title,
				comment,
				ip
			) VALUES (
				'',
				'" . $pid . "',
				'" . time() . "',
				'" . $author . "',
				'" . $_POST['comment_title'] . "',
				'" . $text . "',
				'" . $_SERVER['REMOTE_ADDR'] . "'
			)";
			$GLOBALS['db']->Query($sql);
		}
		echo '<script>window.opener.location.reload(); window.close();</script>';
	}

  function showPolls($tpl_dir) {
		$sql = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_poll");
		$num = $sql->numrows();
		$sql->Close();

		$limit = $this->_adminlimit;
		$pages = ceil($num / $limit);
		$start = prepage() * $limit - $limit;

		$items = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_poll LIMIT $start,$limit");
		while($row = $sql->fetchrow()) {
			$id = $row->id;
		  $sql_hits = $GLOBALS['db']->Query("SELECT hits FROM " . PREFIX . "_modul_poll_items WHERE pollid='$row->id'");

      while($row_hits = $sql_hits->fetchrow()) {
			  $hits[$row->id] = ($hits[$row->id] + $row_hits->hits);
				$row->sum_hits = $hits[$id];
			}

			$sql_c = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_poll_comments WHERE pollid='$id'");
			$row->comments = $sql_c->numrows();
      array_push($items,$row);
		}

		$page_nav = pagenav($pages, prepage()," <a class=\"pnav\" href=\"index.php?do=modules&action=modedit&mod=poll&moduleaction=1&cp=" . SESSION . "&page={s}\">{t}</a> ");

		if($num > $limit) $GLOBALS['tmpl']->assign("page_nav", $page_nav);
		$GLOBALS['tmpl']->assign("items", $items);
		$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_forms.tpl"));
	}

  function editPolls($tpl_dir,$id) {
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_poll WHERE id = '$id'");
		$row_e = $sql->fetchrow();

		$items = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_poll_items WHERE pollid='$id' ORDER BY posi ASC");
		while($row = $sql->fetchrow()) {
			array_push($items,$row);
		}

		$groups = array();
		$sql_g = $GLOBALS['db']->Query("SELECT Benutzergruppe,Name FROM " . PREFIX . "_user_groups");

    while($row_g = $sql_g->fetchrow()) {
			array_push($groups, $row_g);
		}

		$GLOBALS['tmpl']->assign("groups", $groups);
		$GLOBALS['tmpl']->assign("groups_form", explode(",", $row_e->group_id));
		$GLOBALS['tmpl']->assign("row", $row_e);
		$GLOBALS['tmpl']->assign("items", $items);
		$GLOBALS['tmpl']->assign("tpl_path", $tpl_dir);
		$GLOBALS['tmpl']->assign("year", date("Y"));

		$GLOBALS['tmpl']->assign("s_year", date("Y", $row_e->start));
		$GLOBALS['tmpl']->assign("s_mon", date("m", $row_e->start));
		$GLOBALS['tmpl']->assign("s_day", date("d", $row_e->start));
		$GLOBALS['tmpl']->assign("s_hour", date("H", $row_e->start));
		$GLOBALS['tmpl']->assign("s_min", date("i", $row_e->start));

		$GLOBALS['tmpl']->assign("e_year", date("Y", $row_e->ende));
		$GLOBALS['tmpl']->assign("e_mon", date("m", $row_e->ende));
		$GLOBALS['tmpl']->assign("e_day", date("d", $row_e->ende));
		$GLOBALS['tmpl']->assign("e_hour", date("H", $row_e->ende));
		$GLOBALS['tmpl']->assign("e_min", date("i", $row_e->ende));

		$GLOBALS['tmpl']->assign("formaction", "index.php?do=modules&action=modedit&mod=poll&moduleaction=save&cp=" . SESSION . "&id=".$id."&pop=1");
		$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_fields.tpl"));
	}

  function savePolls($tpl_dir,$id) {
    $qid = $id;
		$start_date = mktime($_REQUEST["s_hour"], $_REQUEST["s_min"], 0, $_REQUEST["s_mon"], $_REQUEST["s_day"], $_REQUEST["s_year"]);
		$end_date   = mktime($_REQUEST["e_hour"], $_REQUEST["e_min"], 0, $_REQUEST["e_mon"], $_REQUEST["e_day"], $_REQUEST["e_year"]);

		$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_poll
			SET
				title       = '".$_REQUEST['poll_name']."',
				active      = '".$_REQUEST['active']."',
				can_comment = '".$_REQUEST['can_comment']."',
				start       = '". $start_date ."',
				ende        = '". $end_date ."',
				group_id    = '" . @implode(",", $_REQUEST['groups']) . "'
			WHERE
				id = '$qid'");

		if(!empty($_POST['del'])) {
			foreach($_POST['del'] as $id => $field) {
				$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_poll_items WHERE id = '$id'");
			}
		}

		foreach($_POST['question_title'] as $id => $field) {
			if(!empty($_POST['question_title'][$id])) {

				$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_poll_items
					SET
					title = '" . $_POST['question_title'][$id] ."',
					hits = '" . $_POST['hits'][$id] . "',
					color = '" . $_POST['line_color'][$id] . "',
					posi = '" . $_POST['position'][$id] . "'
					WHERE id = '$id'");
     	}
		}
		header("Location:index.php?do=modules&action=modedit&mod=poll&moduleaction=edit&id=".$qid."&pop=1&cp=" . SESSION);
	}

  function saveFieldsNew($tpl_dir,$id) {

    if(!empty($_POST['question_title'])) {
			$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_poll_items
			 VALUES (
				'',
				'$id',
				'".$_REQUEST['question_title']."',
				'".$_REQUEST['hits']."',
				'".$_REQUEST['line_color']."',
				'".$_REQUEST['position']."'
			)
			");
		}
		reportLog($_SESSION["cp_uname"] . " - добавил новый вопрос для опроса (".$_REQUEST['question_title'].")",'2','2');
		header("Location:index.php?do=modules&action=modedit&mod=poll&moduleaction=edit&id=".$id."&pop=1&cp=" . SESSION);
	}

  function newPolls($tpl_dir) {
		switch($_REQUEST['sub']) {

      case '':
				$groups = array();
				$sql_g = $GLOBALS['db']->Query("SELECT Benutzergruppe,Name FROM " . PREFIX . "_user_groups");
				while($row_g = $sql_g->fetchrow()) {
					array_push($groups, $row_g);
				}

				$GLOBALS['tmpl']->assign("groups", $groups);
				$GLOBALS['tmpl']->assign("year", date("Y"));
				$GLOBALS['tmpl']->assign("mon", date("m"));
				$GLOBALS['tmpl']->assign("day", date("d"));
				$GLOBALS['tmpl']->assign("hour", date("H"));
				$GLOBALS['tmpl']->assign("min", date("i"));
        $GLOBALS['tmpl']->assign("tpl_path", $tpl_dir);
				$GLOBALS['tmpl']->assign("formaction", "index.php?do=modules&action=modedit&mod=poll&moduleaction=new&sub=save&cp=" . SESSION . "&pop=1");
				$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_fields.tpl"));
			break;

			case 'save':

			$start_date = mktime($_REQUEST["s_hour"], $_REQUEST["s_min"], 0, $_REQUEST["s_mon"], $_REQUEST["s_day"], $_REQUEST["s_year"]);
			$end_date = mktime($_REQUEST["e_hour"], $_REQUEST["e_min"], 0, $_REQUEST["e_mon"], $_REQUEST["e_day"], $_REQUEST["e_year"]);

			$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_poll
				 VALUES (
					'',
					'".$_REQUEST['poll_name']."',
					'".$_REQUEST['active']."',
					'" . @implode(",", $_REQUEST['groups']) . "',
					'0',
					'0',
					'".$_REQUEST['can_comment']."',
					'". $start_date ."',
					'". $end_date ."'
				)
				");
				$iid = $GLOBALS['db']->InsertId();
				reportLog($_SESSION["cp_uname"] . " - добавил новый опрос (" . $_REQUEST['poll_name'] . ")",'2','2');
				header("Location:index.php?do=modules&action=modedit&mod=poll&moduleaction=edit&id=$iid&pop=1&cp=" . SESSION);
				exit;
			break;
		}
	}

  function deletePolls($id) {
		$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_poll WHERE id='$id'");
		$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_poll_items WHERE pollid='$id'");
		$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_poll_comments WHERE pollid='$id'");
		reportLog($_SESSION["cp_uname"] . " - удалил опрос (" . $id . ")",'2','2');
		header("Location:index.php?do=modules&action=modedit&mod=poll&moduleaction=1&cp=" . SESSION);
		exit;
	}

  function showComments($tpl_dir,$id)	{
		$qid = $id;
    switch($_REQUEST['sub']) {
			case '':
    		$items = array();
				$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_poll_comments WHERE pollid = '".$qid."'");
				while($row = $sql->fetchrow()) {
					if($row->author != '0') {
						$sql_u = $GLOBALS['db']->Query("SELECT Vorname, Nachname FROM " . PREFIX . "_users WHERE Id = '".$row->author."'");
						$row_u = $sql_u->fetchrow();
						$row->firstname = $row_u->Vorname;
						$row->lastname  = $row_u->Nachname;
					} else {
						$row->firstname = $GLOBALS['tmpl']->get_config_vars('POLL_UNKNOWN_USER');
						$row->lastname  = '';
					}
					array_push($items,$row);
				}
				$GLOBALS['tmpl']->assign("items", $items);
				$GLOBALS['tmpl']->assign("tpl_path", $tpl_dir);
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_comments.tpl"));
			break;

		  case 'save':
		    if(!empty($_POST['del'])) {
				  foreach($_POST['del'] as $id => $comment) {
					  $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_poll_comments WHERE id = '$id'");
				  }
			  }

        foreach($_POST['comment_text'] as $id => $comment) {
				  if(!empty($_POST['comment_text'][$id])) {
					  $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_poll_comments
						  SET
						  title = '" . $_POST['comment_title'][$id] ."',
						  comment = '" . $_POST['comment_text'][$id] . "'
						  WHERE id = '$id'");
          }
			  }
			  header("Location:index.php?do=modules&action=modedit&mod=poll&moduleaction=comments&id=".$qid."&pop=1&cp=" . SESSION);
			break;
		}
	}
}
?>