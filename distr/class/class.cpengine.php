<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class CPENGINE {

  var $_doc_not_fount      = "<h1>Ошибка 404.</h1><br />Запрошенный документ не найден.";
  var $_doc_not_published  = "Запрашиваемый документ запрещен к публикации.";
  var $_module_error       = "Запрашиваемый модуль не может быть загружен.";
  var $_module_notfound    = "Запрашиваемый модуль не найден.";

  function load_error() {
    return $this->_module_error;
    exit;
  }

  function fetchSettings($field, $table='') {
    $sql = $GLOBALS['db']->Query("SELECT " . $field . " FROM " . PREFIX . "_settings");
    $row = $sql->fetcharray();
    $sql->Close();
    return $row[$field];
  }

  function fetchDocPerms($RubId = '') {

    if(!defined('UGROUP')) define('UGROUP',2);
    unset($_SESSION[RUB_ID . '_docread']);

    $sql_dp = $GLOBALS['db']->Query("SELECT Rechte FROM " . PREFIX . "_document_permissions WHERE RubrikId = '" . (int)$RubId . "' AND BenutzerGruppe = '" . UGROUP . "'");

    while($row_dp = $sql_dp->fetchrow()) {
      $dPerms = explode('|', $row_dp->Rechte);

      foreach($dPerms as $mydPerm) {
        if(!empty($mydPerm)) $_SESSION[$RubId . '_' . $mydPerm] = 1;
      }
    }
  }

  function notFound() {
    $redir_to = $this->fetchSettings('Fehlerseite');

    $sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_documents WHERE Id = '" . $redir_to . "'");
    $num = $sql->numrows();

    if($num > 0) {
      header("Location:/index.php?id=" . $redir_to);
      exit;
    } else {

      if(!defined('NOT_FOUND')) {
        return $this->_doc_not_fount;
        define('NOT_FOUND',1);
      }
    }
  }

  function parseRubTemplateFields($rubTemplate, $docid = '') {

    $doc_id = ($docid == '') ? def_id() : $docid;
    $sql_rf = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_document_fields WHERE DokumentId = '" . $doc_id . "'");
    $template_to_fetch = "";

    while($row_rf = $sql_rf->fetchrow()) {
      $template_to_fetch = eregi_replace("\[cprub:([0-9-]*)\]", "<?php ParseFields(\"\\1\"); ?>", $rubTemplate);
    }

    return $template_to_fetch;
  }

  function fetchMainRubTemplate($RubrikId = '', $Template = '', $fetched = '') {

	
    if(defined("ONLYCONTENT") || (isset($_REQUEST['pop']) && $_REQUEST['pop'] == 1)) {
      $out = '[cp:maincontent]';
    } else {

      if(!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id'])) $_REQUEST['id'] = 1;
      $id = (int)$_REQUEST['id'];

      $sql = $GLOBALS['db']->Query("SELECT RubrikId FROM " . PREFIX . "_documents WHERE Id = '" .$id. "'");
      $row = $sql->fetchrow();
      $sql->Close();


      $rub_id = ($RubrikId != '') ? $RubrikId : (!@$row->RubrikId) ? '' : $row->RubrikId;

      $sql_v = $GLOBALS['db']->Query("SELECT Vorlage FROM " . PREFIX . "_rubrics WHERE Id = '$rub_id'");
      $row_v = $sql_v->fetchrow();
      $sql_v->Close();

      $vl = (!@$row_v->Vorlage) ? '' : $row_v->Vorlage;

      if(!empty($vl)) {
        $sql = $GLOBALS['db']->Query("SELECT Template FROM " . PREFIX . "_templates WHERE Id = '" . $vl . "'");
        $row = $sql->fetchrow();
        $sql->Close();
      }

      $out = ($Template != '') ? $Template : (!@$row->Template) ? "" : stripslashes($row->Template);
      $out = ($fetched != '') ? $fetched : $out;
      }
	
    return $out;
  }

  function fetchModuleTemplate() {
//    $found = true;
    $found = false;
    $fetched = '';

//    $sql = $GLOBALS['db']->Query("SELECT Template FROM " . PREFIX . "_module WHERE ModulPfad = '" . $_REQUEST['module'] . "'");
//    $row = $sql->fetchrow();
    if (isset($_SESSION['InstallModules']) && $_SESSION['InstallModules'] != '') {
      foreach ($_SESSION['InstallModules'] as $row_modul) {
        if ($row_modul->ModulPfad == $_REQUEST['module']) {
          $TemplateId = $row_modul->Template;
          $found = true;
          break;
        }
      }
    } else {
      $sql_modul = $GLOBALS['db']->Query("SELECT ModulName,ModulPfad,Template,Status,CpEngineTag,CpPHPTag,ModulFunktion,IstFunktion FROM " . PREFIX. "_module WHERE Status = '1'");
      $InstallModules = array();
      while ($row_modul = $sql_modul->FetchRow()) {
        array_push($InstallModules, $row_modul);
        if ($row_modul->ModulPfad == $_REQUEST['module']) {
          $TemplateId = $row_modul->Template;
          $found = true;
        }
      }
      $sql_modul->Close();
      $_SESSION['InstallModules'] = $InstallModules;
    }

    if(!is_dir(BASE_DIR . '/modules/' . $_REQUEST['module'])) {
      $row_tpl->Template = $this->_module_notfound;
      $found = false;
    }


    if($found == true) {
      $sql = $GLOBALS['db']->Query("SELECT Template FROM " . PREFIX . "_templates WHERE Id = '" . $TemplateId . "'");
      $row_tpl = $sql->fetchrow();
      $sql->Close();

      if(!is_object($row_tpl)) {
        $sql = $GLOBALS['db']->Query("SELECT Template FROM " . PREFIX . "_templates WHERE Id = '1'");
        $row_tpl = $sql->fetchrow();
        $sql->Close();
      }
    }

    if(!$found) {
      echo '<meta http-equiv="Refresh" content="2;URL=/index.php" />';
    }

    $fetched = $row_tpl->Template;

    return $fetched;
  }

//function of searching tags
	function parse_tags($tag){
	
	$j=0;
	$print_out = '<ol class="tag_search_result">';
	$id_tags = array();
	  		$sql_id = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_rubric_fields WHERE RubTyp = 'tags'");
      		while($row_id = $sql_id->fetchrow()){
				array_push($id_tags, $row_id->Id);
			}
	
	$id = array_unique($id_tags);
	$in = implode(",", $id);
	 
	$sql_tags = $GLOBALS['db']->Query("SELECT Inhalt, DokumentId FROM " . PREFIX . "_document_fields WHERE  RubrikFeld IN(".$in.")");
	
	$docsId = array();
	while($row_tags = $sql_tags->fetchrow()){
		if (preg_match("/".$tag."/i", $row_tags->Inhalt)){
			array_push($docsId, $row_tags->DokumentId);
		}
	}
	
	$in_docs = implode(",", $docsId);
	$sql_docs = $GLOBALS['db']->Query("SELECT Titel, Id FROM " . PREFIX . "_documents WHERE  Id IN (".$in_docs.") AND DokStatus=1 AND DokEnde > ".time());
	while($row_docs = $sql_docs->fetchrow()){
		$print_out.= '<li><a href="index.php?id='.$row_docs->Id.'">'.$row_docs->Titel.'</a></li>';
		$j++;
	}
	
	
	$print_out.= '</ol>';
	$digit = (string) $j;
    $digit = substr($digit, -1);

   if ($digit == 1&&!($j >= 5 && $j < 21)) return '<h1>Найден '.$j.' результат</h1>'.$print_out;
   if (($j >= 5 && $j < 21)||($digit >= 5 && $digit <= 9)|| $digit == 0) return '<h1>Найдено '.$j.' результатов</h1>'.$print_out;
   if ($digit >= 2 && $digit <= 4) return '<h1>Найдено '.$j.' результата</h1>'.$print_out;

}

//tags cloud
function parse_cloud_tags(){
	
	$line = '';
	$cloud = '<div id="cloud_tag">';
	$id_tags = array();
	  		$sql_id = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_rubric_fields WHERE RubTyp = 'tags'");
      		while($row_id = $sql_id->fetchrow()){
				array_push($id_tags, $row_id->Id);
			}
	
	$id = array_unique($id_tags);
	$in = implode(",", $id);
	
	
	$sql_tags = $GLOBALS['db']->Query("SELECT Inhalt, DokumentId FROM " . PREFIX . "_document_fields WHERE  RubrikFeld IN(".$in.")");
	$docsId = array();
	$lines = array();
	while($row_tags = $sql_tags->fetchrow()){
			array_push($docsId, $row_tags->DokumentId);
			$lines[$row_tags->DokumentId] = $row_tags->Inhalt;
	}
	
	$in_docs = implode(",", $docsId);
	$sql_doc = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_documents WHERE  Id IN (".$in_docs.") AND DokStatus='1' AND DokEnde > ".time());
	while($row_doc = $sql_doc->fetchrow()){
		$line.= ','.$lines[$row_doc->Id];
	}
	
	$label = explode(',', $line);
	sort($label);
	$maxsize = 18;
	$minsize = 7;
	$count = array_count_values($label);
	
	rsort($count);
	$maxtopic = $count[1];
	
	$count = array_count_values($label);
	foreach($count as $key=>$val) {
		
		if ($maxtopic == 1) $maxtopic =2;
		$k =  (($maxsize-$minsize)/($maxtopic-1))*($val-1) + $minsize;
		$cloud.='<span style="font-size:'.round($k).'pt;" title="'.$val.' topics"><a href="http://'.$_SERVER['HTTP_HOST'].'/index.php?search=1&amp;tag='.urlencode($key).'">'.$key.'</a></span> ';
	}
	$cloud.='</div>';
	
	return $cloud;
}

  function displaySite($id, $rub_id = '') {

    $static = ($id == 2) ? '' : "AND DokStatus = '1'";

    $sql_ds = $GLOBALS['db']->Query("SELECT DokStatus FROM " . PREFIX . "_documents WHERE  Id = '" . $id . "'");
    $row_ds = $sql_ds->fetchrow();

    if( (isset($_SESSION['adminpanel']) && $row_ds->DokStatus != 1)  || (isset($_SESSION['alles']) && $row_ds->DokStatus != 1)) {
      displayNotice($this->_doc_not_published);
      $static = "";
    }

    if (isset($_REQUEST['module']) && $_REQUEST['module'] == 'shop' && !isset($_REQUEST['product_id'])) {
      $q = "SELECT a.*, b.ShopKeywords, b.ShopDescription
        FROM
          " . PREFIX . "_documents AS a,
          " . PREFIX . "_modul_shop AS b
        WHERE a.Id = '1'
        ";

    } elseif (isset($_REQUEST['module']) && $_REQUEST['module'] == 'shop' && isset($_REQUEST['product_id']) && is_numeric($_REQUEST['product_id'])) {
      $q = "SELECT a.*, b.ProdKeywords, b.ProdDescription
        FROM
          " . PREFIX . "_documents AS a,
          " . PREFIX . "_modul_shop_artikel AS b
        WHERE a.Id = '1'
          AND b.Id = '" . (int)$_REQUEST['product_id'] . "'
        ";

    } else {
      $q = "SELECT *
        FROM
          " . PREFIX . "_documents
        WHERE Id = '" . $id . "'
          " . $static . "
          AND (DokStart < '" . time() . "' OR DokStart = '0')
          AND (DokEnde  > '" . time() . "' OR DokEnde  = '0')
          AND Geloescht != '1'
        ";
    }

    $sql = $GLOBALS['db']->Query($q);
    $row = $sql->fetchrow();
    $sql->Close();

    if(!$row && !defined("GET_NO_DOC")) {
      echo $this->notFound();

    } else {

      if(isset($_REQUEST['print']) && $_REQUEST['print'] == 1) {
        $GLOBALS['db']->Query("UPDATE " . PREFIX . "_documents SET Drucke = Drucke+1 WHERE Id = '" . $id . "'");

      } else {

        if (!isset($_SESSION["doc_view[$id]"])) {
        $GLOBALS['db']->Query("UPDATE " . PREFIX . "_documents SET Geklickt = Geklickt+1 WHERE Id = '" . $id . "'");
        $_SESSION["doc_view[$id]"] = $_SESSION["doc_view[$id]"] + 1;
        }



      }
    }
	
	

    if(!@$row->RubrikId) $row->RubrikId = '';

    $rub_id = ($rub_id != '') ? $rub_id : $row->RubrikId;

    $sql_rub_tpl = $GLOBALS['db']->Query("SELECT RubrikTemplate FROM " . PREFIX . "_rubrics WHERE Id = '" . $rub_id . "'");
    $row_rub_tpl = $sql_rub_tpl->fetchrow();
    $sql_rub_tpl->Close();

    define('RUB_ID', $row->RubrikId);
    $this->fetchDocPerms(RUB_ID);


    if(!@$row_rub_tpl->RubrikTemplate) {
      echo $this->notFound();
      $row_rub_tpl->RubrikTemplate = '';
      $row->Titel = '';
    }

    $rubTemplate = $row_rub_tpl->RubrikTemplate;
    $template_to_fetch = $this->parseRubTemplateFields($rubTemplate);
	
	
	//poisk tegov
	if (isset($_REQUEST['search']) && $_REQUEST['search'] == 1){
		
		if (isset($_REQUEST['tag']) && $_REQUEST['tag'] != ''){
		    $template_to_fetch = $this->parse_tags($_REQUEST['tag']);
			
		}
		
	}
	
    $template_to_fetch = preg_replace("[\[cprub:([0-9])\]]", "", $template_to_fetch);
    $template_to_fetch = preg_replace("[\[cp_doc:titel\]]", cp_parse_string($row->Titel), $template_to_fetch);
	
	
    if(!isset($_REQUEST['module'])) {
	      $print_out= $this->fetchMainRubTemplate(); 
    } else {

      $print_out = $this->fetchMainRubTemplate('','', $this->fetchModuleTemplate());
    }

    @ereg("\[cp_theme\:([_a-zA-Z0-9\]*)\]", $print_out, $fund);

    define('T_PATH', $fund[1]);

    $GLOBALS['tmpl']->assign('img_path', '/templates/'.T_PATH.'/images/');

    $template_to_fetch = str_replace('[cp:mediapath]', '/templates/' . T_PATH . '/', $template_to_fetch);

    $globals = new Globals;
    $print_out = eregi_replace("\[cp_theme:([_a-zA-Z0-9\]*)\]", !defined("T_PATH") ? "<?php define(\"T_PATH\",\"\\1\"); ?>" : '', $print_out);
    $print_out = preg_replace ("[\[cp:pagename\]]", $GLOBALS['globals']->cp_settings("Seiten_Name"), $print_out);

    //  !ia?aienaii
    if ($GLOBALS['config']['doc_rewrite']) {
      $urlPref = strtr($_SERVER['HTTP_HOST'], array('http://'=>'', 'www.'=>''));
      $print_out = preg_replace ("[\[cp:document\]]", 'http://www.' . $urlPref . $_REQUEST['urldetectUrlDelPrint'], $print_out);
    } else {
      $print_out = preg_replace ("[\[cp:document\]]", str_replace("&amp;print=1", "", redir()), $print_out);
    }
    $print_out = preg_replace ("[\[cp:home\]]", @homelink(), $print_out);

    if (isset($_REQUEST['module']) && $_REQUEST['module'] == 'shop' && !isset($_REQUEST['product_id'])) {
      $print_out = preg_replace ("[\[cp:keywords\]]", @$row->ShopKeywords, $print_out);
      $print_out = preg_replace ("[\[cp:description\]]", @$row->ShopDescription, $print_out);

    } elseif (isset($_REQUEST['module']) && $_REQUEST['module'] == 'shop' && isset($_REQUEST['product_id'])) {
      $print_out = preg_replace ("[\[cp:keywords\]]", @$row->ProdKeywords, $print_out);
      $print_out = preg_replace ("[\[cp:description\]]", @$row->ProdDescription, $print_out);

    } else {
      $print_out = preg_replace ("[\[cp:keywords\]]", @$row->MetaKeywords, $print_out);
      $print_out = preg_replace ("[\[cp:description\]]", @$row->MetaDescription, $print_out);
    }

    $print_out = preg_replace ("[\[cp:indexfollow\]]", @$row->IndexFollow, $print_out);
    $print_out = preg_replace ("[\[cp:mediapath\]]", "/templates/" . T_PATH . "/", $print_out);


    $allowed_read = (isset($_SESSION[RUB_ID . "_docread"]) && $_SESSION[RUB_ID . "_docread"] == 1) ? true : false;

    if($allowed_read==false) $print_out = str_replace  ("[cp:maincontent]", $this->fetchSettings("FehlerLeserechte"), $print_out);

    if(!isset($_REQUEST['module'])) $print_out = str_replace  ("[cp:maincontent]", $template_to_fetch, $print_out);
	$print_out = str_replace  ("[cp:cloudtags]", $this->parse_cloud_tags(), $print_out);
	//Smarty template
    $print_out = eregi_replace("\[cpsmarty:([\._a-zA-Z0-9\]*)\]", (function_exists("cp_parse_template") ? "<?php cp_parse_template(\"\\1\", ".$rub_id."); ?>" : ''), $print_out);
	
    $print_out = eregi_replace("\[cprequest:([_a-zA-Z0-9\]*)\]", (function_exists("cp_parse_request") ? "<?php cp_parse_request(\"\\1\"); ?>" : ''), $print_out);

    $get_module = array();
    if (isset($_SESSION['InstallModules']) && $_SESSION['InstallModules'] != '') {
      foreach ($_SESSION['InstallModules'] as $row_modul) {
        if(!include_once(BASE_DIR . '/modules/' . $row_modul->ModulPfad . '/modul.php')) {
          $print_out = eregi_replace($row_modul->CpEngineTag, $this->_module_error . " &quot;$row_modul->ModulName&quot;", $print_out);

        } else {
          @array_push($get_module, $row_modul->ModulFunktion);
          $print_out = eregi_replace($row_modul->CpEngineTag, (($row_modul->IstFunktion==1) ? ((function_exists($row_modul->ModulFunktion)) ? $row_modul->CpPHPTag : $this->_module_error . " &quot;$row_modul->ModulName&quot;") : ''), $print_out);
        }
      }
    } else {
      $sql_modul = $GLOBALS['db']->Query("SELECT ModulName,ModulPfad,Template,Status,CpEngineTag,CpPHPTag,ModulFunktion,IstFunktion FROM " . PREFIX. "_module WHERE Status = '1'");
      $InstallModules = array();
      while ($row_modul = $sql_modul->FetchRow()) {
        if(!include_once(BASE_DIR . '/modules/' . $row_modul->ModulPfad . '/modul.php')) {
          $print_out = eregi_replace($row_modul->CpEngineTag, $this->_module_error . " &quot;$row_modul->ModulName&quot;", $print_out);
        } else {
          @array_push($InstallModules, $row_modul);
          @array_push($get_module, $row_modul->ModulFunktion);
          $print_out = eregi_replace($row_modul->CpEngineTag, (($row_modul->IstFunktion==1) ? ((function_exists($row_modul->ModulFunktion)) ? $row_modul->CpPHPTag : $this->_module_error . " &quot;$row_modul->ModulName&quot;") : ''), $print_out);
        }
      }
      $_SESSION['InstallModules'] = $InstallModules;
    }

    if (@in_array('cpComment', $get_module)) $_SESSION['comments_enable'] = 1; else unset($_SESSION['comments_enable']);
    if(defined('MODULE_CONTENT')) {
      $print_out = str_replace  ("[cp:maincontent]", MODULE_CONTENT, $print_out);
      $print_out = preg_replace ("[\[cp_doc:titel\]]", (defined("MODULE_SITE") ? MODULE_SITE : ""), $print_out);
      $print_out = preg_replace ("[\[cp:printlink\]]", ((strpos(@redir(),"?")===false) ? redir() . "?print=1" : redir() . "&amp;print=1"), $print_out);
    } else {
      $print_out = preg_replace ("[\[cp_doc:titel\]]", cp_parse_string($row->Titel), $print_out);
      $print_out = preg_replace ("[\[cp:printlink\]]", @cp_print(), $print_out);
    }


    if(isset($_REQUEST['print']) && $_REQUEST['print'] == 1) {
      $print_out = str_replace (array('[cp:if_print]','[/cp:if_print]'), '', $print_out);
      $print_out = preg_replace("/\[cp:donot_print\](.*?)\[\/cp:donot_print\]/si",'', $print_out);
    } else {
      $print_out = preg_replace("/\[cp:if_print\](.*?)\[\/cp:if_print\]/si","", $print_out);
      $print_out = str_replace  (array('[cp:donot_print]','[/cp:donot_print]'), '', $print_out);
    }

    $print_out = (CP_REWRITE == 1) ? cp_rewrite($print_out) : $print_out;
    $print_out = str_replace  ('[cp:maincontent]', '', $print_out);
    $print_out = str_replace  ('[cp:version]', CO_INFO, $print_out);
    $print_out = str_replace ("[views]", @$row->Geklickt, $print_out);

    $print_out = stripslashes(hide($print_out));
    if(isset($_REQUEST['output']) && $_REQUEST['output']=='false') {
    } else {
      echo $print_out;
    }
  }
}
?>