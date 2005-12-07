<table width="100%">
{foreach from=$orgs item=org}
  <tr>
    <td class="result" width="25">
      <img src="pix/org.png" border="0" width="22" height="22" align="middle">
    </td>
    <td class="result">
      <a href="index.php?org={$org|escape:url}">{$org}</a><br>
    </td>
  </tr>
{foreachelse}
  <tr>
    <td align="center">
      <br /><br />{$lang.err_noentries}<br /><br />
    </td>
  </tr>
{/foreach}
</table>
