{strip}

{if $imgtype == 'bild'}
	<img alt="{$imgtitle}" src="{$imglink}" border="0" {$imgsize[3]} />

{elseif $imgtype == 'bild_links'}
	<img style="padding-right:6px" align="left" alt="{$imgtitle}" src="{$imglink}" border="0" {$imgsize[3]} />

{elseif $imgtype == 'bild_rechts'}
	<img style="padding-left:6px" align="right" alt="{$imgtitle}" src="/thumb.php?file={$imglink}&x_width=162" border="0" />

{/if}

{/strip}