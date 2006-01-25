  </div>

  <div id="footrow">
    {if $user == ''}
      {$lang.notloggedin}
      &nbsp;&nbsp;&nbsp;
      <a href="login.php"><img src="pix/key.png" border="0" width="16" height="16" align="middle" /> {$lang.login}</a>
    {else}
      {$lang.loggedinas} <b>{$user}</b>
      &nbsp;&nbsp;&nbsp;
      <a href="login.php?username="><img src="pix/key_go.png" border="0" width="16" height="16" align="middle" /> {$lang.logout}</a>
    {/if}
  </div>
</div>

</body>
</html>


<!--

  </td>
</tr>

<tr>
  <td class="buttonrow" align="right" colspan="2">
    <table width="100%"><tr>
      <td align="left">
        <a href="orgs.php"><img src="pix/book_open.png" border="0" width="16" height="16" align="middle" />{$lang.orgs}</a>
      </td>
      <td align="right">
      {if $dn}
        <a href="entry.php?dn={$dn|escape:url}&mode=vcf"><img src="pix/vcard.png" border="0" width="16" height="16" align="middle" />{$lang.vcfexport}</a>
      {/if}
      {if $list}
        <a href="index.php?filter={$filter|escape:url}&marker={$marker|escape:url}&search={$search|escape:url}&export=csv"><img src="pix/page_white_excel.png" border="0" width="16" height="16" align="middle">{$lang.csvexport}</a>
      {/if}
      {if $user}
          <a href="import.php"><img src="pix/page_in.png" border="0" width="16" height="16" align="middle">{$lang.vcfimport}</a> 
        {if $dn}
          {if $smarty.request.mode == 'edit'}
            <a href="entry.php?dn={$dn|escape:url}"><img src="pix/page_red.png" border="0" width="16" height="16" align="middle">{$lang.show}</a>
          {elseif $smarty.request.mode != 'copy'}
            <a href="entry.php?dn={$dn|escape:url}&mode=edit"><img src="pix/page_edit.png" border="0" width="16" height="16" align="middle">{$lang.edit}</a>
            <a href="entry.php?dn={$dn|escape:url}&mode=copy"><img src="pix/page_copy.png" border="0" width="16" height="16" align="middle">{$lang.copy}</a>
            <a href="entry.php?dn={$dn|escape:url}&del=1" onClick="return confirm('{$lang.msg_reallydel}');"><img src="pix/page_delete.png" border="0" width="16" height="16" align="middle">{$lang.delete}</a>
          {/if}
        {/if}
          <a href="entry.php?mode=edit"><img src="pix/page_add.png" border="0" width="16" height="16" align="middle">{$lang.new}</a>
      {/if}
      </td>
    </tr></table>
  </td>
</tr>
<tr>
  <td class="footrow" colspan="2">
    {if $user == ''}
      <a href="login.php"><img src="pix/key.png" border="0" width="16" height="16" align="middle" /> {$lang.login}</a>&nbsp;&nbsp;&nbsp;
      {$lang.notloggedin}
    {else}
      <a href="login.php?username="><img src="pix/key_go.png" border="0" width="16" height="16" align="middle" /> {$lang.logout}</a>&nbsp;&nbsp;&nbsp;
      {$lang.loggedinas} <b>{$user}</b>
    {/if}
  </td>
</tr>
</table>
-->
