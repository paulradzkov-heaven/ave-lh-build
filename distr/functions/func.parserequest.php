<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 1)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: February 22, 2008
::::::::::::::::::::::::::::::::::::::::*/

function cp_abkondition($id) {
  $eq_string = '';
  $uebergabe = false;
  $treffer   = false;

  $sql_ak = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_queries_conditions WHERE Abfrage = '$id'");

  $where = '';
  $start = 0;
  $From = PREFIX . "_document_fields AS alias0";
  $defid = def_id();
  $doc_queri = 'doc' . $defid . '_queri' . $id;
  if (isset($_REQUEST['fld']) && $_REQUEST['fld'] != '') {
    $_SESSION[$doc_queri]['fld'] = $_REQUEST['fld'];
  } else {
    if (isset($_SESSION[$doc_queri]['fld']) && $_SESSION[$doc_queri]['fld'] != '') {
      $_REQUEST['fld'] = $_SESSION[$doc_queri]['fld'];
    } else {
      $_REQUEST['fld'] = array();
    }
  }

  while($row_ak = $sql_ak->fetchrow()) {
    $where = 'WHERE';
    $uebergabe = true;
    $Operator = $row_ak->Operator;
    $Feld = $row_ak->Feld;
    if (isset($_REQUEST['fld'][$Feld]) && $_REQUEST['fld'][$Feld] != '') {
      $Wert = $_REQUEST['fld'][$Feld];
      unset($_REQUEST['fld'][$Feld]);
    } else {
      $Wert = $row_ak->Wert;
    }
    $Oper = $row_ak->Oper;
    $alias = ($Oper != 'OR') ? "alias" . $start : "alias0";
    $AddFrom = ($start != 0) ? ", " . PREFIX . "_document_fields AS " . $alias : "";

    $startBracket = ($start != 0) ? ($Oper != 'OR') ? " AND alias0.DokumentId = $alias.DokumentId AND (" : " OR (" : '(';
    $lastbBracket = ($start != 0) ? ') ' : ') ';

    switch ($Operator) {

      case '%%':
        $q_ab = "$startBracket $alias.RubrikFeld = '$Feld' AND $alias.Inhalt like '%$Wert%' $lastbBracket";
      break;

      case '%':
        $q_ab = "$startBracket $alias.RubrikFeld = '$Feld' AND $alias.Inhalt like '$Wert%' $lastbBracket";
      break;

      case '<':
        $q_ab = "$startBracket $alias.RubrikFeld = '$Feld' AND $alias.Inhalt < '$Wert' $lastbBracket";
      break;

      case '<=':
        $q_ab = "$startBracket $alias.RubrikFeld = '$Feld' AND $alias.Inhalt <= '$Wert' $lastbBracket";
      break;

      case '>':
        $q_ab = "$startBracket $alias.RubrikFeld = '$Feld' AND $alias.Inhalt > '$Wert' $lastbBracket";
      break;

      case '>=':
        $q_ab = "$startBracket $alias.RubrikFeld = '$Feld' AND $alias.Inhalt >= '$Wert' $lastbBracket";
      break;

      case '==':
        $q_ab = "$startBracket $alias.RubrikFeld = '$Feld' AND $alias.Inhalt = '$Wert' $lastbBracket";
      break;

      case '!=':
        $q_ab = "$startBracket $alias.RubrikFeld = '$Feld' AND $alias.Inhalt != '$Wert' $lastbBracket";
      break;

      case '--':
        $q_ab = "$startBracket $alias.RubrikFeld = '$Feld' AND $alias.Inhalt not like '%$Wert%' $lastbBracket";
      break;
    }
    $start++;
    $eq_string .= $q_ab;
    $From .= ($Oper != 'OR') ? $AddFrom : "";

  }

  arsort($_REQUEST['fld']);
  while ($Wert = current($_REQUEST['fld'])) {
    $where = 'WHERE';
    $uebergabe = true;
    $Feld = key($_REQUEST['fld']);
    $alias = "alias" . $start;
    $AddFrom = ($start != 0) ? ", " . PREFIX . "_document_fields AS " . $alias : "";

    $startBracket = ($start != 0) ? " AND alias0.DokumentId = $alias.DokumentId AND (" : '(';
    $q_ab = "$startBracket $alias.RubrikFeld = '$Feld' AND $alias.Inhalt = '$Wert' )";
    $start++;
    $eq_string .= $q_ab;
    $From .= $AddFrom;
    next($_REQUEST['fld']);
  }

  if ($where != '') {
    $uebergabe = true;
    $count_match = '';

    $sql_feld = $GLOBALS['db']->Query("SELECT alias0.DokumentId,alias0.Inhalt FROM $From $where $eq_string");
    $count_match = $sql_feld->numrows();

    $ueb = '';
    if($count_match > 0) {
      $ueb = "AND (";
      $i = 0;
      $doc_id = array();

      while($row_feld = $sql_feld->fetchrow()) {
        if (!in_array($row_feld->DokumentId, $doc_id)) {
          array_push($doc_id, $row_feld->DokumentId);
          $ueb .= (($i > 0) ? " OR " : "");
          $ueb .= (@$_SESSION['comments_enable'] == 1) ? 'a.Id='.$row_feld->DokumentId : 'Id='.$row_feld->DokumentId;
          $i++;
        }
      }
      $ueb .= ") ";
    } else {
      $ueb = (@$_SESSION['comments_enable'] == 1) ? "AND (a.Id = '0')" : "AND (Id = '0')";
    }
  }
  $ueb = ($uebergabe) ? $ueb : '';

  return $ueb;
}

