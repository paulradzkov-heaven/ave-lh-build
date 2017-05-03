<div id="pageHeaderTitle" style="padding-top: 7px;">
  <div class="h_dboptions"></div>
  <div style="margin-top: 0pt; padding-top: 2px; font-size: 24px;">
    <h2>{#DB_SUB_TITLE#}</h2>
  </div>
  <div style="padding-left: 40px; padding-bottom: 12px; clear: both;">{#DB_TIPS#}</div>
</div>
<div class="upPage"></div>
<br>
<script language="javascript">
function warn_db() {ldelim}
  if(confirm('{#DB_ACTION_WARNING#}')) return true;
  return false;
{rdelim}

function warn_db_rest() {ldelim}
  if(confirm('{#DB_ACTION_RESET#}')) return true;
  return false;
{rdelim}

</script>
<form onSubmit="return warn_db();" action="index.php?do=dbsettings&cp={$sess}&action=dboption" method="post" name="dbop" id="dbop" style="display:inline;">
  <table width="100%" border="0" cellpadding="8" cellspacing="1" class="tableborder">
    <tr>
      <td colspan="2" class="tableheader">{#DB_OPTION_LIST#}</td>
    </tr>
    <tr class="first">
      <td width="250" class="second"><center>
          <select style="width:240px" size="22" name="ta[]" multiple="multiple">

            {$tabellen}

          </select>
          <br />
          <br />
          <input type="submit" id="rest" class="button" value="{#DB_BUTTON_ACTION#}" />
        </center></td>
      <td class="first"><table width="100%" border="0" cellspacing="1" cellpadding="3">
          <tr>
            <td width="5%"><img src="{$tpl_dir}/images/util/db_optimize.jpg" alt="" hspace="3" /></td>
            <td width="5%" nowrap="nowrap"><div align="left">
                <input style="border:0px" type="radio" name="whattodo" checked="checked" value="optimize" />
              </div></td>
            <td><strong>{#DB_OPTIMIZE_DATABASE#}</strong><br />
              {#DB_OPTIMIZE_INFO#}</td>
          </tr>
          <tr>
            <td width="5%"><img src="{$tpl_dir}/images/util/db_repair.jpg" alt="" hspace="3" /></td>
            <td width="5%" nowrap="nowrap"><div align="left">
                <input style="border:0px" type="radio" name="whattodo" value="repair" />
              </div></td>
            <td><strong>{#DB_REPAIR_DATABASE#}</strong><br />
              {#DB_REPAIR_INFO#}</td>
          </tr>
          <tr>
            <td width="5%"><img src="{$tpl_dir}/images/util/db_save.jpg" alt="" hspace="3" /></td>
            <td width="5%" nowrap="nowrap"><div align="left">
                <input style="border:0px" type="radio" name="whattodo" value="dump" />
              </div></td>
            <td><strong>{#DB_BACKUP_DATABASE#}</strong><br />
              {#DB_BACKUP_INFO#}</td>
          </tr>
        </table></td>
    </tr>
  </table>
</form>
<br />
<form onSubmit="return warn_db_rest();" action="index.php?do=dbsettings&cp={$sess}" method="post" enctype="multipart/form-data">
  <table width="100%"  border="0" cellpadding="8" cellspacing="1" class="tableborder">
    <tr>
      <td class="tableheader">{#DB_RESTORE_TITLE#}</td>
    </tr>
    <tr>
      <td class="second"><table width="100%"  border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td>{if $msg} {$msg} <br />
              {/if}
              <input name="file" type="file" size="40" />
              <input type="submit" id="rest" class="button" value="{#DB_BUTTON_RESTORE#}" />
              <input type="hidden" name="restore" value="1" />
            </td>
          </tr>
        </table></td>
    </tr>
  </table>
</form>
