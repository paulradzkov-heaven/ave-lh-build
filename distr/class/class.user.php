<?php
/*::::::::::::::::::::::::::::::::::::::::
System name: cpengine
Short Desc: Full Russian Security Power Pack
Version: 2.0 (Service Pack 2)
Authors:  Arcanum (php@211.ru) &  Censored!
Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class cpUser {
  var $_limit = 15;
  var $_available_countries = array("ru", "de", "cz", "ch", "dk", "en", "fr", "at", "gb", "it", "es", "be", "se", "gr");
  var $_allperms_admin = array(
  'adminpanel',
  'gen_settings',
  'logs',
  'alles',
  'dbactions',
  'modules',
  'modules_admin',
  'navigation',
  'navigation_edit',
  'navigation_new',
  'docs',
  'docs_php',
  'docs_comments',
  'docs_comments_del',
  'vorlagen',
  'vorlagen_multi',
  'vorlagen_loesch',
  'vorlagen_edit',
  'vorlagen_php',
  'vorlagen_neu',
  'rubs',
  'rub_neu',
  'rub_edit',
  'rub_loesch',
  'rub_multi',
  'rub_perms',
  'rub_php',
  'abfragen',
  'abfragen_neu',
  'abfragen_loesch',
  'user',
  'user_new',
  'user_edit',
  'user_loesch',
  'user_perms',
  'group',
  'group_edit',
  'group_new',
  'mediapool',
  'mediapool_del'
  );

  function getModules() {

    $modules = array();

    $sql = $GLOBALS['db']->Query("SELECT ModulPfad,ModulName FROM " . PREFIX . "_module");

    while($row = $sql->fetchrow()) {
      $row->ModulPfad = 'mod_' . $row->ModulPfad;
      array_push($modules, $row);
    }
    return $modules;
  }

  function allPerms($front = '0') {
    $allperms_admin = $this->_allperms_admin;
    $allperms_front = array('adminpanel');

    if($front == 1)
    return $allperms_front;
    else
    return $allperms_admin;
  }

  function fetchAllPerms($group = '0') {
    $extra = '';

    if($group != 0) $extra = "WHERE Benutzergruppe = '$group'";
    $sql = $GLOBALS['db']->Query("SELECT Rechte FROM " . PREFIX . "_user_permissions $extra");
    $row = $sql->fetchrow();

    if(!$row) {
      $GLOBALS['tmpl']->assign("no_group", 1);
    } else {
      $group_permissions = explode("|",$row->Rechte);
      $all_permissions = $this->allPerms();

      $GLOBALS['tmpl']->assign("g_all_permissions", $all_permissions);
      $GLOBALS['tmpl']->assign("g_group_permissions", $group_permissions);
    }
  }

  function fetchGroupNameById($id = '') {
    $sql = $GLOBALS['db']->Query("SELECT Name FROM " . PREFIX . "_user_groups WHERE Benutzergruppe = '$id'");
    $row = $sql->fetchrow();
    $sql->Close();
    return $row->Name;
  }

  function userCountGroup($group = '0') {
    $group = ($group != '0') ? $group : 1;
    $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_users WHERE Benutzergruppe='$group'");
    $row->Ucount = $sql->numrows();
    $sql->Close();
    return $row->Ucount;
  }

  function listAllGroups($ex = '') {
    $exclude = "";
    $sugroups = array();
    if($ex != '') $exclude = " WHERE Benutzergruppe != 2";
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_user_groups $exclude");

    while($row = $sql->fetchrow()) {
      if($row->Benutzergruppe == 1) $row->FieldDisabled = 1;
      $row->UserCount = $this->userCountGroup($row->Benutzergruppe);
      array_push($sugroups, $row);
    }
    return $sugroups;
  }

  function listUser($gruppe = '') {
    $search_by_group = "";
    $search_by_id_or_name = "";
    $gruppe_navi = "";
    $query_navi = "";
    $status_search = "";
    $status_navi = "";

    if(isset($_REQUEST['Benutzergruppe']) && $_REQUEST['Benutzergruppe'] != '0') {
      $gruppe = ($gruppe != '') ? $gruppe : $_REQUEST['Benutzergruppe'];
      $gruppe_navi = "&amp;Benutzergruppe=$gruppe";
      $search_by_group = " AND Benutzergruppe='$gruppe' ";
    }

    if(isset($_REQUEST['query']) && $_REQUEST['query'] != '') {
      $q = addslashes($_REQUEST['query']);
      $email_domain = explode("@", $q);
      $search_by_id_or_name = " AND (Email like '%".$q. "%' OR Email = '" .$q. "' OR Id='$q' OR Vorname like '" .$q. "%' OR Nachname like '" .$q. "%') ";
      $query_navi = "&amp;query=".$_REQUEST['query']. "";
    }

    if(isset($_REQUEST['Status']) && $_REQUEST['Status'] != 'all') {
      $status_search = " AND (Status = '" .$_REQUEST['Status']. "')";
      $status_navi   = "&amp;Status=".$_REQUEST['Status']. "";
    }


    $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_users WHERE Id > 0 $search_by_group $search_by_id_or_name $status_search");
    $num_user = $sql->numrows();

    $page_limit = $this->_limit;
    $seiten     = ceil($num_user / $page_limit);
    $set_start  = prepage() * $page_limit - $page_limit;

    $users = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_users WHERE Id > 0 $search_by_group $search_by_id_or_name $status_search LIMIT $set_start,$page_limit ");

    while($row = $sql->fetchrow()) {

      if(@file_exists(BASE_DIR . '/modules/shop/modul.php')) {
        $row->IsShop = 1;
        $sql_o = $GLOBALS['db']->Query("SELECT DISTINCT(Id) FROM " . PREFIX . "_modul_shop_bestellungen WHERE Benutzer = '$row->Id'");
        $orders_o = $sql_o->numrows();
        $row->Orders = $orders_o;
      }
      array_push($users,$row);
    }

    $page_nav = pagenav($seiten, prepage(), " <a class=\"pnav\" href=\"index.php?do=user$status_navi&page={s}&amp;cp=".SESSION. "$gruppe_navi$query_navi\">{t}</a> ");
    if($num_user > $page_limit) $GLOBALS['tmpl']->assign("page_nav", $page_nav);

    $GLOBALS['tmpl']->assign("ugroups", $this->listAllGroups());
    $GLOBALS['tmpl']->assign("users", $users);
  }

  function savePerms($id, $perms = '') {
    $id = (int)$id;
    $perms = (isset($_REQUEST['perms']) &&  is_array($_REQUEST['perms']) && !empty($_REQUEST['perms'])) ? implode("|", $_REQUEST['perms']) : "";
    $perms = ($id == 1 || in_array('alles', $_REQUEST['perms']) ) ? 'alles' : $perms;

    if(!empty($_POST['Name'])) $GLOBALS['db']->Query("UPDATE " . PREFIX . "_user_groups SET Name = '" .$_POST['Name']. "' WHERE Benutzergruppe = '$id'");

    $GLOBALS['db']->Query("UPDATE " . PREFIX . "_user_permissions SET Rechte = '$perms' WHERE BenutzerGruppe = '$id'");

    reportLog($_SESSION["cp_uname"] . " - Изменил права доступа для группы ($id)",'2','2');
    header("Location:index.php?do=groups&cp=".SESSION. "");
    exit;
  }

  function newGroup() {
    if(!empty($_POST['Name'])) {
      $sql = $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_user_groups (Benutzergruppe,Name,Aktiv) VALUES ('','" .htmlspecialchars($_POST['Name']). "','1')");
      $iid = $GLOBALS['db']->InsertId();
      $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_user_permissions (Id,Benutzergruppe,Rechte) VALUES ('','$iid','')");

      reportLog($_SESSION["cp_uname"] . " - Создал группу пользователей ($iid)",'2','2');
      header("Location:index.php?do=groups&action=grouprights&cp=".SESSION. "&Id=".$iid. "");
    } else {
      header("Location:index.php?do=groups&cp=".SESSION. "");
    }
  }

  function delGroup($id) {
    $count = $this->userCountGroup($id);

    if($count > 0 || $id == 1 || $id == 2 || $id == 3 || $id == 4) {
      header("Location:index.php?do=groups&cp=".SESSION. "");
    } else {
      $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_user_groups WHERE Benutzergruppe = '$id'");
      $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_user_permissions WHERE BenutzerGruppe = '$id'");

      reportLog($_SESSION["cp_uname"] . " - Удалил группу пользователей ($id)",'2','2');
      header("Location:index.php?do=groups&cp=".SESSION. "");
    }
  }

  function checkFields($new = '0') {
    $errors = array();

    $kennwort   = $_POST['Kennwort'];
    $muster     = "[^ _A-Za-zА-Яа-яЁё0-9-]";
    $muster_pw  = "[^_A-Za-zА-Яа-яЁё0-9-]";
    $muster_geb = "([0-9]{2}).([0-9]{2}).([0-9]{4})";

    if(empty($_POST['UserName']))  array_push($errors, @$GLOBALS['config_vars']['USER_NO_USERNAME']);
    if(ereg($muster, $_POST['UserName'])) array_push($errors, @$GLOBALS['config_vars']['USER_ERROR_USERNAME']);

//    if(empty($_POST['Vorname']))  array_push($errors, @$GLOBALS['config_vars']['USER_NO_FIRSTNAME']);
//    if(ereg($muster, $_POST['Vorname'])) array_push($errors, @$GLOBALS['config_vars']['USER_ERROR_FIRSTNAME']);

//    if(empty($_POST['Nachname'])) array_push($errors, @$GLOBALS['config_vars']['USER_NO_LASTNAME']);
//    if(ereg($muster, $_POST['Nachname'])) array_push($errors, @$GLOBALS['config_vars']['USER_ERROR_LASTNAME']);

    if(empty($_POST['Email']))    array_push($errors, @$GLOBALS['config_vars']['USER_NO_EMAIL']);
    if(!ereg("^[ -._A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,4})$", $_POST['Email'])) array_push($errors, @$GLOBALS['config_vars']['USER_EMAIL_ERROR']);

    if(isset($_REQUEST['action']) && $_REQUEST['action'] != "edit") {
      if(empty($kennwort)) array_push($errors, @$GLOBALS['config_vars']['USER_NO_PASSWORD']);
      if(strlen($kennwort) < 4) array_push($errors, @$GLOBALS['config_vars']['USER_PASSWORD_SHORT']);
      if(ereg($muster_pw, $kennwort)) array_push($errors, @$GLOBALS['config_vars']['USER_PASSWORD_ERROR']);
    }

    if(!empty($_POST['GebTag']) && !ereg($muster_geb, $_POST['GebTag'])) array_push($errors, @$GLOBALS['config_vars']['USER_ERROR_DATEFORMAT']);

    $extra = ($new != 1) ? " AND Email != '" .$_SESSION['cp_email']. "'" : "";

    $sql = $GLOBALS['db']->Query("SELECT Email FROM " . PREFIX . "_users WHERE Email != '" .$_REQUEST['Email_Old']. "' AND Email = '" .$_POST['Email']. "' $extra");
    $num = $sql->numrows();

    if($num > 0) array_push($errors, @$GLOBALS['config_vars']['USER_EMAIL_EXIST']);

    return $errors;
  }

  function getPost() {
    while (list($key, $val) = each($_POST)) {
      $row->$key = htmlspecialchars(stripslashes($val));
    }

    return $row;
  }

  function editUser($id) {
    switch($_REQUEST['sub']) {

      case '':
        $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_users WHERE Id = '" .$id. "'");
        $row = $sql->fetchrow();
        $GLOBALS['tmpl']->assign("row", $row);

        $sql_fp = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_forum_userprofile WHERE BenutzerId = '" .$id. "'");
        $row_fp = $sql_fp->fetchrow();

        if(is_object($row_fp)) {
          $GLOBALS['tmpl']->assign('row_fp', $row_fp);
          $GLOBALS['tmpl']->assign('is_shop', 1);
        }

        $globals = new Globals;
        $GLOBALS['tmpl']->assign("available_countries", $GLOBALS['globals']->fetchCountries());
        $GLOBALS['tmpl']->assign("BenutzergruppeMisc", explode(";", $row->BenutzergruppeMisc));
        $GLOBALS['tmpl']->assign("ugroups", $this->listAllGroups(2));
        $GLOBALS['tmpl']->assign("formaction", "index.php?do=user&amp;action=edit&amp;sub=save&amp;cp=".SESSION. "&amp;Id=$id");
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("user/form.tpl"));
        break;

      case 'save':
        $ok = true;
        $errors = $this->checkFields(0);
        if(count($errors) > 0) $ok = false;

        $globals = new Globals;
        $SystemMail = $GLOBALS['globals']->cp_settings("Mail_Absender");
        $SystemMailName = $GLOBALS['globals']->cp_settings("Mail_Name");

        if($ok==true) {

          if(isset($_REQUEST['Kennwort']) && $_REQUEST['Kennwort'] != "") {

            $pass = "Kennwort = '" .md5(md5($_REQUEST['Kennwort'])). "',";
            $mailpasschange = true;
          } else {

            $pass = "";
            $mailpasschange = false;
          }

          $ugroup = ($_SESSION["cp_benutzerid"] != $_REQUEST['Id']) ? "Benutzergruppe = '" .$_REQUEST['Benutzergruppe']. "'," : "";

          $sql = $GLOBALS['db']->Query("UPDATE " . PREFIX . "_users
            SET
              $pass
              Email = '" .$_REQUEST['Email']. "',
              Strasse = '" .$_REQUEST['Strasse']. "',
              HausNr = '" .$_REQUEST['HausNr']. "',
              Postleitzahl = '" .$_REQUEST['Postleitzahl']. "',
              Ort = '" .$_REQUEST['Ort']. "',
              Telefon = '" .$_REQUEST['Telefon']. "',
              Telefax = '" .$_REQUEST['Telefax']. "',
              Bemerkungen = '" .$_REQUEST['Bemerkungen']. "',
              Vorname = '" .$_REQUEST['Vorname']. "',
              Nachname = '" .$_REQUEST['Nachname']. "',
              `UserName` = '" .@$_REQUEST['UserName']. "',
              $ugroup
              Status = '" .$_REQUEST['Status']. "',
              Land = '" .$_REQUEST['Land']. "',
              GebTag = '" .$_REQUEST['GebTag']. "',
              UStPflichtig = '".$_REQUEST['UStPflichtig']."',
              Firma = '".$_REQUEST['Firma']."',
              BenutzergruppeMisc = '" . @implode(";", $_REQUEST['BenutzergruppeMisc']) . "'
            WHERE
              Id = '" . $_REQUEST['Id'] . "'");

          $sql = $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_forum_userprofile
            SET
              GroupIdMisc = '" . @implode(";", $_REQUEST['BenutzergruppeMisc']) . "',
              BenutzerName = '" . @addslashes($_REQUEST['BenutzerName_fp']). "',
              Signatur = '" . @addslashes($_REQUEST['Signatur_fp']) . "' ,
              Avatar = '" . @addslashes($_REQUEST['Avatar_fp']) . "'
            WHERE
              BenutzerId='" . $_REQUEST['Id'] . "'");

          if($_REQUEST['Status']==1 && @$_REQUEST['SendFreeMail']==1) {
            $host = explode("?", redir());
            $host_real  = substr($host[0],0,-15);
            $body_start = $GLOBALS['config_vars']['USER_MAIL_BODY1'];
            $body_start = str_replace("%USER%", $_REQUEST['UserName'], $body_start);
            $body_start .= str_replace("%HOST%", $host_real, $GLOBALS['config_vars']['USER_MAIL_BODY2']);
            $body_start .= str_replace("%HOMEPAGENAME%", $GLOBALS['globals']->cp_settings("Seiten_Name"), $GLOBALS['config_vars']['USER_MAIL_FOOTER']);
            $body_start = str_replace("%N%", "\n", $body_start);
            $body_start = str_replace("%HOST%", $host_real, $body_start);

            $GLOBALS['globals']->cp_mail($_POST['Email'],  $body_start, $GLOBALS['config_vars']['USER_MAIL_SUBJECT'], $SystemMail, $SystemMailName . " (" . $GLOBALS['globals']->cp_settings("Seiten_Name") . ")", "text", "");
          }

          if($mailpasschange==true && $_REQUEST['PassChange']==1) {
            $host = explode("?", redir());
            $host_real  = substr($host[0],0,-15);
            $body_start = $GLOBALS['config_vars']['USER_MAIL_BODY1'];
            $body_start = str_replace("%USER%", $_REQUEST['UserName'], $body_start);
            $body_start .= str_replace("%HOST%", $host_real, $GLOBALS['config_vars']['USER_MAIL_PASSWORD2']);
            $body_start = str_replace("%NEWPASS%", $_REQUEST['Kennwort'], $body_start);
            $body_start .= str_replace("%HOMEPAGENAME%", $GLOBALS['globals']->cp_settings("Seiten_Name"), $GLOBALS['config_vars']['USER_MAIL_FOOTER']);
            $body_start = str_replace("%N%", "\n", $body_start);
            $body_start = str_replace("%HOST%", $host_real, $body_start);

            $GLOBALS['globals']->cp_mail($_POST['Email'], $body_start, $GLOBALS['config_vars']['USER_MAIL_PASSWORD'], $SystemMail, $SystemMailName . " (" . $GLOBALS['globals']->cp_settings("Seiten_Name") . ")", "text", "");
          }

          if($_REQUEST['SimpleMessage'] != '') {
            $message = stripslashes($_REQUEST['SimpleMessage']);
            $GLOBALS['globals']->cp_mail($_POST['Email'],  $message, $_REQUEST['SubjectMessage'], $_SESSION["cp_email"], $_SESSION["cp_uname"], "text", "");
          }

          if($_REQUEST['Id'] == $_SESSION['cp_benutzerid'] && $mailpasschange==true) {
            $_SESSION['cp_kennwort'] = md5(md5($_POST['Kennwort']));
            $_SESSION['cp_email'] = $_POST['Email'];
          }

          reportLog($_SESSION["cp_uname"] . " - Отредактировал параметры пользователя (".$_POST['UserName'].")",'2','2');
          header("Location:index.php?do=user&cp=".SESSION. "");
          exit;

        } else {
          $row = $this->getPost();

          $globals = new Globals;
          $GLOBALS['tmpl']->assign("available_countries", $GLOBALS['globals']->fetchCountries());
          $GLOBALS['tmpl']->assign("row", $row);
          $GLOBALS['tmpl']->assign("errors", $errors);
          $GLOBALS['tmpl']->assign("ugroups", $this->listAllGroups(2));
          $GLOBALS['tmpl']->assign("formaction", "index.php?do=user&amp;action=edit&amp;sub=save&amp;cp=".SESSION. "&amp;Id=$id");
          $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("user/form.tpl"));
        }

        break;
    }
  }

  function newUser() {
    switch($_REQUEST['sub']) {

      case 'save':
        $ok = true;
        $errors = $this->checkFields(1);
        if(count($errors) > 0) $ok = false;

        if($ok) {

          $sql = "INSERT INTO " . PREFIX . "_users
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
            Registriert,
            Status,
            ZuletztGesehen,
            Land,
            GebTag,
            Firma,
            UStPflichtig,
            BenutzergruppeMisc
          ) VALUES (
            '',
            '" .md5(md5($_POST['Kennwort'])). "',
            '$_POST[Email]',
            '$_POST[Strasse]',
            '$_POST[HausNr]',
            '$_POST[Postleitzahl]',
            '$_POST[Ort]',
            '$_POST[Telefon]',
            '$_POST[Telefax]',
            '$_POST[Bemerkungen]',
            '$_POST[Vorname]',
            '$_POST[Nachname]',
            '$_POST[Username]',
            '$_POST[Benutzergruppe]',
            '" .time(). "',
            '" .$_REQUEST['Status']. "',
            '" .time(). "',
            '$_POST[Land]',
            '$_POST[GebTag]',
            '$_POST[Firma]',
            '$_POST[UStPflichtig]',
            '" . @implode(";", $_REQUEST['BenutzergruppeMisc']) . "'

          )
          ";

          $GLOBALS['db']->Query($sql);

          $host = explode("?", redir());
          $message = $GLOBALS['globals']->cp_settings("Mail_Text_NeuReg");
          $message = str_replace("%NAME%", $_POST['UserName'], $message);
          $message = str_replace("%HOST%", $host[0], $message);
          $message = str_replace("%KENNWORT%", $_POST['Kennwort'], $message);
          $message = str_replace("%EMAIL%", $_POST['Email'], $message);
          $message = str_replace("%EMAILFUSS%", $GLOBALS['globals']->cp_settings("Mail_Text_Fuss"), $message);

          $GLOBALS['globals']->cp_mail($_POST['Email'],$message, $GLOBALS['config_vars']['USER_MAIL_SUBJECT']);

          // Log
          reportLog($_SESSION["cp_uname"] . " - Добавил пользователя (".$_POST['UserName'].")",'2','2');

          header("Location:index.php?do=user&cp=".SESSION. "");

        } else {


          while (list($key, $val) = each($_POST)) {
            $row->$key = htmlspecialchars(stripslashes($val));
          }

          $globals = new Globals;
          $GLOBALS['tmpl']->assign("available_countries", $GLOBALS['globals']->fetchCountries());
          $GLOBALS['tmpl']->assign("row", $row);
          $GLOBALS['tmpl']->assign("errors", $errors);
          $GLOBALS['tmpl']->assign("ugroups", $this->listAllGroups(2));
          $GLOBALS['tmpl']->assign("formaction", "index.php?do=user&amp;action=new&amp;sub=save&amp;cp=".SESSION. "");
          $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("user/form.tpl"));
        }

        break;

      case '':
        $globals = new Globals;
        $GLOBALS['tmpl']->assign("available_countries", $GLOBALS['globals']->fetchCountries());
        $GLOBALS['tmpl']->assign("ugroups", $this->listAllGroups(2));
        $GLOBALS['tmpl']->assign("formaction", "index.php?do=user&amp;action=new&amp;sub=save&amp;cp=".SESSION. "");
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("user/form.tpl"));
        break;
    }
  }

  function deleteUser($id) {

    if($id != 1) {
      $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_users WHERE Id = '" .$id. "'");
      $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_forum_userprofile WHERE BenutzerId = '$id'");
      reportLog($_SESSION["cp_uname"] . " - Удалил пользователя ($id)",'2','2');
    }

    header("Location:index.php?do=user&cp=".SESSION. "");
  }

  function quicksaveUser() {

    foreach ($_POST['del'] as $id => $del) {
      $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_users WHERE Id = '$id'");
      reportLog($_SESSION["cp_uname"] . " - Удалил пользователя (".$id. ")",'2','2');
    }

    foreach ($_POST['Benutzergruppe'] as $id => $bG) {

      if(!empty($_POST['Benutzergruppe'])) {
        $GLOBALS['db']->Query("UPDATE " . PREFIX . "_users SET  Benutzergruppe = '" .$_POST['Benutzergruppe'][$id]. "' WHERE Id='" .$id. "'");
        reportLog($_SESSION["cp_uname"] . " - Изменил группу для пользователя (".$id. ")",'2','2');
      }
    }

    header("Location:index.php?do=user&cp=".SESSION. "");
    exit;
  }
}
?>