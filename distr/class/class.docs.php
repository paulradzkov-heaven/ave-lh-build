<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 1)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: February 22, 2008
::::::::::::::::::::::::::::::::::::::::*/


class docs {

  var $_limit             = 15;
  var $_field_width       = '400px';
  var $_textarea_width    = '98%';
  var $_textarea_height   = '400px';
  var $_textarea_width_small    = '98%';
  var $_textarea_height_small   = '200px';
  var $_max_commentlength = 5000;

  function prettyCode($code) {
//    $code = str_replace(REPLACEMENT, "[cp:replacement]", $code);
    $code = eregi_replace("<b>", "<strong>", $code);
    $code = eregi_replace("</b>", "</strong>", $code);
    $code = eregi_replace("<i>", "<em>", $code);
    $code = eregi_replace("</i>", "</em>", $code);
    $code = eregi_replace("<br>", "<br />", $code);
    $code = eregi_replace("<br/>", "<br />", $code);
    $code = eregi_replace(REPLACEMENT, "[cp:replacement]", $code);
    return $code;
  }

  function rediRubs() {
    $items = array();
    $sql_rr = $GLOBALS['db']->Query("SELECT Id,RubrikName FROM " . PREFIX . "_rubrics");
    while($row_rr = $sql_rr->fetchrow()) {
      $this->fetchDocPerms($row_rr->Id);

      if(UGROUP == 1) $row_rr->Show = 1;
      elseif(@$_SESSION[$row_rr->Id . '_editown'] == 1) $row_rr->Show = 1;
      elseif(@$_SESSION[$row_rr->Id . '_new']     == 1) $row_rr->Show = 1;
      elseif(@$_SESSION[$row_rr->Id . '_newnow']  == 1) $row_rr->Show = 1;
      elseif(@$_SESSION[$row_rr->Id . '_alles']   == 1) $row_rr->Show = 1;

      array_push($items, $row_rr);
      unset($row_rr);
    }
    $GLOBALS['tmpl']->assign("rub_items", $items);
  }

  function dokEnde() {
    $ende = 0;
    if($_REQUEST['DokEndeDay'] != '' && $_REQUEST['DokEndeYear'] != ''  && $_REQUEST['DokEndeMonth'] != '') {
      $ende = mktime($_REQUEST['EndeHour'], $_REQUEST['EndeMinute'] , 0, $_REQUEST['DokEndeMonth'], $_REQUEST['DokEndeDay'], $_REQUEST['DokEndeYear']);
    }
    return $ende;
  }

  function dokStart() {
    $start = 0;
    if($_REQUEST['DokStartDay'] != '' && $_REQUEST['DokStartYear'] != ''  &&  $_REQUEST['DokStartMonth'] != '') {
      $start = mktime($_REQUEST['StartHour'], $_REQUEST['StartMinute'] , 0, $_REQUEST['DokStartMonth'], $_REQUEST['DokStartDay'], $_REQUEST['DokStartYear']);
    }
    return $start;
  }

  function fetchDocPerms($RubId) {
    $sql_dp = $GLOBALS['db']->Query("SELECT Rechte FROM " . PREFIX . "_document_permissions WHERE RubrikId = '" . (int)$RubId . "' AND BenutzerGruppe = '" . UGROUP . "'");
    while($row_dp = $sql_dp->fetchrow()) {
      $dPerms = explode('|', $row_dp->Rechte);
      foreach($dPerms as $mydPerm) {
        if(!empty($mydPerm)) $_SESSION[$RubId . '_' . $mydPerm] = 1;
      }
    }
  }

