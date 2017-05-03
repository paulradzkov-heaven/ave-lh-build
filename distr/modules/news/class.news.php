<?php
class News {

  var $_categ_limit = 20;
	var $_default_limit = 20;

	function showNews($tpl_dir,$id,$lim) {
		if(!isset($_REQUEST['page'])) $_REQUEST['page'] = 1;
		$where = "AND news = '1'
        			AND Geloescht != '1'
        			AND DokStatus != '0'
        			AND (DokEnde  = '0' || DokEnde  > '" . time() . "')
        			AND (DokStart = '0' || DokStart < '" . time() . "')
          	  ";
		$sql = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_news_rubz WHERE parent = '" . $id . "'");
		$wharr = array();
		while($row = $sql->fetcharray()) {
			$wharr[] = $row['id'];
		}
		if(count($wharr) > 0) {
			$where .= " AND (";
			$i = 0;
			foreach($wharr as $rubid) {
				if($i > 0) $where .= " OR ";
				$where .= "rubid = '" . $rubid . "'";
				$i++;
			}
			$where .= ")";
		} elseif($id > 1) {
			$where .= " AND rubid = '" . $id . "'";
			$sql = $GLOBALS['db']->Query("SELECT parent FROM " . PREFIX . "_modul_news_rubz WHERE id = '" . $id . "'");
			$row = $sql->fetcharray();
			$parent = $row['parent'];
		}
    $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_documents WHERE Id != '2' " . $where);
    $num = $sql->numrows();
    if($lim == '') {
    	$limit = $this->_default_limit;
    } else {
    	$limit = $lim;
    }
    $seiten = ceil($num / $limit);
		if (isset($_REQUEST['page']) && (int)$_REQUEST['page'] > $seiten) $_REQUEST['page'] = $seiten;
		$start = prepage() * $limit - $limit;

  	if (@$_SESSION['comments_enable'] == 1) {
  		$sql = $GLOBALS['db']->Query("SELECT
  		    doc.Id,
  		    Url,
  		    doc.Titel,
  		    DokStart,
  		    Geklickt,
  		    preimage,
  		    pretext,
  		    COUNT(cmnt.Id) AS c_nums
  		  FROM
    			" . PREFIX . "_documents AS doc
    		LEFT JOIN
    			" . PREFIX . "_modul_comment_info AS cmnt ON doc.Id = DokId
  			WHERE doc.Id != '2'
    			" . $where . "
  			GROUP BY doc.Id ORDER BY DokStart DESC
  			LIMIT " . $start . "," . $limit);
  	} else {
  		$sql = $GLOBALS['db']->Query("SELECT
  		    Id,
  		    Url,
  		    Titel,
  		    DokStart,
  		    Geklickt,
  		    preimage,
  		    pretext
  		  FROM
  				" . PREFIX . "_documents
  			WHERE Id != '2'
    			" . $where . "
  			ORDER BY DokStart DESC
  			LIMIT " . $start . "," . $limit);
  	}

    $news = array();
    while($row = $sql->fetcharray()) {
    	$row['date'] = date($GLOBALS['globals']->cp_settings("Zeit_Format"),$row['DokStart']);
			//	формирование ссылок на полную версию новости: doc_rewrite, mod_rewrite, без реврайтов
			if ($GLOBALS['config']['doc_rewrite']) {
      	$row['link'] = $row['Url'];
			} else {
				$row['link'] = (CP_REWRITE==1) ? cp_rewrite('/index.php?id=' . $row['Id'] . '&amp;doc=' . cp_parse_linkname($row['Titel'])) : '/index.php?id=' . $row['Id'] . '&amp;doc=' . cp_parse_linkname($row['Titel']);
			}
			$row['pretext'] = stripslashes($row['pretext']);
    	$news[] = $row;
		}
		$docid = def_id();
		if($docid == 1) {
			$docname = "index";
	  } else {
			$sql = $GLOBALS['db']->Query("SELECT Url,Titel FROM " . PREFIX . "_documents WHERE Id = '" . $docid . "' LIMIT 1");
			$row = $sql->fetcharray();
			if ($GLOBALS['config']['doc_rewrite']) {
      	$docname = $row['Url'];
			} else {
  			$docname = cp_parse_linkname($row['Titel']);
			}
		}
		//	формирование ссылок постраничной навигации
		if ($GLOBALS['config']['doc_rewrite']) { // doc_rewrite
			$page_nav = pagenav($seiten, prepage(), " <a class=\"pnav\" href=\"" . $docname . "page{s}.html\">{t}</a> ");
		} elseif (CP_REWRITE == 1) { // mod_rewrite
			$page_nav = pagenav($seiten, prepage(), " <a class=\"pnav\" href=\"" . cp_rewrite("/index.php?id=" . $docid . "&amp;doc=" . $docname . "&amp;page={s}" ) . "\">{t}</a> ");
		} else { // без реврайтов
			$page_nav = pagenav($seiten, prepage(), " <a class=\"pnav\" href=\"/index.php?id=" . $docid . "&amp;doc=" . $docname . "&amp;page={s}\">{t}</a> ");
		}

		$GLOBALS['tmpl']->assign('news', $news);
    $GLOBALS['tmpl']->assign('page_nav', $page_nav);

    //	выбор шаблона
    if (file_exists($tpl_dir . "news-" . $id . ".tpl")) { // подрубрика
      $GLOBALS['tmpl']->display($tpl_dir . "news-" . $id . ".tpl");
    } elseif (file_exists($tpl_dir . "news-" . $parent . ".tpl")) { // рубрика
      $GLOBALS['tmpl']->display($tpl_dir . "news-" . $parent . ".tpl");
    } else { // общий
      $GLOBALS['tmpl']->display($tpl_dir . "news.tpl");
    }
	}

	function delRub($id) {
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_news_rubz WHERE parent = '" . $id . "'");
		while($row = $sql->fetcharray()) {
			$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_documents WHERE rubid = '" . $row['id'] . "'");
		}
		$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_news_rubz WHERE parent = '" . $id . "'");
		$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_news_rubz WHERE id = '" . $id . "' LIMIT 1");
		header("Location:index.php?do=modules&action=modedit&mod=news&moduleaction=1&cp=" . SESSION . ((isset($_REQUEST['page']) && $_REQUEST['page'] > 1) ? "&page=" . $_REQUEST['page'] : ""));
		exit;
	}

	function delSubrub($id) {
		$sql = $GLOBALS['db']->Query("SELECT parent FROM " . PREFIX . "_modul_news_rubz WHERE id = '" . $id . "'");
		$row = $sql->fetcharray();
		$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_documents WHERE rubid = '" . $id . "'");
		$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_news_rubz WHERE id = '" . $id . "' LIMIT 1");
		header("Location:index.php?do=modules&action=modedit&mod=news&moduleaction=editrub&id=" . $row['parent'] . "&cp=" . SESSION . ((isset($_REQUEST['page']) && $_REQUEST['page'] > 1) ? "&page=" . $_REQUEST['page'] : ""));
		exit;
	}

	function addNews() {
		define("REPLACEMENT", substr($_SERVER['SCRIPT_NAME'], 0, -15));
//		include_once(SOURCE_DIR . "/class/class.rubs.php");
		include_once(SOURCE_DIR . "/class/class.docs2.php");
		include_once(SOURCE_DIR . "/class/class.queries.php");
		include_once(SOURCE_DIR . "/class/class.navigation.php");

		$cpQuery = new Query;
		$cpDoc   = new docs;
//		$cpRub   = new rubs;
		$cpNavi  = new Navigation;

		$cpDoc->rediRubs();
		$cpDoc->tplTimeAssign();

		$GLOBALS['tmpl']->assign("navi", $GLOBALS['tmpl']->fetch("navi/navi.tpl"));

		$GLOBALS['tmpl']->config_load(SOURCE_DIR . "/admin/lang/" . $_SESSION['cp_admin_lang'] . "/docs.txt", 'docs');
		$config_vars = $GLOBALS['tmpl']->get_config_vars();
		$GLOBALS['tmpl']->assign("config_vars", $config_vars);

		$_REQUEST['sub'] = (!isset($_REQUEST['sub'])) ? '' : addslashes($_REQUEST['sub']);
		$_REQUEST['action'] = (!isset($_REQUEST['action'])) ? '' : addslashes($_REQUEST['action']);
		$_REQUEST['submit'] = (!isset($_REQUEST['submit'])) ? '' : addslashes($_REQUEST['submit']);

		if(permCheck('docs')) {
			$cpNavi->showAllEntries();
			$cpQuery->showQueries('extern');
			$cpDoc->newDoc(2);
		}
	}

	function editSubrub($id) {
		define("REPLACEMENT", substr($_SERVER['SCRIPT_NAME'], 0, -15));
//		include_once(SOURCE_DIR . "/class/class.rubs.php");
		include_once(SOURCE_DIR . "/class/class.docs2.php");
//		include_once(SOURCE_DIR . "/class/class.queries.php");
//		include_once(SOURCE_DIR . "/class/class.navigation.php");

//		$cpQuery = new Query;
		$cpDoc   = new docs;
//		$cpRub   = new rubs;
//		$cpNavi  = new Navigation;

		$cpDoc->rediRubs();
		$cpDoc->tplTimeAssign();

		$GLOBALS['tmpl']->assign("navi", $GLOBALS['tmpl']->fetch("navi/navi.tpl"));

		$GLOBALS['tmpl']->config_load(SOURCE_DIR . "/admin/lang/" . $_SESSION['cp_admin_lang'] . "/docs.txt", 'docs');
		$config_vars = $GLOBALS['tmpl']->get_config_vars();
		$GLOBALS['tmpl']->assign("config_vars", $config_vars);

		$_REQUEST['sub'] = (!isset($_REQUEST['sub'])) ? '' : addslashes($_REQUEST['sub']);
		$_REQUEST['action'] = (!isset($_REQUEST['action'])) ? '' : addslashes($_REQUEST['action']);
		$_REQUEST['submit'] = (!isset($_REQUEST['submit'])) ? '' : addslashes($_REQUEST['submit']);

		if(permCheck('docs')) {
			switch($_REQUEST['sub']) {
				// Schnellspeichern
				case 'quicksave':
					$cpDoc->quickSave();
				break;
			}

			// Dokumente auslesen
			$cpDoc->showDocs();

		}
		$sql = $GLOBALS['db']->Query("SELECT parent FROM " . PREFIX . "_modul_news_rubz WHERE id = '" . $id . "'");
		$row = $sql->fetcharray();
		$GLOBALS['tmpl']->assign('parent', $row['parent']);
		$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("documents/docs2.tpl"));
	}

	function editNews() {
		define("REPLACEMENT", substr($_SERVER['SCRIPT_NAME'], 0, -15));
//		include_once(SOURCE_DIR . "/class/class.rubs.php");
		include_once(SOURCE_DIR . "/class/class.docs2.php");
		include_once(SOURCE_DIR . "/class/class.queries.php");
		include_once(SOURCE_DIR . "/class/class.navigation.php");

		$cpQuery = new Query;
		$cpDoc   = new docs;
//		$cpRub   = new rubs;
		$cpNavi  = new Navigation;

		$cpDoc->rediRubs();
		$cpDoc->tplTimeAssign();

		$GLOBALS['tmpl']->assign("navi", $GLOBALS['tmpl']->fetch("navi/navi.tpl"));

		$GLOBALS['tmpl']->config_load(SOURCE_DIR . "/admin/lang/" . $_SESSION['cp_admin_lang'] . "/docs.txt", 'docs');
		$config_vars = $GLOBALS['tmpl']->get_config_vars();
		$GLOBALS['tmpl']->assign("config_vars", $config_vars);

		$_REQUEST['sub'] = (!isset($_REQUEST['sub'])) ? '' : addslashes($_REQUEST['sub']);
		$_REQUEST['action'] = (!isset($_REQUEST['action'])) ? '' : addslashes($_REQUEST['action']);
		$_REQUEST['submit'] = (!isset($_REQUEST['submit'])) ? '' : addslashes($_REQUEST['submit']);

		if(permCheck('docs')) {
			$cpNavi->showAllEntries();
			$cpQuery->showQueries('extern');
			$cpDoc->editDoc($_REQUEST['Id']);
		}
	}

	function adminSettings($tpl_dir) {
		$sql = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_news_rubz WHERE parent = '0'");
		$num = $sql->numrows();

		$limit = $this->_categ_limit;
		$seiten = ceil($num / $limit);
		if (isset($_REQUEST['page']) && (int)$_REQUEST['page'] > $seiten) $_REQUEST['page'] = $seiten;
		$start = prepage() * $limit - $limit;

		$rubs = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_news_rubz WHERE parent = '0' LIMIT " . $start . "," . $limit);
		while($row = $sql->fetcharray()) {
			$id=$row['id'];
			$where="WHERE news = '1'";
			if($id == 1) {
        $tsql = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_news_rubz WHERE parent > '0'");
        $row['subs'] = $GLOBALS['config_vars']['NEWS_RUB_COUNT'] . ($num - 1) . $GLOBALS['config_vars']['NEWS_SUBRUB_COUNT'] . $tsql->numrows();
        $tsql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_documents " . $where);
        $row['news'] = $tsql->numrows();
			} else {
				$tsql = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_news_rubz WHERE parent = '" . $id . "'");
				$wharr=array();
				while($trow = $tsql->fetcharray()) {
					$wharr[] = $trow['id'];
				}
				$row['subs']=count($wharr);
				if($row['subs'] > 0) {
					$where .= " AND (";
					$i = 0;
					foreach($wharr as $rubid) {
						if($i > 0) $where .= " OR ";
						$where .= "rubid = '" . $rubid . "'";
						$i++;
					}
					$where .= ")";
				} elseif($id > 1) {
					$where .= " AND rubid = '" . $id . "'";
				}
        $tsql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_documents " . $where);
        $row['news'] = $tsql->numrows();
		  }
			array_push($rubs,$row);
		}

		$page_nav = pagenav($seiten, prepage()," <a class=\"pnav\" href=\"index.php?do=modules&action=modedit&mod=news&moduleaction=1&cp=" . SESSION . "&page={s}\">{t}</a> ");
		if($num > $limit) {
			$GLOBALS['tmpl']->assign("page_nav", $page_nav);
		}

		$GLOBALS['tmpl']->assign('rubs', $rubs);
		$GLOBALS['tmpl']->assign('formaction', 'index.php?do=modules&action=modedit&mod=news&moduleaction=new&sub=save&cp=' . SESSION . '&page=' . $seiten);
		$GLOBALS['tmpl']->assign('sess', SESSION);
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'admin_settings.tpl'));
	}

	function newRub() {
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save') {
			if(isset($_POST['name']) AND $_POST['name'] != "") {
				$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_news_rubz (parent,name) VALUES ('0','" . $_POST['name'] . "')");
			}
			header("Location:index.php?do=modules&action=modedit&mod=news&moduleaction=1&cp=" . SESSION . ((isset($_REQUEST['page']) && $_REQUEST['page'] > 1) ? "&page=" . $_REQUEST['page'] : ""));
			exit;
		} else {
			header("Location:index.php?do=modules&action=modedit&mod=news&moduleaction=1&cp=" . SESSION . ((isset($_REQUEST['page']) && $_REQUEST['page'] > 1) ? "&page=" . $_REQUEST['page'] : ""));
			exit;
		}
	}

	function newSubrub() {
		if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save') {
			if(isset($_POST['name']) AND $_POST['name'] != "") {
				$GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_news_rubz (parent,name) VALUES ('" . $_POST['id'] . "','" . $_POST['name'] . "')");
			}
			header("Location:index.php?do=modules&action=modedit&mod=news&moduleaction=editrub&id=" . $_POST['id'] . "&cp=" . SESSION . ((isset($_REQUEST['page']) && (int)$_REQUEST['page'] > 1) ? "&page=" . $_REQUEST['page'] : ""));
			exit;
		} else {
			header("Location:index.php?do=modules&action=modedit&mod=news&moduleaction=editrub&id=" . $_POST['id'] . "&cp=" . SESSION . ((isset($_REQUEST['page']) && (int)$_REQUEST['page'] > 1) ? "&page=" . $_REQUEST['page'] : ""));
			exit;
		}
	}

	function editRub($tpl_dir,$id) {
		$sql = $GLOBALS['db']->Query("SELECT id FROM " . PREFIX . "_modul_news_rubz WHERE parent = '" . $id . "'");
		$num = $sql->numrows();

		$limit = $this->_categ_limit;
		$seiten = ceil($num / $limit);
		if (isset($_REQUEST['page']) && (int)$_REQUEST['page'] > $seiten) $_REQUEST['page'] = $seiten;
		$start = prepage() * $limit - $limit;

		$rubs = array();
		$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_news_rubz WHERE parent = '" . $id . "' LIMIT " . $start . "," . $limit);
		while($row = $sql->fetcharray()) {
			$tsql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_documents WHERE news = '1' AND rubid = '" . $row['id'] . "'");
			$row['news'] = $tsql->numrows();
			array_push($rubs,$row);
		}

		if($num > $limit) {
			$page_nav = pagenav($seiten, prepage()," <a class=\"pnav\" href=\"index.php?do=modules&action=modedit&mod=news&moduleaction=editrub&id=" . $id . "&cp=" . SESSION . "&page={s}\">{t}</a> ");
			$GLOBALS['tmpl']->assign("page_nav", $page_nav);
		}

		$GLOBALS['tmpl']->assign('id', $id);
		$GLOBALS['tmpl']->assign('rubs', $rubs);
		$GLOBALS['tmpl']->assign('formaction', 'index.php?do=modules&action=modedit&mod=news&moduleaction=newsr&sub=save&cp=' . SESSION . ((isset($_REQUEST['page']) && (int)$_REQUEST['page'] > 1) ? "&page=" . $_REQUEST['page'] : ""));
		$GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'admin_editrub.tpl'));
	}
}
?>