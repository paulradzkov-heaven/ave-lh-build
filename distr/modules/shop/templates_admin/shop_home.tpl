<div class="pageHeaderTitle" style="padding-top: 7px;">
<div class="h_module"></div>
    <div class="HeaderTitle"><h2>{#ModName#}</h2></div>
    <div class="HeaderText">{#WelcomeText#}</div>
</div>
<br />
{include file="$source/shop_topnav.tpl"}
<br />
{* <table width="100%" border="0" cellpadding="0" cellspacing="1" class="tableborder">
  <tr>
    <td><h4 class="navi">{#QuickStartHeader#}</h4><br><br></td>
  </tr>
  <tr>
    <td>

      <table width="100%" border="0" cellspacing="1" cellpadding="8">
        <tr>
          <td class="first"><strong>{#QuickStartOrdersName#}</strong></td>
          <td class="second">
		  <select style="width: 200px;" onchange="javascript: if (this.selectedIndex!=0) self.location=this.value;">
			<option value="">{#QuickStartTarget#}</option>
			<option value="javascript:cp_pop('index.php?do=user&cp={$sess}','980','780','1','shopimport');">{#QuickStartUserSearch#}</option>
			<option value="javascript:cp_pop('index.php?do=user&action=new&cp={$sess}&pop=1','980','780','1','shopimport');">{#QuickStartNewUser#}</option>
			<option value="">------------------------------------</option>
			<option value="index.php?do=modules&action=modedit&mod=shop&moduleaction=showorders&cp={$sess}">{#QuickStartOrders#}</option>
			<option value="index.php?do=modules&action=modedit&mod=shop&moduleaction=showorders&cp={$sess}&date=today">{#QuickStartOrdersToday#}</option>
			</select>
		  </td>
        </tr>

        <tr>
          <td width="300" class="first"><strong>{#QuickStartProductsConfig#}</strong></td>
          <td class="second">
		  <select style="width: 200px;" onchange="javascript: if (this.selectedIndex!=0) self.location=this.value;">
			<option value="">{#QuickStartTarget#}</option>
			<option value="index.php?do=modules&action=modedit&mod=shop&moduleaction=products&cp={$sess}">{#Products#}</option>
			<option value="javascript:cp_pop('index.php?do=modules&action=modedit&mod=shop&moduleaction=product_new&cp={$sess}&pop=1','980','780','1','');">{#ProductPNew#}</option>
			<option value="index.php?do=modules&action=modedit&mod=shop&moduleaction=variants_categories&cp={$sess}">{#VariantsCats#}</option>
			<option value="index.php?do=modules&action=modedit&mod=shop&moduleaction=units&cp={$sess}">{#ProductUnits#}</option>
			<option value="index.php?do=modules&action=modedit&mod=shop&moduleaction=product_categs&cp={$sess}">{#ProductCategs#}</option>
			<option value="javascript:cp_pop('index.php?do=modules&action=modedit&mod=shop&moduleaction=new_categ&cp={$sess}&pop=1&Id=false','980','780','1','');">{#ProductCategNewLink#}</option>
			</select>
		  </td>
        </tr>
        <tr>
          <td class="first"><strong>{#QuickStartConfig#}</strong></td>
          <td class="second">
		  <select style="width: 200px;" onchange="javascript: if (this.selectedIndex!=0) self.location=this.value;">
			<option value="">{#QuickStartTarget#}</option>
			<option value="index.php?do=modules&action=modedit&mod=shop&moduleaction=settings&cp={$sess}">{#ShopSettings#}</option>
			<option value="index.php?do=modules&action=modedit&mod=shop&moduleaction=email_settings&cp={$sess}">{#EmailSettings#}</option>
			<option value="index.php?do=modules&action=modedit&mod=shop&moduleaction=helppages&cp={$sess}">{#EditHelpPages#}</option>
			</select>
			</td>
        </tr>
        <tr>
          <td class="first"><strong>{#QuickStartPayment#}</strong></td>
          <td class="second">
		  <select style="width: 200px;" onchange="javascript: if (this.selectedIndex!=0) self.location=this.value;">
			<option value="">{#QuickStartTarget#}</option>
			<option value="index.php?do=modules&action=modedit&mod=shop&moduleaction=paymentmethods&cp={$sess}">{#PaymentMethods#}</option>
			<option value="index.php?do=modules&action=modedit&mod=shop&moduleaction=shipping&cp={$sess}">{#SShipper#}</option>
			</select>
		  </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
*}

<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
        <tr>
          <td><h4 class="navi">{#QuickStartTopseller#}</h4><br>{#QuickStartTopSellerInf#}</td>
        </tr>
        <tr>
          <td style="padding:5px">
            <table width="100%" border="0" cellpadding="5" cellspacing="1" class="tableborder">
              <tr>
                <td>&nbsp;</td>
                <td><strong>{#CatName#}</strong></td>
                <td><strong>{#ProductBought#}</strong></td>
              </tr>
              {foreach from=$TopSeller item=ts}
              <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows"> {assign var="num" value=$num+1}
                <td width="25"> {if $num<10}0{/if}{$num}.</td>
                <td> <a href="javascript:void(0);" onclick="cp_pop('index.php?do=modules&action=modedit&mod=shop&moduleaction=edit_product&cp={$sess}&pop=1&Id={$ts->Id}','980','740','1','edit_product');">{$ts->ArtName|truncate:55}</a></td>
                <td>{$ts->Bestellungen}</td>
              </tr>
              {/foreach}
            </table>
          <br />
		  <div align="right">
		  <a href="index.php?do=modules&action=modedit&mod=shop&moduleaction=1&cp={$sess}&sub=topseller">&raquo; {#QuickStartShowHundret#}</a>		  </div>
		  </td>
        </tr>
      </table>
    </td>
    <td valign="top">&nbsp;</td>
    <td valign="top">
      <table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
        <tr>
          <td><h4 class="navi">{#QuickStartFlopseller#}</h4><br>{#QuickStartFlopSellerInf#}</td>
        </tr>
        <tr>
          <td style="padding:5px">
            <table width="100%" border="0" cellpadding="5" cellspacing="1" class="tableborder">
              <tr>
                <td>&nbsp;</td>
                <td><strong>{#CatName#}</strong></td>
                <td><strong>{#ProductBought#}</strong></td>
              </tr>
			  {assign var="num" value="0"}
              {foreach from=$FlopSeller item=ts}
              <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows"> {assign var="num" value=$num+1}
                <td width="25"> {if $num<10}0{/if}{$num}.</td>
                <td> <a href="javascript:void(0);" onclick="cp_pop('index.php?do=modules&action=modedit&mod=shop&moduleaction=edit_product&cp={$sess}&pop=1&Id={$ts->Id}','980','740','1','edit_product');">{$ts->ArtName|truncate:55}</a></td>
                <td>{$ts->Bestellungen}</td>
              </tr>
              {/foreach}
            </table>
			<br />
		  <div align="right">
		  <a href="index.php?do=modules&action=modedit&mod=shop&moduleaction=1&cp={$sess}&sub=flopseller">&raquo; {#QuickStartShowHundret#}</a>		  </div>

          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<br />

<table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
  <tr>
    <td><h4 class="navi">{#QuickStartRez#}</h4><br>{#QuickStartRezInf#}10</td>
  </tr>
  <tr>
    <td style="padding:1px">
      <table width="100%" border="0" cellpadding="5" cellspacing="1" class="tableborder">
        {foreach from=$Rez item=r}
  <tr style="background-color: #eff3eb;" onmouseover="this.style.backgroundColor='#dae0d8';" onmouseout="this.style.backgroundColor='#eff3eb';" id="table_rows"> {assign var="num" value=$num+1}
    <td>
	<a href="javascript:void(0);" onclick="cp_pop('index.php?do=modules&action=modedit&mod=shop&moduleaction=edit_comments&cp={$sess}&pop=1&Id={$r->ArtNr}','980','740','1','edit_comments');">{$r->Artname}</a>	</td>
    </tr>
        {/foreach}
      </table>
	  <br />
		  <div align="right"><a href="index.php?do=modules&action=modedit&mod=shop&moduleaction=1&cp={$sess}&sub=rez">&raquo; {#QuickStartShowHundret#}</a></div>
    </td>
  </tr>
</table>