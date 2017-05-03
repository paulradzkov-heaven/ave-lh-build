{if $display_comments==1}
<h3>{#COMMENT_SITE_TITLE#}{if $closed==1} {#COMMENT_SITE_CLOSED#}{/if}</h3>


<div class="blockborder noprint" align="center">
  {if $cancomment==1 && $closed!=1}
    <a href="javascript:void(0);" onclick="popup('/index.php?docid={$smarty.request.id}&amp;module=comment&amp;action=form&amp;pop=1&amp;cp_theme={$cp_theme}&amp;page={$page}','comment','500','600','1')">{#COMMENT_SITE_ADD#}</a> | 
  {/if}

    <a href="#end">{#COMMENT_LAST_COMMENT#}</a> 

  {if $ugroup==1}
    | 
  {if $closed==1}
    <a href="javascript:void(0);" onclick="popup('/index.php?DokId={$smarty.request.id}&amp;module=comment&amp;action=open&amp;pop=1','comment','50','50','1');">{#COMMENT_SITE_OPEN#}</a>
  {else}
    <a href="javascript:void(0);" onclick="popup('/index.php?DokId={$smarty.request.id}&amp;module=comment&amp;action=close&amp;pop=1','comment','50','50','1');">{#COMMENT_SITE_CLOSE#}</a>
  {/if}

{/if}
</div>

{foreach from=$comments item=c name=co}
  {if $smarty.request.subaction=='showonly' && $smarty.request.comment_id==$c.Id}
    <div class="mod_comment_highlight">
  {/if}
<div style="float:left; line-height:25px;">
          <a name="n{$c.Id}"></a><a onmouseover="$.cursorMessage('{$config_vars.COMMENT_INFO}');" onmouseout="$.hideCursorMessage();" href="javascript:void(0);" onclick="popup('/index.php?module=comment&amp;action=postinfo&amp;AuthorId={$c.Author_Id}&amp;pop=1&amp;Id={$c.Id}&amp;cp_theme={$cp_theme}','comment','500','300','1');">{$c.Author|stripslashes|escape:html}</a> ({$c.Erstellt|date_format:"%d-%m-%Y ã."} {#COMMENT_USER_TIME#} {$c.Erstellt|date_format:"%H:%M"}){if $ugroup==1} IP:{$c.AIp}{/if}</div>
        
        <div style="float:right" class="noprint">
        {strip}
          <a onmouseover="$.cursorMessage('{$config_vars.COMMENT_ANSWER_LINK}');" onmouseout="$.hideCursorMessage();" href="javascript:void(0);" onclick="popup('/index.php?parent={$c.Id}&amp;docid={$smarty.request.id}&amp;module=comment&amp;action=form&amp;pop=1&amp;cp_theme={$cp_theme}&amp;page={$page}','comment','500','520','1');">
            <img src="/modules/comment/templates/images/comments_reply.png" alt="{$config_vars.COMMENT_ANSWER_LINK}" border="0" />
          </a>
        {if $ugroup==1 || $c.Author_Id==$smarty.session.cp_benutzerid}
          <a onmouseover="$.cursorMessage('{$config_vars.COMMENT_EDIT_LINK}');" onmouseout="$.hideCursorMessage();" href="javascript:void(0);" onclick="popup('/index.php?parent={$c.Id}&amp;docid={$smarty.request.id}&amp;module=comment&amp;action=edit&amp;pop=1&amp;Id={$c.Id}&amp;cp_theme={$cp_theme}','comment','500','620','1');">
            <img src="/modules/comment/templates/images/comments_edit.png" alt="{$config_vars.COMMENT_EDIT_LINK}" border="0" />
          </a>
        {/if}
        
        {if $ugroup==1}
          <a onmouseover="$.cursorMessage('{$config_vars.COMMENT_DELETE_LINK}');" onmouseout="$.hideCursorMessage();" href="javascript:void(0);" onclick="popup('/index.php?parent={$c.Id}&amp;docid={$smarty.request.id}&amp;module=comment&amp;action=delete&amp;pop=1&amp;Id={$c.Id}','comment','100','100','1');">
            <img src="/modules/comment/templates/images/comments_delete.png" alt="{$config_vars.COMMENT_DELETE_LINK}" border="0" />
          </a>
        {if $c.Status!=1}
          <a onmouseover="$.cursorMessage('{$config_vars.COMMENT_UNLOCK_LINK}');" onmouseout="$.hideCursorMessage();" href="javascript:void(0);" onclick="popup('/index.php?parent={$c.Id}&amp;docid={$smarty.request.id}&amp;module=comment&amp;action=unlock&amp;pop=1&amp;Id={$c.Id}','comment','100','100','1');">
            <img src="/modules/comment/templates/images/comments_unlock.png" alt="{$config_vars.COMMENT_UNLOCK_LINK}" border="0" />
          </a>
        {else}
          <a onmouseover="$.cursorMessage('{$config_vars.COMMENT_LOCK_LINK}');" onmouseout="$.hideCursorMessage();" href="javascript:void(0);" onclick="popup('/index.php?parent={$c.Id}&amp;docid={$smarty.request.id}&amp;module=comment&amp;action=lock&amp;pop=1&amp;Id={$c.Id}','comment','100','100','1');">
            <img src="/modules/comment/templates/images/comments_lock.png" alt="{$config_vars.COMMENT_LOCK_LINK}" border="0" />
          </a>
        {/if}
        {/if}
        {/strip}
        </div>

  <div class="mod_comment_steam noprint"><!--Empty--></div>
	<div class="mod_comment_top noprint"><div><!--Empty--></div></div>
  	<div class="mod_comment_body">
    {$c.Text|stripslashes|wordwrap:80:"\n":true}
       {if $c.Geaendert > 1}<br /><span class="mod_comment_changed noprint">{#COMMENT_TEXT_CHANGED#} {$c.Erstellt|date_format:"%d-%m-%Y ã."} {#COMMENT_USER_TIME#} {$c.Erstellt|date_format:"%H:%M"}</span>{/if}
  </div>
    <div class="mod_comment_bottom noprint"><div><!--Empty--></div></div>       
       
    {if $smarty.request.subaction=='showonly' && $smarty.request.comment_id==$c.Id}
    </div>
    {/if}


  {foreach from=$c.Subcomments item=sc}
    <div style="margin-left:30px">
    {if $smarty.request.subaction=='showonly' && $smarty.request.comment_id==$sc.Id}
      <div class="mod_comment_highlight">
    {/if}
  

          <div style="float:left; line-height:25px;"><a name="n{$sc.Id}"></a><a onmouseover="$.cursorMessage('{$config_vars.COMMENT_INFO}');"  onmouseout="$.hideCursorMessage();"  href="javascript:void(0);" onclick="popup('/index.php?module=comment&amp;action=postinfo&amp;AuthorId={$sc.Author_Id}&amp;pop=1&amp;Id={$sc.Id}&amp;cp_theme={$cp_theme}','comment','500','300','1');">{$sc.Author|stripslashes|escape:html}</a> ({$sc.Erstellt|date_format:"%d-%m-%Y ã."} {#COMMENT_USER_TIME#} {$sc.Erstellt|date_format:"%H:%M"}){if $ugroup==1}	IP:{$sc.AIp}{/if}</div>
         <div style="float:right" class="noprint">
          {strip}
          	
            {if $ugroup==1 || $sc.Author_Id==$smarty.session.cp_benutzerid}
              <a onmouseover="$.cursorMessage('{$config_vars.COMMENT_EDIT_LINK}');" onmouseout="$.hideCursorMessage();" href="javascript:void(0);" onclick="popup('/index.php?parent={$sc.Id}&amp;docid={$smarty.request.id}&amp;module=comment&amp;action=edit&amp;pop=1&amp;Id={$sc.Id}&amp;cp_theme={$cp_theme}','comment','500','620','1');"><img src="/modules/comment/templates/images/comments_edit.png" alt="{$config_vars.COMMENT_EDIT_LINK}" border="0" /></a>
            {/if}
          
            {if $ugroup==1}
              <a onmouseover="$.cursorMessage('{$config_vars.COMMENT_DELETE_LINK}');" onmouseout="$.hideCursorMessage();" href="javascript:void(0);" onclick="popup('/index.php?parent={$sc.Id}&amp;docid={$smarty.request.id}&amp;module=comment&amp;action=delete&amp;pop=1&amp;Id={$sc.Id}','comment','100','100','1');"><img src="/modules/comment/templates/images/comments_delete.png" alt="{$config_vars.COMMENT_DELETE_LINK}" border="0" /></a>
            
            {else}
              &nbsp;
            {/if}
          {/strip}
          </div>
          
            
  <div class="mod_comment_steam noprint"><!--Empty--></div>
	<div class="mod_comment_top noprint"><div><!--Empty--></div></div>
  	<div class="mod_comment_body">
       {$sc.Text|stripslashes|wordwrap:50:"\n":true}
         {if $sc.Geaendert > 1}<br /><span class="mod_comment_changed noprint">{#COMMENT_TEXT_CHANGED#} {$c.Erstellt|date_format:"%d-%m-%Y ã."} {#COMMENT_USER_TIME#} {$c.Erstellt|date_format:"%H:%M"}</span>{/if}
  	</div>
    <div class="mod_comment_bottom noprint"><div><!--Empty--></div></div>       

  
      {if $smarty.request.subaction=='showonly' && $smarty.request.comment_id==$sc.Id}
    </div>
      {/if}
    </div>
{/foreach}
{/foreach}
  
  {if $smarty.foreach.co.last}<a name="end"></a>{/if}
{/if}


