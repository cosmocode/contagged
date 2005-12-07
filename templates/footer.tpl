  </td>
</tr>

<tr>
  <td class="buttonrow" align="right">
    <table width="100%"><tr>
      <td align="left">
        <a href="orgs.php"><img src="pix/orgs.png" border="0" width="22" height="22" align="middle">{$lang.orgs}</a>
      </td>
      <td align="right">
      {if $dn}
        <a href="entry.php?dn={$dn|escape:url}&mode=vcf"><img src="pix/vcard.png" border="0" width="22" height="22" align="middle">{$lang.vcfexport}</a>
      {/if}
      {if $list}
        <a href="index.php?filter={$filter|escape:url}&export=csv"><img src="pix/csv.png" border="0" width="22" height="22" align="middle">{$lang.csvexport}</a>
      {/if}
      {if $user}
          <a href="import.php"><img src="pix/import.png" border="0" width="22" height="22" align="middle">{$lang.vcfimport}</a> 
        {if $dn}
          {if $smarty.request.mode == 'edit'}
            <a href="entry.php?dn={$dn|escape:url}"><img src="pix/show.png" border="0" width="22" height="22" align="middle">{$lang.show}</a>
          {elseif $smarty.request.mode != 'copy'}
            <a href="entry.php?dn={$dn|escape:url}&mode=edit"><img src="pix/edit.png" border="0" width="22" height="22" align="middle">{$lang.edit}</a>
            <a href="entry.php?dn={$dn|escape:url}&mode=copy"><img src="pix/copy.png" border="0" width="22" height="22" align="middle">{$lang.copy}</a>
            <a href="entry.php?dn={$dn|escape:url}&del=1" onClick="return confirm('{$lang.msg_reallydel}');"><img src="pix/delete.png" border="0" width="22" height="22" align="middle">{$lang.delete}</a>
          {/if}
        {/if}
          <a href="entry.php?mode=edit"><img src="pix/new.png" border="0" width="22" height="22" align="middle">{$lang.new}</a>
      {/if}
      </td>
    </tr></table>
  </td>
</tr>
<tr>
  <td class="footrow">
    {if $user == ''}
      <a href="login.php"><img src="pix/login.png" border="0" width="22" height="22" align="middle"> {$lang.login}</a>&nbsp;&nbsp;&nbsp;
      {$lang.notloggedin}
    {else}
      <a href="login.php?username="><img src="pix/logout.png" border="0" width="22" height="22" align="middle"> {$lang.logout}</a>&nbsp;&nbsp;&nbsp;
      {$lang.loggedinas} <b>{$user}</b>
    {/if}
  </td>
</tr>
</table>
