<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 1)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: February 22, 2008
::::::::::::::::::::::::::::::::::::::::*/


class docs {

	var $_limit = 15;
	var $_field_width = "400px";
	var $_textarea_width = "98%";
	var $_textarea_height = "400px";
	var $_max_commentlength = 5000;

	//=======================================================
	//  HTML - verbessern
	//=======================================================
	function prettyCode($code) {
//		$code = str_replace(REPLACEMENT, "[cp:replacement]", $code);
		$code = eregi_replace("<b>", "<strong>", $code);
		$code = eregi_replace("</b>", "</strong>", $code);
		$code = eregi_replace("<i>", "<em>", $code);
		$code = eregi_replace("</i>", "</em>", $code);
		$code = eregi_replace("<br>", "<br />", $code);
		$code = eregi_replace("<br/>", "<br />", $code);
		$code = eregi_replace(REPLACEMENT, "[cp:replacement]", $code);
		return $code;
	}

	//=======================================================
	//  Nur die Rubriken anzeigen, fьr die der Redakteur
	//  Rechte besitzt
	//=======================================================
	function rediRubs() {
		$items = array();
		$sql_rr = $GLOBALS['db']->Query("SELECT Id,RubrikName FROM " . PREFIX . "_rubrics");
		while($row_rr = $sql_rr->fetchrow()) {
			$this->fetchDocPerms($row_rr->Id);

			if(@$_SESSION[$row_rr->Id . '_editown'] == 1) $row_rr->Show = 1;
			if(@$_SESSION[$row_rr->Id . '_new'] == 1) $row_rr->Show = 1;
			if(@$_SESSION[$row_rr->Id . '_newnow'] == 1) $row_rr->Show = 1;
			if(@$_SESSION[$row_rr->Id . '_alles'] == 1) $row_rr->Show = 1;
			if(UGROUP == 1) $row_rr->Show = 1;

			array_push($items, $row_rr);
			unset($row_rr);
		}
		$GLOBALS['tmpl']->assign("rub_items",$items);
	}

	//=======================================================
	//  Ablauf
	//=======================================================
	function dokEnde() {
		$ende = 0;
		if($_REQUEST['DokEndeDay'] != '' && $_REQUEST['DokEndeYear'] != ''  && $_REQUEST['DokEndeMonth'] != '')	{
			$ende = mktime($_REQUEST['EndeHour'], $_REQUEST['EndeMinute'] ,0, $_REQUEST['DokEndeMonth'], $_REQUEST['DokEndeDay'], $_REQUEST['DokEndeYear']);
		}
		return $ende;
	}

	//=======================================================
	//  Start
	//=======================================================
	function dokStart() {
		$start = 0;
		if($_REQUEST['DokStartDay'] != '' && $_REQUEST['DokStartYear'] != ''  && $_REQUEST['DokStartMonth'] != '') {
			$start = mktime($_REQUEST['StartHour'], $_REQUEST['StartMinute'] ,0, $_REQUEST['DokStartMonth'], $_REQUEST['DokStartDay'], $_REQUEST['DokStartYear']);
		}
		return $start;
	}

	//=======================================================
	//  Doukument - Rechte
	//=======================================================
	function fetchDocPerms($RubId) {
		$sql_dp = $GLOBALS['db']->Query("SELECT Rechte FROM " . PREFIX . "_document_permissions WHERE RubrikId = '" .(int)$RubId. "' AND BenutzerGruppe = '" . UGROUP . "'");
		while($row_dp = $sql_dp->fetchrow()) {
			$dPerms = explode("|", $row_dp->Rechte);
			foreach($dPerms as $mydPerm) {
				if(!empty($mydPerm)) $_SESSION[$RubId . '_' . $mydPerm] = 1;
			}
		}
	}

