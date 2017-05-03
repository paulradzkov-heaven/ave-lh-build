<?php
/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

class Recommend {

  var $_recommend_link_tpl     = 'recommend_link.tpl';
  var $_recommend_form_tpl     = 'recommend_form.tpl';
  var $_recommend_thankyou_tpl = 'recommend_thankyou.tpl';

  function displayLink() {

// !переписано
//    $GLOBALS['tmpl']->assign('page', base64_encode(redir()));
		if ($GLOBALS['config']['doc_rewrite']) {
			$urlPref = strtr($_SERVER['HTTP_HOST'], array('http://'=>'', 'www.'=>''));
			$urlPref = 'http://www.'.$urlPref.$_REQUEST['urldetectUrl'];
			$GLOBALS['tmpl']->assign('page', base64_encode($urlPref));
		} else {
			$GLOBALS['tmpl']->assign('page', base64_encode(redir()));
		}

		$GLOBALS['tmpl']->assign('cp_theme', $GLOBALS['mod']['cp_theme']);
		$GLOBALS['tmpl']->display($GLOBALS['mod']['tpl_dir'] . $this->_recommend_link_tpl);
	}

  function displayForm($cp_theme) {
    $GLOBALS['tmpl']->assign('cp_theme', $cp_theme);
    $GLOBALS['tmpl']->display($GLOBALS['mod']['tpl_dir'] . $this->_recommend_form_tpl);
  }

  function sendForm($cp_theme) {
    $globals = new Globals;
    $Mail_Absender = $GLOBALS['globals']->cp_settings('Mail_Absender');
    $Mail_Name = $GLOBALS['globals']->cp_settings('Mail_Name');

    $message = $GLOBALS['mod']['config_vars']['RECOMMEND_MESSAGE'];
    $message = str_replace("%N%", "\n", $message);
    $message = str_replace("%PAGE%", base64_decode($_POST['page']), $message);
    $message = str_replace("&amp;", "&", $message);

    $GLOBALS['globals']->cp_mail($_POST['receiver_email'], $message, $GLOBALS['mod']['config_vars']['RECOMMEND_SUBJECT'], $_POST['recommend_email'], $_POST['recommend_name'], 'text');
    $GLOBALS['tmpl']->display($GLOBALS['mod']['tpl_dir'] . $this->_recommend_thankyou_tpl);
  }
}
?>