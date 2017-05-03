<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

function shopRewrite($print_out)
{
//	$print_out = preg_replace('/index.php([?])module=shop&amp;action=product_detail&amp;product_id=([0-9]*)&amp;categ=([0-9]*)&amp;navop=([0-9]*)/', 'produkt,\\2,\\3,\\4.htm', $print_out);
//	$print_out = preg_replace('/index.php([?])module=shop&amp;categ=([0-9]*)&amp;parent=([0-9]*)&amp;navop=([0-9]*)/', 'produkt_kategorie,\\2,\\3,\\4.htm', $print_out);
//	$print_out = preg_replace('/index.php([?])module=shop&amp;manufacturer=([0-9]*)/', 'hersteller,\\2.htm', $print_out);
//	$print_out = preg_replace('/index.php([?])module=shop&amp;action=showbasket/', 'warenkorb.htm', $print_out);
//	$print_out = preg_replace('/index.php([?])module=shop&amp;action=checkout/', 'kasse.htm', $print_out);
//	$print_out = preg_replace('/index.php([?])module=shop&amp;action=myorders/', 'meine_bestellungen.htm', $print_out);
//	$print_out = preg_replace('/index.php([?])module=shop&amp;action=mydownloads/', 'meine_downloads.htm', $print_out);
//	//$print_out = preg_replace('/index.php([?])module=shop/', 'shop.htm', $print_out);
//	$print_out = str_replace(".htm&amp;print=1", ",print.htm", $print_out);
	
	$print_out = preg_replace('/index.php([?])module=shop&amp;action=product_detail&amp;product_id=([0-9]*)&amp;categ=([0-9]*)&amp;navop=([0-9]*)/', 'product-\\2-\\3-\\4.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=shop&amp;categ=([0-9]*)&amp;parent=([0-9]*)&amp;navop=([0-9]*)/', 'category-\\2-\\3-\\4.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=shop&amp;manufacturer=([0-9]*)/', 'manufacturer-\\2.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=shop&amp;action=showbasket/', 'basket.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=shop&amp;action=checkout/', 'checkout.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=shop&amp;action=myorders/', 'my_orders.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=shop&amp;action=mydownloads/', 'my_downloads.htm', $print_out);
	//$print_out = preg_replace('/index.php([?])module=shop/', 'shop.htm', $print_out);
	$print_out = str_replace(".htm&amp;print=1", "-print.htm", $print_out);
	
	return $print_out;
}
?>