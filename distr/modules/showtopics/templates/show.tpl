<ul>

{foreach from=$topics item=c name=co}
<li>
<!-- Заголовок топика -->
<a href="/index.php?module=forums&show=showtopic&toid={$c.id}&fid={$c.forum_id}">{$c.title}</a><br />

{foreach from=$c.uid item=sc}
<!-- Пользователь создавший топик -->
<a href="/index.php?module=forums&show=userprofile&user_id={$sc.BenutzerId}" class="name">{$sc.BenutzerName|stripslashes|escape:html}</a>
{/foreach}

<!-- Дата создания топика -->
{$c.datum|date_format:'%d.%m.%Y'}
</li>
{/foreach}

</ul>