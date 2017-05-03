<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>{#RECOMMEND_TITLE#}</title>
<link href="/templates/{$cp_theme}/css/style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body id="body_popup">
<div id="module_header">
  <h2>{#RECOMMEND_TITLE#}</h2>
</div>
<div id="module_content">
  <p id="module_intro">{#RECOMMEND_INFO#}</p>
  <form method="post" action="/index.php">
    <fieldset>
    <legend>
    <label for="l_receiver_email">{#RECOMMEND_EMAIL_RECEIVER#}</label>
    </legend>
    <input style="width:250px" name="receiver_email" type="text" id="l_receiver_email" />
    </fieldset>
    <br />
    <fieldset>
    <legend>
    <label for="l_recommend_name">{#RECOMMEND_YOUR_NAME#}</label>
    </legend>
    <input name="recommend_name" type="text" id="l_recommend_name" style="width:250px" value="{$smarty.session.cp_uname}" />
    </fieldset>
    <br />
    <fieldset>
    <legend>
    <label for="l_recommend_email">{#RECOMMEND_YOUR_EMAIL#}</label>
    </legend>
    <input name="recommend_email" type="text" id="l_recommend_email" style="width:250px" value="{$smarty.session.cp_email}" />
    </fieldset>
    <input name="cp_theme" type="hidden" id="cp_theme" value="{$smarty.request.cp_theme}" />
    <input name="module" type="hidden" id="module" value="recommend" />
    <input name="action" type="hidden" id="action" value="recommend" />
    <input name="pop" type="hidden" id="pop" value="1" />
    <input name="sub" type="hidden" id="sub" value="send" />
    <input name="page" type="hidden" id="page" value="{$smarty.request.page}" />
    <p>
      <input type="submit" class="button" value="{#RECOMMEND_BUTTON#}" />
    </p>
  </form>
</div>
</body>
</html>
