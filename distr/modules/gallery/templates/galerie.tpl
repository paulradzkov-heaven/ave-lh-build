{strip}
{if $row_gs->TypeOut==3}
	<link rel="stylesheet" href="/modules/gallery/templates/css/jquery.lightbox.packed.css" type="text/css" media="screen" />
	<script type="text/javascript" src="/modules/gallery/templates/js/jquery.lightbox.min.js"></script>

{elseif $row_gs->TypeOut==4}
	<link rel="stylesheet" href="/modules/gallery/templates/css/jquery.lightbox.packed.css" type="text/css" media="screen" />
	<script type="text/javascript" src="/modules/gallery/templates/js/jquery.lightbox.min.js"></script>
{/if}

{assign var=count value=0}

{if $row_gs->ZeigeBeschreibung==1}
	<h3>{$row_gs->GName}</h3>
	{$row_gs->Beschreibung}
{/if}

{assign var=maximages value=$row_gs->MaxZeile}
<div class="gal">
{foreach from=$images item=f}
	{assign var=count value=$count+1}
	<div class="galimages_border">
		<div class="mod_gal_imgcontainer">
        
        	<script type="text/javascript"> $(document).ready(function(){ldelim} $('.galimages_border a').lightbox(); {rdelim}); </script>
            
				{assign var=deftext value=$f.BildBeschr|default:$config_vars.NoDescr}
	
				{if $row_gs->TypeOut==2}
					<a onmouseover="$.cursorMessage('{$deftext|escape:html}');" onmouseout="$.hideCursorMessage();" href="/index.php?module=gallery&amp;pop=1&amp;iid={$f.Id}" target="_blank" class="lightbox-enabled">
					<img src="/modules/gallery/thumb.php?file={$f.Pfad}&amp;type={$f.Type}&amp;folder={$f.GPfad}" alt="{$f.BildTitel|escape:html|stripslashes}" border="0" />
					
				{elseif $row_gs->TypeOut==3}
					<a onmouseover="$.cursorMessage('{$deftext|escape:html}');" onmouseout="$.hideCursorMessage();" href="/modules/gallery/uploads/{$f.GPfad}{$f.Pfad}" rel="lightbox[group]" title="{$deftext}" class="lightbox-enabled">
					<img  src="/modules/gallery/thumb.php?file={$f.Pfad}&amp;type={$f.Type}&amp;folder={$f.GPfad}" alt="{$f.BildTitel|escape:html|stripslashes}" border="0" />
					
				{elseif $row_gs->TypeOut==4}
					<a onmouseover="$.cursorMessage('{$deftext|escape:html}');" onmouseout="$.hideCursorMessage();" href="/modules/gallery/uploads/{$f.GPfad}{$f.Pfad}" class="lightview" rel="gallery[myset]" title="{$deftext}" class="lightbox-enabled">
					<img  src="/modules/gallery/thumb.php?file={$f.Pfad}&amp;type={$f.Type}&amp;folder={$f.GPfad}" alt="{$f.BildTitel|escape:html|stripslashes}" border="0" />
					
				{else}
					<img  onmouseover="$.cursorMessage('{$deftext|escape:html}');" onmouseout="$.hideCursorMessage();" src="/modules/gallery/thumb.php?file={$f.Pfad}&amp;type={$f.Type}&amp;folder={$f.GPfad}" alt="{$f.BildTitel|escape:html|stripslashes}" border="0" />
				{/if}
                
                
				
            
   			{if $row_gs->ZeigeTitel==1}
				<span><em>{$f.BildTitel|stripslashes|default:$config_vars.NoTitle}
                {if $row_gs->ZeigeGroesse==1}
					- {$f.Size} kb
				{/if}
				</em></span>
			{/if}


				{if $row_gs->TypeOut==2}
					</a>
				{elseif $row_gs->TypeOut==3}
					</a>
				{elseif $row_gs->TypeOut==4}
					</a>
				{else}
					
				{/if}


		</div>
	</div>

	{if $count % $maximages == 0}
		<div style="clear:both"></div>
	{/if}
{/foreach}
</div>
<div style="clear:both"></div>

{if $more_images==1}
	<a href="javascript:void(0);" onclick="popup('index.php?module=gallery&amp;pop=1&amp;sub=allimages&amp;gal_id={$gal_id}&amp;cp_theme={$cp_theme}','comment','720','750','1');">{#MoreImages#}</a>
{/if}

{$page_nav}

{assign var=more_images value=''}
{/strip}