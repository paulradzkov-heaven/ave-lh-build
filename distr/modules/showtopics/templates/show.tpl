<ul>

{foreach from=$topics item=c name=co}
<li>
<!-- ��������� ������ -->
<a href="/index.php?module=forums&show=showtopic&toid={$c.id}&fid={$c.forum_id}">{$c.title}</a><br />

{foreach from=$c.uid item=sc}
<!-- ������������ ��������� ����� -->
<a href="/index.php?module=forums&show=userprofile&user_id={$sc.BenutzerId}" class="name">{$sc.BenutzerName|stripslashes|escape:html}</a>
{/foreach}

<!-- ���� �������� ������ -->
{$c.datum|date_format:'%d.%m.%Y'}
</li>
{/foreach}

</ul>