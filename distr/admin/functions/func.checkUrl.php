<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

header("Content-Type: text/html;charset=windows-1251");

include_once("../../inc/db.config.php");

$url = (isset($_POST['url']))  ?  $_POST['url']  : 0;

if ($url) {
  if(!preg_match("/^[-.0-9a-z_\/]+$/", $url)) {
    echo '&raquo;&nbsp;<a href="#" onclick="clickUrlCheck(); return false;" class="checkUrl">���������</a> <font class="checkUrlErr">������������ �������</font>';
    exit();
  }

  if ($url[0] != '/') {
    echo '&raquo;&nbsp;<a href="#" onclick="clickUrlCheck(); return false;" class="checkUrl">���������</a> <font class="checkUrlErr">������ ������ ���������� � /</font>';
    exit();
  }

  if (strpos($url, '/index.php') || strpos($url, '/index.html') || strpos($url, '/print.html')) {
    echo '&raquo;&nbsp;<a href="#" onclick="clickUrlCheck(); return false;" class="checkUrl">���������</a> <font class="checkUrlErr">���������� ���������</font>';
    exit();
  }

  mysql_connect($config['dbhost'], $config['dbuser'], $config['dbpass']) or die("�� ���� ����������� � ��<br>: " . mysql_error());
  mysql_select_db($config['dbname']) or die("�� ���� ����������� � ��<br>: " . mysql_error());

  $query = mysql_query("SELECT Id FROM " . $config['dbpref'] . "_documents WHERE Url = '" . $_POST['url'] . "'");
  $result = mysql_num_rows($query);

  if ($result) {
    echo '&raquo;&nbsp;<a href="#" onclick="clickUrlCheck(); return false;" class="checkUrl">���������</a> <font class="checkUrlErr">��� ����������</font>';
  } else {
    echo '&raquo;&nbsp;<a href="#" onclick="clickUrlCheck(); return false;" class="checkUrl">���������</a> <font class="checkUrlOk">����� ������������</font>';
  }
  exit();

} else {
  echo '&raquo;&nbsp;<a href="#" onclick="clickUrlCheck(); return false;" class="checkUrl">���������</a> <font class="checkUrlErr">������� ������</font>';
  exit();
}
?>