<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function cpReadfile($filename,$retbytes=true) {
    $chunksize = 1*(1024*1024);
    $buffer = '';
    $cnt =0;

    $handle = fopen($filename, 'rb');

    if ($handle === false) {
      return false;
    }

    while (!feof($handle)) {
      $buffer = fread($handle, $chunksize);
      echo $buffer;
      flush();
      if ($retbytes) {
        $cnt += strlen($buffer);
      }
    }

    $status = fclose($handle);

    if ($retbytes && $status) {
      return $cnt;
    }

    return $status;
  }
?>