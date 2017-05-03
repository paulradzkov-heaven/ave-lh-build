<?php
if(!defined('BASE_DIR')) exit;

$modul = array();
$modul['ModulName'] = "WhoisOnline";
$modul['ModulPfad'] = "whoisonline";
$modul['ModulVersion'] = "1.0";
$modul['Beschreibung'] = "........................";
$modul['Autor'] = "cron";
$modul['MCopyright'] = "&copy; 2008 cron";
$modul['Status'] = 1;
$modul['IstFunktion'] = 1;
$modul['AdminEdit'] = 1;
$modul['ModulFunktion'] = "cpWOnline";
$modul['CpEngineTagTpl'] = "[cp:wonline]";
$modul['CpEngineTag'] = "\\\[cp:wonline\\\]";
$modul['CpPHPTag'] = "<?php cpWOnline(); ?>";

if(defined('ACP')){
	$modul_sql_update = array();
	$modul_sql_deinstall = array();
	$modul_sql_install = array();
	include_once(BASE_DIR . '/modules/whoisonline/sql.php');
}
include_once(BASE_DIR . '/functions/func.modulglobals.php');

function cpWOnline() {

}

function get_user_ip() {
	//определяем истиный IP человека.
	$strRemoteIP = $_SERVER['REMOTE_ADDR'];

	if (!$strRemoteIP) {
		$strRemoteIP = urldecode(getenv('HTTP_CLIENTIP'));
	}

	if (getenv('HTTP_X_FORWARDED_FOR')) {
		$strIP = getenv('HTTP_X_FORWARDED_FOR');

	} elseif (getenv('HTTP_X_FORWARDED')) {
		$strIP = getenv('HTTP_X_FORWARDED');

	} elseif (getenv('HTTP_FORWARDED_FOR')) {
		$strIP = getenv('HTTP_FORWARDED_FOR');

	} elseif (getenv('HTTP_FORWARDED')) {
		$strIP = getenv('HTTP_FORWARDED');

	} else {
		$strIP = $_SERVER['REMOTE_ADDR'];
	}

	if ($strRemoteIP != $strIP) {
		$strIP = $strRemoteIP . ', ' . $strIP;
	}

	return $strIP;
}

function SplitText($s) {
	if (strlen($s)>70) for ($i=50; $i<strlen($s); $i++) if ($s[$i]=='&') break;
	$first = substr($s,0,$i);
	$second = substr($s,$i);
	return $first.'<br />'.$second;
}

//ОПРЕДЕЛИМ НУЖНЫЕ ПАРАМЕТРЫ, ЗАБЬЕМ ИХ В ТАБЛИЦУ.
if(empty($_GET['swtch'])) {
	$ip = get_user_ip();

	if (empty($_SESSION['cp_uname'])) {
		$usr = 'Guest';

	} else {
		$usr = $_SESSION['cp_uname'];
	}

	if (empty($_SESSION['cp_email'])) {
		$mail = '';

	} else {
		$mail = $_SESSION['cp_email'];
	}

	$page = getenv("REQUEST_URI");
	$referer = getenv("HTTP_REFERER");
	$agent = getenv("HTTP_USER_AGENT");
	$GLOBALS['db']->Query("INSERT INTO `". PREFIX ."_modul_wonline` (User,Email,Ip,Page,Referer,UserAgent,Time) VALUES ('".$usr."','".$mail."','".$ip."','".$page."','".$referer."','".$agent."',NOW())");
	$GLOBALS['db']->Query("DELETE FROM `". PREFIX ."_modul_wonline` WHERE `Time` < (NOW() - INTERVAL '3' DAY)");
}

//НУЖНО ПОСТРОИТЬ УРЛ!!!!
$uri = 'index.php?do=modules&action=modedit&mod=whoisonline&moduleaction=1&cp=' . SESSION;

