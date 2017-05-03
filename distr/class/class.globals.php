<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class Globals {

  function fetchCountries($aktiv="") {
    $dbextra = "";
    if($aktiv=="") $dbextra = "WHERE Aktiv = '1'";

    $laender = array();
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_countries $dbextra ORDER BY LandName ASC");
    while($row = $sql->fetchrow()) {
      $row->LandCode = strtolower($row->LandCode);
      array_push($laender, $row);
    }
    return $laender;
  }

  function delUser($id) {
    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_users WHERE Id = '$id'");
    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_modul_forum_userprofile WHERE BenutzerId = '$id'");
  }

  function cp_settings($field,$table='') {
    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_settings");
    $row = $sql->fetcharray();
    $sql->Close();
    return $row[$field];
  }

  function cp_mail($to, $text, $subject='', $fromemail='', $from='', $content_type='', $attach='', $html='') {
    $sql = $GLOBALS['db']->Query("SELECT
      Mail_Typ,
      Mail_Content,
      Mail_Port,
      Mail_Host,
      Mail_Username,
      Mail_Passwort,
      Mail_Sendmailpfad,
      Mail_WordWrap,
      Mail_Absender,
      Mail_Name
    FROM
      " . PREFIX . "_settings
    ");

    $row = $sql->fetchrow();
    $sql->Close();

    $mail = new PHPMailer;


    $mail->ContentType  = ($row->Mail_Content=='text/plain' || $content_type == 'text') ? "text/plain" : "text/html";
    $mail->ContentType  = ($html==1) ? "text/html" : $mail->ContentType;
    $mail->From     = ($fromemail != "") ? $fromemail : $row->Mail_Absender;
    $mail->FromName = ($from != "") ? $from : $row->Mail_Name;
    $mail->Host     = $row->Mail_Host;
    $mail->Mailer   = $row->Mail_Typ;
    $mail->AddAddress($to);
    $mail->Subject = $subject;
    $mail->Body    = $text . "\n\n" . (($mail->ContentType=='text/html') ? '' : $this->cp_settings("Mail_Text_Fuss"));
    $mail->Sendmail = $row->Mail_Sendmailpfad;
    $mail->WordWrap = $row->Mail_WordWrap;

    if($attach != '') {
      if(is_array($attach)) {
        foreach($attach as $attachment) {
          $mail->AddAttachment(BASE_DIR . "/attachments/" . $attachment);
        }
      } else {
        $mail->AddAttachment(BASE_DIR . "/attachments/" . $attach);
      }
    }

    $mail->Send();

    if(is_array($attach)) {
      foreach($attach as $attachment) {
        //@unlink(BASE_DIR . "/attachments/" . $attachment);
      }
    } else {
      //@unlink(BASE_DIR . "/attachments/" . $attach);
    }
  }

  function makePass() {
    $pass  = "";
    $chars = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s",1,2,3,4,5,6,7,8,9);
    $count = count($chars) - 1;
    srand((double)microtime() * 1000000);
    for($i = 0; $i < 7; $i++) {
      $pass .= $chars[rand(0, $count)];
    }
    return $pass;
  }
}
?>