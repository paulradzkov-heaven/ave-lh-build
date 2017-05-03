<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function getParentShopcateg($param='')
{
global $db;
	$id = (is_array($param)) ? $param['id'] : $param ;
	
	$parent_id = $id;
	$id = 0;
	while($parent_id != 0)
	{
		$sql = $db->Query("SELECT Elter,Id FROM ".PREFIX."_modul_shop_kategorie WHERE Id='".$parent_id."'");
		
		$row = $sql->fetchrow();
		@$parent_id = $row->Elter;
		@$id = $row->Id;
	}
	return($id);
}
?>