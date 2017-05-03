<?php
function DownloadRewrite($print_out)
{
	$print_out = preg_replace('/index.php([?])module=download&amp;action=showfile&amp;file_id=([0-9]*)&amp;categ=([0-9]*)/', 'download,\\2,\\3.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=download&amp;action=get_file&amp;file_id=([0-9]*)&amp;pop=1&amp;cp_theme=([_a-zA-Z0-9]*)/', 'download_file,\\2,\\3.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=download&amp;action=get_nopay_file&amp;file_id=([0-9]*)&amp;pop=1&amp;cp_theme=([_a-zA-Z0-9]*)/', 'nopay_file,\\2,\\3.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=download&amp;action=get_notmine_file&amp;file_id=([0-9]*)&amp;pop=1&amp;cp_theme=([_a-zA-Z0-9]*)/', 'notmine_file,\\2,\\3.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=download&amp;action=get_nouserpay_file&amp;diff=([0-9,]*)&amp;val=([0-9]*)&amp;file_id=([0-9]*)&amp;pop=1&amp;cp_theme=([_a-zA-Z0-9]*)/', 'nouserpay_file,\\2,\\3,\\4,\\5.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=download&amp;action=pay&amp;file_id=([0-9]*)&amp;pop=0&amp;cp_theme=([_a-zA-Z0-9]*)/', 'pay,\\2,\\3.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=download&amp;action=toreg&amp;file_id=([0-9]*)&amp;pop=1&amp;cp_theme=([_a-zA-Z0-9]*)/', 'toreg,\\2,\\3.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=download&amp;action=get_denied&amp;file_id=([0-9]*)&amp;pop=1&amp;cp_theme=([_a-zA-Z0-9]*)/', 'denied,\\2,\\3.htm', $print_out);
	
	$print_out = preg_replace('/index.php([?])module=download&amp;categ=([0-9]*)&amp;parent=([0-9]*)&amp;navop=([0-9]*)&amp;c=([_a-zA-Z0-9]*)&amp;page=([}{_a-zA-Z0-9]*)&amp;orderby=([_a-zA-Z0-9]*)/', 'download_kategorie,\\2,\\3,\\4,\\5,\\6,\\7.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=download&amp;categ=([0-9]*)&amp;parent=([0-9]*)&amp;navop=([0-9]*)&amp;c=([_a-zA-Z0-9]*)&amp;page=([}{_a-zA-Z0-9]*)/', 'download_kategorie,\\2,\\3,\\4,\\5,\\6.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=download&amp;categ=([0-9]*)&amp;parent=([0-9]*)&amp;navop=([0-9]*)&amp;c=([_a-zA-Z0-9]*)/', 'download_kategorie,\\2,\\3,\\4,\\5.htm', $print_out);

	$print_out = preg_replace('/index.php([?])module=download&amp;manufacturer=([0-9]*)/', 'hersteller,\\2.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=download&amp;action=showbasket/', 'warenkorb.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=download&amp;action=checkout/', 'kasse.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=download&amp;action=myorders/', 'meine_bestellungen.htm', $print_out);
	$print_out = preg_replace('/index.php([?])module=download&amp;action=mydownloads/', 'meine_downloads.htm', $print_out);

	$print_out = preg_replace('/index.php([?])module=download/', 'downloads.htm', $print_out);
	$print_out = str_replace(".htm&amp;print=1", ",print.htm", $print_out);
	
	return $print_out;
}
?>