//ВЫВОДИМ ИЗ БАЗЫ ТЕХ, КТО БЫЛ ЗАБИТ НЕ БОЛЬШЕ 20 МИНУТ НАЗАД.
if(defined("ACP") && $_REQUEST['action'] != 'delete') {
	//вывод таблицы по нажатию на ссылку "admin"
	$config_vars = $GLOBALS['tmpl']->get_config_vars();
	$GLOBALS['tmpl']->assign("config_vars", $config_vars);

	if(isset($_REQUEST['moduleaction']) && $_REQUEST['moduleaction'] == 1) {
		//ОНЛАЙН числом
		$tmpstr = '';
		$online = 0;
		$sql = $GLOBALS['db']->Query("SELECT * FROM `" . PREFIX . "_modul_wonline` WHERE Time > NOW() - INTERVAL '20' MINUTE ORDER BY Time DESC");
		while($row = $sql->fetchrow()) {
			$pos = strpos($tmpstr, $row->Ip);
			if($pos === false) {
				$online++;
				$tmpstr .= "::".$row->Ip;
			}
		}

		$tablstr = '<div class="pageHeaderTitle" style="padding-top: 7px;"><div class="h_module"></div><div class="HeaderTitle"><h2>Управление модулем <span style="color: #000;">&nbsp;&gt;&nbsp;WhoIsOnline</span></h2></div><div class="HeaderText">&nbsp;</div></div><br><div class="infobox">Online '.$online.' |<a href="'.$uri.'">Обновить</a> | <a href="'.$uri.'&swtch=logs">Logs</a></div><br>';
		//выводим заголовок таблицы

		if (empty($_REQUEST['swtch'])) {
			$tablstr .= '<table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder">
			<tr class="tableheader">
				<td>Пользователь</td>
				<td>E-Mail</td>
			 	<td>IP</td>
				<td>Страница</td>
				<td>Время</td>
			</tr>';

			//строим саму таблицу
			$tmpstr = '';
			$sql = $GLOBALS['db']->Query("SELECT * FROM `" . PREFIX . "_modul_wonline` WHERE Time > NOW() - INTERVAL '20' MINUTE ORDER BY Time DESC");
			while($row = $sql->fetchrow()) {
				$pos = strpos($tmpstr, $row->Ip);
				if($pos === false) {
					$tablstr .='<tr style="background-color: #eff3eb;" onmouseover=this.style.backgroundColor="#dae0d8"; onmouseout=this.style.backgroundColor="#eff3eb"; id="table_rows">
						<td><strong>'.$row->User.'</strong></td>
						<td>'.$row->Email.'</td>
						<td>'.$row->Ip.'</td>
						<td>'.SplitText($row->Page).'</td>
						<td class="time">'.$row->Time.'</td></tr>';
					$tmpstr .= "::".$row->Ip;
				}
			}

			$tablstr .= '</table>';

		} else {
			$tablstr .= '<table width="100%" border="0" cellspacing="1" cellpadding="8" class="tableborder" style="table-layout:fixed;">
			<tr class="tableheader">
				<td>Пользователь</td>
			</tr>';

			$sql = $GLOBALS['db']->Query("SELECT COUNT(*) FROM `" . PREFIX . "_modul_wonline`");
			$row = $sql->FetchArray();
			$pages = ceil($row[0]/15);
			$num = (int)$_REQUEST['p'];
			if (empty($num)) $num = 1;
			$p = ($num-1)*15;

			$sqllog = $GLOBALS['db']->Query("SELECT * FROM `" . PREFIX . "_modul_wonline` ORDER BY Time DESC LIMIT ".$p.",15");
			while($rw = $sqllog->fetchrow()) {
			$tablstr .='<tr style="background-color: #eff3eb;" onmouseover=this.style.backgroundColor="#dae0d8"; onmouseout=this.style.backgroundColor="#eff3eb"; id="table_rows"><td><b>'.$rw->User.'</b> '.$rw->Email.' ('.$rw->Ip.')<br /><b>Просматривал:</b> <a href="'.$rw->Page.'">'.$rw->Page.'</a><br /><b>Дата: </b>'.$rw->Time.'<br /><b>REFERER: </b><a href="'.$rw->Referer.'">'.$rw->Referer.'</a><br />'.$rw->UserAgent.'</td></tr>';
			}

			$tablstr .= '</table><br><div align="center" class="infobox">';

			for ($i=1; $i <= $pages; $i++) {
				if ($i!=$num) {
					$tablstr .= '<a href="'.$uri.'&swtch=logs&p='.$i.'">'.$i.'</a> ';

				} else {
					$tablstr .= $i.' ';
				}
			}
				$tablstr .='</div>';
			}

		$GLOBALS['tmpl']->assign('content', $tablstr);
	}
}
?>