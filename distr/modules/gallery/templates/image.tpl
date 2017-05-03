<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>{$titel|stripslashes|default:'-'}</title>

</head>
{strip}
<body style="margin:0px;overflow:{if $scrollbars==1}auto{else}hidden{/if}">

{if $mediatype=='avi'}
<object id="MediaPlayer" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112" height="350" width="100%">
<param name="animationatStart" value="false">
<param name="autostart" value="false">
<param name="URL" value="{$image}">
<param name="volume" value="-200">
<embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" name="MediaPlayer" src="{$image}" autostart="0" displaysize="0" showcontrols="1" showdisplay="0" showtracker="1" showstatusbar="1" height="350" width="100%">
</object>
{elseif $mediatype=='mov'}
<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" width="350" height="100%" codebase="http://www.apple.com/qtactivex/qtplugin.cab">
<param name="src" value="{$image}">
<param name="autoplay" value="false">
<param name="controller" value="true">
<param name="target" value="myself"> 
<param name="type" value="video/quicktime">
<embed TARGET="myself" src="{$image}" width="100%" height="350" autoplay="false" controller="true" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/">
</embed>
</object>
{else}
<a onclick="window.close();" href="javascript:void(0);"><img border="0" src="{$image}" alt="" /></a>
{/if}

</body>
{/strip}
</html>