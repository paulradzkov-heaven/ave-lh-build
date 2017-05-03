<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#UploadProg#}</h2></div>
    <div class="HeaderText">&nbsp;</div>
</div>
<h4>{#UploadProgT#}</h4>
<div style="width:99%; padding:10px;height:200px; overflow:auto; border:1px solid #ccc">
{foreach from=$arr item=t}{$t}{/foreach}
</div>
<br />
&raquo;&nbsp;<a href="index.php?do=modules&action=modedit&mod=gallery&moduleaction=add&id={$smarty.request.id}&cp={$sess}">{#ImagesMore#}</a>
<br />
&raquo;&nbsp;<a href="index.php?do=modules&action=modedit&mod=gallery&moduleaction=1&cp={$sess}">{#GalView#}</a>