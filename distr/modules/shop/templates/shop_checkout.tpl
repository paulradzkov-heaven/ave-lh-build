<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
   {if $smarty.request.print!=1}
    <td valign="top" class="mod_shop_left">
	<!-- SHOP - NAVI -->
	<div id="leftnavi" style="margin:0px; padding:0px;">
	{$ShopNavi}	</div>
	
	<!-- FLOAT END -->
	<div style="clear:both"></div>
	
	<!-- USER - PANEL -->
	{$UserPanel}
	
	<!-- USER - ORDERS -->
	{$MyOrders}

	<!-- INFOBOX -->
	{$InfoBox}	</td>
   {/if}
    <td valign="top" id="contents_middle_shop2">

{* <!-- GLOBAL JS -->
      <script type="text/javascript" src="modules/shop/js/shop.js"></script> *}
	  <div class="mod_shop_topnav"><a class="mod_shop_navi" href="{$ShopStartLink}">{#PageName#}</a> {#PageSep#} <a class="mod_shop_navi" href="{$ShowBasketLink}">{#ShopBasket#}</a> {#PageSep#} {#ShopPaySite#}</div>

     
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top">
            <table border="0" cellpadding="6" cellspacing="0" class="mod_shop_checkoutborder">
              <tr>
                <td> <strong>{#PaymentNewCust#}</strong> </td>
              </tr>
              <tr>
                <td height="150"> {#PaymentRegNew#} <br />
                  <br />
                  <a href="index.php?module=login&action=register"><img src="{$shop_images}register.gif" alt="{#PaymentRegNewLink#}" border="" /></a>
				  {* &gt;&gt; <strong>{#PaymentRegNewLink#}</strong> *}
				  </td>
              </tr>
            </table>
          </td>
          <td valign="top">&nbsp;&nbsp;</td>
          <td width="50%" valign="top">
            <table width="100%" border="0" cellpadding="6" cellspacing="0" class="mod_shop_checkoutborder">
              <tr>
                <td> <strong>{#PaymentAllreadyCust#}</strong> </td>
              </tr>
              <tr>
                <td height="150" valign="top">
                  <form method="post" action="index.php">
				  
				  {#PaymentAllCust#}<br /><br />
                    {if $login=='false'}
                    <div class="mod_shop_warn">{#LoginFalse#}</div>
					{else}
					
                    {/if}
					<table>
				  <tr>
				  <td>
                    <label for="shop_modul_forum_email">{#LoginLog#}</label>
                   </td>
				   <td>
                    <input class="mod_shop_inputfields" name="shop_cp_loginemail" type="text" id="shop_modul_forum_email" />
                   </td>
				   </tr>
				   <tr>
				   <td>
                    <label for="shop_modul_forum_kennwort">{#Pass#}</label>
                     </td>
					<td>
                    <input class="mod_shop_inputfields"  name="shop_cp_loginkennwort" type="password" id="shop_modul_forum_kennwort" />
                   </td>
				   </tr>
				   <tr>
				   <td>&nbsp;</td>
				   <td>
                    <input name="do" value="login" type="hidden" />
                    <input name="module" value="shop" type="hidden" />
                    <input name="action" value="checkout" type="hidden" />
					<input class="absmiddle" type="image" src="{$shop_images}login.gif" alt="{#BLogin#}" />
					</td>
					</tr>
				  </table>
                    {* <input class="button" type="submit" value="{#BLogin#}" /> *}
					
                  </form>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="150">
		  {if $GastBestellung==1}
            <table border="0" cellpadding="6" cellspacing="0" class="mod_shop_checkoutborder">
              <tr>
                <td> <strong>{#PaymentGuestTitle#}</strong></td>
              </tr>
              <tr>
                <td> {#PaymentGuest#} <br />
                  <br />
				  <form method="post" action="index.php">
				  <input type="hidden" name="module" value="shop" />
				   <input type="hidden" name="action" value="checkout" />
				    <input type="hidden" name="create_account" value="no" />
				  <input class="absmiddle" type="image" src="{$shop_images}guest_order.gif" alt="{#PaymentGuestLink#}" />
				  </form>
                  {* &gt;&gt; <a href="index.php?module=shop&amp;action=checkout&amp;create_account=no"><strong>{#PaymentGuestLink#}</strong></a> *}
				  </td>
              </tr>
            </table>
		{/if}
          </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
      
    <!-- FOOTER -->

<p>&nbsp;</p><p>&nbsp;</p>
{$FooterText}
<br />
  </td>
</tr>
</table>