  function editDoc($id) {
    switch($_REQUEST['sub']) {
      case 'save':
        $sql = $GLOBALS['db']->Query("SELECT RubrikId,Redakteur FROM " . PREFIX . "_documents WHERE Id = '" . (int)$_REQUEST['Id'] . "'");
        $row = $sql->fetchrow();
        $row->cantEdit = 0;

        // разрешаем редактирование если автор имеет право изменять свои документы в рубрике
        // разрешаем редактирование если пользователю разрешено изменять все документы в рубрике
        if( ($row->Redakteur == @$_SESSION['cp_benutzerid'] && isset($_SESSION[$row->RubrikId . '_editown']) && @$_SESSION[$row->RubrikId . '_editown'] == 1) ||
            (isset($_SESSION[$row->RubrikId . '_editall']) && @$_SESSION[$row->RubrikId . '_editall'] == 1) ) {
          $row->cantEdit = 1;
        }
        // запрещаем редактирование документов с Id=1 или Id=2 если требуется одобрение Администратора
        if( ((int)$_REQUEST['Id'] == 1 || (int)$_REQUEST['Id'] == 2) && isset($_SESSION[$row->RubrikId . '_newnow']) && @$_SESSION[$row->RubrikId . '_newnow'] != 1 ) {
          $row->cantEdit = 0;
        }
        // разрешаем редактирование если пользователь принадлежит группе Администраторов или имеет все права на рубрику
        if( (UGROUP == 1) || (@$_SESSION[$row->RubrikId . '_alles'] == 1) ) {
          $row->cantEdit = 1;
        }

        if($row->cantEdit == 1) {
          $suche     = (isset($_POST['Suche']) && $_POST['Suche'] == 1) ? 1 : 0;
          $docstatus = ((isset($_SESSION[$row->RubrikId . '_newnow']) && @$_SESSION[$row->RubrikId . '_newnow'] == 1) || UGROUP == 1 || @$_SESSION[$row->RubrikId . '_alles'] == 1) ? $_REQUEST['DokStatus'] : 0;
          $docend    = ($_REQUEST['Id']==1 || $_REQUEST['Id']==2) ? 0 : $this->dokEnde();
          $docstart  = ($_REQUEST['Id']==1 || $_REQUEST['Id']==2) ? 0 : $this->dokStart();

		  if ($_POST['Parent']!=''){
		  	$sql_id = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_documents WHERE Titel = '".$_POST['Parent']."'");
          	$row_id = $sql_id->fetchrow();
          	$docid_par = (isset($row_id->Id)&&$row_id->Id!=0) ? $row_id->Id : 0;
		  } else {
		  	$docid_par = 0;
		  }

          $GLOBALS['db']->Query("UPDATE " . PREFIX . "_documents
            SET
              Titel = '" . htmlspecialchars($_POST['Titel']) . "',
              Url = '" . ereg_replace('[^-_a-zA-Z0-9./]','',$_POST['Url']) . "',
              Suche = '" . $suche . "',
              MetaKeywords = '" . htmlspecialchars($_POST['MetaKeywords']) . "',
              MetaDescription = '" . htmlspecialchars($_POST['MetaDescription']) . "',
              IndexFollow = '" . htmlspecialchars($_POST['IndexFollow']) . "',
              DokStatus = '" . $docstatus . "',
              DokEnde = '" . $docend . "',
              DokStart = '" . $docstart . "',
              ElterNavi = '" . (int)$_POST['ElterNavi'] . "',
			  ParentId = '". $docid_par ."'

            WHERE
              Id = '" . (int)$_REQUEST['Id'] . "'
            ");

          if(isset($_POST['feld'])) {
            foreach ($_POST['feld'] as $id => $f) {
              $Eintrag = true;
			  $trigger = true;
				
              $sql_tc = $GLOBALS['db']->Query("SELECT RubrikFeld FROM " . PREFIX . "_document_fields WHERE Id = '$id'");
              $row_tc = $sql_tc->fetchrow();
              $sql_tc->Close();

              $sql_rf = $GLOBALS['db']->Query("SELECT RubTyp FROM " . PREFIX . "_rubric_fields WHERE Id = '$row_tc->RubrikFeld'");
              $row_rf = $sql_rf->fetchrow();
              $sql_rf->Close();

              if( ($row_rf->RubTyp == 'langtext' || $row_rf->RubTyp == 'text') && (!cp_perm('docs_php')) ) {
                if(isPhpCode($_POST['feld'][$id])) {
                  $Eintrag = false;
                }
              }
/* Добавлено */
              if( ($row_rf->RubTyp == 'smalltext' || $row_rf->RubTyp == 'text') && (!cp_perm('docs_php')) ) {
                if(isPhpCode($_POST['feld'][$id])) {
                  $Eintrag = false;
                }
              }
//tags
			  if (@$row_rf->RubTyp == 'tags'){
                    $tags = $_POST['feld'][$id];
                    $label = explode(',', $tags);
                    for ($g=0; $g<count($label);$g++){ $tag[$g] = trim($label[$g]);}
                    $items = array_unique($tag);
                    $str = implode(',', $items);
                    $trigger = false;
            	}

              if($Eintrag) {
                $q = "UPDATE " . PREFIX . "_document_fields
                  SET
                    Inhalt = '" . (!$trigger? $str : $this->prettyCode($_POST['feld'][$id])) . "' ,
                    Suche = '" . $suche . "'
                  WHERE
                    Id = '$id'";
                $sql = $GLOBALS['db']->Query($q);
                reportLog($_SESSION["cp_uname"] . " - отредактировал документ (" . $id . ")",'2','2');
              }
            }
          }
        }

        if(isset($_REQUEST['closeafter']) && $_REQUEST['closeafter']==1) {
          echo "<script>window.opener.location.reload(); window.close();</script>";
          ///header("Location:index.php?do=docs&cp=".SESSION. "");
          exit;
        }

        echo "<script>window.opener.location.reload();</script>";
        $GLOBALS['tmpl']->assign("name_empty", $GLOBALS['config_vars']['DOC_TOP_MENU_ITEM']);
        $GLOBALS['tmpl']->assign("Id", $_REQUEST['Id']);
        $GLOBALS['tmpl']->assign("RubrikId", $_REQUEST['RubrikId']);
        $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("documents/form_after.tpl"));
      break;

      case 'savenavi':
        $elter_pre = ($_REQUEST['Elter']=='0') ? 0 : explode('____', $_REQUEST['Elter']);
        $elter = (is_array($elter_pre)) ? $elter_pre[0] : 0;
        $ebene = (is_array($elter_pre)) ? $elter_pre[1] : 1;
				if ($GLOBALS['config']['doc_rewrite']) {
        	$link = $_REQUEST['Url'];
				} else {
					$link = "index.php?id=" . $_REQUEST['Id'];
				}
				$ziel = ($_REQUEST['Ziel']=='') ? "_self" : $_REQUEST['Ziel'];
        $rang = ($_REQUEST['Rang']=='') ? 1 : $_REQUEST['Rang'];

        if($elter != '0') {
          $sql_ri = $GLOBALS['db']->Query("SELECT Rubrik FROM " . PREFIX . "_navigation_items WHERE Id = '" . $elter_pre[0] . "'");
          $row_ri = $sql_ri->fetchrow();
          $sql_ri->Close();
          $rubrik = $row_ri->Rubrik;
        } else {
          $rubrik = $_REQUEST['NaviRubric'];
        }

        $Titel = ereg_replace('([^ :)_A-Za-zА-Яа-яЁё0-9-])', '', $_REQUEST['Titel']);
        $Titel = htmlspecialchars($Titel);

        $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_navigation_items
        (Id,Titel,Elter,Link,Ziel,Ebene,Rang,Rubrik)
        VALUES
        ('','" . $Titel . "', '$elter', '$link', '$ziel', '$ebene', '$rang', '$rubrik')
        ");

        if($_REQUEST['pop']==1) {
          echo "<script>window.opener.location.reload(); window.close();</script>";
        } else {
          echo "<script>window.opener.location.reload();</script>";
        }
      break;

      case '':
        $sql_redi = $GLOBALS['db']->Query("SELECT Redakteur,RubrikId FROM " . PREFIX . "_documents WHERE Id = '" . (int)$_REQUEST['Id'] . "'");
        $row_redi = $sql_redi->fetchrow();
        $sql_redi->close();

        $show = true;

        $this->fetchDocPerms($row_redi->RubrikId);
        // запрещаем доступ если автору документа не разрешено изменять свои документы в рубрике
        // запрещаем доступ если пользователю не разрешено изменять все документы в рубрике
        if( ($row_redi->Redakteur == $_SESSION['cp_benutzerid'] && isset($_SESSION[$row_redi->RubrikId . '_editown']) && @$_SESSION[$row_redi->RubrikId . '_editown'] != 1) ||
            (isset($_SESSION[$row_redi->RubrikId . '_editall']) && @$_SESSION[$row_redi->RubrikId . '_editall'] != 1)) {
          $show = false;
        }
        // запрещаем доступ к документам с Id=1 или Id=2 если требуется одобрение Администратора
        if( ((int)$_REQUEST['Id'] == 1 || (int)$_REQUEST['Id'] == 2) && isset($_SESSION[$row_redi->RubrikId . '_newnow']) && @$_SESSION[$row_redi->RubrikId . '_newnow'] != 1 ) {
          $show = false;
        }
        // разрешаем доступ если пользователь принадлежит группе Администраторов или имеет все права на рубрику
        if( (UGROUP == 1) || (@$_SESSION[$row_redi->RubrikId . '_alles'] == 1) ) {
          $show = true;
        }

        if($show) {
          $items = array();

          $sql_doc = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_documents WHERE Id = '" . (int)$_REQUEST['Id'] . "'");
          $row_doc = $sql_doc->fetchrow();
		  
		  $par_id = $row_doc->ParentId;
          
          $sql_par = $GLOBALS['db']->Query("SELECT Titel FROM " . PREFIX . "_documents WHERE Id = '".$par_id."'");
          $row_par = $sql_par->fetchrow();
          $row_doc->ParentTitle = $row_par->Titel;


          if(@$_SESSION[$row_redi->RubrikId . '_newnow'] != 1) {
            $row_doc->dontChangeStatus = 1;
          }

          $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_rubric_fields WHERE RubrikId='" . (int)$_REQUEST['RubrikId'] . "' ORDER BY rubric_position ASC");

          while($row = $sql->fetchrow()) {

            if($row->RubTyp == 'dropdown') {
              $sql_df = $GLOBALS['db']->Query("SELECT a.Id, a.StdWert, b.Id as did, b.Inhalt
                 FROM " . PREFIX . "_rubric_fields as a LEFT JOIN
                      " . PREFIX . "_document_fields as b ON (b.RubrikFeld = a.Id  AND b.DokumentId='" . (int)$_REQUEST['Id'] . "')
                 WHERE a.RubrikId='" . (int)$_REQUEST['RubrikId'] . "' AND a.RubTyp='dropdown' AND a.Id = '" . $row->Id . "'");
              $res = $sql_df->fetchrow();
              $row_df->Inhalt = $res->StdWert;
              $row_df->Id = $res->did;
              $drop = $res->Inhalt;
            } else {
              $sql_df = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_document_fields WHERE DokumentId='" . (int)$_REQUEST['Id'] . "' AND RubrikFeld='$row->Id'");
              $row_df = $sql_df->fetchrow();
            }

            $num_df = $sql_df->numrows();
            $new = ($num_df < 1) ? 1 : "";
            @$row->Feld = $this->getField($row->RubTyp, $row_df->Inhalt, $row_df->Id, $new, $_REQUEST['Id'], $row_doc->Redakteur, $drop);

            if ($row->RubTyp == 'created' && $_REQUEST['action'] != 'new') {
              $now_date = date($GLOBALS['globals']->cp_settings("Zeit_Format"));
              $now_date = strtr($now_date, array('January'=>'января','February'=>'февраля','March'=>'марта','April'=>'апреля','May'=>'мая','June'=>'июня','July'=>'июля','August'=>'августа','September'=>'сентября','October'=>'октября','November'=>'ноября','December'=>'декабря','Sunday'=>'Воскресенье','Monday'=>'Понедельник','Tuesday'=>'Вторник','Wednesday'=>'Среда','Thursday'=>'Четверг','Friday'=>'Пятница','Saturday'=>'Суббота',));
            }
			
            array_push($items, $row);

          }
          $GLOBALS['tmpl']->assign("now_date", $now_date);
          $GLOBALS['tmpl']->assign("items", $items);
          $GLOBALS['tmpl']->assign("row_doc", $row_doc);
          $GLOBALS['tmpl']->assign("formaction", "index.php?sub=save&do=docs&action=edit&RubrikId=" . (int)$_REQUEST['RubrikId'] . "&Id=" . (int)$_REQUEST['Id'] . "&cp=" . SESSION . "");
          $GLOBALS['tmpl']->assign("RubName", $this->showRubName((int)$_REQUEST['RubrikId']));
          $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("documents/form.tpl"));
        } else {
          $GLOBALS['tmpl']->assign("content", $GLOBALS['config_vars']['DOC_NO_PERMISSION']);
        }

      break;
    }
  }

