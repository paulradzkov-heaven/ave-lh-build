<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class Roadmap {

  var $_limit = 15;

  function list_projects($tpl_dir) {
  	$limit = $this->_limit;
  	$sql   = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_roadmap");
  	$num   = $sql->numrows();

  	$pages = ceil($num / $limit);
  	$start = prepage() * $limit - $limit;

  	$items = array();
  	$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_roadmap ORDER BY position LIMIT $start,$limit");
  	while($row = $sql->fetchrow()) {
  		$get_open_tasks  = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_roadmap_tasks WHERE pid='$row->id' AND task_status = '0'");
  		$row->open_tasks = $get_open_tasks->numrows();

  		$get_closed_tasks  = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_roadmap_tasks WHERE pid='$row->id' AND task_status = '1'");
  		$row->closed_tasks = $get_closed_tasks->numrows();

  		array_push($items,$row);
  	}

  	$page_nav = pagenav($pages, prepage(),
  	" <a class=\"pnav\" href=\"index.php?do=modules&action=modedit&mod=roadmap&moduleaction=1&cp=" . SESSION . "&page={s}\">{t}</a> ");
  	if($num > $limit) $GLOBALS['tmpl']->assign("page_nav", $page_nav);

  	$GLOBALS['tmpl']->assign("items", $items);
  	$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_projects.tpl"));
  }

  function edit_project($tpl_dir,$id) {
  	switch($_REQUEST['sub']) {
  		case '':
  			$GLOBALS['tmpl']->assign("formaction", "index.php?do=modules&action=modedit&mod=roadmap&moduleaction=edit_project&id=$id&sub=save&cp=" . SESSION . "&pop=1");

  			$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_roadmap WHERE id='$id'");
  			$row = $sql->fetchrow();

  			$GLOBALS['tmpl']->assign("item", $row);
  			$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_project_form.tpl"));

  		break;

  		case 'save':

  		$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_roadmap SET
  			project_desc = '" . $_REQUEST['project_desc']. "',
  			project_name = '" . $_REQUEST['project_name'] . "',
  			position = '" . $_REQUEST['position'] . "',
  			project_status = '" . $_REQUEST['project_status'] . "'
  			WHERE id='$id'");
  		reportLog($_SESSION["cp_uname"] . " - отредактировал проект",'2','2');

  		echo "<script>window.opener.location.reload(); self.close();</script>";
  		break;
  	}
  }

  function new_project($tpl_dir) {

  	switch($_REQUEST['sub']) {
  		case '':
  			$GLOBALS['tmpl']->assign("formaction", "index.php?do=modules&action=modedit&mod=roadmap&moduleaction=new_project&sub=save&cp=" . SESSION . "&pop=1");
  			$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_project_form.tpl"));
  		break;

  		case 'save':
  			$sql = "INSERT INTO " . PREFIX . "_modul_roadmap (
  				id,
  				project_name,
  				project_desc,
  				project_status,
  				position
  			) VALUES (
  				'',
  				'" . $_REQUEST['project_name'] . "',
  				'" . $_REQUEST['project_desc'] . "',
  				'" .$_REQUEST['project_status']. "',
  				'" . $_REQUEST["position"] . "'
  			)";

  			$GLOBALS['db']->Query($sql);
  			reportLog($_SESSION["cp_uname"] . " - добавил новый проект",'2','2');

  		echo "<script>window.opener.location.reload(); self.close();</script>";
  		break;
  	}
  }

  function del_project($id) {
  	$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_roadmap WHERE id = '$id'");
  	reportLog($_SESSION["cp_uname"] . " - удалил проект",'2','2');
  	header("Location:index.php?do=modules&action=modedit&mod=roadmap&moduleaction=1&cp=" . SESSION);
  	exit;
  }

  function show_tasks($tpl_dir, $id, $closed) {
  	$limit = $this->_limit;
  	$sql = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_roadmap_tasks WHERE pid='$id' AND task_status = '$closed' ORDER BY priority");
  	$num = $sql->numrows();

  	$pages = ceil($num / $limit);
  	$start = prepage() * $limit - $limit;

  	$items = array();
  	$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_roadmap_tasks WHERE pid='$id' AND task_status = '$closed' ORDER BY priority LIMIT $start,$limit");

  	while($row = $sql->fetchrow()) {

  		$sql_2 = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_users WHERE Id = '$row->uid'");
  		$row_2 = $sql_2->fetchrow();

  		$row->lastname  = $row_2->Nachname;
  		$row->firstname = $row_2->Vorname;

  		switch($row->priority) {
  			case'1': $row->priority = $GLOBALS['tmpl']->get_config_vars('ROADMAP_TASK_HIGHEST'); break;
  			case'2': $row->priority = $GLOBALS['tmpl']->get_config_vars('ROADMAP_TASK_HIGH'); break;
  			case'3': $row->priority = $GLOBALS['tmpl']->get_config_vars('ROADMAP_TASK_NORMAL'); break;
  			case'4': $row->priority = $GLOBALS['tmpl']->get_config_vars('ROADMAP_TASK_LOW'); break;
  			case'5': $row->priority = $GLOBALS['tmpl']->get_config_vars('ROADMAP_TASK_LOWEST'); break;
  		}

  		array_push($items,$row);
  	}

  	$page_nav = pagenav($pages, prepage(),
  	" <a class=\"pnav\" href=\"index.php?do=modules&action=modedit&mod=roadmap&moduleaction=show_tasks&closed=$closed&id=$id&cp=" . SESSION . "&page={s}\">{t}</a> ");
  	if($num > $limit) $GLOBALS['tmpl']->assign("page_nav", $page_nav);

  	$GLOBALS['tmpl']->assign("items", $items);
  	$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_tasks.tpl"));

  }

  function new_task($tpl_dir,$id) {
  	switch($_REQUEST['sub']) {
  		case '':
  			$GLOBALS['tmpl']->assign("formaction", "index.php?do=modules&action=modedit&mod=roadmap&moduleaction=new_task&id=$id&sub=save&cp=" . SESSION . "&pop=1");
  			$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_task_form.tpl"));
  		break;

  		case 'save':
  			$sql = "INSERT INTO " . PREFIX . "_modul_roadmap_tasks (
  				id,
  				pid,
  				task_desc,
  				date_create,
  				task_status,
  				uid,
  				priority
  			) VALUES (
  				'',
  				'" . $_REQUEST['id'] . "',
  				'" . $_REQUEST['task_desc'] . "',
  				'". time() ."',
  				'" .$_REQUEST['task_status']. "',
  				'" . $_SESSION["cp_benutzerid"] . "',
  				'" . $_REQUEST['priority'] . "'
  			)";

  			$GLOBALS['db']->Query($sql);
  			reportLog($_SESSION["cp_uname"] . " - добавил новую задачу",'2','2');

  			echo "<script>window.opener.location.reload(); self.close();</script>";
  		break;
  	}
  }

  function edit_task($tpl_dir,$id) {
  	switch($_REQUEST['sub']) {
  		case '':
  			$GLOBALS['tmpl']->assign("formaction", "index.php?do=modules&action=modedit&mod=roadmap&moduleaction=edit_task&id=$id&sub=save&cp=" . SESSION . "&pop=1");

  			$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_roadmap_tasks WHERE id='$id'");
  			$row = $sql->fetchrow();

  			$sql_2 = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_users WHERE Id = '$row->uid'");
  			$row_2 = $sql_2->fetchrow();

  			$row->lastname = $row_2->Nachname;
  			$row->firstname = $row_2->Vorname;

  			$GLOBALS['tmpl']->assign("item", $row);
  			$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_task_form.tpl"));

  		break;

  		case 'save':

  		$GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_roadmap_tasks SET
  			task_desc = '" . $_REQUEST['task_desc']. "',
  			task_status = '" . $_REQUEST['task_status'] . "',
  			uid = '" . $_REQUEST['uid'] . "',
  			date_create = '". time() ."',
  			priority = '" . $_REQUEST['priority'] . "'
  			WHERE Id='$id'");
  		reportLog($_SESSION["cp_uname"] . " - отредактировал задачу",'2','2');

  		echo "<script>window.opener.location.reload(); self.close();</script>";
  		break;
  	}
  }

  function del_task($id,$pid,$closed) {
  	$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_roadmap_tasks WHERE id = '$id'");
  	reportLog($_SESSION["cp_uname"] . " - удалил задачу",'2','2');
  	header("Location:index.php?do=modules&action=modedit&mod=roadmap&moduleaction=show_tasks&id=$pid&closed=$closed&cp=" . SESSION);
  	exit;
  }

  function show_p($tpl_dir) {

  	$items = array();
  	$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_roadmap ORDER BY position");
  	while($row = $sql->fetchrow()) {
  		$sql_date = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_roadmap_tasks WHERE pid='$row->id' ORDER BY date_create DESC LIMIT 0,1");
  		$row_date = $sql_date->fetchrow();
  		$row->date = $row_date->date_create;

  		$query = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_roadmap_tasks WHERE pid='$row->id'");
  		$all_count = $query->numrows();

  		$get_closed_tasks = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_roadmap_tasks WHERE pid='$row->id' AND task_status = '1'");
  		$row->num_closed = $get_closed_tasks->numrows();

  		$row->num_open = $all_count - $row->num_closed;

  		if($row->num_closed != "0") {
  			$row->closed = round($row->num_closed * 100 / $all_count);
  		} else {
  			$row->closed = 0;
  		}

  		$row->open = round(100 - $row->closed);
  		array_push($items,$row);
  	}


  	$GLOBALS['tmpl']->assign("items", $items);
  	$tpl_out = $GLOBALS['tmpl']->fetch($tpl_dir.'projects.tpl');
  	define("MODULE_CONTENT", $tpl_out);


  }

  function show_t($id,$closed,$tpl_dir) {
  	$id     = addslashes($id);
  	$closed = addslashes($closed);

  	$items = array();
  	$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_roadmap_tasks WHERE pid='$id' AND task_status ='$closed' ORDER BY priority");
  	while($row = $sql->fetchrow()) {
          $sql_2 = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_users WHERE Id = '$row->uid'");
  		$row_2 = $sql_2->fetchrow();

  		$row->lastname  = $row_2->Nachname;
  		$row->firstname = $row_2->Vorname;

  		switch($row->priority) {
  			case'1': $row->priority = $GLOBALS['tmpl']->get_config_vars('ROADMAP_TASK_HIGHEST'); $row->prio = 1; break;
  			case'2': $row->priority = $GLOBALS['tmpl']->get_config_vars('ROADMAP_TASK_HIGH'); $row->prio = 2; break;
  			case'3': $row->priority = $GLOBALS['tmpl']->get_config_vars('ROADMAP_TASK_NORMAL'); $row->prio = 3; break;
  			case'4': $row->priority = $GLOBALS['tmpl']->get_config_vars('ROADMAP_TASK_LOW'); $row->prio = 4; break;
  			case'5': $row->priority = $GLOBALS['tmpl']->get_config_vars('ROADMAP_TASK_LOWEST'); $row->prio = 5; break;
  		}

  		array_push($items,$row);
  	}

  	$sql_r = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_roadmap WHERE id='$id'");
  	$row_r = $sql_r->fetchrow();

  	$GLOBALS['tmpl']->assign("row", $row_r);
  	$GLOBALS['tmpl']->assign("items", $items);
  	$tpl_out = $GLOBALS['tmpl']->fetch($tpl_dir.'tasks.tpl');
  	define("MODULE_CONTENT", $tpl_out);


  }
}
?>