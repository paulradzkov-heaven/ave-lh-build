{strip}

{if $imgtype == 'bild'}
	<img alt="{$imgtitle}" src="{$imglink}" border="0" {$imgsize[3]} />

{elseif $imgtype == 'bild_links'}
	<img style="margin-right:5px" align="left" alt="{$imgtitle}" src="{$imglink}" border="0" {$imgsize[3]} />

{elseif $imgtype == 'bild_rechts'}
	<img style="margin-left:5px" align="right" alt="{$imgtitle}" src="{$imglink}" border="0" {$imgsize[3]} />

{/if}

{/strip}