  function newDoc($rubid) {
    $rubid = (isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'savenavi') ? $_REQUEST['RubrikId'] : $rubid;
    $this->fetchDocPerms($rubid);

    if( (@$_SESSION[$rubid . '_newnow'] != 1 && @$_SESSION[$rubid . '_new'] != 1 && @$_SESSION[$rubid . '_alles'] != 1) ) {
      $GLOBALS['tmpl']->assign("content", $GLOBALS['config_vars']['DOC_NO_PERMISSION_RUB']);
    } else {
      switch($_REQUEST['sub']) {
        case 'save':
          $innavi = true;
          $ende = $this->dokEnde();
          $start = $this->dokStart();
          $ds = (isset($_REQUEST['DokStatus']) && $_REQUEST['DokStatus'] != "") ? (int)$_REQUEST['DokStatus'] : '';

          if(empty($ds))

          $innavi = false;
          @reset($_POST);
          $newtext = "\n\n";

          while (list($key, $val) = each($_POST['feld'])) {
            if (!empty($val)) {
              $newtext .= $val;
              $newtext .= "\n---------------------\n";
            }
          }
          $text = strip_tags($newtext);

          $globals = new Globals;
          $SystemMail = $GLOBALS['globals']->cp_settings('Mail_Absender');
          $SystemMailName = $GLOBALS['globals']->cp_settings('Mail_Name');

          $body_toadmin = $GLOBALS['config_vars']['DOC_MAIL_BODY_CHECK'];
          $body_toadmin = str_replace("%N%", "\n", $body_toadmin);
          $body_toadmin = str_replace("%TITLE%", stripslashes($_POST['Titel']), $body_toadmin);
          $body_toadmin = str_replace("%USER%", "'" .$_SESSION['cp_uname']. "'", $body_toadmin);
          $GLOBALS['globals']->cp_mail($SystemMail, $body_toadmin . $text, $GLOBALS['config_vars']['DOC_MAIL_SUBJECT_CHECK'], $SystemMail, $SystemMailName, "text", "");

          $body_toauthor = str_replace("%N%", "\n", $GLOBALS['config_vars']['DOC_MAIL_BODY_USER']);
          $body_toauthor = str_replace("%TITLE%", stripslashes($_POST['Titel']), $body_toauthor);
          $body_toauthor = str_replace("%USER%", "'" .$_SESSION['cp_uname']. "'", $body_toauthor);
          $GLOBALS['globals']->cp_mail($_SESSION["cp_email"], $body_toauthor, $GLOBALS['config_vars']['DOC_MAIL_SUBJECT_USER'], $SystemMail, $SystemMailName, "text", "");

          $this->fetchDocPerms($_REQUEST['Id']);

          if ( (@$_SESSION[$_REQUEST['Id'] . '_newnow'] != 1) && (@$_SESSION[$_REQUEST['Id'] . '_alles'] != 1) && (UGROUP != 1) ) {
            $ds = 0;
          }
			
		  if ($_POST['Parent']!=''){
		  		$sql_id = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_documents WHERE Titel = '".$_POST['Parent']."'");
          		$row_id = $sql_id->fetchrow();
		  		$docid_par = (isset($row_id->Id)&&$row_id->Id!=0) ? $row_id->Id : 0;
		 } else {
		 		$docid_par = 0;
		 }

          $suche = (isset($_POST['Suche']) && $_POST['Suche'] == 1) ? 1 : 0;
          $q = "INSERT INTO " . PREFIX . "_documents (
            Id,
            RubrikId,
            Url,
            Titel,
            DokTyp,
            DokStart,
            DokEnde,
            DokEdi,
            Redakteur,
            Suche,
            MetaKeywords,
            MetaDescription,
            IndexFollow,
            DokStatus,
            ElterNavi,
			ParentId
            ) VALUES (
            '',
            '" . $_REQUEST['Id'] . "',
            '" . ereg_replace('[^-_a-zA-Z0-9./]','',$_POST['Url']) . "',
            '" . htmlspecialchars($_POST['Titel']) . "',
            '',
            '$start',
            '$ende',
            '$start',
            '" . $_SESSION['cp_benutzerid'] . "',
            '" . $suche. "',
            '" . htmlspecialchars($_POST['MetaKeywords']) . "',
            '" . htmlspecialchars($_POST['MetaDescription']) . "',
            '" . htmlspecialchars($_POST['IndexFollow']) . "',
            '$ds',
            '" .$_POST['ElterNavi']. "',
			'" . $docid_par . "'
            )";

          $sql = $GLOBALS['db']->Query($q);
          $iid = $GLOBALS['db']->InsertId();
		  

          foreach ($_POST['feld'] as $id => $f) {
            $Eintrag = true;
			$trigger = true;
			
            $sql_tc = $GLOBALS['db']->Query("SELECT RubrikFeld FROM " . PREFIX . "_document_fields WHERE RubrikFeld = '$id'");
            $row_tc = $sql_tc->fetchrow();
            $sql_tc->close();

            @$sql_rf = $GLOBALS['db']->Query("SELECT RubTyp FROM " . PREFIX . "_rubric_fields WHERE Id = '$row_tc->RubrikFeld'");
            $row_rf = $sql_rf->fetchrow();
            $sql_rf->close();

            if( (@$row_rf->RubTyp == 'langtext' || @$row_rf->RubTyp == 'text') && (!cp_perm('docs_php')) ) {
              if(isPhpCode($_POST['feld'][$id])) {
                $Eintrag = false;
              }
            }

			/* Добавлено */
            if( (@$row_rf->RubTyp == 'smalltext' || @$row_rf->RubTyp == 'text') && (!cp_perm('docs_php')) ) {
              if(isPhpCode($_POST['feld'][$id])) {
                $Eintrag = false;
              }
            }

//Удаляет повторяющиеся в поле тэги
			 if (@$row_rf->RubTyp == 'tags'){
                    $tags = $_POST['feld'][$id];
                    $label = explode(',', $tags);
                    for ($g=0; $g<count($label);$g++){ $tag[$g] = trim($label[$g]);}
                    $items = array_unique($tag);
                    $str = implode(',', $items);
                    $trigger = false;
            	}
//Удаляет повторяющиеся в поле тэги

            $q = "INSERT INTO " . PREFIX . "_document_fields (Id, RubrikFeld, DokumentId, Inhalt,Suche) VALUES ('','" .$id. "','$iid','','$suche')";
            $sql = $GLOBALS['db']->Query($q);
            $iid_df = $GLOBALS['db']->InsertId();
            $q = "UPDATE " . PREFIX . "_document_fields
                SET
                Inhalt = '" . (!$trigger ? $str :(!$Eintrag ? "" : $this->prettyCode($_POST['feld'][$id]))) . "'
                WHERE Id = '$iid_df'";
              $sql = $GLOBALS['db']->Query($q);

            reportLog($_SESSION["cp_uname"] . " - добавил документ (".$_POST['Titel'].")",'2','2');
          }

          $GLOBALS['tmpl']->assign('name_empty', $GLOBALS['config_vars']['DOC_TOP_MENU_ITEM']);
          $GLOBALS['tmpl']->assign('Id', $iid);
          $GLOBALS['tmpl']->assign('innavi', (($innavi != false) ? 1 : 0));
          $GLOBALS['tmpl']->assign('RubrikId', $_REQUEST['Id']);
          $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("documents/form_after.tpl"));
        break;



      case 'savenavi':
        $elter_pre = ($_REQUEST['Elter']=='0') ? 0 : explode('____', $_REQUEST['Elter']);
        $elter = (is_array($elter_pre)) ? $elter_pre[0] : 0;
        $ebene = (is_array($elter_pre)) ? $elter_pre[1] : 1;
				if ($GLOBALS['config']['doc_rewrite']) {
        	$link = $_REQUEST['Url'];
				} else {
					$link = "index.php?id=" . $_REQUEST['Id'];
				}
        $ziel = ($_REQUEST['Ziel']=='') ? '_self' : $_REQUEST['Ziel'];
        $rang = ($_REQUEST['Rang']=='') ? 1 : $_REQUEST['Rang'];

        if($elter != '0') {
          $sql_ri = $GLOBALS['db']->Query("SELECT Rubrik FROM " . PREFIX . "_navigation_items WHERE Id = '" . $elter_pre[0] . "'");
          $row_ri = $sql_ri->fetchrow();
          $sql_ri->Close();
          $rubrik = $row_ri->Rubrik;
        } else {
          $rubrik = $_REQUEST['NaviRubric'];
        }

        $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_navigation_items
        (Id,Titel,Elter,Link,Ziel,Ebene,Rang,Rubrik)
        VALUES
        ('','" . htmlspecialchars($_REQUEST['Titel']). "', '$elter', '$link', '$ziel', '$ebene', '$rang', '$rubrik')
        ");

        header("Location:index.php?do=docs&cp=".SESSION. "");
        exit;
      break;

      case '':
        $hidden = "";
        $row_doc = "";
        $this->fetchDocPerms($_REQUEST['Id']);

        if(@$_SESSION[$_REQUEST['Id'] . '_newnow'] != 1) {
          $row_doc->dontChangeStatus = 1;
        }

        $items = array();
        $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_rubric_fields WHERE RubrikId='" .(int)$_REQUEST['Id']. "' ORDER BY rubric_position ASC");
        while($row = $sql->fetchrow()) {
          $row->Feld = $this->getField($row->RubTyp, $row->StdWert, $row->Id, 1);
          $hidden .= "<input type=\"hidden\" value=\"rf[]\" value=\"$row->Id\" />";
          array_push($items, $row);
        }

        // Авто подстановка ссылки для ЧПУ (вариант с датой)
        $sql = $GLOBALS['db']->Query("SELECT RubrikName,UrlPrefix FROM " . PREFIX . "_rubrics WHERE Id='" .(int)$_REQUEST['Id']. "'");
        $row = $sql->fetchrow();
        $autoUrl = ($row->UrlPrefix != '/news/' ) ? $row->UrlPrefix : ($row->UrlPrefix).Date('Y_m_d').'/';

        $RubName = $row->RubrikName;

        $sql = $GLOBALS['db']->Query("SELECT count(Url) AS numUrl FROM " . PREFIX . "_documents WHERE Url LIKE '%".$autoUrl."%'");
        $row = $sql->fetchrow();
        if ($row->numUrl > 0) {
          $autoUrl = $autoUrl.($row->numUrl+1).'/';
        }
        $GLOBALS['tmpl']->assign('autoUrl', $autoUrl);

        $GLOBALS['tmpl']->assign('row_doc', $row_doc);
        $GLOBALS['tmpl']->assign('items', $items);
        $GLOBALS['tmpl']->assign('hidden', $hidden);
        $GLOBALS['tmpl']->assign('formaction', "index.php?do=docs&action=new&cp=".SESSION. "&amp;sub=save&Id=".(int)$_REQUEST['Id'] . ((isset($_REQUEST['pop']) && $_REQUEST['pop']==1) ? "pop=1" : "") );
        $GLOBALS['tmpl']->assign("RubName", $RubName);
        $GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch('documents/form.tpl'));
      break;
    }
   }
 }

  function delDoc($id) {
    $sql = $GLOBALS['db']->Query("SELECT Id,RubrikId,Redakteur FROM " . PREFIX . "_documents WHERE Id = '$id'");
    $row = $sql->fetchrow();

    if( ($row->Redakteur == @$_SESSION['cp_benutzerid']) &&
      (isset($_SESSION[$row->RubrikId . '_editown']) &&
      @$_SESSION[$row->RubrikId . '_editown'] == 1) ||
      @$_SESSION[$row->RubrikId . '_alles'] == 1 ||
      UGROUP == 1
    )
    {
      if($id != 1 && $id != 2) {
        $GLOBALS['db']->Query("UPDATE " . PREFIX . "_documents SET Geloescht = 1 WHERE Id = '$id'");
        reportLog($_SESSION["cp_uname"] . " - временно удалил документ (" . $id . ")",'2','2');
      }
    }
    header("Location:index.php?do=docs&cp=" . SESSION . "");

  }

  function redelDoc($id) {
    $GLOBALS['db']->Query("UPDATE " . PREFIX . "_documents SET Geloescht = 0 WHERE Id = '$id'");
    reportLog($_SESSION["cp_uname"] . " - восстановил удаленный документ (" . $id . ")",'2','2');
    header("Location:index.php?do=docs&cp=" . SESSION . "");
  }

  function enddelDoc($id) {
    if($id != 1 && $id != 2) {
      $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_documents WHERE Id = '$id'");
      $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_document_fields WHERE DokumentId = '$id'");
      reportLog($_SESSION["cp_uname"] . " - окончательно удалил документ (" . $id . ")",'2','2');
    }
    header("Location:index.php?do=docs&cp=" . SESSION . "");
  }

  function openCloseDoc($id,$openclose) {
    $sql = $GLOBALS['db']->Query("SELECT RubrikId,Redakteur FROM " . PREFIX . "_documents WHERE Id = '$id'");
    $row = $sql->fetchrow();

    if( ($row->Redakteur == @$_SESSION['cp_benutzerid']) &&
      (isset($_SESSION[$row->RubrikId . '_newnow']) &&
      @$_SESSION[$row->RubrikId . '_newnow'] == 1) ||
      @$_SESSION[$row->RubrikId . '_alles'] == 1 ||
      UGROUP == 1
    )
    {
      if($id != 1 && $id != 2) {
        $GLOBALS['db']->Query("UPDATE " . PREFIX . "_documents SET DokStatus = $openclose WHERE Id = '$id'");
        reportLog($_SESSION["cp_uname"] . " - " . (($openclose=='open') ? 'активировал' : 'деактивировал') . " документ (" . $id . ")",'2','2');
      }
    }
    header("Location:index.php?do=docs&cp=".SESSION. "");

  }

  function selectStart() {
    $start = mktime(0, 0, 1, $_REQUEST['DokStartMonth'], $_REQUEST['DokStartDay'], $_REQUEST['DokStartYear']);
    return $start;
  }

  function selectEnde() {
    $ende = mktime(23, 59, 59, $_REQUEST['DokEndeMonth'], $_REQUEST['DokEndeDay'], $_REQUEST['DokEndeYear']);
    return $ende;
  }

  function tplTimeAssign()  {
    if(isset($_REQUEST['TimeSelect']) && $_REQUEST['TimeSelect'] != '') {
      $GLOBALS['tmpl']->assign("sel_start", $this->selectStart());
      $GLOBALS['tmpl']->assign("sel_ende", $this->selectEnde());
    }
  }

  function showDocs($simple='') {
    global $cpRub;

    $ex_Titel = '';
    $nav_Titel = '';
    $ex_Zeit = '';
    $nav_Zeit = '';
    $ex_Geloescht = '';
    $first = '';
    $ex_Titel_not = '';
    $request = '';
    $ex_rub = '';
    $nav_rub = '';
    $ex_docstatus = '';
    $navi_docstatus = '';
    $only_id = '';

    if(isset($_REQUEST['QueryTitel']) && $_REQUEST['QueryTitel'] != '') {
      $request = $_REQUEST['QueryTitel'];
      $Kette = explode(' ', $request);

      foreach($Kette as $Suche) {

        $Und = @explode(' +', $Suche);
        foreach($Und as $UndWort) {
          if(strpos($UndWort, "+")!==false)
            $ex_Titel .= " AND (Titel like '%" . substr($UndWort, 1) . "%')";
        }

        $UndNicht = @explode(' -', $Suche);
        foreach($UndNicht as $UndNichtWort) {
          if(strpos($UndNichtWort, '-') !== false)
            $ex_Titel .= " AND (Titel not like '%" . substr($UndNichtWort, 1) . "%')";
        }

        $Start = explode(' +', $request);
        if(strpos($Start[0], ' -') !== false)
          $Start = explode(' -', $request);

        $Start = $Start[0];
      }

      $ex_Titel = " AND (Titel like '%" . $Start . "%') $ex_Titel ";
      $nav_Titel = "&QueryTitel=" . urlencode($request);
    }

    if(isset($_REQUEST['RubrikId']) && $_REQUEST['RubrikId'] != 'all') {
      $ex_rub = " AND RubrikId = '" . $_REQUEST['RubrikId'] . "'";
      $nav_rub = "&RubrikId=" . $_REQUEST['RubrikId'];
    } else {
      $nav_rub = '';
    }

    if(isset($_REQUEST['TimeSelect']) && $_REQUEST['TimeSelect'] != '') {
      $ex_Zeit   = " AND (DokStart BETWEEN " . $this->selectStart() . " AND " . $this->selectEnde() . ")";
      $nav_Zeit  = "&DokStartMonth=$_REQUEST[DokStartMonth]&DokStartDay=$_REQUEST[DokStartDay]&DokStartYear=$_REQUEST[DokStartYear]";
      $nav_Zeit .= "&DokEndeMonth=$_REQUEST[DokEndeMonth]&DokEndeDay=$_REQUEST[DokEndeDay]&DokEndeYear=$_REQUEST[DokEndeYear]&TimeSelect=1";
    }


    if(isset($_REQUEST['DokStatus']) && $_REQUEST['DokStatus'] != '') {
      switch($_REQUEST['DokStatus']) {
        case '':
        case 'All':
          $ex_docstatus = '';
          $navi_docstatus = '';
        break;

        case 'Opened':
          $ex_docstatus = " AND DokStatus = 1";
          $navi_docstatus = "&DokStatus=Opened";
        break;

        case 'Closed':
          $ex_docstatus = " AND DokStatus = 0";
          $navi_docstatus = "&DokStatus=Closed";
        break;

        case 'Deleted':
          $ex_docstatus = " AND Geloescht = 1";
          $navi_docstatus = "&DokStatus=Deleted";
        break;
		
		case 'Outtime':
          $ex_docstatus = " AND DokEnde < ".time();
        break;
		
      }
    }

    $ex_Geloescht = (UGROUP != 1) ? ' AND Geloescht != 1 ' : '' ;
    $w_id = (isset($_REQUEST['doc_id']) && $_REQUEST['doc_id'] != '') ? " AND Id = '" . $_REQUEST['doc_id'] . "'" : '';

    $q = "SELECT Id FROM " . PREFIX . "_documents
      WHERE Id > 0
        $ex_Geloescht
        $ex_Zeit
        $ex_Titel
        $ex_rub
        $ex_docstatus
        $w_id
        ";

    $sql = $GLOBALS['db']->Query($q);
    $num = $sql->numrows();
    $sql->Close();

    if(isset($_REQUEST['Datalimit']) && $_REQUEST['Datalimit'] != '') {
      $limit = (int)$_REQUEST['Datalimit'];
      $nav_limit = "&Datalimit=" . $limit;
    } else  {
      $limit = $this->_limit;
      $nav_limit = "&Datalimit=" . $limit;
    }

    $seiten = ceil($num / $limit);
    $start = prepage() * $limit - $limit;

    $db_sort   = ' ORDER BY DokStart DESC ';
    $navi_sort = '&sort=ErstelltDesc';

    if(isset($_REQUEST['sort']) && $_REQUEST['sort'] != '') {
      switch($_REQUEST['sort']) {

        case 'Id' :
          $db_sort   = " ORDER BY Id ASC ";
          $navi_sort = "&sort=Id";
        break;

        case 'IdDesc' :
          $db_sort   = " ORDER BY Id DESC ";
          $navi_sort = "&sort=IdDesc";
        break;

        case 'Titel' :
          $db_sort   = " ORDER BY Titel ASC ";
          $navi_sort = "&sort=Titel";
        break;

        case 'TitelDesc' :
          $db_sort   = " ORDER BY Titel DESC ";
          $navi_sort = "&sort=TitelDesc";
        break;

        case 'Url' :
          $db_sort   = " ORDER BY Url ASC ";
          $navi_sort = "&sort=Url";
        break;

        case 'UrlDesc' :
          $db_sort   = " ORDER BY Url DESC ";
          $navi_sort = "&sort=UrlDesc";
        break;

        case 'Rubrik' :
          $db_sort   = " ORDER BY RubrikId ASC ";
          $navi_sort = "&sort=Rubrik";
        break;

        case 'RubrikDesc' :
          $db_sort   = " ORDER BY RubrikId DESC ";
          $navi_sort = "&sort=RubrikDesc";
        break;

        case 'Erstellt' :
          $db_sort   = " ORDER BY DokStart ASC ";
          $navi_sort = "&sort=Erstellt";
        break;

        case 'ErstelltDesc' :
          $db_sort   = " ORDER BY DokStart Desc ";
          $navi_sort = "&sort=ErstelltDesc";
        break;

        case 'Klicks' :
          $db_sort   = " ORDER BY Geklickt ASC ";
          $navi_sort = "&sort=Klicks";
        break;

        case 'KlicksDesc' :
          $db_sort   = " ORDER BY Geklickt DESC ";
          $navi_sort = "&sort=KlicksDesc";
        break;

        case 'Druck' :
          $db_sort   = " ORDER BY Drucke ASC ";
          $navi_sort = "&sort=Druck";
        break;

        case 'DruckDesc' :
          $db_sort   = " ORDER BY Drucke DESC ";
          $navi_sort = "&sort=DruckDesc";
        break;

        case 'Autor' :
          $db_sort   = " ORDER BY Redakteur ASC ";
          $navi_sort = "&sort=Autor";
        break;

        case 'AutorDesc' :
          $db_sort   = " ORDER BY Redakteur DESC ";
          $navi_sort = "&sort=AutorDesc";
        break;

        default :
          $db_sort   = " ORDER BY DokStart ASC ";
          $navi_sort = "&sort=Erstellt";
        break;
      }
    }

    $docs = array();
    $q = "SELECT * FROM " . PREFIX . "_documents
      WHERE Id > 0
        $ex_Geloescht
        $ex_Zeit
        $ex_Titel
        $ex_rub
        $ex_docstatus
        $w_id
      $db_sort
      LIMIT
        $start,$limit";

    $sql = $GLOBALS['db']->Query($q);
    while($row = $sql->fetchrow()) {

      $sql_numkommentare = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_document_comments WHERE DokumentId='" . $row->Id . "'");
      $row->Kommentare = $sql_numkommentare->numrows();

      $this->fetchDocPerms($row->RubrikId);

      $row->RubName = $this->showRubName($row->RubrikId);
      $row->RBenutzer = getUserById($row->Redakteur);
      $row->cantEdit     = 0;
      $row->canDelete    = 0;
      $row->canEndDel    = 0;
      $row->canOpenClose = 0;

      // разрешаем редактирование и удаление если автор имеет право изменять свои документы в рубрике
      // разрешаем редактирование и удаление если пользователю разрешено изменять все документы в рубрике
      if( ($row->Redakteur == @$_SESSION['cp_benutzerid'] && isset($_SESSION[$row->RubrikId . '_editown']) && @$_SESSION[$row->RubrikId . '_editown'] == 1) ||
          (isset($_SESSION[$row->RubrikId . '_editall']) && $_SESSION[$row->RubrikId . '_editall'] == 1) ) {
        $row->cantEdit  = 1;
        $row->canDelete = 1;
      }
      // запрещаем редактирование документов с Id=1 или Id=2 если требуется одобрение Администратора
      if( ($row->Id == 1 || $row->Id == 2) && isset($_SESSION[$row->RubrikId . '_newnow']) && @$_SESSION[$row->RubrikId . '_newnow'] != 1) {
        $row->cantEdit = 0;
      }
      // разрешаем автору блокировать и разблокировать свои документы если не требуется одобрение Администратора
      if($row->Redakteur == @$_SESSION['cp_benutzerid'] && isset($_SESSION[$row->RubrikId . '_newnow']) && @$_SESSION[$row->RubrikId . '_newnow'] == 1) {
        $row->canOpenClose = 1;
      }
      // разрешаем всё если пользователь принадлежит группе Администраторов или имеет все права на рубрику
      if(UGROUP == 1 || @$_SESSION[$row->RubrikId . '_alles'] == 1) {
        $row->cantEdit     = 1;
        $row->canDelete    = 1;
        $row->canEndDel    = 1;
        $row->canOpenClose = 1;
      }
      // документы с Id=1 или Id=2 удалять нельзя
      if($row->Id == 1 || $row->Id == 2) {
        $row->canDelete = 0;
        $row->canEndDel = 0;
      }

      array_push($docs, $row);
    }

    $GLOBALS['tmpl']->assign('docs', $docs);

    $nav_RubId = (isset($_REQUEST['RubrikId']) && $_REQUEST['RubrikId'] != '') ? "&amp;RubrikId=" . (int)$_REQUEST['RubrikId'] : '';

    $nav_target = (isset($_REQUEST['target']) && $_REQUEST['target'] != '') ? "&target=$_REQUEST[target]" : "";
    $nav_doc = (isset($_REQUEST['doc']) && $_REQUEST['doc'] != '') ? "&doc=$_REQUEST[doc]" : "";

    $pop = (isset($_REQUEST['pop']) && $_REQUEST['pop'] == 1) ? "&amp;pop=1" : "";
    $showsimple = (isset($_REQUEST['action']) && $_REQUEST['action'] == "showsimple") ? "&amp;action=showsimple" : "";
  	/*------Для редактора------*/
  	$showsimple_edit = (isset($_REQUEST['action']) && $_REQUEST['action'] == "showsimple_edit") ? "&amp;action=showsimple_edit" : "";
    $idonly = (isset($_REQUEST['idonly']) && $_REQUEST['idonly'] == 1) ? "&amp;idonly=1" : "";
	$idonly = (isset($_REQUEST['idonly']) && $_REQUEST['idonly'] == 2) ? "&amp;idonly=2" : "";
    $page_nav = pagenav($seiten, prepage(),
    " <a class=\"pnav\" href=\"index.php?do=docs$nav_target$nav_doc$navi_sort$navi_docstatus$nav_Titel&page={s}&amp;cp=" . SESSION . "$nav_rub$nav_Zeit$nav_rub$nav_limit$pop$showsimple$showsimple_edit$idonly\">{t}</a> ");
    if($num > $limit) $GLOBALS['tmpl']->assign("page_nav", $page_nav);
	
	$formaction_script = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."$nav_rub";
	$GLOBALS['tmpl']->assign("formaction_script", $formaction_script);
	
	$select = "<select name=\"page\" id=\"page\" onChange=\"selectPage();\">";
	for ($p=1; $p <= $seiten; $p++){
			$selected = ($p==$_REQUEST['page']) ? "selected" : "";
			$select.="<option value=\"".$p."\" ".$selected.">".$p."</option>";
	}
	$select.="</select>";
	$GLOBALS['tmpl']->assign("select", $select);
  }

  function showRubName($id) {
    $sql = $GLOBALS['db']->Query("SELECT RubrikName FROM " . PREFIX . "_rubrics WHERE Id='$id'");
    $row = $sql->fetchrow();
    return $row->RubrikName;
  }

  function showRubs() {

    $sql = $GLOBALS['db']->Query("SELECT Id,RubrikName FROM " . PREFIX . "_rubrics");
    $rubs = array();
    while($row = $sql->fetchrow()) {
      array_push($rubs, $row);
    }
    return $rubs;
  }

  function newComment($id,$reply = '') {

    if(isset($_REQUEST["sub"]) && $_REQUEST['sub'] == 'save') {
      if(!empty($_REQUEST["Kommentar"]) && cp_perm("docs_comments") && $_REQUEST['reply'] != 1) {
        $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_document_comments (
        Id,
        DokumentId,
        Titel,
        Kommentar,
        Author,
        Zeit,
        KommentarStart,
        AntwortEMail
        ) VALUES (
        '',
        '$_REQUEST[Id]',
        '" .htmlspecialchars($_REQUEST['Titel']). "',
        '" .substr(htmlspecialchars($_REQUEST['Kommentar']),0, $this->_max_commentlength). "',
        '" .$_SESSION['cp_uname']. "',
        '" .time(). "',
        '1',
        '" .$_SESSION["cp_email"]. "'
        )");
      }
      header("Location:index.php?do=docs&action=comment_reply&Id=$_REQUEST[Id]&pop=1&cp=". SESSION);
    }


    if($reply == 1) {
      if(isset($_REQUEST["sub"]) && $_REQUEST['sub'] == 'save') {
        if(!empty($_REQUEST["Kommentar"]) && cp_perm("docs_comments")) {
          $comm = $GLOBALS['db']->Query("SELECT AntwortEMail FROM  " . PREFIX . "_document_comments WHERE KommentarStart = '1' AND DokumentId = '$_REQUEST[Id]'");
          $row = $comm->fetchrow();

          $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_document_comments (
          Id,
          DokumentId,
          Titel,
          Kommentar,
          Author,
          Zeit,
          KommentarStart
          ) VALUES (
          '',
          '$_REQUEST[Id]',
          '" .htmlspecialchars($_REQUEST['Titel']). "',
          '" .substr(htmlspecialchars($_REQUEST['Kommentar']),0, $this->_max_commentlength). "',
          '" .$_SESSION['cp_uname']. "',
          '" .time(). "',
          '0'
          )");
        }

        $globals = new Globals;
        $SystemMail = $GLOBALS['globals']->cp_settings('Mail_Absender');
        $SystemMailName = $GLOBALS['globals']->cp_settings('Mail_Name');

        $host = explode('?', redir());
        $host_real = substr($host[0],0,-9);
        $host_real = $host_real . "/index.php?do=docs&doc_id=$_REQUEST[Id]";

        $body_toadmin = $GLOBALS['config_vars']['DOC_MAIL_BODY_NOTICE'];
        $body_toadmin = str_replace("%N%", "\n", $body_toadmin);
        $body_toadmin = str_replace("%TITLE%", stripslashes($_POST['Titel']), $body_toadmin);
        $body_toadmin = str_replace("%USER%", $_SESSION['cp_uname'], $body_toadmin);
        $body_toadmin = str_replace("%LINK%", $host_real, $body_toadmin);
        $GLOBALS['globals']->cp_mail($row->AntwortEMail, $body_toadmin, $GLOBALS['config_vars']['DOC_MAIL_SUBJECT_NOTICE'], $SystemMail, $SystemMailName, "text", "");

        header("Location:index.php?do=docs&action=comment_reply&RubrikId=$_REQUEST[RubrikId]&Id=$_REQUEST[Id]&pop=1&cp=". SESSION);
      }


      $sql_n = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_document_comments WHERE DokumentId = '$_REQUEST[Id]'");
      $num_n = $sql_n->numrows();

      $limit = 10;
      $seiten = ceil($num_n / $limit);
      $start = prepage() * $limit - $limit;

      $answers = array();
      $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_document_comments WHERE DokumentId = '$_REQUEST[Id]' ORDER BY Id DESC LIMIT $start,$limit");
      while($row = $sql->fa()) {
        $row['Kommentar'] = nl2br($row['Kommentar']);
        array_push($answers, $row);
      }

      $sql_a = $GLOBALS['db']->Query("SELECT Aktiv FROM " . PREFIX . "_document_comments WHERE DokumentId = '$_REQUEST[Id]' AND KommentarStart='1'");
      $row_a = $sql_a->fetcharray();

      $page_nav = pagenav($seiten, prepage()," <a class=\"pnav\" href=\"index.php?do=docs&action=comment_reply&Id=$_REQUEST[Id]&pop=1&page={s}&amp;cp=".SESSION. "\">{t}</a> ");
      if($num_n > $limit) $GLOBALS['tmpl']->assign("page_nav", $page_nav);

      $GLOBALS['tmpl']->assign("row_a", $row_a);
      $GLOBALS['tmpl']->assign("answers", $answers);
      $GLOBALS['tmpl']->assign("reply", 1);
      $GLOBALS['tmpl']->assign("formaction", "index.php?do=docs&action=comment_reply&reply=1&sub=save&cp=".SESSION. "&Id=$_REQUEST[Id]");
      $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("documents/newcomment.tpl"));
    } else {
      $GLOBALS['tmpl']->assign("reply", 1);
      $GLOBALS['tmpl']->assign("new", 1);
      $GLOBALS['tmpl']->assign("formaction", "index.php?do=docs&action=comment&sub=save&cp=".SESSION. "&Id=$_REQUEST[Id]");
      $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("documents/newcomment.tpl"));
    }
  }

  function openCloseDiscussion($id) {
    $GLOBALS['db']->Query("UPDATE " . PREFIX . "_document_comments SET Aktiv = '$_REQUEST[Aktiv]' WHERE KommentarStart = '1' AND DokumentId = '$_REQUEST[Id]'");
    header("Location:index.php?do=docs&action=comment_reply&Id=$_REQUEST[Id]&pop=1&cp=". SESSION);
    exit;
  }

  function delComment($id,$start) {
    if($start==1) {
      $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_document_comments WHERE DokumentId = '$_REQUEST[Id]'");
      header("Location:index.php?do=docs&action=comment&Id=$_REQUEST[Id]&pop=1&cp=". SESSION);
      exit;
    } else {
      $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_document_comments WHERE DokumentId = '$_REQUEST[Id]' AND Id = '$_REQUEST[CId]'");
      header("Location:index.php?do=docs&action=comment_reply&Id=$_REQUEST[Id]&pop=1&cp=". SESSION);
      exit;
    }
  }

  function getField($RubTyp,$inhalt,$Id, $new, $docid = '', $redakteur = '', $drop='') {
    $docstart = '';
    $docredi = '';

    if($docid != '') {
      $sql = $GLOBALS['db']->Query("SELECT DokStart FROM " . PREFIX . "_documents WHERE Id = '$docid'");
      $row = $sql->fetchrow();
      $sql->close();
      $docstart = $row->DokStart;

      $sql = $GLOBALS['db']->Query("SELECT Vorname,Nachname FROM " . PREFIX . "_users WHERE Id = '$redakteur'");
      $row = $sql->fetchrow();
      $sql->close();
      $docredi = $row->Vorname . " " . $row->Nachname;
    }

    $img_pixel = "templates/" . $_SESSION["cp_admin_theme"] . "/images/blanc.gif";
    $inhalt = str_replace("[cp:replacement]", REPLACEMENT,$inhalt);

    $feld = "";
    $ft = ($new == 1) ? "feld" : "feld";

    switch($RubTyp) {
      case 'kurztext' :
        $feld = "<a name=\"$Id\"></a><input id=\"feld_$Id\" type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value='".$inhalt. "'> ";
      break;

      case 'langtext' :
        if(isset($_COOKIE['no_wysiwyg']) && $_COOKIE['no_wysiwyg'] == 1)
        {
          $feld = "<a name=\"$Id\"></a><textarea style=\"width:".$this->_textarea_width. "; height:".$this->_textarea_height. "\"  name=\"".$ft. "[$Id]\">".$inhalt. "</textarea>";
        }
        else
        {
          $oFCKeditor->BasePath = SOURCE_DIR . "editor/" ;
          $oFCKeditor = new FCKeditor($ft. "[$Id]") ;
          $oFCKeditor->Height = $this->_textarea_height;
          $oFCKeditor->Value  = $inhalt;
          $feld = $oFCKeditor->Create($Id);
          $GLOBALS['tmpl']->assign("extended_insert", 1);
          $feld .= "<input class=\"button\" onclick=\"insertHTML('$Id', '<h3>[cp:newpage]</h3>');\" value=\"".$GLOBALS['config_vars']['DOC_NEW_PAGE']. "\" type=\"button\">";
        }
      break;

	/* Добавлено */

      case 'smalltext' :
        //if(isset($_COOKIE['no_wysiwyg']) && $_COOKIE['no_wysiwyg'] == 1)
        //{
          $feld = "<a name=\"$Id\"></a><textarea style=\"width:".$this->_textarea_width_small. "; height:".$this->_textarea_height_small. "\"  name=\"".$ft. "[$Id]\">".$inhalt. "</textarea>";
        //}
        //else
        //{
        //  $oFCKeditor->BasePath = SOURCE_DIR . "editor/" ;
        //  $oFCKeditor = new FCKeditor($ft. "[$Id]") ;
        //  $oFCKeditor->Height = $this->_textarea_height_small;
         // $oFCKeditor->Value  = $inhalt;
		//  $oFCKeditor->ToolbarSet	= 'cpengine_small';
        //  $feld = $oFCKeditor->Create($Id);
        //  $GLOBALS['tmpl']->assign("extended_insert", 1);
       // }
      break;

      case 'created' :
        $globals = new Globals;
        $inhalt = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'new') ? date($GLOBALS['globals']->cp_settings("Zeit_Format")) : (($inhalt == "") ? date($GLOBALS['globals']->cp_settings("Zeit_Format"),$docstart) : $inhalt);
		$inhalt = strtr($inhalt, array('January'=>'января','February'=>'февраля','March'=>'марта','April'=>'апреля','May'=>'мая','June'=>'июня','July'=>'июля','August'=>'августа','September'=>'сентября','October'=>'октября','November'=>'ноября','December'=>'декабря','Sunday'=>'Воскресенье','Monday'=>'Понедельник','Tuesday'=>'Вторник','Wednesday'=>'Среда','Thursday'=>'Четверг','Friday'=>'Пятница','Saturday'=>'Суббота',));
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'new') {
          $feld = "<a name=\"$Id\"></a><input id=\"feld_$Id\" type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".$inhalt. "\">";
        } else {
          $feld = "<a name=\"$Id\"></a><input id=\"feld_$Id\" type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".$inhalt. "\"> <input type=\"button\" value=\"Текущая дата\" class=\"button\" onclick=\"insert_now_date('feld_$Id');\" />";
        }
      break;

      case 'author':
        $inhalt = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'new') ? $_SESSION["cp_uname"] : $inhalt;
        $feld = "<a name=\"$Id\"></a><input id=\"feld_$Id\" type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".$inhalt. "\"> ";
      break;

