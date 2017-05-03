<?php
$valid = 60*60;
$filename = dirname(__FILE__) . '/yamarket.xml';

if ( (@$file = fopen($filename, 'r'))
  && filemtime($filename) > (time() - $valid)
  && (!isset($_GET['save']) || $_GET['save'] == '') ) {

  flock($file, LOCK_SH);
  $XML = file_get_contents($filename);
  fclose($file);

} else {
  $XML = CreateXML();
}

if (isset($_GET['save']) && $_GET['save'] != '') {
  switch ($_GET['save']) {
    case 'all' :
      saveInXML($filename, $XML);
      saveInGZ($filename . '.gz', $XML);
    break;

    case 'xml' :
      saveInXML($filename, $XML);
    break;

    case 'gz' :
      saveInGZ($filename . '.gz', $XML);
    break;
  }
}

header('Content-type: text/xml');
print_r($XML);

function CreateXML() {
  $shopName    = 'Site name';
  $shopCompany = 'Name company';
  $shopUrl     = 'http://cpe-over';

  include_once "class/class.database.php";
  include_once "inc/db.config.php";

  define('PREFIX', $config['dbpref']);
  $Db = new DB($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

  $date = date("Y-m-d H:i");
  $XML .= <<<xml
<?xml version="1.0" encoding="windows-1251"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="{$date}">
  <shop>
    <name>{$shopName}</name>
    <company>{$shopCompany}</company>
    <url>{$shopUrl}</url>
    <currencies>
      <currency id="RUR" rate="1"/>
      <currency id="USD" rate="CBRF" plus="3"/>
      <currency id="EUR" rate="CBRF" plus="3"/>
      <currency id="UAH" rate="CBRF" plus="3"/>
    </currencies>
    <categories>\n
xml;

  $categories_result = $Db->Query("SELECT Id, Elter AS parentId, KatName AS category FROM " . PREFIX . "_modul_shop_kategorie");
  if ($categories_result) {
    while ($row = $categories_result->FetchAssocArray()) {
      $category = htmlspecialchars(htmlspecialchars_decode(trim($row['category'])));
      if ($row['parentId']) {
        $XML .= <<<xml
      <category id="{$row['Id']}" parentId="{$row['parentId']}">{$category}</category>\n
xml;
      } else {
        $XML .= <<<xml
      <category id="{$row['Id']}">{$category}</category>\n
xml;
      }
    }
  }
  $XML .= <<<xml
    </categories>
    <offers>\n
xml;

  $offers_result = $Db->Query("SELECT
      art.Id,
      TextLang AS description,
      Lager    AS available,
      KatId    AS categoryId,
      Preis    AS price,
      Bild     AS picture,
      ArtName  AS name,
      ArtNr    AS vendorCode,
      Name     AS vendor
    FROM
      " . PREFIX . "_modul_shop_hersteller AS vend
    LEFT JOIN
      " . PREFIX . "_modul_shop_artikel AS art ON vend.Id = Hersteller
    WHERE
      Aktiv = '1'
    ");

  if ($offers_result) {
    while ($row = $offers_result->FetchAssocArray()) {
      $available = ($row['available'] > 0) ? 'true' : 'false';
      $XML_Offer .= <<<xml
      <offer id="{$row['Id']}" available="{$available}">
        <url>{$shopUrl}/index.php?module=shop&amp;action=product_detail&amp;product_id={$row['Id']}&amp;categ={$row['categoryId']}&amp;navop={$row['categoryId']}</url>
        <price>{$row['price']}</price>
        <currencyId>EUR</currencyId>
        <categoryId>{$row['categoryId']}</categoryId>\n
xml;
      if ($row['picture'])
        $XML_Offer .= <<<xml
        <picture>{$shopUrl}/modules/shop/uploads/{$row['picture']}</picture>\n
xml;
      $name = htmlspecialchars(htmlspecialchars_decode(trim($row['name'])));
      $XML_Offer .= <<<xml
        <delivery>true</delivery>
        <name>{$name}</name>\n
xml;
      if ($row['vendor']) {
        $vendor = htmlspecialchars(htmlspecialchars_decode(trim($row['vendor'])));
        $XML_Offer .= <<<xml
        <vendor>{$vendor}</vendor>\n
xml;
      }
      $vendorCode = htmlspecialchars(htmlspecialchars_decode(trim($row['vendorCode'])));
      $XML_Offer .= <<<xml
        <vendorCode>{$vendorCode}</vendorCode>\n
xml;
      if ($row['description']) {
        $description = preg_replace("/(\<.*?\>)/", " ", $row['description']);
        $description = strip_tags($description, '<b><br><br /><br/><BR><BR /><BR/>');
        $description = htmlspecialchars(htmlspecialchars_decode(trim($description)));
        $XML_Offer .= <<<xml
        <description>{$description}</description>\n
xml;
      } else {
        $XML_Offer .= <<<xml
        <description></description>\n
xml;
      }
      $XML_Offer .= <<<xml
      </offer>\n
xml;
      $XML .= $XML_Offer;
      $XML_Offer = '';
    }
  }

  $XML .= <<<xml
    </offers>
  </shop>
</yml_catalog>\n
xml;

  return $XML;
}

function saveInXML($filename, $contents) {
  $file = @fopen($filename, 'w');
  if ($file) {
    flock($file, LOCK_EX);
    fputs($file, $contents);
    fclose($file);
  }
}

function saveInGZ($filename, $contents) {
  $file = gzopen($filename, 'w9');
  if ($file) {
    flock($file, LOCK_EX);
    gzwrite($file, $contents);
    gzclose($file);
  }
}
?>