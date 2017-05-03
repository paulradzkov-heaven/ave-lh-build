{strip}

{if cp_perm('docs')}
{include file='documents/nav.tpl'}
{/if}

{if cp_perm('rubriken') || cp_perm('rubs')}
{include file='rubs/nav.tpl'}
{/if}

{if cp_perm('abfragen')}
{include file='queries/nav.tpl'}
{/if}

{if cp_perm('navigation')}
{include file='navigation/nav.tpl'}
{/if}

{if cp_perm('vorlagen') || cp_perm('vorlagen_multi') || cp_perm('vorlagen_loesch') || cp_perm('vorlagen_edit') || cp_perm('vorlagen_neu')}
{include file='templates/nav.tpl'}
{/if}

{if cp_perm('modules')}
{include file='modules/nav.tpl'}
{/if}

{if cp_perm('user')}
{include file='user/nav.tpl'}
{/if}

{if cp_perm('group')}
{include file='groups/nav.tpl'}
{/if}

{if cp_perm('gen_settings')}
{include file='settings/nav.tpl'}
{/if}

{if cp_perm('dbactions')}
  {include file='dbactions/nav.tpl'}
{/if}

{if cp_perm('logs')}
  {include file='logs/nav.tpl'}
{/if}


<li><a href="index.php?do=settings&amp;sub=clrcache&amp;cp={$sess}">{#SETTINGS_CLEAR_CACHE#}</a></li>


{/strip}