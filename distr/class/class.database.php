<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class DB_Result {

  var $_result;

  function DB_Result($result) {
    $this->_result = $result;
  }

  function FetchArray() {
    return @mysql_fetch_array($this->_result);
  }

  function fa() {
    return @mysql_fetch_array($this->_result);
  }

  function dataseek() {
    return @mysql_data_seek($this->_result);
  }

  function cp_dataseek($id='') {
    return @mysql_data_seek($this->_result,$id);
  }

  function fetchrow_assoc() {
    return @mysql_fetch_assoc($this->_result);
  }

  function FetchAssocArray() {
    return @mysql_fetch_assoc($this->_result);
  }

  function FetchObject() {
    return mysql_fetch_object($this->_result);
  }

  function FetchRow() {
    return @mysql_fetch_object($this->_result);
  }

  function fr() {
    return @mysql_fetch_object($this->_result);
  }

  function NumRows() {
    return @mysql_num_rows($this->_result);
  }

  function smysql_version() {
    return  mysql_get_server_info($this->_result);
  }

  function NumFields() {
    return mysql_num_fields($this->_result);
  }

  function FieldName($i) {
    return mysql_field_name($this->_result, $i);
  }

  function Close() {
    $r = @mysql_free_result($this->_result);
    unset($this);
    return $r;
  }
}

class DB {

  var $_handle;

  function DB($host, $user, $pass, $db) {
    $this->_handle = @mysql_connect($host, $user, $pass);
    if(!$this->_handle) {
      $this->Error('connect');
      return false;
    }

    if(!@mysql_select_db($db, $this->_handle)) {
      $this->Error('select');
      return false;
    }

    return true;
  }

  function Query($query) {

    $res = @mysql_query($query, $this->_handle);
    if(!$res) {
      $this->Error('query', $query);
    }
    $_SESSION['dbq']++;
    return new DB_Result($res);
  }

  function Escape($query) {
    return mysql_escape_string($query);
  }

  function InsertId() {
    return mysql_insert_id($this->_handle);
  }

  function AffectedRows() {
    return mysql_affected_rows($this->_handle);
  }

  function mysql_version() {
    return  mysql_get_server_info($this->_handle);
  }

  function Error($type, $query = '') {
//    global $pref;
    $my_error = mysql_error();
//    $my_errno = mysql_errno();
    if ($type != 'query') {
      echo 'Error ' . $type . ' MySQL database. <br />';
    } else {
      $globals = new Globals;
      $SystemMail = $GLOBALS['globals']->cp_settings('Mail_Absender');
      $SystemMailName = $GLOBALS['globals']->cp_settings('Mail_Name');
      $MailBody = ('Query:' . $query . ' Errror:' . $my_error . ' IP:' . $_SERVER[REMOTE_ADDR] . ' Time:' . date("d-m-Y, H:i:s") . ' URL: http://' . $_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI]);
      $GLOBALS['globals']->cp_mail($SystemMail, $MailBody, 'MySQL Error!', $SystemMail, $SystemMailName, 'text');
    }
    return true;
  }

  function Disconnect() {
    mysql_close($this->_handle);
  }
}
?>