	//=======================================================
	//  Speichern
	//=======================================================
	function editDoc($id) {
		switch($_REQUEST['sub']) {
			case 'save':
			$sql = $GLOBALS['db']->Query("SELECT Id,RubrikId,Redakteur FROM " . PREFIX . "_documents WHERE Id = '" .$_REQUEST['Id']. "'");
			$row = $sql->fetchrow();
			$row->cantEdit = 0;

			if( ($row->Redakteur == @$_SESSION['cp_benutzerid']) &&
				(isset($_SESSION[$row->RubrikId . '_editown']) &&
				@$_SESSION[$row->RubrikId . '_editown'] == 1) ||
				@$_SESSION[$row->RubrikId . '_alles'] == 1
			)
			{
				if($row->Id != 1 && $row->Id != 2) {
					$row->cantEdit = 1;
				}
			}

			if( (isset($_SESSION[$row->RubrikId . '_editall']) &&
				$_SESSION[$row->RubrikId . '_editall'] == 1) ||
				@$_SESSION[$row->RubrikId . '_alles'] == 1 ||
				(@$_SESSION[$row->RubrikId . '_editown'] == 1 && $row->Redakteur == @$_SESSION['cp_benutzerid'])
			)
			{
				$row->cantEdit = 1;
			}

			// Ist Admin und darf alles!
			if(UGROUP==1)
				$row->cantEdit = 1;

			if($row->cantEdit==1) {
				$suche = (isset($_POST['Suche']) && $_POST['Suche'] == 1) ? 1 : 0;
				$docstatus = (isset($_REQUEST['DokStatus']) && $_REQUEST['DokStatus'] == 1) ? 1 : 0;

				if($_FILES['preImage']['size'] != 0) {
					$path_parts = pathinfo($_FILES['preImage']['name']);
					$uploadTo = BASE_DIR . '/modules/news/preimages/' . $_REQUEST['Id'] . '.' . $path_parts['extension'];
					if(move_uploaded_file($_FILES['preImage']['tmp_name'], $uploadTo)) {
						chmod($uploadTo, 0644);
						if($this->ResizeImg2($uploadTo, $uploadTo, $_POST['maxW'], $_POST['maxH'])) {
							$q = "UPDATE " . PREFIX . "_documents
								SET preimage = '" . $_REQUEST['Id'] . '.' . $path_parts['extension'] . "'
								WHERE Id = '" . $_REQUEST['Id'] . "' LIMIT 1";
							$sql = $GLOBALS['db']->Query($q);
							setcookie("maxW", $_POST['maxW'], time()+31536000, '/');
							setcookie("maxH", $_POST['maxH'], time()+31536000, '/');
						}
					}
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
					DokEnde = '" . $this->dokEnde() . "',
					DokStart = '" . $this->dokStart() . "',
					ElterNavi = '" . $_POST['ElterNavi'] . "',
					rubid='" .$_POST['Rubrika']. "',
					pretext='" .mysql_escape_string($_POST['preText']). "'
					WHERE Id = '" . (int)$_REQUEST['Id'] . "'");


				if(isset($_POST['feld'])) {
					foreach ($_POST['feld'] as $id => $f) {
						$Eintrag = true;

						//=======================================================
						//  Prьfen, welcher Feld - Typ aktualisiert werden soll
						//=======================================================
						$sql_tc = $GLOBALS['db']->Query("SELECT RubrikFeld FROM " . PREFIX . "_document_fields WHERE Id = '$id'");
						$row_tc = $sql_tc->fetchrow();
						$sql_tc->Close();

						$sql_rf = $GLOBALS['db']->Query("SELECT RubTyp FROM " . PREFIX . "_rubric_fields WHERE Id = '$row_tc->RubrikFeld'");
						$row_rf = $sql_rf->fetchrow();
						$sql_rf->Close();

						//=======================================================
						// Wenn PHP - Code eingefьgt werden kann, muss geprьft werden,
						// ob der Redakteur dies auch darf
						//=======================================================
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

						if($Eintrag) {
							$q = "UPDATE " . PREFIX . "_document_fields
								SET
								Inhalt = '" . $this->prettyCode($_POST['feld'][$id]) . "' ,
								Suche = '" . $suche . "'
								WHERE Id = '$id'";
							$sql = $GLOBALS['db']->Query($q);
							reportLog($_SESSION["cp_uname"] . " - отредактировал документ (".$id. ")",'2','2');
						}
					}
				}
			}

			if(isset($_REQUEST['closeafter']) && $_REQUEST['closeafter']==1) {
				echo "<script>window.opener.location.reload(); window.close();</script>";
				exit;
			}

			echo "<script>window.opener.location.reload();</script>";
			$GLOBALS['tmpl']->assign("name_empty", $GLOBALS['config_vars']['DOC_TOP_MENU_ITEM']);
			$GLOBALS['tmpl']->assign("newsRub", $_POST['Rubrika']);
			$GLOBALS['tmpl']->assign("Id", $_REQUEST['Id']);
			$GLOBALS['tmpl']->assign("RubrikId", $_REQUEST['RubrikId']);
			$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("documents/form_after2.tpl"));
			break;

			case 'savenavi':

				$elter_pre = ($_REQUEST['Elter']=='0') ? 0 : explode("____", $_REQUEST['Elter']);
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

			//=======================================================
			//  Auslesen
			//=======================================================
			case '':
				$sql_redi = $GLOBALS['db']->Query("SELECT Redakteur,RubrikId FROM " . PREFIX . "_documents WHERE Id = '" . (int)$_REQUEST['Id'] . "'");
				$row_redi = $sql_redi->fetchrow();
				$sql_redi->Close();

				$show = true;

				$this->fetchDocPerms($row_redi->RubrikId);

				if( ($row_redi->Redakteur != $_SESSION['cp_benutzerid']) && ( @$_SESSION[$row_redi->RubrikId . '_editall'] != 1 || @$_SESSION[$row_redi->RubrikId . '_alles'] != 1) ) {
					$show = false;
				}

				if ( ($row_redi->Redakteur != $_SESSION['cp_benutzerid']) && (@$_SESSION[$row_redi->RubrikId . '_editown'] != 1)) {
					 $show = false;
				}


				if(UGROUP == 1) $show = true;

				if($show) {
					$items = array();

					// Dokument - Infos
					$sql_doc = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_documents WHERE Id = '" . (int)$_REQUEST['Id'] . "'");
					$row_doc = $sql_doc->fetchrow();

					//=======================================================
					// Kann Redakteur Dokument - Status дndern?
					// Der Status der Dokumente 1 und 2 kцnnen nie geдndert werden!
					//=======================================================
					if ( @$_SESSION[$row_redi->RubrikId . '_newnow'] != 1) {
						$row_doc->dontChangeStatus = 1;
					}

					$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_rubric_fields WHERE RubrikId='" .(int)$_REQUEST['RubrikId']. "' ORDER BY rubric_position ASC");

					while($row = $sql->fetchrow()) {

						if ($row->RubTyp == 'dropdown') {
							$sql_df = $GLOBALS['db']->Query("SELECT a.Id, a.StdWert, b.Id as did, b.Inhalt
		                                               FROM " . PREFIX . "_rubric_fields as a LEFT JOIN
		                                                    " . PREFIX . "_document_fields as b ON (b.RubrikFeld = a.Id  AND b.DokumentId='" .(int)$_REQUEST['Id']. "')
		                                               WHERE a.RubrikId='" .(int)$_REQUEST['RubrikId']. "' AND a.RubTyp='dropdown' AND a.Id = '".$row->Id."'");
							$res = $sql_df->fetchrow();
							$row_df->Inhalt = $res->StdWert;
							$row_df->Id = $res->did;
							$drop = $res->Inhalt;
						} else {
							$sql_df = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_document_fields WHERE DokumentId='" .(int)$_REQUEST['Id']. "' AND RubrikFeld='$row->Id'");
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

					$rc="<select name=\"Rubrika\" size=\"1\">\n";
					$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_news_rubz WHERE parent=0");
					while($row = $sql->fetcharray()) {
						$sql2 = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_news_rubz WHERE parent=".$row['id']);
						while($row2 = $sql2->fetcharray()) {
							if($row_doc->rubid==$row2['id']) {
								$rc.="<option value=\"".$row2['id']."\" selected>".$row['name']."/".$row2['name']."</option>\n";
							} else {
								$rc.="<option value=\"".$row2['id']."\">".$row['name']."/".$row2['name']."</option>\n";
							}
						}
					}
					$rc.="</select>\n";
					if(!isset($_COOKIE['maxW']))
						$_COOKIE['maxW']="";
					if(!isset($_COOKIE['maxH']))
						$_COOKIE['maxH']="";
					$GLOBALS['tmpl']->assign("maxW", $_COOKIE['maxW']);
					$GLOBALS['tmpl']->assign("maxH", $_COOKIE['maxH']);
					$GLOBALS['tmpl']->assign("rubrikz", $rc);
					$GLOBALS['tmpl']->assign("pretext", stripslashes($row_doc->pretext));
					$GLOBALS['tmpl']->assign("items", $items);
					$GLOBALS['tmpl']->assign("row_doc", $row_doc);
					$GLOBALS['tmpl']->assign("formaction", "index.php?sub=save&do=modules&action=modedit&mod=news&moduleaction=editnews&RubrikId=".(int)$_REQUEST['RubrikId']. "&Id=".(int)$_REQUEST['Id']. "&cp=".SESSION. "");
					$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("documents/form2.tpl"));
				} else {
					$GLOBALS['tmpl']->assign("content", $GLOBALS['config_vars']['DOC_NO_PERMISSION']);
				}

			break;
		}
	}

  function ResizeImg2($strSourceImagePath, $strDestImagePath, $intMaxWidth=150, $intMaxHeight=150) {
    $arrImageProps = getimagesize($strSourceImagePath);
    $intImgWidth   = $arrImageProps[0];
    $intImgHeight  = $arrImageProps[1];
    $intImgType    = $arrImageProps[2];
    if(!((int)$intMaxWidth > 0))
    	$intMaxWidth = 150;
    if(!((int)$intMaxHeight > 0))
    	$intMaxHeight = 150;

    switch( $intImgType) {
        case 1: $rscImg = ImageCreateFromGif($strSourceImagePath);  break;
        case 2: $rscImg = ImageCreateFromJpeg($strSourceImagePath); break;
        case 3: $rscImg = ImageCreateFromPng($strSourceImagePath);  break;
        default: return false;
    }

    if ( !$rscImg) return false;

//    if ($intImgWidth > $intImgHeight) {
//        $fltRatio = floatval($intMaxWidth / $intImgWidth);
//    } else {
//        $fltRatio = floatval($intMaxHeight / $intImgHeight);
//    }
    $WidthRatio  = floatval($intMaxWidth / $intImgWidth);
    $HeightRatio = floatval($intMaxHeight / $intImgHeight);
    $fltRatio    = min($WidthRatio,$HeightRatio);
		if ($fltRatio > 1) return true;

    $intNewWidth  = intval($fltRatio * $intImgWidth);
    $intNewHeight = intval($fltRatio * $intImgHeight);

    $rscNewImg = imagecreatetruecolor($intNewWidth, $intNewHeight);
    if (!imagecopyresampled($rscNewImg, $rscImg, 0, 0,0, 0, $intNewWidth, $intNewHeight, $intImgWidth, $intImgHeight)) return false;

    switch($intImgType) {
        case 1:  $retVal = ImageGIF($rscNewImg, $strDestImagePath);  break;
        case 3:  $retVal = ImagePNG($rscNewImg, $strDestImagePath);  break;
        case 2:  $retVal = ImageJPEG($rscNewImg, $strDestImagePath,100); break;
        default: return false;
    }

    ImageDestroy($rscNewImg);

    return true;
}

	function newDoc($rubid) {
		$_REQUEST['Id']=2;
		$rubid = (isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'savenavi') ? $_REQUEST['RubrikId'] : $rubid;
		$this->fetchDocPerms($rubid);

		// Redakteur hat hier keine Rechte!
		if( (@$_SESSION[$rubid . '_newnow'] != 1 && @$_SESSION[$rubid . '_new'] != 1 && @$_SESSION[$rubid . '_alles'] != 1) ) {
			$GLOBALS['tmpl']->assign("content", $GLOBALS['config_vars']['DOC_NO_PERMISSION_RUB']);
		} else {
			switch($_REQUEST['sub']) {

				//=======================================================
				//  Speichern
				//=======================================================
				case 'save':

					$innavi = true;
					$ende = $this->dokEnde();
					$start = $this->dokStart();

					//=======================================================
					//  Status
					//=======================================================
					$ds = (isset($_REQUEST['DokStatus']) && $_REQUEST['DokStatus'] != "") ? (int)$_REQUEST['DokStatus'] : "";

					//=======================================================
					// Mail an Admin und Author
					//=======================================================
					if(empty($ds)) {

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
						$SystemMail = $GLOBALS['globals']->cp_settings("Mail_Absender");
						$SystemMailName = $GLOBALS['globals']->cp_settings("Mail_Name");

						// Mail an Admin
						$body_toadmin = $GLOBALS['config_vars']['DOC_MAIL_BODY_CHECK'];
						$body_toadmin = str_replace("%N%", "\n", $body_toadmin);
						$body_toadmin = str_replace("%TITLE%", stripslashes($_POST['Titel']), $body_toadmin);
						$body_toadmin = str_replace("%USER%", "'" .$_SESSION['cp_uname']. "'", $body_toadmin);
						$GLOBALS['globals']->cp_mail($SystemMail, $body_toadmin . $text, $GLOBALS['config_vars']['DOC_MAIL_SUBJECT_CHECK'], $SystemMail, $SystemMailName, "text", "");

						// Mail an Author
						$body_toauthor = str_replace("%N%", "\n", $GLOBALS['config_vars']['DOC_MAIL_BODY_USER']);
						$body_toauthor = str_replace("%TITLE%", stripslashes($_POST['Titel']), $body_toauthor);
						$body_toauthor = str_replace("%USER%", "'" .$_SESSION['cp_uname']. "'", $body_toauthor);
						$GLOBALS['globals']->cp_mail($_SESSION["cp_email"], $body_toauthor, $GLOBALS['config_vars']['DOC_MAIL_SUBJECT_USER'], $SystemMail, $SystemMailName, "text", "");
					}


					// Kann das Dokument sofort verцffentlich werden?
					$this->fetchDocPerms($_REQUEST['Id']);

					if ( (@$_SESSION[$_REQUEST['Id'] . '_newnow'] != 1) && (@$_SESSION[$_REQUEST['Id'] . '_alles'] != 1) && (UGROUP!=1) ) {
						$ds = 0;
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
						news,
						rubid,
						preimage,
						pretext
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
						1,
						'" .$_POST['Rubrika']. "',
						'',
						'" .mysql_escape_string($_POST['preText']). "'
						)";

					$sql = $GLOBALS['db']->Query($q);
					$iid = $GLOBALS['db']->InsertId();

					if($_FILES['preImage']['size']!=0) {
						$path_parts = pathinfo($_FILES['preImage']['name']);
						$uploadTo = BASE_DIR . '/modules/news/preimages/'.$iid.'.'.$path_parts['extension'];
						if(move_uploaded_file($_FILES['preImage']['tmp_name'],$uploadTo)) {
							chmod($uploadTo, 0644);
							if($this->ResizeImg2($uploadTo, $uploadTo, $_POST['maxW'], $_POST['maxH'])) {
								$q = "UPDATE " . PREFIX . "_documents
									SET
									preimage = '" . $iid.'.'.$path_parts['extension'] . "'
									WHERE Id = '".$iid."' LIMIT 1";
								$sql = $GLOBALS['db']->Query($q);
								setcookie("maxW",$_POST['maxW'],time()+31536000,'/');
								setcookie("maxH",$_POST['maxH'],time()+31536000,'/');
							}
						}
					}

					foreach ($_POST['feld'] as $id => $f) {
						//=======================================================
						//  Prьfen, welcher Feld - Typ aktualisiert werden soll
						//=======================================================
						$Eintrag = true;
						$sql_tc = $GLOBALS['db']->Query("SELECT RubrikFeld FROM " . PREFIX . "_document_fields WHERE RubrikFeld = '$id'");
						$row_tc = $sql_tc->fetchrow();
						$sql_tc->Close();

						@$sql_rf = $GLOBALS['db']->Query("SELECT RubTyp FROM " . PREFIX . "_rubric_fields WHERE Id = '$row_tc->RubrikFeld'");
						$row_rf = $sql_rf->fetchrow();
						$sql_rf->Close();

						//=======================================================
						// Wenn PHP - Code eingefьgt werden kann, muss geprьft werden,
						// ob der Redakteur dies auch darf
						//=======================================================
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

						$q = "INSERT INTO " . PREFIX . "_document_fields (Id, RubrikFeld, DokumentId, Inhalt,Suche) VALUES ('','" .$id. "','$iid','','$suche')";
						$sql = $GLOBALS['db']->Query($q);
						$iid_df = $GLOBALS['db']->InsertId();
						$q = "UPDATE " . PREFIX . "_document_fields
							SET
							Inhalt = '" . (!$Eintrag ? "" : $this->prettyCode($_POST['feld'][$id])) . "'
							WHERE Id = '$iid_df'";
							$sql = $GLOBALS['db']->Query($q);

						reportLog($_SESSION["cp_uname"] . " - добавил документ (".$_POST['Titel'].")",'2','2');
					}

					$GLOBALS['tmpl']->assign("name_empty", $GLOBALS['config_vars']['DOC_TOP_MENU_ITEM']);
					$GLOBALS['tmpl']->assign("Id", $iid);
					$GLOBALS['tmpl']->assign("newsRub", $_POST['Rubrika']);
					$GLOBALS['tmpl']->assign("innavi", (($innavi!=false) ? 1 : 0));
					$GLOBALS['tmpl']->assign("RubrikId", $_REQUEST['Id']);
					$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("documents/form_after2.tpl"));
				break;

				//=======================================================
				// Dokument in der Navigation ablegen
				//=======================================================
				case 'savenavi':

					$elter_pre = ($_REQUEST['Elter']=='0') ? 0 : explode("____", $_REQUEST['Elter']);
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
					//=======================================================
					// Kann Redakteur Dokument - Status дndern?
					// Der Status der Dokumente 1 und 2 kцnnen nie geдndert werden!
					//=======================================================
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
					$sql = $GLOBALS['db']->Query("SELECT UrlPrefix FROM " . PREFIX . "_rubrics WHERE Id='" .(int)$_REQUEST['Id']. "'");
					$row = $sql->fetchrow();
					$autoUrl = ($row->UrlPrefix != '/news/' ) ? $row->UrlPrefix : ($row->UrlPrefix).Date('Y_m_d').'/';

					$sql = $GLOBALS['db']->Query("SELECT count(Url) AS numUrl FROM " . PREFIX . "_documents WHERE Url LIKE '%".$autoUrl."%'");
					$row = $sql->fetchrow();
					if ($row->numUrl > 0)
					{
						$autoUrl = $autoUrl.($row->numUrl+1).'/';
					}
					$GLOBALS['tmpl']->assign('autoUrl', $autoUrl);

					$rc="<select name=\"Rubrika\" size=\"1\">\n";
					$sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_news_rubz WHERE parent=0");
					while($row = $sql->fetcharray()) {
						$sql2 = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_news_rubz WHERE parent=".$row['id']);
						while($row2 = $sql2->fetcharray()) {
							if($_REQUEST['id']==$row2['id'])
								$rc.="<option value=\"".$row2['id']."\" selected>".$row['name']."/".$row2['name']."</option>\n";
							else
								$rc.="<option value=\"".$row2['id']."\">".$row['name']."/".$row2['name']."</option>\n";
						}
					}
					$rc.="</select>\n";
					if(!isset($_COOKIE['maxW']))
						$_COOKIE['maxW']="";
					if(!isset($_COOKIE['maxH']))
						$_COOKIE['maxH']="";
					$GLOBALS['tmpl']->assign("maxW", $_COOKIE['maxW']);
					$GLOBALS['tmpl']->assign("maxH", $_COOKIE['maxH']);
					$GLOBALS['tmpl']->assign("rubrikz", $rc);
					$GLOBALS['tmpl']->assign("row_doc", $row_doc);
					$GLOBALS['tmpl']->assign("items", $items);
					$GLOBALS['tmpl']->assign("hidden", $hidden);
					$GLOBALS['tmpl']->assign("formaction", "index.php?do=modules&action=modedit&mod=news&moduleaction=addnews&id=".$_REQUEST['id']."&cp=".SESSION. "&amp;sub=save");
					$GLOBALS['tmpl']->assign("content", $GLOBALS['tmpl']->fetch("documents/form2.tpl"));
				break;
			}
		}
	}

	//=======================================================
	//  Dokument lцschen Achtung!
	//  Dokument kann von Administratoren wiederhergestellt werden
	//=======================================================
	function delDoc($id) {
		$sql = $GLOBALS['db']->Query("SELECT Id,RubrikId,Redakteur FROM " . PREFIX . "_documents WHERE Id = '$id'");
		$row = $sql->fetchrow();

		if( ($row->Redakteur == @$_SESSION['cp_benutzerid']) &&
			(isset($_SESSION[$row->RubrikId . '_editown']) &&
			@$_SESSION[$row->RubrikId . '_editown'] == 1) ||
			@$_SESSION[$row->RubrikId . '_alles'] == 1 || UGROUP == 1
		)
		{
			$GLOBALS['db']->Query("UPDATE " . PREFIX . "_documents SET Geloescht = 1 WHERE Id = '$id'");
			reportLog($_SESSION["cp_uname"] . " - временно удалил документ (".$id. ")",'2','2');
		}
		header("Location:index.php?do=docs&cp=".SESSION. "");

	}

	//=======================================================
	//  Dokument wiederherstellen
	//=======================================================
	function redelDoc($id) {
		$GLOBALS['db']->Query("UPDATE " . PREFIX . "_documents SET Geloescht = 0 WHERE Id = '$id'");
		reportLog($_SESSION["cp_uname"] . " - восстановил удаленный документ (".$id. ")",'2','2');
		header("Location:index.php?do=docs&cp=".SESSION. "");
	}

	//=======================================================
	//  Dokument endgьltig lцschen
	//=======================================================
	function enddelDoc($id) {
		$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_documents WHERE Id = '$id'");
		$GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_document_fields WHERE DokumentId = '$id'");
		reportLog($_SESSION["cp_uname"] . " - окончательно удалил документ (".$id. ")",'2','2');
		header("Location:index.php?do=docs&cp=".SESSION. "");
	}

	//=======================================================
	//  Цffnen & Schliessen
	//=======================================================
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
			$GLOBALS['db']->Query("UPDATE " . PREFIX . "_documents SET DokStatus = $openclose WHERE Id = '$id'");
			reportLog($_SESSION["cp_uname"] . " - " . (($openclose=='open') ? 'активировал' : 'деактивировал') . " документ (".$id. ")",'2','2');
		}
		header("Location:index.php?do=docs&cp=".SESSION. "");

	}

	//=======================================================
	//  Zeit aus Start erzeugen
	//=======================================================
	function selectStart() {
		$start = mktime(0, 0, 1, $_REQUEST['DokStartMonth'], $_REQUEST['DokStartDay'], $_REQUEST['DokStartYear']);
		return $start;
	}

	//=======================================================
	//  Zeit aus Ende erzeugen
	//=======================================================
	function selectEnde() {
		$ende = mktime(23, 59, 59, $_REQUEST['DokEndeMonth'], $_REQUEST['DokEndeDay'], $_REQUEST['DokEndeYear']);
		return $ende;
	}

	//=======================================================
	//  Die ьbergebene Zeit an das Template zurьckgeben
	//=======================================================
	function tplTimeAssign() {
		if(isset($_REQUEST['TimeSelect']) && $_REQUEST['TimeSelect'] != '') {
			$GLOBALS['tmpl']->assign("sel_start", $this->selectStart());
			$GLOBALS['tmpl']->assign("sel_ende", $this->selectEnde());
		}
	}

	//=======================================================
	//  Dokumente anzeigen
	//=======================================================
	function showDocs($simple='') {
		global $cpRub;

		$ex_Titel = "";
		$nav_Titel = "";
		$ex_Zeit = "";
		$nav_Zeit = "";
		$ex_Geloescht = "";
		$first = "";
		$ex_Titel_not = "";
		$request = "";
		$ex_rub = "";
		$nav_rub = "";
		$ex_docstatus = "";
		$navi_docstatus = "";
		$only_id = "";

		// Suche
		if(isset($_REQUEST['QueryTitel']) && $_REQUEST['QueryTitel'] != '') {
			$request = $_REQUEST['QueryTitel'];
			$Kette = explode(" ", $request);

			// Array zerlegen
			foreach($Kette as $Suche) {
				// +
				$Und = @explode(" +", $Suche);
				foreach($Und as $UndWort) {
					if(strpos($UndWort, "+")!==false)
						$ex_Titel .= " AND (Titel like '%" . substr($UndWort,1) . "%')";
				}

				// -
				$UndNicht = @explode(" -", $Suche);
				foreach($UndNicht as $UndNichtWort) {
					if(strpos($UndNichtWort, "-")!==false)
						$ex_Titel .= " AND (Titel not like '%" . substr($UndNichtWort,1) . "%')";
				}

				// 1. Suchwort
				$Start = explode(" +", $request);
				if(strpos($Start[0], " -")!==false)
					$Start = explode(" -", $request);

				$Start = $Start[0];
			}

			// Suche Anfang
			$ex_Titel = " AND (Titel like '%" . $Start . "%') $ex_Titel ";
			$nav_Titel = "&QueryTitel=" . urlencode($request);
		}

		// Rubrik
		if(isset($_REQUEST['RubrikId']) && $_REQUEST['RubrikId'] != 'all') {
			$ex_rub = " AND RubrikId = '" . $_REQUEST['RubrikId'] . "'";
			$nav_rub = "&RubrikId=" . $_REQUEST['RubrikId'];
		} else {
			$nav_rub = "";
		}

		// Zeitraum
		if(isset($_REQUEST['TimeSelect']) && $_REQUEST['TimeSelect'] != '') {
			$ex_Zeit   = " AND (DokStart BETWEEN " . $this->selectStart() . " AND " . $this->selectEnde() . ")";
			$nav_Zeit  = "&DokStartMonth=$_REQUEST[DokStartMonth]&DokStartDay=$_REQUEST[DokStartDay]&DokStartYear=$_REQUEST[DokStartYear]";
			$nav_Zeit .= "&DokEndeMonth=$_REQUEST[DokEndeMonth]&DokEndeDay=$_REQUEST[DokEndeDay]&DokEndeYear=$_REQUEST[DokEndeYear]&TimeSelect=1";
		}


		if(isset($_REQUEST['DokStatus']) && $_REQUEST['DokStatus'] != '') {
			switch($_REQUEST['DokStatus']) {
				case '':
				case 'All':
					$ex_docstatus = "";
					$navi_docstatus = "";
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
			}
		}

		$ex_Geloescht = (UGROUP != 1) ? " AND Geloescht != 1 " : "" ;
		$w_id = (isset($_REQUEST['doc_id']) && $_REQUEST['doc_id'] != '') ? " AND Id = '" . $_REQUEST['doc_id'] . "'" : "";

		$q = "SELECT Id FROM " . PREFIX . "_documents
			WHERE rubid = ".$_REQUEST['id']."
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

		// Anzahl Datensдtze
		if(isset($_REQUEST['Datalimit']) && $_REQUEST['Datalimit'] != '') {
			$limit = (int)$_REQUEST['Datalimit'];
			$nav_limit = "&Datalimit=" . $limit;
		} else {
			$limit = $this->_limit;
			$nav_limit = "&Datalimit=" . $limit;
		}

		$seiten = ceil($num / $limit);
		$start = prepage() * $limit - $limit;

		// Start-Sortierung
		$db_sort = " ORDER BY DokStart DESC ";
		$navi_sort = "&sort=ErstelltDesc";

		// Sortierung
		if(isset($_REQUEST['sort']) && $_REQUEST['sort'] != '') {
			switch($_REQUEST['sort']) {
				case 'Id' :
					$db_sort = " ORDER BY Id ASC ";
					$navi_sort = "&sort=Id";
				break;

				case 'IdDesc' :
					$db_sort = " ORDER BY Id DESC ";
					$navi_sort = "&sort=IdDesc";
				break;

				case 'Titel' :
					$db_sort = " ORDER BY Titel ASC ";
					$navi_sort = "&sort=Titel";
				break;

				case 'TitelDesc' :
					$db_sort = " ORDER BY Titel DESC ";
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
					$db_sort = " ORDER BY RubrikId ASC ";
					$navi_sort = "&sort=Rubrik";
				break;

				case 'RubrikDesc' :
					$db_sort = " ORDER BY RubrikId DESC ";
					$navi_sort = "&sort=RubrikDesc";
				break;

				case 'Erstellt' :
					$db_sort = " ORDER BY DokStart ASC ";
					$navi_sort = "&sort=Erstellt";
				break;

				case 'ErstelltDesc' :
					$db_sort = " ORDER BY DokStart Desc ";
					$navi_sort = "&sort=ErstelltDesc";
				break;

				case 'Klicks' :
					$db_sort = " ORDER BY Geklickt ASC ";
					$navi_sort = "&sort=Klicks";
				break;

				case 'KlicksDesc' :
					$db_sort = " ORDER BY Geklickt DESC ";
					$navi_sort = "&sort=KlicksDesc";
				break;

				case 'Druck' :
					$db_sort = " ORDER BY Drucke ASC ";
					$navi_sort = "&sort=Druck";
				break;

				case 'DruckDesc' :
					$db_sort = " ORDER BY Drucke DESC ";
					$navi_sort = "&sort=DruckDesc";
				break;

				case 'Autor' :
					$db_sort = " ORDER BY Redakteur ASC ";
					$navi_sort = "&sort=Autor";
				break;

				case 'AutorDesc' :
					$db_sort = " ORDER BY Redakteur DESC ";
					$navi_sort = "&sort=AutorDesc";
				break;

				default :
					$db_sort = " ORDER BY DokStart ASC ";
					$navi_sort = "&sort=Erstellt";
				break;
			}
		}

		$docs = array();
		$q = "SELECT * FROM " . PREFIX . "_documents
			WHERE rubid = ".$_REQUEST['id']."
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

			// KOMMANTARE
			$sql_numkommentare = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_document_comments WHERE DokumentId='" .$row->Id. "'");
			$row->Kommentare = $sql_numkommentare->numrows();

			$this->fetchDocPerms($row->RubrikId);

			$row->RubName = $this->showRubName($row->RubrikId);
			$row->RBenutzer = getUserById($row->Redakteur);
			$row->cantEdit = 0;
			$row->canDelete = 0;
			$row->canOpenClose = 0;


			// BEARBEITEN & LЦSCHEN?
			if( ($row->Redakteur == @$_SESSION['cp_benutzerid']) &&
				(isset($_SESSION[$row->RubrikId . '_editown']) &&
				@$_SESSION[$row->RubrikId . '_editown'] == 1) ||
				@$_SESSION[$row->RubrikId . '_alles'] == 1
			)
			{
				if($row->Id != 1 && $row->Id != 2) {
					$row->canDelete = 1;
					$row->cantEdit = 1;
				}
			}

			// Wenn Benutzer alle Dokumente bearbeiten darf
			if( (isset($_SESSION[$row->RubrikId . '_editall']) &&
				$_SESSION[$row->RubrikId . '_editall'] == 1) ||
				@$_SESSION[$row->RubrikId . '_alles'] == 1
			)
			{
				if($row->Id != 1 && $row->Id != 2) {
					$row->canDelete = 1;
					$row->cantEdit = 1;
				}
			}

			// ЦFFNEN & SCHLIESSEN
			if( ($row->Redakteur == @$_SESSION['cp_benutzerid']) &&
				(isset($_SESSION[$row->RubrikId . '_newnow']) &&
				@$_SESSION[$row->RubrikId . '_newnow'] == 1) ||
				@$_SESSION[$row->RubrikId . '_alles'] == 1 ||
				UGROUP == 1
			)
			{
				$row->canOpenClose = 1;
			}

			// Wenn Benutzer Admin ist, darf er alles
			if(UGROUP==1) {
				$row->cantEdit = 1;
				$row->canEndDel = 1;
			}

			array_push($docs, $row);
		}

		$GLOBALS['tmpl']->assign("docs", $docs);

		// Navigation
		$nav_RubId = (isset($_REQUEST['RubrikId']) && $_REQUEST['RubrikId'] != '') ? "&amp;RubrikId=".(int)$_REQUEST['RubrikId'] : "";

		$nav_target = (isset($_REQUEST['target']) && $_REQUEST['target'] != '') ? "&target=$_REQUEST[target]" : "";
		$nav_doc = (isset($_REQUEST['doc']) && $_REQUEST['doc'] != '') ? "&doc=$_REQUEST[doc]" : "";

		$pop = (isset($_REQUEST['pop']) && $_REQUEST['pop'] == 1) ? "&amp;pop=1" : "";
    $showsimple = (isset($_REQUEST['action']) && $_REQUEST['action'] == "showsimple") ? "&amp;action=showsimple" : "";
	/*------Для редактора------*/
	$showsimple_edit = (isset($_REQUEST['action']) && $_REQUEST['action'] == "showsimple_edit") ? "&amp;action=showsimple_edit" : "";
    $idonly = (isset($_REQUEST['idonly']) && $_REQUEST['idonly'] == 1) ? "&amp;idonly=1" : "";

    $page_nav = pagenav($seiten, prepage(),
	" <a class=\"pnav\" href=\"index.php?do=modules&action=modedit&mod=news&moduleaction=editsubrub&id=".$_REQUEST['id']."$nav_target$nav_doc$navi_sort$navi_docstatus$nav_Titel&page={s}&amp;cp=".SESSION. "$nav_rub$nav_Zeit$nav_rub$nav_limit$pop$showsimple$showsimple_edit$idonly\">{t}</a> ");
		if($num > $limit) $GLOBALS['tmpl']->assign("page_nav", $page_nav);
	}

	//=======================================================
	//  Rubrikname
	//=======================================================
	function showRubName($id) {
		$sql = $GLOBALS['db']->Query("SELECT RubrikName FROM " . PREFIX . "_rubrics WHERE Id='$id'");
		$row = $sql->fetchrow();
		return $row->RubrikName;
	}

	//=======================================================
	//  Rubriken anzeigen
	//=======================================================
	function showRubs() {

		$sql = $GLOBALS['db']->Query("SELECT Id,RubrikName FROM " . PREFIX . "_rubrics");
		$rubs = array();
		while($row = $sql->fetchrow()) {
			array_push($rubs, $row);
		}
		return $rubs;
	}

	function newComment($id,$reply="") {

		if(isset($_REQUEST["sub"]) && $_REQUEST['sub'] == "save") {
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
			// echo "<script>window.opener.location.reload(); window.close();< /script>";
			header("Location:index.php?do=docs&action=comment_reply&Id=$_REQUEST[Id]&pop=1&cp=". SESSION);
		}


		if($reply == 1) {
			if(isset($_REQUEST["sub"]) && $_REQUEST['sub'] == "save") {
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
				$SystemMail = $GLOBALS['globals']->cp_settings("Mail_Absender");
				$SystemMailName = $GLOBALS['globals']->cp_settings("Mail_Name");

				// Mail an Autor
				$host = explode("?", redir());
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

	//=======================================================
	//  Felder parsen
	//=======================================================
	function getField($RubTyp,$inhalt,$Id, $new, $docid = "", $redakteur = "") {
		$docstart = "";
		$docredi = "";

		// Dokument-Start
		if($docid != "") {
			$sql = $GLOBALS['db']->Query("SELECT DokStart FROM " . PREFIX . "_documents WHERE Id = '$docid'");
			$row = $sql->fetchrow();
			$sql->Close();
			$docstart = $row->DokStart;

			$sql = $GLOBALS['db']->Query("SELECT Vorname,Nachname FROM " . PREFIX . "_users WHERE Id = '$redakteur'");
			$row = $sql->fetchrow();
			$sql->Close();
			$docredi = $row->Vorname . " " . $row->Nachname;
		}

		$img_pixel = "templates/" . $_SESSION["cp_admin_theme"] . "/images/blanc.gif";
		$inhalt = str_replace("[cp:replacement]", REPLACEMENT,$inhalt);

		$feld = "";
		$ft = ($new == 1) ? "feld" : "feld";
		switch($RubTyp) {
			case 'kurztext' :
				$feld = "<a name=\"$Id\"></a><input id=\"feld_$Id\" type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".$inhalt. "\"> ";
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
					$oFCKeditor->Value	= $inhalt;
					$feld = $oFCKeditor->Create($Id);
					$GLOBALS['tmpl']->assign("extended_insert", 1);
          $feld .= "<input class=\"button\" onclick=\"insertHTML('$Id', '<h3>[cp:newpage]</h3>');\" value=\"".$GLOBALS['config_vars']['MAIN_NEW_PAGE']. "\" type=\"button\">";
				}
			break;

	/* Добавлено */

      case 'smalltext' :
        if(isset($_COOKIE['no_wysiwyg']) && $_COOKIE['no_wysiwyg'] == 1)
        {
          $feld = "<a name=\"$Id\"></a><textarea style=\"width:".$this->_textarea_width_small. "; height:".$this->_textarea_height_small. "\"  name=\"".$ft. "[$Id]\">".$inhalt. "</textarea>";
        }
        else
        {
          $oFCKeditor->BasePath = SOURCE_DIR . "editor/" ;
          $oFCKeditor = new FCKeditor($ft. "[$Id]") ;
          $oFCKeditor->Height = $this->_textarea_height_small;
          $oFCKeditor->Value  = $inhalt;
		  $oFCKeditor->ToolbarSet	= 'cpengine_small';
          $feld = $oFCKeditor->Create($Id);
          $GLOBALS['tmpl']->assign("extended_insert", 1);
        }
      break;

			case 'created' :
				$globals = new Globals;
				$inhalt = (isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] == 'addnews') ? date($GLOBALS['globals']->cp_settings("Zeit_Format")) : (($inhalt == "") ? date($GLOBALS['globals']->cp_settings("Zeit_Format"),$docstart) : $inhalt);
				$inhalt = strtr($inhalt, array('January'=>'января','February'=>'февраля','March'=>'марта','April'=>'апреля','May'=>'мая','June'=>'июня','July'=>'июля','August'=>'августа','September'=>'сентября','October'=>'октября','November'=>'ноября','December'=>'декабря','Sunday'=>'Воскресенье','Monday'=>'Понедельник','Tuesday'=>'Вторник','Wednesday'=>'Среда','Thursday'=>'Четверг','Friday'=>'Пятница','Saturday'=>'Суббота',));
				if (isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] == 'addnews') {
					$feld = "<a name=\"$Id\"></a><input id=\"feld_$Id\" type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".$inhalt. "\"> ";
				} else {
					$feld = "<a name=\"$Id\"></a><input id=\"feld_$Id\" type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".$inhalt. "\"> <input type=\"button\" value=\"Текущая дата\" class=\"button\" onclick=\"insert_now_date('feld_$Id');\" />";
				}
			break;

			case 'author':
				$inhalt = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'new') ? $_SESSION["cp_uname"] : $inhalt;
				$feld = "<a name=\"$Id\"></a><input id=\"feld_$Id\" type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".$inhalt. "\"> ";
			break;

			case 'bild' :
				$feld = "<a name=\"$Id\"></a><div id=\"feld_$Id\"><img id=\"_img_".$ft. "__$Id\" src=\"". (($inhalt != '') ? $inhalt : "$img_pixel") . "\" alt=\"\" border=\"0\" /></div><div style=\"display:none\" id=\"span_".$ft. "__$Id\">&nbsp;</div>".(($inhalt != '') ? "<br />" : ""). "<input type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".htmlspecialchars($inhalt). "\" id=\"img_".$ft. "__$Id\" /> <input value=\"".$GLOBALS['config_vars']['MAIN_OPEN_MEDIAPATH']. "\" class=\"button\" type=\"button\" onclick=\"cp_imagepop('img_".$ft. "__$Id','','','0');\" />";
			break;

			case 'bild_links' :
				$feld = "<a name=\"$Id\"></a><div id=\"feld_$Id\"><img id=\"_img_".$ft. "__$Id\" src=\"". (($inhalt != '') ? $inhalt : "$img_pixel") . "\" alt=\"\" border=\"0\" /></div><div style=\"display:none\" id=\"span_".$ft. "__$Id\">&nbsp;</div>".(($inhalt != '') ? "<br />" : ""). "<input type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".htmlspecialchars($inhalt). "\" id=\"img_".$ft. "__$Id\" /> <input value=\"".$GLOBALS['config_vars']['MAIN_OPEN_MEDIAPATH']. "\" class=\"button\" type=\"button\" onclick=\"cp_imagepop('img_".$ft. "__$Id','','','0');\" />";
			break;

			case 'bild_rechts' :
				$feld = "<a name=\"$Id\"></a><div id=\"feld_$Id\"><img id=\"_img_".$ft. "__$Id\" src=\"". (($inhalt != '') ? $inhalt : "$img_pixel") . "\" alt=\"\" border=\"0\" /></div><div style=\"display:none\" id=\"span_".$ft. "__$Id\">&nbsp;</div>".(($inhalt != '') ? "<br />" : ""). "<input type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".htmlspecialchars($inhalt). "\" id=\"img_".$ft. "__$Id\" /> <input value=\"".$GLOBALS['config_vars']['MAIN_OPEN_MEDIAPATH']. "\" class=\"button\" type=\"button\" onclick=\"cp_imagepop('img_".$ft. "__$Id','','','0');\" />";
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
				$feld = "<a name=\"$Id\"></a><input id=\"feld_$Id\" type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".$inhalt. "\"> ";
			break;

			case 'link_ex' :
				$feld = "<a name=\"$Id\"></a><input id=\"feld_$Id\" type=\"text\" style=\"width:".$this->_field_width. "\" name=\"".$ft. "[$Id]\" value=\"".$inhalt. "\"> ";
			break;
		}
	return $feld;
	}
}
?>