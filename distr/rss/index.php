<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

// Подрубаем основные классы
include_once ('../class/class.globals.php');
include_once ('../class/class.database.php');
include_once ('../inc/db.config.php');

// Определяем константу префикса
define('PREFIX', $config['dbpref']);

// Создаем объекты по работы с БД
$globals = new Globals;
$db = new DB($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
@mysql_query('SET NAMES cp1251');
// Получаем входной id канала
$id = (int)$_GET['id'];

// Выполняем запрос к БД и выгребаем все параметры для данного канала
$sql = $db->Query("SELECT rss.*, RubrikName
  FROM
    " . PREFIX . "_modul_rss AS rss
  LEFT JOIN
    " . PREFIX . "_rubrics AS rub ON rub.Id = rss.rub_id
  WHERE
    rss.id = '" . $id . "'
  ");
$rss_setting = $sql->fetchrow();

// Выполняем запрос к БД и выгребаем ID, URL  и Дату публикации для документов, которые соответсвуют нашей рубрики
// Количество выборки ограничиваем значением установленным для канала
$get_doc_id = $db->Query("SELECT Id, Url, DokStart
  FROM
    " . PREFIX . "_documents
  WHERE RubrikId = " . $rss_setting->rub_id . "
    AND Id != '1'
    AND Id != '2'
    AND DokStatus = '1'
    AND (DokStart < '" . time() . "' OR DokStart = '0')
    AND (DokEnde  > '" . time() . "' OR DokEnde  = '0')
    AND Geloescht != '1'
  ORDER BY DokStart DESC, Id DESC
  LIMIT 0," . $rss_setting->on_page);

// Формируем массивы, которые будут хранить инфу
$rss_item  = array();
$rss_items = array();

// Выполянем обработку полученных из БД данных
while($res = $get_doc_id->fetchrow()) {
  $get_fields = $db->Query("SELECT RubrikFeld, Inhalt
    FROM
      " . PREFIX . "_document_fields
    WHERE DokumentId = '" . $res->Id . "'
      AND (RubrikFeld = '" . $rss_setting->title_id . "' OR
           RubrikFeld = '" . $rss_setting->descr_id . "')
    ");
  while ($f = $get_fields->fetchrow()) {
    // В этой проверке убиваем конченный тег [cp:replacement], чтобы его небыло при выводе
    $f->Inhalt = str_replace("[cp:replacement]", "/", $f->Inhalt);

    if ($f->RubrikFeld == $rss_setting->title_id) {
      $rss_item['Title'] = $f->Inhalt;
    }

    if ($f->RubrikFeld == $rss_setting->descr_id) {
      if (strlen($f->Inhalt) > $rss_setting->lenght) {
        $rss_item['Description'] = substr($f->Inhalt, 0, $rss_setting->lenght) . '...';
      } else {
        $rss_item['Description'] = $f->Inhalt;
      }
    }
  }
  $rss_item['Url'] = $res->Url;
  $rss_item['DataDoc'] = ($res->DokStart == 0) ? date("r", time()) : date("r", $res->DokStart);

  array_push($rss_items, $rss_item);
}

// Ну а тут собственно шлем заголовок, что у нас документ XML и в путь... выводим данные
header("Content-Type: application/xml");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
echo "<?xml version=\"1.0\" encoding=\"windows-1251\"?>\n";
echo "<rss version=\"2.0\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\">\n";
echo "<channel>\n";
echo "<title>" . $rss_setting->rss_name . "</title>\n";
echo "<link>http://" . $rss_setting->site_url . "/</link>\n";
echo "<language>ru-ru</language>\n";
echo "<description>" . $rss_setting->rss_descr . "</description>\n";
echo "<category><![CDATA[" . $rss_setting->RubrikName . "]]></category>\n";
echo "<generator>AVECMS 2.0</generator>\n";
foreach ($rss_items as $rss_item) {
  echo "\n<item>\n";
  echo "  <title><![CDATA[" . $rss_item['Title'] . "]]></title>\n";
  echo "  <guid isPermaLink=\"true\">http://" . $rss_setting->site_url . $rss_item['Url'] . "</guid>\n";
  echo "  <link>http://" . $rss_setting->site_url . $rss_item['Url'] . "</link>\n";
  echo "  <description><![CDATA[" . $rss_item['Description'] . "]]></description>\n";
  echo "  <pubDate>" . $rss_item['DataDoc'] . "</pubDate>\n";
  echo "</item>\n";
}
echo "\n</channel>\n";
echo "</rss>";
?>