<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class Login {

  var $_sleep = 0;
  var $_config_id = 1;

  function displayLoginform($tpl_dir,$lang_file) {
    $GLOBALS['tmpl']->config_load($lang_file);
    $config_vars = $GLOBALS['tmpl']->get_config_vars();
    $GLOBALS['tmpl']->assign('config_vars', $config_vars);

    if($this->getSettings('IstAktiv') == 1) {
      $GLOBALS['tmpl']->assign('active', 1);
    }

    $GLOBALS['tmpl']->display($tpl_dir . 'loginform.tpl');
  }

  function displayPanel($tpl_dir,$lang_file) {
    $GLOBALS['tmpl']->config_load($lang_file);
    $config_vars = $GLOBALS['tmpl']->get_config_vars();
    $GLOBALS['tmpl']->assign('config_vars', $config_vars);

    $GLOBALS['tmpl']->display($tpl_dir . 'userpanel.tpl');
  }
// Ïðîâåðêà íà àâòîëîãèí
  function checkAutoLogin() {
    global $_SESSION;
    global $_COOKIE;

    if(isset($_COOKIE['cp_login']) && isset($_COOKIE['cp_password']) && isset($_COOKIE['cp_staylogged']) && $_COOKIE['cp_staylogged'] == 1) {
      $spassword = addslashes($_COOKIE['cp_password']);
      $slogin    = addslashes($_COOKIE['cp_login']);

      $SqlLogin = $GLOBALS['db']->Query("SELECT Id, Kennwort, Status FROM " . PREFIX . "_users WHERE (Email = '$slogin' OR `UserName` = '$slogin') AND Kennwort = '$spassword'");
      $RowLogin = $SqlLogin->fetchrow();
      if(is_object($RowLogin) && $RowLogin->Status == 1) {
        $_SESSION['cp_benutzerid'] = $RowLogin->Id;
        $_SESSION['cp_kennwort']   = $RowLogin->Kennwort;
        setcookie('cp_login', $_COOKIE['cp_login'], time() + (3600 * 31 * 6)*2);
        setcookie('cp_password', md5(md5($spassword)), time() + (3600 * 31 * 6)*2);
        setcookie('cp_staylogged', '1', time() + (3600 * 31 * 6)*2);
        $SqlLogin->Close();
        return true;
      }
    }
    return false;
  }
// Ëîãèí
  function loginProcess($tpl_dir,$lang_file,$out = '') {
    global $_SESSION;

    $GLOBALS['tmpl']->config_load($lang_file);
    $config_vars = $GLOBALS['tmpl']->get_config_vars();
    $GLOBALS['tmpl']->assign("config_vars", $config_vars);

    if(isset($_REQUEST['module']) && $_REQUEST['module'] == 'login' && $_REQUEST['action'] == 'logout') {
      @setcookie('cp_login', '', -1, '/');
      @setcookie('cp_password', '', -1, '/');
      @setcookie('cp_staylogged', '', -1, '/');
      @session_destroy();
      header("Location:/");
      exit;
    }

    if (!empty($_POST['cp_login']) && !empty($_POST['cp_password']) &&  $out == '') {
        sleep($this->_sleep);
        $spassword = addslashes($_POST['cp_password']);
        $slogin    = addslashes($_POST['cp_login']);


        $SqlLogin = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_users WHERE (Email = '$slogin' OR `UserName` = '$slogin') AND Kennwort = '" .md5(md5($spassword)). "'");
        $RowLogin = $SqlLogin->fetchrow();


        switch(@$RowLogin->Status) {

          case 1:
            $_SESSION['cp_benutzerid'] = $RowLogin->Id;
            $_SESSION['cp_kennwort']   = $RowLogin->Kennwort;

            if(isset($_POST['SaveLogin']) && $_POST['SaveLogin'] == 1) {
              @setcookie('cp_login', base64_encode($slogin), time() + (3600 * 31 * 6)*2);
              @setcookie('cp_password', md5(md5($spassword)), time() + (3600 * 31 * 6)*2);
              @setcookie('cp_staylogged', '1', time() + (3600 * 31 * 6)*2);
            }
            header('Location:' . $_SERVER['HTTP_REFERER']);
            exit;
          break;

          case '':
            unset($_SESSION['cp_benutzerid']);
            unset($_SESSION['cp_kennwort']);
            $GLOBALS['tmpl']->assign('login', 'false');
          break;
        }
    } else {
      $GLOBALS['tmpl']->assign('login', "false");
    }

    $GLOBALS['tmpl']->assign('inc_path', BASE_DIR . '/modules/login/templates');
    $tpl_out = $GLOBALS['tmpl']->fetch($tpl_dir . 'process.tpl');

    if(!defined('MODULE_CONTENT')) {
      define('MODULE_CONTENT', $tpl_out);
    }
  }

  function getSettings($field) {
    $sql = $GLOBALS['db']->Query("SELECT $field FROM " . PREFIX . "_modul_login WHERE Id='1'");
    $row = $sql->fetchrow();
    $sql->Close();
    return $row->$field;
  }

  function checkEmailExist($email) {
    $sql = $GLOBALS['db']->Query("SELECT Email FROM " . PREFIX . "_users WHERE Email='$email' LIMIT 1");
    $num = $sql->numrows();
    if($num == 1) return false;
    return true;
  }

  function checkEmailDomainInBlacklist($email = '') {
    if(empty($email)) return false;

    $sql = $GLOBALS['db']->Query("SELECT DomainsVerboten FROM " . PREFIX . "_modul_login WHERE Id='1'");
    $row = $sql->fetchrow();
    $DomainsVerboten = explode(',', chop($row->DomainsVerboten));
    $DomainGesendet  = explode('@',$email);

    if(in_array(@$DomainGesendet[1],$DomainsVerboten)) return false;
    return true;
  }

  function checkEmailInBlacklist($email) {
    $sql = $GLOBALS['db']->Query("SELECT EmailsVerboten FROM " . PREFIX . "_modul_login WHERE Id='1'");
    $row = $sql->fetchrow();

    $Verboten = explode(',', chop($row->EmailsVerboten));
    $Gesendet = $email;

    if(in_array($Gesendet,$Verboten)) return false;
    return true;
  }

  function isRequired($field) {
    $sql = $GLOBALS['db']->Query("SELECT {$field} FROM " . PREFIX . "_modul_login WHERE Id = 1");
    $row = $sql->fetchrow();
    if($row->$field == 1) return true;
    return false;
  }

  function userNameExists($UserName) {
    $sql = $GLOBALS['db']->Query("SELECT `UserName` FROM " . PREFIX . "_users WHERE `UserName`='$UserName'");
    $row = $sql->fetchrow();
    if(!empty($row->UserName)) return true;
    return false;
  }

// Ðåãèñòðàöèÿ íîâîãî ïîëüçîâàòåëÿ

  function registerNew($tpl_dir,$lang_file) {

    if(isset($_SESSION['cp_benutzerid']) || isset($_SESSION['cp_kennwort'])) {
      header('Location:index.php');
      exit;
    }

    $GLOBALS['tmpl']->config_load($lang_file);
    $config_vars = $GLOBALS['tmpl']->get_config_vars();
    $GLOBALS['tmpl']->assign('config_vars', $config_vars);
    $GLOBALS['config_vars'] = $config_vars;

    define('MODULE_SITE', $config_vars['LOGIN_TEXT_REGISTER']);

    $im = '';

    if(function_exists('imagettftext') && function_exists('imagepng') && $this->getSettings('AntiSpam')== 1) {
      define('ANTI_SPAM', 1);
    }

    switch($this->getSettings('IstAktiv')) {

      case '1':
        switch($_REQUEST['sub']) {

          case '':
          default :

          if(defined('ANTI_SPAM')) {
            $codeid = $this->secureCode();
            $im     = $codeid;
            $sql_sc = $GLOBALS['db']->Query("SELECT Code FROM " . PREFIX . "_antispam WHERE Id = '$codeid'");
            $row_sc = $sql_sc->fetchrow();
            $GLOBALS['tmpl']->assign('im', $im);
            $_SESSION['reg_secure'] = $row_sc->Code;
            $_SESSION['reg_secure_id'] = $codeid;
          }

          $globals =& new Globals;

          if($this->isRequired('ZeigeFirma'))    $GLOBALS['tmpl']->assign('FirmName', 1);
          if($this->isRequired('ZeigeVorname'))  $GLOBALS['tmpl']->assign('FirstName', 1);
          if($this->isRequired('ZeigeNachname')) $GLOBALS['tmpl']->assign('LastName', 1);

          $GLOBALS['tmpl']->assign('available_countries', $GLOBALS['globals']->fetchCountries());

          $tpl_out = $GLOBALS['tmpl']->fetch($tpl_dir . 'register.tpl');
          define('MODULE_CONTENT', $tpl_out);
          break;

          case 'register':
            $securecode = true;
            $error      = '';

            $UserName = (!empty($_POST['UserName'])) ? addslashes($_POST['UserName']) : '';

            if(@ereg("[^ ._A-Za-zÀ-ßà-ÿ¨¸0-9-]", $UserName) || empty($UserName))
              $error[] = $GLOBALS['config_vars']['LOGIN_WRONG_LOGIN'];

            if($this->userNameExists($UserName))
              $error[] = $GLOBALS['config_vars']['LOGIN_WRONG_L_INUSE'];

            $pass = (!empty($_POST['reg_pass'])) ? $_POST['reg_pass'] : '';

            $_POST['reg_email'] = (!empty($_POST['reg_email'])) ? chop(str_replace(' ', '', $_POST['reg_email'])) : '';
            $_POST['reg_email_return'] = (!empty($_POST['reg_email_return'])) ? chop(str_replace(' ', '', $_POST['reg_email_return'])) : '';

            if($this->isRequired('ZeigeVorname')) {
              if(empty($_POST['reg_firstname'])) $error[] = $GLOBALS['config_vars']['LOGIN_WRONG_FIRSTNAME'];
              if(@ereg("[^ _A-Za-zÀ-ßà-ÿ¨¸0-9-]", $_POST['reg_firstname']))  $error[] = $GLOBALS['config_vars']['LOGIN_WRONG_FIRSTNAME'];
            }

            if($this->isRequired('ZeigeNachname')) {
              if(empty($_POST['reg_lastname'])) $error[] = $GLOBALS['config_vars']['LOGIN_WRONG_LASTNAME'];
              if(@ereg("[^ _A-Za-zÀ-ßà-ÿ¨¸0-9-]", $_POST['reg_lastname']))  $error[] = $GLOBALS['config_vars']['LOGIN_WRONG_LASTNAME'];
            }

            if(empty($_POST['reg_email'])) $error[] = $GLOBALS['config_vars']['LOGIN_WRONG_EM_EMPTY'];
            if(empty($_POST['reg_email_return'])) $error[] = $GLOBALS['config_vars']['LOGIN_WRONG_ER_EMPTY'];
            if(!empty($_POST['reg_email_return']) && !empty($_POST['reg_email']) && $_POST['reg_email'] != $_POST['reg_email_return']) $error[] = $GLOBALS['config_vars']['LOGIN_WRONG_RETRY'];
            if(@!ereg("^[ -._A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,4})$", $_POST['reg_email']))  $error[] = $GLOBALS['config_vars']['LOGIN_WRONG_EMAIL'];


            if(empty($_POST['reg_pass'])) $error[] = $GLOBALS['config_vars']['LOGIN_WRONG_PASS'];
            if(@ereg("[^_A-Za-zÀ-ßà-ÿ¨¸0-9-]", $pass))  $error[] = $GLOBALS['config_vars']['LOGIN_WRONG_SYM_PASS'];
            if(!empty($pass) && strlen($pass) < 5) $error[] = $GLOBALS['config_vars']['LOGIN_WRONG_SHORT_PASS'];


            if(!is_array($error)) {
              if(!$this->checkEmailExist($_POST['reg_email']))  $error[] = $GLOBALS['config_vars']['LOGIN_WRONG_INUSE'];
            }

            if(!$this->checkEmailDomainInBlacklist($_POST['reg_email']))  $error[] = $GLOBALS['config_vars']['LOGIN_DOMAIN_FALSE'];
            if(!$this->checkEmailInBlacklist($_POST['reg_email']))  $error[] = $GLOBALS['config_vars']['LOGIN_EMAIL_FALSE'];

            if(defined("ANTI_SPAM") && empty($_POST['reg_secure'])) {
              $codeid = $this->secureCode();
              $im = $codeid;
              $sql_sc = $GLOBALS['db']->Query("SELECT Code FROM " . PREFIX . "_antispam WHERE Id = '$codeid'");
              $row_sc = $sql_sc->fetchrow();
              $error[] = $GLOBALS['config_vars']['LOGIN_WROND_E_SCODE'];
              $_SESSION['reg_secure'] = $row_sc->Code;
              $securecode = false;
            }


            if(defined("ANTI_SPAM") && !empty($_POST['reg_secure']) && $_POST['reg_secure'] != $_SESSION['reg_secure']) {
              $codeid = $this->secureCode();
              $im = $codeid;
              $sql_sc = $GLOBALS['db']->Query("SELECT Code FROM " . PREFIX . "_antispam WHERE Id = '$codeid'");
              $row_sc = $sql_sc->fetchrow();
              $error[] = $GLOBALS['config_vars']['LOGIN_WROND_SCODE'];
              $_SESSION['reg_secure'] = $row_sc->Code;
              $securecode = false;
            }


            if(defined("ANTI_SPAM") && !empty($_POST['reg_secure']) && $_POST['reg_secure'] == $_SESSION['reg_secure']) {
             $sql_sc = $GLOBALS['db']->Query("SELECT Id FROM " . PREFIX . "_antispam WHERE Code = '" .$_POST['reg_secure']. "'");
             $row_sc = $sql_sc->fetchrow();
             $im = $row_sc->Id;
            }


            if(is_array($error) && count($error) > 0) {
              $GLOBALS['tmpl']->assign('errors', $error);
              $GLOBALS['tmpl']->assign('im', $im);

              $globals =& new Globals;

              if($this->isRequired('ZeigeFirma'))    $GLOBALS['tmpl']->assign('ZeigeFirma', 1);
              if($this->isRequired('ZeigeVorname'))  $GLOBALS['tmpl']->assign('ZeigeVorname', 1);
              if($this->isRequired('ZeigeNachname')) $GLOBALS['tmpl']->assign('ZeigeNachname', 1);

              $GLOBALS['tmpl']->assign('available_countries', $GLOBALS['globals']->fetchCountries());
              $tpl_out = $GLOBALS['tmpl']->fetch($tpl_dir . 'register.tpl');
              define('MODULE_CONTENT', $tpl_out);

            } else {

              $free_now = false;
              $emailcode = md5(rand(100000,999999));
              switch($this->getSettings('RegTyp')) {

                case 'now':
                  $host = explode('?', redir());
                  $email_body = str_replace("%N%", "\n", $GLOBALS['config_vars']['LOGIN_MESSAGE_1']);
                  $email_body = str_replace("%NAME%", $_POST['UserName'], $email_body);
                  $email_body = str_replace("%HOST%", $host[0], $email_body);
                  $email_body = str_replace("%KENNWORT%", $_POST['reg_pass'], $email_body);
                  $email_body = str_replace("%EMAIL%", $_POST['reg_email'], $email_body);
                  $free_now = true;
				  $reg_type = 'now';
                break;

                case 'email':
                  $host = explode('?', redir());
                  $email_body = str_replace("%N%", "\n", $GLOBALS['config_vars']['LOGIN_MESSAGE_2']);
                  $email_body = str_replace("%NAME%", $_POST['UserName'], $email_body);
                  $email_body = str_replace("%KENNWORT%", $_POST['reg_pass'], $email_body);
                  $email_body = str_replace("%EMAIL%", $_POST['reg_email'], $email_body);
                  $email_body .= str_replace("%N%", "\n", $GLOBALS['config_vars']['LOGIN_MESSAGE_3']);
                  $email_body = str_replace("%REGLINK%", $host[0] . "?module=login&action=register&sub=registerfinal&emc=$emailcode", $email_body);
                  $email_body = str_replace("%HOST%", $host[0], $email_body);
                  $email_body = str_replace("%CODE%", $emailcode, $email_body);
                  $free_now = false;
				  $reg_type = 'email';
                break;

                case 'byadmin':
                  $host = explode('?', redir());
                  $email_body = str_replace("%N%", "\n", $GLOBALS['config_vars']['LOGIN_MESSAGE_2']);
                  $email_body = str_replace("%NAME%", $_POST['UserName'], $email_body);
                  $email_body = str_replace("%KENNWORT%", $_POST['reg_pass'], $email_body);
                  $email_body = str_replace("%EMAIL%", $_POST['reg_email'], $email_body);
                  $email_body = str_replace("%HOST%", $host[0], $email_body);
                  $email_body .= str_replace("%N%", "\n", $GLOBALS['config_vars']['LOGIN_MESSAGE_4']);
                  $email_body = str_replace("%HOST%", $host[0], $email_body);
                  $free_now = false;
				  $reg_type = 'byadmin';
                break;
              }

              $bodytoadmin = $GLOBALS['config_vars']['LOGIN_MESSAGE_5'];
              $bodytoadmin = str_replace("%N%", "\n", $bodytoadmin);
              $bodytoadmin = str_replace("%NAME%", $_POST['UserName'], $bodytoadmin);
              $bodytoadmin = str_replace("%EMAIL%", $_POST['reg_email'], $bodytoadmin);

              $status = ($free_now==true) ? 1 : '0';

              $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_users (
                Id,
                `UserName`,
                Kennwort,
                Vorname,
                Nachname,
                Benutzergruppe,
                Registriert,
                Status,
                Email,
                emc,
                Land,
                IpReg,
                UStPflichtig,
                Firma
              ) VALUES (
                '',
                '" . @addslashes($_POST['UserName']) . "',
                '" . md5(md5($_POST['reg_pass'])) . "',
                '" . @addslashes(htmlspecialchars($_POST['reg_firstname'])) . "',
                '" . @addslashes(htmlspecialchars($_POST['reg_lastname'])) . "',
                '3',
                '" . time() . "',
                '" . $status . "',
                '" . addslashes(htmlspecialchars($_POST['reg_email'])) . "',
                '" . $emailcode . "',
                '" . addslashes($_POST['Land']) . "',
                '" . $_SERVER['REMOTE_ADDR'] . "',
                '1',
                '" . @addslashes(htmlspecialchars($_POST['Firma'])) . "'
              )");

              if($free_now == true) {
                $_SESSION['cp_benutzerid'] = $GLOBALS['db']->InsertId();
                $_SESSION['cp_kennwort']   = md5(md5($_POST['reg_pass']));
                $_SESSION['cp_email']      = $_POST['reg_email'];
                $_SESSION['cp_loggedin']   = 1;
                $_SESSION['cp_uname']      = htmlspecialchars($_POST['UserName']);
              }

              $globals =& new Globals;
              $SystemMail     = $GLOBALS['globals']->cp_settings('Mail_Absender');
              $SystemMailName = $GLOBALS['globals']->cp_settings('Mail_Name');

              $GLOBALS['globals']->cp_mail($SystemMail, $bodytoadmin, $GLOBALS['config_vars']['LOGIN_SUBJECT_ADMIN'], $SystemMail, $SystemMailName, 'text', '');

              $GLOBALS['globals']->cp_mail($_POST['reg_email'], $email_body, $GLOBALS['config_vars']['LOGIN_SUBJECT_USER'], $SystemMail, $SystemMailName, 'text', '');

              $_SESSION['reg_secure'] = '';


			  if ($reg_type == "now")header('Location:/login/edit/');

			  if ($reg_type == "email")header('Location:/login/register/final/');

			  if ($reg_type == "byadmin")header('Location:/login/register/thanks/');


              exit;
            }
          break;

          case 'thankyou':
            $GLOBALS['tmpl']->config_load($lang_file);
            $config_vars = $GLOBALS['tmpl']->get_config_vars();
            $GLOBALS['tmpl']->assign("config_vars", $config_vars);
            $tpl_out = $GLOBALS['tmpl']->fetch($tpl_dir . 'register_thankyou.tpl');
            define('MODULE_CONTENT', $tpl_out);
          break;


          case 'registerfinal':
            if(isset($_REQUEST['emc']) && $_REQUEST['emc'] != '0') {
              $sql = $GLOBALS['db']->Query("SELECT * FROM  " . PREFIX . "_users WHERE emc = '" .addslashes($_REQUEST['emc']). "'");
              $num = $sql->numrows();
              $row = $sql->fetchrow();
              if($num == 1) {
			    $GLOBALS['tmpl']->assign('reg_type', '$reg_type');
                $GLOBALS['tmpl']->assign('final', 'ok');
                $GLOBALS['db']->Query("UPDATE " . PREFIX . "_users SET Status='1' WHERE emc = '" .addslashes($_REQUEST['emc']). "'");
                $_SESSION['cp_benutzerid'] = $row->Id;
                $_SESSION['cp_kennwort'] = $row->Kennwort;
                $_SESSION['cp_email'] = $row->Email;
                $_SESSION['cp_loggedin'] = 1;
                $_SESSION['cp_uname'] = htmlspecialchars($row->UserName);
              }
            }

            $tpl_out = $GLOBALS['tmpl']->fetch($tpl_dir . 'register_final.tpl');
            define('MODULE_CONTENT', $tpl_out);
          break;

          case 'thankadmin':
            $GLOBALS['tmpl']->config_load($lang_file);
            $config_vars = $GLOBALS['tmpl']->get_config_vars();
            $GLOBALS['tmpl']->assign("config_vars", $config_vars);
            $tpl_out = $GLOBALS['tmpl']->fetch($tpl_dir . 'register_admin.tpl');
            define('MODULE_CONTENT', $tpl_out);
          break;

        }
      break;

      case '0':
        $tpl_out = $GLOBALS['config_vars']['LOGIN_NOT_ACTIVE'];
        define('MODULE_CONTENT', $tpl_out);
      break;
    }
  }

  function passwordReminder($tpl_dir,$lang_file) {

    if(isset($_SESSION['cp_benutzerid'])) {
      header('Location:index.php');
      exit;
    }

    $GLOBALS['tmpl']->config_load($lang_file);
    $config_vars = $GLOBALS['tmpl']->get_config_vars();
    $GLOBALS['tmpl']->assign('config_vars', $config_vars);
    $GLOBALS['config_vars'] = $config_vars;
    define('MODULE_SITE', $config_vars['LOGIN_REMIND']);

    if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'confirm' && !empty($_REQUEST['email'])) {
      $sql_rem = $GLOBALS['db']->Query("SELECT KennTemp FROM " . PREFIX . "_users WHERE Email = '" .addslashes($_REQUEST['email']). "' AND KennTemp != '' AND KennTemp = '" .addslashes($_REQUEST['code']). "' LIMIT 1");
      $num_rem = $sql_rem->numrows();
      $row_rem = $sql_rem->fetchrow();
      if($num_rem == 1) {
        $GLOBALS['db']->Query("UPDATE " . PREFIX . "_users SET Kennwort = '" .$row_rem->KennTemp. "'  WHERE Email = '" .addslashes($_REQUEST['email']). "' AND KennTemp = '" .addslashes($_REQUEST['code']). "'");
      }

      $tpl_out = $GLOBALS['tmpl']->fetch($tpl_dir . 'password_ok.tpl');
      define('MODULE_CONTENT', $tpl_out);
    } else {

      if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'send' && !empty($_POST['f_mailreminder'])) {
        $sql_rem = $GLOBALS['db']->Query("SELECT Email,Vorname,Nachname FROM " . PREFIX . "_users WHERE Email = '" .addslashes($_POST['f_mailreminder']). "' LIMIT 1");
        $row_rem = $sql_rem->fetchrow();
        $num_rem = $sql_rem->numrows();
        $sql_rem->Close();

        if($num_rem == 1) {

          $globals        =& new Globals;
          $newpass        = $GLOBALS['globals']->makePass();
          $SystemMail     = $GLOBALS['globals']->cp_settings('Mail_Absender');
          $SystemMailName = $GLOBALS['globals']->cp_settings('Mail_Name');

          $GLOBALS['db']->Query("UPDATE " . PREFIX . "_users SET KennTemp = '" .md5(md5($newpass)). "' WHERE Email = '" .addslashes($_POST['f_mailreminder']). "'");

          $host = explode('?', redir());
          $body = $GLOBALS['config_vars']['LOGIN_MESSAGE_6'];
          $body = str_replace("%NAME%", $row_rem->UserName, $body);
          $body = str_replace("%PASS%", $newpass, $body);
          $body = str_replace("%HOST%", $host[0], $body);
          $body = str_replace("%LINK%", $host[0] . "?module=login&action=passwordreminder&sub=confirm&code=" .md5(md5($newpass)). "&email=" . $_POST['f_mailreminder'], $body);
          $body = str_replace("%N%", "\n", $body);
          $GLOBALS['globals']->cp_mail($_POST['f_mailreminder'], $body, $GLOBALS['config_vars']['LOGIN_SUBJECT_REMINDER'], $SystemMail, $SystemMailName, 'text', '');
        }
      }

      $tpl_out = $GLOBALS['tmpl']->fetch($tpl_dir . 'password_lost.tpl');
      define('MODULE_CONTENT', $tpl_out);
    }
  }

  function passwordChange($tpl_dir,$lang_file) {
    $GLOBALS['tmpl']->config_load($lang_file);
    $config_vars = $GLOBALS['tmpl']->get_config_vars();
    $GLOBALS['tmpl']->assign('config_vars', $config_vars);
    $GLOBALS['config_vars'] = $config_vars;

    define('MODULE_SITE', $config_vars['LOGIN_PASSWORD_CHANGE']);

    if(!isset($_SESSION['cp_benutzerid'])) {
      header('Location:index.php');
      exit;
    }

    if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'send') {

	  $error = '';

	  if (trim($_POST['old_pass']) == '') {
		$error[] = $GLOBALS['config_vars']['LOGIN_EMPTY_OLD_PASS'];
	  } elseif (trim($_POST['new_pass']) == '') {
		$error[] = $GLOBALS['config_vars']['LOGIN_EMPTY_NEW_PASS'];
	  } elseif (trim($_POST['new_pass_c']) == '') {
		$error[] = $GLOBALS['config_vars']['LOGIN_EMPTY_NEW_PASS_C'];
	  } elseif($_SESSION["cp_kennwort"] != md5(md5(trim($_POST['old_pass'])))) {
		$error[] = $GLOBALS['config_vars']['LOGIN_WRONG_OLD_PASS'];
	  } elseif(trim($_POST['new_pass']) != trim($_POST['new_pass_c'])) {
		$error[] = $GLOBALS['config_vars']['LOGIN_WRONG_EQU_PASS'];
	  } else {
	    $pass = addslashes(htmlspecialchars(trim($_POST['new_pass'])));
        if(@ereg("[^_A-Za-zÀ-ßà-ÿ¨¸0-9-]", $pass))  $error[] = $GLOBALS['config_vars']['LOGIN_WRONG_SYM_PASS'];
        if(!empty($pass) && strlen($pass) < 5) $error[] = $GLOBALS['config_vars']['LOGIN_WRONG_SHORT_PASS'];
	  }

	  if(is_array($error) && count($error) > 0) {
        $GLOBALS['tmpl']->assign('errors', $error);
      } else {
        $GLOBALS['db']->Query("UPDATE " . PREFIX . "_users SET Kennwort = '" .md5(md5($pass)). "' WHERE Email = '" .$_SESSION["cp_email"]. "' AND Kennwort = '" .$_SESSION["cp_kennwort"]. "'");
        $_SESSION['cp_kennwort'] = md5(md5($pass));
        $GLOBALS['tmpl']->assign('changeok', 1);
      }
    }
    $tpl_out = $GLOBALS['tmpl']->fetch($tpl_dir . 'password_change.tpl');
    define('MODULE_CONTENT', $tpl_out);
  }

  function delAccount($tpl_dir,$lang_file) {
    $GLOBALS['tmpl']->config_load($lang_file);
    $config_vars = $GLOBALS['tmpl']->get_config_vars();
    $GLOBALS['tmpl']->assign('config_vars', $config_vars);
    $GLOBALS['config_vars'] = $config_vars;

    define('MODULE_SITE', $config_vars['LOGIN_DELETE_ACCOUNT']);

    if(!isset($_SESSION['cp_benutzerid']) || !isset($_SESSION['cp_kennwort'])) {
      header('Location:index.php');
      exit;
    }

    if(isset($_REQUEST['delconfirm']) && $_REQUEST['delconfirm'] == 1  && UGROUP != 1) {
      $globals =& new Globals;
      $GLOBALS['globals']->delUser($_SESSION['cp_benutzerid']);
      unset($_SESSION['cp_benutzerid']);
      unset($_SESSION['cp_kennwort']);
      $GLOBALS['tmpl']->assign('delok', 1);
    }

    if(defined('UGROUP') && UGROUP == 1) {
      $GLOBALS['tmpl']->assign('admin', 1);
    }

    $tpl_out = $GLOBALS['tmpl']->fetch($tpl_dir . 'delete_account.tpl');
    define('MODULE_CONTENT', $tpl_out);
  }

  function myProfile($tpl_dir,$lang_file) {
    $GLOBALS['tmpl']->config_load($lang_file);
    $config_vars = $GLOBALS['tmpl']->get_config_vars();
    $GLOBALS['tmpl']->assign('config_vars', $config_vars);
    $GLOBALS['config_vars'] = $config_vars;
    define('MODULE_SITE', $config_vars['LOGIN_CHANGE_DETAILS']);

    if(!isset($_SESSION['cp_benutzerid']) || !isset($_SESSION['cp_kennwort'])) {
      header('Location:index.php');
      exit;
    }


    if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'update') {
      $ok     = true;
      $errors = '';
      $muster       = "[^ +_A-Za-zÀ-ßà-ÿ¨¸0-9-]";
      $muster_geb   = "([0-9]{2}).([0-9]{2}).([0-9]{4})";
      $muster_email = "^[-._A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,4})$";


      if($this->isRequired('ZeigeVorname')) {
        if(empty($_POST['Vorname']))         $errors[] = $GLOBALS['config_vars']['LOGIN_WRONG_FIRSTNAME'];
        if(ereg($muster, $_POST['Vorname'])) $errors[] = $GLOBALS['config_vars']['LOGIN_WRONG_FIRSTNAME'];
      }

      if($this->isRequired('ZeigeNachname')) {
        if(empty($_POST['Nachname']))         $errors[] = $GLOBALS['config_vars']['LOGIN_WRONG_LASTNAME'];
        if(ereg($muster, $_POST['Nachname'])) $errors[] = $GLOBALS['config_vars']['LOGIN_WRONG_LASTNAME'];
      }

      if(!empty($_POST['Strasse']) && ereg($muster, $_POST['Strasse']))           $errors[] = $GLOBALS['config_vars']['LOGIN_WRONG_STREET'];
      if(!empty($_POST['HausNr']) && ereg($muster, $_POST['HausNr']))             $errors[] = $GLOBALS['config_vars']['LOGIN_WRONG_HOUSE'];
      if(!empty($_POST['Postleitzahl']) && ereg($muster, $_POST['Postleitzahl'])) $errors[] = $GLOBALS['config_vars']['LOGIN_WRONG_ZIP'];
      if(!empty($_POST['Ort']) && ereg($muster, $_POST['Ort']))                   $errors[] = $GLOBALS['config_vars']['LOGIN_WRONG_TOWN'];
      if(!empty($_POST['Telefon']) && ereg($muster, $_POST['Telefon']))           $errors[] = $GLOBALS['config_vars']['LOGIN_WRONG_PHONE'];
      if(!empty($_POST['Telefax']) && ereg($muster, $_POST['Telefax']))           $errors[] = $GLOBALS['config_vars']['LOGIN_WRONG_FAX'];

      if(!ereg($muster_email, $_POST['Email'])) $errors[] = $GLOBALS['config_vars']['LOGIN_WRONG_EMAIL'];

      $sql = $GLOBALS['db']->Query("SELECT Email FROM " . PREFIX . "_users WHERE Email != '" .$_REQUEST['Email_Old']. "' AND Email = '" .$_POST['Email']. "'");
      $num = $sql->numrows();

      if($num > 0) $errors[] = $GLOBALS['config_vars']['LOGIN_WRONG_INUSE'];

      if(!empty($_POST['GebTag']) && !ereg($muster_geb, $_POST['GebTag'])) $errors[] = $GLOBALS['config_vars']['LOGIN_WRONG_BIRTHDAY'];

      if(!empty($_POST['GebTag']))  {
        $check_year = explode('.', $_POST['GebTag']);
        if($check_year[0] > 31)           $errors[] = $GLOBALS['config_vars']['LOGIN_WRONG_DATE'];
        if($check_year[1] > 12)           $errors[] = $GLOBALS['config_vars']['LOGIN_WRONG_MONTH'];
        if($check_year[2] < date("Y")-75) $errors[] = $GLOBALS['config_vars']['LOGIN_WRONG_YEAR'];
      }


      if(is_array($errors) && count($errors) > 0) {
        $ok = false;
        $GLOBALS['tmpl']->assign('errors', $errors);
      } else {
          $GLOBALS['db']->Query("UPDATE " . PREFIX . "_users SET
            Email        = '".addslashes(htmlspecialchars($_POST['Email']))."',
            Strasse      = '".addslashes(htmlspecialchars($_POST['Strasse']))."',
            HausNr       = '".addslashes(htmlspecialchars($_POST['HausNr']))."',
            Postleitzahl = '".addslashes(htmlspecialchars($_POST['Postleitzahl']))."',
            Ort          = '".addslashes(htmlspecialchars($_POST['Ort']))."',
            Telefon      = '".addslashes(htmlspecialchars($_POST['Telefon']))."',
            Telefax      = '".addslashes(htmlspecialchars($_POST['Telefax']))."',
            Vorname      = '".addslashes(htmlspecialchars($_POST['Vorname']))."',
            Nachname     = '".addslashes(htmlspecialchars($_POST['Nachname']))."',
            Land         = '".addslashes(htmlspecialchars($_POST['Land']))."',
            GebTag       = '".addslashes(htmlspecialchars($_POST['GebTag']))."',
            Firma        = '".@addslashes(htmlspecialchars($_POST['Firma'])) . "'
          WHERE Id = '" . $_SESSION['cp_benutzerid'] . "' AND Kennwort = '" . $_SESSION['cp_kennwort'] . "'");
        $GLOBALS['tmpl']->assign('changed', 1);
      }
    }

    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_users WHERE Id='" . $_SESSION["cp_benutzerid"] . "' LIMIT 1");
    $row = $sql->fetcharray();
    $sql->Close();

    $globals =& new Globals;
    $GLOBALS['tmpl']->assign('available_countries', $GLOBALS['globals']->fetchCountries());
    $GLOBALS['tmpl']->assign('row', $row);

    if($this->isRequired('ZeigeFirma'))    $GLOBALS['tmpl']->assign('ZeigeFirma', 1);
    if($this->isRequired('ZeigeVorname'))  $GLOBALS['tmpl']->assign('ZeigeVorname', 1);
    if($this->isRequired('ZeigeNachname')) $GLOBALS['tmpl']->assign('ZeigeNachname', 1);

    $tpl_out = $GLOBALS['tmpl']->fetch($tpl_dir . 'myprofile.tpl');
    define('MODULE_CONTENT', $tpl_out);
  }

  function showConfig($tpl_dir,$lang_file) {
    if(isset($_REQUEST['sub']) && $_REQUEST['sub'] == 'save') {
      $DomainsVerboten = str_replace(array("\r\n","\n"), ',', chop($_REQUEST['DomainsVerboten']));
      $EmailsVerboten  = str_replace(array("\r\n","\n"), ',', chop($_REQUEST['EmailsVerboten']));

      $GLOBALS['db']->Query("UPDATE " . PREFIX . "_modul_login
      SET
        RegTyp           = '$_REQUEST[RegTyp]',
        AntiSpam         = '$_REQUEST[AntiSpam]',
        IstAktiv         = '$_REQUEST[IstAktiv]',
        DomainsVerboten  = '$DomainsVerboten',
        EmailsVerboten   = '$EmailsVerboten',
        ZeigeFirma       = '$_REQUEST[ZeigeFirma]',
        ZeigeVorname     = '$_REQUEST[ZeigeVorname]',
        ZeigeNachname    = '$_REQUEST[ZeigeNachname]'
      WHERE Id = '" . $this->_config_id . "' ");
      header('Location:index.php?do=modules&action=modedit&mod=login&moduleaction=1&cp=' . SESSION);
      exit;
    }

    $sql = $GLOBALS['db']->Query("SELECT * FROM " . PREFIX . "_modul_login WHERE Id = '" . $this->_config_id . "' ");
    $row = $sql->fa();

    $row['DomainsVerboten'] = explode(',', chop($row['DomainsVerboten']));
    $row['EmailsVerboten']  = explode(',', chop($row['EmailsVerboten']));
    $GLOBALS['tmpl']->assign('row', $row);
    $GLOBALS['tmpl']->assign('content', $GLOBALS['tmpl']->fetch($tpl_dir . 'admin_config.tpl'));
  }

  function secureCode($c=0) {
    $tdel = time() - 1200;
    $GLOBALS['db']->Query("DELETE FROM " . PREFIX . "_antispam WHERE Ctime < " . $tdel . "");
    $pass = '';
    $chars = array(2,3,4,5,6,7,8,9);
    $ch = ($c!=0) ? $c : 7;
    $count = count($chars) - 1;
    srand((double)microtime() * 1000000);
    for($i = 0; $i < $ch; $i++) {
      $pass .= $chars[rand(0, $count)];
    }

    $code = $pass;
    $sql = $GLOBALS['db']->Query("INSERT INTO " . PREFIX . "_antispam (Id,Code,Ctime) VALUES ('','" .$code. "','" .time(). "')");
    $codeid = $GLOBALS['db']->InsertId();

    return $codeid;
  }
}
?>