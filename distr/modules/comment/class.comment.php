<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class Comment {

  var $_comment_link_tpl = 'comments_link.tpl';
  var $_comment_form_tpl = 'comment_form.tpl';
  var $_comment_thankyou_tpl = 'comment_thankyou.tpl';
  var $_edit_link_tpl = 'comment_edit.tpl';
  var $_admin_edit_link_tpl = 'admin_edit.tpl';
  var $_postinfo_tpl = 'comment_info.tpl';
  var $_limit = 15;

  function displayComments() {
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_comments WHERE Id = 1");
    $row = $sql->fetchrow();

    if($row->Aktiv == 1) {
      $GLOBALS['tmpl']->assign('display_comments', 1);
      $gruppen = explode(',', $row->Gruppen);
      if(in_array(UGROUP, $gruppen)) {
        $GLOBALS['tmpl']->assign('cancomment', 1);
      }

      $comments = array();

      $query_status = (UGROUP == 1) ? '' : 'Status = 1 AND';

      $sql_c = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_comment_info WHERE $query_status  DokId = '".(int)$_REQUEST['id']."' AND Elter = 0 ORDER BY Id ASC");
      while($row_c = $sql_c->fetcharray()) {
        $sub_comments = array();
        $sql_sc = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_comment_info WHERE $query_status DokId = '".(int)$_REQUEST['id']."' AND Elter = '".$row_c['Id']."' ORDER BY Id ASC");
        while($row_sc = $sql_sc->fetcharray()) {
          $row_sc['Text'] = nl2br($row_sc['Text']);
          array_push($sub_comments, $row_sc);
        }

        $row_c['Text'] = nl2br($row_c['Text']);
        $row_c['Subcomments'] = $sub_comments;
        array_push($comments, $row_c);
      }

      $sql_closed = $GLOBALS['db']->Query("SELECT Geschlossen FROM " . PREFIX . "_modul_comment_info WHERE DokId = '".(int)$_REQUEST['id']."' LIMIT 1");
      $row_closed = $sql_closed->fetchrow();

      $GLOBALS['tmpl']->assign('closed', @$row_closed->Geschlossen);
      $GLOBALS['tmpl']->assign('ugroup', UGROUP);
      $GLOBALS['tmpl']->assign('comments', $comments);
      $GLOBALS['tmpl']->assign('config_vars', $GLOBALS['mod']['config_vars']);
      $GLOBALS['tmpl']->assign('cp_theme', $GLOBALS['mod']['cp_theme']);
      $GLOBALS['tmpl']->assign('page', base64_encode(redir()));
      $GLOBALS['tmpl']->display($GLOBALS['mod']['tpl_dir'] . $this->_comment_link_tpl);
    }
  }

  function displayForm($cp_theme) {
    $sql_closed = $GLOBALS['db']->Query("SELECT Geschlossen FROM " . PREFIX . "_modul_comment_info WHERE DokId = '".(int)$_REQUEST['docid']."' LIMIT 1");
    $row_closed = @$sql_closed->fetchrow();
    $GLOBALS['tmpl']->assign('closed', @$row_closed->Geschlossen);

    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_comments WHERE Id = 1");
    $row = $sql->fetchrow();
    $gruppen = explode(',', $row->Gruppen);

    if($row->Aktiv == 1 && in_array(UGROUP, $gruppen)) {
      $GLOBALS['tmpl']->assign('cancomment', 1);
    }
    $GLOBALS['tmpl']->assign('MaxZeichen', $row->MaxZeichen);
    $GLOBALS['tmpl']->assign('cp_theme', $cp_theme);
    $GLOBALS['tmpl']->display($GLOBALS['mod']['tpl_dir'] . $this->_comment_form_tpl);
  }

  function sendForm($cp_theme) {

    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_comments WHERE Id = 1");
    $row = $sql->fetchrow();
    $gruppen = explode(',', $row->Gruppen);

    $MaxZeichen = ($row->MaxZeichen != '' && $row->MaxZeichen > 10) ? $row->MaxZeichen : 20;

    $Status = ($row->Zensur == 1) ? 0 : 1;
    $JsAfter = ($row->Zensur == 1) ? $GLOBALS['mod']['config_vars']['COMMENT_AFTER_MODER'] : $GLOBALS['mod']['config_vars']['COMMENT_THANKYOU_TEXT'];

    $Text = substr(htmlspecialchars($_POST['Text']), 0, $MaxZeichen);
    $Text_length = strlen($Text);
    $Text .= ($Text_length > $MaxZeichen) ? '...' : '';
    $Text = cp_parse_string($Text);

    if($row->Aktiv == 1 && in_array(UGROUP, $gruppen) && $Text_length > 3 && !empty($_POST['Author'])) {
      $sql = "INSERT INTO " . PREFIX . "_modul_comment_info (
        Id,
        Elter,
        DokId,
        Author,
        Author_Id,
        AEmail,
        AOrt,
        AWebseite,
        AIp,
        Erstellt,
        Titel,
        Text,
        Status
      ) VALUES (
        '',
        '" . (int)$_POST['Elter'] . "',
        '" . (int)$_POST['DokId'] . "',
        '" . $_POST['Author'] . "',
        '" . @$_SESSION['cp_benutzerid'] . "',
        '" . $_POST['AEmail'] . "',
        '" . $_POST['AOrt'] . "',
        '" . $_POST['AWebseite'] . "',
        '" . $_SERVER['REMOTE_ADDR'] . "',
        '" . time() . "',
        '',
        '" . $Text . "',
        '" . $Status . "'
      )";
      $GLOBALS['db']->Query($sql);
      @$iid = @$GLOBALS['db']->InsertId();

      $globals = new Globals;
      $Mail_Absender = $GLOBALS['globals']->cp_settings("Mail_Absender");
      $Mail_Name = $GLOBALS['globals']->cp_settings("Mail_Name");
      $Page = base64_decode($_POST['page']) . '&subaction=showonly&comment_id=' . $iid . '#' . $iid;

      $message = $GLOBALS['mod']['config_vars']['COMMENT_MESSAGE_ADMIN'];
      $message = str_replace("%COMMENT%", stripslashes($Text), $message);
      $message = str_replace("%N%", "\n", $message);
      $message = str_replace("%PAGE%", $Page, $message);
      $message = str_replace("&amp;", "&", $message);

      $GLOBALS['globals']->cp_mail($Mail_Absender, $message, $GLOBALS['mod']['config_vars']['COMMENT_SUBJECT_MAIL'], $Mail_Absender, $Mail_Name, "text");
    }

    $GLOBALS['tmpl']->assign('JsAfter', $JsAfter);
    $GLOBALS['tmpl']->display($GLOBALS['mod']['tpl_dir'] . $this->_comment_thankyou_tpl);
    echo '<script>window.opener.location.reload();</script>';
  }

  function deleteComment($id) {
    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_comment_info WHERE Id = $id");
    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_comment_info WHERE Elter = $id AND Elter != '0'");
    echo '<script>window.opener.location.reload();window.close();</script>';
  }

  function Lock($id) {
    $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_comment_info SET Status = 0 WHERE Id = $id");
    echo '<script>window.opener.location.reload();window.close();</script>';
  }

  function unLock($id) {
    $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_comment_info SET Status = 1 WHERE Id = $id");
    echo '<script>window.opener.location.reload();window.close();</script>';
  }

  function editComment($id, $false='') {

    if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'send' && $false != 1) {
      $Text = htmlspecialchars($_POST['Text']);
      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_comment_info SET
        Author = '" . $_POST['Author'] . "',
        AEmail = '" . $_POST['AEmail'] . "',
        Text = '" . $Text . "',
        Geaendert = '" . time() . "',
        AOrt = '" . $_POST['AOrt'] . "',
        AWebseite = '" . $_POST['AWebseite'] . "'
        WHERE Id = '" . $_REQUEST['Id'] . "'");


      echo '<script>window.opener.location.reload();window.close();</script>';

    }

    if($false == 1) {
      $GLOBALS['tmpl']->assign('editfalse', 1);
    } else {
      $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_comment_info WHERE Id = $id");
      $row = $sql->fetcharray();
      $GLOBALS['tmpl']->assign('row', $row);
    }

    $sql_mz = $GLOBALS['db']->Query("SELECT MaxZeichen FROM " . PREFIX . "_modul_comments WHERE Id = 1");
    $row_mz = $sql_mz->fetchrow();

    $sql_closed = $GLOBALS['db']->Query("SELECT Geschlossen FROM " . PREFIX . "_modul_comment_info WHERE DokId = '".(int)$_REQUEST['docid']."' LIMIT 1");
    $row_closed = $sql_closed->fetchrow();

    $GLOBALS['tmpl']->assign('closed', $row_closed->Geschlossen);
    $GLOBALS['tmpl']->assign('MaxZeichen', $row_mz->MaxZeichen);
    $GLOBALS['tmpl']->assign('ugroup', UGROUP);
    $GLOBALS['tmpl']->assign('config_vars', $GLOBALS['mod']['config_vars']);
    $GLOBALS['tmpl']->assign('cp_theme', $_REQUEST['cp_theme']);
    $GLOBALS['tmpl']->assign('page', base64_encode(redir()));
    $GLOBALS['tmpl']->display($GLOBALS['mod']['tpl_dir'] . $this->_edit_link_tpl);
  }

  function editCommentAdmin($id, $false='') {

    if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'send' && $false != 1) {
      $Text = htmlspecialchars($_POST['Text']);
      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_comment_info SET
        Author = '" . $_POST['Author'] . "',
        AEmail = '" . $_POST['AEmail'] . "',
        Text = '" . $Text . "',
        Geaendert = '" . time() . "',
        AOrt = '" . $_POST['AOrt'] . "',
        AWebseite = '" . $_POST['AWebseite'] . "'
        WHERE Id = '" . $_REQUEST['Id'] . "'");


      echo '<script>window.opener.location.reload();window.close();</script>';

    }

    if($false == 1) {
      $GLOBALS['tmpl']->assign('editfalse', 1);
    } else {
      $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_comment_info WHERE Id = $id");
      $row = $sql->fetcharray();
      $GLOBALS['tmpl']->assign('row', $row);
    }

    $sql_mz = $GLOBALS['db']->Query("SELECT MaxZeichen FROM " . PREFIX . "_modul_comments WHERE Id = 1");
    $row_mz = $sql_mz->fetchrow();

    $sql_closed = $GLOBALS['db']->Query("SELECT Geschlossen FROM " . PREFIX . "_modul_comment_info WHERE DokId = '".(int)$_REQUEST['docid']."' LIMIT 1");
    $row_closed = $sql_closed->fetchrow();

    $GLOBALS['tmpl']->assign('closed', $row_closed->Geschlossen);
    $GLOBALS['tmpl']->assign('MaxZeichen', $row_mz->MaxZeichen);
    $GLOBALS['tmpl']->assign('ugroup', UGROUP);
    $GLOBALS['tmpl']->assign('config_vars', $GLOBALS['mod']['config_vars']);
    $GLOBALS['tmpl']->assign('cp_theme', $_REQUEST['cp_theme']);
    $GLOBALS['tmpl']->assign('page', base64_encode(redir()));
    $GLOBALS['tmpl']->display($GLOBALS['mod']['tpl_dir'] . $this->_admin_edit_link_tpl);
  }

  function postInfo($AuthorId) {
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_comment_info WHERE Id = '".(int)$_REQUEST['Id']."'");
    $row = $sql->fetcharray();
    $row['AWebseite'] = str_replace('http://', '', $row['AWebseite']);
    $row['AWebseite'] = ($row['AWebseite'] != '') ? '<a target="_blank" href="http://' . $row['AWebseite'] . '">'.$row['AWebseite'] .'</a>' : '';

    $sql = $GLOBALS['db']->Query("SELECT Author_Id FROM " . PREFIX . "_modul_comment_info WHERE Author_Id = '".$row['Author_Id']."' AND Author_Id != '0'");
    $num = $sql->numrows();

    $GLOBALS['tmpl']->assign('c', $row);
    $GLOBALS['tmpl']->assign('num', $num);
    $GLOBALS['tmpl']->assign('cp_theme', $_REQUEST['cp_theme']);
    $GLOBALS['tmpl']->display($GLOBALS['mod']['tpl_dir'] . $this->_postinfo_tpl);
  }

  function close($DokId) {
    $sql = $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_comment_info SET Geschlossen = 1 WHERE DokId = '".$DokId."'");
    echo '<script>window.opener.location.reload();window.close();</script>';
  }

  function open($DokId) {
    $sql = $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_comment_info SET Geschlossen = '0' WHERE DokId = '".$DokId."'");
    echo '<script>window.opener.location.reload();window.close();</script>';
  }

  function showComments($tpl_dir) {
    $sql = $GLOBALS['db']->Query("SELECT
      a.Id,
      a.Titel,
      b.DokId
    FROM
      " . PREFIX . "_documents AS a,
      " . PREFIX . "_modul_comment_info AS b
    WHERE b.DokId = a.Id");

    $num = $sql->numrows();
    while($row = $sql->fetcharray())

    $limit = $this->_limit;
    @$seiten = @ceil($num / $this->_limit);
    $start = prepage() * $this->_limit - $this->_limit;


    $docs = array();

    $def_sort = 'ORDER BY a.Id DESC';
    $def_nav = '&sort=NULL';

    if(isset($_REQUEST['sort']) && $_REQUEST['sort'] != '') {
      switch($_REQUEST['sort']) {

        case 'doc_desc':
          $def_sort = 'ORDER BY CId ASC';
          $def_nav = '&sort=doc_desc';
        break;

        case 'doc_asc':
          $def_sort = 'ORDER BY CId DESC';
          $def_nav = '&sort=doc_asc';
        break;

        case 'comment_desc':
          $def_sort = 'ORDER BY b.Text ASC';
          $def_nav = '&sort=comment_desc';
        break;

        case 'comment_asc':
          $def_sort = 'ORDER BY b.Text DESC';
          $def_nav = '&sort=comment_asc';
        break;

        case 'created_desc':
          $def_sort = 'ORDER BY b.Erstellt ASC';
          $def_nav = '&sort=created_desc';
        break;

        case 'created_asc':
          $def_sort = 'ORDER BY b.Erstellt DESC';
          $def_nav = '&sort=created_asc';
        break;
      }
    }

    $q = "SELECT
      a.Id,
      a.Titel,
      b.Id AS CId,
      b.DokId,
      b.Text,
      b.Erstellt
    FROM
      " . PREFIX . "_documents AS a,
      " . PREFIX . "_modul_comment_info AS b
    WHERE b.DokId = a.Id
    $def_sort
    LIMIT $start,".$this->_limit."";

    $sql = $GLOBALS['db']->Query($q);

    while($row = $sql->fetcharray()) {
      $sql_c = $GLOBALS['db']->Query("SELECT COUNT(Id) AS count_c FROM " . PREFIX . "_modul_comment_info WHERE DokId='".$row['Id']."'");
      $row_c = $sql_c->fetchrow();
      $num_c = $row_c->count_c;

      $row['Comments'] = $num_c;
      array_push($docs, $row);
    }

    $page_nav = pagenav($seiten, prepage()," <a class=\"pnav\" href=\"index.php?do=modules&action=modedit&mod=comment&moduleaction=1&cp=".SESSION."&page={s}$def_nav\">{t}</a> ");
    $GLOBALS['tmpl']->assign('docs', $docs);
    if($num > $this->_limit) $GLOBALS['tmpl']->assign("page_nav", $page_nav);
    $GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'admin_comments.tpl'));
  }

  function listAllGroups() {
    $groups = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_user_groups");
    while($row = $sql->fetchrow()) {
      array_push($groups, $row);
    }
    return $groups;

  }

  function settings($tpl_dir) {
    if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save') {
      $_POST['MaxZeichen'] = (empty($_POST['MaxZeichen']) || $_POST['MaxZeichen'] < 50) ? 50 : $_POST['MaxZeichen'];
      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_comments SET
        MaxZeichen = '" . @(int)$_POST['MaxZeichen'] . "',
        Gruppen = '" . @implode(',',$_POST['Gruppen']) . "',
        Zensur = '" . @(int)$_POST['Zensur'] . "',
        Aktiv = '" . @(int)$_POST['Aktiv'] . "'
        WHERE Id = 1");
    }

    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_comments WHERE Id = 1");
    $row = $sql->fetchrow();
    $row->Gruppen = explode(',', $row->Gruppen);

    $GLOBALS['tmpl']->assign("row", $row);
    $GLOBALS['tmpl']->assign("groups", $this->listAllGroups());
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . 'admin_settings.tpl'));
  }
}
?>