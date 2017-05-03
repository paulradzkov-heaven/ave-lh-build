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
    echo '&raquo;&nbsp;<a href="#" onclick="clickUrlCheck(); return false;" class="checkUrl">Проверить</a> <font class="checkUrlErr">недопустимые символы</font>';
    exit();
  }

  if ($url[0] != '/') {
    echo '&raquo;&nbsp;<a href="#" onclick="clickUrlCheck(); return false;" class="checkUrl">Проверить</a> <font class="checkUrlErr">ссылка должна начинаться с /</font>';
    exit();
  }

  if (strpos($url, '/index.php') || strpos($url, '/index.html') || strpos($url, '/print.html')) {
    echo '&raquo;&nbsp;<a href="#" onclick="clickUrlCheck(); return false;" class="checkUrl">Проверить</a> <font class="checkUrlErr">запрещеные окончания</font>';
    exit();
  }

  mysql_connect($config['dbhost'], $config['dbuser'], $config['dbpass']) or die("Не могу соединиться с БД<br>: " . mysql_error());
  mysql_select_db($config['dbname']) or die("Не могу соединиться с БД<br>: " . mysql_error());

  $query = mysql_query("SELECT Id FROM " . $config['dbpref'] . "_documents WHERE Url = '" . $_POST['url'] . "'");
  $result = mysql_num_rows($query);

  if ($result) {
    echo '&raquo;&nbsp;<a href="#" onclick="clickUrlCheck(); return false;" class="checkUrl">Проверить</a> <font class="checkUrlErr">уже существует</font>';
  } else {
    echo '&raquo;&nbsp;<a href="#" onclick="clickUrlCheck(); return false;" class="checkUrl">Проверить</a> <font class="checkUrlOk">можно использовать</font>';
  }
  exit();

} else {
  echo '&raquo;&nbsp;<a href="#" onclick="clickUrlCheck(); return false;" class="checkUrl">Проверить</a> <font class="checkUrlErr">введите ссылку</font>';
  exit();
}
?>