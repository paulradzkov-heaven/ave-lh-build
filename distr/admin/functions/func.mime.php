<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function getMimeTyp($file) {
  $file_extension = strtolower(substr(strrchr($file,"."),1));

  switch ($file_extension) {
    case 'psd': $ctype = "image/x-photoshop"; break;
    case 'rar': $ctype = "application/x-rar-compressed"; break;
    case 'zip': $ctype = "application/x-zip-compressed"; break;
    case 'pdf': $ctype = "application/pdf"; break;
    case 'bz2': $ctype = "application/bzip2"; break;
    case 'doc':
    case 'dot':
    case 'wiz':
    case 'wzs': $ctype = "application/msword"; break;
    case 'eps': $ctype = "application/postscript"; break;
    case 'pot':
    case 'ppa':
    case 'pps':
    case 'ppt':
    case 'pwz': $ctype = "application/vnd.ms-powerpoint"; break;
    case 'rtf': $ctype = "application/rtf"; break;
    case 'rnx': $ctype = "application/vnd.rn-realmedia"; break;
    case 'hlp': $ctype = "hlp"; break;
    case 'gtar': $ctype = "application/x-gtar"; break;
    case 'gzip':
    case 'tgz': $ctype = "application/x-gzip"; break;
    case 'lnx': $ctype = "application/x-latex"; break;
    case 'exe': $ctype = "application/x-msdownload"; break;
    case 'swf': $ctype = "application/x-shockwafe-flash"; break;
    case 'xml': $ctype = "application/xml"; break;
    case 'midi': $ctype = "audio/midi"; break;
    case 'mp3':
    case 'mp2':
    case 'mpga': $ctype = "audio/mpeg"; break;
    case 'wav': $ctype = "audio/wav"; break;
    case 'bmp': $ctype = "audio/wav"; break;
    case 'gif': $ctype = "image/gif"; break;
    case 'jpeg':
    case 'jpg':
    case 'jpe': $ctype = "image/jpeg"; break;
    case 'png': $ctype = "image/png"; break;
    case 'tif':
    case 'tiff': $ctype = "image/tiff"; break;
    case 'ico': $ctype = "image/x-icon"; break;
    case 'csv': $ctype = "text/comma-separated-values"; break;
    case 'css': $ctype = "text/css"; break;
    case 'htm':
    case 'html':
    case 'shtml': $ctype = "text/html"; break;
    case 'txt':
    case 'klp':
    case 'tex':
    case 'php':
    case 'asp':
    case 'aspx':
    case 'php3':
    case 'php4':
    case 'php5':
    case 'sql': $ctype = "text/plain"; break;
    case 'xml': $ctype = "text/xml"; break;
    case 'xhtm': $ctype = "text/xhtml"; break;
    case 'wml': $ctype = "text/wml"; break;
    case 'mpeg':
    case 'mpg':
    case 'mpe':
    case 'mlv':
    case 'mpa':
    case 'wma':
    case 'wmv': $ctype = "video/mpeg"; break;
    case 'avi': $ctype = "video/x-msvideo"; break;
    case 'mov': $ctype = "video/quicktime"; break;
    case 'xls': $ctype = "application/vnd.ms-excel"; break;
    case 'ai': $ctype = "application/postscript"; break;
    case 'rm': $ctype = "application/vnd.rn-realmedia"; break;
    case 'gz': $ctype = "application/x-gzip"; break;
    case 'js': $ctype = "application/x-javascript"; break;
    case 'pl':
    case 'cc': $ctype = "text/plain"; break;
    case 'qt': $ctype = "video/quicktime"; break;
    default : $ctype="application/force-download";
  }
  return $ctype;
}
?>