//      case 'bild' :
//        $feld = "<a name=\"$Id\"></a><div id=\"feld_$Id\"><img id=\"_img_".$ft. "__$Id\" src=\"". (($inhalt != '') ? $inhalt : "$img_pixel") . "\" alt=\"\" border=\"0\" /></div><div style=\"display:none\" id=\"span_".$ft. "__$Id\">&nbsp;</div>".(($inhalt != '') ? "<br />" : ""). "<input type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".htmlspecialchars($inhalt). "\" id=\"img_".$ft. "__$Id\" /> <input value=\"".$GLOBALS['config_vars']['MAIN_OPEN_MEDIAPATH']. "\" class=\"button\" type=\"button\" onclick=\"cp_imagepop('img_".$ft. "__$Id','','','0');\" />";
//      break;
	  
	  case 'bild' :
        $massiv = explode("||",$inhalt);
        $feld = "<a name=\"$Id\"></a><div id=\"feld_$Id\"><img id=\"_img_".$ft. "__$Id\" src=\"". (($inhalt != '') ? $massiv[0] : "$img_pixel") . "\" alt=\"" . $massiv[1] . "\" border=\"0\" /></div><div style=\"display:none\" id=\"span_".$ft. "__$Id\">&nbsp;</div>".(($inhalt != '') ? "<br />" : ""). "<input type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".htmlspecialchars($inhalt). "\" id=\"img_".$ft. "__$Id\" /> <input value=\"".$GLOBALS['config_vars']['MAIN_OPEN_MEDIAPATH']. "\" class=\"button\" type=\"button\" onclick=\"cp_imagepop('img_".$ft. "__$Id','','','0');\" />";
      break;

	  case 'bild_links' :
        $massiv = explode("||",$inhalt);
        $feld = "<a name=\"$Id\"></a><div id=\"feld_$Id\"><img id=\"_img_".$ft. "__$Id\" src=\"". (($inhalt != '') ? $massiv[0] : "$img_pixel") . "\" alt=\"" . $massiv[1] . "\" border=\"0\" /></div><div style=\"display:none\" id=\"span_".$ft. "__$Id\">&nbsp;</div>".(($inhalt != '') ? "<br />" : ""). "<input type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".htmlspecialchars($inhalt). "\" id=\"img_".$ft. "__$Id\" /> <input value=\"".$GLOBALS['config_vars']['MAIN_OPEN_MEDIAPATH']. "\" class=\"button\" type=\"button\" onclick=\"cp_imagepop('img_".$ft. "__$Id','','','0');\" />";
      break;

	  case 'bild_rechts' :
        $massiv = explode("||",$inhalt);
        $feld = "<a name=\"$Id\"></a><div id=\"feld_$Id\"><img id=\"_img_".$ft. "__$Id\" src=\"". (($inhalt != '') ? $massiv[0] : "$img_pixel") . "\" alt=\"" . $massiv[1] . "\" border=\"0\" /></div><div style=\"display:none\" id=\"span_".$ft. "__$Id\">&nbsp;</div>".(($inhalt != '') ? "<br />" : ""). "<input type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".htmlspecialchars($inhalt). "\" id=\"img_".$ft. "__$Id\" /> <input value=\"".$GLOBALS['config_vars']['MAIN_OPEN_MEDIAPATH']. "\" class=\"button\" type=\"button\" onclick=\"cp_imagepop('img_".$ft. "__$Id','','','0');\" />";
      break;

      case 'javascript' :
        $feld = "<a name=\"$Id\"></a><textarea id=\"feld_$Id\" style=\"width:".$this->_textarea_width. "; height:".$this->_textarea_height. "\"  name=\"".$ft. "[$Id]\">".stripslashes($inhalt). "</textarea>";
      break;

      case 'php' :
        $feld = "<a name=\"$Id\"></a><textarea id=\"feld_$Id\" style=\"width:".$this->_textarea_width. "; height:".$this->_textarea_height. "\"  name=\"".$ft. "[$Id]\">".stripslashes($inhalt). "</textarea>";
      break;

      case 'code' :
        $feld = "<a name=\"$Id\"></a><textarea id=\"feld_$Id\" style=\"width:".$this->_textarea_width. "; height:".$this->_textarea_height. "\"  name=\"".$ft. "[$Id]\">".stripslashes($inhalt). "</textarea>";
      break;

      case 'html' :
        $feld = "<a name=\"$Id\"></a><textarea id=\"feld_$Id\" style=\"width:".$this->_textarea_width. "; height:".$this->_textarea_height. "\"  name=\"".$ft. "[$Id]\">".stripslashes($inhalt). "</textarea>";
      break;

      case 'js' :
        $feld = "<a name=\"$Id\"></a><textarea id=\"feld_$Id\" style=\"width:".$this->_textarea_width. "; height:".$this->_textarea_height. "\"  name=\"".$ft. "[$Id]\">".stripslashes($inhalt). "</textarea>";
      break;

      case 'flash' :
        $feld = "<a name=\"$Id\"></a><div style=\"display:none\" id=\"feld_$Id\"><img style=\"display:none\" id=\"_img_".$ft. "__$Id\" src=\"". (($inhalt != '') ? $inhalt : "$img_pixel") . "\" alt=\"\" border=\"0\" /></div><div style=\"display:none\" id=\"span_".$ft. "__$Id\"></div>".(($inhalt != '') ? "" : ""). "<input type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".htmlspecialchars($inhalt). "\" id=\"img_".$ft. "__$Id\" /> <input value=\"".$GLOBALS['config_vars']['MAIN_OPEN_MEDIAPATH']. "\" class=\"button\" type=\"button\" onclick=\"cp_imagepop('img_".$ft. "__$Id','','','0');\" />";
        $feld .= '<input style="margin-left:5px" class="button" type="button" value="?" onmouseover="return overlib(\''.$GLOBALS['config_vars']['DOC_FLASH_TYPE_HELP'].'\',ABOVE,WIDTH,300);" onmouseout="nd();" style="cursor: help;">';
      break;

      case 'download' :
        $feld = "<div style=\"\" id=\"feld_$Id\"><a name=\"$Id\"></a><div style=\"display:none\" id=\"feld_$Id\"><img style=\"display:none\" id=\"_img_".$ft. "__$Id\" src=\"". (($inhalt != '') ? $inhalt : "$img_pixel") . "\" alt=\"\" border=\"0\" /></div><div style=\"display:none\" id=\"span_".$ft. "__$Id\"></div>".(($inhalt != '') ? "" : ""). "<input type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".htmlspecialchars($inhalt). "\" id=\"img_".$ft. "__$Id\" /> <input value=\"".$GLOBALS['config_vars']['MAIN_OPEN_MEDIAPATH']. "\" class=\"button\" type=\"button\" onclick=\"cp_imagepop('img_".$ft. "__$Id','','','0');\" />";
        $feld .= '<input style="margin-left:5px" class="button" type="button" value="?" onmouseover="return overlib(\''.$GLOBALS['config_vars']['DOC_FILE_TYPE_HELP'].'\',ABOVE,WIDTH,300);" onmouseout="nd();" style="cursor: help;">';
        $feld .= '</div>';
      break;

      case 'video_avi' :
      case 'video_wmf' :
      case 'video_wmv' :
        $feld = "<div style=\"\" id=\"feld_$Id\"><a name=\"$Id\"></a><div style=\"display:none\" id=\"feld_$Id\"><img style=\"display:none\" id=\"_img_".$ft. "__$Id\" src=\"". (($inhalt != '') ? $inhalt : "$img_pixel") . "\" alt=\"\" border=\"0\" /></div><div style=\"display:none\" id=\"span_".$ft. "__$Id\"></div>".(($inhalt != '') ? "" : ""). "<input type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".htmlspecialchars($inhalt). "\" id=\"img_".$ft. "__$Id\" /> <input value=\"".$GLOBALS['config_vars']['MAIN_OPEN_MEDIAPATH']. "\" class=\"button\" type=\"button\" onclick=\"cp_imagepop('img_".$ft. "__$Id','','','0');\" />";
        $feld .= '<input style="margin-left:5px" class="button" type="button" value="?" onmouseover="return overlib(\''.$GLOBALS['config_vars']['DOC_VIDEO_TYPE_HELP'].'\',ABOVE,WIDTH,300);" onmouseout="nd();" style="cursor: help;">';
        $feld .= '</div>';
      break;


      case 'video_mov' :
        $feld = "<div style=\"\" id=\"feld_$Id\"><a name=\"$Id\"></a><div style=\"display:none\" id=\"feld_$Id\"><img style=\"display:none\" id=\"_img_".$ft. "__$Id\" src=\"". (($inhalt != '') ? $inhalt : "$img_pixel") . "\" alt=\"\" border=\"0\" /></div><div style=\"display:none\" id=\"span_".$ft. "__$Id\"></div>".(($inhalt != '') ? "" : ""). "<input type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".htmlspecialchars($inhalt). "\" id=\"img_".$ft. "__$Id\" /> <input value=\"".$GLOBALS['config_vars']['MAIN_OPEN_MEDIAPATH']. "\" class=\"button\" type=\"button\" onclick=\"cp_imagepop('img_".$ft. "__$Id','','','0');\" />";
        $feld .= '<input style="margin-left:5px" class="button" type="button" value="?" onmouseover="return overlib(\''.$GLOBALS['config_vars']['DOC_VIDEO_TYPE_HELP'].'\',ABOVE,WIDTH,300);" onmouseout="nd();" style="cursor: help;">';
        $feld .= '</div>';
      break;


      case 'dropdown' :
        $items = explode(",",$inhalt);
        $feld = "<select name=\"".$ft. "[$Id]\">";
        $cnt = count($items);

        for ($i=0;$i<$cnt;$i++) {
          if (trim($drop) == trim($items[$i])) {
            $feld .= "<option value=\"".$items[$i]."\" selected>".$items[$i]."</option>";
          } else{
            $feld .= "<option value=\"".$items[$i]."\">".$items[$i]."</option>";
          }
        }

        $feld .= "</select>";
      break;

      case 'link' :
        $feld = "<a name=\"$Id\"></a><input id=\"feld_$Id\" type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".$inhalt. "\"> <input value=\"".$GLOBALS['config_vars']['MAIN_BROWSE_DOCUMENTS']. "\" class=\"button\" type=\"button\" onclick=\"openLinkWin('feld_$Id','feld_$Id');\" />";
		$feld .= '<input style="margin-left:5px" class="button" type="button" value="?" style="cursor: help;" onmouseover="return overlib(\''.$GLOBALS['config_vars']['DOC_LINK_TYPE_HELP'].'\',ABOVE,WIDTH,300);" onmouseout="nd();"  />';
      break;

      case 'link_ex' :
        $feld = "<a name=\"$Id\"></a><input id=\"feld_$Id\" type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".$inhalt. "\"> <input value=\"".$GLOBALS['config_vars']['MAIN_BROWSE_DOCUMENTS']. "\" class=\"button\" type=\"button\" onclick=\"openLinkWin('feld_$Id','feld_$Id');\" />";
      break;
	  
	  //Добавление полей тэги в админке
	  case 'tags':
	  		$id_tags = array();
	  		$sql_id = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_rubric_fields WHERE RubTyp = 'tags'");
      		while($row_id = $sql_id->fetchrow()){
				array_push($id_tags, $row_id->Id);
			}
			
			$id = array_unique($id_tags);							
			$label = array();
			$result = array();
			for ($i=0; $i<count($id); $i++) { 
				$sql_tags = $GLOBALS['db']->Query("SELECT Inhalt FROM " . PREFIX . "_document_fields WHERE  RubrikFeld='".$id[$i]."'");
      			while($row_tags = $sql_tags->fetchrow()){
					if (!empty($row_tags->Inhalt)) array_push($label, $row_tags->Inhalt);
				}
			}
					
			$feld = '';
			$labels = implode(',', $label);
							
				$tags = explode(',', $labels);
				for ($g=0; $g<count($tags);$g++){ $tag[$g] = trim($tags[$g]);}
				$j = 0;
				for ($z=0; $z<count($tag);$z++){
					if (!in_array($tag[$z], $result)){
						$result[$j] = $tag[$z];
						$j++;
					}
				}

				if (!empty($result)){				
					for($k=0; $k<count($result); $k++) {
						$feld.= "<a href=\"javascript:void(0);\" onClick = \"AddTags(this,'feld_".$Id."');\">".$result[$k].'</a>| ';
					}
				}
			
			$feld.= "<br/><br/><input type=\"text\" value=\"".$inhalt."\" style=\"width:".$this->_field_width."\" id=\"feld_$Id\" name=\"".$ft. "[$Id]\"/>&nbsp;(Перечисляйте тэги через запятую)";
					
	  break;
	  
    }
  return $feld;
  }

  function changeRubs() {
    if ((!empty($_POST['NewRubr'])) and (!empty($_GET['Id']))) {
      foreach ($_POST as $key=>$value) {
        if (is_integer($key)) {
          switch ($value) {
            case 0:
              $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_document_fields WHERE ((DokumentId='" . (int)$_REQUEST['Id'] . "') && (RubrikFeld='" . $key . "'))");
              break;
            case -1:
              //информация о старом поле
              $sql_fd = $GLOBALS['db']->Query("SELECT Titel,RubTyp FROM " . PREFIX . "_rubric_fields WHERE Id='" . $key . "'");
              $row_fd = $sql_fd->fetchrow();

              //последняя позиция в новой рубрике
              $sql_pos = $GLOBALS['db']->Query("SELECT rubric_position FROM " . PREFIX . "_rubric_fields WHERE RubrikId='" . (int)$_POST['NewRubr'] . "' ORDER BY `rubric_position` DESC LIMIT 0, 1");
              $row_pos = $sql_pos->fetchrow();
              $new_pos = ($row_pos->rubric_position) + 1;

              //создаем новое поле
              $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_rubric_fields (RubrikId,Titel,RubTyp,rubric_position) VALUES ('" . (int)$_POST['NewRubr'] . "','" . $row_fd->Titel . "','" . $row_fd->RubTyp . "','" . $new_pos . "')");

              //добавляем запись о поле в таблицу с полями документов
              $lastid = $GLOBALS['db']->InsertId();
              $sql_docs = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_documents WHERE RubrikId='" . (int)$_POST['NewRubr'] . "'");
              while($row_docs = $sql_docs->fetchrow()) {
                $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_document_fields (RubrikFeld,DokumentId,Inhalt,Suche ) VALUES ('" . $lastid . "','" . $row_docs->Id . "','','1')");
              }

              //создаем новое поле для изменяемого документа
              $GLOBALS['db']->Query("UPDATE " . PREFIX . "_document_fields SET RubrikFeld= " . $lastid . " WHERE ((RubrikFeld=" . $key . ") && (DokumentId=" . (int)$_REQUEST['Id'] . "))");

              break;
            default:
              $GLOBALS['db']->Query("UPDATE " . PREFIX . "_document_fields SET RubrikFeld= " . $value . " WHERE ((RubrikFeld=" . $key . ") && (DokumentId=" . (int)$_REQUEST['Id'] . "))");
              break;
          }
        }
      }

      //получаем список всех полей новой рубрики
      $sql_rub = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_rubric_fields WHERE RubrikId='" . (int)$_POST['NewRubr'] . "' ORDER BY Id ASC");

      //проверяем наличие нужных полей
      while($row_rub = $sql_rub->fetchrow()) {
        $sql_exist_rub = $GLOBALS['db']->Query("SELECT RubrikFeld FROM " . PREFIX . "_document_fields WHERE ((RubrikFeld='" . $row_rub->Id . "') && (DokumentId='" . (int)$_REQUEST['Id'] . "'))");
        $num = $sql_exist_rub->numrows();

        //если поля нет, тогда создадим его
        if ($num == 0)  $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_document_fields (RubrikFeld,DokumentId,Inhalt,Suche) VALUES ('" . $row_rub->Id . "','" . (int)$_REQUEST['Id'] . "','','1')");
      }
      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_documents SET RubrikId= " . (int)$_POST['NewRubr'] . " WHERE Id=" . (int)$_REQUEST['Id']);
      echo "<script>window.opener.location.reload(); window.close();</script>";

    } else {
      if ((!empty($_GET['NewRubr'])) and ((int)$_REQUEST['RubrikId'] != (int)$_GET['NewRubr'])) {
        //выбираем все поля новой рубрики
        $sql_rub = $GLOBALS['db']->Query("SELECT Id,Titel,RubTyp FROM " . PREFIX . "_rubric_fields WHERE RubrikId='" . (int)$_GET['NewRubr'] . "' ORDER BY Id ASC");
        $mass_new_rubr = Array();
        while($row_rub = $sql_rub->fetchrow()) {
          $mass_new_rubr[] = array('Id'=>$row_rub->Id, 'Titel'=>$row_rub->Titel, 'RubTyp'=>$row_rub->RubTyp);
        }

        //обрабатываем все поля старой рубрики
        $sql_old_rub = $GLOBALS['db']->Query("SELECT Id,Titel,RubTyp FROM " . PREFIX . "_rubric_fields WHERE RubrikId='" . (int)$_REQUEST['RubrikId'] . "' ORDER BY Id ASC");
        $field_arra = array();
        while($row_nr = $sql_old_rub->fetchrow()) {
          $type = $row_nr->RubTyp;
          $option_arr = array('0'=>$GLOBALS['config_vars']['DOC_CHANGE_DROP_FIELD'], '-1'=>$GLOBALS['config_vars']['DOC_CHANGE_CREATE_FIELD']);
          $selected = -1;
          foreach ($mass_new_rubr as $row) {
            if ($row['RubTyp'] == $type) {
              $option_arr[$row['Id']] = $row['Titel'];
              if ($row_nr->Titel == $row['Titel']) {
                $selected = $row['Id'];
              }
            }
          }
          $field_arr[$row_nr->Id] = array('Titel'=>$row_nr->Titel, 'Options'=>$option_arr, 'Selected'=>$selected);
        }
      }

      $GLOBALS['tmpl']->assign("fields", $field_arr);
      $GLOBALS['tmpl']->assign("formaction", "index.php?do=docs&action=change&Id=" . (int)$_REQUEST['Id'] . "&RubrikId=" . (int)$_REQUEST['RubrikId'] . "&pop=1&cp=" . SESSION);
      $GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("documents/change.tpl"));
    }
  }
}
?>
