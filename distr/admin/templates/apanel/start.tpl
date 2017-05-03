{strip}
<div class="pageHeaderTitle">
  <div class="h_start"></div>
  <div class="HeaderTitle"><h2>{#MAIN_WELCOME#}</h2></div>
  <div class="HeaderText">{#MAIN_WELCOME_INFO#}</div>
</div>
<div class="upPage"></div>

<table width="100%" border="0" cellpadding="5" cellspacing="5">
  <tr>
    <td width="50%">
{if cp_perm('docs')}
      <div id="docsTasks">
        <a href="index.php?do=docs&amp;cp={$sess}">
          <div class="taskTitle">{#MAIN_LINK_DOCUMENT#}</div>
          <div class="taskDescription">{#MAIN_LINK_DOC_TIPS#}</div>
        </a>
      </div>
{else}
      <div id="docsTasks_no">
          <div class="taskTitle">{#MAIN_LINK_DOCUMENT#}</div>
          <div class="taskDescription">{#MAIN_LINK_DOC_TIPS#}</div>
      </div>
{/if}
  	</td>

  	<td width="50%">
{if cp_perm('rubriken') || cp_perm('rubs')}
      <div id="rubsTasks">
        <a href="index.php?do=rubs&amp;cp={$sess}">
          <div class="taskTitle">{#MAIN_LINK_RUBRICS#}</div>
          <div class="taskDescription">{#MAIN_LINK_RUBRIK_TIP#}</div>
        </a>
      </div>
{else}
      <div id="rubsTasks_no">
          <div class="taskTitle">{#MAIN_LINK_RUBRICS#}</div>
          <div class="taskDescription">{#MAIN_LINK_RUBRIK_TIP#}</div>
      </div>
{/if}
    </td>

    <td rowspan="12" valign="top">
      {* <!-- STATISTIC --> *}
      <div id="previewSnip">
        <div class="title">{#MAIN_STAT#}</div><br />
        <div class="text">{#MAIN_STAT_DOCUMENTS#}</div><div class="numeric">{$cnts.documents}</div><br />
        <br />
        <div class="text">{#MAIN_STAT_RUBRICS#}</div><div class="numeric">{$cnts.rubrics}</div><br />
        <br />
        <div class="text">{#MAIN_STAT_QUERIES#}</div><div class="numeric">{$cnts.queries}</div><br />
        <br />
        <div class="text">{#MAIN_STAT_TEMPLATES#}</div><div class="numeric">{$cnts.templates}</div><br />
        <br />
        <div class="text">{#MAIN_STAT_MODULES#}</div><div class="numeric">{$cnts.modules_0+$cnts.modules_1}</div><br />
        <br />
        {if $cnts.modules_0}
          <div class="text">{#MAIN_STAT_MODULES_OFF#}</div><div class="numeric">{$cnts.modules_0|default:0}</div><br />
          <br />
        {/if}
        <div class="text">{#MAIN_STAT_USERS#}</div><div class="numeric">{$cnts.users_0+$cnts.users_1}</div><br />
        <br />
        {if $cnts.users_0}
          <div class="text">{#MAIN_STAT_USERS_WAIT#}</div><div class="numeric">{$cnts.users_0|default:0}</div><br />
          <br />
        {/if}
      </div>
      <div style="background: transparent url({$tpl_dir}/images/util/tear.jpg) no-repeat scroll left bottom;">&nbsp;</div><br />
      <br />

      {* <!-- MODULES --> *}
      {if $modules && cp_perm('modules')}
        <div id="previewSnip">
          <div class="title">{#MAIN_QUICK_MODULE#}</div><br />
          {foreach from=$modules item=modul}
            <div class="text">
              &raquo;&nbsp;<a href="index.php?do=modules&action=modedit&mod={$modul->ModulPfad}&moduleaction=1&cp={$sess}">{$modul->ModulName}</a>
            </div><br />
            <br />
          {/foreach}
        </div>
        <div style="background: transparent url({$tpl_dir}/images/util/tear.jpg) no-repeat scroll left bottom;">&nbsp;</div>
      {/if}
    </td>
  </tr>

  <tr>
    <td width="50%">
{if cp_perm('abfragen')}
      <div id="queryTasks">
        <a href="index.php?do=queries&amp;cp={$sess}">
          <div class="taskTitle">{#MAIN_LINK_QUERYES#}</div>
          <div class="taskDescription">{#MAIN_LINK_QUERY_TIP#}</div>
        </a>
      </div>
{else}
      <div id="queryTasks_no">
          <div class="taskTitle">{#MAIN_LINK_QUERYES#}</div>
          <div class="taskDescription">{#MAIN_LINK_QUERY_TIP#}</div>
      </div>
{/if}
    </td>

    <td width="50%">
{if cp_perm('navigation')}
      <div id="naviTasks">
        <a href="index.php?do=navigation&amp;cp={$sess}">
          <div class="taskTitle">{#MAIN_LINK_NAVIGATION#}</div>
          <div class="taskDescription">{#MAIN_LINK_NAVI_TIP#}</div>
        </a>
      </div>
{else}
      <div id="naviTasks_no">
          <div class="taskTitle">{#MAIN_LINK_NAVIGATION#}</div>
          <div class="taskDescription">{#MAIN_LINK_NAVI_TIP#}</div>
      </div>
{/if}
  	</td>
  </tr>

  <tr>
    <td width="50%">
{if cp_perm('vorlagen') || cp_perm('vorlagen_multi') || cp_perm('vorlagen_loesch') || cp_perm('vorlagen_edit') || cp_perm('vorlagen_neu')}
      <div id="templTasks">
        <a href="index.php?do=templates&amp;cp={$sess}">
          <div class="taskTitle">{#MAIN_LINK_TEMPLATES#}</div>
          <div class="taskDescription">{#MAIN_LINK_TEMPLATES_TIP#}</div>
        </a>
      </div>
{else}
      <div id="templTasks_no">
          <div class="taskTitle">{#MAIN_LINK_TEMPLATES#}</div>
          <div class="taskDescription">{#MAIN_LINK_TEMPLATES_TIP#}</div>
      </div>
{/if}
  	</td>

  	<td width="50%">
{if cp_perm('modules')}
      <div id="moduleTasks">
        <a href="index.php?do=modules&amp;cp={$sess}">
          <div class="taskTitle">{#MAIN_LINK_MODULES#}</div>
          <div class="taskDescription">{#MAIN_LINK_MODULES_TIP#}</div>
        </a>
      </div>
{else}
      <div id="moduleTasks_no">
          <div class="taskTitle">{#MAIN_LINK_MODULES#}</div>
          <div class="taskDescription">{#MAIN_LINK_MODULES_TIP#}</div>
      </div>
{/if}
  	</td>
  </tr>

  <tr>
    <td width="50%">
{if cp_perm('user')}
      <div id="userTasks">
        <a href="index.php?do=user&amp;cp={$sess}">
          <div class="taskTitle">{#MAIN_LINK_USERS#}</div>
          <div class="taskDescription">{#MAIN_LINK_USER_TIP#}</div>
        </a>
      </div>
{else}
      <div id="userTasks_no">
          <div class="taskTitle">{#MAIN_LINK_USERS#}</div>
          <div class="taskDescription">{#MAIN_LINK_USER_TIP#}</div>
      </div>
{/if}
  	</td>

  	<td width="50%">
{if cp_perm('group')}
      <div id="groupTasks">
        <a href="index.php?do=groups&amp;cp={$sess}">
          <div class="taskTitle">{#MAIN_LINK_GROUPS#}</div>
          <div class="taskDescription">{#MAIN_LINK_UGROUP_TIP#}</div>
        </a>
      </div>
{else}
      <div id="groupTasks_no">
          <div class="taskTitle">{#MAIN_LINK_GROUPS#}</div>
          <div class="taskDescription">{#MAIN_LINK_UGROUP_TIP#}</div>
      </div>
{/if}
  	</td>
  </tr>

  <tr>
    <td width="50%">
{if cp_perm('gen_settings')}
      <div id="settTasks">
        <a href="index.php?do=settings&amp;cp={$sess}">
          <div class="taskTitle">{#MAIN_LINK_SETTINGS#}</div>
          <div class="taskDescription">{#MAIN_LINK_SETTINGS_TIP#}</div>
        </a>
      </div>
{else}
      <div id="settTasks_no">
          <div class="taskTitle">{#MAIN_LINK_SETTINGS#}</div>
          <div class="taskDescription">{#MAIN_LINK_SETTINGS_TIP#}</div>
      </div>
{/if}
  	</td>

  	<td width="50%">
{if cp_perm('dbactions')}
      <div id="dbTasks">
        <a href="index.php?do=dbsettings&amp;cp={$sess}">
          <div class="taskTitle">{#MAIN_LINK_DATABASE#}</div>
          <div class="taskDescription">{#MAIN_LINK_DB_TIP#}</div>
        </a>
      </div>
{else}
      <div id="dbTasks_no">
          <div class="taskTitle">{#MAIN_LINK_DATABASE#}</div>
          <div class="taskDescription">{#MAIN_LINK_DB_TIP#}</div>
      </div>
{/if}
  	</td>
  </tr>

</table>
{/strip}