function cp_parse_request($id) {
  $sql_ab = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_queries WHERE Id = '$id'");
  $row_ab = $sql_ab->fetchrow();
  $sql_ab->Close();

if(is_object($row_ab)) {
  $eq = '';
  $wo = '';
  $suchart = '';

  $first = '';
  $second = '';

  $limit      = ($row_ab->Zahl < 1) ? 1 : $row_ab->Zahl;
  $template    = $row_ab->Template;
  $geruest    = $row_ab->AbGeruest;
  $Sortierung = $row_ab->Sortierung;
  $AscDesc    = $row_ab->AscDesc;
  $ausgabe    = $template;
  $return      = '';
  $link        = '';

  $cp_abkondition = cp_abkondition($row_ab->Id);


  if ($row_ab->Navi==1) {
    if (@$_SESSION['comments_enable'] == 1) {
      $q = $GLOBALS['db']->Query("SELECT COUNT(*) AS num FROM
        " . PREFIX . "_documents AS a
        WHERE
            a.Id != '2' " . $cp_abkondition . "
        AND a.RubrikId = '$row_ab->RubrikId'
        AND a.Geloescht != 1
        AND a.DokStatus != 0
        AND (a.DokEnde = 0 || a.DokEnde > UNIX_TIMESTAMP())
        AND (a.DokStart = 0 || a.DokStart < UNIX_TIMESTAMP())
        ");
    } else {
      $q = $GLOBALS['db']->Query("SELECT COUNT(*) AS num FROM
        " . PREFIX . "_documents
        WHERE
            Id != '2' " . $cp_abkondition . "
        AND RubrikId = '$row_ab->RubrikId'
        AND Geloescht != 1
        AND DokStatus != 0
        AND (DokEnde = 0 || DokEnde > UNIX_TIMESTAMP())
        AND (DokStart = 0 || DokStart < UNIX_TIMESTAMP())
        ");
    }
    $row = $q->fetchrow();
    $num = $row->num;
    $q->Close();

    $seiten = ceil($num / $limit);
    $start  = prepage(1) * $limit - $limit;

  } else {
    $start  = 0;
  }

  if (@$_SESSION['comments_enable'] == 1) {
    $q = $GLOBALS['db']->Query("SELECT a.Id, a.Titel, Geklickt, COUNT(*) AS nums, Url FROM
      " . PREFIX . "_documents AS a LEFT JOIN
      " . PREFIX . "_modul_comment_info ON a.Id = DokId
      WHERE
          a.Id != '2' " . $cp_abkondition . "
      AND RubrikId = '$row_ab->RubrikId'
      AND Geloescht != 1
      AND DokStatus != 0
      AND (DokEnde = 0 || DokEnde > UNIX_TIMESTAMP())
      AND (DokStart = 0 || DokStart < UNIX_TIMESTAMP())
      GROUP BY a.Id ORDER BY $Sortierung $AscDesc
      LIMIT $start,$limit");
  } else {
    $q = $GLOBALS['db']->Query("SELECT Id, Titel, Geklickt, Url FROM
        " . PREFIX . "_documents
      WHERE
          Id != '2'" . $cp_abkondition . "
      AND RubrikId = '$row_ab->RubrikId'
      AND Geloescht != 1
      AND DokStatus != 0
      AND (DokEnde = 0 || DokEnde > UNIX_TIMESTAMP())
      AND (DokStart = 0 || DokStart < UNIX_TIMESTAMP())
      ORDER BY $Sortierung $AscDesc
      LIMIT $start,$limit");
  }

  $defid = def_id();

  $sql_doc = $GLOBALS['db']->Query("SELECT Url, Titel FROM " . PREFIX . "_documents WHERE Id = '" . $defid . "'");
  $row_doc = $sql_doc->fetchrow();
  $sql_doc->Close();
  $DocTitel = cp_parse_linkname(stripslashes($row_doc->Titel));

// Переписано для ЧПУ
//  $page_nav = ($row_ab->Navi==1) ? pagenav($seiten, prepage(1), " <a class=\"pnav\" href=\"".((CP_REWRITE==1) ? cp_rewrite("/index.php?id=" . $defid . "&amp;doc=" . $DocTitel . "&amp;apage={s}" ): "/index.php?id=" . $defid . "&amp;doc=" . $DocTitel . $navi_sort . "&amp;apage={s}"). "\">{t}</a> ") : "";
  if ($row_ab->Navi==1&&$seiten>=2) {
    if ($GLOBALS['config']['doc_rewrite']) {
      $page_nav = pagenav($seiten, prepage(1), " <a class=\"pnav\" href=\"" . $row_doc->Url . "apage{s}.html\">{t}</a> ");
    } else {
      $page_nav = pagenav($seiten, prepage(1), " <a class=\"pnav\" href=\"".((CP_REWRITE==1) ? cp_rewrite("/index.php?id=" . $defid . "&amp;doc=" . $DocTitel . "&amp;apage={s}" ) : "/index.php?id=" . $defid . "&amp;doc=" . $DocTitel . "&amp;apage={s}"). "\">{t}</a> ");
    }
  } else {
    $page_nav = "";
  }

  while($row = $q->fetchrow()) {
    // Переписано для ЧПУ
    if ($GLOBALS['config']['doc_rewrite']) {
      $link = $row->Url;
    } else {
      $link = (CP_REWRITE==1) ? cp_rewrite('/index.php?id=' . $row->Id . '&amp;doc=' . cp_parse_linkname($row->Titel) ) : '/index.php?id=' . $row->Id . '&amp;doc=' . cp_parse_linkname($row->Titel);
    }

    $return .= str_replace('[link]', $link, $template);
	//CPSMARTY
    $return = eregi_replace("\[cpsmarty:([\._a-zA-Z0-9\]*)\]", (function_exists("cp_parse_template") ? "<?php cp_parse_template(\"\\1\", ".$row_ab->RubrikId.", ".$row->Id."); ?>" : ''), $return);
	// end of CPSMARTY
    $return = preg_replace("[\[cpabrub:([0-9-]*)\]\[([0-9-]*)\]]", "<?php getField(\"\\1\", $row->Id, \"\\2\"); ?>", $return);
    /******************************************************************************************/
    $return = preg_replace("[\[cpabid\]]", "$row->Id", $return);
    /******************************************************************************************/
    $return = str_replace('[views]', $row->Geklickt, $return);
	
	$sql_comment = $GLOBALS['db']->Query("SELECT COUNT(*) AS num FROM " . PREFIX . "_modul_comment_info WHERE DokId='".$row->Id."'");
	$row_comment =$sql_comment ->fetchrow();
    $nums = $row_comment->num;
	
    $return = (@$_SESSION['comments_enable'] == 1) ? str_replace('[comments]', $nums, $return) : str_replace('[comments]', "", $return);
  }

  $return = str_replace('[link]', $link, $return);
  $geruest = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $geruest);
  $geruest = str_replace(array("\r\n","\n","\r"),"",$geruest);
  $geruest = preg_replace("/\<ul (.*?)\>\<\/ul\>/si", "", $geruest);
  if ($GLOBALS['config']['doc_rewrite']) {
    $formaction = $row_doc->Url;
  } else {
    $formaction = (CP_REWRITE==1) ? cp_rewrite("/index.php?id=" . $defid . "&amp;doc=" . $DocTitel) : "/index.php?id=" . $defid . "&amp;doc=" . $DocTitel;
  }
  $geruest = preg_replace("[\[cpctrlrub:([,0-9-]*)\]]", "<?php getDropdown(\"\\1\", $row_ab->RubrikId, $row_ab->Id, \"$formaction\"); ?>", $geruest);
  $return = str_replace("[content]", (($return=='') ? "" : $return ), $geruest);
  $return = str_replace('[pages]', $page_nav, $return);
  $return = str_replace("[cp:mediapath]", "/templates/" . T_PATH . "/", $return);
  $return = preg_replace("/\<ul\>\<\/ul\>/si", "", $return);

  if (isset($_SESSION['InstallModules']) && $_SESSION['InstallModules'] != '') {
    foreach ($_SESSION['InstallModules'] as $row_modul) {
      include_once(BASE_DIR . '/modules/' . $row_modul->ModulPfad . '/modul.php');
      $return = eregi_replace($row_modul->CpEngineTag, (($row_modul->IstFunktion==1) ? ((function_exists($row_modul->ModulFunktion)) ? $row_modul->CpPHPTag : ""." &quot;$row_modul->ModulName&quot;") : ''), $return);
    }
  } else {
    $sql_modul = $GLOBALS['db']->Query("SELECT ModulName,ModulPfad,Template,Status,CpEngineTag,CpPHPTag,ModulFunktion,IstFunktion FROM " . PREFIX. "_module WHERE Status = '1'");
    $InstallModules = array();
    while ($row_modul = $sql_modul->FetchRow()) {
      array_push($InstallModules, $row_modul);
      include_once(BASE_DIR . '/modules/' . $row_modul->ModulPfad . '/modul.php');
      $return = eregi_replace($row_modul->CpEngineTag, (($row_modul->IstFunktion==1) ? ((function_exists($row_modul->ModulFunktion)) ? $row_modul->CpPHPTag : ""." &quot;$row_modul->ModulName&quot;") : ''), $return);
    }
    $_SESSION['InstallModules'] = $InstallModules;
  }

  $return = stripslashes(hide($return));
  $ausgabe = $return;

  eval ("?>" . $ausgabe . "<?");
}
}

function getField($rid,$doc,$maxlength='') {
  if (isset($_SESSION['Doc_Arr'][$doc]) && $_SESSION['Doc_Arr'][$doc] != '') {
    $DocFields = $_SESSION['Doc_Arr'][$doc];
  } else {
    $sqlx = $GLOBALS['db']->Query("SELECT doc_field.Id,RubrikFeld,RubTyp,Inhalt,Redakteur
      FROM " . PREFIX . "_document_fields AS doc_field
      LEFT JOIN " . PREFIX . "_rubric_fields AS rub_field ON rub_field.Id = RubrikFeld
      LEFT JOIN " . PREFIX . "_documents AS doc ON doc.Id = DokumentId
      WHERE DokumentId = '" . $doc . "'
        AND Inhalt != ''
      ");
    while ($rowx = $sqlx->fetchrow()) {
      $DocFields[$rowx->RubrikFeld] = array(
        'Id' => $rowx->Id,
        'RubTyp' => $rowx->RubTyp,
        'Inhalt' => $rowx->Inhalt,
        'Redakteur' => $rowx->Redakteur
      );
    }
    $_SESSION['Doc_Arr'][$doc] = $DocFields;
  }
  $Inhalt = isset($DocFields[$rid]) ? $DocFields[$rid]['Inhalt'] : '';

  if($Inhalt != '') {
    $ausg = '';
    if(!defined("REQUEST_REPLACEMENT")) define("REQUEST_REPLACEMENT", substr($_SERVER['SCRIPT_NAME'],0,-9));
    $ausg = str_replace("[cp:replacement]", REQUEST_REPLACEMENT, $Inhalt);
    $ausg = strip_tags($ausg, "<br /><strong><em><p><i>");
    $ausg = str_replace("[cp:mediapath]", "/templates/" . T_PATH . "/", $ausg);

    $RubTyp = $DocFields[$rid]['RubTyp'];
    switch($RubTyp) {

      case 'bild_links' :
        $image_file = explode("||", @$ausg);
        if(!empty($ausg) && (file_exists(BASE_DIR . phpReplace($image_file[0])))) {
          if (file_exists(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/rfield-" . $rid . ".tpl")) {
            if(function_exists("getimagesize")) {
              $GLOBALS['tmpl']->assign("imgsize", getimagesize(BASE_DIR . phpReplace($image_file[0])));
            }
            $GLOBALS['tmpl']->assign("imgtype", $RubTyp);
            $GLOBALS['tmpl']->assign("imglink", phpReplace($image_file[0]));
            $GLOBALS['tmpl']->assign("imgtitle", @$image_file[1]);
            $ausg = $GLOBALS['tmpl']->fetch(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/rfield-" . $rid . ".tpl");
          } elseif (file_exists(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/rfield.tpl")) {
            if(function_exists("getimagesize")) {
              $GLOBALS['tmpl']->assign("imgsize", getimagesize(BASE_DIR . phpReplace($image_file[0])));
            }
            $GLOBALS['tmpl']->assign("imgtype", $RubTyp);
            $GLOBALS['tmpl']->assign("imglink", phpReplace($image_file[0]));
            $GLOBALS['tmpl']->assign("imgtitle", @$image_file[1]);
            $ausg = $GLOBALS['tmpl']->fetch(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/rfield.tpl");
          } else {
            $ausg = '<img style="margin-right:5px" align="left" src="'.phpReplace($image_file[0]).'" alt="'.@$image_file[1].'" border="0" />';
          }
        }
        $maxlength = '';
      break;

      case 'bild_rechts' :
        $image_file = explode("||", @$ausg);
        if(!empty($ausg) && (file_exists(BASE_DIR . phpReplace($image_file[0])))) {
          if (file_exists(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/rfield-" . $rid . ".tpl")) {
            if(function_exists("getimagesize")) {
              $GLOBALS['tmpl']->assign("imgsize", getimagesize(BASE_DIR . phpReplace($image_file[0])));
            }
            $GLOBALS['tmpl']->assign("imgtype", $RubTyp);
            $GLOBALS['tmpl']->assign("imglink", phpReplace($image_file[0]));
            $GLOBALS['tmpl']->assign("imgtitle", @$image_file[1]);
            $ausg = $GLOBALS['tmpl']->fetch(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/rfield-" . $rid . ".tpl");
          } elseif (file_exists(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/rfield.tpl")) {
            if(function_exists("getimagesize")) {
              $GLOBALS['tmpl']->assign("imgsize", getimagesize(BASE_DIR . phpReplace($image_file[0])));
            }
            $GLOBALS['tmpl']->assign("imgtype", $RubTyp);
            $GLOBALS['tmpl']->assign("imglink", phpReplace($image_file[0]));
            $GLOBALS['tmpl']->assign("imgtitle", @$image_file[1]);
            $ausg = $GLOBALS['tmpl']->fetch(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/rfield.tpl");
          } else {
            $ausg = '<img style="margin-left:5px" align="right" src="'.phpReplace($image_file[0]).'" alt="'.@$image_file[1].'" border="0" />';
          }
        }
        $maxlength = '';
      break;

      case 'bild' :
        $image_file = explode("||", @$ausg);
        if(!empty($ausg) && (file_exists(BASE_DIR . phpReplace($image_file[0])))) {
          if (file_exists(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/rfield-" . $rid . ".tpl")) {
            if(function_exists("getimagesize")) {
              $GLOBALS['tmpl']->assign("imgsize", getimagesize(BASE_DIR . phpReplace($image_file[0])));
            }
            $GLOBALS['tmpl']->assign("imgtype", $RubTyp);
            $GLOBALS['tmpl']->assign("imglink", phpReplace($image_file[0]));
            $GLOBALS['tmpl']->assign("imgtitle", @$image_file[1]);
            $ausg = $GLOBALS['tmpl']->fetch(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/rfield-" . $rid . ".tpl");
          } elseif (file_exists(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/rfield.tpl")) {
            if(function_exists("getimagesize")) {
              $GLOBALS['tmpl']->assign("imgsize", getimagesize(BASE_DIR . phpReplace($image_file[0])));
            }
            $GLOBALS['tmpl']->assign("imgtype", $RubTyp);
            $GLOBALS['tmpl']->assign("imglink", phpReplace($image_file[0]));
            $GLOBALS['tmpl']->assign("imgtitle", @$image_file[1]);
            $ausg = $GLOBALS['tmpl']->fetch(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/rfield.tpl");
          } else {
            $ausg = '<img src="'.phpReplace($image_file[0]).'" alt="'.@$image_file[1].'" border="0" />';
          }
        }
        $maxlength = '';
      break;


      case 'link' :
        $link = explode("||", $ausg);
        if (@empty($link[1])){$ausg = " <a target=\"_self\" href=\"$link[0]\">" . @$link[0] . "</a>";}else{$ausg = " <a target=\"_self\" href=\"$link[0]\">" . @$link[1] . "</a>";}
        $maxlength = '';
      break;

      case 'link_ex' :
        $link = explode("||", $ausg);
        if (@empty($link[1])){$ausg = " <a target=\"_blank\" href=\"$link[0]\">" . @$link[0] . "</a>";}else{$ausg = " <a target=\"_blank\" href=\"$link[0]\">" . @$link[1] . "</a>";}
        $maxlength = '';
      break;

    }

    if($maxlength != '') {
      if ($maxlength < 0) {
        $ausg = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $ausg);
        $ausg = str_replace(array("\r\n","\n","\r")," ",$ausg);
        $ausg = strip_tags($ausg);
        $ausg=preg_replace('/(\\s+)/', ' ',$ausg);
        $maxlength = abs($maxlength);
      }
      $ausg = substr($ausg, 0, $maxlength) . ((strlen($ausg) >= $maxlength) ? '... ' : '');
    }
    echo $ausg;
  }
}

function getDropdown($FeldsDD, $rid, $qid, $formaction) {

  $Felds = explode(",", $FeldsDD);
  $doc_queri = 'doc' . def_id() . '_queri' . $qid;

  foreach ($Felds as $Felds => $Feld) {
    $sql = $GLOBALS['db']->Query("
      SELECT Titel,StdWert
      FROM " . PREFIX . "_rubric_fields
      WHERE Id = '" . $Feld . "'
        AND RubrikId = '" . $rid . "'
        AND RubTyp = 'dropdown'
      ");
    $row = $sql->fetchrow();
    if (!($row)) continue;

    $select[$Feld]['titel'] = $row->Titel;
    $select[$Feld]['selected'] = (isset($_SESSION[$doc_queri]['fld'][$Feld]) && $_SESSION[$doc_queri]['fld'][$Feld] != '') ? $_SESSION[$doc_queri]['fld'][$Feld] : $row->Wert;
    $select[$Feld]['options'] = explode(",", $row->StdWert);
  }

  $GLOBALS['tmpl']->assign("ctrlrequest", $select);
  $GLOBALS['tmpl']->assign("formaction", $formaction);
  $GLOBALS['tmpl']->display(BASE_DIR . $GLOBALS['tmpl']->_tpl_vars['tpl_path'] . "/modules/request/remote.tpl");
}

function getDbField($rid,$doc,$maxlength='') {
/******************************************************************************************
 * Функция получения содержимого поля для обработки в шаблоне запроса
 * Параметры:
 *   $rid - идентификатор поля, для [cpabrub:12][150] $rid=12
 *
 *   $doc - идентификатор документа к которому принадлежит поле.
 *   Для его подстановки в функцию cp_parse_request() добавлена строка:
 *   $return = preg_replace("[\[cpabid\]]", "$row->Id", $return);
 *
 *   $maxlength - необязательный параметр, количество возвращаемых символов содержимого поля.
 *   если данный параметр указать со знаком минус содержимое поля будет очищено от HTML-тэгов.
 *
 * Пример использования в шаблоне:
 *   <li>
 *     <?php
 *      $r = getDbField(12, [cpabid]);
 *      echo $r . " (" . strlen($r) . ")";
 *     ?>
 *   </li>
 *
 *****************************************************************************************/
  if (isset($_SESSION['Doc_Arr'][$doc]) && $_SESSION['Doc_Arr'][$doc] != '') {
    $DocFields = $_SESSION['Doc_Arr'][$doc];
  } else {
    $sqlx = $GLOBALS['db']->Query("SELECT doc_field.Id,RubrikFeld,RubTyp,Inhalt,Redakteur
      FROM " . PREFIX . "_document_fields AS doc_field
      LEFT JOIN " . PREFIX . "_rubric_fields AS rub_field ON RubrikFeld = rub_field.Id
      LEFT JOIN " . PREFIX . "_documents AS doc ON doc.Id = DokumentId
      WHERE DokumentId = '" . $doc . "'
        AND Inhalt != ''
      ");
    while ($rowx = $sqlx->fetchrow()) {
      $DocFields[$rowx->RubrikFeld] = array(
        'Id' => $rowx->Id,
        'RubTyp' => $rowx->RubTyp,
        'Inhalt' => $rowx->Inhalt,
        'Redakteur' => $rowx->Redakteur
      );
    }
    $_SESSION['Doc_Arr'][$doc] = $DocFields;
  }
  $Inhalt = isset($DocFields[$rid]) ? $DocFields[$rid]['Inhalt'] : '';

  $ausg = '';
  if($Inhalt != '') {
    if(!defined("REQUEST_REPLACEMENT")) define("REQUEST_REPLACEMENT", substr($_SERVER['SCRIPT_NAME'],0,-9));
    $ausg = str_replace("[cp:replacement]", REQUEST_REPLACEMENT, $Inhalt);
    $ausg = strip_tags($ausg, "<br /><strong><em><p><i>");
    $ausg = str_replace("[cp:mediapath]", "/templates/" . T_PATH . "/", $ausg);
  }

  if($maxlength != '') {
    if ($maxlength < 0) {
      $ausg = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $ausg);
      $ausg = str_replace(array("\r\n","\n","\r")," ",$ausg);
      $ausg = strip_tags($ausg);
      $ausg=preg_replace('/(\\s+)/', ' ',$ausg);
      $maxlength = abs($maxlength);
    }
    $ausg = substr($ausg, 0, $maxlength) . ((strlen($ausg) >= $maxlength) ? '... ' : '');
  }

  return $ausg;
}
?>