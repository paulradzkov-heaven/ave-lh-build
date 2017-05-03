<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function phpReplace($code) {
  $code = eregi_replace(array('<?', '?>', '<script'), '', $code);
  return $code;
}

function shortl($string,$maxlength) {
  if ($maxlength === "fetch") { 
  	return $string; 
  } else {
  	$string = substr($string, 0, $maxlength) . '... ';
  	return $string;
  }
}

function docPages($text, $length) {
  if(!isset($_REQUEST['artpage'])) {
    $_REQUEST['artpage']=1;
  }

  $seite_anzeigen = explode("<h3>[cp:newpage]</h3>",$text);
  $anzahl_seiten = @count($seite_anzeigen);
  $text = @$seite_anzeigen[$_REQUEST['artpage']-1];

  if ($length==''||$length!='fetch') echo $text;
  if ($length=='fetch') return $text;

  if($anzahl_seiten > 1) {
  if (strpos($_REQUEST['urldetectUrl'], '-') || strpos($_REQUEST['urldetectUrl'], '?'))
    {
    $doc = (!isset($_REQUEST['doc'])) ? "doc" : $_REQUEST['doc'];
    $url = "/index.php?id=$_REQUEST[id]&amp;doc=$doc&amp;artpage={s}";
    $url = (CP_REWRITE == 1) ? cp_rewrite($url) : $url;

    echo "<div class=\"container_pages_navigation\">";
    echo docPage($anzahl_seiten, fetchSettings("NaviSeiten") . " " . $_REQUEST['artpage'] . "&nbsp;", " <a href=\"$url\">{t}</a> ");
    echo "</div>";
    }
  else
    {
    for ($i=1;$i<$anzahl_seiten+1;$i++)
      {
  $outPage .= ($_REQUEST['artpage'] == $i) ? '<span class="pages_navigation">'.$i.'</span> ' : '<a href="'.$_REQUEST[urldetectUrlDelPage].'artpage'.$i.'.html">'.$i.'</a> ';
      }

    $outPageStart = ($_REQUEST['artpage'] == 1) ? '' : '<a href="'.$_REQUEST[urldetectUrlDelPage].'">Первая</a> <a href="'.$_REQUEST[urldetectUrlDelPage].'artpage'.($_REQUEST[artpage]-1).'.html">Предыдущая</a> ';

        $outPageEnd = ($_REQUEST['artpage'] == $anzahl_seiten) ? '' : '<a href="'.$_REQUEST[urldetectUrlDelPage].'artpage'.($_REQUEST[artpage]+1).'.html">Следующая</a> <a href="'.$_REQUEST[urldetectUrlDelPage].'artpage'.$anzahl_seiten.'.html">Последняя</a> ';

    $outPage = '<div class="page_navigation">'.$outPageStart.$outPage.$outPageEnd.'</div>';
  echo $outPage;
    }
  }

}

