{if $smarty.session.cp_benutzerid}
<div class="mod_shop_myordersbox ">
<strong>{#MyOrders#}</strong>
<br />
<br />
<img class="absmiddle" src="{$shop_images}arrow.gif" alt="" /> <a href="index.php?module=shop&amp;action=myorders">{#OrderOverviewShowLink#}</a><br />
<img class="absmiddle" src="{$shop_images}arrow.gif" alt="" /> <a href="index.php?module=shop&amp;action=mydownloads">{#DownloadsOverviewShowLink#}</a><br />
{if $WishListActive==1}
<img class="absmiddle" src="{$shop_images}arrow.gif" alt="" /> <a href="index.php?module=shop&amp;action=wishlist">{#Wishlist#}</a>
&nbsp; &laquo; <a {popup sticky=false text=$config_vars.WishlistInf} href="#">{#WhatsThat#}</a>
{/if}
</div>
{/if}