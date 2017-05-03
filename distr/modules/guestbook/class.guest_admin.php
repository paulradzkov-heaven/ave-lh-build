<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class Guest_Module {

  //=========================================================
  // ‘ункци€ вывода настроек модул€
  //=========================================================
  function settings($tpl_dir) {
    switch ($_REQUEST['sub']) {
      case '':
        //=========================================================
        // ≈сли в запросе не пришел параметр на сохранение, тогда
        // получаем все настройки дл€ модул€ и передаем их в шаблон
        //=========================================================
        $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_guestbook_settings");
        $row_settings = $sql->fetchrow();
        $GLOBALS['tmpl']->assign('settings', $row_settings);

        //===========================================================
        // ѕолучеам список последних добавленных сообщений
        //===========================================================
        $limit = ($_REQUEST['pp']!='') ? $_REQUEST['pp'] : '15';
        	$sort = ($_REQUEST['sort']!='') ? mysql_escape_string($_REQUEST['sort']) : 'desc';
        	$inserts = array();

        	$sql = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_guestbook");
        	$num = $sql->numrows();

        	$seiten = ceil($num / $limit);
        	$a = prepage() * $limit - $limit;

        	// ‘ормируем навигацию между сообщени€ми
        	if($num > $limit) {
        		$GLOBALS['tmpl']->assign('pnav', pagenav($seiten, prepage(), " <a class=\"page_navigation\" href=\"index.php?do=modules&action=modedit&mod=guestbook&moduleaction=1&cp={$sess}&pp=$limit&sort=$_REQUEST[sort]&page={s}\">{t}</a> "));
        	}

        	//ѕолучаем сообщени€ которые будут выведены в зависимости от страницы
          $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_guestbook order by id $sort limit $a,$limit");
        	$erg = $sql->numrows();

        while ( $row = $sql->fetchrow()) {
      		$row->ctime = date("m.d.y",$row->ctime);
      		array_push($inserts, $row);
      	}

        $GLOBALS['tmpl']->assign('comments_array', $inserts);

        $GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'admin_conf.tpl'));
      break;

      case 'save':
        $sql = $GLOBALS['db']->Query("UPDATE ".PREFIX."_modul_guestbook_settings SET
  	      spamprotect = '$_REQUEST[spamprotect]',
  	      mailbycomment = '$_REQUEST[mailbycomment]',
  	      maxpostlength  = '$_REQUEST[maxpostlength]',
  	      spamprotect_time = '$_REQUEST[spamprotect_time]',
  	      entry_censore = '$_REQUEST[entry_censore]',
  	      smiles = '$_REQUEST[ensmiles]',
  	      bbcodes = '$_REQUEST[enbbcodes]',
  	      mailsend = '$_REQUEST[mailsend]',
  	      smiliebr = '$_REQUEST[sbr]'
        ");
	      header("Location:index.php?do=modules&action=modedit&mod=guestbook&moduleaction=1&cp={$sess}");
	    break;
   }
  }

  //================================================================
  // ‘ункци€ управлени€ сообщени€ми (активаци€, удаление и т.д.)
  //================================================================
  function edit_massage ($tpl_dir) {

    if(count($_POST['author'])>0)	{
			foreach ($_POST['author'] as $id => $author) {
				$gisa = ($_POST['is_active'][$id] != '') ? ",is_active = '" . $_POST['is_active'][$id] . "'" : "";
				$sql = " UPDATE " . PREFIX . "_modul_guestbook
				  SET
    				author ='" . $_POST['author'][$id] . "',
    				comment ='" . $_POST['comment'][$id] . "',
    				email ='" . $_POST['email'][$id] . "',
    				web ='" . $_POST['web'][$id] . "',
    				authfrom = '" . $_POST['authfrom'][$id] . "'
    				$gisa
    			WHERE
    			  id = '" . $id . "'
    		";

				$q = $GLOBALS['db']->Query($sql);
  		}
  	}

    if(count($_POST['del'])>0) {
      foreach ($_POST['del'] as $id => $del) {
  			$sql =$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_guestbook WHERE id = '" . $id . "'");
  			$sql = $GLOBALS['db']->Query("ALTER TABLE " . PREFIX . "_modul_guestbook PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =1");
	    }
  	}

    header("Location:index.php?do=modules&action=modedit&mod=guestbook&moduleaction=1&cp={$sess}");

  }
}
?>