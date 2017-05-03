<?php
class showTopics {

  function displayTopics($tpl_dir) {
  
    $sql_c = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_forum_topic WHERE show_page = '1' ORDER BY id ASC");
	
	$topics = array();
	
    while($row_c = $sql_c->fetcharray()) {
        $user_name = array();
        $sql_sc = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_forum_userprofile WHERE Id = '".$row_c['uid']."'");
        while($row_sc = $sql_sc->fetcharray()) {
          $row_sc['BenutzerName'] = nl2br($row_sc['BenutzerName']);
          array_push($user_name, $row_sc);
        }
			$row_c['uid'] = $user_name;
			$row_c['show_page'] = $show_page;
        array_push($topics, $row_c);
		}
		
    $GLOBALS['tmpl']->assign('topics', $topics);
	$GLOBALS['tmpl']->display($tpl_dir . "show.tpl");

  }

}
?>
