{include file="header.tpl"}

<table cellspacing="0" cellpadding="0" width="100%" align="center">
  {if $conf.userlogreq == 1 && $user == ''}
    <tr>
      <td align="center">
      {$lang.msg_login}
      </td>
    </tr>
  {else}
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
  {/if}
</table>

{include file="footer.tpl"}
