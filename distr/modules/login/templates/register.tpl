<script type="text/javascript" src="/templates/default/js/validate/jquery.validate.min.js"></script>
<script type="text/javascript" src="/templates/default/js/validate/cmxforms.js"></script>
<script type="text/javascript">

$().ready(function(){ldelim}
	
	
	$("#registr").validate({ldelim}
		
		
		rules: {ldelim}
			UserName: {ldelim} required: true,
					     	   minlength: 2
					  {rdelim},
			reg_email: {ldelim}
							   required: true,
							   email: true,
							   remote: "/templates/default/js/validate/form.php"
					   {rdelim},
					   
			reg_email_return: {ldelim}
							   required: true,
							   email: true,
							   equalTo: "#reg_email"
					   {rdelim},
			reg_pass: {ldelim}
							   required: true,
							   minlength: 5
					    {rdelim},
			reg_secure: "required"
		{rdelim},
		
	    messages: {ldelim}
			UserName: {ldelim}
				required: "Пожалуйста введите имя пользователя",
				minlength: "Имя пользователя должно содержать не менее двух символов"	
			{rdelim},
			reg_email: {ldelim}
							   required: "Пожалуйста введите email",
							   email: "Пожалуйста введите корректный email адрес",
							   remote: jQuery.format("Такой email адрес уже существует")
					   {rdelim},
			reg_email_return: {ldelim}
							   required: "Пожалуйста введите email",
							   email: "Пожалуйста введите корректный email адрес",
							   equalTo: "Пожалуйста введите такой же email адрес"
					   {rdelim},
			reg_pass: {ldelim}
							   required: "Пожалуйста введите пароль",
							   minlength: "Пароль должен содержать не менее 5 символов"
					    {rdelim},
			reg_secure: "Пожалуйста введите защитный код"
		
		{rdelim},
		
		success: function(label) {ldelim}
			label.html("&nbsp;").addClass("checked");
		{rdelim}
		
	{rdelim});

	$("#reg_email").blur(function() {ldelim}
		
		$("#reg_email").valid();
	{rdelim});
	
	$("#reg_secure").blur(function() {ldelim}
			$("#reg_secure").valid();
	{rdelim});
	
	$("#UserName").blur(function() {ldelim}
			$("#UserName").valid();
	{rdelim});
	
{rdelim});

</script>

<div id="module_header">
  <h2>{#LOGIN_TEXT_REGISTER#}</h2>
</div>

<div id="module_content" style="width:600px;">
  {if $errors}
    <ul>
      {foreach from=$errors item=e}
        <li class="regerror">{$e}</li>
      {/foreach}
    </ul>
  {/if}

  <form method="post" action="/login/register/verification/" id="registr">
    <div class="formleft"><label for="UserName">{#LOGIN_YOUR_LOGIN#}</label></div>
    <div class="formright">
      <input name="UserName" id="UserName" type="text" style="width:200px" value="{$smarty.post.UserName|strip_tags|stripslashes}" size="80" />
    </div>

   <div class="clear"></div>
   

  {if $FirstName==1}
    <div class="formleft"><label for="l_reg_firstname">{#LOGIN_YOUR_FIRSTNAME#}</label></div>
    <div class="formright">
      <input name="reg_firstname" type="text" id="reg_firstname" style="width:200px" value="{$smarty.post.reg_firstname|strip_tags|stripslashes}" size="80" />
    </div>

    <div class="clear"></div>
    
  {/if}


  {if $LastName==1}
    <div class="formleft"><label for="reg_lastname">{#LOGIN_YOUR_LASTNAME#}</label></div>
    <div class="formright">
      <small><span id="h_reg_lastname"></span></small>
      <input name="reg_lastname" type="text" id="reg_lastname" style="width:200px" value="{$smarty.post.reg_lastname|strip_tags|stripslashes}" size="50" />
    </div>

    <div class="clear"></div>
   
  {/if}

  {if $FirmName==1}
    <div class="formleft"><label for="l_reg_Firma">{#LOGIN_YOUR_COMPANY#}</label></div>
    <div class="formright">
      <input name="Firma" type="text" id="Firma" style="width:200px" value="{$smarty.post.Firma|strip_tags|stripslashes}" size="80" />
    </div>

    <div class="clear"></div>
    
  {/if}

  <div class="formleft"><label for="reg_email">{#LOGIN_YOUR_MAIL#}</label></div>
  <div class="formright">
    <small><span id="h_reg_email"></span></small>
    <input name="reg_email" type="text" id="reg_email" style="width:200px" value="{$smarty.post.reg_email|strip_tags|stripslashes}" size="80" />
  </div>

  <div class="clear"></div>
  

  <div class="formleft"><label for="l_reg_email_return">{#LOGIN_MAIL_CONFIRM#}</label></div>
  <div class="formright">
    <small><span id="h_reg_email_return"></span></small>
    <input name="reg_email_return" type="text" id="reg_email_return" style="width:200px" value="{$smarty.post.reg_email_return|strip_tags|stripslashes}" size="80" />
  </div>

  <div class="clear"></div>
  <br/>

  <div class="formleft"><label for="l_reg_pass">{#LOGIN_PASSWORD#}</label></div>
  <div class="formright">
    <small><span id="h_reg_pass"></span></small>
    <input name="reg_pass" type="text" id="reg_pass" style="width:100px" value="{$smarty.post.reg_pass|strip_tags|stripslashes}" size="25" />
  </div>

  <div class="clear"></div>
  
  <div class="formleft"><label for="land">{#LOGIN_YOUR_COUNTRY#}</label></div>
  <div class="formright">
    <select name="Land" id="Land">
      {if $smarty.request.action=='register' && $smarty.request.sub == 'register'}
      {assign var=sL value=$smarty.request.Land}
      {else}
      {assign var=sL value=$row->Land|default:$defland}
      {/if}
      {foreach from=$available_countries item=land}
        <option value="{$land->LandCode}" {if $sL==$land->LandCode}selected{/if}>{$land->LandName}</option>
      {/foreach}
    </select>
  </div>

  <div class="clear"></div>
    
  {if $im != ''}

    <div class="formleft">{#LOGIN_SECURITY_CODE#}</div>
    <div class="formright">
      <img src="/nospam_{$im}.jpeg" alt="" width="121" height="41" border="0" />
    </div>
   
   <div class="clear"></div>
   <br/>

   <div class="formleft"><label for="l_reg_secure">{#LOGIN_SECURITY_CODER#}</label></div>
   <div class="formright">
     <small><span id="h_reg_pass"></span></small>
     <input name="reg_secure" type="text" id="reg_secure" style="width:100px" value="" size="25" />
   </div>

   <div class="clear"></div>
	
  {/if}


  <div class="formleft">&nbsp;</div>
  <div class="formright"><input class="button" type="submit" value="{#LOGIN_BUTTON_SUBMIT#}" />
  </div>
 
 <div class="clear"></div>
 
</form>
</div>