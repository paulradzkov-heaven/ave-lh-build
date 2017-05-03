<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine 
 Short Desc: Full Russian Security Power Pack 
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

if(!defined("PN")) exit;
$MAXPN = MAXPN;
$boxtype = "pagecomments";
$ok = 1;
$pnin = 1;

$limit = (isset($_REQUEST['pp']) && is_numeric($_REQUEST['pp']) && $_REQUEST['pp']>0) ? $_REQUEST['pp'] : 50;

if((!$this->fperm("canpn")) || (UGROUP==2))
{
	$this->msg($GLOBALS['mod']['config_vars']['ErrornoPerm']);
} else {
	//=======================================================
	// PN L�SCHEN
	//=======================================================
	if(isset($_REQUEST['del']) && $_REQUEST['del']!="")
	{
		$where_id = (isset($_REQUEST['goto']) && $_REQUEST['goto']=='inbox') ? " AND to_uid='" . UID . "'" : " AND from_uid='" . UID . "'";
		reset ($_POST);
		while (list($key, $val) = each($_POST))
		{
			if (substr($key,0,3)=="pn_")
			{
				$aktid = str_replace("pn_","",$key);
				$sql = $GLOBALS['db']->Query("DELETE FROM ".PREFIX."_modul_forum_pn WHERE pnid='". $aktid. "' $where_id");
			}
		}
		header("Location:index.php?module=forums&show=pn&goto=" . $_REQUEST['goto']);
	}
	
	if(!@isset($_REQUEST['action']) && @$_REQUEST['action']=="")
	{
		
		$goto = (isset($_REQUEST['goto']) && $_REQUEST['goto']=="outbox") ? "outbox" : "inbox";
		$tofrom = $goto=="inbox" ? "to_uid" : "from_uid";
		
		$send_recieve_text = $goto=="inbox" ? $GLOBALS['mod']['config_vars']['PN_r'] : $GLOBALS['mod']['config_vars']['PN_s'];
		$text_fromto = $goto=="inbox" ? $GLOBALS['mod']['config_vars']['PN_sender'] : $GLOBALS['mod']['config_vars']['PN_reciever'];
		
		
		//=======================================================
		// Aufsteigende & Absteigende Sortierung
		//=======================================================
		$sort = (isset($_REQUEST['sort']) && ($_REQUEST['sort'] == 'ASC' || $_REQUEST['sort'] == 'DESC')) ? $_REQUEST['sort'] : 'DESC';
		
		
		//=======================================================
		// Sortierung nach Betreff, Autor, Gelesen und Ungelesen
		//=======================================================
		if(!@isset($_REQUEST['porder']) && @$_REQUEST['porder']==""){
			$porder = "pntime";
		} else {
			$porder = $_REQUEST['porder'];
			if(($porder != "pntime") && ($porder != "topic") && ($porder != "uid") && ($porder != "readed") && ($porder != "notreaded")) $porder = "pntime";
		}
		
		if(($goto=="inbox") && ($porder=="uid")) {
			$porder = "from_uid";
			$inbox_uid = " selected";
		}
		if(($goto=="outbox") && ($porder=="uid")) {
			$porder = "to_uid";
			$outbox_uid = " selected";
		}
		
		if($porder=="pntime"){
			$porder = "pntime";
			$pntime_sel = " selected";
		}
		
		if($porder=="topic"){
			$porder = "topic";
			$topic_sel = " selected";
		}
		
		if($porder=="readed"){
			$porder = "is_readed='yes'";
			$readed_sel = " selected";
		}
		
		if($porder=="notreaded"){
			$porder = "is_readed='no'";
			$notreaded_sel = " selected";
		}

		//=======================================================
		// Sortierungsoptionen dem Template zuweisen...
		//=======================================================
		$sel_topic_read_unread	 = '<option value="pntime" ' . @$pntime_sel . '>'.$GLOBALS['mod']['config_vars']['PN_byDate'].'</option>';
		$sel_topic_read_unread	.= '<option value="topic" ' . @$topic_sel . '>'.$GLOBALS['mod']['config_vars']['PN_byTopic'].'</option>';
		$sel_topic_read_unread	.= '<option value="uid" ' . @$outbox_uid . @$inbox_uid . '>'.$GLOBALS['mod']['config_vars']['PN_byAuthor'].'</option>';
		$sel_topic_read_unread	.= '<option value="readed" ' . @$readed_sel . ' >'.$GLOBALS['mod']['config_vars']['PN_byReaded'].'</option>';
		$sel_topic_read_unread	.= '<option value="notreaded" ' . @$notreaded_sel . '>'.$GLOBALS['mod']['config_vars']['PN_byUnReaded'].'</option>';
		
		$GLOBALS['tmpl']->assign('sel_topic_read_unread', $sel_topic_read_unread) ;
		
		$sql = $GLOBALS['db']->Query("SELECT * FROM ".PREFIX."_modul_forum_pn WHERE $tofrom='".UID."' AND typ='".$goto."'  ORDER BY $porder $sort");
		$pnin = $sql->numrows();
		
		$seiten = ceil($pnin / $limit);
		$a = prepage() * $limit - $limit;
		
		$sql = $GLOBALS['db']->Query("SELECT * FROM ".PREFIX."_modul_forum_pn WHERE $tofrom='".UID."' AND typ='".$goto."'  ORDER BY $porder $sort limit  $a,$limit");
		
		
		$LISTPN = -1;
		if(!isset($_COOKIE['listpn']))
		{
			$LISTPN = 1;
			setcookie("listpn", "katalog", time()+(365*24*3600));
			$switchto = "katalog";
		}
		
		//=======================================================
		// Normale oder Katalogisierte Ansicht...
		//=======================================================
		if(($_COOKIE['listpn']=="katalog" ) || (!$_COOKIE['listpn'])){
			$LISTPN = 1;
			setcookie("listpn", "katalog", time()+(365*24*3600));
		}
		
		if(isset($_REQUEST['switchto']) && $_REQUEST['switchto']!=""){
			if($_REQUEST['switchto'] == "katalog"){
				$LISTPN = 1;
				setcookie("listpn", "katalog", time()+(365*24*3600));
				$switchto = "katalog";
			}
			if(isset($_REQUEST['switchto']) && $_REQUEST['switchto']=="norm"){
				$LISTPN = -1;
				setcookie("listpn", "norm", time()+(365*24*3600));
				$switchto = "norm";
			}
		}
		
		
		$entry_array = array();
		$table_data = array();
		if($LISTPN==1)
		{
			while ($row = $sql->fetchrow())
			{
				$sql2 = $GLOBALS['db']->Query("
				SELECT
					u.BenutzerName as uname,
					u.BenutzerId as uid 
				FROM 
					".PREFIX."_modul_forum_userprofile as u 
				WHERE 
					u.BenutzerId='".$row->from_uid."' 
					");
				$row2 = $sql2->fetchrow();
				$sql2->close();
				
				if($goto=="inbox")
				{
					$theuserid = $row2->uid;
					$theusername = $row2->uname;
				}else{
					$sql_emp = $GLOBALS['db']->Query("
					SELECT
						u.BenutzerName as uname,
						u.BenutzerId as uid 
					FROM 
						".PREFIX."_modul_forum_userprofile as u 
					WHERE 
						u.BenutzerId='".$row->to_uid."' 
						");
					$row_emp = $sql_emp->fetchrow();
					$theuserid = $row->to_uid;
					$theusername = $row_emp->uname;
				}
				
				$entry_array[] = array(
				'timestamp' => $row->pntime,
				'data'      => array(
				'title'	=> stripslashes($row->topic),
				'message' => htmlspecialchars($row->message),
				'pntime' => $row->pntime,
				'pnday' => $row->pntime,
				'von'	=> $theusername,
				'goto'	=> $goto,
				'pnid'	=> $row->pnid,
				'icon' => $this->isreaded($row->pnid),
				'toid' => "index.php?module=forums&amp;show=userprofile&amp;user_id=".$theuserid,
				'mlink'	=> "index.php?module=forums&amp;show=pn&amp;action=message&amp;pn_id=".$row->pnid."&amp;goto=".$goto
				)
				);
			}
			
			$last = 0;
			
			$ts = array();
			$ts[0] = array(
			'anfang' => mktime(0,0,0,date("m"),date("d"),date("Y")),
			'ende'	 => mktime(23,59,59,date("m"),date("d"),date("Y"))
			);
			
			$last = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$wochentag = date("w") + 1;
			
			for ($i = 1; $i < $wochentag; $i++) {
				$a = $wochentag - $i;
				if (date("d")-$i > 0) {
					$last -= 86400;
					$ts[$a] = array(
					'anfang' => $last,
					'ende'	 => $last+86399
					);
				}
			}
			
			$ts[-2] = array(
			'anfang'	=> $last-(7 * 86400),
			'ende'		=> $last
			);
			
			$last -= 7 * 86400;
			
			$ts[-1] = array(
			'anfang'	=> 0,
			'ende'		=> $last
			);
			

			$wochentage = explode(",",$GLOBALS['mod']['config_vars']['PN_DayNames']);
			while(list($key,$val)=each($ts))
			{
				
				if ($key==0) {
					$t = $GLOBALS['mod']['config_vars']['Today'];
					$d = ", " . date("d.m.Y", $val['anfang']);
				} elseif ($key==-1) {
					$t = $GLOBALS['mod']['config_vars']['PN_Later'];
					$d = "";
				} elseif ($key==-2) {
					$t = $GLOBALS['mod']['config_vars']['PN_LastWeek'];
					$d = "";
				} else {
					$t = $wochentage[$key-1];
					$d = ", " . date("d.m.Y", $val['anfang']);
				}
				
				$mys = 0;
				
				reset($entry_array);
				while(list($k,$v) = each($entry_array))
				{
					if($v['timestamp'] > $val['anfang'] && $v['timestamp'] < $val['ende'])
					{
						$mys++;
					}
				}
				
				if($mys > 0) {
					$a = 0;
					reset($entry_array);
					while(list($k,$v) = each($entry_array))
					{
						if($v['timestamp'] > $val['anfang'] && $v['timestamp'] < $val['ende'])
						{
							$a++;
							if($a==1) {
								
								
								unset($position,$plusminus,$disp);
								$disp = "";
								$position = @strpos($_COOKIE["pn"], "pn" . $goto.$key);
								
								if ( is_numeric($position) ) {
									$disp= "none";
									$plusminus = "plus.gif";
								} else {
									$plusminus = "minus.gif";
								}
								$toggle = "<img hspace=\"2\" class=\"absmiddle\" border=\"0\" id=\"pn_".$goto.$key."\" src=\"templates/".T_PATH."/modules/forums/".$plusminus."\" 
								onmouseover=\"this.style.cursor = 'pointer'\"
								onclick=\"MWJ_changeDisplay('pn".$goto.$key."', MWJ_getStyle( 'pn".$goto.$key."', 'display' ) ? '' : 'none');
								cpengine_toggleImage('pn_".$goto.$key."', this.src);
								cpengine_setCookie('pn', 'pn".$goto.$key."');\"
								alt=\"\" />";
								
								$v['data']['tbody_start'] = "<tr><td class=\"toggletr\" colspan=\"4\">".$toggle.$t.$d."</td></tr><tbody id='pn".$goto.$key."' name='pn".$goto.$key."' style=\"display: ".$disp.";\">";
							}
							if($a==$mys) {
								$v['data']['tbody_end'] = "</tbody>";
							}
							
							array_push($table_data, $v['data']);
							
						}
					}
				}
				
			}
			
		} else {
			while ($row = $sql->fetchrow())
			{
				$sql2 = $GLOBALS['db']->Query("
				SELECT 
					BenutzerName as uname,
					BenutzerId as uid 
				FROM ".PREFIX."_modul_forum_userprofile WHERE BenutzerId='".$row->from_uid."'");
				
				$row2 = $sql2->fetchrow();
				$sql2->close();
				
				if ($goto=="inbox")
				{
					$theuserid = $row2->uid;
					$theusername = $row2->uname;
				} else {
					$sql_emp = $GLOBALS['db']->Query("
					SELECT 
						BenutzerName as uname,
						BenutzerId as uid 
					FROM ".PREFIX."_modul_forum_userprofile WHERE BenutzerId='".$row->to_uid."'");
					$row_emp = $sql_emp->fetchrow();
					$theuserid = $row->to_uid;
					$theusername = $row_emp->uname;
				}
				array_push($table_data, array(
					'timestamp' => $row->pntime,
					'title'	=> stripslashes($row->topic),
					'pntime' => $row->pntime,
					'pnday' => $row->pntime,
					'von'	=> $theusername,
					'pnid'	=> $row->pnid,
					'goto'	=> $goto,
					'icon' => $this->isreaded($row->pnid),
					'toid' => "index.php?module=forums&amp;show=userprofile&amp;user_id=" . $theuserid,
					'mlink'	=> "index.php?module=forums&amp;show=pn&amp;action=message&amp;pn_id=" . $row->pnid . "&amp;goto=" . $goto
					)
				);
			}
		}
		
		//=======================================================
		// Selektion f�r gelesene und ungelesene Nachrichten wieder �ndern...
		//=======================================================
		if (isset($_REQUEST['porder']) && $_REQUEST['porder']=="readed") {
			$porder = "readed";
		}
		if (isset($_REQUEST['porder']) && $_REQUEST['porder']=="notreaded") {
			$porder = "notreaded";
		}
		if (isset($_REQUEST['porder']) && $_REQUEST['porder']=="uid") {
			$porder = "uid";
		}
		
		//=======================================================
		// Navigation erzeugen
		//=======================================================
		if($pnin > $limit){
			$nav = pagenav($seiten, $this->getActPage(), " <a class=\"page_navigation\" href=\"index.php?module=forums&amp;show=pn&amp;goto=".$goto."&sort=".$sort."&porder=".$porder."&pp=".$limit."&page={s}&switchto=" . ((empty($switchto)) ? 'katalog' : $switchto) . "\">{t}</a> ");
			$GLOBALS['tmpl']->assign("nav", $nav) ;
		}
		
		//=======================================================
		// Anzahlwahl + Auf oder Absteigend
		//=======================================================
		$pp_l = "";
		for ($i = 50; $i >= 10; $i-=10)
		{
			unset($thisselect);
			$thisselect = "";
			if (isset($_REQUEST['pp']) && $_REQUEST['pp']==$i)
			{
				$thisselect = "selected";
			}
			$pp_l .= '<option value="'.$i.'" '.$thisselect.'>'.$i.' '.$GLOBALS['mod']['config_vars']['PN_EachPage'].'</option>';
		}
		$GLOBALS['tmpl']->assign('pp_l', $pp_l) ;
		
		
		$page = (isset($_REQUEST['page']) && $_REQUEST['page'] != '' && $_REQUEST['page'] > 0 && is_numeric($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
		
		
		if(isset($_REQUEST['sort']) && $_REQUEST['sort']=="DESC"){ // ABSTEIGEND
			$GLOBALS['tmpl']->assign('sel1', "selected");
			$GLOBALS['tmpl']->assign('sel2', "");
		}
		if(isset($_REQUEST['sort']) && $_REQUEST['sort']=="ASC"){ // AUFSTEIGEND
			$GLOBALS['tmpl']->assign('sel2', "selected");
			$GLOBALS['tmpl']->assign('sel1', "");
		}
		$GLOBALS['tmpl']->assign('page', $page);
		
		
		//=======================================================
		// Normale Ansicht & Katalogisierte Ansicht
		//=======================================================
		$link_the_normmodus = "index.php?module=forums&amp;show=pn&goto=".$goto."&sort=".$sort."&porder=".$porder."&pp=".$limit."&page=".$page."&switchto=norm";
		$link_the_katmodus = "index.php?module=forums&amp;show=pn&goto=".$goto."&sort=".$sort."&porder=".$porder."&pp=".$limit."&page=".$page."&switchto=katalog";
		$GLOBALS['tmpl']->assign('normmodus_link', $link_the_normmodus) ;
		$GLOBALS['tmpl']->assign('katmodus_link', $link_the_katmodus) ;
		
		//=======================================================
		// PROZENT EINGANG / AUSGANG
		//=======================================================
		$warningpnfull = "";
		$onepn = 100 / $MAXPN;
		$allpn = $onepn * $pnin;
		$inoutwidth = round($allpn/1.005, 3);
		$inoutpercent = round($allpn, 0);
		if($pnin==$MAXPN){$warningpnfull = $lang['warningpnfull'];}
		
		if($goto=="inbox") {
			$GLOBALS['tmpl']->assign('selin', "selected");
			$GLOBALS['tmpl']->assign("view", "inbox");
		}
		
		if($goto=="outbox") {
			$GLOBALS['tmpl']->assign('selout', "selected");
			$GLOBALS['tmpl']->assign("view", "outbox");
		}
	}
	
	if(!@isset($_REQUEST['action']) && @$_REQUEST['action']=="")
	{
		$desclink = "index.php?p=pn&goto=".$goto."&sort=DESC&pp=".@$_REQUEST['pp']."&page=".prepage();
		$asclink = "index.php?p=pn&goto=".$goto."&sort=ASC&pp=".@$_REQUEST['pp']."&page=".prepage();
		
		$GLOBALS['tmpl']->assign('send_recieve_text', $send_recieve_text);
		$GLOBALS['tmpl']->assign('from_t', $text_fromto);
		$GLOBALS['tmpl']->assign('goto', $goto);
		$GLOBALS['tmpl']->assign('inoutwidth', $inoutwidth);
		$GLOBALS['tmpl']->assign('inoutpercent', $inoutpercent);
		$GLOBALS['tmpl']->assign('pnioutnall', $pnin);
		$GLOBALS['tmpl']->assign('pnmax', str_replace("__MAXPN__", $MAXPN, $GLOBALS['mod']['config_vars']['PN_TextFilled']));
		$GLOBALS['tmpl']->assign('warningpnfull', $warningpnfull);
		$GLOBALS['tmpl']->assign('sortdesc', $desclink);
		$GLOBALS['tmpl']->assign('sortasc', $asclink);
		$GLOBALS['tmpl']->assign('pndl_text', "index.php?module=forums&amp;show=pn&amp;goto=".$goto."&amp;download=1&amp;type=text");
		$GLOBALS['tmpl']->assign('pndl_html', "index.php?module=forums&amp;show=pn&amp;goto=".$goto."&amp;download=1&amp;type=html");
		
		//=======================================================
		// Subtemplate
		//=======================================================
		$GLOBALS['tmpl']->assign('outin', 1);
		$GLOBALS['tmpl']->assign('neu', 0);
		
		//=======================================================
		// Wenn keine PN...
		//=======================================================
		if($pnin)
		{
			$GLOBALS['tmpl']->assign('table_data', $table_data);
		} else {
			$GLOBALS['tmpl']->assign('nomessages', 1);
			$GLOBALS['tmpl']->assign('outin', 0);
		}
		
		//=======================================================
		// PN herunterladen
		//=======================================================
		if(isset($_REQUEST['download']) && $_REQUEST['download']==1)
		{
			$sql = $GLOBALS['db']->Query("SELECT * FROM ".PREFIX."_modul_forum_pn WHERE $tofrom='" . UID . "' AND typ='".$goto."'  ORDER BY pntime ".$sort."");
			$dlmessage = "";
			while ($row = $sql->fetchrow())
			{
				$message = str_replace("\n", "\r\n", str_replace("\r\n", "\n", $row->message));
				$message = stripslashes($message);
				
				if($goto=="inbox")
				{
					$pninout = $GLOBALS['mod']['config_vars']['PN_In'];
					$fname = $theusername;
					$tname = $_SESSION['cp_forumname'];
				} else {
					$pninout = $GLOBALS['mod']['config_vars']['PN_Out'];
					$fname = $_SESSION['cp_forumname'];
					$tname = $theusername;
				}
				
				
				if($_REQUEST['type']=="text")
				{
					$dlmessage .= "===============================================================================\r\n";
					$dlmessage .= $GLOBALS['mod']['config_vars']['PN_sender'].":\t" . $fname ."\r\n";
					$dlmessage .= $GLOBALS['mod']['config_vars']['PN_reciever'].":\t" . $tname . "\r\n";
					$dlmessage .= $GLOBALS['mod']['config_vars']['PN_Date'].":\t" . date('d-m-Y H:i', $row->pntime) . "\r\n";
					$dlmessage .= $GLOBALS['mod']['config_vars']['PN_TheSubject'].":\t" . $row->topic . "\r\n";
					$dlmessage .= "-------------------------------------------------------------------------------\r\n";
					$dlmessage .=  wordwrap( $message, 90, "\r\n", 1) . "\r\n\r\n";
					$end = ".txt";
					$type = "text/plain";
					
				} else {
					
					$dlmessage .= '<style><!-- td,div,th{FONT-SIZE: 11px;FONT-FAMILY:   Verdana, Arial, Helvetica, sans-serif;} --></style>';
					$dlmessage .= '<table width="100%"  border="1" cellpadding="3" cellspacing="0" bordercolor="#333333"><tr><td bgcolor="#F4F4F4">';
					$dlmessage .= "<B>".$GLOBALS['mod']['config_vars']['PN_sender']."</B>:\t" . $fname ."\r<br>";
					$dlmessage .= "<B>".$GLOBALS['mod']['config_vars']['PN_reciever']."</B>:\t" . $tname . "\r<br>";
					$dlmessage .= "<B>".$GLOBALS['mod']['config_vars']['PN_Date']."</B>:\t" . date('d-m-Y H:i', $row->pntime) . "\r<br>";
					$dlmessage .= "<B>".$GLOBALS['mod']['config_vars']['PN_TheSubject']."</B>:\t" . $row->topic . "\r<br>";
					$dlmessage .= '</td></tr><tr><td>';
					$dlmessage .=  nl2br(stripslashes(wordwrap(htmlspecialchars($message), 90, "\r\n", 1))) ."";
					$dlmessage .= '</td></tr></table><br>';
					$end = ".htm";
					$type = "text/html";
				}
			}
			$this->downloadfile($dlmessage, $pninout ."__" . date("d-m-Y") . $end, $type);
		}
		
	}
	
	//=======================================================
	// NACHRICHT ANSEHEN
	//=======================================================
	if(isset($_REQUEST['action']) && $_REQUEST['action']=="message")
	{
		
		$goto = $_REQUEST['goto']=="inbox" ? "inbox" : "outbox";
		$tofrom = $goto=="inbox" ? "to_uid" : "from_uid";
		$sql = $GLOBALS['db']->Query("SELECT * FROM ".PREFIX."_modul_forum_pn WHERE pnid='".$_REQUEST['pn_id']."' AND $tofrom='".UID."' AND typ='".$goto."'");
		$num = $sql->numrows();
		$row = $sql->fetchrow();
		$sql->close();
		
		// WENN UNG�LTIGE ID ODER PN NICHT == USER-ID
		if(!$num)
		{
			$this->msg($GLOBALS['mod']['config_vars']['PN_WrongId'], 'index.php?module=forums&show=pn');
		}
		
		if($ok==1):
		$pn_id = addslashes($_REQUEST['pn_id']);
		if(isset($_REQUEST['do']) && $_REQUEST['do']=="del")
		{
			$where_id = (isset($_REQUEST['goto']) && $_REQUEST['goto']=='inbox') ? " AND to_uid='" . UID . "'" : " AND from_uid='" . UID . "'";
			$sql = $GLOBALS['db']->Query("DELETE FROM ".PREFIX."_modul_forum_pn WHERE pnid='" . $pn_id . "' $where_id");
			header("Location:index.php?module=forums&show=pn");
			exit;
		}
		
		if($goto == "inbox")
		{
			$sql = $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_forum_pn SET is_readed='yes' WHERE pnid='" . addslashes($_GET['pn_id']) . "'");
			$sql = $GLOBALS['db']->Query("SELECT pntime,topic FROM ".PREFIX."_modul_forum_pn WHERE pnid='" . addslashes($_GET['pn_id']) . "'");
			$row_subid = $sql->fetchrow();
			$sql = $GLOBALS['db']->Query("UPDATE ".PREFIX."_modul_forum_pn SET is_readed='yes' WHERE pntime='".$row_subid->pntime."' AND topic='".$row_subid->topic."'");
		}
		
		if(isset($_POST['goto']) && $_POST['goto'] == "inbox")
		{
			$sqlid = $row->from_uid;
			$tfrlink = "index.php?module=forums&amp;show=userprofile&amp;user_id=" . UID;
		} elseif(isset($_POST['goto']) && $_POST['goto'] == "outbox")
		{
			$sqlid = $row->to_uid;
			$tfrlink = "index.php?module=forums&amp;show=userprofile&amp;user_id=" . $row_u->uid;
		} else {
			$sqlid = $row->from_uid;
		}
		
		
		$goto = (!isset($_REQUEST['goto']) && ($_REQUEST['goto']=="")) ? 'inbox' : $_REQUEST['goto'];
		$text_fromto = ($goto=="inbox") ? "Von" : "An";
		$message = $row->message;
		
		if($row->smilies=="yes")
		{
			$GLOBALS['tmpl']->assign('message', $this->replaceWithSmileys($this->kcodes($message)));
		} else {
			$GLOBALS['tmpl']->assign('message', $this->kcodes($message));
		}
		
		
		
		$sql = $GLOBALS['db']->Query("
		SELECT	
			BenutzerId as uid,
			BenutzerName as uname,
			Registriert as user_regdate,
			Beitraege as user_posts
		FROM 
			".PREFIX."_modul_forum_userprofile
		WHERE 
			BenutzerId='".$sqlid."'
			");
		$row_u = $sql->fetchrow();
		$sql->close();
		
		$pnlink = "index.php?module=forums&amp;show=pn&action=message&pn_id=". $_GET['pn_id'] ."&goto=".$goto."&do=del";
		$forwardlink = "index.php?module=forums&amp;show=pn&action=new&forward=1&pn_id=". $_GET['pn_id'] ."&subject=".base64_encode($row->topic)."&aut=".base64_encode($row_u->uname)."&date=".base64_encode($row->pntime);
		$relink = "index.php?module=forums&amp;show=pn&action=new&forward=2&pn_id=". $_GET['pn_id'] ."&subject=".base64_encode($row->topic)."&aut=".base64_encode($row_u->uname)."&date=".base64_encode($row->pntime);
		
		if(isset($_POST['goto']) && $_POST['goto'] == "inbox"){
			$tfrlink = "index.php?module=forums&show=userprofile&user_id=".$row_u->uid."";
		} else {
			$tfrlink = "index.php?module=forums&show=userprofile&user_id=".$row_u->uid."";
		}
		
		if($goto=="inbox")
		{
			$GLOBALS['tmpl']->assign('answerok', 1);
		}
		
		$GLOBALS['tmpl']->assign('delpn', $pnlink);
		$GLOBALS['tmpl']->assign('forwardlink', $forwardlink);
		$GLOBALS['tmpl']->assign('relink', $relink);
		$GLOBALS['tmpl']->assign('membersince_date', $row_u->user_regdate);
		$GLOBALS['tmpl']->assign('posts_num', $row_u->user_posts);
		$GLOBALS['tmpl']->assign('pntitle', $row->topic);
		$GLOBALS['tmpl']->assign('pntime', $row->pntime);
		$GLOBALS['tmpl']->assign('tofromname', $row_u->uname);
		$GLOBALS['tmpl']->assign('tofromname_link', $tfrlink);
		$GLOBALS['tmpl']->assign('pndate', $row->pntime);
		$GLOBALS['tmpl']->assign('to_t', $text_fromto);
		$GLOBALS['tmpl']->assign('showmessage', 1);
		endif;
	}
	
	// NEUE NACHRICHT
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == "new")
	{
		$sql = $GLOBALS['db']->Query("SELECT typ FROM ".PREFIX."_modul_forum_pn WHERE typ='outbox' and from_uid='".UID."'");
		$num = $sql->numrows();
		
		if($num == $MAXPN || $num >= $MAXPN)
		{
			$this->msg($GLOBALS['mod']['config_vars']['PN_BoxMyFull'], 'index.php?module=forums&show=pn&goto=inbox');
		}
		
		
		// Vorschau
		if(isset($_POST['send']) && $_POST['send']=="1")
		{
			$message = $_POST['text'];
			
			if(isset($_POST['parseurl']) &&  $_POST['parseurl']=="yes")
				$message = $this->parseurl($message);
			
			if(isset($_POST['use_smilies']) && $_POST['use_smilies'] == "yes") 
				$GLOBALS['tmpl']->assign('preview_text', $this->replaceWithSmileys($this->kcodes($message)));
			else
				$GLOBALS['tmpl']->assign('preview_text', $this->kcodes($message));
			
			$text = "";
			$GLOBALS['tmpl']->assign('tofromname', $_POST['tofromname']);
			$GLOBALS['tmpl']->assign('title', $_POST['title']);
			$GLOBALS['tmpl']->assign('text',htmlspecialchars($_POST['text']));
			$GLOBALS['tmpl']->assign('preview', 1);
		}

		if(isset($_POST['send']) && $_POST['send']=="2")
		{
			$error = "";
			if(empty($_POST['tofromname'])) $pnerror[] = $GLOBALS['mod']['config_vars']['PN_NoR'];
			if(empty($_POST['title']))  $pnerror[] = $GLOBALS['mod']['config_vars']['PN_NoS'];
			if(empty($_POST['text'])) $pnerror[] = $GLOBALS['mod']['config_vars']['PN_NoT'];
			if(strlen($_POST['text']) > MAXPNLENTH) $pnerror[] = $GLOBALS['mod']['config_vars']['PN_TextToLong'];
			
			$GLOBALS['tmpl']->assign('tofromname', $_POST['tofromname']);
			$GLOBALS['tmpl']->assign('title', $_POST['title']);
			$GLOBALS['tmpl']->assign('text', $_POST['text']);
			
			// CHECK OB USER EXISTIERT
			$q = "
				SELECT 
					u.BenutzerName,
					u.BenutzerId,
					u.Pnempfang,
					u.Email 
				FROM 
					".PREFIX."_modul_forum_userprofile  as u 
				WHERE 
					u.BenutzerName = '" . addslashes($_POST['tofromname']) . "' 
				OR
					u.BenutzerId = '" . addslashes($_POST['tofromname']) . "' 
				";
			$sql = $GLOBALS['db']->Query($q);
			$num = $sql->numrows();
			$row = $sql->fetchrow();
			
			if($row->Pnempfang != 1) $pnerror[] = $GLOBALS['mod']['config_vars']['PN_NotWant'];
			if(empty($pnerror) && $num<1) $pnerror[] = $GLOBALS['mod']['config_vars']['PN_UserError'];
			
			// CHECK OB ABSENDER IN IGNORIERLISTE DES EMPF�NGERS STEHT
			$num_ignore = 0;
			if(is_object($row))
			{
				$sql_ignore = $GLOBALS['db']->Query("
					SELECT 
						IgnoreId,
						BenutzerId 
					FROM 
						".PREFIX."_modul_forum_ignorelist
					WHERE 
						BenutzerId='" . addslashes($row->BenutzerId) . "' AND 
						IgnoreId='".UID."' 
					");
				
				$num_ignore = $sql_ignore->numrows();
			}
			if(empty($pnerror) && $num_ignore>0) $pnerror[] = $GLOBALS['mod']['config_vars']['PN_IgnoredByUser'];
			
			// CHECK OB NACHRICHTENBOX VOLL IST
			if(empty($pnerror))
			{
				$usermaxpn = MAXPN;
				
				$sql3 = $GLOBALS['db']->Query("SELECT * FROM ".PREFIX."_modul_forum_pn WHERE to_uid='".$row->BenutzerId."' AND typ='inbox'");
				$numuserpn = $sql3->numrows();
				
				if($numuserpn >= $usermaxpn) $pnerror[] = $GLOBALS['mod']['config_vars']['PN_BoxFull'];
			}
			
			// CHECK OB USER PN'S EMPFANGEN M�CHTE
			if(empty($pnerror))
			{
				if($row->Pnempfang=="no") $pnerror[] = $GLOBALS['mod']['config_vars']['PN_NotWant'];
			}
			
			$text = substr($_POST['text'],0, MAXPNLENTH);
			if($_POST['parseurl']=="yes")
			{
				$text = $this->parseurl($text);
			}
			
			if(empty($pnerror))
			{
				$sql = $GLOBALS['db']->Query("INSERT INTO ".PREFIX."_modul_forum_pn (smilies,pnid,to_uid,from_uid,topic,message,is_readed,pntime,typ) VALUES ('".$_POST['use_smilies']."','','".$row->BenutzerId."','".UID."','".$_POST['title']."','".$text."','no','".time()."','inbox')");
				if(isset($_REQUEST['savecopy']) && $_REQUEST['savecopy']=="yes")
				{
					$sql = $GLOBALS['db']->Query("INSERT INTO ".PREFIX."_modul_forum_pn (smilies,pnid,to_uid,from_uid,topic,message,is_readed,pntime,typ) VALUES ('".$_POST['use_smilies']."','','".$row->BenutzerId."','".UID."','".$_POST['title']."','".$text."','no','".time()."','outbox')");
				}
				
				// MAILBENACHRICHTIGUNG
				$body = $GLOBALS['mod']['config_vars']['PN_Body'];
				$body = str_replace("__USER__", $row->BenutzerName, $body);
				$body = str_replace("__AUTOR__", $_SESSION['cp_forumname'], $body);
				$body = str_replace("__LINK__", "http://" . $_SERVER['HTTP_HOST'].str_replace("/index.php","",$_SERVER['PHP_SELF'])."/index.php?module=forums&show=pn&goto=inbox", $body);
				$body = str_replace("%%N%%","\n", $body);
				
				$globals = new Globals;
				$GLOBALS['globals']->cp_mail($row->Email, stripslashes($body), $GLOBALS['mod']['config_vars']['PN_Subject'], FORUMEMAIL, FORUMABSENDER, "text", "");

				// Vielen Dank, Ihre Nachricht wurde erfolgreich versendet.
				$this->msg($GLOBALS['mod']['config_vars']['PN_ThankYou'], 'index.php?module=forums&show=pn&goto=outbox');
			}
		}
		
		
		if(!empty($pnerror))
		{
			$GLOBALS['tmpl']->assign('iserror', 1);
			$GLOBALS['tmpl']->assign('error', $pnerror);
		}
		
		
		
		$GLOBALS['tmpl']->assign('listfonts',  $this->fontdropdown());
		$GLOBALS['tmpl']->assign('sizedropdown',  $this->sizedropdown());
		$GLOBALS['tmpl']->assign('colordropdown',  $this->colordropdown());
		$GLOBALS['tmpl']->assign('max_post_length', MAXPNLENTH);
		$GLOBALS['tmpl']->assign('listemos', $this->listsmilies());
		$GLOBALS['tmpl']->assign('newpn_t', str_replace("__ZEICHEN__", MAXPNLENTH , $GLOBALS['mod']['config_vars']['PN_TextNewMsg']));
		$GLOBALS['tmpl']->assign('newpn_error', @$pnerror);
		
		if(isset($_REQUEST['to']) && $_REQUEST['to']!="")
		{
			$GLOBALS['tmpl']->assign('tofromname', base64_decode($_REQUEST['to']));
		}
		
		
		if(isset($_REQUEST['forward']) && $_REQUEST['forward']!="")
		{
			$fwre = $_REQUEST['forward']=="1" ? "Fw: " : "Re: ";
			$fromto = $_REQUEST['forward']=="1" ? "from_uid" : "to_uid";
			
			$sql = $GLOBALS['db']->Query("SELECT message FROM ".PREFIX."_modul_forum_pn WHERE pnid='".addslashes($_GET['pn_id'])."' AND $fromto='".UID."'");
			$row = $sql->fetchrow();
			$sql->close();
			
			$qtext = $row->message;
			$qtext = str_replace("\r\n", "\r\n", $qtext);
			$qtext = htmlspecialchars($qtext);
			
			$qtext = "\r\n\r\n" . "===================\n" . $GLOBALS['mod']['config_vars']['PN_originalmessage'] . "\n===================\r\n" . $GLOBALS['mod']['config_vars']['PN_sender'].": ".base64_decode($_REQUEST['aut'])."\n". $GLOBALS['mod']['config_vars']['PN_TheSubject'] .": ".base64_decode($_REQUEST['subject'])."\n". $GLOBALS['mod']['config_vars']['PN_Date'] .": " . date("d.m.Y, H:i:s", base64_decode($_REQUEST['date'])) . "\r\n\n". $qtext . "";
			
			$GLOBALS['tmpl']->assign('tofromname', base64_decode($_REQUEST['aut']));
			$GLOBALS['tmpl']->assign('title', $fwre.base64_decode($_REQUEST['subject']));
			$GLOBALS['tmpl']->assign('text', $qtext);
		}
		
		if(SMILIES == 1) $GLOBALS['tmpl']->assign('smilie', 1);
		
		$GLOBALS['tmpl']->assign('outin', 0);
		$GLOBALS['tmpl']->assign('neu', 1);
		
	}
	
	if($ok == 1)
	{
		$tpl_out = $GLOBALS['tmpl']->fetch($GLOBALS['mod']['tpl_dir'] . 'pn.tpl');
		define("MODULE_CONTENT", $tpl_out);	
		define("MODULE_SITE", $GLOBALS['mod']['config_vars']['PN_Name']);
	} else {
		//$GLOBALS['tmpl']->assign("content", $EOUT);
	}
	
}
?>