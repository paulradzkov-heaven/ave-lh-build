<select name="Elter" id="Elter">
  <option value="0">{$name_empty}</option>
  {foreach from=$rubs item=r}
    <optgroup label="{$r->RubrikName}"></optgroup>
    {foreach name=e from=$navi_entries item=ne}
      {if $r->Id == $ne->Rubrik}
      <option value="{$ne->Id}____{$ne->Ebene+1}" {if $row_doc->ElterNavi==$ne->Id}selected{/if}>&nbsp; {$ne->Titel|escape:html|stripslashes}</option>
        {foreach from=$ne->ebene_2 item=item_2}
          <option value="{$item_2->Id}____{$item_2->Ebene+1}" {if $row_doc->ElterNavi==$item_2->Id}selected{/if}>&nbsp;&nbsp;&nbsp;&nbsp;- {$item_2->Titel|escape:html|stripslashes} </option>
            {foreach from=$ne->ebene_3 item=item_3}
              <option value="{$item_3->Id}" {if $row_doc->ElterNavi==$item_3->Id}selected{/if}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- {$item_3->Titel|escape:html|stripslashes} </option>
            {/foreach}
        {/foreach}
      {/if}
    {/foreach}
  {/foreach}
</select>
