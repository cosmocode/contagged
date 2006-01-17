<tr>
  <td class="result" width="25">
    <img src="pix/{$entry.type}.png" border="0" width="16" height="16" align="middle" title="{$entry.type}">
  </td>
  <td class="result">
    <b><a href="entry.php?dn={$entry.dn|escape:url}">{$entry.name}, {$entry.givenname}</a></b>
  </td>
  <td class="result">
    <a href="index.php?org={$entry.organization|escape:url}">{$entry.organization}</a>&nbsp;
  </td>
  <td class="result">
    {$entry.phone}&nbsp;
  </td>
  <td class="result">
    <a href="mailto:{$entry.mail[0]}">{$entry.mail[0]}</a>&nbsp;
  </td>
  <td class="result" width="16">
    {if $entry.photo}
      <a href="img.php?dn={$entry.dn|escape:url}" target="_blank" title="{$lang.photo}"><img src="pix/image.png" border="0" width="16" height="16" align="middle" title="{$lang.photo}"></a>
    {else}
      &nbsp;
    {/if}
  </td>
</tr>

