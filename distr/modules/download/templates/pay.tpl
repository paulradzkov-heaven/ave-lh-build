{if $excl_pay==1}
	{if $excl_chk==1}
	  <p>������! � ��� ��� ���� �� �������� ����� �����.</p>
	{else}
		{if $pay_sum==0 || $diff==0}
			<p>��������:��� �� ��������� ���������� ������ ����. �� ������ ��������� ���, ���������� �� �������� <a href="index.php?module=download&action=showfile&file_id={$file_id}&categ={$cat_id}">�����</a>.</p>
		{else}
			<form name="pay" method="post" action="https://merchant.webmoney.ru/lmi/payment.asp" id="pay">
      	<table align="center" cellpadding="0" cellspacing="0" width="98%">
          <tr>
              <td align="center"  class="mod_download_dlbox">
                 <h2>����� �� �������� �����</h2>
              </td>
          </tr>
          <tr>
              <td align="center"><br><br>
				        {if $diff<>$pay_sum}
					      	����� ���������� {$diff} {$pay_val}. ��������:�� ����� �� ��� �������� ����� ��������� ������� �����.
                                                                               ���� �� ������ ��� ���������, �� ��� ��������, ��� ��������� ����� ����� 
                                                                               ���� �������� ����� ����� ��������� ������ � ��� ���������� ��������� ���� ������� � ���������.
					      	<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="{$diff}"><br>
				        {else}
					      	����� ���������� {$pay_sum} {$pay_val}.
					      	<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="{$pay_sum}"><br>
				        {/if}
								<br><input type="hidden" name="LMI_PAYMENT_DESC" value="{$pay_descr}">
								<input type="hidden" name="LMI_PAYMENT_NO" value="{$pay_num}">
								<input type="hidden" name="LMI_PAYEE_PURSE" value="{$wm_purse}">
								<input type="hidden" name="LMI_SIM_MODE" value="0">
								<input type="hidden" name="pay_fileid" value="{$file_id}">
								<input type="hidden" name="pay_count" value="{$pay_count}">
								<input type="hidden" name="pay_userid" value="{$user_id}">
								<input type="hidden" name="pay_userIP" value="{$user_IP}">
          	</td>
          </tr>
          <tr>
              <td align="center">
					<input type="submit" value="��������">
              </td>
          </tr>
       </table>
      </form>
		{/if}
	{/if}
{elseif $pay_type==0}
	{if $pay_sum==0}
		<p>��������:��� �� ��������� ���������� ������ ����. �� ������ ��������� ���, ���������� �� �������� <a href="index.php?module=download&action=showfile&file_id={$file_id}&categ={$cat_id}">�����</a>.</p>
	{else}
		<form name="pay" method="post" action="https://merchant.webmoney.ru/lmi/payment.asp" id="pay">
    	<table align="center" cellpadding="0" cellspacing="0" width="98%">
        <tr>
            <td align="center"  class="mod_download_dlbox">
               <h2>����� �� �������� �����</h2>
            </td>
        </tr>
        <tr>
            <td align="center"><br><br>
			        ��������� ����� ���������� {$pay_sum} {$pay_val}.<br><br>
			        ����� <input type="text" name="LMI_PAYMENT_AMOUNT" value="{$pay_sum}" onblur="if (this.value>{$pay_sum}) this.value={$pay_sum};"> {$pay_val}<br>
							<br><input type="hidden" name="LMI_PAYMENT_DESC" value="{$pay_descr}">
							<input type="hidden" name="LMI_PAYMENT_NO" value="{$pay_num}">
							<input type="hidden" name="LMI_PAYEE_PURSE" value="{$wm_purse}">
							<input type="hidden" name="LMI_SIM_MODE" value="0">
							<input type="hidden" name="pay_fileid" value="{$file_id}">
							<input type="hidden" name="pay_count" value="{$pay_count}">
							<input type="hidden" name="pay_userid" value="{$user_id}">
							<input type="hidden" name="pay_userIP" value="{$user_IP}">
        	</td>
        </tr>
        <tr>
            <td align="center">
				<input type="submit" value="��������">
            </td>
        </tr>
     </table>
    </form>
	{/if}
{elseif $pay_type==1}
	{if $pay_sum==0 || $diff==0}
		<p>��������:��� �� ��������� ���������� ������ ����. �� ������ ��������� ���, ���������� �� ��������<a href="index.php?module=download&action=showfile&file_id={$file_id}&categ={$cat_id}">�����</a>.</p>
	{else}
		<form name="pay" method="post" action="https://merchant.webmoney.ru/lmi/payment.asp" id="pay">
    	<table align="center" cellpadding="0" cellspacing="0" width="98%">
        <tr>
            <td align="center"  class="mod_download_dlbox">
               <h2>����� �� �������� �����</h2>
            </td>
        </tr>
        <tr>
            <td align="center"><br><br>
			        {if $diff<>$pay_sum}
				      	����� ���������� {$diff} {$pay_val}. ��������:�� ����� �� ��� �������� ����� ��������� ������� �����.
                                                                         ���� �� ������ ��� ���������, �� ��� ��������, ��� ��������� ����� ����� 
                                                                         ���� �������� ����� ����� ��������� ������ � ��� ���������� ��������� ���� ������� � ���������.
				      	<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="{$diff}"><br>
			        {else}
				      	����� ���������� {$pay_sum} {$pay_val}.
				      	<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="{$pay_sum}"><br>
			        {/if}
							<br><input type="hidden" name="LMI_PAYMENT_DESC" value="{$pay_descr}">
							<input type="hidden" name="LMI_PAYMENT_NO" value="{$pay_num}">
							<input type="hidden" name="LMI_PAYEE_PURSE" value="{$wm_purse}">
							<input type="hidden" name="LMI_SIM_MODE" value="0">
							<input type="hidden" name="pay_fileid" value="{$file_id}">
							<input type="hidden" name="pay_count" value="{$pay_count}">
							<input type="hidden" name="pay_userid" value="{$user_id}">
							<input type="hidden" name="pay_userIP" value="{$user_IP}">
        	</td>
        </tr>
        <tr>
            <td align="center">
				<input type="submit" value="��������">
            </td>
        </tr>
     </table>
    </form>
	{/if}
{/if}