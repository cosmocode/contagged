<br><br>
<table cellspacing="0" cellpadding="0" width="100%" align="center">
  {if $list == ''}
    <tr>
      <td align="center">
        {$lang.err_noentries}
      </td>
    </tr>
  {else}
    {* $list is a concatenation of multiple list_entry.tpl *}
    {$list}
  {/if}
</table>
<br><br><br>
