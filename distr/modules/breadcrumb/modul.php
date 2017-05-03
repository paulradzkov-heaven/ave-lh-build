<?php


if(!defined('BASE_DIR')) exit;

$modul['ModulName'] = "Хлебные крошки";
$modul['ModulPfad'] = "breadcrumb";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "Данный модуль предназначен для добавления на сайт пути к документу (хлебные крошки). Для того, чтобы модуль работал, необходимо для каждого документа задавать родительский документ";
$modul['Autor'] = "rez";
$modul['MCopyright'] = "&copy; 2009 Rezerford";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['ModulFunktion'] = "cpBreadcrumb";
$modul['CpEngineTagTpl'] = "[cp:breadcrumb]";
$modul['CpEngineTag'] = "\\\[cp:breadcrumb\\\]";
$modul['CpPHPTag'] = "<?php cpBreadcrumb(); ?>";


function cpBreadcrumb() {
  
  	$id = 1;
  	$noprint = 0;
	$bread_crumb = "<a href=\"/\"  target=\"_self\">Главная</a>&nbsp;&raquo;&nbsp;";
	$par = array();
  	$r_id   = (isset($_GET['id']) && is_numeric($_GET['id']) ) ? (int)$_GET['id'] : $id;
  	$refurl = 'index.php?id=' . $r_id;
  	
	if ($r_id == 1|| $r_id == 2) $noprint = 1;
	
	$sql_edoc = $GLOBALS['db']->Query("SELECT Titel, ParentId FROM " . PREFIX . "_documents WHERE Id = '".$r_id."'");
    $row_edoc = $sql_edoc->fetchrow();
    $sql_edoc->Close();
    $current['Title'] = $row_edoc->Titel;
    if (isset($row_edoc->ParentId)&&$row_edoc->ParentId!=0){
    
    $i = 0;
    
    $current['ParentId'] = $row_edoc->ParentId;
    while ($current['ParentId']!=0){
        $sql_doc = $GLOBALS['db']->Query("SELECT Id, Url, Titel, ParentId FROM " . PREFIX . "_documents WHERE Id = '".$current['ParentId']."'");
        $row_doc = $sql_doc->fetchrow();
		$current['ParentId'] = $row_doc->ParentId;
        if ($row_doc->ParentId == $row_doc->Id){
            echo "Ошибка! Вы указали в качестве родительского документа текущий документ.<br>";
            $current['ParentId'] = 1;
        }
        $par['Title'][$i] = $row_doc->Titel;
        $par['Url'][$i] = $row_doc->Url;
        $par['Id'][$i] = $row_doc->Id;
        $i++;
      
    }
	
	$length = count($par['Title']);
	$par['Title'] = array_reverse($par['Title']);
	$par['Url'] = array_reverse($par['Url']);
	$par['Id'] = array_reverse($par['Id']);
	
	for ($n=0; $n < $length; $n++){
		$url = (isset($par['Url'][$n])&&$par['Url'][$n]!='') ? $par['Url'][$n] : "index.php?id=".$par['Id'];
		$bread_crumb.= "<a href=\"".$url."\"  target=\"_self\">".$par['Title'][$n]."</a>&nbsp;&raquo;&nbsp;";
	}
	
	}					
	$bread_crumb.= "<span>".$current['Title']."</span>";
	
	 if (!$noprint) echo $bread_crumb;
								
}


?>