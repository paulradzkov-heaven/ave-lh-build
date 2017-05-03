<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function cntStat(){
	$cnts = array();

	$sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_templates");
	$cnts['templates'] = $sql->numrows();

	$sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_queries");
	$cnts['queries'] = $sql->numrows();

	$sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_documents");
	$cnts['documents'] = $sql->numrows();

	$sql = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_rubrics");
	$cnts['rubrics'] = $sql->numrows();

	$sql = $GLOBALS['db']->Query("SELECT Status,COUNT(Status) AS cntStatus FROM " . PREFIX . "_module GROUP BY Status");
	while($row = $sql->fetchrow()) $cnts['modules_'.$row->Status] = $row->cntStatus;

	$sql = $GLOBALS['db']->Query("SELECT Status,COUNT(Status) AS cntStatus FROM " . PREFIX . "_users GROUP BY Status");
	while($row = $sql->fetchrow()) $cnts['users_'.$row->Status] = $row->cntStatus;

	$GLOBALS['tmpl']->assign('cnts', $cnts);
}
?>