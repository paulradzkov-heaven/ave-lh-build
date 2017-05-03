<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class Contact {

  var $_adminlimit = 15;
  var $_delfile    = 1;

  function fetchForm($tpl_dir,$lang_file,$id,$im="",$maxupload="0",$fetch="0") {

    $id = stripslashes($id);
    $GLOBALS['tmpl']->config_load($lang_file);
    $config_vars = $GLOBALS['tmpl']->get_config_vars();

    $sqls = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_contacts WHERE Id='$id'");
    $rows = $sqls->fetchrow();

    $empaenger = "";
    if($rows->Empfaenger_Multi != "") {
      $empaenger = array();
      $e = explode(";", $rows->Empfaenger_Multi);
      foreach($e as $em) {
        $e_name = explode(",", $em);
        $empaenger[] = $e_name[0];
        $em = "";
      }
    }

    $GLOBALS['tmpl']->assign("empaenger", $empaenger);
    if($rows->ZeigeBetreff=='0' && $rows->StandardBetreff!='') $GLOBALS['tmpl']->assign("StandardBetreff", $rows->StandardBetreff);

    $felder = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_contact_fields WHERE Aktiv='1' AND KontaktId='$id' ORDER BY Position ASC");
    while($row = $sql->fetchrow()) {
      if($row->Feld == 'dropdown' && $row->StdWert != "") {
        $value = explode(",", $row->StdWert);
        $row->StdWert = $value;
      }

      $row->value = (isset($_REQUEST[str_replace(" ", "_", $row->FeldTitel)]) && $_REQUEST[str_replace(" ", "_", $row->FeldTitel)]!="") ? $_REQUEST[str_replace(" ", "_", $row->FeldTitel)] : "" ;
      array_push($felder,$row);
    }

//   @$_REQUEST["doc"] = (!isset($_REQUEST["doc"]) && $_REQUEST['doc'] == "")  ? "mail" : $_REQUEST['doc'];


    $AllowedGroups = @explode(",", $rows->Gruppen);

    $GLOBALS['tmpl']->assign("felder", $felder);
    $GLOBALS['tmpl']->assign("im", $im);
    $GLOBALS['tmpl']->assign("fid", $id);
    $GLOBALS['tmpl']->assign("maxupload", $maxupload);
    $GLOBALS['tmpl']->assign("formid", $this->secureCode());
// Переписано!
//   $GLOBALS['tmpl']->assign("contact_action", ((CP_REWRITE == 1) ? cp_rewrite("/index.php?id=".$_REQUEST['id']."&amp;doc=".$_REQUEST['doc']."") : "/index.php?id=".$_REQUEST['id']."&amp;doc=".htmlspecialchars($_REQUEST['doc'])."'"));
    $GLOBALS['tmpl']->assign("contact_action",'/mail-'.$_REQUEST['id'].'.html');
    $GLOBALS['tmpl']->assign("config_vars", $config_vars);
    $GLOBALS['tmpl']->assign("ZeigeKopie", $rows->ZeigeKopie);

    if(!in_array($_SESSION["cp_ugroup"],$AllowedGroups)) {
      $GLOBALS['tmpl']->assign("no_access", 1);
      $GLOBALS['tmpl']->assign("TextKeinZugriff", $rows->TextKeinZugriff);
    }

    if($fetch==1) {
      $out = $GLOBALS['tmpl']->fetch($tpl_dir. "form.tpl");
      return $out;
    } else {
    $GLOBALS['tmpl']->display($tpl_dir. "form.tpl");
  }
  }

  function thankyou($tpl_dir,$lang_file) {
    $maxup  = '0';
    $codeid = $this->secureCode();

    $GLOBALS['tmpl']->config_load($lang_file);
    $config_vars = $GLOBALS['tmpl']->get_config_vars();

    $sql = $GLOBALS['db']->Query("SELECT AntiSpam,MaxUpload FROM " . PREFIX . "_modul_contacts WHERE Id = '" . addslashes(@$_POST['FId']) . "'");
    $row = $sql->fetchrow();
    $sql->Close();

    if(is_object($row)) {
      $codeid = (defined("ANTI_SPAMIMAGE") && $row->AntiSpam == 1) ? $codeid : "";
      $maxup = $row->MaxUpload;
    }

    $GLOBALS['tmpl']->assign('form', $this->fetchForm($tpl_dir,$lang_file, addslashes(@$_POST['FId']), $codeid, $maxup, 1 ));
    $GLOBALS['tmpl']->display($tpl_dir. "thankyou.tpl");

  }

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
    $sql = $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_antispam (Id,Code,Ctime) VALUES ('','" .$code. "','" .time(). "')");
    $codeid = $GLOBALS['db']->InsertId();

    return $codeid;
  }

  function sendSecure($tpl_dir,$lang_file,$id,$secure="0",$maxupload="0") {
    $return = true;

    $GLOBALS['tmpl']->config_load($lang_file);
    $config_vars = $GLOBALS['tmpl']->get_config_vars();


    if($secure == 1) {
      $sql = $GLOBALS['db']->Query("SELECT Code FROM " . PREFIX . "_antispam WHERE Id='" . (int)$_REQUEST['SecureImageId'] . "'");
      $row = $sql->fetchrow();

      if($row->Code != $_REQUEST['cpSecurecode']) {
        $return = false;
        $sql = $GLOBALS['db']->Query("SELECT AntiSpam,MaxUpload FROM " . PREFIX . "_modul_contacts WHERE Id = '" . addslashes(@$_POST['FId']) . "'");
        $row = $sql->fetchrow();
        $sql->Close();

        if(is_object($row)) {
          $maxup = $row->MaxUpload;
          echo $maxup;
          $GLOBALS['tmpl']->assign("maxupload", $maxup);
        }

        $GLOBALS['tmpl']->assign("wrongSecureCode", 1);
      }
    }


    if($return==false) {
      $codeid = $this->secureCode();
      $im = $codeid;
      $this->fetchForm($tpl_dir,$lang_file,$id,$im);
    } else  {
      $mail = true;

      if(isset($_REQUEST['FormId']) && $_REQUEST['FormId'] != "") {
        $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_contact_info WHERE FormId='" .(int)$_REQUEST['FormId']. "'");
        $num = $sql->numrows();
        if($num == 1) $mail = false;
      }

      if($mail) {

        $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_contacts WHERE Id = '" . (int)$_REQUEST['FId'] . "'");
        $row = $sql->fetchrow();

        if(isset($_REQUEST['Reciever']) && $_REQUEST['Reciever'] != "") {
          $_REQUEST['Reciever'] = $_REQUEST['Reciever']-1;
          $arr = explode(";", $row->Empfaenger_Multi);
          $i = 0;

          while (list(, $value) = each ($arr)) {
            $tom = explode(",", $value);
            $multi_e[$i]['email'] = $tom[1];
            if($i == $_REQUEST['Reciever']) {
              $row->Empfaenger = $multi_e[$i]['email'];
            }
            $i++;
          }
        }

        $this->thankyou($tpl_dir,$lang_file);
        $attach = $this->uploadFile($maxupload);
        @reset($_POST);
        $newtext = "";

        while (list($key, $val) = each($_POST)) {
          if (!empty($val) && $key!= "contact_action" && $key != "sendcopy" &&  $key != "Reciever" && $key != "FormId" && $key != "FId" && $key != "SecureImageId" && $key != "action" && $key != "modules" && $key != "cpSecurecode") {
            $key = ($key == 'Betreff') ? 'Тема сообщения' : $key;
            $key = ($key == 'Email') ? 'Контактный E-mail' : $key;
            $newtext .= str_replace("_", " ", $key);
            $newtext .= ":  ";
            $newtext .= $val;
            $newtext .= "\n\n";
          }
        }
        $text = strip_tags($newtext);
        $Anhang = (is_array($attach) && count($attach) >= 1) ? implode(";", $attach) : "";

        $globals = new Globals;

        $GLOBALS['tmpl']->config_load($lang_file);
        $config_vars = $GLOBALS['tmpl']->get_config_vars();

        $GLOBALS['globals']->cp_mail($row->Empfaenger, stripslashes(substr($text,0,$row->MaxZeichen)), $_POST['Betreff'], $_POST['Email'], $_POST['Email'], "text", $attach);

        if(isset($_REQUEST['sendcopy']) && $_REQUEST['sendcopy']==1) {
          $Mail_Absender = $GLOBALS['globals']->cp_settings("Mail_Absender");
          $Mail_Name     = $GLOBALS['globals']->cp_settings("Mail_Name");
          $GLOBALS['globals']->cp_mail($_POST['Email'], $GLOBALS['config_vars']['CONTACT_TEXT_THANKYOU'] . "\n\n" . stripslashes(substr($text,0,$row->MaxZeichen)), $_POST['Betreff'] . " " . $GLOBALS['config_vars']['CONTACT_SUBJECT_COPY'], $Mail_Absender, $Mail_Name, "text", "");
        }

        $sql = $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_contact_info (
          Id,
          FormId,
          Email,
          Datum,
          Betreff,
          Text,
          FId,
          Anhang
        ) VALUES (
          '',
          '$_POST[FormId]',
          '$_POST[Email]',
          '" .time(). "',
          '$_POST[Betreff]',
          '" .stripslashes(substr($text,0,$row->MaxZeichen)). "',
          '$_REQUEST[FId]',
          '$Anhang'
        )
        ");
      }
      //$this->thankyou($tpl_dir,$lang_file);
    }
  }

  function renameFile($file) {
    $old = $file;
    mt_rand();
    $random = rand(1000, 9999);
    $new = $random . "_" . $old;
    return $new;
  }

  function uploadFile($maxupload="0") {
    global $_FILES;
    $attach = "";
    define("UPDIR", BASE_DIR . "/attachments/");
    if(isset($_FILES['upfile']) &&  is_array($_FILES['upfile'])) {

      for($i=0;$i<count($_FILES['upfile']['tmp_name']);$i++) {

        if($_FILES['upfile']['tmp_name'][$i] != "") {

          $d_name = strtolower(ltrim(rtrim($_FILES['upfile']['name'][$i])));
          $d_name = str_replace(" ","", $d_name);
          $d_tmp = $_FILES['upfile']['tmp_name'][$i];

          $fz = filesize($_FILES['upfile']['tmp_name'][$i]);
          $mz = $maxupload*1024;;

          if($mz >= $fz) {
            if(file_exists(UPDIR . $d_name)) {
              $d_name = $this->renameFile($d_name);
            }

            @move_uploaded_file($d_tmp, UPDIR . $d_name);

            $attach[] = $d_name;
          }
        }
      }
      return $attach;
    }
  }

  function showForms($tpl_dir) {
    $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_contacts");
    $num = $sql->numrows();
    $sql->Close();

    $limit  = $this->_adminlimit;
    $seiten = ceil($num / $limit);
    $start  = prepage() * $limit - $limit;

    $items = array();
    $sql   = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_contacts LIMIT $start,$limit");

    while($row = $sql->fetchrow()) {
      $sql_n = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_contact_info WHERE FId='$row->Id' AND Aw_Zeit < 1");
      $row->messages = $sql_n->numrows();

      $sql_o = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_contact_info WHERE FId='$row->Id' AND Aw_Zeit > 1");
      $row->messages_new = $sql_o->numrows();

      array_push($items,$row);
    }

    $page_nav = pagenav($seiten, prepage()," <a class=\"pnav\" href=\"index.php?do=modules&action=modedit&mod=contact&moduleaction=1&cp=" . SESSION . "&page={s}\">{t}</a> ");

    if($num > $limit) $GLOBALS['tmpl']->assign("page_nav", $page_nav);
    $GLOBALS['tmpl']->assign("items", $items);
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_forms.tpl"));
  }

  function replace_wildcode($code) {
    $code = ereg_replace('([^ +-,!?_A-Za-zА-Яа-яЁё0-9-])', '', $code);
    $code = htmlspecialchars($code);
    return $code;
  }

  function editForms($tpl_dir,$id) {
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_contacts WHERE Id = '$id'");
    $row_e = $sql->fetchrow();

    $items = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_contact_fields WHERE KontaktId='$id' ORDER BY Position ASC");

    while($row = $sql->fetchrow()) {
      array_push($items,$row);
    }

    $Groups = array();
    $sql_g = $GLOBALS['db']->Query("SELECT Benutzergruppe,Name FROM " . PREFIX . "_user_groups");

    while($row_g = $sql_g->fetchrow()) {
      array_push($Groups, $row_g);
    }

    $GLOBALS['tmpl']->assign("groups", $Groups);
    $GLOBALS['tmpl']->assign("groups_form", explode(",", $row_e->Gruppen));
    $GLOBALS['tmpl']->assign("row", $row_e);
    $GLOBALS['tmpl']->assign("items", $items);
    $GLOBALS['tmpl']->assign("tpl_path", $tpl_dir);
    $GLOBALS['tmpl']->assign("formaction", "index.php?do=modules&action=modedit&mod=contact&moduleaction=save&cp=" . SESSION . "&id=$_REQUEST[id]&pop=1");
    $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_fields.tpl"));
  }

  function saveForms($tpl_dir,$id) {

    $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_contacts
      SET
        Name = '$_REQUEST[Name]',
        MaxZeichen = '$_REQUEST[MaxZeichen]',
        Empfaenger = '$_REQUEST[Empfaenger]',
        Empfaenger_Multi = '$_REQUEST[Empfaenger_Multi]',
        AntiSpam = '$_REQUEST[AntiSpam]',
        MaxUpload = '$_REQUEST[MaxUpload]',
        ZeigeBetreff = '$_REQUEST[ZeigeBetreff]',
        StandardBetreff = '$_REQUEST[StandardBetreff]',
        Gruppen = '" . @implode(",", $_REQUEST['Gruppen']) . "',
        TextKeinZugriff = '". $_POST['TextKeinZugriff'] ."',
        ZeigeKopie = '" . $_POST['ZeigeKopie']. "'
      WHERE
        Id = '$id'");

    if(!empty($_POST['del'])) {

      foreach($_POST['del'] as $id => $Feld) {
        $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_contact_fields WHERE Id = '$id'");
      }
    }

    foreach($_POST['FeldTitel'] as $id => $Feld) {
      if(!empty($_POST['FeldTitel'][$id])) {

        $FeldTitel = $this->replace_wildcode($_POST['FeldTitel'][$id]);
        $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_contact_fields
          SET
          FeldTitel = '$FeldTitel',
          Feld = '" . $_POST['Feld'][$id] . "',
          Position = '" . $_POST['Position'][$id] . "',
          Pflicht = '" . $_POST['Pflicht'][$id] . "',
          StdWert = '" . $this->replace_wildcode($_POST['StdWert'][$id]) . "',
          Aktiv = '" . $this->replace_wildcode($_POST['Aktiv'][$id]) . "'
          WHERE Id='$id'");
        reportLog($_SESSION["cp_uname"] . " - Отредактировал поле в модуле контакты (" . $FeldTitel . ")",'2','2');
      }
    }
    header("Location:index.php?do=modules&action=modedit&mod=contact&moduleaction=edit&id=$_REQUEST[id]&pop=1&cp=" . SESSION);
  }

  function saveFormsNew($tpl_dir,$id) {

    if(!empty($_POST['FeldTitel'])) {
      $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_contact_fields (
        Id,
        KontaktId,
        Feld,
        Position,
        FeldTitel,
        Pflicht,
        StdWert,
        Aktiv
      ) VALUES (
        '',
        '$id',
        '$_REQUEST[Feld]',
        '$_REQUEST[Position]',
        '" . $this->replace_wildcode($_REQUEST['FeldTitel']) . "',
        '$_REQUEST[Pflicht]',
        '" . $this->replace_wildcode($_REQUEST['StdWert']) . "',
        '$_REQUEST[Aktiv]'
      )
      ");
    }
    reportLog($_SESSION["cp_uname"] . " - Добавил новое поле в модуле контакты (".$this->replace_wildcode($_REQUEST['FeldTitel']). ")",'2','2');
    header("Location:index.php?do=modules&action=modedit&mod=contact&moduleaction=edit&id=$_REQUEST[id]&pop=1&cp=" . SESSION);
  }

  function newForms($tpl_dir) {
    switch($_REQUEST['sub']) {

      case '':
        $Groups = array();
        $sql_g = $GLOBALS['db']->Query("SELECT Benutzergruppe,Name FROM " . PREFIX . "_user_groups");

        while($row_g = $sql_g->fetchrow()) {
          array_push($Groups, $row_g);
        }

        $GLOBALS['tmpl']->assign("groups", $Groups);
        $GLOBALS['tmpl']->assign("tpl_path", $tpl_dir);
        $GLOBALS['tmpl']->assign("formaction", "index.php?do=modules&action=modedit&mod=contact&moduleaction=new&sub=save&cp=" . SESSION . "&pop=1");
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_fields.tpl"));
      break;

      case 'save':
        $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_modul_contacts (
           Id,
           Name,
           MaxZeichen,
           Empfaenger,
           Empfaenger_Multi,
           AntiSpam,
           MaxUpload,
           Gruppen,
           TextKeinZugriff,
           ZeigeKopie
        ) VALUES (
          '',
          '$_REQUEST[Name]',
          '$_REQUEST[MaxZeichen]',
          '$_REQUEST[Empfaenger]',
          '$_REQUEST[Empfaenger_Multi]',
          '$_REQUEST[AntiSpam]',
          '$_REQUEST[MaxUpload]',
          '" . @implode(",", $_REQUEST['Gruppen']) . "',
          '" . $_REQUEST['TextKeinZugriff'] . "',
          '" . $_REQUEST['ZeigeKopie'] ."'
        )
        ");

        $iid = $GLOBALS['db']->InsertId();
        reportLog($_SESSION["cp_uname"] . " - Добавил новую контактную форму (" . $_REQUEST['Name'] . ")",'2','2');
        header("Location:index.php?do=modules&action=modedit&mod=contact&moduleaction=edit&id=$iid&pop=1&cp=" . SESSION);
        exit;
      break;
    }
  }

  function deleteForms($id) {

    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_contacts WHERE Id='$id'");
    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_contact_fields WHERE KontaktId='$id'");

    $sql = $GLOBALS['db']->Query("SELECT Anhang,Aw_Anhang FROM " . PREFIX . "_modul_contact_info WHERE FId='$id'");
    $row = $sql->fetchrow();

    if($row->Anhang != "") {
      $del = explode(";", $row->Anhang);

      foreach($del as $delfile) {
        @unlink(BASE_DIR . "/attachments/" . $delfile);
      }
    }

    if($row->Aw_Anhang != "") {
      $del = explode(";", $row->Aw_Anhang);

      foreach($del as $delfile) {
        @unlink(BASE_DIR . "/attachments/" . $delfile);
      }
    }


    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_contact_info WHERE FId='$id'");
    reportLog($_SESSION["cp_uname"] . " - Удалил контактную форму (" . $id . ")",'2','2');
    header("Location:index.php?do=modules&action=modedit&mod=contact&moduleaction=1&cp=" . SESSION);
    exit;
  }

  function showMessages($tpl_dir,$id,$newold="") {

    switch($_REQUEST['sub']) {

      case '':
        $n_o     = ($newold == "new") ? "AND Aw_Zeit < 1" : "AND Aw_Zeit > 1";
        $new_old = ($newold == "new") ? "showmessages_new" : "showmessages_old";

        $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_modul_contact_info WHERE FId='$id' $n_o");
        $num = $sql->numrows();
        $sql->Close();

        $limit  = $this->_adminlimit;
        $seiten = ceil($num / $limit);
        $start  = prepage() * $limit - $limit;

        $items = array();
        $sql   = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_contact_info WHERE FId='$id' $n_o ORDER BY Datum DESC LIMIT $start,$limit");

        while($row = $sql->fetchrow()) {
          array_push($items, $row);
        }
        $sql->Close();

        $page_nav = pagenav($seiten, prepage()," <a class=\"pnav\" href=\"index.php?do=modules&action=modedit&mod=contact&moduleaction=$new_old&cp=" . SESSION . "&page={s}&id=$_REQUEST[id]\">{t}</a> ");

        if($num > $limit) {
          $GLOBALS['tmpl']->assign("page_nav", $page_nav);
        }
        $GLOBALS['tmpl']->assign("items", $items);
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_messages.tpl"));
      break;


      case 'view':
        $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_contact_info WHERE Id='$_REQUEST[id]'");
        $row = $sql->fetchrow();
        $attachments = "";

        if($row->Anhang != "") {
          $attachments = array();
          $attachments_arr = explode(";", $row->Anhang);

          foreach($attachments_arr as $attachment) {
            $row_a->name = $attachment;
            $row_a->size = round(filesize(BASE_DIR . "/attachments/" . $attachment)/1024,2);
            array_push($attachments, $row_a);
            $row_a = "";
          }
        }

        $row->nl2brText = nl2br(stripslashes($row->Text));
        $row->replytext = str_replace("", "", $row->Text);
        $GLOBALS['tmpl']->assign("attachments", $attachments);
        $GLOBALS['tmpl']->assign("row", $row);
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch($tpl_dir . "admin_messageform.tpl"));
      break;
    }

  }

  function getAttachment($file) {
    $file_ex = getMimeTyp($file);
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
    header("Content-Type: $file_ex");
    header("Content-Disposition: attachment; filename=$file");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".@filesize(BASE_DIR . '/attachments/' . $file));
    @set_time_limit(0);
    @cpReadfile(BASE_DIR . '/attachments/' . $file) or die("File not found. ");
  }

  function replyMessage() {
    $attach = $this->uploadFile(100000);
    $Anhang = (is_array($attach) && count($attach) >= 1) ? implode(";", $attach) : "";

    $globals = new Globals;
    $GLOBALS['globals']->cp_mail($_REQUEST['to'], stripslashes($_REQUEST['message']), $_REQUEST['subject'], $_REQUEST['fromemail'], $_POST['fromname'], "text", $attach);

    $sql = $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_contact_info
    SET
      Aw_Zeit     = '" .time(). "',
      Aw_Email    = '" . $_REQUEST['fromemail'] . "',
      Aw_Absender = '" . $_POST['fromname'] . "',
      Aw_Text     = '" . $_REQUEST['message'] . "',
      Aw_Anhang   = '" . $Anhang . "'
    WHERE
      Id          = '$_REQUEST[id]'");
    echo "<script>window.opener.location.reload(); window.close();</script>";
  }

  function quickSave() {
    foreach($_POST['del'] as $id => $del) {

      if($this->_delfile == 1) {
        $sql = $GLOBALS['db']->Query("SELECT Anhang,Aw_Anhang FROM " . PREFIX . "_modul_contact_info WHERE Id='$id'");
        $row = $sql->fetchrow();

        if($row->Anhang != "") {
          $del = explode(";", $row->Anhang);
          foreach($del as $delfile) {
            @unlink(BASE_DIR . "/attachments/" . $delfile);
          }
        }

        if($row->Aw_Anhang != "") {
          $del = explode(";", $row->Aw_Anhang);
          foreach($del as $delfile) {
            @unlink(BASE_DIR . "/attachments/" . $delfile);
          }
        }
      }
      $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_contact_info WHERE Id='$id'");
    }
    header("Location:index.php?do=modules&action=modedit&mod=contact&moduleaction=1&cp=" . SESSION);
    exit;
  }
}
?>