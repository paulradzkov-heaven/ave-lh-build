<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

$SESS_DBHOST = $GLOBALS['config']['dbhost'];
$SESS_DBNAME = $GLOBALS['config']['dbname'];
$SESS_DBUSER = $GLOBALS['config']['dbuser'];
$SESS_DBPASS = $GLOBALS['config']['dbpass'];
$DB_PREFIX   = $GLOBALS['config']['dbpref'];

$SESS_DBH = "";

if(empty($SESS_DBHOST)) {
  header("Location:install.php");
  exit;
}

$SESS_LIFE = (!empty($GLOBALS['config']['session_lt'])) ? $GLOBALS['config']['session_lt']*60 : ((get_cfg_var("session.gc_maxlifetime") < 1000) ? 1000 : get_cfg_var("session.gc_maxlifetime")*20);

function sess_open($save_path, $session_name) {
  global $DB_PREFIX, $SESS_DBHOST, $SESS_DBNAME, $SESS_DBUSER, $SESS_DBPASS, $SESS_DBH;

  if (! $SESS_DBH = @mysql_connect($SESS_DBHOST, $SESS_DBUSER, $SESS_DBPASS)) {
    echo "<li>Can't connect to $SESS_DBHOST as $SESS_DBUSER";
    echo "<li>MySQL Error: ", mysql_error();
    die;
  }

  if (! mysql_select_db($SESS_DBNAME, $SESS_DBH)) {
    echo "<li>Ќевозможно получить доступ к базе данных $SESS_DBNAME";
    die;
  }

  return true;
}

function sess_close() {
  return true;
}

function sess_read($key) {
  global $DB_PREFIX,$SESS_DBH, $SESS_LIFE;

  $qry = "SELECT value FROM ".$DB_PREFIX. "_sessions WHERE sesskey = '$key' AND expiry > " . time() . " AND Ip = '" .$_SERVER['REMOTE_ADDR']. "'";
  $qid = mysql_query($qry, $SESS_DBH);

  if (list($value) = @mysql_fetch_row($qid)) {
    return $value;

  } else {

    $qry = "DELETE FROM ".$DB_PREFIX. "_sessions WHERE sesskey = '$key' AND expiry < " . time() . "";
    $qid = mysql_query($qry, $SESS_DBH);
  }

  return false;
}

function sess_write($key, $val) {
  global $DB_PREFIX,$SESS_DBH, $SESS_LIFE;

  $expiry = time() + $SESS_LIFE;
  $expire_datum = date("d.m.Y, H:i:s", $expiry);
  $value = addslashes($val);
  $qry = "INSERT INTO ".$DB_PREFIX. "_sessions VALUES ('$key', $expiry, '$value', '" .$_SERVER['REMOTE_ADDR']. "','$expire_datum')";
  $qid = mysql_query($qry, $SESS_DBH);

  if (! $qid) {
    $qry = "UPDATE ".$DB_PREFIX. "_sessions SET expire_datum = '$expire_datum', expiry = $expiry, value = '$value', Ip='" .$_SERVER['REMOTE_ADDR']. "' WHERE sesskey = '$key' AND Ip = '" .$_SERVER['REMOTE_ADDR']. "' AND expiry > " . time();
    $qid = mysql_query($qry, $SESS_DBH);
  }

  return $qid;
}

function sess_destroy($key) {
  global $DB_PREFIX,$SESS_DBH;

  $qry = "DELETE FROM ".$DB_PREFIX. "_sessions WHERE sesskey = '$key' AND Ip = '" .$_SERVER['REMOTE_ADDR']. "'";
  $qid = mysql_query($qry, $SESS_DBH);

  return $qid;
}

function sess_gc($maxlifetime) {
  global $DB_PREFIX,$SESS_DBH;

  $qry = "DELETE FROM ".$DB_PREFIX. "_sessions WHERE expiry < " . time() . " AND Ip = '" .$_SERVER['REMOTE_ADDR']. "'";
  $qid = mysql_query($qry, $SESS_DBH);

  return mysql_affected_rows($SESS_DBH);
}

session_set_save_handler("sess_open", "sess_close", "sess_read", "sess_write", "sess_destroy", "sess_gc");
?>