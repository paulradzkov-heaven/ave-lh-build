<script language="javascript" src="modules/forums/js/common.js"></script>
{strip}

{if $smarty.request.print!=1}



{if $smarty.session.cp_ugroup != 2}
	<div class="forum_greeting">
			<h3>{#WelcomeStart#}, {$smarty.session.cp_forumname}!</h3>
			<p>{#WelcomeMessage#}</p>
	</div>
	<div class="forum_userpanel">
		 <ul>
			<li><a class="pg_exit" onclick="return confirm(\'{#LogoutC#}\');" href="index.php?module=login&amp;action=logout">{#Logout#}</a></li>
			<li><a class="pg_panel" href="index.php?module=forums&amp;show=myabos">{#ShowAbos#}</a></li>
			<li><a class="pg_prof" href="index.php?module=login&amp;action=profile">{#MyProfile#}</a></li>
			<li><a class="pg_prof" href="index.php?module=forums&amp;show=publicprofile">{#MyProfilePublic#}</a></li>
			<li><a class="pg_panel" href="index.php?module=forums&amp;show=ignorelist&amp;action=showlist">{#IgnoreList#}</a></li>
			<li style="width:40%;"><a style="float: left;" class="pg_panel" href="index.php?module=forums&amp;show=pn">{#PN_Name#}:</a><div style="line-height: 25px;" >&nbsp; новые: {if $PNunreaded != 0}<span class="forum_pn_unread">{$PNunreaded}</span>{else}{$PNunreaded}{/if} | прочитанные: {$PNreaded}</div></li>
		  </ul>
			<div class="clearall"><!--x--></div>
	</div>
{else}
			<div id="loginform"  class="blockborder">
		   <form method="post" action="index.php" name="login">
			  <input name="module" value="login" type="hidden" />
			  <input name="action" value="login" type="hidden" />
			  <input name="redir" type="hidden" value="{$redir}" />
			  <input name="do" type="hidden" value="login" />			  
			  <input name="SaveLogin" value="1" type="hidden" />
			  <label for="cp_login">{#LOgin#}</label>
			  <input  tabindex="1" class="loginfield" name="cp_login" type="text" style="width:99%" />
			  <label for="cp_password">{#Pass#}</label>
			  <input tabindex="2" type="password" class="loginfield" name="cp_password" style="width:99%" />
			  
				<div align="center">
			  <input tabindex="4" class="button" type="submit" value="{#Login#}" style="width:100px" /></div>
			</form>
		  <div class="dotted"><!--x--></div>
		  <a class="pg_qw" onmouseover="$.cursorMessage('{$config_vars.ReminderInfo|default:''}');" onmouseout="$.hideCursorMessage();" href="/login/reminder/">{#FLostPass#}</a>
		  <a class="pg_plus" onmouseover="$.cursorMessage('{$config_vars.Info|default:''}');" onmouseout="$.hideCursorMessage();" href="/login/register/">{#FReg#}</a>
		</div>
		<div class="forum_greeting_2">	  
		  {#FRegMessage#}
		  <h4>Правила пользования форумом</h4>
		  <p>Запрещается:</p><ul><li>Использовать ненормативную лексику на&nbsp;форуме.</li><li>Флудить (отправлять сообщения, не&nbsp;несущие никакой содержательной нагрузки, а&nbsp;также устраивать из&nbsp;форума чат).</li><li>Отправлять в&nbsp;одной теме несколько сообщений подряд. Умещайте свой ответ в&nbsp;одно сообщение.</li><li>Давать темам название, не&nbsp;отражающее содержание (например, &laquo;внимание!!&raquo;, &laquo;помогите&raquo;, и&nbsp;т. п.). Старайтесь в&nbsp;названии темы кратко выразить свою мысль и&nbsp;содержание сообщения.</li><li>Нарушать тематику разделов и&nbsp;отходить от&nbsp;темы (офф-топ), в&nbsp;последнем случае просто откройте соответствующую тему.</li><li>Злоупотреблять выбором шрифтов, цветов, смайликов и&nbsp;т. д. Используйте эти возможности по&nbsp;необходимости.</li></ul><p>При нарушении Правил форума Администратор или Модератор форума может без предварительного уведомления удалить сообщение или тему, переместить тему, отредактировать сообщение и&nbsp;предпринять иные меры (в&nbsp;том числе забанить).</p><p>Администратор или Модератор форума имеет право удалить или изменить сообщение, которое противоречит принципам нравственности, порочит и&nbsp;унижает посетителей настоящего сайта и&nbsp;форума, а&nbsp;также нарушает общепризнанные правила, обычно предъявляемые к&nbsp;размещению и&nbsp;содержанию сообщений в&nbsp;интерактивных форумах.</p><p>Все вышеуказанное не&nbsp;налагает на&nbsp;Администратора и&nbsp;/ или Модераторов форума ответственность за&nbsp;содержание форума и&nbsp;сообщений, за&nbsp;исключением личной ответственности каждого за&nbsp;высказанное им&nbsp;самим.</p>
	</div>

{/if}	
<div class="clearall"><!--x--></div>
{/if}

{/strip}