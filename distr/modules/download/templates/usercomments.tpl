{if $Kommentare==1}
<a name="comments"></a>
<h1>{#CommentsW#}</h1>
<br /><br />
{if $Comments}
<div class="mod_download_commentbox">
<table width="100%" border="0" cellpadding="2" cellspacing="0">
{foreach from=$Comments item=c}
<tr>
<td>
<a href="javascript:void(0);" {popup sticky=false text=$c->Kommentar width=500}>{$c->Titel|stripslashes|truncate:50}</a>
</td>
<td>
{$c->Name|stripslashes}
</td>
<td align="right">
{$c->Datum|date_format:$config_vars.DateFormatAll}</td>
</tr>
{/foreach}
</table>
</div>
{else}
{#NoComments#}
{/if}


<div class="mod_download_spacer"></div>
<a name="comment_new"></a>
<h1>{#CommentNew#}</h1>
<br />
{#CommentInf#}
<form method="post">
{if $Spam==1}
<br />
<h1 class="mod_download_nospam">{#PleaseNoSpam#}</h1>
<br />
{#NoSpamInf#}
<br />
<br />
{/if}
<label for="c_title">{#TitleC#}</label><br />
{if $NoTitle==1}
<div class="mod_download_commenterror">{#NoTitle#}</div>
{/if}
<input name="Titel" type="text" id="c_title" size="40" value="{$smarty.post.Titel|stripslashes|escape:html}" />
<br />
<label for="c_comment">{#YourComment#}</label><br />
{if $NoComment==1}
<div class="mod_download_commenterror">{#NoComment#}</div>
{/if}

<textarea name="Kommentar" cols="45" rows="5" id="c_comment">{$smarty.post.Kommentar|stripslashes|escape:html}</textarea><br />
<label for="c_name">{#NameY#}</label><br />
{if $NoName==1}
<div class="mod_download_commenterror">{#NoName#}</div>
{/if}
<input name="Name" type="text" id="c_name" size="40" value="{$smarty.post.Name|stripslashes|escape:html}" /><br />
<label for="c_email">{#EmailY#}</label><br />
{if $NoEmail==1}
<div class="mod_download_commenterror">{#NoMail#}</div>
{/if}
<input name="Email" type="text" id="c_email" size="40" value="{$smarty.post.Email|stripslashes|escape:html}" />
<input type="hidden" name="fileaction" value="comment" /><br />

{if $anti_spam == 1}{#SecureCode#}<br />{/if}

{if $CodeCheck=='False'}
<a name="code_wrong"></a>
<div class="mod_download_commenterror">{#WrongCode#}</div>
{/if}

<table border="0" cellpadding="2" cellspacing="0">
  <tr>
{if $anti_spam == 1}
    <td><img src="/nospam_{$im}.jpeg" alt="" width="121" height="41" border="0" /></td>
  </tr>
  <tr>
    <td><input name="scode" type="text" maxlength="7" style="font-size:18px; text-align:center; width:118px;" id="sCode" /></td>
{/if}
    <td><input type="submit" class="button" value="{#ButtonSend#}" /></td>
  </tr>
</table>

</form>

{if $CodeCheck=='False'}
<script>location.href='#code_wrong';</script>
{/if}

{/if}