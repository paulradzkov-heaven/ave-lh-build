<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function reportLog($meldung, $typ='0', $rub='0') {
  
  $sql = $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_log (
      Id, 
      Zeit, 
      IpCode, 
      Seite, 
      Meldung,
      LogTyp,
      Rub
    ) VALUES (
      '',
      '" . time() . "',
      '" . $_SERVER['REMOTE_ADDR'] . "',
      '" . $_SERVER['QUERY_STRING'] . "',
      '" . addslashes($meldung) . "',
      '$typ',
      '$rub'
    )
    ");
    $sql->Close();
}
?>