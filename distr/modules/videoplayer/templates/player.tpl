<script type='text/javascript' src='/modules/videoplayer/player/swfobject.js'></script>

<div id='video{$Id}'>This div will be replaced</div>

<script type='text/javascript'>
  var s1 = new SWFObject('/modules/videoplayer/player/player.swf','ply','{if $Width!='0' && $Width!=''}{$Width}{else}300{/if}','{if $Height!='0' && $Height!=''}{$Height+20}{else}20{/if}','9');
  s1.addParam("allowfullscreen","{$AllowFullScreen}");
  s1.addParam("allowscriptaccess","{$AllowScriptAccess}");
  s1.addParam('wmode','opaque');
  s1.addParam("seamlesstabbing","true");
  s1.addParam("flashvars","file={$FileName}{if $Duration!='' && $Duration!='0'}&duration={$Duration}{/if}{if $ImagePreview!=''}&image={$ImagePreview}{/if}{if $BufferLength!=''}&bufferlength={$BufferLength}{/if}");
  s1.write('video{$Id}');
</script>