{strip}
<form method="post" class="ctrlrequest" action="{$formaction}">

  <table>

    <tr>
{foreach from=$ctrlrequest item=items key=selname}
      <td>

        <label>{$items.titel} </label> 

        <select name="fld[{$selname}]">

          <option value=''>���</option>
          {html_options values=$items.options output=$items.options selected=$items.selected}

        </select>

      </td>
{/foreach}
      <td><input type="submit" class="button" value="��������" /></td>

    </tr>

  </table>

</form>
{/strip}