function ParseFields($Id, $doc_id='', $width='', $height='', $length='') {

  if($length == '') ob_start();
  if ($doc_id=='') $doc_id = def_id();

  if (isset($_SESSION['Doc_Arr'][$doc_id]) && $_SESSION['Doc_Arr'][$doc_id] != '') {
  	$DocFields = $_SESSION['Doc_Arr'][$doc_id];
  } else {
    $sqlx = $GLOBALS['db']->Query("SELECT doc_field.Id,RubrikFeld,RubTyp,Inhalt,Redakteur
      FROM " . PREFIX . "_document_fields AS doc_field
      LEFT JOIN " . PREFIX . "_rubric_fields AS rub_field ON RubrikFeld = rub_field.Id
      LEFT JOIN " . PREFIX . "_documents AS doc ON doc.Id = DokumentId
      WHERE DokumentId = '" . $doc_id . "'
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
    $_SESSION['Doc_Arr'][$doc_id] = $DocFields;
  }
  $Inhalt = isset($DocFields[$Id]) ? $DocFields[$Id]['Inhalt'] : '';

  $pre = '';
  $first = '<p style="width:100%; float:left; border:1px dashed #ccc; border-top:0px; line-heigth:0.1em; padding:3px">';

  if(!empty($Inhalt)) {
    $RubTyp = $DocFields[$Id]['RubTyp'];

    $Inhalt = eregi_replace("\[cprequest:([_a-zA-Z0-9\]*)\]", (function_exists('cp_parse_request') ? "<?php cp_parse_request(\"\\1\"); ?>" : ''), $Inhalt);
    $Inhalt = str_replace("[cp:replacement]", REPLACE_MENT, $Inhalt);
    $Inhalt = str_replace("[cp:mediapath]", '/templates/' . T_PATH . '/', $Inhalt);
    $Inhalt = ($length != '') ? shortl($Inhalt,$length) : $Inhalt;
    $Inhalt = stripslashes(hide($Inhalt));

    if (isset($_SESSION['InstallModules']) && $_SESSION['InstallModules'] != '') {
      foreach ($_SESSION['InstallModules'] as $row_modul) {
        include_once(BASE_DIR . '/modules/' . $row_modul->ModulPfad . '/modul.php');
        $Inhalt = eregi_replace($row_modul->CpEngineTag, (($row_modul->IstFunktion==1) ? ((function_exists($row_modul->ModulFunktion)) ? $row_modul->CpPHPTag : ""." &quot;$row_modul->ModulName&quot;") : ''), $Inhalt);
      }
    } else {
      $sql_modul = $GLOBALS['db']->Query("SELECT ModulName,ModulPfad,Template,Status,CpEngineTag,CpPHPTag,ModulFunktion,IstFunktion FROM " . PREFIX. "_module WHERE Status = '1'");
      $InstallModules = array();
      while ($row_modul = $sql_modul->FetchRow()) {
        array_push($InstallModules, $row_modul);
        include_once(BASE_DIR . '/modules/' . $row_modul->ModulPfad . '/modul.php');
        $Inhalt = eregi_replace($row_modul->CpEngineTag, (($row_modul->IstFunktion==1) ? ((function_exists($row_modul->ModulFunktion)) ? $row_modul->CpPHPTag : ""." &quot;$row_modul->ModulName&quot;") : ''), $Inhalt);
      }
      $_SESSION['InstallModules'] = $InstallModules;
    }

    $disfirst = true;
    switch($RubTyp) {
      case 'created' :
      case 'author':
        $Inhalt = phpReplace(cp_parse_string($Inhalt));
        $disfirst = false;
      break;

      case 'kurztext' :
        $Inhalt = phpReplace(cp_parse_string($Inhalt));
        $disfirst = false;
      break;

      case 'langtext' :
        $Inhalt = docPages(cp_parse_string(cp_highlight($Inhalt)), $length);
        $disfirst = false;
      break;

/* Добавлено */

      case 'smalltext' :
        $Inhalt = docPages(cp_parse_string(cp_highlight($Inhalt)), $length);
        $disfirst = false;
      break;

      case 'bild' :
        $image_file = explode("||", $Inhalt);
        if(file_exists(BASE_DIR . phpReplace($image_file[0]))) {
          if (file_exists(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/field-" . $Id . ".tpl")) {
            if(function_exists("getimagesize")) {
              $GLOBALS['tmpl']->assign("imgsize", getimagesize(BASE_DIR . phpReplace($image_file[0])));
            }
            $GLOBALS['tmpl']->assign("imgtype", $RubTyp);
            $GLOBALS['tmpl']->assign("imglink", phpReplace($image_file[0]));
			$GLOBALS['tmpl']->assign("imgtitle", @$image_file[1]);
            $Inhalt = $GLOBALS['tmpl']->fetch(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/field-" . $Id . ".tpl");
          } elseif (file_exists(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/field.tpl")) {
            if(function_exists("getimagesize")) {
              $GLOBALS['tmpl']->assign("imgsize", getimagesize(BASE_DIR . phpReplace($image_file[0])));
            }
            $GLOBALS['tmpl']->assign("imgtype", $RubTyp);
            $GLOBALS['tmpl']->assign("imglink", phpReplace($image_file[0]));
			$GLOBALS['tmpl']->assign("imgtitle", @$image_file[1]);
            $Inhalt = $GLOBALS['tmpl']->fetch(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/field.tpl");
          } else {
            $Inhalt = $pre . "<img alt=\"" . @$image_file[1] . "\" src=\"" . phpReplace($image_file[0]) . "\" border=\"\" />";
          }
        }
        $disfirst = true;
      break;

      case 'bild_links' :
        $image_file = explode("||", $Inhalt);
        if(file_exists(BASE_DIR . phpReplace($image_file[0]))) {
          if (file_exists(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/field-" . $Id . ".tpl")) {
            if(function_exists("getimagesize")) {
              $GLOBALS['tmpl']->assign("imgsize", getimagesize(BASE_DIR . phpReplace($image_file[0])));
            }
            $GLOBALS['tmpl']->assign("imgtype", $RubTyp);
            $GLOBALS['tmpl']->assign("imglink", phpReplace($image_file[0]));
			$GLOBALS['tmpl']->assign("imgtitle", @$image_file[1]);
            $Inhalt = $GLOBALS['tmpl']->fetch(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/field-" . $Id . ".tpl");
          } elseif (file_exists(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/field.tpl")) {
            if(function_exists("getimagesize")) {
              $GLOBALS['tmpl']->assign("imgsize", getimagesize(BASE_DIR . phpReplace($image_file[0])));
            }
            $GLOBALS['tmpl']->assign("imgtype", $RubTyp);
            $GLOBALS['tmpl']->assign("imglink", phpReplace($image_file[0]));
			$GLOBALS['tmpl']->assign("imgtitle", @$image_file[1]);
            $Inhalt = $GLOBALS['tmpl']->fetch(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/field.tpl");
          } else {
            $Inhalt = $pre . "<img style=\"padding-right:6px\" align=\"left\" alt=\"" . @$image_file[1] . "\" src=\"" . phpReplace($image_file[0]) . "\" border=\"\" />";
          }
        }
        $disfirst = true;
      break;

      case 'bild_rechts' :
        $image_file = explode("||", $Inhalt);
        if(file_exists(BASE_DIR . phpReplace($image_file[0]))) {
          if (file_exists(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/field-" . $Id . ".tpl")) {
            if(function_exists("getimagesize")) {
              $GLOBALS['tmpl']->assign("imgsize", getimagesize(BASE_DIR . phpReplace($image_file[0])));
            }
            $GLOBALS['tmpl']->assign("imgtype", $RubTyp);
            $GLOBALS['tmpl']->assign("imglink", phpReplace($image_file[0]));
			$GLOBALS['tmpl']->assign("imgtitle", @$image_file[1]);
            $Inhalt = $GLOBALS['tmpl']->fetch(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/field-" . $Id . ".tpl");
          } elseif (file_exists(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/field.tpl")) {
            if(function_exists("getimagesize")) {
              $GLOBALS['tmpl']->assign("imgsize", getimagesize(BASE_DIR . phpReplace($image_file[0])));
            }
            $GLOBALS['tmpl']->assign("imgtype", $RubTyp);
            $GLOBALS['tmpl']->assign("imglink", phpReplace($image_file[0]));
			$GLOBALS['tmpl']->assign("imgtitle", @$image_file[1]);
            $Inhalt = $GLOBALS['tmpl']->fetch(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/field.tpl");
          } else {
            $Inhalt = $pre . "<img style=\"padding-left:6px\" align=\"right\" alt=\"" . @$image_file[1] . "\" src=\"" . phpReplace($image_file[0]) . "\" border=\"\" />";
          }
        }
        $disfirst = true;
      break;


      case 'js' :
        $Inhalt = @cp_code_table($Inhalt,'JAVASCRIPT',0);
        $disfirst = false;
      break;

      case 'php' :
        $Inhalt = @cp_code_table($Inhalt,'PHP',0);
        $disfirst = false;
      break;

      case 'code' :
        $Inhalt = @cp_code_table($Inhalt,'CODE',0);
        $disfirst = false;
      break;

      case 'html' :
        $Inhalt = @cp_code_table($Inhalt,'HTML',0);
        $disfirst = false;
      break;

      case 'flash' :
        $flash_explode = explode("|", $Inhalt);
        $Inhalt = "<embed scale=\"exactfit\"  width=\"".@$flash_explode[1]. "\" height=\"".@$flash_explode[2]. "\"  type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" src=\"".phpReplace($flash_explode[0]). "\" play=\"true\" loop=\"true\" menu=\"true\"></embed>";
        $disfirst = false;
      break;

      case 'download' :
        $Inhalt =  phpReplace($Inhalt);
        $start_dl = explode("|", $Inhalt);
        $Inhalt = @$start_dl[1] . '<br /><form method="get" target="_blank" action="'.$start_dl[0].'"><input class="button" type="submit" value="Скачать" /></form>';
        $disfirst = false;
      break;

      case 'video_avi' :
      case 'video_wmf' :
      case 'video_wmv' :
        $Inhalt =  phpReplace($Inhalt);
        $vid_file = explode("|", $Inhalt);
        $vid_width = (is_array($vid_file) && !empty($vid_file[1])) ?  $vid_file[1] : 406;
        $vid_height = (is_array($vid_file) && !empty($vid_file[2])) ?  $vid_file[2] : 335;
        $vid_out = '<object id="MediaPlayer" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112" height="'.$vid_height.'" width="'.$vid_width.'">
              <param name="animationatStart" value="false">
              <param name="autostart" value="false">
              <param name="URL" value="'.$vid_file[0].'">
              <param name="volume" value="-200">
              <embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" name="MediaPlayer" src="'.$vid_file[0].'" autostart="0" displaysize="0" showcontrols="1" showdisplay="0" showtracker="1" showstatusbar="1" height="'.$vid_height.'" width="'.$vid_width.'">
              </object>';
        $Inhalt = $vid_out;
        $disfirst = false;
      break;

      case 'video_mov' :
        $Inhalt =  phpReplace($Inhalt);
        $vid_out = '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" width="406" height="335" codebase="http://www.apple.com/qtactivex/qtplugin.cab">
              <param name="src" value="'.$Inhalt.'">
              <param name="autoplay" value="false">
              <param name="controller" value="true">
              <param name="target" value="myself">
              <param name="type" value="video/quicktime">
              <embed TARGET="myself" src="'.$Inhalt.'" width="406" height="335" autoplay="false" controller="true" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/">
              </embed>
              </object>';
        $Inhalt = $vid_out;
        $disfirst = false;
      break;

      case 'dropdown' :
        $Inhalt =  phpReplace($Inhalt);
        $disfirst = false;
      break;

      case 'link' :
	    $link = explode("||", $Inhalt);
		if (empty($link[1])){$Inhalt = " <a target=\"_self\" href=\"$link[0]\">" . @$link[0] . "</a>";}else{$Inhalt = " <a target=\"_self\" href=\"$link[0]\">" . @$link[1] . "</a>";}
        $disfirst = false;
      break;

      case 'link_ex' :
	    $link_ex = explode("||", $Inhalt);
		if (empty($link_ex[1])){$Inhalt = " <a target=\"_blank\" href=\"$link_ex[0]\">" . @$link_ex[0] . "</a>";}else{$Inhalt = " <a target=\"_blank\" href=\"$link_ex[0]\">" . @$link_ex[1] . "</a>";}
        $disfirst = false;
      break;
	  
// ????? ?????
	  case 'tags':
	  	
		$out_tags = '';
	  	if (isset($_REQUEST['id'])&&$_REQUEST['id']!='') {$id_doc = $_REQUEST['id'];} else {$id_doc = 1;}
		$sql_tags = $GLOBALS['db']->Query("SELECT Inhalt FROM " . PREFIX . "_document_fields WHERE  RubrikFeld='".$Id."' AND DokumentId='".$id_doc."'");
		$row_tags = $sql_tags->fetchrow();
		$tags = explode(',', $row_tags->Inhalt);
		for ($i=0; $i<count($tags); $i++){
	  		$out_tags.= '<span><a href="http://'.$_SERVER['HTTP_HOST'].'/index.php?search=1&amp;tag='.urlencode($tags[$i]).'">'.$tags[$i].'</a></span> ';
		}
		
		$Inhalt = $out_tags;
		$disfirst = false;
	  break;

    }
    $last = "<a href=\"javascript:;\" onclick=\"window.open('/admin/index.php?do=docs&action=edit&closeafter=1&RubrikId=".RUB_ID. "&Id=".addslashes((int)$_REQUEST['id']). "&pop=1&feld=".$rowx->Id. "#".$rowx->Id. "','EDIT','left=0,top=0,width=950,height=700,scrollbars=1');\"><img style=\"vertical-align:middle\" src=\"/inc/stdimage/edit.gif\" border=\"0\" alt=\"\" /></a>";
    $last_2 = "</p><p style=line-height:0.1em;clear:both></p>";

    $wysmode = false;

    if(($DocFields[$Id]['Redakteur'] == @$_SESSION['cp_benutzerid']) && ( isset($_SESSION[RUB_ID . '_editown']) && @$_SESSION[RUB_ID . '_editown'] == 1) || @$_SESSION[RUB_ID . '_alles'] == 1 || @UGROUP == 1) {
      $wysmode = true;
    }

    $Inhalt = ($wysmode && isset($_SESSION['cp_adminmode']) && $_SESSION['cp_adminmode'] == 1) ? (($disfirst==true) ? $first : "") . $Inhalt . (($disfirst == true) ? $last . $last_2 : $last) : $Inhalt;


    if($length == '')
      echo $Inhalt;
    else
      return $Inhalt;
  } else {

      if($length == '')
        echo "<!-- EMPTY -->";
      else
        return "";
  }
   
  if($length == '') {
    $p_out = ob_get_contents();
    ob_end_clean();
    eval ("?>" . $p_out . "<?");
  } 
  
}

function cp_parse_php($code, $nohighlight='0') {
   (string) $highlight = '';
   if ( version_compare(PHP_VERSION, "4.2.0", "<") === 1 ) {
     ob_start();
     highlight_string($code);
     $highlight = ob_get_contents();
     ob_end_clean();
   } else {
     $highlight = highlight_string($code, true);
   }

   if ( $inline === true )
     $highlight=preg_replace("/<code>/i","<code class=\"code_inline\">",$highlight);
   else
     $highlight=preg_replace("/<code>/i","<code class=\"code_block\">",$highlight);

   if ( $return === true ) {
     return $highlight;
   } else {
     echo $highlight;
   }
}

function cp_code_table($string, $title = '', $nohighlight = '0') {
  $Line = explode("\n",$string);
  for($i=1;$i<=count($Line);$i++) {
    $line .= "&nbsp;".$i. "&nbsp;<br />";
  }

if($nohighlight==1) {
  $Code = "<pre>".htmlspecialchars($string). "</pre>";
} else {

  ob_start();
  flush();
  cp_parse_php($string);
  $Code = ob_get_contents();
  ob_end_clean();
}
  $header='<table border="0" cellpadding="0" cellspacing="0" width="95%" style="border-style: solid; border-width:1px; border-color: #ccc">
   <tr>
   <td width="100%" colspan="2"  style="border-bottom:1px solid #fff; padding:5px; background-color: #ccc; font-family:arial; color:white; font-weight:bold;">'.$title.'</td>
   </tr>
   <tr>
   <td width="3%" valign="top" style="color:#fff; background-color: #ccc"><code>'.$line.'</code></td>
   <td width="97%" valign="top" style="background-color: white;"><div style="white-space: nowrap; overflow: auto;"><code>';

  $footer = $Code.'</div></code></td>
   </tr>
  </table>';

  return $header.$footer;
}

function getRubField($Id,$maxlength='') {
/******************************************************************************************
 * Функция получения содержимого поля для обработки в шаблоне рубрики
 * Параметры:
 *  $Id - идентификатор поля, для [cprub:12] $Id=12
 *
 *  $maxlength - необязательный параметр, количество возвращаемых символов содержимого поля.
 *  если данный параметр указать со знаком минус содержимое поля будет очищено от HTML-тэгов.
 *
 * Пример использования в шаблоне:
 *   <?php
 *    $r = getRubField(12);
 *    echo $r . " (" . strlen($r) . ")";
 *   ?>
 *
 *****************************************************************************************/

  $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_document_fields WHERE DokumentId='" . def_id() . "' AND RubrikFeld = '" . $Id . "' limit 1");
  $row = $sql->fetchrow();

  $field = '';
  if(@$row->Inhalt != '') {
    if(!defined("REQUEST_REPLACEMENT")) define("REQUEST_REPLACEMENT", substr($_SERVER['SCRIPT_NAME'], 0, -9));
    $field = str_replace("[cp:replacement]", REQUEST_REPLACEMENT, $row->Inhalt);
    $field = strip_tags($field, "<br /><strong><em><p><i>");
    $field = str_replace("[cp:mediapath]", "/templates/" . T_PATH . "/", $field);

    if($maxlength != '') {
      if ($maxlength < 0) {
        $field = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $field);
        $field = str_replace(array("\r\n", "\n", "\r"), " ", $field);
        $field = strip_tags($field);
        $field=preg_replace('/(\\s+)/', ' ', $field);
        $maxlength = abs($maxlength);
      }
      $field = substr($field, 0, $maxlength) . ((strlen($field) >= $maxlength) ? '... ' : '');
    }
  }

  return $field;
 }

 function cp_highlight($text) {
    // Ищем в тексте тэги обрамляющие блок PHP-кода
    @preg_match_all ("/\[php\](.*?)\[\/php\]/si", $text, $match);

    // Блоков PHP-кода может быть несколько, обрабатываем в цикле все найденные блоки
    for($i=0;$i<count($match[1]);$i++) {
        // Устраняем в PHP-коде изменения сделанные редактором FCKeditor
        $code = html_entity_decode(trim(str_replace("<br />", "",$match[1][$i])));

        // Раскрашиваем синтаксис блока PHP-кода
        if (version_compare(PHP_VERSION, "4.2.0", "<") === 1) {
            ob_start();
            highlight_string($code);
            $color_code = ob_get_contents();
            ob_end_clean();
        } else {
            // Начиная с версии PHP 4.2.0 результат работы функции highlight_string()
            // можно присваиваивать переменной без танцев с бубном и буферизации вывода
            $color_code = highlight_string($code, true);
        }

        // Раскрашенный PHP-код упаковываем в контейнер
        $block_code  = '<div style="margin:5px 0 0">PHP</div>';
        $block_code .= '<div style="margin:0; padding:5px; border:1px inset; overflow:auto">';
        $block_code .= '<code style="white-space:nowrap">' . $color_code . '</code></div>';

        // Контейнер с раскрашенным PHP-кодом вставляем в текст
        $text = str_replace($match[0][$i], $block_code, $text);
    }

    return $text;
}

function cp_parse_template($tpl, $rub_id='', $doc_id=''){
		
		$id_fields = array();
		$sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_rubric_fields WHERE  RubrikId='".$rub_id."'");
		while( $row = $sql->fetchrow()){
			array_push($id_fields, $row->Id);
		}
		
		if (file_exists(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/" .$tpl."-". $rub_id . ".tpl")){
				assign_var($id_fields, $doc_id);
				$GLOBALS['tmpl']->display(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/" .$tpl."-". $rub_id . ".tpl");	
		} else if (file_exists(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/" .$tpl. ".tpl")){
				assign_var($id_fields, $doc_id);
				$GLOBALS['tmpl']->display(BASE_DIR . $GLOBALS['tmpl']->template_dir . "field/" .$tpl. ".tpl");	
		} else {
				echo "No Template";
		}
}

function assign_var($id_fields, $doc_id){
	for($i=0; $i<count($id_fields); $i++){
				if (isset($id_fields[$i])&&!empty($id_fields[$i])) 	$var = ParseFields($id_fields[$i], $doc_id, '', '', 'fetch');
				$var_tmp = "cprub".$id_fields[$i];
				$GLOBALS['tmpl']->assign($var_tmp, $var);
	}
}

?>