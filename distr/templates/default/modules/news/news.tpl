<div align="center">
<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0"><tr><td></td></tr></table>
{foreach from=$news item=n}
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-right:dotted; border-right-color:#000000; border-right-width:1px; border-bottom:dotted; border-bottom-color:#000000; border-bottom-width:1px;">
  <tr>
    <td colspan="2" bgcolor="#eec00a">
    <div align="center"><strong style="color:#ffffff;">{$n.Titel}</strong></div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="left">
    <img src="/modules/news/preimages/{$n.preimage}" alt="{$n.Titel}" align="left" />
    {$n.pretext}<br />
    <a href="{$n.link}">{#NEWS_DOC_DETAILED#}</a></div></td>
  </tr>
  <tr>
    <td bgcolor="#f5f5f5"><div align="left">&nbsp;{$n.date}</div></td>
    <td bgcolor="#f5f5f5"><div align="right">{#NEWS_DOC_VIEW#} {$n.Geklickt} | {#NEWS_DOC_COMMENT#} {$n.c_nums}&nbsp;</div></td>
  </tr>
</table>
<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0"><tr><td></td></tr></table>
{/foreach}
{$page_nav}
</div>